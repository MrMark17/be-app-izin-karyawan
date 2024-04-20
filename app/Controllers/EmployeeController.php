<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class EmployeeController extends BaseController  
{
    use ResponseTrait;
    public function index()
    {
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();

        return $this->respondCreated($users->findAll());
    }
    public function detail($id)
    {
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();

        return $this->respondCreated($users->find($id));
    }

    public function update($id)
    {

        // get json data from client (username, new password, confirm_password, email, role)
        $validation = \Config\Services::validation();
        // validate json data with custom rules
        $requestData = $this->request->getJSON(true);
        if (!$validation->run($requestData, 'profile')) {
            return $this->failValidationErrors($validation->getErrors());
        }
        $users = auth()->getProvider();
        $userObject = $users->findById($id);

        if(!$userObject) {
            return $this->respondCreated([
                "status" => "false",
                "message" => "user not found",
            ]);
        }
        // match with database, if match return error
        if (!array_key_exists('new_password', $requestData) || !$requestData['new_password']) {
            $userObject->fill([
                'username' => $requestData['username'],
                'email'    => $requestData['email'],
                'role'     => $requestData['role'],
            ]);
        } else {
            $validation->setRules([
                'new_password' => [
                    'label' => 'new password',
                    'rules' => [
                        'max_byte[72]',
                        'strong_password[]',
                    ],
                    'errors' => [
                        'max_byte' => 'Auth.errorPasswordTooLongBytes'
                    ]
                ],
                'password_confirm' => [
                    'label' => 'password confirm',
                    'rules' => ['matches[new_password]']
                ],
            ]);

            if (! $validation->run($requestData)) {
                return $this->failValidationErrors($validation->getErrors());
            }
            
            $userObject->fill([
                'username' => $requestData['username'],
                'email'    => $requestData['email'],
                'password' => $requestData['new_password'],
                'role'     => $requestData['role'],
            ]);
        }

        // if doesn't match, update database and update groups and return success
        if( !($requestData['role'] !== $userObject->role) ) {
            $userObject->addGroup($requestData['role']);
            $userObject->removeGroup($userObject->role);
        }
        switch($requestData['role']) {
            case 'admin':
                $userObject->addGroup('admin');
                $userObject->removeGroup('employee');
                break;
            case 'employee':
                $userObject->addGroup('employee');
                $userObject->removeGroup('admin');
                break;
        }
        $users->save($userObject);

        $response = [
            'status' => true,
            'message' => 'Update success',
            'data' => $userObject
        ];
        return $this->respondCreated($response, 200);
    }
}

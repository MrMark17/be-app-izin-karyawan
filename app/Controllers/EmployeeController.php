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
        // match with database, if match return error
        if (!array_key_exists('new_password', $requestData)) {
            // $userObject->fill([
            //     'username' => $requestData['username'],
            //     'email'    => $requestData['email'],
            //     'role'     => $requestData['role'],
            // ]);
            return $this->respondCreated('new password not found', 200);
        } else {
            // $userObject->fill([
            //     'username' => $requestData['username'],
            //     'email'    => $requestData['email'],
            //     'password' => $requestData['new_password'],
            //     'role'     => $requestData['role'],
            // ]);
            return $this->respondCreated('password found', 200);
        }

        // if doesn't match, update database and update groups and return success
        // $users->save($userObject);
        // return $this->respondCreated('Update success', 200);
    }
}

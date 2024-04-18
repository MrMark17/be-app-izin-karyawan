<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Shield\Entities\User;

class RegisterController extends BaseController
{
    use ResponseTrait;


    public function index()
    {
        $validation = \Config\Services::validation();
        $requestData = $this->request->getJSON(true);

        if (!$validation->run($requestData, 'register')) {
            return $this->failValidationErrors($validation->getErrors());
        }

        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();

        $user = new User([
            'username' => $requestData['username'],
            'email'    => $requestData['email'],
            'password' => $requestData['password'],
        ]);
        $users->save($user);

        // To get the complete user object with ID, we need to get from the database
        $user = $users->findById($users->getInsertID());

        // Add to default group
        $users->addToDefaultGroup($user);

        return $this->respondCreated([
            "status" => "success",
            "message" => "Registration successful!",
        ], 200);
    }
}

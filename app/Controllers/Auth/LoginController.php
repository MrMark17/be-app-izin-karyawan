<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Models\UserModel;

class LoginController extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $validation = \Config\Services::validation();
        $requestData = $this->request->getJSON(true);

        if (!$validation->run($requestData, 'login')) {
            return $this->failValidationErrors($validation->getErrors());
        }

        $credentials = [
            'email'    => $requestData['email'],
            'password' => $requestData['password']
        ];

        $loginAttempt = auth()->attempt($credentials);

        if (!$loginAttempt->isOK()) {
            $response = [
                'status' => false,
                'message' => $loginAttempt->reason(),
                'data' => []
            ];
            return $this->respond($response);
        } else {
            $userObject = new UserModel();
            $userData = $userObject->findById(auth()->id());
            $token = $userData->generateAccessToken('thisissecretkey');
            $auth_token = $token->raw_token;

            $response = [
                "status" => true,
                "message" => "User logged in successfully",
                "data" => [
                    "token" => $auth_token
                ]
            ];
        }
        return $this->respondCreated($response);
    }
}

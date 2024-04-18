<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;


class LogoutController extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $users = auth()->getProvider();

        $user = auth()->user();
        $user->revokeAllAccessTokens();
        $users->save($user);
        auth()->logout();

        return $this->respond([
            'status' => 'true',
            'message' => 'Logout success',
        ], 200);
    }
}

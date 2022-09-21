<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class LogoutController extends BaseController
{

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $success['message'] = 'logout' ;

        return $this->sendResponse($success, 'User logout successfully.');
    }
}

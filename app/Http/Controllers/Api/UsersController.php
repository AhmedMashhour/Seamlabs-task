<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UsersController extends BaseController
{

    public function getUserByID(Request $request)
    {
        /**
         * Validate parameters
         */
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ]);
        if ($validate->fails()) {
            return $this->sendError('Validation Error.',  $validate->errors(),400);
        }
        $user = User::find($request->user_id);
        if ($user) {
            $success['user'] = $user;
            return $this->sendResponse($success, '');
        } else {
            return $this->sendError('User Not Found',  'User ID  Not Found In The Database !',404);
        }
    }

    public function getAllUsers()
    {
        $users = User::all();
        $success['users'] = $users;
        return $this->sendResponse($success, 'Get All Users In Database');
    }

    public function updateUserData(Request $request)
    {
        /**
         * Validate parameters
         */

        $user = $request->user();
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:users,name,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|numeric|digits:11|regex:/(01)[0-9]{9}/|unique:users,phone,' . $user->id,
            'date_of_birth' => 'required|date',
            'password' => 'required|confirmed',
        ]);
        if ($validate->fails()) {
            return $this->sendError('Validation Error.',  $validate->errors(),400);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->date_of_birth = $request->date_of_birth;
        $user->password = $request->password;
        $user->save();
        $success['user'] = $user;
        return $this->sendResponse($success, 'User Updated successfully .');
    }

    public function deleteUserById(Request $request)
    {
        /**
         * Validate parameters
         */
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ]);
        if ($validate->fails()) {
            return $this->sendError('Validation Error.',  $validate->errors(),400);
        }
        $user = User::find($request->user_id);
        if ($user) {
            $user->delete();
            return $this->sendResponse([], 'User Deleted successfully .');
        } else {
            return $this->sendError('Not Found',  "",404);
        }
    }
}

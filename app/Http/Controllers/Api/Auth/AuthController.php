<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class AuthController extends BaseController
{

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Check if email is validated
            if ($user->email_verified_at == NULL) {

                return $this->sendError('Access unauthorized.', ['verification' => ['Please verify email. Click on the button to resend the activation mail.']], 403);
            }

            $success['token'] =  $user->createToken('token')->plainTextToken;
            $success['name'] =  $user->name;
            $success['roles'] = $user->getRoleNames();



            return $this->sendResponse($success, 'User signed in');
        } else {
            return $this->sendError('Access unauthorized.', ['authentication' => 'Email or Password wrong'], 403);
        }
    }

    public function register(Request $request)
    {

        // Delete Emails from Demo Account if reached 20
        if (User::all()->count() > 20) {
            Artisan::call('migrate:fresh');
        }
        $request->validate([
            'name' => 'required|unique:users,name|min:3|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:40',
            'confirm_password' => 'required|same:password',

        ]);



        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $user->assignRole('user');

        $user->sendApiEmailVerificationNotification();

        $success['token'] = $user->createToken('token')->plainTextToken;
        $success['name'] =  $user->name;
        $success['roles'] = $user->getRoleNames();

        return $this->sendResponse($success, 'We have send you an email. Please check your account: ' . $user->email);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        $success['token'] = [];
        return $this->sendResponse($success, 'Token deleted');
    }

    public function getUser(Request $request)
    {

        return $request->user();
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // register user create auth token
    public function register(Request $request){
        $fields = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed']
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;
        
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        // verify email in db
        $user = User::where('email', $fields['email'])->first();

        // verify password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            // return 401 unauthorised error
            // either email is not found or password hash doesn't match
            return response(['message' => 'Bad Credentials'], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;
        
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response);
    }

    public function logout(Request $request){
        // delete user auth token to logout

        // https://stackoverflow.com/a/73092953/6001253
        // the comment below just to ignore intelephense(1013) annoying error.
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $user->tokens()->delete();
        
        return ['message' => 'logged out'];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateLogin;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    { 
        $credentials = [
            'email' => $request->email,
            'password'  => $request->password
        ];
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $user['token'] =  $user->createToken('dossims')->plainTextToken;
            return $user;
        }
        else{
            return 'test';
        }
    }
}

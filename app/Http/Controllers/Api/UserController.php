<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:15',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:15|confirmed',
        ]);


        $data = $request->all();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return response()->json([
            'status' => 1,
            'msg' => 'successful registration',
        ]);

    }

    public function login(Request $request)
    {

    }

    public function userProfile()
    {

    }

    public function logout()
    {

    }
}

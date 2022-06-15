<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|max:15',
        ]);

        if(Auth::attempt($request->all())){

            $user = User::where('email',$request->email)->first();
            $user->tokens()->delete();
            $token = $user->createToken('token');

            $data = [
                'status' => 1,
                'msg' => 'successful login',
                'token' =>$token->plainTextToken,
            ];
        
        }
        else{
            $data = [
                'status' => 0, 
                'msg' => 'Incorrect credentials',
            ];    
        }

        return response()->json($data);

    }

    public function userProfile()
    {
        $user = Auth::user();
        return response()->json([
            'status' => 1,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'status' => 1,
            'msg' => 'Bye, see you later :)',
        ]);
    }
}

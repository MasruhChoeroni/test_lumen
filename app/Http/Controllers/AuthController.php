<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request){
        $name       = $request->input('name');
        $email      = $request->input('email');
        $password   = Hash::make($request->input('password'));

        $register   = User::create([
            'name'      => $name,
            'email'    => $email,
            'password' => $password
        ]);

        if ($register){
            return response()->json([
                'status'    => true,
                'message'   => 'Register success.',
                'data'      => $register
            ], 201);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Register failed.',
                'data'      => ''
            ], 400);
        }
    }

    public function login(Request $request){
        $email      = $request->input('email');
        $password   = $request->input('password');

        $user       = User::where('email', $email)->first();

        if ($user) {
            if (Hash::check($password, $user->password)){
                $api_token      = base64_encode(str_random(40));

                $user->update([
                    'api_token' => $api_token
                ]);

                return response()->json([
                    'status'    => true,
                    'message'   => 'Login success.',
                    'data'      => [
                        'user'      => $user,
                        'api_token' => $api_token
                    ]
                ], 201);
            } else {
               return response()->json([
                    'status'    => false,
                    'message'   => 'Password is wrong.',
                    'data'      => ''
                ], 404); 
            }
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Email not found.',
                'data'      => ''
            ], 404);
        }
    }

    public function check(Request $request){
        $user       = User::all();
        $userInfo   = Auth::user();

        if ($user) {
            return response()->json([
                'status'    => true,
                'message'   => 'Data found.',
                'data'      => $user,
                'user_info' => $userInfo
            ], 404); 
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Data not found.',
                'data'      => '',
                'user_info' => $userInfo
            ], 404);
        }
    }
}

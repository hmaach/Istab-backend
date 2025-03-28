<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function user()
    {
         $user = Auth::user();
         return response([
             'user'=>$user
         ]);
    }

    public function register(Request $request)
    {
        User::create([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'email' => $request->input('email'),
            'tel' => $request->input('tel'),
            'sex' => $request->input('password'),
            'role' => $request->input('role'),
            'password' => Hash::make($request->input('password'))
        ]);
    }


    public function createUser()
    {
        User::create([
            'nom' => 'maach',
            'prenom' => 'Issam',
            'email' => 'a@a.com',
            'tel' => '5672892',
            'sex' => 'M',
            'role' => 'admin',
            'password' => Hash::make('aaaa')
        ]);
        return 'You just created a user with : \r\n email : a@a.com \r\n password : aaaa';
    }


    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'invalid login'
            ], Response::HTTP_UNAUTHORIZED);
        }
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = Auth::user();
        $user->pdfCategories;
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24);

        return response([
            'user' => $user,
            'token' => $token,
        ])->withCookie($cookie);
    }


    public function logout(){

        $cookie = Cookie::forget('jwt');
        return \response([
            'message' => 'success'
        ])->withCookie($cookie);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required'
            ],[
                'name.required' => 'Nama Wajib Diisi',
                'email.required' => 'Email Wajib Diisi',
                'email.unique' => 'Email Tidak Boleh Sama',
                'password.required' => 'Password Wajib Diisi'
            ]
            );
        
        if($validatedData->fails()){
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi Gagal',
                'errors' => $validatedData->errors(),
            ]); 
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'data' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $validatedData = Validator::make($request->all(),
            [
                'email' => 'required',
                'password' => 'required'
            ],[
                'email.required' => 'Email Wajib Diisi',
                'password.required' => 'Password Wajib Diisi'
            ]
            );

        if($validatedData->fails()){
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi Salah',
                'errors' => $validatedData->errors()
            ]);
        }

        if(!Auth::attempt(
            $request->only('email', 'password')
        )){
            return response()->json([
                'pesan' => 'Tidak Terdafar'
            ], 401);
        }
        $user = User::where('email', $request->email)->firstorfail();

        $token = $user->createToken('api-auth')->plainTextToken;

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil Login',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {   
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'pesan' => 'See u later'
        ]);
    }
}

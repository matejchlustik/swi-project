<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string'
        ]);

        $user = User::create([
            'last_name' => $fields['last_name'],
            'first_name' => $fields['first_name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'role_id' => Role::firstWhere("role", "Študent")->id
        ]);

        $token = $user->createToken("testtoken")->plainTextToken;

        return response(
            [
                'user' => $user,
                'token' => $token
            ],
            201
        );
    }
}
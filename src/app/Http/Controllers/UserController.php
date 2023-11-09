<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CompanyEmployee;
use App\Models\DepartmentEmployee;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    
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
            'role_id' => Role::firstWhere("role", "Admin")->id
        ]);

        $token = $user->createToken("adminToken, ['create-department-head,create-department-employee,create-company-representative,create-student']")->plainTextToken;

        return response(
            [
                'user' => $user,
                'token' => $token
            ],
            201
        );
    }

    public function store(Request $request) {

        $fields = $request->validate([
            'email' => 'required|string|unique:users,email',
            'role_id' => 'required',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        $fields['department_id'] = $request->department_id;
        $fields['company_id'] = $request->company_id;
        $fields['phone'] = $request->phone;
     
        if (($fields['company_id'] !== null && $fields['department_id'] !== null)) {
            return response('can\'t send company_id and department_id together', 400);
        }

        function createUser($fields) {
            return User::create([
                'last_name' => $fields['last_name'],
                'first_name' => $fields['first_name'],
                'email' => $fields['email'],
                'password' => Hash::make('123'),
                'role_id' => $fields['role_id'],
            ]);
        }

        switch ($fields['role_id']) {
            case 2:
                if($fields['department_id']) {
                    if (auth()->user()->tokenCan('create-department-head')) {
                        
                        $newUser = createUser($fields);

                        DepartmentEmployee::create([
                            'user_id' => $newUser->id,
                            'department_id' => $fields['department_id']
                        ]);

                        return response('User created', 201);
                    } else return response('Unauthorized', 401);
                } else return response("Missing department_id", 400);
                break;

            case 3:
                if($fields['department_id'] !== null) {
                    if (auth()->user()->tokenCan('create-department-head')) {
                        
                        $newUser = createUser($fields);

                        DepartmentEmployee::create([
                            'user_id' => $newUser->id,
                            'department_id' => $fields['department_id']
                        ]);

                        return response('User created', 201);
                    } else return response('Unauthorized', 401);
                } else return response("Missing department_id", 400);
                break;

            case 4:
                if($fields['company_id'] !== null && $fields['phone'] !== null) {
                    if (auth()->user()->tokenCan('create-company-representative')) {
                        
                        $newUser = createUser($fields);

                        CompanyEmployee::create([
                            'user_id' => $newUser->id,
                            'company_id' => $fields['company_id'],
                            'phone' => $fields['phone']
                        ]);

                        return response('User created', 201);
                    } else return response('Unauthorized', 401);
                } else return response("Missing company_id or phone", 400);
                break;

            case 5:
                if (auth()->user()->tokenCan('create-student')) {
                    
                    $newUser = createUser($fields);

                    return response('User created', 201);
                } else return response('Unauthorized', 401);
                break;

            default:
                return response('Wrong role_id', 401);
        }

        
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\Test;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CompanyEmployee;
use App\Models\DepartmentEmployee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

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
            'role_id' => Role::firstWhere("role", "Študent")->id
        ]);

        event(new Registered($user));

        $token = $user->createToken(
            "studentToken",
            [

            ]
        )->plainTextToken;

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
                if ($fields['department_id']) {
                    if (auth()->user()->tokenCan('create-department-head')) {

                        $newUser = createUser($fields);

                        DepartmentEmployee::create([
                            'user_id' => $newUser->id,
                            'department_id' => $fields['department_id']
                        ]);
                        Password::sendResetLink(
                            [
                                'email' => $fields['email']
                            ]
                        );
                        $newUser->markEmailAsVerified();
                        return response('User created', 201);
                    } else return response('Forbidden', 403);
                } else return response("Missing department_id", 400);
                break;
            case 3:
                if ($fields['department_id'] !== null) {
                    if (auth()->user()->tokenCan('create-department-employee')) {

                        $newUser = createUser($fields);

                        DepartmentEmployee::create([
                            'user_id' => $newUser->id,
                            'department_id' => $fields['department_id']
                        ]);
                        Password::sendResetLink(
                            [
                                'email' => $fields['email']
                            ]
                        );
                        $newUser->markEmailAsVerified();
                        return response('User created', 201);
                    } else return response('Forbidden', 403);
                } else return response("Missing department_id", 400);
                break;
            case 4:
                if ($fields['company_id'] !== null && $fields['phone'] !== null) {
                    if (auth()->user()->tokenCan('create-company-representative')) {

                        $newUser = createUser($fields);

                        CompanyEmployee::create([
                            'user_id' => $newUser->id,
                            'company_id' => $fields['company_id'],
                            'phone' => $fields['phone']
                        ]);
                        Password::sendResetLink(
                            [
                                'email' => $fields['email']
                            ]
                        );
                        $newUser->markEmailAsVerified();
                        return response('User created', 201);
                    } else return response('Forbidden', 403);
                } else return response("Missing company_id or phone", 400);
                break;
            case 5:
                if (auth()->user()->tokenCan('create-student')) {

                    $newUser = createUser($fields);
                    Password::sendResetLink(
                        [
                            'email' => $fields['email']
                        ]
                    );
                    $newUser->markEmailAsVerified();
                    return response('User created', 201);
                } else return response('Forbidden', 403);
                break;
            default:
                return response('Wrong role_id', 401);
        }
    }

    public function login(Request $request) {

        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Incorrect credentials'
            ], 401);
        }
        switch ($user->role->id) {
            case 1:
                $token = $user->createToken(
                    "adminToken",
                    [
                        "create-department-head",
                        "create-department-employee",
                        "create-company-representative",
                        "create-student",
                        "manage-practices",
                        "read-practices",
                        "manage-practice-offers",
                        "manage-company-department",
                        "manage-company",
                        "edit-company",
                        "manage-comments",
                        "filter-practices",
                        "manage-feedback",
                        "read-feedback",
                        "manage-workplaces",
                        "read-workplaces",
                        "manage-evaluation",
                    ]
                )->plainTextToken;
                break;
            case 2:
                $token = $user->createToken(
                    "departmentHeadToken",
                    [
                        "create-department-employee",
                        "create-company-representative",
                        "create-student",
                        "manage-practices",
                        "read-practices",
                        "manage-practice-offers",
                        "manage-company-department",
                        "manage-company",
                        "edit-company",
                        "manage-comments",
                        "filter-practices",
                        "manage-feedback",
                        "read-feedback",
                        "manage-workplaces",
                        "read-workplaces",
                        "manage-evaluation",
                    ]
                )->plainTextToken;
                break;
            case 3:
                $token = $user->createToken(
                    "departmentEmployeeToken",
                    [
                        "create-company-representative",
                        "create-student",
                        "manage-practices",
                        "read-practices",
                        "manage-practice-offers",
                        "manage-company-department",
                        "manage-company",
                        "edit-company",
                        "manage-comments",
                        "filter-practices",
                        "manage-feedback",
                        "read-feedback",
                        "manage-faculties",
                        "manage-workplaces",
                        "read-workplaces",
                        "manage-evaluation",
                    ]
                )->plainTextToken;
                break;
            case 4:
                $token = $user->createToken(
                    "companyRepresentativeToken",
                    [
                        "read-practices",
                        "manage-practice-offers",
                        "edit-company",
                        "manage-feedback",
                        "read-feedback",
                        "read-workplaces",
                    ]
                )->plainTextToken;
                break;
            case 5:
                $token = $user->createToken(
                    "studentToken",[
                        "manage-practices",
                        "read-practices",
                        "manage-comments",
                        "manage-feedback",
                        "read-feedback",
                        "read-workplaces",
                    ]
                )->plainTextToken;
                break;
            default:
                return response('Wrong role_id', 401);
        }

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function forgotPassword(Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? response(['status' => __($status)])
                    : response(['email' => __($status)]);
    }

    public function resetToken(string $token) {
        return response(['token' => $token]);
    }

    public function passwordReset(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? response(['status' => __($status)])
                    : response(['email' => __($status)]);
    }

    public function changePassword(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'new_password' => 'required|confirmed|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Incorrect credentials'
            ], 401);
        }

        $user->forceFill([
            'password' => Hash::make($fields['new_password'])
        ]);

        $user->save();

        event(new PasswordReset($user));

        return response("Password changed");

    }
}

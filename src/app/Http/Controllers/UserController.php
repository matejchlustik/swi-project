<?php

namespace App\Http\Controllers;

use App\Mail\Test;
use App\Models\Department;
use App\Models\Practice;
use App\Models\PracticeOffer;
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

class UserController extends Controller
{
    public function index()
    {
            $users = User::paginate(20);

        return response([
            'items' => $users->items(),
            'prev_page_url' => $users->previousPageUrl(),
            'next_page_url' => $users->nextPageUrl(),
            'last_page' => $users->lastPage(),
            'total' => $users->total()
        ]);
    }
    public function indexDeleted()
    {
        $users = User::onlyTrashed()->paginate(20);

        return response([
            'items' => $users->items(),
            'prev_page_url' => $users->previousPageUrl(),
            'next_page_url' => $users->nextPageUrl(),
            'last_page' => $users->lastPage(),
            'total' => $users->total()
        ]);
    }
    public function register(Request $request)
    {
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
            'role_id' => Role::firstWhere("role", "Študent")->id,
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

    public function store(Request $request)
    {

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

        function createUser($fields)
        {
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
                if ($fields['phone'] == null) {
                    if ($fields['department_id'] !== null) {
                        if (auth()->user()->tokenCan('create-department-employee')) {

                            $newUser = createUser($fields);

                            DepartmentEmployee::create([
                                'user_id' => $newUser->id,
                                'department_id' => $fields['department_id'],
                                'from'=>now()
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
                } else return response("Do not send field phone when creating Vedúci pracoviska or Poverený pracovník pracoviska", 400);
                break;
            case 3:
                if ($fields['phone'] == null) {
                    if ($fields['department_id'] !== null) {
                        if (auth()->user()->tokenCan('create-department-employee')) {

                            $newUser = createUser($fields);

                            DepartmentEmployee::create([
                                'user_id' => $newUser->id,
                                'department_id' => $fields['department_id'],
                                'from'=>now()
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
                } else return response("Do not send field phone when creating department-head or department-employee", 400);
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

    public function login(Request $request)
    {

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
                            "manage-comments",
                            "filter-practices",
                            "manage-workplaces",
                            "read-workplaces",
                            "manage-evaluation",
                            "manage-company",
                            "manage-users",
                            "admin-deleted-data"
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
                            "manage-comments",
                            "filter-practices",
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
                            "manage-comments",
                            "filter-practices",
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
                            "read-workplaces",
                        ]
                    )->plainTextToken;
                    break;
                case 5:
                    $token = $user->createToken(
                        "studentToken", [
                            "manage-practices",
                            "read-practices",
                            "manage-comments",
                            "read-workplaces",
                            "read-evaluation",
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

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function forgotPassword(Request $request)
    {

            $request->validate(['email' => 'required|email']);


            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? response(['status' => __($status)])
                : response(['email' => __($status)]);

    }

    public function resetToken(string $token)
    {
        return response(['token' => $token]);
    }

    public function passwordReset(Request $request)
    {
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

    public function changePassword(Request $request)
    {
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

    public function showByDepartment(Department $department)
    {
        $users = Department::find($department->id)->users()->paginate(10);

        return response([
            'items' => $users->items(),
            'prev_page_url' =>$users->previousPageUrl(),
            'next_page_url' => $users->nextPageUrl(),
            'last_page' =>$users->lastPage(),
            'total' => $users->total()
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        $user->tokens()->delete();      //toto by som asi nemal vymazavat alebo pri activate by som mal spravit token znova
        $user->save();
        return response()->json(['message' => 'Úspešne zmazaný']);
    }
    public function restore(User $user){
        $user->restore();
        return response()->json(['message' => 'Úspešne obnovený záznam']);
    }

    public function forceDelete(User $user)
    {
                if($user->id === auth()->user()->id) {
                    return response("Cant delete yourself");
                }
                $user->forceDelete();
                return response()->json([
                    'message' => 'úspešne odstránený záznam',
                ]);
    }

    public function show(User $user)
    {
        if (auth()->user()->role->role !== "Admin" && $user->trashed()) {
            return response("User thrashed", 403);
        }

        return response()->json($user);
    }

    public function showByRole(Role $role)
    {
        $users=$role->users()->paginate(10);
        return response([
            'items' => $users->items(),
            'prev_page_url' =>$users->previousPageUrl(),
            'next_page_url' => $users->nextPageUrl(),
            'last_page' =>$users->lastPage(),
            'total' => $users->total()
        ]);
    }


    public function update(Request $request, User $user)
    {
        $fields = $request->all();

        $userRole = $user->role->role;

        if (auth()->user()->role->role=="Admin") {
            $validate = $request->validate([
                'first_name' => 'string',
                'last_name' => 'string',
                'email' => 'email|unique:users,email',
                'phone' => 'string',
                'company_id' => 'integer|exists:companies,id',
                'department_id' => 'integer|exists:departments,id'
            ]);
            if ((isset($fields['role_id'])==2 && $user->role_id==3) || ($user->role_id==2 && isset($fields['role_id'])==3)) {
                $this->updateUser($user, $validate, $userRole);
                return response($user);
            }else{
                if (isset($fields['role_id'])){
                    return response('Cannot change role', 400);
                }

                $this->updateUser($user,$validate,$userRole);
                return response($user);
            }
        }

        if (auth()->user()->id === $user->id) {
            if (isset($fields['role_id'])){
                return response('Cannot change role', 400);
            }
            else {
                $validate = $request->validate([
                    'first_name' => 'string',
                    'last_name' => 'string',
                    'email' => 'email',
                    'phone'=>'string'
                ]);
                return response($this->updateUser($user, $validate, $userRole));
            }
        } else {
            return response("Forbidden", 403);
        }
    }

    private function updateUser($user,$fields,$userRole)
    {
        $user->fill($fields);
        $user->save();
        if ((isset($fields['company_id']) || isset($fields['phone'])) && $userRole === "Zástupca firmy") {
            $companyEmployee = CompanyEmployee::where('user_id',$user->id)->first();
            $companyEmployee->fill($fields);
            $companyEmployee->save();
            return $user->load(["companyEmployee"]);
        }
        if($userRole==="Poverený pracovník pracoviska" || $userRole==="Vedúci pracoviska"){
            $departmentEmployee=DepartmentEmployee::where('user_id',$user->id)->where('to',null)->first();
            if(isset($fields["department_id"])){
                $departmentEmployee->to=now();
                $departmentEmployee->save();
                $newDepartmentEmployee=DepartmentEmployee::create([
                    'user_id'=>$user->id,
                    'department_id'=>$fields['department_id'],
                    'from'=>now()
                ]);

            }
            return $user->load(["departmentEmployee"]);
        }
        return $user;
    }


}

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
            'deactivate' => 0
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
                'deactivate' => 0
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
                        $newUser->markEmailAsVerified();
                        return response('User created', 201);
                    } else return response('Forbidden', 403);
                } else return response("Missing company_id or phone", 400);
                break;
            case 5:
                if (auth()->user()->tokenCan('create-student')) {

                    $newUser = createUser($fields);
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
        if (!$this->deactivated($user)) {

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
                            "read-company",
                            "manage-company",
                            "edit-company",
                            "manage-company",
                            "manage-wo-admin",
                            "manage-users"
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
                            "read-company",
                            "manage-company",
                            "edit-company",
                            "manage-wo-admin"
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
                            "read-company",
                            "manage-company",
                            "edit-company",
                            "manage-wo-admin"
                        ]
                    )->plainTextToken;
                    break;
                case 4:
                    $token = $user->createToken(
                        "companyRepresentativeToken",
                        [
                            "read-practices",
                            "manage-practice-offers",
                            "read-company",
                            "edit-company",
                            "manage-wo-admin-wo-dephead"
                        ]
                    )->plainTextToken;
                    break;
                case 5:
                    $token = $user->createToken(
                        "studentToken", [
                            "manage-practices",
                            "read-practices",
                            "read-company",
                           "manage-wo-admin-wo-dephead"
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
        else{return response("Your account is deactivated");}
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
        if (!$this->deactivated()) {

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? response(['status' => __($status)])
                : response(['email' => __($status)]);
        }
        else{return response("Your account is deactivated");}
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

        // Kontrola, či používateľ nie je admin, ak nie je, získa iba nezmazaných používateľov
        if (auth()->user()->role->role !== "Admin") {
            $users->whereNull('deleted_at');
        }

        return response($users);
    }

    public function deactivate(User $user)
    {
        $user->delete();
        $user->tokens()->delete();      //toto by som asi nemal vymazavat alebo pri activate by som mal spravit token znova
        $user->save();
        return response()->json(['message' => 'Úspešne deaktivovaný']);
    }
    public function activate(User $user){
        $user->restore();
        return response()->json(['message' => 'Úspešne reaktovovaný']);
    }

    public function destroy(User $user)
    {
        $practices = Practice::where('user_id', $user->id)->get();
        if ($practices->isEmpty()) {
            $departmentEmployees = DepartmentEmployee::where('user_id', $user->id)->get();
            $companyEmployees = CompanyEmployee::where('user_id', $user->id)->get();
            if ($departmentEmployees->isEmpty() && $companyEmployees->isEmpty()) {
                $user->forceDelete();

                return response()->json([
                    'message' => 'Používateľ bol úspešne odstránený.',
                ]);
            } else {
                return response()->json([
                    'message' => 'Používateľ nie je možné odstrániť, pretože je priradený k pracoviskám alebo spoločnostiam.',
                ], 422);
            }
        } else {
            return response()->json([
                'message' => 'Používateľ nie je možné odstrániť, pretože je priradený k praxiam.',
            ], 422);
        }
    }

    public function show(User $user)
    {
        // Kontrola, či používateľ nie je admin a záznam nie je soft deleted
        if (auth()->user()->role->role !== "Admin" && $user->trashed()) {
            return response("User thrashed", 403);
        }

        return response()->json($user);
    }

    public function showByRole(Request $request)
    {
        $roles = $request->query('role_id');

        $users = User::query();

        if ($roles) {
            $users->whereHas('role_id', function ($query) use ($roles) {
                $query->whereIn('role.id', $roles);
            });
        }

        // Kontrola, či používateľ nie je admin, ak nie je, získa iba nezmazaných používateľov
        if (auth()->user()->role->role !== "Admin") {
            $users->whereNull('deleted_at');
        }

        $result = $users->get();

        return response()->json($result);
    }

    public function deactivated(User $user)
    {
        return $user->trashed() ? 'true' : 'false';
    }

    public function update(Request $request, User $user)
    {
        if (isset($fields['role_id'])) {
            return response('Cannot change role', 400);
        }
        $fields = $request->all();
        $userRole = $user->role->role;
        if (auth()->user()->id === $user->id) {
            $validate = $request->validate([
                'first_name' => 'string',
                'last_name' => 'string',
                'email' => 'email',
            ]);
            if ($validate['email']!==null) {
                //verification
            }
            $user->fill($validate);
            $user->save();

            return response('User updated');
        }

        if (auth()->user()->tokenCan("manage-users")) {
            $this->updateUser($user, $fields, $userRole);
            return response('User updated');
        }
        if (auth()->user()->tokenCan("manage-wo-admin")) {
            if ($userRole === "Admin" || $userRole === "Vedúci pracoviska") {return response("You can't update admin or department head ",403);}

                if ($userRole === "Poverený pracovník pracoviska"){
                    if ($user->department->id === auth()->user()->department->id){
                        $this->updateUser($user, $fields, $userRole);
                        return response('User updated');
                    } else {return response("You can't update someone out of your department ",403);}
                }
            else {$this->updateUser($user, $fields, $userRole);return response('User updated');}
        }
        if (auth()->user()->tokenCan("manage-wo-admin-wo-dephead")) {

            if ($userRole === "Admin" || $userRole === "Vedúci pracoviska" || $userRole === "Poverený pracovník pracoviska") {
                return response("You can't update admin or department head ", 403);
            } else {
                $this->updateUser($user, $fields, $userRole);
                return response('User updated');
            }
        }
    }


    public function updateUser($user,$fields,$userRole)
    {
        $user->fill($fields);
        $user->save();
        if (($fields['company_id'] || $fields['phone']) && $userRole === "Zástupca firmy") {
            $companyEmployee = CompanyEmployee::where('user_id',$user->id);
            $companyEmployee->fill($fields);
            $companyEmployee->save();
        }
        if($userRole==="Poverený pracovník pracoviska" ||$userRole==="Vedúci pracoviska"){
            $departmentEmployee=DepartmentEmployee::where('user_id',$user->id)->where('to',null);
            if($fields["department_id"]){
                $departmentEmployee->to=now();
                $departmentEmployee->save();
                $newDepartmentEmployee=DepartmentEmployee::create([
                    'user_id'=>$user->id,
                    'department_id'=>$fields['department_id'],
                    'from'=>now()
                ]);
            }
            else{
                $departmentEmployee->fill($fields);
                $departmentEmployee->save();
            }
        }
    }


}

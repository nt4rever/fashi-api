<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRoleRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $user = $request->only('email', 'password');
        if (!$token = Auth::attempt($user)) {
            return response()->json(['error' => 'Unauthorized', 'status' => "failed"], 401);
        }
        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        if (!Auth::user()->authorizeRoles('admin')) {
            return response()->json([
                'message' => 'Action unauthorized!',
            ], 201);
        }

        $user = User::create(array_merge(
            $request->all(),
            ['password' => bcrypt($request->password)]
        ));

        $role = Role::where('name', 'user')->first();
        $user->roles()->attach($role);

        return response()->json([
            'status' => 'success',
            'message' => 'User successfully registered',
            'user' => new UserResource($user)
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'User successfully signed out', 'status' => 'success']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(Auth::refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return new UserResource(Auth::user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => new UserResource(Auth::user()),
        ]);
    }

    public function addRole(AddRoleRequest $request)
    {
        if (!Auth::user()->authorizeRoles('admin')) {
            return response()->json([
                'message' => 'Action unauthorized!',
            ], 201);
        }

        if (Auth::user()->id == $request->user_id) {
            return response()->json([
                'message' => 'Action unauthorized (id error)!',
            ], 201);
        }

        try {
            $user = User::where('id', $request->user_id)->firstOrFail();
            $user->roles()->detach();
            foreach ($request->roles as $item) {
                $role = Role::where('name', $item)->firstOrFail();
                $user->roles()->attach($role);
            }

            return response()->json([
                'message' => 'User Successfully add new role',
                'status' => 'success'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error when add new role!',
                'status' => 'fail'
            ], 201);
        }
    }

    public function changePassWord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $userId = Auth::user()->id;

        $user = User::where('id', $userId)->update(
            ['password' => bcrypt($request->new_password)]
        );

        return response()->json([
            'message' => 'User successfully changed password',
            'user' => $user,
        ], 201);
    }
}

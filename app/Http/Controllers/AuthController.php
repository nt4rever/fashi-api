<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AddRoleRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\DeleteUserRequest;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\API;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $userService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->userService = $userService;
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
            return response()->json([
                'error' => 'Unauthorized',
                'status' => API::STATUS_ATHORIZE_FAILED
            ], 401);
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
        $res = $this->userService->create($request, Auth::user()->id);
        return response()->json($res, 201);
    }

    public function addRole(AddRoleRequest $request)
    {
        $res = $this->userService->addRole($request, Auth::user()->id);
        return response()->json($res, 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'User successfully signed out',
            'status' => API::STATUS_SUCCESS
        ]);
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
        return response()->json([
            'data' => new UserResource(Auth::user()),
            'status' => API::STATUS_SUCCESS
        ], 201);;
    }

    public function changePassWord(ChangePasswordRequest $request)
    {
        $res = $this->userService->changePassWord($request, Auth::user()->id);
        return response()->json($res, 201);
    }

    public function delete(DeleteUserRequest $request)
    {
        return response()->json($this->userService->deleteUser($request, Auth::user()->id), 201);
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
            'status' => API::STATUS_SUCCESS
        ]);
    }
}

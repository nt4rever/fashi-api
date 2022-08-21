<?php

namespace App\Services;

use App\Http\Resources\API;
use App\Http\Resources\UserResource;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    protected $userRepository;
    protected $roleRepository;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function createUser($request)
    {
        $attributes = $request->only('email', 'name', 'password');
        $user = $this->userRepository->create($attributes);
        $r = $this->roleRepository->findByName('user');
        $this->userRepository->attachRole($user->id, $r);
        return [
            'message' => 'User registration successful.',
            'user' => new UserResource($user),
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function addUserRole($request)
    {
        if (Auth::user()->id == $request->id) {
            return [
                'message' => 'Action unauthorized!',
                'status' => API::STATUS_ATHORIZE_FAILED
            ];
        }
        $this->userRepository->detachRole($request->id);
        foreach ($request->roles as $item) {
            $role = $this->roleRepository->findByName($item);
            $this->userRepository->attachRole($request->id, $role);
        }
        return [
            'message' => 'Add user role successfully.',
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function changeUserPassword($request, $id)
    {
        $user = $this->userRepository->find($id);
        if (!Hash::check($request->old_password, $user->password)) {
            return [
                'message' => 'Old password incorrect!',
                'status' => API::STATUS_FAILED
            ];
        }
        $this->userRepository->update($id, ['password' => $request->new_password]);
        return [
            'message' => 'Change user password successfully.',
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function deleteUser($request)
    {
        if (Auth::user()->id == $request->id) {
            return [
                'message' => "Delete user failed, can't delete yourself.",
                'status' => API::STATUS_ATHORIZE_FAILED
            ];
        }
        $this->userRepository->detachRole($request->id);
        $this->userRepository->delete($request->id);
        return [
            'message' => 'Delete user successfully.',
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function allUser()
    {
        $all = $this->userRepository->all();
        return [
            'data' => UserResource::collection($all),
            'status' => API::STATUS_SUCCESS
        ];
    }
}

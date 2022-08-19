<?php

namespace App\Services;

use App\Http\Requests\Auth\AddRoleRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\DeleteUserRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\API;
use App\Http\Resources\UserResource;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;

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

    public function create(UserRegisterRequest $request, $id)
    {
        $auth = $this->userRepository->find($id);
        if (!$auth->authorizeRoles('admin')) {
            return [
                'message' => 'Action unauthorized!',
                'status' => API::STATUS_ATHORIZE_FAILED
            ];
        }
        $attributes = $request->only('email', 'name', 'password');
        $user = $this->userRepository->create($attributes);
        $r = $this->roleRepository->findByName('user');
        $this->userRepository->attachRole($user->id, $r);
        return [
            'message' => 'User successfully registered',
            'user' => new UserResource($user),
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function addRole(AddRoleRequest $request, $id)
    {
        $this->validateUser($request->user_id, $id);
        $this->userRepository->detachRole($request->user_id);
        foreach ($request->roles as $item) {
            $role = $this->roleRepository->findByName($item);
            $this->userRepository->attachRole($request->user_id, $role);
        }
        return [
            'message' => 'User Successfully add new role',
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function changePassword(ChangePasswordRequest $request, $id)
    {
        $this->userRepository->update($id, ['password' => $request->new_password]);
        return [
            'message' => 'User successfully changed password',
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function deleteUser(DeleteUserRequest $request, $id)
    {
        $this->validateUser($request->user_id, $id);
        $this->userRepository->detachRole($request->user_id);
        $this->userRepository->delete($request->user_id);
        return [
            'message' => 'Delete user successfully',
            'status' => API::STATUS_SUCCESS
        ];
    }

    protected function validateUser($id, $auth_id)
    {
        $auth = $this->userRepository->find($auth_id);
        if (!$auth->authorizeRoles('admin') || $auth->id == $id) {
            return [
                'message' => 'Action unauthorized!',
                'status' => API::STATUS_ATHORIZE_FAILED
            ];
        }
    }
}

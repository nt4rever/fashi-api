<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements Repository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function find($id)
    {
        return $this->user->find($id);
    }

    public function create($attributes)
    {
        return $this->user->create($attributes);
    }

    public function update($id, $attributes)
    {
        return $this->user->find($id)->update($attributes);
    }

    public function delete($id)
    {
        return $this->user->find($id)->delete();
    }

    public function attachRole($id, $role)
    {
        return $this->user->find($id)->roles()->attach($role);
    }

    public function detachRole($id)
    {
        return $this->user->find($id)->roles()->detach();
    }
}

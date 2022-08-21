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
        return $this->user->findOrFail($id);
    }

    public function create($attributes)
    {
        return $this->user->create($attributes);
    }

    public function update($id, $attributes)
    {
        return $this->user->findOrFail($id)->update($attributes);
    }

    public function delete($id)
    {
        return $this->user->findOrFail($id)->delete();
    }

    public function all()
    {
        return $this->user->all();
    }

    public function attachRole($id, $role)
    {
        return $this->user->findOrFail($id)->roles()->attach($role);
    }

    public function detachRole($id)
    {
        return $this->user->findOrFail($id)->roles()->detach();
    }
}

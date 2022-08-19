<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository implements Repository
{
    protected $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function find($id)
    {
        return $this->role->find($id);
    }

    public function findByName($name)
    {
        return $this->role->where('name', $name)->first();
    }
}

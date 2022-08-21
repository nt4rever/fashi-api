<?php

namespace App\Repositories;

use App\Models\Brand;

class BrandRepository
{
    protected $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    public function find($id)
    {
        return $this->brand->findOrFail($id);
    }

    public function showAll()
    {
        return $this->brand->all();
    }

    public function create($attributes)
    {
        return $this->brand->create($attributes);
    }

    public function update($id, $attributes)
    {
        return $this->brand->findOrFail($id)->update($attributes);
    }

    public function delete($id)
    {
        return $this->brand->findOrFail($id)->delete();
    }
}

<?php

namespace App\Services;

use App\Http\Resources\API;
use App\Repositories\BrandRepository;

/**
 * Class BrandService
 * @package App\Services
 */
class BrandService
{

    protected $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function create($request)
    {

        $this->brandRepository->create($request->only('name', 'desc'));
        return [
            'message' => 'Add new brand successfully.',
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function delete($request)
    {
        $this->brandRepository->delete($request->id);
        return [
            'message' => 'Delete brand successfully.',
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function update($request)
    {
        $this->brandRepository->update($request->id, $request->only('name', 'status', 'desc'));
        return [
            'message' => 'Update brand successfully.',
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function showAll()
    {
        $res = $this->brandRepository->showAll();
        return [
            'data' => $res,
            'status' => API::STATUS_SUCCESS
        ];
    }

    public function show($id)
    {
        $res = $this->brandRepository->find($id);
        return [
            'data' => $res,
            'status' => API::STATUS_SUCCESS
        ];
    }
}

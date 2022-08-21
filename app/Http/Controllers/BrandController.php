<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Requests\Brand\DestroyBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Services\BrandService;

class BrandController extends Controller
{

    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->middleware('auth.role:admin,author', ['except' => ['index', 'show']]);
        $this->brandService = $brandService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = $this->brandService->showAll();
        return response()->json($res, 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBrandRequest $request)
    {
        $res = $this->brandService->create($request);
        return response()->json($res, 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res = $this->brandService->show($id);
        return response()->json($res, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrandRequest $request)
    {
        $res = $this->brandService->update($request);
        return response()->json($res, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyBrandRequest $request)
    {
        $res = $this->brandService->delete($request);
        return response()->json($res, 201);
    }
}

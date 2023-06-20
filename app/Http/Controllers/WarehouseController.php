<?php

namespace App\Http\Controllers;

use App\Http\Requests\warehouseRequest;
use App\Http\Resources\warehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    try {
             $warehouses=Warehouse::all();
             return warehouseResource::collection($warehouses);
        } catch (\Throwable $th) {
            return response(['error'=>$th]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(warehouseRequest $request)
    {
       
        try {
            $user=Auth::user();
            
             $result=Warehouse::create([
                'name'=>$request['name'],
                'created_by'=>$user->id,
        ]);
       
             return new warehouseResource($result);
        } catch (\Throwable $th) {
            return response(['error'=>$th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {   
        
                $result=Warehouse::query()->find($id);
             
                return new warehouseResource($result);
        } catch (\Throwable $th) {
            return response(['error'=>'not warehouse with this id']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Warehouse $warehouse)
    {   
     try {
        $warehouse->update($request->only([
            'name'
        ]));

        return new warehouseResource($warehouse);
     } catch (\Throwable $th) {
       return response(['error' => $th]);
     }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        if($warehouse) {
            $warehouse->forceDelete();
            return response(['msg'=>'deleted']);
        }

        return response(['msg'=>"no warehouse with id {$warehouse} found"]);
    }
}

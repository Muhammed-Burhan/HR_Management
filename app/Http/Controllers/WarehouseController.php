<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Http\Requests\warehouseRequest;
use App\Http\Resources\warehouseResource;
use App\Http\Resources\warehouse_branch_resource;
use App\Models\Device;
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
            throw (new GeneralJsonException($th->getMessage()));
        }
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
            throw (new GeneralJsonException($th->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        
        throw_if(!$warehouse, GeneralJsonException::class, 'Failed to get the warehouse');
           
         return new warehouseResource($warehouse);
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
       throw (new GeneralJsonException($th->getMessage()));
     }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        throw_if(!$warehouse, GeneralJsonException::class,'Record not found');

        $warehouse->forceDelete();
        return response(['msg'=>'deleted']);
       
    }

    public function getWarehouseBranch(Request $request,string $id)
    {      
        $warehouse=Warehouse::find($id);

        throw_if(!$warehouse,GeneralJsonException::class,'No warehouse found');

        $data=new warehouse_branch_resource($warehouse);

        return response([$data]);
    }

    public function getDevicesOfWarehouse(Warehouse $warehouse){
        throw_if(!$warehouse,GeneralJsonException::class);

        $devices = Device::join('branch', 'devices.branch_id', '=', 'branch.id')
        ->where('warehouse_id', $warehouse->id)
        ->select('device_name', 'serial_number', 'mac_address', 'status', 'registered_date', 'sold_date', 'cartoon_number')
        ->get();

        return response()->json($devices);
    }

}

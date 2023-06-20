<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Http\Resources\BranchResource;
use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         try {
             $warehouses=Branch::all();
             return BranchResource::collection($warehouses);
            } catch (\Throwable $th) {
            throw (new GeneralJsonException($th->getMessage()));
        }
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchRequest $request)
    {   
        
        try {
            $user=Auth::user();
            
             $result=Branch::create([
                'name'=>$request['name'],
                'warehouse_id'=>$request['warehouse_id'],
                'profile_logo'=>$request['profile_logo'],
                'address'=>$request['address'],
                'account_id'=>$user->id,
                'time'=>now()
                
        ]);
            return new BranchResource($result);
        } catch (\Throwable $th) {
            throw (new GeneralJsonException($th->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        throw_if(!$branch, GeneralJsonException::class, 'Failed to get the warehouse');
        
        $remaining_devices=$branch->remainingDevice()->count();
        $sold_devices=$branch->soldDevice()->count();

        $branch_device_data=[
            'name'=>$branch->name,
            'profileLogo'=>$branch->profile_logo,
            'address'=>$branch->address,
            'remind devices'=>$remaining_devices,
            'sold device'=>$sold_devices
        ];
        
         return response($branch_device_data);
    }

 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        try {
        $branch->update($request->only([
            'name','profile_logo','address','warehouse_id'
        ]));

        return new BranchResource($branch);
     } catch (\Throwable $th) {
       throw (new GeneralJsonException($th->getMessage()));
     }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        throw_if(!$branch, GeneralJsonException::class, 'Record not found');

        $branch->forceDelete();
        return response(['msg'=>'deleted the branch']);
    }
}

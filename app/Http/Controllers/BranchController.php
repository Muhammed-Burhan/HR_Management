<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use Illuminate\Http\Request;
use App\Http\Resources\BranchResource;
use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use App\Models\Log;
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
            $log = new LogController();
            $log->log([
                'user_id' =>Auth::user()->id,
                'user' =>Auth::user()->name,
                'action' => 'Show Branch',
                'details' => 'User requesting all branches',
                ]);
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
            $log = new LogController();
            $log->log([
                'user_id' =>$user->id,
                'user' =>$user->name,
                'action' => 'Creates Branch',
                'details' => 'User creates a new branch',
                ]);

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
           $log = new LogController();
            $log->log([
                'user_id' =>$branch->id,
                'user' =>Auth::user()->name,
                'action' => 'request for branch',
                'details' => 'User request for a single branch',
                ]);
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
           $log = new LogController();
            $log->log([
                'user_id' =>$branch->id,
                'user' =>Auth::user()->name,
                'action' => 'Update:Branch',
                'details' => 'User updates a new branch',
                ]);
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
        $log = new LogController();
            $log->log([
                'user_id' =>$branch->id,
                'user' =>Auth::user()->name,
                'action' => 'Delete:Branch',
                'details' => 'User deletes a new branch',
                ]);
        $branch->forceDelete();
        return response(['msg'=>'deleted the branch']);
    }
}

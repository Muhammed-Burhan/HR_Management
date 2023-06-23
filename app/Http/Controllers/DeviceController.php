<?php
namespace App\Http\Controllers;
use App\Events\Device_Status_Changed;
use App\Http\Requests\DeviceRequest;
use App\Http\Resources\DeviceResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use App\Exceptions\GeneralJsonException;
use App\Jobs\ImportDevices;
use App\Models\Branch;
use Illuminate\Support\Facades\Cache;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         try {
             $log = new LogController();
             $log->log([
                'user_id' =>Auth::user()->id,
                'user' =>Auth::user()->name,
                'action' => 'Show Device',
                'details' => 'User requesting all Devices',
                ]);
             $devices=Device::paginate(10);
             return DeviceResource::collection($devices);
            } catch (\Throwable $th) {
            throw (new GeneralJsonException($th->getMessage()));
        }
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(DeviceRequest $request)
    {
          try {
            $user=Auth::user();
             $log = new LogController();
             $log->log([
                'user_id' =>$user->id,
                'user' =>$user->name,
                'action' => 'store Device',
                'details' => 'User creates a new Device',
                ]);

                $branch = Branch::find($request['branch_id']);
                if (!$branch) {
                    throw new GeneralJsonException('Branch with id ' . $request['branch_id'] . ' does not exist');
                }
             $result=Device::create([
                'device_name'=>$request['device_name'],
                'serial_number'=>$request['serial_number'],
                'mac_address'=>$request['mac_address'],
                'registered_date'=>Date::now()->format('Y-m-d H:i:s'),
                'status'=>false,
                'branch_id'=>$request['branch_id'],
                'cartoon_number'=>$request['cartoon_number'],
        ]);
            return new DeviceResource($result);
        } catch (\Throwable $th) {
            throw (new GeneralJsonException($th->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
          if(!$device){
            abort(404,'device not found');
          }
            $log = new LogController();
             $log->log([
                'user_id' =>Auth::user()->id,
                'user' =>Auth::user()->name,
                'action' => 'Show device',
                'details' => 'User requests for a device',
                ]);
         return new DeviceResource($device);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
       try {
        $device->update($request->only([
                 'device_name',
                'serial_number',
                'mac_address',
                'sold_date',
                'branch_id',
                'cartoon_number',
        ]));
        $log = new LogController();
             $log->log([
                'user_id' =>Auth::user()->id,
                'user' =>Auth::user()->name,
                'action' => 'Update device',
                'details' => 'User updates a device',
                ]);
        return new DeviceResource($device);
     } catch (\Throwable $th) {
       throw (new GeneralJsonException($th->getMessage()));
     }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        throw_if(!$device, GeneralJsonException::class,'Record not found');
         $log = new LogController();
             $log->log([
                'user_id' =>Auth::user()->id,
                'user' =>Auth::user()->name,
                'action' => 'deleted device',
                'details' => 'User deleted a device',
                ]);
        $device->forceDelete();
        return response(['msg'=>'deleted']);
    }


    //the result of this method will be cached for 3 minutes
    //and we use pagination
     public function search(Request $request){
       $query_params = $request->query('q');
       
       if(!$query_params) 
       {
       return response(['msg'=>'please provide query']);
       }
       $paginateSize = $request->paginate_size ?? 10;
       $cacheKey='deviceSearch'.md5(json_encode($request->all()));
        $devices = Cache::remember( $cacheKey,180,function()use($query_params,$paginateSize){
        return Device::where('serial_number', 'like', "%$query_params%")
        ->orWhere('mac_address', 'like', "%$query_params%")
        ->paginate($paginateSize);
      });

        $log = new LogController();
        $log->log([
                'user_id' =>Auth::user()->id,
                'user' =>Auth::user()->name,
                'action' => 'search',
                'details' => 'User requests searching',
                ]);

        return  DeviceResource::collection($devices);
    }

    public function changeStatus(Device $device){
      
      throw_if(!$device, GeneralJsonException::class);
      $user=Auth::user();
      if($device->status){
        return response(['msg'=>'device status changed before']);
      }
      $log = new LogController();
      $log->log([
                'user_id' =>Auth::user()->id,
                'user' =>Auth::user()->name,
                'action' => 'changeStatus',
                'details' => 'User requests for changing device status',
                ]);
      $device->status = true;
      $device->save();

        if($user->role === 'super_admin'){
          
          event(new Device_Status_Changed($user,$device));
          return response(['msg'=>'email send']);
        }
        return response(['msg'=>'you perform this action']);
    }



   public function export()
  {
     if (!Auth::check()) {
        return response(['message' => 'Unauthorized'], 401);
      }
     $log = new LogController();
      $log->log([
                'user_id' =>Auth::user()->id,
                'user' =>Auth::user()->name,
                'action' => 'export',
                'details' => 'User requests for exporting devices',
                ]);
                
    $filePath = app_path('Http/devices.csv');
    $devices =Device::select(['id', 'device_name', 'serial_number', 'mac_address', 'status', 'branch_id', 'registered_date', 'sold_date', 'cartoon_number'])->get();
  
    if(empty($devices)){
      return response(['message'=>"devices table is empty"]);
    }

    $file = fopen($filePath, 'w');
      
    
    fputcsv($file, ['id', 'device_name', 'serial_number Number', 'mac_address', 'status', 'branch_id', 'registered_date', 'sold_date', 'cartoon_number']);

    // Write device data rows
    foreach ($devices as $device) {
        fputcsv($file, [
            $device->id,
            $device->device_name,
            $device->serial_number,
            $device->mac_address,
            $device->status,
            $device->branch_id ?? 1,
            $device->registered_date,
            $device->sold_date,
            $device->cartoon_number,
        ]);
    }
    fclose($file);
    
  
    return response(['message' => 'Devices exported successfully']);
  }


  public function import(Request $request){
    $filePath = app_path('Http/devices.csv');
    $log = new LogController();
      $log->log([
                'user_id' =>Auth::user()->id,
                'user' =>Auth::user()->name,
                'action' => 'import devices',
                'details' => 'User requests for importing devices data',
                ]);
    $isSuccessful=ImportDevices::dispatch($filePath);
    if(!$isSuccessful) return response(['msg'=>'failed to import devices']);
    return response(['msg'=>'Successfully imported devices']);
  }
}

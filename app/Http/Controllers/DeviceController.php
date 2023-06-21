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
use League\Csv\Writer;
use League\Csv\CannotInsertRecord;
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
             $warehouses=Device::all();
             return DeviceResource::collection($warehouses);
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
             $result=Device::create([
                'device_name'=>$request['device_name'],
                'serial_number'=>$request['serial_number'],
                'mac_address'=>$request['mac_address'],
                'registered_date'=>Date::now()->format('Y-m-d H:i:s'),
                'status'=>false,
                'branch_id'=>$request['branch_id'],
                'cartoon_number'=>$request['device_name'],
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
                'status',
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


     public function search(Request $request){
       $query_params = $request->query('q');
       
       if(!$query_params) return response(['msg'=>'please provide query']);
        $devices = Device::where('serial_number', 'like', "%$query_params%")
        ->orWhere('mac_address', 'like', "%$query_params%")
        ->get();

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
        }
    }



   public function export()
  {
    $filePath = app_path('Http/devices.csv');
    $devices = Device::all(['id', 'device_name', 'serial_number', 'mac_address', 'status', 'branch_id', 'registered_date', 'sold_date', 'cartoon_number']);
    $log = new LogController();
      $log->log([
                'user_id' =>Auth::user()->id,
                'user' =>Auth::user()->name,
                'action' => 'export devices',
                'details' => 'User requests for exporting devices data',
                ]);
    if(empty($devices)){
      return response(['meassge'=>"devices table is empty"]);
    }

    $file = fopen($filePath, 'a');
      
    // Write the header row
    fputcsv($file, ['id', 'device_name', 'serial_number Number', 'mac_address', 'status', 'branch_id', 'registered_date', 'sold_date', 'cartoon_number']);

    // Write device data rows
    foreach ($devices as $device) {
        fputcsv($file, [
            $device->id,
            $device->device_name,
            $device->serial_number,
            $device->mac_address,
            $device->status,
            strval($device->branch_id),
            $device->registered_date,
            $device->sold_date,
            $device->cartoon_number,
        ]);
    }
    
    fclose($file);
    
    return response(['message' => 'Devices exported successfully']);
  }

  public function import(){
    $filePath = app_path('Http/devices.csv');

    ImportDevices::dispatch($filePath);
  }


}

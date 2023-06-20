<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Date;
use App\Exceptions\GeneralJsonException;
use App\Http\Resources\LogResource;
use Illuminate\Support\Facades\Log as FileLog;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         try {
            $warehouses=Log::all();
           
             return LogResource::collection($warehouses);
            } catch (\Throwable $th) {
            throw (new GeneralJsonException($th->getMessage()));
        }
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function log(array $data)
    {
        
        $log = new Log();
            $currentTime = Date::now()->format('Y-m-d H:i:s');
            $log->user_id = $data['user_id'];
            $log->user=$data['user'];
            $log->action = $data['action'];
            $log->details = $data['details'];
            $log->time_of_log=$currentTime;
            $log->save();


        $Message="User {$log->user_id}:{$log->user}: {$log->action} - {$log->details} / Date/Time: {$log->time_of_log}";
        FileLog::Channel('userActions')->info($Message);

    }

    /**
     * Display the specified resource.
     */
    public function show(Log $log)
    {
        throw_if(!$log, GeneralJsonException::class);
           
        return new LogResource($log);
    }

   
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Log $log)
    {
        throw_if(!$log, GeneralJsonException::class,'Record not found');

        $log->forceDelete();
        return response(['msg'=>'deleted']);
    }

   
}

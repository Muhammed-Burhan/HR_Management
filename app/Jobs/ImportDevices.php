<?php

namespace App\Jobs;

use App\Exceptions\GeneralJsonException;
use App\Models\Branch;
use App\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class ImportDevices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath)
    {
         $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $is_import_successful = false;
        if (($handle = fopen($this->filePath, 'r')) !== false) {
            $data = fgetcsv($handle, 1000, ','); //doing this to skip the column names
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $stringToInteger = (int)$data[5];
                $device = new Device();
                $device->device_name= $data[1];
                $device->serial_number = $data[2]; 
                $device->mac_address = $data[3];
                $device->status = boolval($data[4]);
                $device->branch_id =$stringToInteger;
                $branch = Branch::find($device->branch_id);
                if (!$branch) {
                    throw new GeneralJsonException('Branch with id ' . $device->branch_id . ' does not exist');
                }
                $device->registered_date = Date::now()->format('Y-m-d H:i:s');
                $device->sold_date = null;
                $device->cartoon_number = $data[8];
          
                
                try {
                    $device->save();
                    $is_import_successful = true;
                } catch (\Throwable $th ) {
                    throw new GeneralJsonException($th->getMessage());
                }
            }
            
            fclose($handle);
        } else {
            response(['message'=>"Failed to open CSV file:{$this->filePath}"]);
        }
        return $is_import_successful;
    }
}

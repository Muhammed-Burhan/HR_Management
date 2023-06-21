<?php

namespace App\Jobs;

use App\Exceptions\GeneralJsonException;
use App\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
    public function handle(): void
    {
        if (($handle = fopen($this->filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $device = new Device();
                $device->device_name= $data[1];
                $device->serial_number = $data[2]; 
                $device->mac_address = $data[3];
                $device->status = boolval($data[4]);
                $device->branch_id =1;
                $device->registered_date = Date::now()->format('Y-m-d H:i:s');
                $device->sold_date = null;
                $device->cartoon_number = $data[8];
                
          
                
                try {
                    $device->save();
                } catch (\Throwable $th ) {
                    throw new GeneralJsonException($th->getMessage());
                }
            }

            fclose($handle);
        } else {
            response(['message'=>"Failed to open CSV file:{$this->filePath}"]);
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Device;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
class ExportDevicesToJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    
     protected $signature = 'devices:export-json';
    protected $description = 'Export all devices to a JSON file';
    
    

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $devices = Device::all();
        $data = $devices->toArray();
        $json = json_encode($data, JSON_PRETTY_PRINT);

        $filename = app_path('Http/devices.csv');
        $path = storage_path($filename);

        File::put($path, $json);

        $this->info("Devices exported to $filename");
    }
}

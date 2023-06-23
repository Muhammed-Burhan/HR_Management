<?php

namespace App\Console\Commands;

use App\Models\Device;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
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
        $json = json_encode($data, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        //I made this change to create a new json file every time the command ran by user 
        $filename = 'devices.date'. date('Y_m_d_His') .'.json';
        $path = storage_path('app/' . $filename);

        //open and append the json in the path
        File::append($path, $json);

        $this->info("Devices exported to $filename");
    }
}

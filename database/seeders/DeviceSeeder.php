<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisablingForeignKey;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{   use TruncateTable,DisablingForeignKey;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKey();
        $this->truncate('devices');
        \App\Models\Device::factory(3000)->create();
        $this->enableForeignKey();
    }
}

<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisablingForeignKey;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{   
    use TruncateTable,DisablingForeignKey;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKey();
        $this->truncate('warehouse');
        \App\Models\Warehouse::factory(2)->create();
        $this->enableForeignKey();
    }
}

<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisablingForeignKey;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use TruncateTable, DisablingForeignKey;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $this->disableForeignKey();
        $this->truncate('users');
        $user=\App\Models\User::factory(1)->create();
        $this->enableForeignKey();
    }
}
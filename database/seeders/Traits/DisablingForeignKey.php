<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait DisablingForeignKey
{

    protected function disableForeignKey()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }
    protected function enableForeignKey()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

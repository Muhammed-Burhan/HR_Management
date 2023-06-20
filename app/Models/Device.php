<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table='devices';

    public $timestamps = false;


    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id');
    }
}

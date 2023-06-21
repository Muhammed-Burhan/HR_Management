<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    public $table='devices';

    public $timestamps = false;

    protected $filters=[
        'sort',
        'between',
        'like'
    ];
     protected $fillable = [
        'device_name',
        'serial_number',
        'mac_address',
        'branch_id',
        'registered_date',
        'sold_date',
        'cartoon_number'
    ];


    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id');
    }
}

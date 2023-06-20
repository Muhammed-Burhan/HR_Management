<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;


     protected $table = 'branch';

     public function user()
    {
        return $this->belongsTo(User::class, 'account_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function devices()
    {
        return $this->hasMany(Device::class, 'branch_id','id');
    }
}

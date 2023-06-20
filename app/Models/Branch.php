<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;


     protected $table = 'branch';

      protected $fillable = [
        'name',
        'warehouse_id',
        'account_id',
        'profile_logo',
        'address',
        'time'
    ];
    public $timestamps = false;
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

    public function remainingDevice(){
        return $this->devices()->whereNull('sold_date');
    }
    public function soldDevice(){
        return $this->devices()->whereNotNull('sold_date');
    }
}

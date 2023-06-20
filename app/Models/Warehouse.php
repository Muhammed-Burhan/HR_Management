<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouse';


 /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

      protected $fillable = [
        'name',
        'created_by',
    ];

}

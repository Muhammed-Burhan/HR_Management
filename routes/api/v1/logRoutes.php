<?php

use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;


Route::group([
                'middleware' => ['auth:sanctum', SuperAdminMiddleware::class],
                'prefix' => 'log'
            ], 
 function () 
        {
            Route::get('/',[LogController::class,'index']);
            
            Route::get('/{log}',[LogController::class,'show']);
    
            Route::delete('/{log}',[LogController::class,'destroy']);
    

        });
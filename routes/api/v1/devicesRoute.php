<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Middleware\AdminAndSuperAdmin;
use App\Http\Middleware\SuperAdminMiddleware;

Route::group([
                'middleware' => ['auth:sanctum', AdminAndSuperAdmin::class],
                'prefix' => 'devices'
            ], 
        function () 
               {

               Route::group(['middleware' => [SuperAdminMiddleware::class]],
               function()
               {
                Route::get('/export',[DeviceController::class,'export']);
                   
                Route::get('/import',[DeviceController::class,'import']);
                   
                Route::delete('/{device}',[DeviceController::class,'destroy']);
                });

                Route::get('/',[DeviceController::class,'index']);
               
                Route::get('/search',[DeviceController::class,'search']);
               
                Route::get('/{device}',[DeviceController::class,'show']);
               
                Route::get('/{device}/status',[DeviceController::class,'changeStatus']);
               
                Route::post('/',[DeviceController::class,'store']);
               
                Route::put('/{device}',[DeviceController::class,'update']);
    });
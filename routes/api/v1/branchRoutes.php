<?php

use App\Http\Middleware\AdminAndSuperAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Middleware\SuperAdminMiddleware;

Route::group([
                'middleware' => ['auth:sanctum', AdminAndSuperAdmin::class],
                'prefix' => 'branch'
            ], 
 function () 
        {
        Route::group(['middleware' => [SuperAdminMiddleware::class]],function()
            {
                Route::post('/',[BranchController::class,'store']);
                
                Route::delete('/{branch}',[BranchController::class,'destroy']);
                
                Route::put('/{branch}',[BranchController::class,'update']);
            });
        Route::get('/',[BranchController::class,'index']);
        
        Route::get('/{branch}',[BranchController::class,'show']);
        
        

        });
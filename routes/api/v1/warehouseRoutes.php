<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\WarehouseController;



Route::group([
                'middleware' => ['auth:sanctum', SuperAdminMiddleware::class],
                'prefix' => 'warehouse'
            ], 
 function () 
        {
            Route::get('/',[WarehouseController::class,'index']);

            Route::post('/',[WarehouseController::class,'store']);

            Route::get('/{warehouse}',[WarehouseController::class,'show']);

            Route::put('/{warehouse}',[WarehouseController::class,'update']);

            Route::delete('/{warehouse}',[WarehouseController::class,'destroy']);

            //get all branches related to the same warehouse
            Route::get('/{id}/branch',[WarehouseController::class,'getWarehouseBranch']);

            //get all devices related to the same warehouse
            Route::get('/{warehouse}/device',[WarehouseController::class,'getDevicesOfWarehouse']);
   
        });

 
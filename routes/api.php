<?php

use App\Http\Controllers\DeviceController;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

 


Route::prefix('v1')->group(function(){

    require base_path('routes/api/v1/authRoutes.php');

    require base_path('routes/api/v1/warehouseRoutes.php');
    
    require base_path('routes/api/v1/branchRoutes.php');
    
    require base_path('routes/api/v1/logRoutes.php');

    require base_path('routes/api/v1/devicesRoute.php');
});






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

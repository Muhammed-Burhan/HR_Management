<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WarehouseController;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Models\Warehouse;

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


Route::get('/login',function(){
    return response(['error'=>'unauthorized']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum', SuperAdminMiddleware::class]], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //WareHouse End Points 
    Route::get('/warehouse',[WarehouseController::class,'index']);
    Route::post('/warehouse',[WarehouseController::class,'store']);
    Route::get('/warehouse/{warehouse}',[WarehouseController::class,'show']);
    Route::put('/warehouse/{warehouse}',[WarehouseController::class,'update']);
    Route::delete('/warehouse/{warehouse}',[WarehouseController::class,'destroy']);

    //Branch End Points
    Route::get('/warehouse/{id}/branch',[WarehouseController::class,'getWarehouseBranch']);


});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

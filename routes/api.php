<?php

use App\Http\Controllers\TasksController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resources([
    '/tasks' => TasksController::class
]);

Route::post('/sortByDate/{startDate}/{endDate}', [TasksController::class, 'sortByDate']);
Route::post('/sortByStatus/{status}/', [TasksController::class, 'sortByStatus']);
Route::get('/lastId', [TasksController::class, 'lastId']);
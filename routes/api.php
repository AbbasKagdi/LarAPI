<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// --------------------------------- Public routes ----------------------------------------------------
// splitting resources for api auth protection
// Route::resource('products', ProductController::class);

// fetch all products
Route::get('/products', [ProductController::class, 'index']);

// fetch one product
Route::get('/products/{id}', [ProductController::class, 'show']);
    
// search a product by name
Route::get('/products/search/{name}', [ProductController::class, 'search']);

// --------------------------------- Protected routes ----------------------------------------------------
Route::group(['middleware'=> ['auth:sanctum']] , function () {
    // create a product
    Route::post('/products', [ProductController::class, 'store']);

    // update a product
    Route::put('/products/{id}', [ProductController::class, 'update']);

    // delete a product
    Route::delete('/products/{id}', [ProductController::class, 'delete']);
});

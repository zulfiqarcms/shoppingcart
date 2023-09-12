<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\OrderController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Client Routes
Route::get('/',[ClientController::class,'home'] );
Route::get('/shop',[ClientController::class,'shop'] );
Route::get('/cart',[ClientController::class,'cart'] );
Route::get('/checkout',[ClientController::class,'checkout'] );
Route::get('/login',[ClientController::class,'login'] );
Route::get('/signup',[ClientController::class,'signup'] );


//Admin Routes
Route::get('/admin',[AdminController::class,'dashboard'] );
Route::get('/test',[AdminController::class,'test'] );

//Product Routes
Route::get('/addproduct',[ProductController::class,'addproduct'] );
Route::get('/products',[ProductController::class,'products'] );

//Category Routes
Route::get('/addcategory',[CategoryController::class,'addcategory'] );
Route::get('/categories',[CategoryController::class,'categories'] );

Route::post('/savecategory',[CategoryController::class,'savecategory']);
Route::get('/edit_category/{id}',[CategoryController::class,'edit_category'] );
Route::get('/updatecategory',[CategoryController::class,'updatecategory'] );
Route::post('/updatecategory',[CategoryController::class,'updatecategory'] );
//Slider Routes
Route::get('/addslider',[SliderController::class,'addslider'] );
Route::get('/sliders',[SliderController::class,'sliders'] );


//Orders Routes
Route::get('/orders',[ClientController::class,'orders'] );



/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';*/ 

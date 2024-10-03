<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LandingPageController::class, 'index'])->name('landing-page');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//products
Route::get('admin/products', [ProductController::class, 'index'])->name('products.index');
Route::get('admin/products/data', [ProductController::class, 'dataTable'])->name('products.data');
Route::post('admin/products', [ProductController::class, 'store'])->name('products.store');
Route::get('admin/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('admin/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('admin/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('admin/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('admin/categories/get', [ProductController::class, 'getCategories'])->name('categories.get');

Route::get('admin/categories/index', [CategoryController::class, 'indexBlade'])->name('categories.index');
Route::get('admin/categories/data', [CategoryController::class, 'dataTable'])->name('categories.data');
Route::post('admin/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('admin/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('admin/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');


Route::get('guest/shop/index', [ShopController::class, 'index'])->name('shop.index');


Route::get('guest/cart/index', [CartController::class, 'index'])->name('cart.index');
Route::post('guest/cart/store', [CartController::class, 'add'])->name('cart.store');
Route::delete('guest/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::patch('guest/cart/{id}/increase', [CartController::class, 'increaseQuantity'])->name('cart.increase');
Route::patch('guest/cart/{id}/decrease', [CartController::class, 'decreaseQuantity'])->name('cart.decrease');


Route::get('guest/contact/index', [ContactController::class, 'index'])->name('contact.index');

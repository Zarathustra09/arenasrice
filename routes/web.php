<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductContainerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Middleware\RoleMiddleware;
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

Route::get('/', [LandingPageController::class, 'index'])->name('landing-page')->middleware('redirect.home');

Auth::routes();

Route::group(['middleware' => ['auth', 'role:1']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    //admin products
    Route::get('admin/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('admin/products/data', [ProductController::class, 'dataTable'])->name('products.data');
    Route::post('admin/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('admin/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('admin/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('admin/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('admin/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('admin/categories/get', [ProductController::class, 'getCategories'])->name('categories.get');

    //admin categories
    Route::get('admin/categories/index', [CategoryController::class, 'indexBlade'])->name('categories.index');
    Route::get('admin/categories/data', [CategoryController::class, 'dataTable'])->name('categories.data');
    Route::post('admin/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('admin/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('admin/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    //admin orders
    Route::get('admin/order/index', [AdminOrderController::class, 'index'])->name('admin.order.index');
    Route::get('admin/order/data', [AdminOrderController::class, 'dataTable'])->name('admin.order.data');
    Route::put('admin/orders/{id}', [AdminOrderController::class, 'update'])->name('admin.orders.update');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');



    Route::get('/admin/ingredients', [IngredientController::class, 'index'])->name('ingredients.index');
    Route::get('/admin/ingredients/data', [IngredientController::class, 'dataTable'])->name('ingredients.data');
    Route::post('/admin/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
    Route::get('/admin/ingredients/{id}', [IngredientController::class, 'show'])->name('ingredients.show');
    Route::put('/admin/ingredients/{id}', [IngredientController::class, 'update'])->name('ingredients.update');
    Route::delete('/admin/ingredients/{id}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');

    Route::get('/admin/logs', [LogController::class, 'index'])->name('admin.logs.index');
    Route::get('/api/logs', [LogController::class, 'getLogs']);

    Route::get('/admin/accounts', [AccountController::class, 'index'])->name('admin.accounts.index');
    Route::get('/admin/accounts/data', [AccountController::class, 'data'])->name('admin.accounts.data');
    Route::post('/admin/accounts', [AccountController::class, 'store'])->name('admin.accounts.store');
    Route::get('/admin/accounts/{id}', [AccountController::class, 'show']);
    Route::put('/admin/accounts/{id}', [AccountController::class, 'update']);
    Route::delete('/admin/accounts/{id}', [AccountController::class, 'destroy']);

    Route::get('products/lowStockData', [HomeController::class, 'lowStockData'])->name('products.lowStockData');


    Route::resource('product-containers', ProductContainerController::class);


});

Route::get('admin/orders/{id}/download', [OrderController::class, 'downloadOrder'])->name('admin.order.download');
//products
Route::post('guest/contact/send', [ContactController::class, 'send'])->name('contact.send');
Route::get('guest/shop/index', [ShopController::class, 'index'])->name('shop.index');
Route::get('guest/contact/index', [ContactController::class, 'index'])->name('contact.index');
Route::get('guest/cart/index', [CartController::class, 'index'])->name('cart.index');
Route::post('guest/cart/store', [CartController::class, 'add'])->name('cart.store');
Route::delete('guest/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::patch('guest/cart/{id}/increase', [CartController::class, 'increaseQuantity'])->name('cart.increase');
Route::patch('guest/cart/{id}/decrease', [CartController::class, 'decreaseQuantity'])->name('cart.decrease');
Route::post('guest/cart/checkout', [OrderController::class, 'checkout'])->name('cart.checkout');

Route::get('guest/order/index', [OrderController::class, 'index'])->name('order.index');
Route::get('guest/order/list', [OrderController::class, 'orederListIndex'])->name('order.list');


Route::get('/guest/profile', [ProfileController::class, 'guestIndex'])->name('guest.profile.index');
Route::post('/guest/profile', [ProfileController::class, 'guestUpdate'])->name('guest.profile.update');


Route::get('/staff/pos/index', [PosController::class, 'index'])->name('pos.index');
Route::post('/staff/pos/saveOrder', [PosController::class, 'saveOrder'])->name('pos.saveOrder');
Route::get('/pos/orders', [PosController::class, 'getOrders'])->name('pos.getOrders');

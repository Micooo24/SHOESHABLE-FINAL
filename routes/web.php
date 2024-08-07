<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;

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

Route::view('/', 'welcome')->name('welcome');
Route::view('/about', 'about');
Route::view('/contact', 'contact');

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::view('/', 'welcome')->name('welcome');
Route::view('/about', 'about');
Route::view('/contact', 'contact');

Route::middleware(['auth', 'role:customer,admin'])->group(function () {
Route::view('/profile', 'user.profile');
Route::view('/shop', 'shop.index');
Route::view('/user/purchase', 'user.order');
Route::view('/my-wishlist', 'user.wishlist');
Route::view('/carts', 'user.cart');
Route::view('/checkout', 'user.checkout');
Route::get('/product/{id}', [ReviewController::class, 'show']);
Route::post('/product/{id}/review', [ReviewController::class, 'store'])->name('review.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::view('/products', 'admin.product');
    Route::view('/crud', 'admin.crud');
    Route::view('/brands', 'admin.brand');
    Route::view('/suppliers', 'admin.supplier');
    Route::view('/couriers', 'admin.courier');
    Route::view('/admin/profile', 'admin.profile')->name('admin.profile');
    Route::view('/stocks', 'admin.stock')->name('admin.stocks');
    Route::view('/users', 'admin.user')->name('admin.users');
    Route::view('/orders', 'admin.order')->name('admin.orders');
    Route::view('/admin-dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/charts', 'admin.chart')->name('admin.charts');
    Route::view('/payment/method', 'admin.payment');
});


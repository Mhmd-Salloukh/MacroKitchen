<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ExtrasController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KitchenController;

use App\Http\Middleware\CheckIfAdmin;
use App\Http\Middleware\CheckIfKitchen;
use App\Models\Item;

Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('/menu', [MainController::class, 'Menu'])->name('menu');
Route::get('/cart', [CartController::class, 'Cart'])->name('cart');
Route::post('/cart/add/{item}', [CartController::class, 'AddToCart'])->name('cart.add');
Route::post('/cart/increment/{item}', [CartController::class, 'IncrementCart'])->name('cart.increment');
Route::post('/cart/decrement/{item}', [CartController::class, 'DecrementCart'])->name('cart.decrement');
Route::post('/cart/remove/{item}', [CartController::class, 'RemoveFromCart'])->name('cart.remove');
Route::post('/cart/extras', [CartController::class, 'UpdateExtras'])->name('cart.updateExtras');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'RegisterPage'])->name('register');
    Route::post('/register', [AuthController::class, 'Register'])->name('register.action');
    Route::get('/login', [AuthController::class, 'LoginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'Login'])->name('login.action');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'Logout'])->name('logout');

    // Profile routes
    Route::get('/profile', [AuthController::class, 'Profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'UpdateProfile'])->name('profile.update');
    Route::get('/profile/info', [AuthController::class, 'ProfileInfo'])->name('profile.info');
    Route::post('cart/order', [CartController::class, 'Order'])->name('cart.order');
    Route::get('/myorders', [AuthController::class, 'MyOrders'])->name('myorders');
    Route::get('/cart/reorder/{order}', [CartController::class, 'Reorder'])->name('cart.reorder');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'AdminHome'])->name('admin.home');

    Route::get('/admin/users', [AdminController::class, 'ManageUsers'])->name('admin.users');
    Route::get('/admin/user/{user}/edit', [AdminController::class, 'EditUser'])->name('admin.userEdit');
    Route::post('/admin/user/{user}/UpdateRole', [AdminController::class, 'UpdateRole'])->name('admin.UpdateRole');
    Route::post('/admin/user/{user}/delete', [AdminController::class, 'DeleteUser'])->name('admin.userDelete');
    Route::post('/admin/user/{id}/restore', [AdminController::class, 'RestoreUser'])->name('admin.userRestore');
    Route::post('/admin/user/{id}/PDelete', [AdminController::class, 'PermanentDeleteUser'])->name('admin.userPDelete');

    Route::get('/admin/items', [ItemController::class, 'ManageItems'])->name('admin.items');
    Route::post('/admin/items/create', [ItemController::class, 'CreateItem'])->name('admin.itemCreate');
    Route::get('/admin/items/{item}/edit', [ItemController::class, 'EditItem'])->name('admin.itemEdit');
    Route::post('/admin/items/{item}/update', [ItemController::class, 'UpdateItem'])->name('admin.itemUpdate');
    Route::get('/admin/items/{item}/delete', [ItemController::class, 'DeleteItem'])->name('admin.itemDelete');
    Route::post('/admin/items/{item}/restore', [ItemController::class, 'RestoreItem'])->name('admin.itemRestore');
    Route::post('/admin/items/{item}/PDelete', [ItemController::class, 'PermanentDeleteItem'])->name('admin.itemPDelete');
    Route::post('/admin/items/{item}/addImage', [ItemController::class, 'AddItemImage'])->name('admin.itemAddImage');
    
    Route::get('/admin/categories', [CategoryController::class, 'ManageCategories'])->name('admin.categories');
    Route::post('/admin/categories/create', [CategoryController::class, 'CreateCategory'])->name('admin.CatCreate');
    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'EditCategory'])->name('admin.CatEdit');
    Route::post('/admin/categories/{category}/update', [CategoryController::class, 'UpdateCategory'])->name('admin.CatUpdate');
    Route::post('/admin/items/{item}/addCategory', [CategoryController::class, 'AddItemCategory'])->name('admin.itemAddCategory');
    Route::delete('/admin/items/{item}/removeCategory/{category}', [CategoryController::class, 'RemoveItemCategory'])->name('admin.itemRemoveCategory');

    Route::get('/admin/extras', [ExtrasController::class, 'ManageExtras'])->name('admin.extras');
    Route::post('/admin/extras/create', [ExtrasController::class, 'CreateExtra'])->name('admin.extraCreate');
    Route::get('/admin/extras/{extra}/edit', [ExtrasController::class, 'EditExtra'])->name('admin.extraEdit');
    Route::post('/admin/extras/{extra}/update', [ExtrasController::class, 'UpdateExtra'])->name('admin.extraUpdate');
    Route::delete('/admin/extras/{extra}/delete', [ExtrasController::class, 'DeleteExtra'])->name('admin.extraDelete');
     Route::post('/admin/items/{item}/addExtra', [ExtrasController::class, 'AddItemExtra'])->name('admin.itemAddExtra');
     Route::delete('/admin/items/{item}/removeExtra/{extra}', [ExtrasController::class, 'RemoveItemExtra'])->name('admin.itemRemoveExtra');

});

Route::middleware(['auth', 'kitchen'])->group(function () {
    Route::get('/kitchen', [KitchenController::class, 'KitchenHome'])->name('kitchen.home');
    Route::get('/kitchen/previous-orders', [KitchenController::class, 'PreviousOrders'])->name('kitchen.previousOrders');
    Route::get('/kitchen/orders', [KitchenController::class, 'ManageOrders'])->name('kitchen.orders');
    Route::get('/kitchen/orders/{id}', [KitchenController::class, 'ShowOrder'])->name('kitchen.order');
    Route::post('/kitchen/orders/{id}/status/{status}', [KitchenController::class, 'UpdateStatus'])->name('kitchen.updateStatus');
    Route::delete('/kitchen/order/{id}', [KitchenController::class, 'DeleteOrder'])->name('kitchen.deleteOrder');
});


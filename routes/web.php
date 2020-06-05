<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.shops.index')->with('status', session('status'));
    }

    return redirect()->route('admin.shops.index');
});

Route::get('/', 'HomeController@index')->name('home');
Route::get('tag/{tag}', 'HomeController@show')->name('tag');

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Shops
    Route::delete('shops/destroy', 'ShopsController@massDestroy')->name('shops.massDestroy');
    Route::post('shops/media', 'ShopsController@storeMedia')->name('shops.storeMedia');
    Route::resource('shops', 'ShopsController');
});

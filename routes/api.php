<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Tags
    Route::post('tags/media', 'TagsApiController@storeMedia')->name('tags.storeMedia');
    Route::apiResource('tags', 'TagsApiController');
});

Route::post('register', 'Auth\RegisterController@apiregister');
Route::post('login', 'Auth\LoginController@apilogin');
Route::post('logout', 'Auth\LoginController@apilogout');

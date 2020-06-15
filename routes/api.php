<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin'], function () {
    
    Route::group([
        'middleware' => ['auth:api']
    ], function() {
        Route::apiResource('permissions', 'PermissionsApiController');
        Route::apiResource('roles', 'RolesApiController');
        Route::apiResource('users', 'UsersApiController');
        Route::post('tags/media', 'TagsApiController@storeMedia')->name('tags.storeMedia');
        Route::apiResource('tags', 'TagsApiController');
        // ... Other secured API endpoints
    });
    
    /* // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController'); */

    /* // Users
    Route::apiResource('users', 'UsersApiController'); */

    /* // Tags
    Route::post('tags/media', 'TagsApiController@storeMedia')->name('tags.storeMedia');
    Route::apiResource('tags', 'TagsApiController'); */
});

Route::post('register', 'Auth\RegisterController@apiregister');
Route::post('login', 'Auth\LoginController@apilogin');
Route::get('logout', 'Auth\LoginController@apilogout');

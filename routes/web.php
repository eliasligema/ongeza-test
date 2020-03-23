<?php

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

// Register authentication routes
Auth::routes(['register' => true, 'verify' => false]);
Route::group(['namespace'=>'Auth', 'middleware' => []], function () {
    Route::middleware(['auth'])->get('logout', 'LoginController@logout');
});

Route::group(['prefix'=>'user-profile', 'middleware' => []], function () {
    Route::middleware([])->get('/details', 'UserProfileController@details');
    Route::middleware([])->post('/update', 'UserProfileController@update');
    Route::middleware([])->post('/image-update', 'UserProfileController@image_update');
    Route::middleware([])->get('/change-password', 'UserProfileController@change_password');
    Route::middleware([])->post('/change-password', 'UserProfileController@change_password_store');
});

Route::middleware(['admin'])->get('/', 'Admin\CustomerController@index'); // Retrieve list of Dashboards

Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware' => []], function () {
    // -------------------- Start routes for module Dashboard --------------------- //
    Route::group([], function () {
        
        Route::middleware(['admin'])->get('/', 'CustomerController@index'); // Retrieve list of Dashboards
        
        Route::middleware(['admin'])->get('/customer/list', 'CustomerController@index'); // Retrieve list of Customers
        Route::middleware(['admin'])->get('/customer/api-list', 'CustomerController@index_api'); // Retrieve list of json Customers 
        Route::middleware(['admin'])->get('/customer/create', 'CustomerController@create'); // Display form TO add Customer
        Route::middleware(['admin'])->post('/customer/store', 'CustomerController@store'); // Save new Customer
        Route::middleware(['admin'])->get('/customer/show/{id}/{sub_page?}', 'CustomerController@show'); // Retrieve specific Customer data by ID
        Route::middleware(['admin'])->get('/customer/edit/{id}', 'CustomerController@edit'); // Retrieve edit form for specific Customer
        Route::middleware(['admin'])->post('/customer/update/{id}', 'CustomerController@update'); // Update specific Customer data by ID
        Route::middleware(['admin'])->get('/customer/delete/{id}', 'CustomerController@destroy'); // Delete specific Customer by ID

        
        Route::middleware(['admin'])->get('/gender/list', 'GenderController@index'); // Retrieve list of Genders
        Route::middleware(['admin'])->get('/gender/api-list', 'GenderController@index_api'); // Retrieve list of json Genders 
        Route::middleware(['admin'])->get('/gender/create', 'GenderController@create'); // Display form TO add Gender
        Route::middleware(['admin'])->post('/gender/store', 'GenderController@store'); // Save new Gender
        Route::middleware(['admin'])->get('/gender/show/{id}/{sub_page?}', 'GenderController@show'); // Retrieve specific Gender data by ID
        Route::middleware(['admin'])->get('/gender/edit/{id}', 'GenderController@edit'); // Retrieve edit form for specific Gender
        Route::middleware(['admin'])->post('/gender/update/{id}', 'GenderController@update'); // Update specific Gender data by ID
        Route::middleware(['admin'])->get('/gender/delete/{id}', 'GenderController@destroy'); // Delete specific Gender by ID
        
        
        Route::middleware(['admin'])->get('/user/list', 'UserController@index'); // Retrieve list of Users
        Route::middleware(['admin'])->get('/user/api-list', 'UserController@index_api'); // Retrieve list of json Users 
        Route::middleware(['admin'])->get('/user/create', 'UserController@create'); // Display form TO add User
        Route::middleware(['admin'])->post('/user/store', 'UserController@store'); // Save new User
        Route::middleware(['admin'])->get('/user/show/{id}/{sub_page?}', 'UserController@show'); // Retrieve specific User data by ID
        Route::middleware(['admin'])->get('/user/edit/{id}', 'UserController@edit'); // Retrieve edit form for specific User
        Route::middleware(['admin'])->post('/user/update/{id}', 'UserController@update'); // Update specific User data by ID
        Route::middleware(['admin'])->get('/user/delete/{id}', 'UserController@destroy'); // Delete specific User by ID
        
    });
    // -------------------- End routes for module User --------------------- //
});
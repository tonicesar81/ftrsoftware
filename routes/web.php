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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('empresas', 'EmpresasController');
Route::get('empresas/edit/{id}', 'EmpresasController@edit');

Route::resource('shoppings', 'ShoppingsController');
Route::get('shoppings/edit/{id}', 'ShoppingsController@edit');
Route::post('shoppings/pesquisa', 'ShoppingsController@pesquisa');

Route::resource('users', 'UsersController');
Route::get('users/edit/{id}', 'UsersController@edit');
Route::post('users/pesquisa', 'UsersController@pesquisa');

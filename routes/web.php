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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::apiResource('codigos','CodigosController');
Route::apiResource('leads','LeadsController');
Route::apiResource('promos','PromosController');

Route::get('findEmail/', 'LeadsController@findEmail');
Route::post('recibo', 'ReciboController@store');
// Route::post('sendNotes', 'LeadsController@sendNotes');
Route::post('solution', 'SolucionController@calculationSolution');
// Route::post('solution', 'SolucionController@calculationSolution');

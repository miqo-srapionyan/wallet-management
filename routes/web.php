<?php

use Illuminate\Support\Facades\Auth;
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


Auth::routes(['verify' => true]);

Route::get('/', 'DashboardController@index')->name('/')->middleware('auth', 'verified');

Route::get('auth/facebook', 'Auth\FacebookController@redirectToFacebook');
Route::get('auth/facebook/callback', 'Auth\FacebookController@handleFacebookCallback');

Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');

Route::group(['middleware' => ['auth', 'verified']],function () {
    Route::get('/wallets', 'UserWalletController@get')->name('/wallets');
    Route::get('/wallets/{id}', 'UserWalletController@getById')->name('/wallets');
    Route::post('/wallets', 'UserWalletController@store')->name('/wallets');
    Route::patch('/wallets/{id}', 'UserWalletController@edit')->name('/wallets');
    Route::delete('/wallets/{id}', 'UserWalletController@delete')->name('/wallets');

    Route::get('/wallets/{id}/balance', 'UserWalletBalanceController@get')->name('/wallets/{id}/balance');
    Route::post('/wallets/{id}/balance', 'UserWalletBalanceController@store')->name('/wallets/{id}/balance');
});

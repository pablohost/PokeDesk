<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

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

Route::get('/refresh', [Controller::class, 'refresh']);
Route::get('/', [Controller::class, 'home']);
Route::get('/show/{offset}', [Controller::class, 'show']);
Route::get('/show_details/{pokemon}', [Controller::class, 'show_details']);
Route::get('/search/{pokemon}', [Controller::class, 'search']);

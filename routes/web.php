<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RowController;
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
    return view('upload_xml');
});

Route::post('/', [RowController::class, 'uploadXmlFile'])
    ->name('upload-xml');

Route::get('/row', [RowController::class, 'fetchRows'])
    ->name('row-list');

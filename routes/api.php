<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user',                 'UserController@index');
Route::get('/user/{user}',          'UserController@show');
Route::post('/user',                'UserController@store');
Route::post('/user/update/{user}',  'UserController@update');
Route::post('/login',               'UserController@login');
Route::post('/user/delete/{user}',  'UserController@destroy');

//Mahasiswa
Route::get  ('mahasiswa',                'MahasiswaController@index');
Route::get  ('mahasiswa/{mahasiswa}',    'MahasiswaController@cari');
Route::post ('mahasiswa',                'MahasiswaController@tambah');
Route::post ('mahasiswa/update/{id}',    'MahasiswaController@ubah');
Route::post ('mahasiswa/delete/{id}',    'MahasiswaController@hapus');

//Buku
Route::get  ('buku/gambar/{id}',  'BukuController@tampilGambar');
Route::get  ('buku',              'BukuController@index');
Route::post ('buku',              'BukuController@tambah');
Route::post ('buku/update/{id}',  'BukuController@edit');
Route::post ('buku/delete/{id}',  'BukuController@hapus');

//Detail Transaksi Buku
/*Route::get  ('dtBuku'               , 'DTBukuController@index');
Route::get  ('dtBuku/{id}'          , 'DTBukuController@tampilDetailBuku');
Route::post ('dtBuku'               , 'DTBukuController@tambah');
Route::post ('dtBuku/update'        , 'DTBukuController@edit');
Route::post ('dtBuku/delete'        , 'DTBukuController@hapus');*/

//Transaksi Buku
Route::get  ('transaksiBuku'              ,'TransaksiBukuController@index');
Route::post ('transaksiBuku'              ,'TransaksiBukuController@tambah');
/*Route::post ('transaksiBuku/update/{id}'  ,'TransaksiBukuController@edit');
Route::post ('transaksiBuku/delete/{id}'  ,'TransaksiBukuController@hapus');*/

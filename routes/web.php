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

Route::get('/', 'PencarianC@index')->name('pencarian');
Route::get('/tentang', function () {
    return view('tentang');
});

Route::get('/pencarian', 'PencarianC@searchKey');

Auth::routes();

// Route::get('/login', function () {
//     return view('admin.login');
// })->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/beranda', 'HomeController@adminHome')->name('beranda');
    Route::get('/profil_admin', 'ProfilAdminC@index')->name('profil');
    Route::post('/profil_admin/edit', 'ProfilAdminC@updateData')->name('edit_profil');
    Route::post('/profil_admin/edit_pass', 'ProfilAdminC@updatePass')->name('edit_password');
    Route::get('/lupa_pass', 'ProfilAdminC@gotoReset');

    Route::get('/daftar_pt', 'DataPtC@index')->name('daftar_pt');

    Route::get('/daftar_kategori', 'DataKategoriC@index')->name('daftar_kategori');

    Route::get('/daftar_jurnal', 'DataJurnalC@index')->name('daftar_jurnal');

    Route::get('/detail_jurnal/{kode}', 'DataJurnalC@indexDetail')->name('detail_jurnal');

    Route::get('/riwayat_pencarian', 'DataPencarianC@index')->name('riwayat_pencarian');

    Route::get('/pengaturan', 'ScrapingC@index')->name('pengaturan');
    Route::post('/cek_radio', 'ScrapingC@radioCheck');
    Route::post('/cek_jurnal', 'ScrapingC@radioCheckJurnal');
    Route::post('/pengaturan/scraping_jurnal', 'ScrapingC@scrapJurnal')->name('scraping_jurnal');
    Route::post('/pengaturan/scraping_jadwal', 'ScrapingC@scrapJadwal')->name('scraping_jadwal');
});

Route::middleware(['role: 1'])->group(function (){
    Route::get('/data_user', 'DataAdminC@index')->name('data_user');
    Route::post('/data_user/tambah', 'DataAdminC@create')->name('tambah_admin');
    Route::post('/data_user/edit/{kode}', 'DataAdminC@update')->name('edit_admin');
    Route::delete('/data_user/hapus/{kode}', 'DataAdminC@delete')->name('hapus_admin');

    Route::post('/daftar_pt/tambah', 'DataPtC@create')->name('pt_tambah');
    Route::post('/detail_pt/edit/{kode}', 'DataPtC@update');
    Route::delete('/detail_pt/hapus/{kode}', 'DataPtC@delete');

    Route::post('/daftar_kategori/tambah', 'DataKategoriC@create')->name('kategori_tambah');
    Route::post('/detail_kategori/edit/{kode}', 'DataKategoriC@update');
    Route::delete('/detail_kategori/hapus/{kode}/{total}', 'DataKategoriC@delete');

    Route::post('/daftar_jurnal/tambah', 'DataJurnalC@create')->name('jurnal_tambah');
    Route::delete('/daftar_jurnal/hapus/{kode}', 'DataJurnalC@delete');

    Route::post('/detail_jurnal/edit/{kode}', 'DataJurnalC@update');
    Route::post('/detail_jurnal/tambah/kat/{kode}', 'DataJurnalC@createJK');
    Route::post('/detail_jurnal/edit/kat/{kode}', 'DataJurnalC@updateJK');
    Route::delete('/detail_jurnal/hapus/kat/{kode}', 'DataJurnalC@deleteJK');
    
    Route::post('/detail_jurnal/tambah/jp/{kode}', 'DataJurnalC@createJP');
    Route::post('/detail_jurnal/edit/jp/{kode}', 'DataJurnalC@updateJP');
    Route::delete('/detail_jurnal/hapus/jp/{kode}', 'DataJurnalC@deleteJP');
});


Route::get('/lupa_password', 'Auth\ForgotPasswordController@index');

Route::post('/goto_reset', 'AkunC@gotoReset');
Route::get('/verif_token', 'AkunC@verifyToken')->name('verif_token');
Route::post('/reset_password/{email}', 'AkunC@resetPass');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

<?php
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\UMKMImportController;
use App\Http\Controllers\UMKMExportController;
use Illuminate\Support\Facades\Route;
use App\Models\Umkm;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/kategori/{categoryId}', [LandingController::class, 'showCategoryMap']);
Route::get('/landing-umkm', [LandingController::class, 'showUmkmList'])->name('landing.landingumkm');
Route::get('/detail-umkm/{id}', [LandingController::class, 'showUmkmDetail'])->name('landing.landingdetail');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
Route::post('/actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');
Route::get('/peta', [PetaController::class, 'index'])->name('peta');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-user', [UserController::class, 'index'])->name('user');
    Route::get('/create-user', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/user/{user}/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/data-umkm', [UmkmController::class, 'index'])->name('umkm');
    Route::get('/create-umkm', [UmkmController::class, 'create'])->name('umkm.create');
    Route::post('/umkm', [UmkmController::class, 'store'])->name('umkm.store');
    Route::get('/umkm/{umkm}/edit', [UmkmController::class, 'edit'])->name('umkm.edit');
    Route::patch('/umkm/{umkm}/update', [UmkmController::class, 'update'])->name('umkm.update');
    Route::delete('/umkm/{umkm}', [UmkmController::class, 'destroy'])->name('umkm.destroy');
    Route::get('/umkm/{umkm}', [UmkmController::class, 'show'])->name('umkm.show');
    Route::get('/kelurahan/{kecamatan_id}', [UmkmController::class, 'getKelurahan']);
   
    Route::get('/data-kecamatan', [KecamatanController::class, 'index'])->name('kecamatan');
    Route::get('/kecamatan/{kecamatanId}', [KecamatanController::class, 'showKecamatanMap']);
    Route::get('/create-kecamatan', [KecamatanController::class, 'create'])->name('kecamatan.create');
    Route::post('/kecamatan', [KecamatanController::class, 'store'])->name('kecamatan.store');
    Route::get('/kecamatan/{id}/edit', [KecamatanController::class, 'edit'])->name('kecamatan.edit');
    Route::patch('/kecamatan/{id}', [KecamatanController::class, 'update'])->name('kecamatan.update');

    Route::get('/data-kelurahan', [KelurahanController::class, 'index'])->name('kelurahan');
    Route::get('/kelurahan/{kelurahanId}', [KelurahanController::class, 'showKelurahanMap']);
    Route::get('/create-kelurahan', [KelurahanController::class, 'create'])->name('kelurahan.create');
    Route::post('/kelurahan', [KelurahanController::class, 'store'])->name('kelurahan.store');
    Route::get('/kelurahan/{id}/edit', [KelurahanController::class, 'edit'])->name('kelurahan.edit');
    Route::patch('/kelurahan/{id}', [KelurahanController::class, 'update'])->name('kelurahan.update');

    Route::delete('/kecamatan/{kecamatan}', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');
    Route::delete('/kelurahan/{kelurahan}', [KelurahanController::class, 'destroy'])->name('kelurahan.destroy');
    Route::post('/imports', [UMKMImportController::class, 'import'])->name('umkm.import');
    Route::get('/export/all', [UMKMExportController::class, 'exportAll'])->name('umkm.export.all');
    Route::get('/export/by-category', [UMKMExportController::class, 'exportByCategory'])->name('umkm.export.by.category');

    Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik');
    // Add more protected routes here
});

Route::get('/umkm/search', 'UmkmController@search')->name('umkm.search');

<?php
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LupapwController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UMKMImportController;
use App\Http\Controllers\UMKMExportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|A
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [LandingController::class, 'index']);
Route::get('/landing', [LandingController::class, 'index'])->name('landing');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/login',[LoginController::class, 'index'])->name('login');
Route::get('/lupapw',[LupapwController::class, 'index'])->name('lupapw');
Route::get('/peta', [PetaController::class, 'index'])->name('peta');

Route::get('/data-pengguna', [PenggunaController::class, 'index'])->name('pengguna');
Route::get('/create-pengguna', [PenggunaController::class, 'create'])->name('pengguna.create');
Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
Route::get('/pengguna/{pengguna}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
Route::patch('/pengguna/{pengguna}/update', [PenggunaController::class, 'update'])->name('pengguna.update');
Route::delete('/pengguna/{pengguna}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

Route::get('/data-umkm', [UmkmController::class, 'index'])->name('umkm');
Route::get('/create-umkm', [UmkmController::class, 'create'])->name('umkm.create');
Route::post('/umkm', [UmkmController::class, 'store'])->name('umkm.store');
Route::get('/umkm/{umkm}/edit', [UmkmController::class, 'edit'])->name('umkm.edit');
Route::patch('/umkm/{umkm}/update', [UmkmController::class, 'update'])->name('umkm.update');
Route::delete('/umkm/{umkm}', [UmkmController::class, 'destroy'])->name('umkm.destroy');
Route::get('/umkm/{umkm}', [UmkmController::class, 'show'])->name('umkm.show');

Route::get('/data-kategori', [KategoriController::class, 'index'])->name('kategori');
Route::get('/create-kategori', [KategoriController::class, 'create'])->name('kategori.create');
Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
Route::patch('/kategori/{kategori}/update', [KategoriController::class, 'update'])->name('kategori.update');
Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

Route::post('/umkm/import', [UMKMImportController::class, 'import'])->name('umkm.import');
Route::get('/export/all', [UMKMExportController::class, 'exportAll'])->name('umkm.export.all');
Route::get('/export/by-category', [UMKMExportController::class, 'exportByCategory'])->name('umkm.export.by.category');

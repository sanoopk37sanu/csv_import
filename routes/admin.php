<?php

use App\Http\Controllers\Admin\CsvImportController;
use App\Http\Controllers\Admin\DashbordController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

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


Route::name('admin.')->group(function () {
    Route::get('admin/login', [LoginController::class, 'login'])->name('login');
    Route::post('admin/do-login', [LoginController::class, 'doLogin'])->name('do.login');
    Route::middleware('auth:admin')->group(function () {
        Route::get('admin/csv_import', [CsvImportController::class, 'csv_import'])->name('csv.import');
        Route::post('admin/import-users', [CsvImportController::class, 'import'])->name('import-users');
        Route::get('admin/logout', [LoginController::class, 'logout'])->name('logout');
    });
});

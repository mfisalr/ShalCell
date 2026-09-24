<?php

use App\Http\Controllers\DebugController;
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

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('/debug',[DebugController::class,'index']);
Route::middleware(['auth', 'no.cache'])->group(function(){
Route::get('/dashboard',[\App\Http\Controllers\DashboardController::class,'index'])->name('dashboard');

Route::get('/customers',[\App\Http\Controllers\CustomerController::class,'index'])->name('customers.index');
Route::get('/saldo',[\App\Http\Controllers\CustomerController::class,'saldo'])->name('saldo.index');
Route::post('/saldo',[\App\Http\Controllers\CustomerController::class,'tambahSaldo'])->name('saldo.store');
Route::get('/saldo/export/excel',[\App\Http\Controllers\CustomerController::class,'exportSaldoExcel'])->name('saldo.export.excel');
Route::get('/saldo/export/pdf',[\App\Http\Controllers\CustomerController::class,'exportSaldoPdf'])->name('saldo.export.pdf');
Route::get('/customers/export/excel',[\App\Http\Controllers\CustomerController::class,'exportExcel'])->name('customers.export.excel');
Route::get('/customers/export/pdf',[\App\Http\Controllers\CustomerController::class,'exportPdf'])->name('customers.export.pdf');
Route::post('/customers',[\App\Http\Controllers\CustomerController::class,'store']);
Route::get('/customers/create',[\App\Http\Controllers\CustomerController::class,'create']);
Route::get('/customers/{id}/edit',[\App\Http\Controllers\CustomerController::class,'edit']);
Route::put('/customers/{id}',[\App\Http\Controllers\CustomerController::class,'update']);
Route::delete('/customers/{id}',[\App\Http\Controllers\CustomerController::class,'destroy']);

Route::get('/armada',[\App\Http\Controllers\ArmadaController::class,'index'])->name('armada.index');
Route::post('/armada',[\App\Http\Controllers\ArmadaController::class,'store']);
Route::get('/armada/create',[\App\Http\Controllers\ArmadaController::class,'create']);
Route::get('/armada/{id}/edit',[\App\Http\Controllers\ArmadaController::class,'edit']);
Route::put('/armada/{id}',[\App\Http\Controllers\ArmadaController::class,'update']);
Route::delete('/armada/{id}',[\App\Http\Controllers\ArmadaController::class,'destroy']);

Route::get('/orders/import', [\App\Http\Controllers\OrderImportController::class, 'create'])
    ->name('orders.import.create');
Route::post('/orders/import', [\App\Http\Controllers\OrderImportController::class, 'store'])
    ->name('orders.import.store');
Route::get('/rekap-data', [\App\Http\Controllers\RecapController::class, 'index'])
    ->name('recaps.index');
Route::get('/rekap-data/export/excel', [\App\Http\Controllers\RecapController::class, 'exportExcel'])
    ->name('recaps.export.excel');
Route::get('/rekap-data/export/pdf', [\App\Http\Controllers\RecapController::class, 'exportPdf'])
    ->name('recaps.export.pdf');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

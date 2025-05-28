<?php

use App\Http\Controllers\CertificadoParticipante;
use App\Http\Controllers\ComprovanteInscricao;
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
Route::get('/', function () { return redirect('/admin'); })->name('login');
/*Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('pdf/Comprovante/{id}',[ComprovanteInscricao::class, 'print'])->name('imprimirInscricao');
Route::get('pdf/CertificadoParticipante/{id}',[CertificadoParticipante::class, 'print'])->name('imprimirCertificadoParticipante');





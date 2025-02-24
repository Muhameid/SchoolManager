<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AuthenticationController;

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

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/login', [AuthenticationController::class, 'show'])->name('login');
        Route::post('/authenticate', [AuthenticationController::class, 'authenticate']);
        Route::get('/dashboard', [AuthenticationController::class, 'dashboard'])->name('dashboard')->middleware('auth');
        Route::get('/logout',[AuthenticationController::class,'logout'])->name('logout');
        Route::get('/tenants_liste',[TenantController::class,'listeEcoles'])->name('tenants_liste')->middleware('auth');
        Route::get('/ajout_tenant',[TenantController::class,'ajouterTenant'])->name('ajout_tenant')->middleware('auth');
        Route::post('/enregistrer_tenant', [TenantController::class, 'enregistrement']);
        Route::get('/supprimer_tenant/{id}',[TenantController::class,'suppression'])->name('suppression_tenant')->middleware('auth');


    });
}
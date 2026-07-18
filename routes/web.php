<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{CompanyController, InvitationController};

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});


Route::get('/company/s/{shortUrl}', [CompanyController::class, 'openShortUrl']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(CompanyController::class)->group(function () {
          Route::get('/dashboard', 'index')->name('home');
          Route::post('/company/store','store');
          Route::post('/company/store', 'store');
          Route::get('/company/short-url', 'getShortUrl')->name('company.short_url');
          Route::post('/company/{companyId}/generate-short-url', 'generateShortUrl')->name('company.generate-short-url');
    });

    Route::post('/company/{companyId}/invite', [InvitationController::class, 'index']);
});

  Route::get('/invite/{token}',[InvitationController::class, 'acceptInvitation'] )->name('invite.accept');

require __DIR__.'/settings.php';

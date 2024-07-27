<?php

use App\Http\Controllers\Web\RazorpayPaymentController;
use App\Http\Controllers\Web\RdSubscriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\SettingController;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
})->name('view.welcome');

Route::get('/optimize', function () {
    Artisan::call('optimize:clear');
    dd('Application optimized');
});

Route::get('/migrate', function () {
    Artisan::call('migrate');
    dd('Database migrated');
});

Route::get('/storage/link', function () {
    Artisan::call('storage:link');
    dd('Storage Linked');
});

Route::middleware(['guest'])->group(function () {

    Route::get('login', [AuthController::class, 'viewLogin'])
        ->name('web.view.login');
    Route::post('login', [AuthController::class, 'handleLogin'])
        ->name('web.handle.login');

    Route::get('register', [AuthController::class, 'viewRegister'])
        ->name('web.view.register');
    Route::post('register', [AuthController::class, 'handleRegister'])
        ->name('web.handle.register');

    Route::get('forgot-password', [AuthController::class, 'viewForgotPassword'])
        ->name('web.view.forgot.password');
    Route::post('forgot-password', [AuthController::class, 'handleForgotPassword'])
        ->name('web.handle.forgot.password');

    Route::get('reset-password/{token}', [AuthController::class, 'viewResetPassword'])
        ->name('web.view.reset.password');
    Route::post('reset-password/{token}', [AuthController::class, 'handleResetPassword'])
        ->name('web.handle.reset.password');
});

Route::middleware(['auth'])->group(function () {

    Route::post('logout', function () {
        Auth::logout();
        return redirect()->route('web.view.login');
    })->name('web.handle.logout');

    Route::get('dashboard', [DashboardController::class, 'viewDashboard'])
        ->name('web.view.dashboard');

    Route::prefix('setting')->controller(SettingController::class)->group(function () {
        Route::get('/', 'viewSetting')->name('web.view.setting');
        Route::get('/account-information', 'viewAccountSetting')->name('web.view.setting.account');
        Route::post('/account-information', 'handleAccountSetting')->name('web.handle.setting.account');
        Route::get('/update-password', 'viewPasswordSetting')->name('web.view.setting.password');
        Route::post('/update-password', 'handlePasswordSetting')->name('web.handle.setting.password');
    });

    Route::prefix('rd-subscription')->controller(RdSubscriptionController::class)->group(function () {
        Route::get('/plans', 'viewRdPlanList')->name('web.view.rdplan.list');
        Route::get('/list', 'viewRdSubscriptionList')->name('web.view.rdsubscription.list');
        Route::get('/create', 'viewRdSubscriptionCreate')->name('web.view.rdsubscription.create');
        Route::post('/create', 'handleRdSubscriptionCreate')->name('web.handle.rdsubscription.create');
        Route::get('/preview/{id}', 'viewRdSubscriptionPreview')->name('web.view.rdsubscription.preview');
        Route::get('/instalment/{rd_subscription_id}', 'viewRdInstallmentCreate')->name('web.view.rdinstallment.create');
        Route::post('/instalment', 'handleRdInstallmentCreate')->name('web.handle.rdinstallment.create');
        Route::get('/download/recipt/{id}', 'handleDownloadRecipt')->name('web.handle.rdsubscription.recipt.download');
    });
    
    Route::get('razorpay-payment', [RazorpayPaymentController::class, 'index']);
    Route::post('razorpay-payment', [RazorpayPaymentController::class, 'store'])->name('razorpay.payment.store');
});

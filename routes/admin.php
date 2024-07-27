<?php

use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RdPlanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminAccessController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\RdSubscriptionController;

Route::middleware(['guest:admin'])->group(function () {

    Route::get('login', [AuthController::class, 'viewLogin'])
        ->name('admin.view.login');
    Route::post('login', [AuthController::class, 'handleLogin'])
        ->name('admin.handle.login');

    Route::get('/forgot-password', [AuthController::class, 'viewForgotPassword'])
        ->name('admin.view.forgot.password');
    Route::post('/forgot-password', [AuthController::class, 'handleForgotPassword'])
        ->name('admin.handle.forgot.password');

    Route::get('/reset-password/{token}', [AuthController::class, 'viewResetPassword'])
        ->name('admin.view.reset.password');
    Route::post('/reset-password/{token}', [AuthController::class, 'handleResetPassword'])
        ->name('admin.handle.reset.password');
});


Route::middleware(['auth:admin'])->group(function () {

    Route::post('logout', function () {
        Auth::logout();
        return redirect()->route('admin.view.login');
    })->name('admin.handle.logout');

    Route::get('dashboard', [DashboardController::class, 'viewDashboard'])
        ->name('admin.view.dashboard');

    Route::prefix('admin-access')->controller(AdminAccessController::class)->group(function () {
        Route::get('/', 'viewAdminAccessList')->name('admin.view.admin.access.list');
        Route::get('/create', 'viewAdminAccessCreate')->name('admin.view.admin.access.create');
        Route::get('/update/{id}', 'viewAdminAccessUpdate')->name('admin.view.admin.access.update');
        Route::post('/create', 'handleAdminAccessCreate')->name('admin.handle.admin.access.create');
        Route::post('/update/{id}', 'handleAdminAccessUpdate')->name('admin.handle.admin.access.update');
        Route::put('/status', 'handleToggleAdminAccessStatus')->name('admin.handle.admin.access.status');
        Route::get('/delete/{id}', 'handleAdminAccessDelete')->name('admin.handle.admin.access.delete');
    });

    Route::prefix('rdplan')->controller(RdPlanController::class)->group(function () {
        Route::get('/', 'viewRdPlanList')->name('admin.view.rdplan.list');
        Route::get('/create', 'viewRdPlanCreate')->name('admin.view.rdplan.create');
        Route::get('/update/{id}', 'viewRdPlanUpdate')->name('admin.view.rdplan.update');
        Route::post('/create', 'handleRdPlanCreate')->name('admin.handle.rdplan.create');
        Route::post('/update/{id}', 'handleRdPlanUpdate')->name('admin.handle.rdplan.update');
        Route::put('/status', 'handleToggleRdPlanStatus')->name('admin.handle.rdplan.status');
        Route::get('/delete/{id}', 'handleRdPlanDelete')->name('admin.handle.rdplan.delete');
    });

    Route::prefix('rd-subscription')->controller(RdSubscriptionController::class)->group(function () {
        Route::get('/list', 'viewRdSubscriptionList')->name('admin.view.rdsubscription.list');
        Route::get('/preview/{id}', 'viewRdSubscriptionPreview')->name('admin.view.rdsubscription.preview');
        Route::get('/download/recipt/{id}', 'handleDownloadRecipt')->name('admin.handle.rdsubscription.recipt.download');
    });

    Route::prefix('setting')->controller(SettingController::class)->group(function () {
        Route::get('/', 'viewSetting')->name('admin.view.setting');
        Route::get('/account-information', 'viewAccountSetting')->name('admin.view.setting.account');
        Route::post('/account-information', 'handleAccountSetting')->name('admin.handle.setting.account');
        Route::get('/update-password', 'viewPasswordSetting')->name('admin.view.setting.password');
        Route::post('/update-password', 'handlePasswordSetting')->name('admin.handle.setting.password');

        Route::get('/roles-permissions', 'viewRolePermission')->name('admin.view.setting.role.permission');
        Route::get('/role/create', 'viewRoleCreate')->name('admin.view.setting.role.create');
        Route::post('/role/create', 'handleRoleCreate')->name('admin.handle.setting.role.create');
        Route::get('/role/update/{id}', 'viewRoleUpdate')->name('admin.view.setting.role.update');
        Route::post('/role/update/{id}', 'handleRoleUpdate')->name('admin.handle.setting.role.update');
        Route::get('/role/remove/permission/{role_id}/{permission_id}', 'handleRemovePermission')->name('admin.view.setting.role.remove.permission');
    });
});

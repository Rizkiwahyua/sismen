<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\DocumentCodeController;
use App\Http\Controllers\WorkUnitController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\DocumentDetailController;
use App\Exports\DocumentsExport;
use Maatwebsite\Excel\Facades\Excel;
// Halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// ============================
// Profile (Auth Only)
// ============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================
// Admin Routes
// ============================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {


        Route::prefix('rekap')
            ->name('rekap.')
            ->group(function () {

                Route::get('/', [RekapController::class, 'index'])
                    ->name('index');

                Route::get('/export', [RekapController::class, 'export'])
                    ->name('export');
                Route::resource('documents', DocumentController::class);
            });
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard'); // nama route = admin.dashboard

        Route::resource('documents', DocumentController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Document Categories
        Route::resource('document-categories', DocumentCategoryController::class)
            ->only(['index', 'create', 'store']);

        Route::get('documents/category/{slug}', [DocumentController::class, 'byCategory'])
            ->name('documents.byCategory');
        // Document Codes
        Route::resource('document-codes', DocumentCodeController::class)
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);


        Route::resource('user', AdminUserController::class);
        Route::resource('department', DepartmentController::class);

        // Users
        Route::resource('user', AdminUserController::class);
        Route::resource('department', DepartmentController::class);



        #recycle bin
        Route::get(
            'trash',
            [DocumentController::class, 'trash']
        )->name('documents.trash');

        Route::get(
            'trash/export',
            [DocumentController::class, 'exportTrash']
        )->name('documents.trash.export');

        Route::post(
            'documents/{id}/restore',
            [DocumentController::class, 'restore']
        )->name('documents.restore');

        Route::delete(
            'documents/{id}/force-delete',
            [DocumentController::class, 'forceDelete']
        )->name('documents.forceDelete');



        Route::get(
            'documents/export',
            [DocumentController::class, 'export']
        )->name('documents.export');
    });

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::put('/sub-detail/{id}', [DocumentDetailController::class, 'update'])->name('subdetail.update');
    Route::delete('/sub-detail/{id}', [DocumentDetailController::class, 'destroy'])->name('subdetail.destroy');
});

// Shared routes for viewing and downloading documents
Route::middleware(['auth', 'role:admin,user'])->group(function () {
    Route::get('/admin/documents/{document}/preview', [DocumentController::class, 'preview'])->name('admin.documents.preview');
    Route::get('/admin/documents/{document}/stream', [DocumentController::class, 'stream'])->name('admin.documents.stream');
    Route::get('/admin/documents/{id}/download', [DocumentController::class, 'download'])->name('admin.documents.download');
});

// ============================
// User Routes
// ============================
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->as('user.')
    ->group(function () {

        Route::get('/dashboard', [UserController::class, 'index'])
            ->name('dashboard'); // nama route = user.dashboard

        // Preview, Stream & Download for User Role
        Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
        Route::get('/documents/{document}/stream', [DocumentController::class, 'stream'])->name('documents.stream');
        Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
    });



require __DIR__ . '/auth.php';

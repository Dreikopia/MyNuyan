<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ComplaintCategoryController as AdminComplaintCategoryController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsCategoryController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\HotlineController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('is_admin')
    ->prefix('admin')          // now URL is /admin/news
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints');

        Route::patch('/complaints/{complaint}/review', [AdminComplaintController::class, 'review'])
            ->name('complaints.review');

        Route::patch('/complaints/{complaint}', [AdminComplaintController::class, 'update'])->name('complaints.update');

        Route::get('/categories', [AdminComplaintCategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [AdminComplaintCategoryController::class, 'store'])->name('categories.store');
        Route::patch('/categories/{category}', [AdminComplaintCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminComplaintCategoryController::class, 'destroy'])->name('categories.delete');

        Route::get('/news', [AdminNewsController::class, 'index'])->name('news');
        Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');

        Route::get('/news/category', [NewsCategoryController::class, 'index'])->name('news.categories');
    });

Route::middleware('is_resident')
    ->group(function () {

        Route::get('/', [HomepageController::class, 'index'])->name('home');

        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaint.index');
        Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaint.show');

        Route::get('/news', [NewsController::class, 'index'])->name('news');
        Route::get('/hotlines', [HotlineController::class, 'index'])->name('hotlines');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

        Route::middleware('auth')->group(function () {
            Route::prefix('complaints/create')->name('complaints.create.')->group(function () {
                Route::get('/category', [ComplaintController::class, 'createCategory'])->name('category');
                Route::post('/category', [ComplaintController::class, 'storeCategory'])->name('category.store');
                Route::get('/details', [ComplaintController::class, 'createDetails'])->name('details');
                Route::post('/details', [ComplaintController::class, 'store'])->name('store');
            });
        });
    });

require __DIR__.'/auth.php';

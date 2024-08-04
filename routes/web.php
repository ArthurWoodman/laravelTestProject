<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


// for React
Route::get('/articles', function () {
    return Inertia::render('Articles');
})->middleware(['auth', 'articles', 'verified'])->name('articles');
Route::get('articles/{id}', function () {
    return Inertia::render('Article');
})->middleware(['auth', 'article', 'verified'])->name('article.read');
Route::post('articles', [ArticleController::class, 'create'])->name('article.create');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('owner')->group(function () {
    Route::delete('articles/{id}', [ArticleController::class, 'delete'])->name('article.delete');
    Route::put('articles/{id}', [ArticleController::class, 'update'])->name('article.update');
    Route::get('articles/{id}/edit', function () {
        return Inertia::render('EditArticle');
    })->middleware(['auth', 'article', 'verified'])->name('article.edit');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/get-tokens', function() {
    $credentials = request()->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete'])->plainTextToken;
        $updateToken = $user->createToken('update-token', ['create', 'update'])->plainTextToken;
        $basicToken = $user->createToken('basic-token')->plainTextToken;

        return [
            'adminToken' => $adminToken,
            'updateToken' => $updateToken,
            'basicToken' => $basicToken,
        ];
    }
});

require __DIR__.'/auth.php';

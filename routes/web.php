<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TargetsMonitoredController;
use App\Http\Controllers\WorkspaceController;
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
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/targets', [TargetsMonitoredController::class, 'index'])->name('targets-monitored.index');

    Route::get('/workspaces', [WorkspaceController::class, 'index'])->name('workspace.index');
});

require __DIR__ . '/auth.php';
<?php

use Illuminate\Support\Facades\Route;
use TestVendor\UsersList\Http\Controllers\User\UserController;

Route::middleware(['web'])->group(function () {
    Route::get('/phonebook',  [UserController::class, 'index'])->name('phonebook.index');
    Route::post('/phonebook', [UserController::class, 'store'])->name('phonebook.store');
    Route::get('/phonebook/{user}/edit', [UserController::class, 'edit'])->name('phonebook.edit');
    Route::put('/phonebook/{user}', [UserController::class, 'update'])->name('phonebook.update');
    Route::delete('/phonebook/{user}', [UserController::class, 'destroy'])->name('phonebook.destroy');
});

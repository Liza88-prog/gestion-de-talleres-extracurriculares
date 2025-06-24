<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\RegisterUser;  // Importa el componente Livewire de registro

Route::view('/', 'livewire.auth.login')->name('login');

Route::view('/register', RegisterUser::class)->name('register'); // Ruta para el registro (Livewire component)

Route::get('/welcome', function () {
    return view('welcome');
})->name('home');

Route::get('/components', function () {
    return view('components.flyonui-components');
})->name('components');


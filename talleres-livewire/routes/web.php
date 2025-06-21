<?php

use Illuminate\Support\Facades\Route;

use App\Http\Livewire\Auth\Login;

Route::view('/login', 'livewire.auth.login')->name('login');

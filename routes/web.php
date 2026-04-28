<?php

use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/', function () {
    return view('login');
});
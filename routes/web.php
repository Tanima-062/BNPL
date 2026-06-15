<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard', [
        'purchases' => \App\Models\Purchase::with('installments')->latest()->get()
    ]);
});

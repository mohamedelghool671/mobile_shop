<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Cache;


Route::get('test',function() {
    $users = Cache::remember('products',30,function() {
        return Product::limit(5000)->get();
    });
    return view('welcome',get_defined_vars());
});

Route::get('/',function() {
     abort(403,'page not found');
});


Route::view('listen','welcome');
Broadcast::routes();
require __DIR__.'/auth.php';

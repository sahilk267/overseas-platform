<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/__test/models', function () {
    $user = User::with('profiles')->first();
    return $user ? $user->profiles : 'No user';
});

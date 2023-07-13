<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

Route::get('/tags', [LinkController::class, 'index']);
Route::get('/tags/{tagIds}', [LinkController::class, 'tags']);

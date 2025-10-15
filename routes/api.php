<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NodosController;

Route::get('/nodos',            [NodosController::class,'index']);
Route::get('/nodos/{id}',       [NodosController::class,'find']);
Route::post('/nodos',           [NodosController::class,'create']);
Route::delete('/nodos/{id}',    [NodosController::class,'delete']);



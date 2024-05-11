<?php

use App\Http\Controllers\Api\v1\AuthorTicketsController;
use App\Http\Controllers\Api\v1\TicketController;
use App\Http\Controllers\Api\v1\AuthorsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('tickets', TicketController::class)->except('update');
Route::middleware('auth:sanctum')->put('tickets/{ticket}', [TicketController::class, 'replace']);

Route::middleware('auth:sanctum')->apiResource('authors', AuthorsController::class);
Route::middleware('auth:sanctum')->apiResource('authors.tickets', AuthorTicketsController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

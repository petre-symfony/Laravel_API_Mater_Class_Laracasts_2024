<?php

use App\Http\Controllers\Api\v1\AuthorTicketsController;
use App\Http\Controllers\Api\v1\TicketController;
use App\Http\Controllers\Api\v1\AuthorsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
	Route::apiResource('tickets', TicketController::class)->except('update');
	Route::put('tickets/{ticket}', [TicketController::class, 'replace']);

	Route::apiResource('authors', AuthorsController::class);
	Route::apiResource('authors.tickets', AuthorTicketsController::class);

	Route::get('/user', function (Request $request) {
		return $request->user();
	});
});

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StoreTicketRequest;
use App\Http\Requests\Api\v1\UpdateTicketRequest;
use App\Http\Resources\v1\TicketResource;
use App\Models\Ticket;

class TicketController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		return TicketResource::collection(Ticket::all());
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreTicketRequest $request) {
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Ticket $ticket) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateTicketRequest $request, Ticket $ticket) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Ticket $ticket) {
		//
	}
}

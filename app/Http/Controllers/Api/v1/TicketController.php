<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Filters\v1\TicketFilter;
use App\Http\Requests\Api\v1\ReplaceTicketRequest;
use App\Http\Requests\Api\v1\StoreTicketRequest;
use App\Http\Requests\Api\v1\UpdateTicketRequest;
use App\Http\Resources\v1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class TicketController extends ApiController {
	/**
	 * Display a listing of the resource.
	 */
	public function index(TicketFilter $filters) {

		return TicketResource::collection(Ticket::filter($filters)->paginate());
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreTicketRequest $request) {
		try {

			Gate::authorize('store', Ticket::class);

			//To do create ticket
			return new TicketResource(Ticket::create($request->mappedAttributes()));
		} catch (AuthorizationException $exception) {
			return $this->error('You are not authorized to update that resource', 401);
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show($ticket_id) {
		try {
			$ticket = Ticket::findOrFail($ticket_id);

			if ($this->include('author')) {
				return new TicketResource($ticket->load('user'));
			}

			return new TicketResource($ticket);
		} catch (ModelNotFoundException $exception) {
			return  $this->error('Ticket Not Found', 404);
		}
	}

	public function replace(ReplaceTicketRequest $request, $ticket_id) {
		try {
			$ticket = Ticket::findOrFail($ticket_id);

			Gate::authorize('replace', $ticket);

			$ticket->update($request->mappedAttributes());

			return new TicketResource($ticket);
		} catch (ModelNotFoundException $exception) {
			return  $this->error('Ticket Not Found', 404);
		} 
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateTicketRequest $request, $ticket_id) {
		try {
			$ticket = Ticket::findOrFail($ticket_id);

			//policy
			Gate::authorize('update', $ticket);

			$ticket->update($request->mappedAttributes());

			return new TicketResource($ticket);
		} catch (ModelNotFoundException $exception) {
			return  $this->error('Ticket Not Found', 404);
		} catch (AuthorizationException $exception) {
			return $this->error('You are not authorized to update that resource', 401);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($ticket_id) {
		try {
			$ticket = Ticket::findOrFail($ticket_id);

			Gate::authorize('delete', $ticket);

			$ticket->delete();

			return $this->ok('Ticket successfully deleted');
		} catch (ModelNotFoundException $exception) {
			return  $this->error('Ticket Not Found', 404);
		}

	}

}

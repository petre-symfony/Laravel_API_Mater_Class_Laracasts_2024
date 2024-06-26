<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Filters\v1\TicketFilter;
use App\Http\Requests\Api\v1\ReplaceTicketRequest;
use App\Http\Requests\Api\v1\StoreTicketRequest;
use App\Http\Requests\Api\v1\UpdateTicketRequest;
use App\Http\Resources\v1\TicketResource;
use App\Models\Ticket;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class AuthorTicketsController extends ApiController {

	public function index($author_id, TicketFilter $filter) {
		return TicketResource::collection(
			Ticket::where('user_id', $author_id)->filter($filter)->paginate()
		);
	}

	public function store(StoreTicketRequest $request, $author_id) {

		try {

			Gate::authorize('store', Ticket::class);

			return new TicketResource(Ticket::create($request->mappedAttributes([
				'author' => 'user_id'
			])));
		} catch (AuthorizationException $exception) {
			return $this->error('You are not authorized to create that resource', 401);
		}
	}

	public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id) {
		try {
			$ticket = Ticket::where('id', $ticket_id)
				->where('user_id', $author_id)
				->firstOrFail();

			Gate::authorize('replace', $ticket);

			$ticket->update($request->mappedAttributes());

			return new TicketResource($ticket);

		} catch (ModelNotFoundException $exception) {
			return  $this->error('Ticket Not Found', 404);
		} catch (AuthorizationException $exception) {
			return $this->error('You are not authorized to update that resource', 401);
		}
	}

	public function update(UpdateTicketRequest $request, $author_id, $ticket_id) {
		try {
			$ticket = Ticket::where('id', $ticket_id)
				->where('user_id', $author_id)
				->firstOrFail();

			Gate::authorize('update', $ticket);

			$ticket->update($request->mappedAttributes());

			return new TicketResource($ticket);

			//ToDo ticket doesn't belong to user
		} catch (ModelNotFoundException $exception) {
			return  $this->error('Ticket Not Found', 404);
		} catch (AuthorizationException $exception) {
			return $this->error('You are not authorized to update that resource', 401);
		}
	}

	public function destroy($author_id, $ticket_id) {
		try {
			$ticket = Ticket::where('id', $ticket_id)
				->where('user_id', $author_id)
				->firstOrFail();

			Gate::authorize('delete', $ticket);

			$ticket->delete();
			return $this->ok('Ticket successfully deleted');

		} catch (ModelNotFoundException $exception) {
			return  $this->error('Ticket Not Found', 404);
		} catch (AuthorizationException $exception) {
			return $this->error('You are not authorized to update that resource', 401);
		}

	}
}

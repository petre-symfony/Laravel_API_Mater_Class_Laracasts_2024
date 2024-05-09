<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Filters\v1\TicketFilter;
use App\Http\Resources\v1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AuthorTicketsController extends Controller {
	public function index($author_id, TicketFilter $filter) {
		return TicketResource::collection(
			Ticket::where('user_id', $author_id)->filter($filter)->paginate()
		);
	}
}

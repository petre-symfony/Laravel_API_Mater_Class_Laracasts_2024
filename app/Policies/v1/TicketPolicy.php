<?php

namespace App\Policies\v1;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy {
	/**
	 * Create a new policy instance.
	 */
	public function __construct() {
		//
	}

	public function update(User $user, Ticket $ticket) {
		if ($user->isInRole('ticket:update')) {
			return true;
		}
		return $user->id === $ticket->user_id;
	}
}

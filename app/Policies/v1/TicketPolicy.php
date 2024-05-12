<?php

namespace App\Policies\v1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\v1\Abilities;

class TicketPolicy {
	/**
	 * Create a new policy instance.
	 */
	public function __construct() {
		//
	}

	public function update(User $user, Ticket $ticket) {
		if ($user->tokenCan(Abilities::UpdateTicket)) {
			return true;
		} elseif ($user->tokenCan(Abilities::UpdateOwnTicket)) {
			return $user->id === $ticket->user_id;
		}

		return false;
	}
}

<?php

namespace App\Http\Filters\v1;

class TicketFilter {
	public function status($value) {
		return $this->builder->where('status', $value);
	}
}
<?php

namespace App\Http\Filters\v1;

class TicketFilter extends QueryFilter {
	public function include($value) {
		return $this->builder->with($value);
	}

	public function status($value) {
		return $this->builder->where('status', $value);
	}
}
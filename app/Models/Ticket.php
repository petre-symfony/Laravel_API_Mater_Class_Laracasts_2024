<?php

namespace App\Models;

use App\Http\Filters\v1\TicketFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model {
	use HasFactory;

	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}

	public function scopeFilter(Builder $builder, TicketFilter $filters) {
		return $filters->apply($builder);
	}
}

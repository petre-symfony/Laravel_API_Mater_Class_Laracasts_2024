<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Gate;


class ApiController extends Controller {
	use ApiResponses;

	protected string $namespace = 'App\\Policies\\v1';

	public function __construct() {
		Gate::guessPolicyNamesUsing(fn(string $modelClass) => "{$this->namespace}\\" . class_basename($modelClass) . "Policy"
		);
	}

	public function include(string $relationship): bool {
		$param = request()->get('include');

		if (!isset($param)) {
			return false;
		}

		$includeValues = explode(',', strtolower($param));

		return in_array(strtolower($relationship), $includeValues);
	}
}

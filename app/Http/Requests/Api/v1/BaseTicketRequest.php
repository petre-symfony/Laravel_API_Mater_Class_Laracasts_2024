<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest {

	public function messages() {
		return [
			'data.attributes.status' => 'The data.attributes.status value is invalid. Please use A, C, H, or X'
		];
	}
}

<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array<mixed>|string>
	 */
	public function rules(): array {
		$rules = [
			'data.attributes.title' => 'required|string',
			'data.attributes.description' => 'required|string',
			'data.attributes.status' => 'required|string|in:A,C,H,X'
		];

		if ($this->routeIs('tickets.store')) {
			$rules['data.relationships.author.data.id'] = 'required|integer';
		}

		return $rules;
	}

	public function messages() {
		return [
			'data.attributes.status' => 'The data.attributes.status value is invalid. Please use A, C, H, or X'
		];
	}
}

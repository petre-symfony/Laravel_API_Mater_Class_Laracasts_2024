<?php

namespace App\Http\Requests\Api\v1;

use App\Permissions\v1\Abilities;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreTicketRequest extends BaseTicketRequest {
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
			'data.attributes.status' => 'required|string|in:A,C,H,X',
			'data.relationships.author.data.id' => 'required|integer|exists:users,id'
		];

		$user = $this->user();

		if ($user->tokenCan(Abilities::CreateOwnTicket)) {
			$rules['data.relationships.author.data.id'] .= '|size:' . $user->id;
		}

		return $rules;
	}

	protected function prepareForValidation() {
		if ($this->routeIs('authors.tickets.store')) {
			$this->merge([
				'data.relationships.author.data.id' => $this->route('author')
			]);
		}
	}
}

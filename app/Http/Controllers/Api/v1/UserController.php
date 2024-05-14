<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Filters\v1\AuthorFilter;
use App\Http\Requests\Api\v1\ReplaceUserRequest;
use App\Http\Requests\Api\v1\StoreUserRequest;
use App\Http\Requests\Api\v1\UpdateUserRequest;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class UserController extends ApiController {
	/**
	 * Display a listing of the resource.
	 */
	public function index(AuthorFilter $filters) {
		return UserResource::collection(
			User::filter($filters)->paginate()
		);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreUserRequest $request) {
		try {

			Gate::authorize('store', User::class);

			return new UserResource(User::create($request->mappedAttributes()));
		} catch (AuthorizationException $exception) {
			return $this->error('You are not authorized to create that resource', 401);
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(User $user) {
		if ($this->include('tickets')){
			return new UserResource($user->load('tickets'));
		}

		return new UserResource($user);
	}

	public function replace(ReplaceUserRequest $request, $user_id) {
		try {
			$user = User::findOrFail($user_id);

			Gate::authorize('replace', $user);

			$user->update($request->mappedAttributes());

			return new UserResource($user);
		} catch (ModelNotFoundException $exception) {
			return  $this->error('User Not Found', 404);
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateUserRequest $request,  $user_id) {
		try {
			$user = User::findOrFail($user_id);

			//policy
			Gate::authorize('update', $user);

			$user->update($request->mappedAttributes());

			return new UserResource($user);
		} catch (ModelNotFoundException $exception) {
			return  $this->error('Ticket Not Found', 404);
		} catch (AuthorizationException $exception) {
			return $this->error('You are not authorized to update that resource', 401);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($user_id) {
		try {
			$user = User::findOrFail($user_id);

			Gate::authorize('delete', $user);

			$user->delete();

			return $this->ok('User successfully deleted');
		} catch (ModelNotFoundException $exception) {
			return  $this->error('User Not Found', 404);
		}
	}
}

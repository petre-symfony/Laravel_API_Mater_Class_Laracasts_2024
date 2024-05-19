<?php

use App\Exceptions\Api\v1\ApiExceptions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
				then: function () {
					Route::middleware('api')
						->prefix('api/v1')
						->group(__DIR__.'/../routes/api_v1.php');
				}
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
    	$exceptions->render(function (Throwable $e, Request $request) use ($exceptions){
				$className = basename(get_class($e));
				$handlers = ApiExceptions::$handlers;

				$index = strrpos($className, '\\');

				if (array_key_exists($className, $handlers)) {
					$method = $handlers[$className];
					return ApiExceptions::$method($e, $request);
				}

				return response()->json([
					'errors' => [
						'type' => substr(get_class($e), $index + 1),
						'status' => intval($e->getCode()), // returns 0 if no code
						'message' =>  $e->getMessage()
					]
				]);
			});
    })->create();

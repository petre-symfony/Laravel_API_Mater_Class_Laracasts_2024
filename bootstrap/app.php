<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    	$exceptions->render(function (Throwable $e, Request $request) {
				$classname = basename(get_class($e));
				
				$index = strrpos($classname, '\\');

				return response()->json([
					'error' => [
						'type' => substr($classname, $index + 1),
						'status' => 0,
						'message' => $e->getMessage(),
						'source' => 'Line! ' . $e->getLine() . ': ' . $e->getFile()
					]
				]);
			});
    })->create();

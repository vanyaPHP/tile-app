<?php

namespace App\Exceptions;

use App\Exceptions\Handlers\ExceptionHandlerInterface;
use App\Exceptions\Handlers\ModelNotFoundExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ExceptionHandlerFactory
{
    public function handle(\Throwable $e): ?JsonResponse
    {
        /** @var ExceptionHandlerInterface|null $handler */
        $handler = match (true) {
            $e instanceof ModelNotFoundException => new ModelNotFoundExceptionHandler(),
        };

        return $handler?->handle($e);
    }
}

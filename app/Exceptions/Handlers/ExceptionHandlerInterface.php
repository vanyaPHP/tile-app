<?php

namespace App\Exceptions\Handlers;

use Illuminate\Http\JsonResponse;
use Throwable;

interface ExceptionHandlerInterface
{
    public function handle(Throwable $e): JsonResponse;
}

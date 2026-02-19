<?php

namespace App\Exceptions\Handlers;

use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use PHPUnit\Event\Code\Throwable;

class ModelNotFoundExceptionHandler implements ExceptionHandlerInterface
{
    protected array $modelMessages = [
        Order::class => 'Order with such id not found',
    ];

    public function handle(Throwable $e): JsonResponse
    {
        /** @var ModelNotFoundException $e */
        $message = $this->modelMessages[$e->getModel()] ?? $e->getMessage();

        return response()->json([
            'message' => $message,
        ], 404);
    }
}

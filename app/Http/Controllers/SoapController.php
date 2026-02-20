<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderData;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use stdClass;

class SoapController
{
    public function handle(Request $request)
    {
        $server = new \SoapServer(null, [
            'uri' => route('api.soap'),
        ]);

        $server->setClass(self::class);
        ob_start();
        try {
            $server->handle();
        } catch (\Exception $e) {
            Log::error('SOAP handling error: ' . $e->getMessage());
        }
        $soapResponse = ob_get_clean();

        return response($soapResponse, 200)->header('Content-Type', 'text/xml');
    }

    public function createOrder(stdClass|array $data, OrderService $service)
    {
        try {
            $data = (array) $data;
            $validatedData = CreateOrderData::from($data);

            $order = $service->createOrder($validatedData);

            return response()->json([
                'order_id' => $order->id,
                'message' => 'Order created successfully',
            ]);
        } catch (ValidationException $e) {
            $errors = implode(', ', $e->errors());
            throw new \SoapFault('Client', "Validation Failed: $errors");
        } catch (\Exception $e) {
            Log::error('SOAP Creation Error: ' . $e->getMessage());
            throw new \SoapFault('Server', 'Internal Server Error');
        }
    }
}
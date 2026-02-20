<?php

namespace Tests\Feature\Controllers;

use App\Enums\Order\OrderStatus;
use App\Enums\Order\PayType;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SoapControllerTest extends TestCase
{
    public function testOrderCreatedSuccessfully(): void
    {
        $xml = $this->buildSoapXml([
            'name' => 'SOAP Test Order',
            'client_name' => 'Alice',
            'client_surname' => 'Smith',
            'email' => 'alice.smith@example.com',
            'company_name' => 'Tile Corp',
            'delivery_city' => 'Rome',
            'delivery' => 120.50,
            'status' => OrderStatus::NEW,
            'pay_type' => PayType::CASHLESS
        ]);

        $response = $this->postSoap($xml);

        $response->assertSuccessful();

        $content = $response->getContent();
        dd($content);
        $this->assertStringContainsString('<message>Order created successfully</message>', $content);
        $this->assertStringContainsString('<order_id>', $content);

        preg_match('/<order_id>(\d+)<\/order_id>/', $content, $matches);
        $orderId = $matches[1];

        $this->assertDatabaseHas('orders', ['id' => $orderId]);
    }

    public function testOrderEmailValidationFails(): void
    {
        $xml = $this->buildSoapXml([
            'name' => 'Bad Email',
            'email' => 'this-is-not-an-email',
            'client_name' => 'Bob'
        ]);

        $response = $this->postSoap($xml);

        $response->assertSuccessful(200);
        $content = $response->getContent();
        $this->assertStringContainsString('SOAP-ENV:Fault', $content);
        $this->assertStringContainsString('Validation Failed', $content);
        $this->assertStringContainsString('email', $content);
    }

    public function testOrderStatusValidationFails(): void
    {
        $xml = $this->buildSoapXml([
            'name' => 'Invalid Status',
            'status' => 999
        ]);

        $response = $this->postSoap($xml);

        $response->assertStatus(200);
        $this->assertStringContainsString('SOAP-ENV:Fault', $response->getContent());
    }

    private function buildSoapXml(array $data): string
    {
        $dataFields = '';
        foreach ($data as $key => $value)
        {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            if ($value instanceof \BackedEnum) {
                $value = $value->value;
            }
            $dataFields .= "<{$key}>{$value}</{$key}>";
        }

        $createOrderRouteName = route('api.soap');

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
    <Body>
        <createOrder xmlns="$createOrderRouteName">
            <data>
                {$dataFields}
            </data>
        </createOrder>
    </Body>
</Envelope>
XML;
    }

    private function postSoap(string $xml): TestResponse
    {
        return $this->call(
            'POST',
            route('api.soap'),
            [],
            [],
            [],
            ['Content-Type' => 'text/xml; charset=UTF-8'],
            $xml
        );
    }
}
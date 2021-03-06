<?php

namespace OneId;

use OneId\Api\Client;


class OrderTest extends ApiTestCases
{

    public function testGetClient()
    {
        $order = new Order(1, 2, 3, 4, 5);
        $this->assertEquals(Client::defaultClient(), $order->getClient());

        $xClient = new Client();
        $order->bindClient($xClient);
        $this->assertEquals($xClient, $order->getClient());
    }

    public function test__construct_full()
    {
        $callbackURL = Utilities::generateGUID4();
        $description = Utilities::generateGUID4();
        $amount = Utilities::generateGUID4();
        $currency = Utilities::generateGUID4();
        $storeCode = Utilities::generateGUID4();
        $posCode = Utilities::generateGUID4();
        $orderReferenceId = Utilities::generateGUID4();
        $extraData = Utilities::generateGUID4();

        $order = new Order(
            $callbackURL,
            $description,
            $amount,
            $storeCode,
            $posCode,
            $orderReferenceId,
            $extraData,
            $currency,
        );

        $this->assertEquals($callbackURL, $order->callbackURL);
        $this->assertEquals($description, $order->description);
        $this->assertEquals($amount, $order->amount);
        $this->assertEquals($currency, $order->currency);
        $this->assertEquals($storeCode, $order->storeCode);
        $this->assertEquals($posCode, $order->posCode);
        $this->assertEquals($orderReferenceId, $order->orderReferenceId);
        $this->assertEquals($extraData, $order->extraData);
        $this->assertEquals("PURCHASE", $order->serviceType);
    }

    public function test__construct_part()
    {
        $callbackURL = Utilities::generateGUID4();
        $description = Utilities::generateGUID4();
        $amount = Utilities::generateGUID4();
        $storeCode = Utilities::generateGUID4();
        $posCode = Utilities::generateGUID4();

        $order = new Order(
            $callbackURL,
            $description,
            $amount,
            $storeCode,
            $posCode,
        );

        $this->assertEquals($callbackURL, $order->callbackURL);
        $this->assertEquals($description, $order->description);
        $this->assertEquals($amount, $order->amount);
        $this->assertEquals("VND", $order->currency);
        $this->assertEquals($storeCode, $order->storeCode);
        $this->assertEquals($posCode, $order->posCode);
        $this->assertNull($order->orderReferenceId);
        $this->assertNull($order->extraData);
    }

    public function testGetQRImage()
    {
        $callbackURL = "http://localhost";
        $description = "Order from Unittest";
        $amount = 10012;
        $storeCode = Utilities::generateGUID4();
        $posCode = Utilities::generateGUID4();

        $order = new Order(
            $callbackURL,
            $description,
            $amount,
            $storeCode,
            $posCode,
        );
        $fakeRes = [
            "qr_data"=> "iVBORw0KGgoAAAANSUhEUgAAAQAAAAEAAQMAAABmvDolAAAABlBMVEX///8AAABVwtN+AAACWUlEQVR42uyZzY0jIRSEq8WBIyEQComN+keTGKEQAkcOyLWqR8/akgMY2DUHq936Loj3qurR+KzP+i/XRpIPpB4ePjueIbfImvQ2LwQ0ADuSnhKAkBGvej8tBETWHXBk009NXrv27BMCCFdLPV7V3q4J6P+J5AqAFh8LAlZRQCDZ4xnoC/Becr8LWPP63HUWruxq3uu9uycHxvKZVEXhYG446pucTg9EvWbH0RLLjsRy0E5sHmBjYd0aXD3sLAAdA4GvlQAAkqDUw+nJclTtNWTyuy4FxFFR9WiQXUFGjBY7VgJUXLunfrLjVeF52WHdGjUDsLEgXMysQOqSz4ZN8eDgYoBCjlNqwLCrR3jRoTUANTY8VUDJle1NSGcA4Dk2EEx4tppkxSr7vyW3ALCxDNOt8mCpj1fqfM0PSwDEMLWNGZHMXp7gi+NUAFVRpkN9ZB4yt3i+DIPTAxJSmwOrCemYA2vyxdWlADqOilJDjAAU2OL3RMAmC7Vj2H3u8VJFaYx6VtQKAHxRReUxd8cTSUJqT1gH0DYDm6IaoMSsCUV9fj6nxV8HAPOsTJ4y1qumFu225ny61gKAxP1osOYg75EWLFgJUE+HSyFia8nZcBIfwXa9EACgK6opcMJGWl/Mg/EsuRkAG0CcEn3HVscU8ryoWQL4uQOxbD8mKEmQ57kUcN9HjewcH4H3Nr85E2D3k0rFDeZZbezsRe1XASyltaSjyW3cbzvmFQGTz42UE7xEtTmAn4raPUdCVshh+cJKwP0dx3bFu2UD/duHnqmBz/qsf2z9CQAA//87GIM+C12YZwAAAABJRU5ErkJggg==",
            "qr_code"=> "https://qr.id.vin/TX.20200217T00100018959",
            "order_id"=> "20200217T00100018959",
            "expiration"=> 1581914928
        ];
        $client = $this->createMockClient($fakeRes);
        $order->bindClient($client);

        $qr = $order->getQRData();

        $this->assertEquals($fakeRes['qr_data'], $qr->getImgData());
        $this->assertEquals($fakeRes['qr_code'], $qr->getHref());
        $this->assertEquals($fakeRes['order_id'], $qr->getOrderId());
        $this->assertEquals($fakeRes['expiration'], $qr->getExpiration());
    }
}

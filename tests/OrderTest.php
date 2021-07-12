<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

/**
 * Class OrderTest
 * @package App\Tests
 */
class OrderTest extends PantherTestCase
{
    /**
     *
     */
    public function createOrder(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('POSt', '/api/order', '{"user":1, "products":[{"id":1,"count":2},{"id":2,"count":2}]}');

        $this->assert('h1', 'Hello World');
    }
}

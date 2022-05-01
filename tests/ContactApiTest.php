<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ContactApiTest extends ApiTestCase
{
    public function testSomething(): void
    {
        $response = static::createClient()->request('GET', '/api/contacts');

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains([
            '@context' => '/api/contexts/Contact',
            '@id' => '/api/contacts',
            '@type' => 'hydra:Collection',
        ]);
    }
}

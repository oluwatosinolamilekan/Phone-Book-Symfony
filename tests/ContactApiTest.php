<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ContactApiTest extends ApiTestCase
{

    public function testContacts(): void
    {
        $response = static::createClient()->request('GET', '/api/contacts');

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains([
            '@context' => '/api/contexts/Contact',
            '@id' => '/api/contacts',
            '@type' => 'hydra:Collection',
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

    public function testCreateContact(): void
    {
        static::createClient()->request('POST', '/api/contacts',[
            'json' => [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'address' => '2 Frankrunct Germany',
                'phone_number' => '448233048339',
                'birthday' => '10/2/2021',
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);

        $this->assertResponseHeaderSame(
            'content-type', 'application/ld+json; charset=uft-8'
        );

        $this->assertJsonContains([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'address' => '2 Frankrunct Germany',
            'phone_number' => '448233048339',
            'birthday' => '10/2/2021',
        ]);
    }

//    public function testUpdateContact(): void
//    {
//        $client = static::createClient();
//
//        $client->request('PATCH', '/api/contacts/1', ['json' =>[
//            'first_name' => 'Yug'
//        ]]);
//
//        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains([
//            '@id' => '/api/contacts/1',
//            'first_name' => 'Yug'
//        ]);
//    }
}

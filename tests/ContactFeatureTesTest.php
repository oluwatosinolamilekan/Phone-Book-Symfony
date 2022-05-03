<?php

namespace App\Tests;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

class ContactFeatureTesTest extends TestCase
{
//    public function testContactCreate(): void
//    {
//        $data =  [
//            'first_name' => 'Jane',
//            'last_name' => 'Doe',
//            'birthday' => '10/17/2015',
//            'address' => 'Frankfurt am Main, Hessen',
//            'email' => 'jane@lillydoo.com',
//            'phone_number' => '+4930178299607'
//        ];
//
//        $contact = new Contact();
//        $contact->setFirstName($data['first_name']);
//        $contact->setLastName($data['last_name']);
//        $contact->setAddress($data['address']);
//        $contact->setPhoneNumber($data['phone_number']);
//        $contact->setBirthday($data['birthday']);
//        $contact->setEmail($data['email']);
//
//        $this->assertEquals($data['first_name'], $contact['first_name']);
//    }

    public function testCustomerFirstName(): void
    {
        $contact = new Contact();
        $contact->setFirstName($this->customerData()['first_name']);

        $this->assertEquals($this->customerData()['first_name'], $contact->getFirstName());
    }

    public function testCustomerLastName(): void
    {
        $contact = new Contact();
        $contact->setLastName($this->customerData()['last_name']);

        $this->assertEquals($this->customerData()['last_name'], $contact->getLastName());
    }

    public function testReturnsCustomerFullName()
    {
        $customer = new Contact();
        $customer->setFirstName($this->customerData()['first_name']);
        $customer->setLastName($this->customerData()['first_name']);

        $fullName = $customer->getFirstName() . '' . $customer->getLastName();

        $this->assertSame($fullName, $customer->getCustomerFullName());
    }

    private function customerData(): array
    {
        return [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'birthday' => '10/17/2015',
            'address' => 'Frankfurt am Main, Hessen',
            'email' => 'jane@lillydoo.com',
            'phone_number' => '+4930178299607'
        ];
    }
}

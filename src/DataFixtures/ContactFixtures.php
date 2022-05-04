<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $contact = new Contact();
         $contact->setFirstName($this->customerData()['first_name']);
         $contact->setLastName($this->customerData()['last_name']);
         $contact->setAddress($this->customerData()['address']);
         $contact->setEmail($this->customerData()['email']);
         $contact->setBirthday($this->customerData()['birthday']);
         $contact->setPhoneNumber($this->customerData()['phone_number']);

         $manager->persist($contact);
         $manager->flush();
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

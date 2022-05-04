<?php

namespace App\Controller\Resources;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContactResource
{
    public function action($contacts)
    {
        $r = [];
        if (is_array($contacts)){
            foreach ($contacts as $contact){
                $r[] =$this->attributes($contact);
            }
        }else{
            return $this->attributes($contacts);
        }
        return $r;
    }

    public function attributes($contact): Response
    {
        $data =  [
            'success' => 'true',
           'data' => [
               'first_name' => $contact->getId(),
               'last_name' => $contact->getFirstName(),
               'birthday' => $contact->getLastName(),
               'address' => $contact->getAddress(),
               'phone_number' => $contact->getPhoneNumber()
           ]
        ];
        return new JsonResponse($data, 200);

    }
}
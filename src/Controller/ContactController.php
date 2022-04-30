<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Factory\JsonResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    public function __construct(
        public EntityManagerInterface $em,
        public  ManagerRegistry $doctrine,
        private JsonResponseFactory $jsonResponseFactory
    ) {}

    #[Route('/contact', name: 'app_contact', methods: 'GET')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ContactController.php',
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods: 'POST')]
    public function store(Request $request): Response
    {
        $data = $request->toArray();
        $entityManager = $this->doctrine->getManager();
        try {
            $this->em->getConnection()->beginTransaction();
            $contact = new Contact();
            $contact->setFirstName($data['first_name']);
            $contact->setLastName($data['last_name']);
            $contact->setAddress($data['address']);
            $contact->setPhoneNumber($data['phone_number']);
            $contact->setBirthday($data['birthday']);
            $contact->setEmail($data['email']);

            // tell Doctrine you want to (eventually) save the Contact (no queries yet)
            $entityManager->persist($contact);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
            $this->em->getConnection()->commit();
            return $this->jsonResponseFactory->create($contact);
        }catch (Exception $exception){
            return $this->json($exception->getMessage());
        }
    }
}

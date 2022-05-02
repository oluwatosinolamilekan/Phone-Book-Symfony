<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Factory\JsonResponseFactory;
use App\Form\ContactTypeFormType;
use App\Repository\ContactRepository;
use App\Request\ContactRequest;
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
        public ContactRepository $contactRepository,
        private JsonResponseFactory $jsonResponseFactory
    ) {}

    #[Route('/contacts', name: 'contact', methods: 'GET')]
    public function index(Request $request = null): Response
    {
        try {
            $query = $request->query->get('name');
            if($query){
                $contacts = $this->contactRepository->search($query);
            }else{
                $contacts = $this->contactRepository->getAllContacts();
            }
            return $this->json($contacts);
        }catch (Exception $exception){
            return $this->json($exception->getMessage());
        }
    }

    #[Route('/contact', name: 'store_contact', methods: 'POST')]
    public function store(ContactRequest $request): Response
    {
        $data = $request->validate();
//        $data = $request->toArray();
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

            $entityManager->persist($contact);

            $entityManager->flush();
            $this->em->getConnection()->commit();
            return $this->jsonResponseFactory->create($contact, 201);
        }catch (Exception $exception){
            return $this->json($exception->getMessage());
        }
    }

    #[Route('/contact/edit/{id}', name: 'edit_contact', methods: 'PATCH')]
    public function edit(Request $request, int $id): Response
    {
        $data = $request->toArray();
        $entityManager = $this->doctrine->getManager();
        try{
            $contact = $this->em->getRepository(Contact::class)->find($id);
            if(!$contact){
                throw $this->createNotFoundException(
                    'The contact cannot be found '.$id
                );
            }
            $contact->setFirstName($data['first_name']);
            $contact->setLastName($data['last_name']);
            $contact->setAddress($data['address']);
            $contact->setPhoneNumber($data['phone_number']);
            $contact->setBirthday($data['birthday']);
            $contact->setEmail($data['email']);
            $entityManager->persist($contact);

            $entityManager->flush();
            $this->em->getConnection()->commit();
            return $this->jsonResponseFactory->create($contact, 200);
        }catch (Exception $exception){
            return $this->json($exception->getMessage());
        }
    }

    #[Route('/contact/delete/{id}', name: 'delete_contact', methods: 'DELETE')]
    public function delete(int $id): Response
    {
        try {
            $contact = $this->em->getRepository(Contact::class)->find($id);
            if (!$contact){
                return $this->json('The contact cannot be found '. $id, 404);
            }
            $this->em->remove($contact);
            $this->em->flush();
            return $this->json('Deleted a contact successfully with id ' . $id);
        }catch (Exception $exception){
            return $this->json($exception->getMessage());
        }
    }
}

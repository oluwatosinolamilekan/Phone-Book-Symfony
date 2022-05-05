<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Controller\Response\CustomerResponse;
use App\Factory\JsonResponseFactory;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Request\ContactRequest;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    public function __construct(
        public EntityManagerInterface $em,
        public  ManagerRegistry $doctrine,
        public ContactRepository $contactRepository,
        private JsonResponseFactory $jsonResponseFactory,
        public CustomerResponse $customerResponse
    ) {}

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/contact/index', name: 'contact', methods: 'GET')]
    public function index(Request $request): Response
    {
        try {
            $query = $request->query->get('name');
            if($query ){
                $contacts = $this->contactRepository->search($query);
            }else{
                $contacts = $this->contactRepository->getAllContacts();
            }
            return $this->json($contacts);
        }catch (Exception $exception){
            return $this->customerResponse->errorResource($exception->getMessage());
        }
    }

    /**
     * store the given customer.
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     * @throws Exception
     */
    #[Route('/contact/store', name: 'store_contact', methods: 'POST')]
    public function store(Request $request, FileUploader $fileUploader): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, null, [
            'csrf_protection' => false,
        ]);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            $data = [
                'title' => 'Validation Error',
                'errors' => $errors
            ];
            return new JsonResponse($data, 400);
        }
        $picture = $request->files->get('picture');

        $entityManager = $this->doctrine->getManager();
        try {
            $data = $request->request->all();
            if ($picture) {
                $FileName = $fileUploader->upload($picture);
                $contact->setPicture($FileName);
            }
            $this->extracted($contact, $data, $entityManager);
            return $this->jsonResponseFactory->create($contact, 201);
        }catch (Exception $exception){
            return $this->customerResponse->errorResource($exception->getMessage());
        }
    }

    /**
     * Update the given customer.
     * @param ContactRequest $request
     * @param int $id
     * @return Response
     */
    #[Route('/contact/edit/{id}', name: 'edit_contact', methods: 'PATCH')]
    public function edit(ContactRequest $request, int $id): Response
    {
        $data = $request->getRequest()->toArray();
        $entityManager = $this->doctrine->getManager();
        try{
            $contact = $this->em->getRepository(Contact::class)->find($id);
            if(!$contact){
                return $this->customerResponse->notFound('The contact cannot be found '. $id);
            }
            $this->extracted($contact, $data, $entityManager);
            return $this->jsonResponseFactory->create($contact, 200);
        }catch (Exception $exception){
            return $this->customerResponse->errorResource($exception->getMessage());
        }
    }

    /**
     * Update picture of only contact
     * @param Request $request
     * @param int $id
     * @param FileUploader $fileUploader
     * @return Response
     * @throws Exception
     */
    #[Route('/contact/edit/picture/{id}', name: 'edit_contact', methods: 'POST')]
    public function editPicture(Request $request, int $id, FileUploader $fileUploader): Response
    {
        $contact = $this->em->getRepository(Contact::class)->find($id);
        if(!$contact){
            return $this->customerResponse->notFound('The contact cannot be found '. $id);
        }
            $entityManager = $this->doctrine->getManager();
        try{
            if ($picture = $request->files->get('picture')) {
                $FileName = $fileUploader->editContactUpload($picture, $contact);
                $contact->setPicture($FileName);
            }else{
                return $this->json([
                    'status' => 'failed',
                    'message' => 'Picture required'
                ], 422);
            }
            $data = $request->toArray();
            $this->extracted($contact, $data, $entityManager);
            return $this->jsonResponseFactory->create($contact, 200);
        }catch (Exception $exception){
            return $this->customerResponse->errorResource($exception->getMessage());
        }
    }

    /**
     * delete the given customer.
     * @param int $id
     * @return Response
     */
    #[Route('/contact/delete/{id}', name: 'delete_contact', methods: 'DELETE')]
    public function delete(int $id): Response
    {
        try {
            $contact = $this->em->getRepository(Contact::class)->find($id);
            if (!$contact){
                return $this->customerResponse->notFound('The contact cannot be found '. $id);
            }
            $this->em->remove($contact);
            $this->em->flush();
            return $this->json('Deleted a contact successfully with id ' . $id);
        }catch (Exception $exception){
            return $this->customerResponse->errorResource($exception->getMessage());
        }
    }

    /**
     * @param mixed $contact
     * @param array $data
     * @param ObjectManager $entityManager
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function extracted(mixed $contact, array $data, ObjectManager $entityManager): void
    {
        $this->em->getConnection()->beginTransaction();
        $contact->setFirstName($data['first_name'] ?? $contact->getFirstName());
        $contact->setLastName($data['last_name'] ?? $contact->getLastName());
        $contact->setAddress($data['address'] ?? $contact->getAddress());
        $contact->setPhoneNumber($data['phone_number'] ?? $contact->getPhoneNumber());
        $contact->setBirthday($data['birthday'] ?? $contact->getBirthday());
        $contact->setEmail($data['email'] ?? $contact->getEmail());
        $entityManager->persist($contact);

        $entityManager->flush();
        $this->em->getConnection()->commit();
    }


    /**
     * validating form request.
     * @return array
     */
    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}

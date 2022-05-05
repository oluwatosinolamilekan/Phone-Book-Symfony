<?php

namespace App\Controller\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomerResponse extends AbstractController
{
    public function deleteResource(): Response
    {
        return $this->json([
            'status' => 'success',
            'message' => 'deleted successfully'
        ], 204);
    }

    public function errorResource($error): Response
    {
        return $this->json([
            'status' => 'failed',
            'message' => $error
        ], 500);
    }

    public function notFound($message = null): Response
    {
        return $this->json([
            'status' => 'success',
            'message' => $message
        ], 404);
    }

    public function validationError($validator): JsonResponse
    {
        $errors = $validator->validate($this);
        $messages = ['message' => 'validation_failed', 'errors' => []];

        /** @var \Symfony\Component\Validator\ConstraintViolation  */
        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'message' => $message->getMessage(),
            ];
        }
        if (count($messages['errors']) > 0) {
            $response = new JsonResponse($messages, 400);
            $response->send();

            exit;
        }
    }
}
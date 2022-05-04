<?php

namespace App\Controller\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomerResponse extends AbstractController
{
    protected function deleteResource(): Response
    {
        return $this->json([
            'status' => 'success',
            'message' => 'deleted successfully'
        ], 204);
    }

    protected function errorResource($error): Response
    {
        return $this->json([
            'status' => 'success',
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
}
<?php

namespace App\Serializer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MyCustomProblemNormalizer implements NormalizerInterface
{
    public function normalize($exception, string $format = null, array $context = [])
    {
        return [
            'message' => $exception->getMessage(),
            'code' => $exception->getStatusCode(),
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof FlattenException;
    }
}
<?php

namespace App\Serializer;

use App\Entity\Contact;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ErrorNormalizer
{

    public function __construct(
        private  UrlGeneratorInterface $router,
        private ObjectNormalizer $normalizer
    )
    {}

    public function normalize($contact, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($contact, $format, $context);

        // Here, add, edit, or delete some data:
        $data['href']['self'] = $this->router->generate('topic_show', [
            'id' => $contact->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Contact;
    }
}
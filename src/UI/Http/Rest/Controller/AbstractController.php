<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Domain\Shared\Entity\PaginationProperties;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractController
{
    protected Request $request;

    public function __construct(
        RequestStack $requestStack,
        protected SerializerInterface $serializer,
        protected NormalizerInterface $normalizer,
    ) {
        $request = $requestStack->getCurrentRequest();
        if (! $request instanceof Request) return;
        $this->request = $request;
    }

    protected function generatePaginationPropertiesByQueryParams(): PaginationProperties
    {
        return new PaginationProperties(
            intval($this->request->query->get('page')),
            intval($this->request->query->get('page_size')),
            $this->request->query->get('sort_by'),
            $this->request->query->get('sort_order'),
        );
    }
}

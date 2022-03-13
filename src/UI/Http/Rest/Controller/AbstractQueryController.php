<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Application\Shared\Query\QueryBusInterface;
use App\Application\Shared\Query\QueryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractQueryController extends AbstractController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        RequestStack $requestStack,
        SerializerInterface $serializer,
        NormalizerInterface $normalizer,
    ) {
        parent::__construct($requestStack, $serializer, $normalizer);
    }

    protected function dispatch(QueryInterface $query)
    {
        return $this->queryBus->dispatch($query);
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Shared\Query\QueryBusInterface;
use App\Application\Shared\Query\QueryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessengerQueryBus implements QueryBusInterface
{
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function dispatch(QueryInterface $query)
    {
        $queryDispatched = $this->queryBus->dispatch($query)->last(HandledStamp::class);
        if (null === $queryDispatched) {
            return null;
        }

        return $queryDispatched->getResult();
    }
}

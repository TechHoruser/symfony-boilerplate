<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessengerCommandBus implements CommandBusInterface
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function dispatch(CommandInterface $command)
    {
        $queryDispatched = $this->commandBus->dispatch($command)->last(HandledStamp::class);
        if (null === $queryDispatched) {
            return null;
        }

        return $queryDispatched->getResult();
    }
}

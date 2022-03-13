<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Shared\Event\EventBusInterface;
use App\Application\Shared\Event\EventMessageInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerEventBus implements EventBusInterface
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function dispatch(EventMessageInterface $message): void
    {
        $this->eventBus->dispatch($message);
    }
}

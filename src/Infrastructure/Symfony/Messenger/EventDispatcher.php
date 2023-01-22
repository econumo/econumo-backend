<?php

declare(strict_types=1);


namespace App\Infrastructure\Symfony\Messenger;

use App\Domain\Service\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

class EventDispatcher implements EventDispatcherInterface
{
    private MessageBusInterface $messageBus;

    private LoggerInterface $logger;

    public function __construct(
        MessageBusInterface $messageBus,
        LoggerInterface $logger
    ) {
        $this->messageBus = $messageBus;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event)
    {
        try {
            return $this->dispatchEvent($event);
        } catch (Throwable $throwable) {
            $this->logger->error(sprintf('Error dispatch event %s', get_class($event)));
            throw $throwable;
        }
    }

    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    /**
     * @inheritDoc
     */
    public function dispatchDelayed(object $event, int $delay): void
    {
        $this->messageBus->dispatch($event, [new DelayStamp($delay * 1000)]);
    }

    protected function dispatchEvent(object $event)
    {
        $envelope = $this->messageBus->dispatch($event);
        /** @var HandledStamp[] $handledStamps */
        $handledStamps = $envelope->all(HandledStamp::class);

        if ($handledStamps === []) {
            return null;
        }

        return $handledStamps[0]->getResult();
    }
}

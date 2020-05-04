<?php

namespace App\Infrastructure\JWT;

use App\Application\User\AuthTokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\HeaderAwareJWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTEncodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as ContractsEventDispatcherInterface;

class JWTTokenManager extends JWTManager implements AuthTokenInterface
{
    public function create(UserInterface $user)
    {
        $payload = $this->preparePayload($user);
        $this->addUserIdentityToPayload($user, $payload);

        $jwtCreatedEvent = new JWTCreatedEvent($payload, $user);
        if ($this->dispatcher instanceof ContractsEventDispatcherInterface) {
            $this->dispatcher->dispatch($jwtCreatedEvent, Events::JWT_CREATED);
        } else {
            $this->dispatcher->dispatch(Events::JWT_CREATED, $jwtCreatedEvent);
        }

        if ($this->jwtEncoder instanceof HeaderAwareJWTEncoderInterface) {
            $jwtString = $this->jwtEncoder->encode($jwtCreatedEvent->getData(), $jwtCreatedEvent->getHeader());
        } else {
            $jwtString = $this->jwtEncoder->encode($jwtCreatedEvent->getData());
        }

        $jwtEncodedEvent = new JWTEncodedEvent($jwtString);

        if ($this->dispatcher instanceof ContractsEventDispatcherInterface) {
            $this->dispatcher->dispatch($jwtEncodedEvent, Events::JWT_ENCODED);
        } else {
            $this->dispatcher->dispatch(Events::JWT_ENCODED, $jwtEncodedEvent);
        }

        return $jwtString;
    }

    private function preparePayload(UserInterface $user): array
    {
        return [
            'roles' => $user->getRoles(),
            'budgetId' => 'budget_1'
        ];
    }
}

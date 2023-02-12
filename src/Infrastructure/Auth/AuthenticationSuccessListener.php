<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use App\Application\User\Assembler\CurrentUserToDtoResultAssembler;
use App\Domain\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    public function __construct(private readonly CurrentUserToDtoResultAssembler $currentUserToDtoResultAssembler)
    {
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }
        $data['user'] = $this->currentUserToDtoResultAssembler->assemble($user);
        $event->setData($data);
    }
}

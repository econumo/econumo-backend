<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Mailer;

use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserPasswordRequestRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\EmailServiceInterface;
use App\Domain\Service\Translation\TranslationServiceInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as EmailContainer;

readonly class EmailService implements EmailServiceInterface
{
    public function __construct(
        private string $fromEmail,
        private string $replyToEmail,
        private MailerInterface $mailer,
        private UserRepositoryInterface $userRepository,
        private UserPasswordRequestRepositoryInterface $userPasswordRequestRepository,
        private TranslationServiceInterface $translationService
    ) {
    }


    public function sendResetPasswordConfirmationCode(Email $recipient, Id $userId): void
    {
        $user = $this->userRepository->get($userId);
        $passwordRequest = $this->userPasswordRequestRepository->getByUser($userId);
        $this->translationService->trans('email.reset_password_confirmation_code.subject');
        $container = (new EmailContainer())
            ->to($recipient->getValue())
            ->subject($this->translationService->trans('email.reset_password_confirmation_code.subject'))
            ->text(
                $this->translationService->trans('email.reset_password_confirmation_code.body', [
                    '{{name}}' => $user->getName(),
                    '{{code}}' => $passwordRequest->getCode()->getValue(),
                ])
            );
        $this->send($container);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function send(EmailContainer $container): void
    {
        if (empty($this->fromEmail)) {
            return;
        }

        $container->from($this->fromEmail);
        if (!empty($this->replyToEmail)) {
            $container->replyTo($this->replyToEmail);
        }
        $this->mailer->send($container);
    }
}

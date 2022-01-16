<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\Invite\Validation;

use App\Domain\Entity\ValueObject\AccountUserRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class GenerateInviteV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('accountId', TextType::class, [
            'constraints' => [new NotBlank(), new Uuid()],
        ])->add('recipientUsername', TextType::class, [
            'constraints' => [new NotBlank(), new Email()],
        ])->add('role', ChoiceType::class, [
            'constraints' => [new NotBlank()],
            'choices' => [
                AccountUserRole::MAPPING[AccountUserRole::ADMIN],
                AccountUserRole::MAPPING[AccountUserRole::USER],
                AccountUserRole::MAPPING[AccountUserRole::GUEST],
            ]
        ]);
    }
}

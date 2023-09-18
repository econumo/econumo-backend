<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Budget\SharedAccess\Validation;

use App\Domain\Entity\ValueObject\UserRole;
use App\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class GrantSharedAccessV1Form extends AbstractType
{
    public function __construct(private readonly ValueObjectValidationFactoryInterface $valueObjectValidationFactory)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('planId', TextType::class, [
            'constraints' => [new NotBlank(), new Uuid()],
        ])->add('userId', TextType::class, [
            'constraints' => [new NotBlank(), new Uuid()],
        ])->add('role', TextType::class, [
            'constraints' => [new NotBlank(), $this->valueObjectValidationFactory->create(UserRole::class)],
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Connection\AccountAccess\Validation;

use App\Domain\Entity\ValueObject\AccountUserRole;
use App\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class SetAccountAccessV1Form extends AbstractType
{
    private ValueObjectValidationFactoryInterface $valueObjectValidationFactory;

    public function __construct(ValueObjectValidationFactoryInterface $valueObjectValidationFactory)
    {
        $this->valueObjectValidationFactory = $valueObjectValidationFactory;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('accountId', TextType::class, [
            'constraints' => [new NotBlank(), new Uuid()],
        ])->add('userId', TextType::class, [
            'constraints' => [new NotBlank(), new Uuid()],
        ])->add('role', TextType::class, [
            'constraints' => [new NotBlank(), $this->valueObjectValidationFactory->create(AccountUserRole::class)],
        ]);
    }
}

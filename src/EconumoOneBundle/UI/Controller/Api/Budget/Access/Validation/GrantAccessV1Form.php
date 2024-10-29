<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\UI\Controller\Api\Budget\Access\Validation;

use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetUserRole;
use App\EconumoOneBundle\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class GrantAccessV1Form extends AbstractType
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
        $builder
            ->add('budgetId', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid()],
            ])
            ->add('userId', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid()],
            ])
            ->add('role', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    $this->valueObjectValidationFactory->create(BudgetUserRole::class)
                ],
            ]);
    }
}
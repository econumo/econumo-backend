<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Controller\Api\Budget\Limit\Validation;

use App\EconumoBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoBundle\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class SetLimitV1Form extends AbstractType
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
                'constraints' => [
                    new NotBlank(),
                    new Uuid()
                ],
            ])
            ->add('elementId', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Uuid()
                ],
            ])
            ->add('period', TextType::class, [
                'constraints' => [new DateTime("Y-m-d")]
            ])
            ->add('amount', NumberType::class, [
                'constraints' => [],
            ]);
    }
}
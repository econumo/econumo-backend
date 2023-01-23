<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Category\Category\Validation;

use App\Domain\Entity\ValueObject\CategoryName;
use App\Infrastructure\Symfony\Form\Constraints\OperationId;
use App\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class CreateCategoryV1Form extends AbstractType
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
            ->add('id', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid(), new OperationId()],
            ])
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => CategoryName::MAX_LENGTH, 'min' => CategoryName::MIN_LENGTH]),
                    $this->valueObjectValidationFactory->create(CategoryName::class)
                ],
            ])
            ->add('type', ChoiceType::class, [
                'constraints' => [new NotBlank()],
                'choices' => ['expense', 'income']
            ])->add('accountId', TextType::class, [
                'constraints' => [new Uuid()],
            ])->add('icon', TextType::class, [
                'constraints' => [],
            ]);
    }
}

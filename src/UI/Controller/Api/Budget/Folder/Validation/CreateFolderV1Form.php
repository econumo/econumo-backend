<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Budget\Folder\Validation;

use App\Domain\Entity\ValueObject\PlanFolderName;
use App\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class CreateFolderV1Form extends AbstractType
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
            ->add('planId', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid()],
            ])
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => PlanFolderName::MAX_LENGTH, 'min' => PlanFolderName::MIN_LENGTH]),
                    $this->valueObjectValidationFactory->create(PlanFolderName::class)
                ],
            ]);
    }
}

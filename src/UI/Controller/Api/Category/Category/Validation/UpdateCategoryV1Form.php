<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Category\Category\Validation;

use App\Infrastructure\Symfony\Form\Constraints\OperationId;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class UpdateCategoryV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid(), new OperationId()],
            ])
            ->add('name', TextType::class, [
                'constraints' => [new NotBlank(), new Length(['max' => 16])],
            ])->add('icon', TextType::class, [
                'constraints' => [new NotBlank(), new Length(['min' => 2])],
            ])->add('isArchived', ChoiceType::class, [
                'constraints' => [new NotBlank()],
                'choices' => [0, 1]
            ]);
    }
}

<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Tag\Tag\Validation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class UpdateTagV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', TextType::class, [
            'constraints' => [new NotBlank(), new Uuid()],
        ])->add('name', TextType::class, [
            'constraints' => [new NotBlank()],
        ])->add('isArchived', ChoiceType::class, [
            'constraints' => [new NotBlank()],
            'choices' => [0, 1]
        ]);
    }
}

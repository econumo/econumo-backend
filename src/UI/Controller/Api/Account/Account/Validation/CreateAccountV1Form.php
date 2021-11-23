<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\Account\Validation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class CreateAccountV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid()],
            ])
            ->add('name', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('currencyId', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid()],
            ])
            ->add('balance', NumberType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('icon', TextType::class, [
                'constraints' => [new NotBlank()],
            ]);
    }
}

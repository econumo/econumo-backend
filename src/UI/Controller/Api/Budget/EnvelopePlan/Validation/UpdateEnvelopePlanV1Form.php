<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Budget\EnvelopePlan\Validation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class UpdateEnvelopePlanV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('envelopeId', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid()],
            ])
            ->add('period', TextType::class, [
                'constraints' => [new NotBlank(), new DateTime("Y-m-d H:i:s")],
            ])
            ->add('amount', TextType::class, [
                'constraints' => [new NotBlank(), new Type("numeric")],
            ]);
    }
}

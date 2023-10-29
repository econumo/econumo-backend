<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Budget\Envelope\Validation;

use App\Domain\Entity\ValueObject\EnvelopeName;
use App\Domain\Entity\ValueObject\EnvelopeType;
use App\Domain\Entity\ValueObject\Icon;
use App\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class CreateEnvelopeV1Form extends AbstractType
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
            ->add('folderId', TextType::class, [
                'constraints' => [new Uuid()],
            ])
            ->add('type', ChoiceType::class, [
                'constraints' => [new NotBlank()],
                'choices' => [EnvelopeType::INCOME_ALIAS, EnvelopeType::EXPENSE_ALIAS]
            ])
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => EnvelopeName::MAX_LENGTH, 'min' => EnvelopeName::MIN_LENGTH]),
                    $this->valueObjectValidationFactory->create(EnvelopeName::class)
                ],
            ])
            ->add('icon', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    $this->valueObjectValidationFactory->create(Icon::class)
                ],
            ])
            ->add('currencyId', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid()],
            ])
            //todo add validation for categories and tags
            ->add('categories', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('tags', CollectionType::class, [
//                'entry_type' => UuidFormType::class,
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
        ;
    }
}

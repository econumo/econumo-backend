<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\Account\Validation;

use App\Domain\Entity\ValueObject\AccountName;
use App\Domain\Entity\ValueObject\Icon;
use App\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class UpdateAccountV1Form extends AbstractType
{
    private ValueObjectValidationFactoryInterface $valueObjectValidationFactory;

    public function __construct(ValueObjectValidationFactoryInterface $valueObjectValidationFactory)
    {
        $this->valueObjectValidationFactory = $valueObjectValidationFactory;
    }

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
                'constraints' => [new NotBlank(), $this->valueObjectValidationFactory->create(AccountName::class)],
            ])
            ->add('balance', NumberType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('icon', TextType::class, [
                'constraints' => [new NotBlank(), $this->valueObjectValidationFactory->create(Icon::class)],
            ])
            ->add('updatedAt', TextType::class, [
                'constraints' => [new NotBlank(), new DateTime("Y-m-d H:i:s")]
            ]);
    }
}

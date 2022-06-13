<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\User\ChangesList\Validation;

use App\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class GetChangesListV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('foldersUpdatedAt', TextType::class, [
                'constraints' => [new DateTime("Y-m-d H:i:s")]
            ])
            ->add('accountsUpdatedAt', TextType::class, [
                'constraints' => [new DateTime("Y-m-d H:i:s")]
            ])
            ->add('categoriesUpdatedAt', TextType::class, [
                'constraints' => [new DateTime("Y-m-d H:i:s")]
            ])
            ->add('tagsUpdatedAt', TextType::class, [
                'constraints' => [new DateTime("Y-m-d H:i:s")]
            ])
            ->add('payeesUpdatedAt', TextType::class, [
                'constraints' => [new DateTime("Y-m-d H:i:s")]
            ])
            ->add('currenciesUpdatedAt', TextType::class, [
                'constraints' => [new DateTime("Y-m-d H:i:s")]
            ])
            ->add('currencyRatesUpdatedAt', TextType::class, [
                'constraints' => [new DateTime("Y-m-d H:i:s")]
            ])
            ->add('transactionsUpdatedAt', TextType::class, [
                'constraints' => [new DateTime("Y-m-d H:i:s")]
            ])
            ->add('connectionsUpdatedAt', TextType::class, [
                'constraints' => [new DateTime("Y-m-d H:i:s")]
            ])
        ;
    }
}

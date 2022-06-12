<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Transaction\Transaction\Validation;

use App\Domain\Entity\ValueObject\TransactionType;
use App\Infrastructure\Symfony\Form\Constraints\OperationId;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class UpdateTransactionV1Form extends AbstractType
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
            ->add('type', ChoiceType::class, [
                'constraints' => [new NotBlank()],
                'choices' => [
                    TransactionType::EXPENSE_ALIAS,
                    TransactionType::INCOME_ALIAS,
                    TransactionType::TRANSFER_ALIAS
                ]
            ])
            ->add('amount', NumberType::class, [
                'constraints' => [new NotBlank()]
            ])
            ->add('amountRecipient', NumberType::class, [
                'constraints' => []
            ])
            ->add('accountId', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid()]
            ])
            ->add('accountRecipientId', TextType::class, [
                'constraints' => [new Uuid()]
            ])
            ->add('categoryId', TextType::class, [
                'constraints' => [new Uuid()]
            ])
            ->add('date', TextType::class, [
                'constraints' => [new NotBlank()]
            ])
            ->add('description', TextType::class, [
                'constraints' => [new Type('string'), new Length(['max' => 4096])]
            ])
            ->add('payeeId', TextType::class, [
                'constraints' => [new Uuid()]
            ])
            ->add('tagId', TextType::class, [
                'constraints' => [new Uuid()]
            ])
        ;
    }
}

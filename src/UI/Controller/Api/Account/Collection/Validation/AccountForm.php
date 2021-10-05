<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\Collection\Validation;

use App\Application\Account\Collection\Dto\AccountRequestDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class AccountForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                                   'csrf_protection' => false,
                                   'data_class' => AccountRequestDto::class,
                                   'allow_extra_fields' => true,
                               ]);
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
            ->add('position', TextType::class, [
                'constraints' => [new Type('number')],
            ]);
    }
}

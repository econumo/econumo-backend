<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Payee\PayeeList\Validation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Uuid;

class OrderPayeeListV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ids', CollectionType::class, [
            'allow_extra_fields' => true,
            'allow_add' => true,
            'entry_options' => [
                'constraints' => [
                    new Uuid()
                ],
            ],
        ]);
    }
}

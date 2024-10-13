<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\UI\Controller\Api\Account\AccountList\Validation;

use App\EconumoOneBundle\UI\Controller\Api\Account\AccountList\Validation\AccountPositionForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderAccountListV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('changes', CollectionType::class, [
            'allow_extra_fields' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => AccountPositionForm::class,
        ]);
    }
}

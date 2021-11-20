<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\Collection\Validation;

use App\Application\Account\Dto\ReorderCollectionV1RequestDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReorderCollectionV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
           'data_class' => ReorderCollectionV1RequestDto::class,
                               ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('accounts', CollectionType::class, [
            'entry_type' => AccountForm::class,
            'entry_options' => ['label' => false],
        ]);
    }
}

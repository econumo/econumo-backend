<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Connection\Invite\Validation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteInviteV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }
}

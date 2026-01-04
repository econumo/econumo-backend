<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Controller\Api\Transaction\TransactionList\Validation;

use App\EconumoBundle\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImportTransactionListV1Form extends AbstractType
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
        $builder->add('file', FileType::class, [
            'constraints' => [
                new NotBlank(),
                new File([
                    'maxSize' => '10M',
                    'mimeTypes' => [
                        'text/csv',
                        'text/plain',
                        'application/csv',
                        'application/vnd.ms-excel',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid CSV file',
                ])
            ],
        ]);

        $builder->add('mapping', CollectionType::class, [
            'constraints' => [new NotBlank()],
            'allow_add' => true,
            'allow_delete' => true,
        ]);
    }
}

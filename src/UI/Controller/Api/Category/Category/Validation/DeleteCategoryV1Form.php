<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Category\Category\Validation;

use App\Application\Category\Dto\DeleteCategoryV1RequestDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DeleteCategoryV1Form extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', TextType::class, [
            'constraints' => [new NotBlank(), new Uuid()],
        ])->add('mode', ChoiceType::class, [
            'constraints' => [new NotBlank(), new Callback([$this, 'validateReplaceMode'])],
            'choices' => [
                DeleteCategoryV1RequestDto::MODE_DELETE,
                DeleteCategoryV1RequestDto::MODE_REPLACE,
            ]
        ])->add('replaceId', TextType::class, [
            'constraints' => [new Uuid()],
        ]);
    }

    public function validateReplaceMode($value, ExecutionContextInterface $context, $payload)
    {
        if ($value !== DeleteCategoryV1RequestDto::MODE_REPLACE) {
            return;
        }

        /** @var \Symfony\Component\Form\Form $form */
        $form = $context->getRoot();
        /** @var DeleteCategoryV1RequestDto $dto */
        $dto = $form->getData();

        if (empty($dto->replaceId)) {
            $context->buildViolation('replaceId is required for mode=replace')
                ->atPath('replaceId')
                ->addViolation();
        }
    }
}

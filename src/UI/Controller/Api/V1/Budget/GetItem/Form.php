<?php
declare(strict_types=1);

namespace App\UI\Controller\Api\V1\Budget\GetItem;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetRepositoryInterface;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Form extends AbstractType
{
    private const MAX_PERIOD_IN_MONTHS = 12;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var BudgetRepositoryInterface
     */
    private $budgetRepository;

    public function __construct(
        Security $security,
        BudgetRepositoryInterface $budgetRepository
    ) {
        $this->security = $security;
        $this->budgetRepository = $budgetRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'id',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Uuid(),
                        new Callback(['callback' => [$this, 'accessAllowed']]),
                    ],
                ]
            )
            ->add(
                'fromDate',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Date(),
                    ],
                ]
            )
            ->add(
                'toDate',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Date(),
                        new Callback(['callback' => [$this, 'moreThanYear']]),
                    ],
                ]
            );
    }

    public function accessAllowed(string $value, ExecutionContextInterface $context): void
    {
        $budget = $this->budgetRepository->find(new Id($value));
        if ($budget === null) {
            $context->buildViolation('Budget not found')->addViolation();

            return;
        }
        /** @var User $user */
        $user = $this->security->getUser();
        if (!$budget->getUserId()->isEqual($user->getId())) {
            $context->buildViolation('Budget not found')->addViolation();

            return;
        }
    }

    public function moreThanYear(string $value, ExecutionContextInterface $context): void
    {
        /** @var FormInterface $form */
        $form = $context->getRoot();
        /** @var Model $model */
        $model = $form->getData();
        $fromDate = DateTime::createFromFormat('Y-m-d', $model->fromDate);
        $toDate = DateTime::createFromFormat('Y-m-d', $value);
        $months = $fromDate->diff($toDate)->m + ($fromDate->diff($toDate)->y * 12);
        if ($months >= static::MAX_PERIOD_IN_MONTHS) {
            $context->buildViolation('Too big period. Maximum ' . static::MAX_PERIOD_IN_MONTHS . ' months is allowed')->addViolation();
        }
    }
}

<?php

namespace App\Validator;

use App\Repository\VacationRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoVacationOnThisDateValidator extends ConstraintValidator
{
    private VacationRepository $vacationRepository;

    public function __construct(VacationRepository $vacationRepository)
    {
        $this->vacationRepository = $vacationRepository;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\NoVacationOnThisDate $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $veto = $this->context->getObject()->getVeto();

        $vacationOrNull = $this->vacationRepository->getVacationOn($value, $veto->getAgenda());
        if (null === $vacationOrNull) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $vacationOrNull->getLib())
            ->addViolation();
    }
}

<?php

namespace App\Validator;

use App\Repository\UnavailabilityRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoUnavailabilityOnThisDatetimeValidator extends ConstraintValidator
{
    private UnavailabilityRepository $unavailabilityRepository;

    public function __construct(UnavailabilityRepository $unavailabilityRepository)
    {
        $this->unavailabilityRepository = $unavailabilityRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\NoUnavailabilityOnThisDatetime $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $veto = $this->context->getObject()->getVeto();
        $type = $this->context->getObject()->getType();
        $startTime = $this->context->getObject()->getStartHour();
        $datetime = (clone $value)->setTime($startTime->format('H'), $startTime->format('i'));

        $unavailability = $this->unavailabilityRepository->getUnavailabilityAt($datetime, $type, $veto->getAgenda());
        if (null === $unavailability) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $unavailability->getLib())
            ->addViolation();
    }
}

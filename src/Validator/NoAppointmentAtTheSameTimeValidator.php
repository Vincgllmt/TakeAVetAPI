<?php

namespace App\Validator;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoAppointmentAtTheSameTimeValidator extends ConstraintValidator
{
    private AppointmentRepository $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\NoAppointmentAtTheSameTime $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $object = $this->context->getObject();

        if (!$object instanceof Appointment) {
            throw new \Exception('This validator can only be used on Appointment entity');
        }

        if (!$this->appointmentRepository->hasAppointmentOverlapping($object)) {
            return;
        }

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}

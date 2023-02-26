<?php

namespace App\Validator;

use App\Entity\Veto;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RequireAgendaFromUserValidator extends ConstraintValidator
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var RequireAgendaFromUser $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $user = $this->security->getUser();

        if ($user instanceof Veto) {
            if (null != $user->getAgenda()) {
                return;
            }
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}

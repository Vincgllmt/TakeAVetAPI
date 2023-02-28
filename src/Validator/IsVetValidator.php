<?php

namespace App\Validator;

use App\Entity\Veto;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsVetValidator extends ConstraintValidator
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\IsVet $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->security->getUser() instanceof Veto) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}

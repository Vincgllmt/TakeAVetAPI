<?php

namespace App\Validator;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsAuthenticatedUserValidator extends ConstraintValidator
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var IsAuthenticatedUser $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->security->getUser() === $value) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}

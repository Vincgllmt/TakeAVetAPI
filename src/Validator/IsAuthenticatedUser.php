<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class IsAuthenticatedUser extends Constraint
{
    public const MESSAGE = 'You can only access your own data.';

    public string $message = self::MESSAGE;
}

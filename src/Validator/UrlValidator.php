<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UrlValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // url or content url
        if (preg_match('/src=.+?(")/', $value) || filter_var($value, FILTER_VALIDATE_URL)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}

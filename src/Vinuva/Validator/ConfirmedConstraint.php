<?php

namespace Paho\Vinuva\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
abstract class ConfirmedConstraint extends Constraint
{
    public const
        LESS = 1,
        LESS_OR_EQUAL = 2,
        EQUAL = 3,
        GREATER = 4,
        GREATER_OR_EQUAL = 5;

    /** @var string */
    public $message;

    /** @var string */
    public $propertyPath;

    protected $comparison = self::EQUAL;

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return ConfirmedValidator::class;
    }

    public function getComparison(): int
    {
        return $this->comparison;
    }
}

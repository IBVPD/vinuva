<?php

namespace Paho\Vinuva\Validator;

/**
 * @Annotation
 */
class GreaterThanOrEqualConfirmed extends ConfirmedConstraint
{
    protected $comparison = self::GREATER_OR_EQUAL;
}

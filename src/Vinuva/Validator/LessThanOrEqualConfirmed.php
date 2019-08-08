<?php

namespace Paho\Vinuva\Validator;

/**
 * @Annotation
 */
class LessThanOrEqualConfirmed extends ConfirmedConstraint
{
    protected $comparison = self::LESS_OR_EQUAL;
}

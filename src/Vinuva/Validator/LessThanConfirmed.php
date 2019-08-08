<?php

namespace Paho\Vinuva\Validator;

/**
 * @Annotation
 */
class LessThanConfirmed extends ConfirmedConstraint
{
    protected $comparison = self::LESS;
}

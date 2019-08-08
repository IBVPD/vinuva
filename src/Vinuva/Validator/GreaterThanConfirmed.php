<?php

namespace Paho\Vinuva\Validator;

/**
 * @Annotation
 */
class GreaterThanConfirmed extends ConfirmedConstraint
{
    protected $comparison = self::GREATER;
}

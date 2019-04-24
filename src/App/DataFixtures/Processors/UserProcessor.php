<?php

namespace App\DataFixtures\Processors;

use Fidry\AliceDataFixtures\ProcessorInterface;
use Paho\Vinuva\Models\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserProcessor implements ProcessorInterface
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var PasswordEncoderInterface|null */
    private $encoder;

    /**
     * UserProcessor constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function preProcess(string $id, $object): void
    {
        if (!$object instanceof User) {
            return;
        }

        if ($object->getPlainPassword() === null) {
            return;
        }

        if ($this->encoder === null) {
            $this->encoder = $this->encoderFactory->getEncoder($object);
        }

        $object->setPassword($this->encoder->encodePassword($object->getPlainPassword(), $object->getSalt()));
    }

    public function postProcess(string $id, $object): void
    {

    }
}

<?php

namespace App\Controller\Traits;

use Paho\Vinuva\Models\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

trait UserProviderTrait
{
    /** @var TokenStorageInterface */
    protected $tokenProvider;

    /** @var User|object|string|null */
    protected $user;

    /**
     * @param TokenStorageInterface $provider
     *
     * @required
     */
    public function setUserPracticeProvider(TokenStorageInterface $provider): void
    {
        $this->tokenProvider = $provider;
    }

    /**
     * @return User|object|string|null
     */
    protected function getUser()
    {
        if ($this->user === null) {
            $token      = $this->tokenProvider->getToken();
            $this->user = $token instanceof TokenInterface ? $token->getUser() : null;
        }

        return $this->user;
    }
}

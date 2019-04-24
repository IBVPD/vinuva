<?php

namespace App\Controller\Traits;

use Paho\Vinuva\Models\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

trait UserProviderTrait
{
    /** @var TokenStorageInterface */
    protected $tokenProvider;

    /** @var User */
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
     * @return User|string|null
     */
    protected function getUser()
    {
        if (!$this->user) {
            $this->user = $this->tokenProvider->getUser();
        }

        return $this->user;
    }
}

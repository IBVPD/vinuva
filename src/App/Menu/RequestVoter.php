<?php

namespace App\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestVoter implements VoterInterface
{
    /** @var RequestStack */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function matchItem(ItemInterface $item): ?bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $uri = $request->getRequestUri();

            if ($item->getUri() === $uri || ($item->getUri() !== '/' && strpos($uri, $item->getUri()) === 0)) {
                return true;
            }
        }

        return null;
    }
}

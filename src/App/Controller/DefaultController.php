<?php

namespace App\Controller;

use App\Controller\Traits\TwigRenderingTrait;
use Paho\Vinuva\Models\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultController
{
    use TwigRenderingTrait;

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        return $this->render('base.html.twig');
    }

    public function noLocaleAction(RouterInterface $router, TokenStorageInterface $tokenStorage, Request $request): RedirectResponse
    {
        $locale = $request->getPreferredLanguage(['en', 'es', 'pt', 'fr']);
        $token  = $tokenStorage->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user instanceof User && $user->getLocale()) {
                $locale = $user->getLocale();
            }
        }

        if (!$locale) {
            $locale = 'en';
        }

        return new RedirectResponse($router->generate('homepage', ['_locale' => $locale]));
    }
}

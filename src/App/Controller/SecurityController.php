<?php

namespace App\Controller;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Form\Types\LoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController
{
    use FormFactoryControllerTrait;
    use GeneralControllerTrait;
    use TwigRenderingTrait;

    /**
     * @Route("/login", name="login")
     *
     * @param AuthenticationUtils $helper
     *
     * @return Response
     */
    public function loginAction(AuthenticationUtils $helper): Response
    {
        $loginForm = $this->formFactory->create(LoginType::class, null, ['method' => 'POST', 'action' => $this->router->generate('login_check')]);

        return $this->render('@App/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
            'form' => $loginForm->createView(),
            'with_login' => true,
        ]);
    }

}

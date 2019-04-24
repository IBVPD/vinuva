<?php

namespace App\Controller;

use App\Controller\Traits\TwigRenderingTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}

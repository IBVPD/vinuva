<?php

namespace App\Controller\Traits;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

trait TwigRenderingTrait
{
    /** @var Environment */
    protected $twig;

    /**
     * @param Environment $twig
     * @required
     */
    public function setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }

    protected function render($template, array $parameters = [], int $responseCode = Response::HTTP_OK): Response
    {
        return new Response($this->twig->render($template,$parameters), $responseCode);
    }
}

<?php

namespace App\Controller\Report;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Form\Report\User\FilterType;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reports/user")
 */
class UserController
{
    use TwigRenderingTrait;
    use FormFactoryControllerTrait;
    use GeneralControllerTrait;

    /**
     * @Route("/roles",name="reportUserRoles")
     *
     * @param FilterBuilderUpdaterInterface $filterBuilder
     * @param Request                       $request
     *
     * @return Response
     */
    public function roleAction(FilterBuilderUpdaterInterface $filterBuilder, Request $request): Response
    {
        $filterForm = $this->createForm(FilterType::class);
        return $this->render('@App/Report/User/role.html.twig', [
            'results' => [],
            'filterForm' => $filterForm->createView(),
        ]);
    }

    /**
     * @Route("/organization",name="reportUserOrganization")
     *
     * @param FilterBuilderUpdaterInterface $filterBuilder
     * @param Request                       $request
     *
     * @return Response
     */
    public function organizationAction(FilterBuilderUpdaterInterface $filterBuilder, Request $request): Response
    {
        $filterForm = $this->createForm(FilterType::class);
        return $this->render('@App/Report/User/organization.html.twig', [
            'results' => [],
            'filterForm' => $filterForm->createView(),
        ]);
    }
}

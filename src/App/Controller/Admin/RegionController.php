<?php

namespace App\Controller\Admin;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Form\Admin\Region\CreateType;
use App\Form\Admin\Region\EditType;
use App\Form\Admin\Region\FilterType;
use Doctrine\ORM\EntityRepository;
use NS\FilteredPaginationBundle\FilteredPagination;
use Paho\Vinuva\Models\Region;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/region")
 */
class RegionController
{
    private const
        INDEX = 'admin.Regions';

    use GeneralControllerTrait;
    use TwigRenderingTrait;
    use FormFactoryControllerTrait;

    /**
     * @Route("/", name="adminRegionIndex")
     *
     * @param FilteredPagination $pager
     * @param Request            $request
     *
     * @return Response
     */
    public function indexAction(FilteredPagination $pager, Request $request): Response
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(Region::class);
        $query      = $repository->createQueryBuilder('r')->orderBy('r.name');
        $result     = $pager->process($request, FilterType::class, $query, self::INDEX);

        if ($result->shouldRedirect()) {
            return new RedirectResponse($this->router->generate('adminRegionIndex'));
        }

        $createForm = $this->createForm(CreateType::class);
        return $this->render('@App/Admin/Region/index.html.twig', [
            'regions' => $result->getPagination(),
            'createForm' => $createForm->createView(),
            'filterForm' => $result->getForm()->createView(),
        ]);
    }

    /**
     * @Route("/create", name="adminRegionCreate", methods={"POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $region = $form->getData();

                $this->entityManager->persist($region);
                $this->entityManager->flush();
                $this->flash->addSuccess('Success', 'Region created successfully');
                return new RedirectResponse($this->router->generate('adminRegionIndex'));
            }

            $this->flash->addError('Error', 'Unable to create Region');
        }

        return new RedirectResponse($this->router->generate('adminRegionIndex'));
    }

    /**
     * @Route("/{regionId}/edit", name="adminRegionEdit")
     * @param Request $request
     * @param int     $regionId
     *
     * @return Response
     */
    public function editRegion(Request $request, int $regionId): Response
    {
        /** @var Region|null $region */
        $region = $this->entityManager->find(Region::class, $regionId);
        if (!$region) {
            throw new NotFoundHttpException('Unable to locate region');
        }

        $form = $this->createForm(EditType::class, $region);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->persist($region);
                $this->entityManager->flush();

                $this->flash->addSuccess('Success', 'Region updated successfully');
                return new RedirectResponse($this->router->generate('adminRegionIndex'));
            }

            $this->flash->addError('Error', 'Unable to update region');
        }

        return $this->render('@App/Admin/Region/edit.html.twig', [
            'form' => $form->createView(),
            'region' => $region,
        ]);
    }
}

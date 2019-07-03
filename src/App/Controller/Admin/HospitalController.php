<?php

namespace App\Controller\Admin;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Form\Admin\Hospital\CreateType;
use App\Form\Admin\Hospital\EditType;
use App\Form\Admin\Hospital\FilterType;
use Doctrine\ORM\EntityRepository;
use NS\FilteredPaginationBundle\FilteredPagination;
use Paho\Vinuva\Models\Hospital;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/hospital")
 */
class HospitalController
{
    private const
        INDEX = 'admin.hospitals';

    use GeneralControllerTrait;
    use TwigRenderingTrait;
    use FormFactoryControllerTrait;

    /**
     * @Route("/", name="adminHospitalIndex")
     *
     * @param FilteredPagination $pager
     * @param Request            $request
     *
     * @return Response
     */
    public function indexAction(FilteredPagination $pager, Request $request): Response
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(Hospital::class);
        $query      = $repository->createQueryBuilder('r')
            ->select('r,c')
            ->join('r.country','c')
            ->orderBy('r.name');

        $result     = $pager->process($request, FilterType::class, $query, self::INDEX);

        if ($result->shouldRedirect()) {
            return new RedirectResponse($this->router->generate('adminHospitalIndex'));
        }

        $createForm = $this->createForm(CreateType::class);
        return $this->render('@App/Admin/Hospital/index.html.twig', [
            'hospitals' => $result->getPagination(),
            'createForm' => $createForm->createView(),
            'filterForm' => $result->getForm()->createView(),
        ]);
    }

    /**
     * @Route("/create", name="adminHospitalCreate", methods={"POST"})
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
                $this->entityManager->persist($form->getData());
                $this->entityManager->flush();
                $this->flash->addSuccess('Success', 'Hospital created successfully');
                return new RedirectResponse($this->router->generate('adminHospitalIndex'));
            }

            $this->flash->addError('Error', 'Unable to create hospital');
        }

        return new RedirectResponse($this->router->generate('adminHospitalIndex'));
    }

    /**
     * @Route("/{hospitalId}/edit", name="adminHospitalEdit")
     * @param Request $request
     * @param int     $hospitalId
     *
     * @return Response
     */
    public function editHospital(Request $request, int $hospitalId): Response
    {
        /** @var Hospital|null $hospital */
        $hospital = $this->entityManager->find(Hospital::class, $hospitalId);
        if (!$hospital) {
            throw new NotFoundHttpException('Unable to locate hospital');
        }

        $form = $this->createForm(EditType::class, $hospital);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->persist($hospital);
                $this->entityManager->flush();

                $this->flash->addSuccess('Success', 'Hospital updated successfully');
                return new RedirectResponse($this->router->generate('adminHospitalIndex'));
            }

            $this->flash->addError('Error', 'Unable to update hospital');
        }

        return $this->render('@App/Admin/Hospital/edit.html.twig', [
            'form' => $form->createView(),
            'hospital' => $hospital,
        ]);
    }
}

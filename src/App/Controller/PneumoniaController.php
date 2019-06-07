<?php

namespace App\Controller;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Controller\Traits\UserProviderTrait;
use App\Form\BaseDiseaseType;
use App\Form\Filters\BaseDiseaseFilterType;
use App\Form\Pneumonia\EditType;
use NS\FilteredPaginationBundle\FilteredPagination;
use NS\FlashBundle\Service\Messages;
use Paho\Vinuva\Models\Pneumonia;
use Paho\Vinuva\Models\User;
use Paho\Vinuva\Repositories\PneumoniaRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pneumonia")
 */
class PneumoniaController
{
    use TwigRenderingTrait;
    use FormFactoryControllerTrait;
    use GeneralControllerTrait;
    use UserProviderTrait;

    protected const
        INDEX = 'pneumonia.index';

    /**
     * @Route("/", name="pneumoniaIndex")
     *
     * @param PneumoniaRepository $repository
     * @param FilteredPagination  $pagination
     * @param Request             $request
     *
     * @return Response
     */
    public function indexAction(PneumoniaRepository $repository, FilteredPagination $pagination, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new NotFoundHttpException('User not found');
        }

        $query  = $repository->getQueryBuilder($user);
        $result = $pagination->process($request, BaseDiseaseFilterType::class, $query, self::INDEX);
        if ($result->shouldRedirect()) {
            return new RedirectResponse($this->router->generate('pneumoniaIndex'));
        }

        $form = $this->createForm(BaseDiseaseType::class);

        return $this->render('@App/Pneumonia/index.html.twig', [
            'filterForm' => $result->getForm()->createView(),
            'createForm' => $form->createView(),
            'results' => $result->getPagination()
        ]);
    }

    /**
     * @Route("/create", name="pneumoniaCreate")
     * @param PneumoniaRepository $repository
     * @param Messages            $flash
     * @param Request             $request
     *
     * @return Response
     */
    public function createAction(PneumoniaRepository $repository, Messages $flash, Request $request): Response
    {
        $form = $this->createForm(BaseDiseaseType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $case = $repository->check($data['country'], $data['hospital'], $data['year'], $data['month']);
                if (!$case) {
                    $case = new Pneumonia($data['hospital'], $data['year'], $data['month']);
                    $this->entityManager->persist($case);
                    $this->entityManager->flush();
                }

                $flash->addSuccess('Created', 'Please complete the entry');

                return new RedirectResponse($this->router->generate('pneumoniaEdit', ['id' => $case->getId()]));
            }
            $flash->addError('Error', 'Missing required fields');
        }

        return $this->render('@App/Pneumonia/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="pneumoniaEdit")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function editAction(Request $request, int $id): Response
    {
        /** @var Pneumonia|null $case */
        $case = $this->entityManager->getRepository(Pneumonia::class)->find($id);
        if ($case === null) {
            throw new NotFoundHttpException('Unable to locate pneumonia case');
        }

        $form = $this->createForm(EditType::class, $case);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->persist($case);
                $this->entityManager->flush();

                $this->flash->addSuccess('Success', 'Case saved');
                return new RedirectResponse($this->router->generate('pneumoniaIndex'));
            }
            $this->flash->addError('Error', 'Unable to save case');
        }
        return $this->render('@App/Pneumonia/edit.html.twig', [
            'form' => $form->createView(),
            'case' => $case,
        ]);

    }
}

<?php

namespace App\Controller;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Form\BaseDiseaseType;
use App\Form\Meningitis\EditType;
use NS\FlashBundle\Service\Messages;
use Paho\Vinuva\Models\Meningitis;
use Paho\Vinuva\Repositories\MeningitisRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/meningitis")
 */
class MeningitisController
{
    use TwigRenderingTrait;
    use FormFactoryControllerTrait;
    use GeneralControllerTrait;

    /**
     * @Route("/create", name="meningitisCreate")
     * @param MeningitisRepository $repository
     * @param Messages             $flash
     * @param Request              $request
     *
     * @return Response
     */
    public function createAction(MeningitisRepository $repository, Messages $flash, Request $request): Response
    {
        $form = $this->createForm(BaseDiseaseType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $case = $repository->check($data['country'], $data['hospital'], $data['year'], $data['month']);
                if (!$case) {
                    $case = new Meningitis($data['hospital'], $data['year'], $data['month']);
                    $this->entityManager->persist($case);
                    $this->entityManager->flush();
                }

                $flash->addSuccess('Created', 'Please complete the entry');

                return new RedirectResponse($this->router->generate('meningitisEdit', ['id' => $case->getId()]));
            }
            $flash->addError('Error', 'Missing required fields');
        }

        return $this->render('@App/Meningitis/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="meningitisEdit")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function editAction(Request $request, int $id): Response
    {
        /** @var Meningitis|null $case */
        $case = $this->entityManager->getRepository(Meningitis::class)->find($id);
        if ($case === null) {
            throw new NotFoundHttpException('Unable to locate meningitis case');
        }

        $form = $this->createForm(EditType::class, $case);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->persist($case);
                $this->entityManager->flush();

                $this->flash->addSuccess('Success', 'Case saved');
                return new RedirectResponse($this->router->generate('meningitisCreate'));
            }
            $this->flash->addError('Error', 'Unable to save case');
        }
        return $this->render('@App/Meningitis/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}

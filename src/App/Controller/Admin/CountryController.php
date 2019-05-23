<?php

namespace App\Controller\Admin;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Form\Admin\Country\CreateType;
use App\Form\Admin\Country\EditType;
use App\Form\Admin\Country\FilterType;
use Doctrine\ORM\EntityRepository;
use NS\FilteredPaginationBundle\FilteredPagination;
use Paho\Vinuva\Models\Country;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/country")
 */
class CountryController
{
    private const
        INDEX = 'admin.countries';

    use GeneralControllerTrait;
    use TwigRenderingTrait;
    use FormFactoryControllerTrait;

    /**
     * @Route("/", name="adminCountryIndex")
     *
     * @param FilteredPagination $pager
     * @param Request            $request
     *
     * @return Response
     */
    public function indexAction(FilteredPagination $pager, Request $request): Response
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(Country::class);
        $query      = $repository->createQueryBuilder('c')
            ->addSelect('r,h')
            ->leftJoin('c.hospitals','h')
            ->join('c.region','r')
            ->orderBy('c.name');
        $result     = $pager->process($request, FilterType::class, $query, self::INDEX);

        if ($result->shouldRedirect()) {
            return new RedirectResponse($this->router->generate('adminCountryIndex'));
        }

        $createForm = $this->createForm(CreateType::class);
        return $this->render('@App/Admin/Country/index.html.twig', [
            'countries' => $result->getPagination(),
            'createForm' => $createForm->createView(),
            'filterForm' => $result->getForm()->createView(),
        ]);
    }

    /**
     * @Route("/create", name="adminCountryCreate", methods={"POST"})
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
                $country = $form->getData();
                $this->entityManager->persist($country);
                $this->entityManager->flush();
                $this->flash->addSuccess('Success', 'Country created successfully');
                return new RedirectResponse($this->router->generate('adminCountryIndex'));
            }

            $this->flash->addError('Error', 'Unable to create country');
        }

        return new RedirectResponse($this->router->generate('adminCountryIndex'));
    }

    /**
     * @Route("/{countryId}/edit", name="adminCountryEdit")
     * @param Request $request
     * @param string  $countryId
     *
     * @return Response
     */
    public function editCountry(Request $request, string $countryId): Response
    {
        /** @var Country|null $country */
        $country = $this->entityManager->find(Country::class, $countryId);
        if (!$country) {
            throw new NotFoundHttpException('Unable to locate country');
        }

        $form = $this->createForm(EditType::class, $country);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->persist($country);
                $this->entityManager->flush();

                $this->flash->addSuccess('Success', 'Country updated successfully');
                return new RedirectResponse($this->router->generate('adminCountryIndex'));
            }

            $this->flash->addError('Error', 'Unable to update country');
        }

        return $this->render('@App/Admin/Country/edit.html.twig', [
            'form' => $form->createView(),
            'country' => $country,
        ]);
    }
}

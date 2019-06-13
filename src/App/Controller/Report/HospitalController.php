<?php

namespace App\Controller\Report;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Form\Report\Hospital\FilterType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Models\Region;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reports/hospital")
 */
class HospitalController
{
    use TwigRenderingTrait;
    use FormFactoryControllerTrait;
    use GeneralControllerTrait;

    /**
     * @Route("/", name="reportHospitalIndex")
     * @param FilterBuilderUpdaterInterface $filterBuilder
     * @param Request                       $request
     *
     * @return Response
     */
    public function indexAction(FilterBuilderUpdaterInterface $filterBuilder, Request $request): Response
    {
        $results    = [];
        $filterForm = $this->createForm(FilterType::class);
        if ($request->getMethod() === 'POST') {
            /** @var EntityRepository $repository */
            $repository = $this->entityManager->getRepository(Region::class);
            $query      = $repository
                ->createQueryBuilder('r')
                ->select('r,c,h')
                ->join('r.countries', 'c')
                ->join('c.hospitals', 'h')
                ->orderBy('r.name,c.name,h.name');

            $filterForm->handleRequest($request);
            $filterBuilder->addFilterConditions($filterForm, $query);
            $results = $query->getQuery()->getResult();
        }

        return $this->render('@App/Report/Hospital/index.html.twig', [
            'results' => $results,
            'filterForm' => $filterForm->createView(),
        ]);
    }

}

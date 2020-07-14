<?php

namespace App\Controller\Report;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Form\Report\Monthly\FilterType as GeneralFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Paho\Vinuva\Models\Meningitis;
use Paho\Vinuva\Models\Pneumonia;
use Paho\Vinuva\Models\Rotavirus;
use Paho\Vinuva\Repositories\MeningitisRepository;
use Paho\Vinuva\Repositories\PneumoniaRepository;
use Paho\Vinuva\Repositories\RotavirusRepository;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reports/country")
 */
class CountryController
{
    use TwigRenderingTrait;
    use GeneralControllerTrait;
    use FormFactoryControllerTrait;

    /** @var MeningitisRepository */
    private $mRepository;

    /** @var PneumoniaRepository */
    private $pRepository;

    /** @var RotavirusRepository */
    private $rvRepository;

    public function __construct(MeningitisRepository $mRepository, PneumoniaRepository $pRepository, RotavirusRepository $rvRepository)
    {
        $this->mRepository  = $mRepository;
        $this->pRepository  = $pRepository;
        $this->rvRepository = $rvRepository;
    }

    /**
     * @Route("/summary", name="reportCountrySummary")
     * @param FilterBuilderUpdaterInterface $filterBuilder
     * @param Request                       $request
     *
     * @return Response
     */
    public function summaryAction(FilterBuilderUpdaterInterface $filterBuilder, Request $request): Response
    {
        $data       = $results = [];
        $filterForm = $this->createForm(GeneralFilterType::class);
        $filterForm->handleRequest($request);
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data     = $filterForm->getData();
            $diseases = [];
            switch ($data['disease']) {
                case null:
                    $diseases = [Pneumonia::class => $this->pRepository, Meningitis::class => $this->mRepository, Rotavirus::class => $this->rvRepository];
                    break;
                case 'pneumonia':
                    $diseases = [Pneumonia::class => $this->pRepository];
                    break;
                case 'meningitis':
                    $diseases = [Meningitis::class => $this->mRepository];
                    break;
                case 'rotavirus':
                    $diseases = [Rotavirus::class => $this->rvRepository];
                    break;
            }

            foreach ($diseases as $diseaseClass => $repository) {
                $query = $repository->getByCountryFilterQuery();

                $filterBuilder->addFilterConditions($filterForm, $query);
                $results = $repository->getByCountrySummary($query, $results);
            }

            $filterData = $request->request->get('filter');
            if (isset($filterData['export'])) {
                $streamedResponse = new StreamedResponse();
                $html = $this->twig->render('@App/Report/Country/summary-table.html.twig', ['results' => $results, 'data' => $data]);
                $streamedResponse->setCallback(static function () use($html) {
                    $reader      = new Html();
                    $spreadsheet = $reader->loadFromString($html);
                    $writer      = new Xlsx($spreadsheet);
                    $writer->save('php://output');
                });

                $streamedResponse->setStatusCode(Response::HTTP_OK);
                $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="country-summary.xlsx"');

                return $streamedResponse->send();
            }
        }

        return $this->render('@App/Report/Country/summary.html.twig', [
            'results' => $results,
            'filterForm' => $filterForm->createView(),
            'data' => $data,
        ]);
    }

}

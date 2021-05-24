<?php
declare(strict_types=1);

namespace Paho\Vinuva\Repositories;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Paho\Vinuva\Models\Common\Probable;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Models\Rotavirus\Vaccination;
use Paho\Vinuva\Report\Common\Probable as SummaryProbable;
use Paho\Vinuva\Report\Country\RotavirusSummary as CountryRotavirusSummary;
use Paho\Vinuva\Report\Hospital\Country;
use Paho\Vinuva\Report\Hospital\CountryCollector;
use Paho\Vinuva\Report\Hospital\Rotavirus;
use Paho\Vinuva\Report\Rotavirus\Vaccination as SummaryVaccination;
use Paho\Vinuva\Report\RotavirusSummary;

class RotavirusRepository extends AbstractRepository
{
    public function getSummaryQuery(QueryBuilder $queryBuilder): array
    {
        $subStrSelect = 'SELECT r0_.country_id, c1_.name, r0_.year, r0_.month as month, 
        SUM(r0_.under5) as u5,SUM(r0_.under5With) as u5with,SUM(r0_.suspected) as susp, 
        SUM(r0_.with_form_and_sample_12) as p12, SUM(r0_.with_form_and_sample_23) as p23, SUM(r0_.with_form_and_sample_59) as p59,SUM(r0_.with_form_and_sample_total) as ptotal,

        SUM(r0_.positive_u12_vaccinated) as positiveU12Vaccinated,SUM(r0_.positive_u12_not_vaccinated) as positiveU12NotVaccinated,SUM(r0_.positive_u12_no_information) as positiveU12NoInformation,
        SUM(r0_.positive_u23_vaccinated) as positiveU23Vaccinated,SUM(r0_.positive_u23_not_vaccinated) as positiveU23NotVaccinated,SUM(r0_.positive_u23_no_information) as positiveU23NoInformation,
        SUM(r0_.positive_u59_vaccinated) as positiveU59Vaccinated,SUM(r0_.positive_u59_not_vaccinated) as positiveU59NotVaccinated,SUM(r0_.positive_u59_no_information) as positiveU59NoInformation,
        SUM(r0_.positive_total_vaccinated) as positiveTotalVaccinated,SUM(r0_.positive_total_not_vaccinated) as positiveTotalNotVaccinated,SUM(r0_.positive_total_no_information) as positiveTotalNoInformation,

        SUM(r0_.death_u12_vaccinated) as deathU12Vaccinated,SUM(r0_.death_u12_not_vaccinated) as deathU12NotVaccinated,SUM(r0_.death_u12_no_information) as deathU12NoInformation,
        SUM(r0_.death_u23_vaccinated) as deathU23Vaccinated,SUM(r0_.death_u23_not_vaccinated) as deathU23NotVaccinated,SUM(r0_.death_u23_no_information) as deathU23NoInformation,
        SUM(r0_.death_u59_vaccinated) as deathU59Vaccinated,SUM(r0_.death_u59_not_vaccinated) as deathU59NotVaccinated,SUM(r0_.death_u59_no_information) as deathU59NoInformation,
        SUM(r0_.death_total_vaccinated) as deathTotalVaccinated,SUM(r0_.death_total_not_vaccinated) as deathTotalNotVaccinated,SUM(r0_.death_total_no_information) as deathTotalNoInformation';

        $groupSql = 'SELECT y.name as countryName, y.year as year, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',u5)) u5,GROUP_CONCAT(CONCAT(y.month,\'=\',u5with)) u5with, GROUP_CONCAT(CONCAT(y.month,\'=\',susp)) susp,  
        GROUP_CONCAT(CONCAT(y.month,\'=\',p12)) p12, GROUP_CONCAT(CONCAT(y.month,\'=\',p23)) p23, GROUP_CONCAT(CONCAT(y.month,\'=\',p59)) p59, GROUP_CONCAT(CONCAT(y.month,\'=\',ptotal)) ptotal,
         
        GROUP_CONCAT(CONCAT(y.month,\'=\',positiveU12Vaccinated)) positiveU12Vaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',positiveU12NotVaccinated)) positiveU12NotVaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',positiveU12NoInformation)) positiveU12NoInformation, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',positiveU23Vaccinated)) positiveU23Vaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',positiveU23NotVaccinated)) positiveU23NotVaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',positiveU23NoInformation)) positiveU23NoInformation, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',positiveU59Vaccinated)) positiveU59Vaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',positiveU59NotVaccinated)) positiveU59NotVaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',positiveU59NoInformation)) positiveU59NoInformation, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',positiveTotalVaccinated)) positiveTotalVaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',positiveTotalNotVaccinated)) positiveTotalNotVaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',positiveTotalNoInformation)) positiveTotalNoInformation, 

        GROUP_CONCAT(CONCAT(y.month,\'=\',deathU12Vaccinated)) deathU12Vaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',deathU12NotVaccinated)) deathU12NotVaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',deathU12NoInformation)) deathU12NoInformation, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',deathU23Vaccinated)) deathU23Vaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',deathU23NotVaccinated)) deathU23NotVaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',deathU23NoInformation)) deathU23NoInformation, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',deathU59Vaccinated)) deathU59Vaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',deathU59NotVaccinated)) deathU59NotVaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',deathU59NoInformation)) deathU59NoInformation, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',deathTotalVaccinated)) deathTotalVaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',deathTotalNotVaccinated)) deathTotalNotVaccinated, GROUP_CONCAT(CONCAT(y.month,\'=\',deathTotalNoInformation)) deathTotalNoInformation';

        [$unusedSelect, $filteredTerms] = explode('FROM', $queryBuilder->getQuery()->getSQL());
        $sql       = "$groupSql FROM ($subStrSelect FROM $filteredTerms)y GROUP BY y.country_id, y.year";
        $statement = $this->entityManager->getConnection()->prepare($sql);
        $this->adjustParameters($queryBuilder, $statement);

        if ($statement->executeStatement()) {
            $results = [];
            while ($row = $statement->fetch(FetchMode::ASSOCIATIVE)) {
                $probable = new SummaryProbable($row['p12'], $row['p23'], $row['p59'], $row['ptotal']);
                $pUnder12 = new SummaryVaccination($row['positiveU12Vaccinated'], $row['positiveU12NotVaccinated'], $row['positiveU12NoInformation']);
                $pUnder23 = new SummaryVaccination($row['positiveU23Vaccinated'], $row['positiveU23NotVaccinated'], $row['positiveU23NoInformation']);
                $pUnder59 = new SummaryVaccination($row['positiveU59Vaccinated'], $row['positiveU59NotVaccinated'], $row['positiveU59NoInformation']);
                $ptotal   = new SummaryVaccination($row['positiveTotalVaccinated'], $row['positiveTotalNotVaccinated'], $row['positiveTotalNoInformation']);

                $dUnder12  = new SummaryVaccination($row['deathU12Vaccinated'], $row['deathU12NotVaccinated'], $row['deathU12NoInformation']);
                $dUnder23  = new SummaryVaccination($row['deathU23Vaccinated'], $row['deathU23NotVaccinated'], $row['deathU23NoInformation']);
                $dUnder59  = new SummaryVaccination($row['deathU59Vaccinated'], $row['deathU59NotVaccinated'], $row['deathU59NoInformation']);
                $dtotal    = new SummaryVaccination($row['deathTotalVaccinated'], $row['deathTotalNotVaccinated'], $row['deathTotalNoInformation']);
                $results[] = new RotavirusSummary(
                    $row['countryName'],
                    (int)$row['year'],
                    $row['u5'],
                    $row['u5with'],
                    $row['susp'],
                    $probable,
                    $pUnder12,
                    $pUnder23,
                    $pUnder59,
                    $ptotal,
                    $dUnder12,
                    $dUnder23,
                    $dUnder59,
                    $dtotal
                );
            }

            return $results;
        }

        return [];
    }

    /**
     * @param QueryBuilder       $queryBuilder
     * @param CountryCollector[] $results
     *
     * @return array
     */
    public function getByHospitalSummary(QueryBuilder $queryBuilder, array $results): array
    {
        [$sqlSelect, $filteredTerms] = explode('FROM', $queryBuilder->getQuery()->getSQL());

        $sqlSelect = 'SELECT h2_.id as hId, h2_.name as hName, c1_.id cId, c1_.name as cName, 
        SUM(r0_.under5) u5, SUM(r0_.under5With) u5with, SUM(r0_.suspected) susp, 
        SUM(r0_.with_form_and_sample_12) p12 ,SUM(r0_.with_form_and_sample_23) p23,SUM(r0_.with_form_and_sample_59) p59, SUM(r0_.with_form_and_sample_total) ptotal, 
        SUM(r0_.positive_u12_vaccinated) as p12Vac,SUM(r0_.positive_u12_not_vaccinated) as p12NoVac,SUM(r0_.positive_u12_no_information) as p12NoInfo,
        SUM(r0_.positive_u23_vaccinated) as p23Vac,SUM(r0_.positive_u23_not_vaccinated) as p23NoVac,SUM(r0_.positive_u23_no_information) as p23NoInfo,
        SUM(r0_.positive_u59_vaccinated) as p59Vac,SUM(r0_.positive_u59_not_vaccinated) as p59NoVac,SUM(r0_.positive_u59_no_information) as p59NoInfo,
        SUM(r0_.positive_total_vaccinated) as pTotalVac,SUM(r0_.positive_total_not_vaccinated) as pTotalNoVac,SUM(r0_.positive_total_no_information) as pTotalNoInfo,
        SUM(r0_.death_u12_vaccinated) as d12Vac,SUM(r0_.death_u12_not_vaccinated) as d12NoVac,SUM(r0_.death_u12_no_information) as d12NoInfo,
        SUM(r0_.death_u23_vaccinated) as d23Vac,SUM(r0_.death_u23_not_vaccinated) as d23NoVac,SUM(r0_.death_u23_no_information) as d23NoInfo,
        SUM(r0_.death_u59_vaccinated) as d59Vac,SUM(r0_.death_u59_not_vaccinated) as d59NoVac,SUM(r0_.death_u59_no_information) as d59NoInfo,
        SUM(r0_.death_total_vaccinated) as dTotalVac,SUM(r0_.death_total_not_vaccinated) as dTotalNoVac,SUM(r0_.death_total_no_information) as dTotalNoInfo';

        $sql       = "$sqlSelect FROM $filteredTerms";
        $statement = $this->entityManager->getConnection()->prepare($sql);
        $this->adjustParameters($queryBuilder, $statement);

        if ($statement->executeStatement()) {
            while ($row = $statement->fetch(FetchMode::ASSOCIATIVE)) {
                if (!isset($results[$row['cId']])) {
                    $results[$row['cId']] = new CountryCollector(new Country((int)$row['cId'], $row['cName']));
                }
                $hospital = Hospital::createDTO((int)$row['hId'], $row['hName']);
                $probable = new Probable($row['p12'] ? (int)$row['p12'] : null, $row['p23'] ? (int)$row['p23'] : null, $row['p59'] ? (int)$row['p59'] : null, $row['ptotal'] ? (int)$row['ptotal'] : null);
                $p12      = new Vaccination(
                    $row['p12Vac'] ? (int)$row['p12Vac'] : null,
                    $row['p12NoVac'] ? (int)$row['p12NoVac'] : null,
                    $row['p12NoInfo'] ? (int)$row['p12NoInfo'] : null
                );
                $p23      = new Vaccination(
                    $row['p23Vac'] ? (int)$row['p23Vac'] : null,
                    $row['p23NoVac'] ? (int)$row['p23NoVac'] : null,
                    $row['p23NoInfo'] ? (int)$row['p23NoInfo'] : null
                );
                $p59      = new Vaccination(
                    $row['p59Vac'] ? (int)$row['p59Vac'] : null,
                    $row['p59NoVac'] ? (int)$row['p59NoVac'] : null,
                    $row['p59NoInfo'] ? (int)$row['p59NoInfo'] : null
                );
                $pTotal   = new Vaccination(
                    $row['pTotalVac'] ? (int)$row['pTotalVac'] : null,
                    $row['pTotalNoVac'] ? (int)$row['pTotalNoVac'] : null,
                    $row['pTotalNoInfo'] ? (int)$row['pTotalNoInfo'] : null
                );
                $d12      = new Vaccination(
                    $row['d12Vac'] ? (int)$row['d12Vac'] : null,
                    $row['d12NoVac'] ? (int)$row['d12NoVac'] : null,
                    $row['d12NoInfo'] ? (int)$row['d12NoInfo'] : null
                );
                $d23      = new Vaccination(
                    $row['d23Vac'] ? (int)$row['d23Vac'] : null,
                    $row['d23NoVac'] ? (int)$row['d23NoVac'] : null,
                    $row['d23NoInfo'] ? (int)$row['d23NoInfo'] : null
                );
                $d59      = new Vaccination(
                    $row['d59Vac'] ? (int)$row['d59Vac'] : null,
                    $row['d59NoVac'] ? (int)$row['d59NoVac'] : null,
                    $row['d59NoInfo'] ? (int)$row['d59NoInfo'] : null
                );
                $dTotal   = new Vaccination(
                    $row['dTotalVac'] ? (int)$row['dTotalVac'] : null,
                    $row['dTotalNoVac'] ? (int)$row['dTotalNoVac'] : null,
                    $row['dTotalNoInfo'] ? (int)$row['dTotalNoInfo'] : null
                );

                $results[$row['cId']]->addCase(new Rotavirus($hospital,
                    (int)$row['u5'],
                    (int)$row['u5with'],
                    (int)$row['susp'],
                    $probable,
                    $p12, $p23, $p59, $pTotal,
                    $d12, $d23, $d59, $dTotal
                ));
            }
        }

        return $results;
    }

    public function getByCountrySummary(QueryBuilder $queryBuilder, array $results): array
    {
        [$sqlSelect, $filteredTerms] = explode('FROM', $queryBuilder->getQuery()->getSQL());

        $sqlSelect = 'SELECT h2_.id as hId, h2_.name as hName, c1_.id cId, c1_.name as cName, 
        SUM(r0_.under5) u5, SUM(r0_.under5With) u5with, SUM(r0_.suspected) susp, 
        SUM(r0_.with_form_and_sample_12) wfs12 ,SUM(r0_.with_form_and_sample_23) wfs23,SUM(r0_.with_form_and_sample_59) wfs59, SUM(r0_.with_form_and_sample_total) wfstotal, 
        SUM(r0_.positive_u12_vaccinated) as p12Vac,SUM(r0_.positive_u12_not_vaccinated) as p12NoVac,SUM(r0_.positive_u12_no_information) as p12NoInfo,
        SUM(r0_.positive_u23_vaccinated) as p23Vac,SUM(r0_.positive_u23_not_vaccinated) as p23NoVac,SUM(r0_.positive_u23_no_information) as p23NoInfo,
        SUM(r0_.positive_u59_vaccinated) as p59Vac,SUM(r0_.positive_u59_not_vaccinated) as p59NoVac,SUM(r0_.positive_u59_no_information) as p59NoInfo,
        SUM(r0_.positive_total_vaccinated) as pTotalVac,SUM(r0_.positive_total_not_vaccinated) as pTotalNoVac,SUM(r0_.positive_total_no_information) as pTotalNoInfo,
        SUM(r0_.death_u12_vaccinated) as d12Vac,SUM(r0_.death_u12_not_vaccinated) as d12NoVac,SUM(r0_.death_u12_no_information) as d12NoInfo,
        SUM(r0_.death_u23_vaccinated) as d23Vac,SUM(r0_.death_u23_not_vaccinated) as d23NoVac,SUM(r0_.death_u23_no_information) as d23NoInfo,
        SUM(r0_.death_u59_vaccinated) as d59Vac,SUM(r0_.death_u59_not_vaccinated) as d59NoVac,SUM(r0_.death_u59_no_information) as d59NoInfo,
        SUM(r0_.death_total_vaccinated) as dTotalVac,SUM(r0_.death_total_not_vaccinated) as dTotalNoVac,SUM(r0_.death_total_no_information) as dTotalNoInfo';

        $sql       = "$sqlSelect FROM $filteredTerms";
        $statement = $this->entityManager->getConnection()->prepare($sql);
        $this->adjustParameters($queryBuilder, $statement);

        if ($statement->executeStatement()) {
            $class = substr(strrchr($this->class, '\\'), 1);
            while ($row = $statement->fetch(FetchMode::ASSOCIATIVE)) {
                $withForm = new Probable($row['wfs12'] ? (int)$row['wfs12'] : null, $row['wfs23'] ? (int)$row['wfs23'] : null, $row['wfs59'] ? (int)$row['wfs59'] : null, $row['wfstotal'] ? (int)$row['wfstotal'] : null);
                $p12      = new Vaccination(
                    $row['p12Vac'] ? (int)$row['p12Vac'] : null,
                    $row['p12NoVac'] ? (int)$row['p12NoVac'] : null,
                    $row['p12NoInfo'] ? (int)$row['p12NoInfo'] : null
                );
                $p23      = new Vaccination(
                    $row['p23Vac'] ? (int)$row['p23Vac'] : null,
                    $row['p23NoVac'] ? (int)$row['p23NoVac'] : null,
                    $row['p23NoInfo'] ? (int)$row['p23NoInfo'] : null
                );
                $p59      = new Vaccination(
                    $row['p59Vac'] ? (int)$row['p59Vac'] : null,
                    $row['p59NoVac'] ? (int)$row['p59NoVac'] : null,
                    $row['p59NoInfo'] ? (int)$row['p59NoInfo'] : null
                );
                $pTotal   = new Vaccination(
                    $row['pTotalVac'] ? (int)$row['pTotalVac'] : null,
                    $row['pTotalNoVac'] ? (int)$row['pTotalNoVac'] : null,
                    $row['pTotalNoInfo'] ? (int)$row['pTotalNoInfo'] : null
                );
                $d12      = new Vaccination(
                    $row['d12Vac'] ? (int)$row['d12Vac'] : null,
                    $row['d12NoVac'] ? (int)$row['d12NoVac'] : null,
                    $row['d12NoInfo'] ? (int)$row['d12NoInfo'] : null
                );
                $d23      = new Vaccination(
                    $row['d23Vac'] ? (int)$row['d23Vac'] : null,
                    $row['d23NoVac'] ? (int)$row['d23NoVac'] : null,
                    $row['d23NoInfo'] ? (int)$row['d23NoInfo'] : null
                );
                $d59      = new Vaccination(
                    $row['d59Vac'] ? (int)$row['d59Vac'] : null,
                    $row['d59NoVac'] ? (int)$row['d59NoVac'] : null,
                    $row['d59NoInfo'] ? (int)$row['d59NoInfo'] : null
                );
                $dTotal   = new Vaccination(
                    $row['dTotalVac'] ? (int)$row['dTotalVac'] : null,
                    $row['dTotalNoVac'] ? (int)$row['dTotalNoVac'] : null,
                    $row['dTotalNoInfo'] ? (int)$row['dTotalNoInfo'] : null
                );

                $results[$class][$row['cId']] = new CountryRotavirusSummary((int)$row['cId'],$row['cName'],
                    (int)$row['u5'],
                    (int)$row['u5with'],
                    (int)$row['susp'],
                    $withForm,
                    $p12, $p23, $p59, $pTotal,
                    $d12, $d23, $d59, $dTotal
                );
            }
        }

        return $results;
    }
}

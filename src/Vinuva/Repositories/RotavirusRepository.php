<?php
declare(strict_types=1);

namespace Paho\Vinuva\Repositories;

use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Paho\Vinuva\Report\Common\Probable;
use Paho\Vinuva\Report\Rotavirus\Vaccination;
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
        /** @var  $parameter Parameter */
        foreach ($queryBuilder->getParameters() as $position => $parameter) {
            $statement->bindValue($position + 1, $parameter->getValue());
        }

        if ($statement->execute()) {
            $results = [];
            while ($row = $statement->fetch(FetchMode::ASSOCIATIVE)) {
                $probable  = new Probable($row['p12'], $row['p23'], $row['p59'], $row['ptotal']);
                $pUnder12  = new Vaccination($row['positiveU12Vaccinated'], $row['positiveU12NotVaccinated'], $row['positiveU12NoInformation']);
                $pUnder23  = new Vaccination($row['positiveU23Vaccinated'], $row['positiveU23NotVaccinated'], $row['positiveU23NoInformation']);
                $pUnder59  = new Vaccination($row['positiveU59Vaccinated'], $row['positiveU59NotVaccinated'], $row['positiveU59NoInformation']);
                $ptotal    = new Vaccination($row['positiveTotalVaccinated'], $row['positiveTotalNotVaccinated'], $row['positiveTotalNoInformation']);

                $dUnder12  = new Vaccination($row['deathU12Vaccinated'], $row['deathU12NotVaccinated'], $row['deathU12NoInformation']);
                $dUnder23  = new Vaccination($row['deathU23Vaccinated'], $row['deathU23NotVaccinated'], $row['deathU23NoInformation']);
                $dUnder59  = new Vaccination($row['deathU59Vaccinated'], $row['deathU59NotVaccinated'], $row['deathU59NoInformation']);
                $dtotal    = new Vaccination($row['deathTotalVaccinated'], $row['deathTotalNotVaccinated'], $row['deathTotalNoInformation']);
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
}

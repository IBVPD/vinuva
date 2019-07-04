<?php declare(strict_types=1);

namespace Paho\Vinuva\Repositories;

use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Paho\Vinuva\Models\Common\Confirmed;
use Paho\Vinuva\Models\Common\DeathCount;
use Paho\Vinuva\Models\Common\Probable;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Report\Common\Confirmed as SummaryConfirmed;
use Paho\Vinuva\Report\Common\DeathCount as SummaryDeathCount;
use Paho\Vinuva\Report\Common\Probable as SummaryProbable;
use Paho\Vinuva\Report\Country\PneumoniaSummary as CountryPneumoniaSummary;
use Paho\Vinuva\Report\Hospital\Country;
use Paho\Vinuva\Report\Hospital\CountryCollector;
use Paho\Vinuva\Report\Hospital\Pneumonia;
use Paho\Vinuva\Report\PneumoniaSummary;

class PneumoniaRepository extends AbstractRepository
{
    public function getSummaryQuery(QueryBuilder $queryBuilder): array
    {
        $subStrSelect = 'SELECT p0_.country_id, c1_.name, p0_.year, p0_.month as month, 
        SUM(p0_.under5) as u5,SUM(p0_.suspected) as susp, SUM(p0_.suspectedWith) suspWith, 
        SUM(p0_.probable_12) as p12, SUM(p0_.probable_23) as p23, SUM(p0_.probable_59) as p59,SUM(p0_.probable_total) as ptotal,
        SUM(p0_.probable_with_blood_12) as p12blood, SUM(p0_.probable_with_blood_23) as p23blood, SUM(p0_.probable_with_blood_59) as p59blood,SUM(p0_.probable_with_blood_total) as pBloodTotal,
        SUM(p0_.probable_with_pleural_12) as p12pleural, SUM(p0_.probable_with_pleural_23) as p23pleural, SUM(p0_.probable_with_pleural_59) as p59pleural,SUM(p0_.probable_with_pleural_total) as pPleuralTotal,
        SUM(p0_.u12_confirmed_hib) as u12ConfirmedHib,SUM(p0_.u12_confirmed_hi) as u12ConfirmedHi,SUM(p0_.u12_confirmed_nm) as u12ConfirmedNm,SUM(p0_.u12_confirmed_spn) as u12ConfirmedSpn,SUM(p0_.u12_confirmed_other) as u12ConfirmedOther,SUM(p0_.u12_confirmed_contamination) as u12ConfirmedCont,
        SUM(p0_.u23_confirmed_hib) as u23ConfirmedHib,SUM(p0_.u23_confirmed_hi) as u23ConfirmedHi,SUM(p0_.u23_confirmed_nm) as u23ConfirmedNm,SUM(p0_.u23_confirmed_spn) as u23ConfirmedSpn,SUM(p0_.u23_confirmed_other) as u23ConfirmedOther,SUM(p0_.u23_confirmed_contamination) as u23ConfirmedCont,
        SUM(p0_.u59_confirmed_hib) as u59ConfirmedHib,SUM(p0_.u59_confirmed_hi) as u59ConfirmedHi,SUM(p0_.u59_confirmed_nm) as u59ConfirmedNm,SUM(p0_.u59_confirmed_spn) as u59ConfirmedSpn,SUM(p0_.u59_confirmed_other) as u59ConfirmedOther,SUM(p0_.u59_confirmed_contamination) as u59ConfirmedCont,
        SUM(p0_.total_confirmed_hib) as uTConfirmedHib,SUM(p0_.total_confirmed_hi) as uTConfirmedHi,SUM(p0_.total_confirmed_nm) as uTConfirmedNm,SUM(p0_.total_confirmed_spn) as uTConfirmedSpn,SUM(p0_.total_confirmed_other) as uTConfirmedOther,SUM(p0_.total_confirmed_contamination) as uTConfirmedCont,
        SUM(p0_.number_of_deaths_12) as d12, SUM(p0_.number_of_deaths_23) as d23, SUM(p0_.number_of_deaths_59) as d59,SUM(p0_.number_of_deaths_total) as dtotal';

        $groupSql = 'SELECT y.name as countryName, y.year as year, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',u5)) u5, GROUP_CONCAT(CONCAT(y.month,\'=\',susp)) susp, GROUP_CONCAT(CONCAT(y.month,\'=\',suspWith)) suspWith, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',p12)) p12, GROUP_CONCAT(CONCAT(y.month,\'=\',p23)) p23, GROUP_CONCAT(CONCAT(y.month,\'=\',p59)) p59, GROUP_CONCAT(CONCAT(y.month,\'=\',ptotal)) ptotal, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',p12blood)) p12blood, GROUP_CONCAT(CONCAT(y.month,\'=\',p23blood)) p23blood, GROUP_CONCAT(CONCAT(y.month,\'=\',p59blood)) p59blood, GROUP_CONCAT(CONCAT(y.month,\'=\',pBloodTotal)) pBloodTotal, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',p12pleural)) p12pleural, GROUP_CONCAT(CONCAT(y.month,\'=\',p23pleural)) p23pleural, GROUP_CONCAT(CONCAT(y.month,\'=\',p59pleural)) p59pleural, GROUP_CONCAT(CONCAT(y.month,\'=\',pPleuralTotal)) pPleuralTotal, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',u12ConfirmedHib)) confirmed12Hib, GROUP_CONCAT(CONCAT(y.month,\'=\',u23ConfirmedHib)) confirmed23Hib, GROUP_CONCAT(CONCAT(y.month,\'=\',u59ConfirmedHib)) confirmed59Hib, GROUP_CONCAT(CONCAT(y.month,\'=\',uTConfirmedHib)) confirmedTHib, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',u12ConfirmedHi)) confirmed12Hi, GROUP_CONCAT(CONCAT(y.month,\'=\',u23ConfirmedHi)) confirmed23Hi, GROUP_CONCAT(CONCAT(y.month,\'=\',u59ConfirmedHi)) confirmed59Hi, GROUP_CONCAT(CONCAT(y.month,\'=\',uTConfirmedHi)) confirmedTHi, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',u12ConfirmedNm)) confirmed12Nm, GROUP_CONCAT(CONCAT(y.month,\'=\',u23ConfirmedNm)) confirmed23Nm, GROUP_CONCAT(CONCAT(y.month,\'=\',u59ConfirmedNm)) confirmed59Nm, GROUP_CONCAT(CONCAT(y.month,\'=\',uTConfirmedNm)) confirmedTNm, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',u12ConfirmedSpn)) confirmed12Spn, GROUP_CONCAT(CONCAT(y.month,\'=\',u23ConfirmedSpn)) confirmed23Spn, GROUP_CONCAT(CONCAT(y.month,\'=\',u59ConfirmedSpn)) confirmed59Spn, GROUP_CONCAT(CONCAT(y.month,\'=\',uTConfirmedSpn)) confirmedTSpn, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',u12ConfirmedOther)) confirmed12Other, GROUP_CONCAT(CONCAT(y.month,\'=\',u23ConfirmedOther)) confirmed23Other, GROUP_CONCAT(CONCAT(y.month,\'=\',u59ConfirmedOther)) confirmed59Other, GROUP_CONCAT(CONCAT(y.month,\'=\',uTConfirmedOther)) confirmedTOther, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',u12ConfirmedCont)) confirmed12Cont, GROUP_CONCAT(CONCAT(y.month,\'=\',u23ConfirmedCont)) confirmed23Cont, GROUP_CONCAT(CONCAT(y.month,\'=\',u59ConfirmedCont)) confirmed59Cont, GROUP_CONCAT(CONCAT(y.month,\'=\',uTConfirmedCont)) confirmedTCont, 
        GROUP_CONCAT(CONCAT(y.month,\'=\',d12)) d12, GROUP_CONCAT(CONCAT(y.month,\'=\',d23)) d23, GROUP_CONCAT(CONCAT(y.month,\'=\',d59)) d59, GROUP_CONCAT(CONCAT(y.month,\'=\',dtotal)) dtotal';

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
                $probable         = new SummaryProbable($row['p12'], $row['p23'], $row['p59'], $row['ptotal']);
                $probableWBlood   = new SummaryProbable($row['p12blood'], $row['p23blood'], $row['p59blood'], $row['pBloodTotal']);
                $probableWPleural = new SummaryProbable($row['p12pleural'], $row['p23pleural'], $row['p59pleural'], $row['pPleuralTotal']);
                $deathCount       = new SummaryDeathCount($row['d12'], $row['d23'], $row['d59'], $row['dtotal']);
                $u12Confirmed     = new SummaryConfirmed($row['confirmed12Hib'], $row['confirmed12Hi'], $row['confirmed12Nm'], $row['confirmed12Spn'], $row['confirmed12Other'], $row['confirmed12Cont']);
                $u23Confirmed     = new SummaryConfirmed($row['confirmed23Hib'], $row['confirmed23Hi'], $row['confirmed23Nm'], $row['confirmed23Spn'], $row['confirmed23Other'], $row['confirmed23Cont']);
                $u59Confirmed     = new SummaryConfirmed($row['confirmed59Hib'], $row['confirmed59Hi'], $row['confirmed59Nm'], $row['confirmed59Spn'], $row['confirmed59Other'], $row['confirmed59Cont']);
                $totalConfirmed   = new SummaryConfirmed($row['confirmedTHib'], $row['confirmedTHi'], $row['confirmedTNm'], $row['confirmedTSpn'], $row['confirmedTOther'], $row['confirmedTCont']);
                $results[]        = new PneumoniaSummary(
                    $row['countryName'],
                    (int)$row['year'],
                    $row['u5'],
                    $row['susp'],
                    $row['suspWith'],
                    $probable,
                    $probableWBlood,
                    $probableWPleural,
                    $u12Confirmed,
                    $u23Confirmed,
                    $u59Confirmed,
                    $totalConfirmed,
                    $deathCount
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
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getByHospitalSummary(QueryBuilder $queryBuilder, array $results = []): array
    {
        [$sqlSelect, $filteredTerms] = explode('FROM', $queryBuilder->getQuery()->getSQL());

        $sqlSelect = 'SELECT h2_.id as hId, h2_.name as hName, c1_.id cId, c1_.name as cName, SUM(p0_.under5) u5, SUM(p0_.suspected) susp, SUM(p0_.suspectedWith) suspWith, 
        SUM(p0_.probable_12) p12 ,SUM(p0_.probable_23) p23,SUM(p0_.probable_59) p59, SUM(p0_.probable_total) ptotal, 
        SUM(p0_.probable_12) p12blood ,SUM(p0_.probable_23) p23,SUM(p0_.probable_59) p59, SUM(p0_.probable_total) pBloodTotal, 
        SUM(p0_.probable_12) p12pleural ,SUM(p0_.probable_23) p23,SUM(p0_.probable_59) p59, SUM(p0_.probable_total) pPleuralTotal, 
        SUM(p0_.u12_confirmed_hib) confirmed12Hib, SUM(p0_.u12_confirmed_hi) confirmed12Hi, SUM(p0_.u12_confirmed_nm) confirmed12Nm, SUM(p0_.u12_confirmed_spn) confirmed12Spn, SUM(p0_.u12_confirmed_other) confirmed12Other, SUM(p0_.u12_confirmed_contamination) confirmed12Cont,
        SUM(p0_.u23_confirmed_hib) confirmed23Hib, SUM(p0_.u23_confirmed_hi) confirmed23Hi, SUM(p0_.u23_confirmed_nm) confirmed23Nm, SUM(p0_.u23_confirmed_spn) confirmed23Spn, SUM(p0_.u23_confirmed_other) confirmed23Other, SUM(p0_.u23_confirmed_contamination) confirmed23Cont,
        SUM(p0_.u59_confirmed_hib) confirmed59Hib, SUM(p0_.u59_confirmed_hi) confirmed59Hi, SUM(p0_.u59_confirmed_nm) confirmed59Nm, SUM(p0_.u59_confirmed_spn) confirmed59Spn, SUM(p0_.u59_confirmed_other) confirmed59Other, SUM(p0_.u59_confirmed_contamination) confirmed59Cont,
        SUM(p0_.total_confirmed_hib) confirmedTHib, SUM(p0_.total_confirmed_hi) confirmedTHi, SUM(p0_.total_confirmed_nm) confirmedTNm, SUM(p0_.total_confirmed_spn) confirmedTSpn,SUM(p0_.total_confirmed_other) confirmedTOther, SUM(p0_.total_confirmed_contamination) confirmedTCont,
        SUM(p0_.number_of_deaths_12) d12,SUM(p0_.number_of_deaths_23) d23,SUM(p0_.number_of_deaths_59) d59,SUM(p0_.number_of_deaths_total) dtotal';

        $sql       = "$sqlSelect FROM $filteredTerms";
        $statement = $this->entityManager->getConnection()->prepare($sql);
        /** @var  $parameter Parameter */
        foreach ($queryBuilder->getParameters() as $position => $parameter) {
            $statement->bindValue($position + 1, $parameter->getValue());
        }

        if ($statement->execute()) {
            while ($row = $statement->fetch(FetchMode::ASSOCIATIVE)) {
                if (!isset($results[$row['cId']])) {
                    $results[$row['cId']] = new CountryCollector(new Country((int)$row['cId'], $row['cName']));
                }
                $hospital            = Hospital::createDTO((int)$row['hId'], $row['hName']);
                $probable            = new Probable($row['p12'] ? (int)$row['p12'] : null, $row['p23'] ? (int)$row['p23'] : null, $row['p59'] ? (int)$row['p59'] : null, $row['ptotal'] ? (int)$row['ptotal'] : null);
                $probableWithBlood   = new Probable($row['p12blood'] ? (int)$row['p12blood'] : null, $row['p23'] ? (int)$row['p23'] : null, $row['p59'] ? (int)$row['p59'] : null, $row['ptotal'] ? (int)$row['pBloodTotal'] : null);
                $probableWithPleural = new Probable($row['p12pleural'] ? (int)$row['p12pleural'] : null, $row['p23'] ? (int)$row['p23'] : null, $row['p59'] ? (int)$row['p59'] : null, $row['ptotal'] ? (int)$row['pPleuralTotal'] : null);
                $deathCount          = new DeathCount((int)$row['d12'], (int)$row['d23'], (int)$row['d59'], (int)$row['dtotal']);
                $u12Confirmed        = new Confirmed($row['confirmed12Hib'] ? (int)$row['confirmed12Hib'] : null, $row['confirmed12Hi'] ? (int)$row['confirmed12Hi'] : null, $row['confirmed12Nm'] ? (int)$row['confirmed12Nm'] : null, $row['confirmed12Spn'] ? (int)$row['confirmed12Spn'] : null, $row['confirmed12Other'] ? (int)$row['confirmed12Other'] : null, $row['confirmed12Cont'] ? (int)$row['confirmed12Cont'] : null);
                $u23Confirmed        = new Confirmed($row['confirmed23Hib'] ? (int)$row['confirmed23Hib'] : null, $row['confirmed23Hi'] ? (int)$row['confirmed23Hi'] : null, $row['confirmed23Nm'] ? (int)$row['confirmed23Nm'] : null, $row['confirmed23Spn'] ? (int)$row['confirmed23Spn'] : null, $row['confirmed23Other'] ? (int)$row['confirmed23Other'] : null, $row['confirmed23Cont'] ? (int)$row['confirmed23Cont'] : null);
                $u59Confirmed        = new Confirmed($row['confirmed59Hib'] ? (int)$row['confirmed59Hib'] : null, $row['confirmed59Hi'] ? (int)$row['confirmed59Hi'] : null, $row['confirmed59Nm'] ? (int)$row['confirmed59Nm'] : null, $row['confirmed59Spn'] ? (int)$row['confirmed59Spn'] : null, $row['confirmed59Other'] ? (int)$row['confirmed59Other'] : null, $row['confirmed59Cont'] ? (int)$row['confirmed59Cont'] : null);
                $totalConfirmed      = new Confirmed($row['confirmedTHib'] ? (int)$row['confirmedTHib'] : null, $row['confirmedTHi'] ? (int)$row['confirmedTHi'] : null, $row['confirmedTNm'] ? (int)$row['confirmedTNm'] : null, $row['confirmedTSpn'] ? (int)$row['confirmedTSpn'] : null, $row['confirmedTOther'] ? (int)$row['confirmedTOther'] : null, $row['confirmedTCont'] ? (int)$row['confirmedTCont'] : null);
                $results[$row['cId']]->addCase(new Pneumonia($hospital,
                    (int)$row['u5'],
                    (int)$row['susp'],
                    (int)$row['suspWith'],
                    $probable,
                    $probableWithBlood,
                    $probableWithPleural,
                    $u12Confirmed,
                    $u23Confirmed,
                    $u59Confirmed,
                    $totalConfirmed,
                    $deathCount
                ));
            }
        }

        return $results;
    }

    public function getByCountrySummary(QueryBuilder $queryBuilder, array $results): array
    {
        [$sqlSelect, $filteredTerms] = explode('FROM', $queryBuilder->getQuery()->getSQL());

        $sqlSelect = 'SELECT c1_.id cId, c1_.name as cName, SUM(p0_.under5) u5, SUM(p0_.suspected) susp, SUM(p0_.suspectedWith) suspWith, 
        SUM(p0_.probable_12) p12 ,SUM(p0_.probable_23) p23,SUM(p0_.probable_59) p59, SUM(p0_.probable_total) ptotal, 
        SUM(p0_.probable_12) p12blood ,SUM(p0_.probable_23) p23,SUM(p0_.probable_59) p59, SUM(p0_.probable_total) pBloodTotal, 
        SUM(p0_.probable_12) p12pleural ,SUM(p0_.probable_23) p23,SUM(p0_.probable_59) p59, SUM(p0_.probable_total) pPleuralTotal, 
        SUM(p0_.u12_confirmed_hib) confirmed12Hib, SUM(p0_.u12_confirmed_hi) confirmed12Hi, SUM(p0_.u12_confirmed_nm) confirmed12Nm, SUM(p0_.u12_confirmed_spn) confirmed12Spn, SUM(p0_.u12_confirmed_other) confirmed12Other, SUM(p0_.u12_confirmed_contamination) confirmed12Cont,
        SUM(p0_.u23_confirmed_hib) confirmed23Hib, SUM(p0_.u23_confirmed_hi) confirmed23Hi, SUM(p0_.u23_confirmed_nm) confirmed23Nm, SUM(p0_.u23_confirmed_spn) confirmed23Spn, SUM(p0_.u23_confirmed_other) confirmed23Other, SUM(p0_.u23_confirmed_contamination) confirmed23Cont,
        SUM(p0_.u59_confirmed_hib) confirmed59Hib, SUM(p0_.u59_confirmed_hi) confirmed59Hi, SUM(p0_.u59_confirmed_nm) confirmed59Nm, SUM(p0_.u59_confirmed_spn) confirmed59Spn, SUM(p0_.u59_confirmed_other) confirmed59Other, SUM(p0_.u59_confirmed_contamination) confirmed59Cont,
        SUM(p0_.total_confirmed_hib) confirmedTHib, SUM(p0_.total_confirmed_hi) confirmedTHi, SUM(p0_.total_confirmed_nm) confirmedTNm, SUM(p0_.total_confirmed_spn) confirmedTSpn,SUM(p0_.total_confirmed_other) confirmedTOther, SUM(p0_.total_confirmed_contamination) confirmedTCont,
        SUM(p0_.number_of_deaths_12) d12,SUM(p0_.number_of_deaths_23) d23,SUM(p0_.number_of_deaths_59) d59,SUM(p0_.number_of_deaths_total) dtotal';

        $sql       = "$sqlSelect FROM $filteredTerms";
        $statement = $this->entityManager->getConnection()->prepare($sql);
        /** @var  $parameter Parameter */
        foreach ($queryBuilder->getParameters() as $position => $parameter) {
            $statement->bindValue($position + 1, $parameter->getValue());
        }

        if ($statement->execute()) {
            $class = substr(strrchr($this->class, '\\'), 1);
            while ($row = $statement->fetch(FetchMode::ASSOCIATIVE)) {
                $probable            = new Probable($row['p12'] ? (int)$row['p12'] : null, $row['p23'] ? (int)$row['p23'] : null, $row['p59'] ? (int)$row['p59'] : null, $row['ptotal'] ? (int)$row['ptotal'] : null);
                $probableWithBlood   = new Probable($row['p12blood'] ? (int)$row['p12blood'] : null, $row['p23'] ? (int)$row['p23'] : null, $row['p59'] ? (int)$row['p59'] : null, $row['ptotal'] ? (int)$row['pBloodTotal'] : null);
                $probableWithPleural = new Probable($row['p12pleural'] ? (int)$row['p12pleural'] : null, $row['p23'] ? (int)$row['p23'] : null, $row['p59'] ? (int)$row['p59'] : null, $row['ptotal'] ? (int)$row['pPleuralTotal'] : null);
                $deathCount          = new DeathCount((int)$row['d12'], (int)$row['d23'], (int)$row['d59'], (int)$row['dtotal']);
                $u12Confirmed        = new Confirmed($row['confirmed12Hib'] ? (int)$row['confirmed12Hib'] : null, $row['confirmed12Hi'] ? (int)$row['confirmed12Hi'] : null, $row['confirmed12Nm'] ? (int)$row['confirmed12Nm'] : null, $row['confirmed12Spn'] ? (int)$row['confirmed12Spn'] : null, $row['confirmed12Other'] ? (int)$row['confirmed12Other'] : null, $row['confirmed12Cont'] ? (int)$row['confirmed12Cont'] : null);
                $u23Confirmed        = new Confirmed($row['confirmed23Hib'] ? (int)$row['confirmed23Hib'] : null, $row['confirmed23Hi'] ? (int)$row['confirmed23Hi'] : null, $row['confirmed23Nm'] ? (int)$row['confirmed23Nm'] : null, $row['confirmed23Spn'] ? (int)$row['confirmed23Spn'] : null, $row['confirmed23Other'] ? (int)$row['confirmed23Other'] : null, $row['confirmed23Cont'] ? (int)$row['confirmed23Cont'] : null);
                $u59Confirmed        = new Confirmed($row['confirmed59Hib'] ? (int)$row['confirmed59Hib'] : null, $row['confirmed59Hi'] ? (int)$row['confirmed59Hi'] : null, $row['confirmed59Nm'] ? (int)$row['confirmed59Nm'] : null, $row['confirmed59Spn'] ? (int)$row['confirmed59Spn'] : null, $row['confirmed59Other'] ? (int)$row['confirmed59Other'] : null, $row['confirmed59Cont'] ? (int)$row['confirmed59Cont'] : null);
                $totalConfirmed      = new Confirmed($row['confirmedTHib'] ? (int)$row['confirmedTHib'] : null, $row['confirmedTHi'] ? (int)$row['confirmedTHi'] : null, $row['confirmedTNm'] ? (int)$row['confirmedTNm'] : null, $row['confirmedTSpn'] ? (int)$row['confirmedTSpn'] : null, $row['confirmedTOther'] ? (int)$row['confirmedTOther'] : null, $row['confirmedTCont'] ? (int)$row['confirmedTCont'] : null);
                $results[$class][$row['cId']] = new CountryPneumoniaSummary((int)$row['cId'], $row['cName'],
                    (int)$row['u5'],
                    (int)$row['susp'],
                    (int)$row['suspWith'],
                    $probable,
                    $probableWithBlood,
                    $probableWithPleural,
                    $u12Confirmed,
                    $u23Confirmed,
                    $u59Confirmed,
                    $totalConfirmed,
                    $deathCount
                );
            }
        }

        return $results;
    }
}

<?php

namespace App\Form\Filters;

use IntlDateFormatter;
use Locale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class YearMonthFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formatter = new IntlDateFormatter(
            Locale::getDefault(),
            IntlDateFormatter::MEDIUM,
            IntlDateFormatter::NONE,
            // see https://bugs.php.net/bug.php?id=66323
            class_exists('IntlTimeZone', false) ? \IntlTimeZone::createDefault() : null,
            IntlDateFormatter::GREGORIAN,
            null
        );

        $yearRange = range(2000, date('Y'));
        $builder
            ->add('month', ChoiceType::class, ['placeholder' => 'Month', 'choices' => $this->formatTimestamps($formatter, '/[M|L]+/', $this->listMonths(range(1, 12)))])
            ->add('year', ChoiceType::class, ['placeholder' => 'Year', 'choices' => array_combine($yearRange, $yearRange)]);
    }

    private function formatTimestamps(IntlDateFormatter $formatter, $regex, array $timestamps): array
    {
        $pattern             = $formatter->getPattern();
        $timezone            = $formatter->getTimeZoneId();
        $formattedTimestamps = [];

        $formatter->setTimeZone('UTC');

        if (preg_match($regex, $pattern, $matches)) {
            $formatter->setPattern($matches[0]);

            foreach ($timestamps as $timestamp => $choice) {
                $formattedTimestamps[$formatter->format($timestamp)] = $choice;
            }

            // I'd like to clone the formatter above, but then we get a
            // segmentation fault, so let's restore the old state instead
            $formatter->setPattern($pattern);
        }

        $formatter->setTimeZone($timezone);

        return $formattedTimestamps;
    }

    private function listMonths(array $months): array
    {
        $result = [];

        foreach ($months as $month) {
            $result[gmmktime(0, 0, 0, $month, 15)] = $month;
        }

        return $result;
    }
}

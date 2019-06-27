<?php


namespace Paho\Vinuva\Report;


class GroupConcatExploder
{
    public static $defaultMonths = [1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => null, 7 => null, 8 => null, 9 => null, 10 => null, 11 => null, 12 => null, 'total' => null];

    public static function explodeMonths(string $data): array
    {
        $output         = self::$defaultMonths;
        $explodedMonths = explode(',', $data);
        foreach ($explodedMonths as $value) {
            [$month, $verifiedStr] = explode('=', $value);
            $output[$month] = $verifiedStr;
            $output['total'] += $verifiedStr;
        }

        return $output;
    }
}

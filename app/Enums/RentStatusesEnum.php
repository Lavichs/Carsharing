<?php

namespace App\Enums;

enum RentStatusesEnum: int
{
	case Close = 0;
	case Open = 1;
	case ClosedWithIncident = 2;

	public static function randomValue(): string
    {
        $arr = array_column(self::cases(), 'value');

        return $arr[array_rand($arr)];
    }
}
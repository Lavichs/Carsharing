<?php

namespace App\Enums;

enum CarEnginesEnum: int
{
	case Combustion  = 0;
	case Electric = 1;
	case Hybrid = 2;

	public static function randomValue(): string
    {
        $arr = array_column(self::cases(), 'value');

        return $arr[array_rand($arr)];
    }
}
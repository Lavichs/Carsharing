<?php

namespace App\Enums;

enum CarStatusesEnum: int
{
	case Wait = 0;
	case Move = 1;
	case Repair = 2;

	public static function randomValue(): string
    {
        $arr = array_column(self::cases(), 'value');

        return $arr[array_rand($arr)];
    }
}
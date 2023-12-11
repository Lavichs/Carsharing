<?php

namespace App\Enums;

enum OperationTypesEnum: int
{
	case Rent = 0;
	case Replenishment = 1;

	public static function randomValue(): string
    {
        $arr = array_column(self::cases(), 'value');

        return $arr[array_rand($arr)];
    }
}
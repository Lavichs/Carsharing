<?php

namespace App\Enums;

enum OperationTypesEnum: int
{
	case Rent = 0;
	case Fine = 1;
	case Replenishment = 2;
	case Cashback = 3;

	public static function randomValue(): string
    {
        $arr = array_column(self::cases(), 'value');

        return $arr[array_rand($arr)];
    }
}
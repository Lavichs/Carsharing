<?php

namespace App\Enums;

enum UserStatusesEnum: int
{
	case Ordinary = 0;
	case TempBanned = 1;

	public static function randomValue(): string
    {
        $arr = array_column(self::cases(), 'value');

        return $arr[array_rand($arr)];
    }
}
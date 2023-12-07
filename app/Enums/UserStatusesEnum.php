<?php

namespace App\Enums;

enum UserStatusesEnum: int
{
	case Ordinary = 0,
	case TempBanned = 1,
}
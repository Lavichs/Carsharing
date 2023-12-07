<?php

namespace App\Enums;

enum CarStatusesEnum: int
{
	case Wait = 0,
	case Move = 1,
	case Repair = 2,
}
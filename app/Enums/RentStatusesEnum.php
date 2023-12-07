<?php

namespace App\Enums;

enum RentStatusesEnum: int
{
	case Close = 0,
	case Open = 1,
	case ClosedWithIncident = 2,
}
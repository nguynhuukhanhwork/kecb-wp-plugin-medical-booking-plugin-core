<?php

namespace TravelBooking\Config\Enum;

enum LogType: string
{
    case ERROR      = 'error';
    case WARNING    = 'warning';
    case ACTIVE     = 'active';
    case INFO       = 'info';
}

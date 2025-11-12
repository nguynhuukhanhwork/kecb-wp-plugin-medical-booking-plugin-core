<?php

namespace TravelBooking\Config\Enum;

use MyCLabs\Enum\Enum;

Enum NotificationStatus: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
}
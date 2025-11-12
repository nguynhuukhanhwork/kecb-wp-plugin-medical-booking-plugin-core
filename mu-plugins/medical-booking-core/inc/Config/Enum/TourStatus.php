<?php

namespace TravelBooking\Config\Enum;

enum TourStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case FULL = 'full';
}

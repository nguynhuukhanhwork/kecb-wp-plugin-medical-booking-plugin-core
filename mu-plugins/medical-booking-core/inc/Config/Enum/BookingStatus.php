<?php

namespace TravelBooking\Config\Enum;

enum BookingStatus : string
{
    case PENDING   = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
}

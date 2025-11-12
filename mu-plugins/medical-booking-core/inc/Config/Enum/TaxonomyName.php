<?php

namespace TravelBooking\Config\Enum;

ENUM TaxonomyName : string
{
    case TOUR_COST = 'tour_cost';
    case TOUR_LINKED = 'tour_linked';
    case TOUR_LOCATION = 'tour_location';
    case TOUR_PERSON = 'tour_person';
    case TOUR_RATING = 'tour_rating';
    case TOUR_TYPE = 'tour_type';
}
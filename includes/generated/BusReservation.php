<?php

namespace Posterno\SchemaOrg;

/**
 * A reservation for bus travel. 
 * 
 * Note: This type is for information about actual reservations, e.g. in
 * confirmation emails or HTML pages with individual confirmations of
 * reservations. For offers of tickets, use [[Offer]].
 *
 * @see http://schema.org/BusReservation
 *
 * @mixin \Posterno\SchemaOrg\Reservation
 */
class BusReservation extends BaseType
{
}

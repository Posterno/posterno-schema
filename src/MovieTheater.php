<?php

namespace PNO\SchemaOrg;

/**
 * A movie theater.
 *
 * @see http://schema.org/MovieTheater
 *
 * @mixin \PNO\SchemaOrg\CivicStructure
 * @mixin \PNO\SchemaOrg\EntertainmentBusiness
 */
class MovieTheater extends BaseType
{
    /**
     * The number of screens in the movie theater.
     *
     * @param float|float[]|int|int[] $screenCount
     *
     * @return static
     *
     * @see http://schema.org/screenCount
     */
    public function screenCount($screenCount)
    {
        return $this->setProperty('screenCount', $screenCount);
    }

}

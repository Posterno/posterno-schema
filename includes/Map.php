<?php

namespace PNO\SchemaOrg;

/**
 * A map.
 *
 * @see http://schema.org/Map
 *
 * @mixin \PNO\SchemaOrg\CreativeWork
 */
class Map extends BaseType
{
    /**
     * Indicates the kind of Map, from the MapCategoryType Enumeration.
     *
     * @param MapCategoryType|MapCategoryType[] $mapType
     *
     * @return static
     *
     * @see http://schema.org/mapType
     */
    public function mapType($mapType)
    {
        return $this->setProperty('mapType', $mapType);
    }

}

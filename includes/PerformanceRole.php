<?php

namespace PNO\SchemaOrg;

/**
 * A PerformanceRole is a Role that some entity places with regard to a
 * theatrical performance, e.g. in a Movie, TVSeries etc.
 *
 * @see http://schema.org/PerformanceRole
 *
 * @mixin \PNO\SchemaOrg\Role
 */
class PerformanceRole extends BaseType
{
    /**
     * The name of a character played in some acting or performing role, i.e. in
     * a PerformanceRole.
     *
     * @param string|string[] $characterName
     *
     * @return static
     *
     * @see http://schema.org/characterName
     */
    public function characterName($characterName)
    {
        return $this->setProperty('characterName', $characterName);
    }

}

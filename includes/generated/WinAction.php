<?php

namespace Posterno\SchemaOrg;

/**
 * The act of achieving victory in a competitive activity.
 *
 * @see http://schema.org/WinAction
 *
 * @mixin \Posterno\SchemaOrg\AchieveAction
 */
class WinAction extends BaseType
{
    /**
     * A sub property of participant. The loser of the action.
     *
     * @param Person|Person[] $loser
     *
     * @return static
     *
     * @see http://schema.org/loser
     */
    public function loser($loser)
    {
        return $this->setProperty('loser', $loser);
    }

}

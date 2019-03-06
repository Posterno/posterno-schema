<?php

namespace Posterno\SchemaOrg;

/**
 * The act of giving money voluntarily to a beneficiary in recognition of
 * services rendered.
 *
 * @see http://schema.org/TipAction
 *
 * @mixin \Posterno\SchemaOrg\TradeAction
 */
class TipAction extends BaseType
{
    /**
     * A sub property of participant. The participant who is at the receiving
     * end of the action.
     *
     * @param Audience|Audience[]|ContactPoint|ContactPoint[]|Organization|Organization[]|Person|Person[] $recipient
     *
     * @return static
     *
     * @see http://schema.org/recipient
     */
    public function recipient($recipient)
    {
        return $this->setProperty('recipient', $recipient);
    }

}

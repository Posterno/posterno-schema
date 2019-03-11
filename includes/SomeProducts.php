<?php

namespace PNO\SchemaOrg;

/**
 * A placeholder for multiple similar products of the same kind.
 *
 * @see http://schema.org/SomeProducts
 *
 * @mixin \PNO\SchemaOrg\Product
 */
class SomeProducts extends BaseType
{
    /**
     * The current approximate inventory level for the item or items.
     *
     * @param QuantitativeValue|QuantitativeValue[] $inventoryLevel
     *
     * @return static
     *
     * @see http://schema.org/inventoryLevel
     */
    public function inventoryLevel($inventoryLevel)
    {
        return $this->setProperty('inventoryLevel', $inventoryLevel);
    }

}

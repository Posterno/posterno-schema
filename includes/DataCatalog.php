<?php

namespace PNO\SchemaOrg;

/**
 * A collection of datasets.
 *
 * @see http://schema.org/DataCatalog
 *
 * @mixin \PNO\SchemaOrg\CreativeWork
 */
class DataCatalog extends BaseType
{
    /**
     * A dataset contained in this catalog.
     *
     * @param Dataset|Dataset[] $dataset
     *
     * @return static
     *
     * @see http://schema.org/dataset
     */
    public function dataset($dataset)
    {
        return $this->setProperty('dataset', $dataset);
    }

}

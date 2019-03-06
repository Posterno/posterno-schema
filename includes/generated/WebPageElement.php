<?php

namespace Spatie\SchemaOrg;

/**
 * A web page element, like a table or an image.
 *
 * @see http://schema.org/WebPageElement
 *
 * @mixin \Spatie\SchemaOrg\CreativeWork
 */
class WebPageElement extends BaseType
{
    /**
     * A CSS selector, e.g. of a [[SpeakableSpecification]] or
     * [[WebPageElement]]. In the latter case, multiple matches within a page
     * can constitute a single conceptual "Web page element".
     *
     * @param CssSelectorType|CssSelectorType[] $cssSelector
     *
     * @return static
     *
     * @see http://schema.org/cssSelector
     */
    public function cssSelector($cssSelector)
    {
        return $this->setProperty('cssSelector', $cssSelector);
    }

    /**
     * An XPath, e.g. of a [[SpeakableSpecification]] or [[WebPageElement]]. In
     * the latter case, multiple matches within a page can constitute a single
     * conceptual "Web page element".
     *
     * @param XPathType|XPathType[] $xpath
     *
     * @return static
     *
     * @see http://schema.org/xpath
     */
    public function xpath($xpath)
    {
        return $this->setProperty('xpath', $xpath);
    }

}

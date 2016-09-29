<?php

namespace Linkshare\Api\ProductSearch;

use SimpleXMLElement;

final class Result
{
    /**
     * The errors with calling the API.
     *
     * @var Error
     */
    private $error;

    /**
     * The total number of matches.
     *
     * This element gives the total number of results found for a given search.
     * If there is an error, this element will have a value of 0.
     * NOTE: The search system will return a maximum of 4,000 records. For searches that
     * result in more than 4,000 results, the total matches will be -1, indicating that
     * some records that matched the search were not returned.
     *
     * @var int
     */
    private $totalMatches;

    /**
     * The total number of pages.
     *
     * This required element gives the total number of pages for this search result.
     * The number of pages is defined by the formula Total Matches/Results per
     * Page. This is a required element. If there is an error, this element
     * will have a value of 0.
     *
     * @var int
     */
    private $totalPages;

    /**
     * The current page number.
     *
     * This required element gives the current page number. If there is an error,
     * this element will have a value of 0.
     *
     * @var int
     */
    private $pageNumber;

    /**
     * The search result items.
     *
     * @var Item[]
     */
    private $items;

    /**
     * Result constructor.
     *
     * @param SimpleXMLElement $xmlElement
     */
    final public function __construct(SimpleXMLElement $xmlElement)
    {
        // Check if we received an error.
        $this->setError($xmlElement);

        // We don't need to do anything else if we have errors.
        if ($this->hasError()) {
            return;
        }

        $this->setTotalMatches($xmlElement);
        $this->setTotalPages($xmlElement);
        $this->setPageNumber($xmlElement);
        $this->setItems($xmlElement);
    }

    /**
     * Get the errors.
     *
     * @return Error
     */
    final public function error()
    {
        return $this->error;
    }

    /**
     * Determine if the result had errors or not.
     *
     * @return bool
     */
    final public function hasError()
    {
        return isset($this->error);
    }

    /**
     * Set the errors.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setError(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->Errors)) {
            return;
        }

        $this->error = new Error($xmlElement->Errors);
    }

    /**
     * Get the total matches.
     *
     * @return int
     */
    final public function totalMatches()
    {
        return $this->totalMatches;
    }

    /**
     * Set the total matches.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setTotalMatches(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->TotalMatches)) {
            return;
        }

        $this->totalMatches = (int) $xmlElement->TotalMatches;
    }

    /**
     * Get the total pages.
     *
     * @return int
     */
    final public function totalPages()
    {
        return $this->totalPages;
    }

    /**
     * Set the total pages.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setTotalPages(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->TotalPages)) {
            return;
        }

        $this->totalPages = (int) $xmlElement->TotalPages;
    }

    /**
     * Get the page number.
     *
     * @return int
     */
    final public function pageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * Set the page number.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setPageNumber(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->PageNumber)) {
            return;
        }

        $this->pageNumber = (int) $xmlElement->PageNumber;
    }

    /**
     * Get the items.
     *
     * @return Item[]
     */
    final public function items()
    {
        return $this->items;
    }

    /**
     * Set the items.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setItems(SimpleXMLElement $xmlElement)
    {
        $this->items = [];

        if (! isset($xmlElement->item)) {
            return;
        }

        foreach ($xmlElement->item as $item) {
            $this->items[] = new Item($item);
        }
    }
}

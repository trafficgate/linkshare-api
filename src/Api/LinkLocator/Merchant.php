<?php

namespace Linkshare\Api\LinkLocator;

use SimpleXMLElement;

final class Merchant
{
    /**
     * The status of your application to this advertiser’s program.
     *
     * @var string
     */
    private $applicationStatus;

    /**
     * The advertiser’s categories.
     *
     * @var int[]
     */
    private $categories;

    /**
     * The advertiser’s LinkShare ID number.
     *
     * @var int
     */
    private $id;

    /**
     * The name of the advertiser.
     *
     * @var string
     */
    private $name;

    /**
     * The offer.
     *
     * @var Offer
     */
    private $offer;

    /**
     * Merchant constructor.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final public function __construct(SimpleXMLElement $xmlElement)
    {
        $this->setApplicationStatus($xmlElement);
        $this->setCategories($xmlElement);
        $this->setId($xmlElement);
        $this->setName($xmlElement);
        $this->setOffer($xmlElement);
    }

    /**
     * Get the application status.
     *
     * @return string
     */
    final public function applicationStatus()
    {
        return $this->applicationStatus;
    }

    /**
     * Set the application status.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setApplicationStatus(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->applicationstatus)) {
            return;
        }

        $this->applicationStatus = trim($xmlElement->applicationstatus);
    }

    /**
     * Get the categories.
     *
     * @return \int[]
     */
    final public function categories()
    {
        return $this->categories;
    }

    /**
     * Set the merchant's categories.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setCategories(SimpleXMLElement $xmlElement)
    {
        $this->categories = [];

        if (! isset($xmlElement->categories)) {
            return;
        }

        foreach (explode(' ', $xmlElement->categories) as $category) {
            if (is_numeric($category)) {
                $this->categories[] = $category;
            }
        }
    }

    /**
     * Get the merchant ID.
     *
     * @return int
     */
    final public function id()
    {
        return $this->id;
    }

    /**
     * Set the merchant ID.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setId(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->mid)) {
            return;
        }

        $this->id = (int) $xmlElement->mid;
    }

    /**
     * Get the merchant name.
     *
     * @return string
     */
    final public function name()
    {
        return $this->name;
    }

    /**
     * Set the merchant name.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setName(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->name)) {
            return;
        }

        $this->name = trim($xmlElement->name);
    }

    /**
     * Get the offer.
     *
     * @return Offer
     */
    final public function offer()
    {
        return $this->offer;
    }

    /**
     * Set the offer.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setOffer(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->offer)) {
            return;
        }

        $this->offer = new Offer($xmlElement->offer);
    }

    final public function __toString()
    {
        $columnFormat = '%-23s %s'.PHP_EOL;

        $s = '';
        $s .= sprintf($columnFormat, 'Merchant ID', $this->id());
        $s .= sprintf($columnFormat, 'Merchant Name', $this->name());
        $s .= sprintf($columnFormat, 'Application Status', $this->applicationStatus());
        $s .= sprintf($columnFormat, 'Categories', implode(', ', $this->categories()));
        $s .= (string) $this->offer();

        return $s;
    }
}

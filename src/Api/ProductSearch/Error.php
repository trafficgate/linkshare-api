<?php

namespace Linkshare\Api\ProductSearch;

use SimpleXMLElement;

final class Error
{
    /**
     * The error ID.
     *
     * @var int
     */
    private $id;

    /**
     * The error text.
     *
     * @var string
     */
    private $text;

    /**
     * Error constructor.
     *
     * @param SimpleXMLElement $xmlElement
     */
    final public function __construct(SimpleXMLElement $xmlElement)
    {
        $this->setId($xmlElement);
        $this->setText($xmlElement);
    }

    /**
     * Get the error ID.
     *
     * @return int
     */
    final public function id()
    {
        return $this->id;
    }

    /**
     * Set the error ID.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setId(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->ErrorID)) {
            return;
        }

        $this->id = (int) $xmlElement->ErrorID;
    }

    /**
     * Get the error text.
     *
     * @return string
     */
    final public function text()
    {
        return $this->text;
    }

    /**
     * Set the error text.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setText(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->ErrorText)) {
            return;
        }

        $this->text = trim($xmlElement->ErrorText);
    }
}

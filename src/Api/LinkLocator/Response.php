<?php

namespace Linkshare\Api\LinkLocator;

use SimpleXMLElement;

final class Response
{
    /**
     * @var Merchant[]
     */
    private $merchants;

    /**
     * @var Fault
     */
    private $fault;

    /**
     * Response constructor.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final public function __construct(SimpleXMLElement $xmlElement)
    {
        $this->setFault($xmlElement);

        if ($this->hasFault()) {
            return;
        }

        $this->setMerchants($xmlElement);
    }

    /**
     * Get the fault.
     *
     * @return Fault
     */
    final public function fault()
    {
        return $this->fault;
    }

    /**
     * Set the fault.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setFault(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->faultstring)) {
            return;
        }

        if (! isset($xmlElement->detail)) {
            return;
        }

        $this->fault = new Fault($xmlElement);
    }

    /**
     * If there are any faults or not.
     *
     * @return bool
     */
    final public function hasFault()
    {
        return isset($this->fault);
    }

    /**
     * Get the merchants.
     *
     * @return Merchant[]
     */
    final public function merchants()
    {
        return $this->merchants;
    }

    /**
     * Set the merchants.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setMerchants(SimpleXMLElement $xmlElement)
    {
        $this->merchants = [];

        if (! isset($xmlElement->return)) {
            return;
        }

        foreach ($xmlElement->return as $merchant) {
            $this->merchants[] = new Merchant($merchant);
        }
    }
}

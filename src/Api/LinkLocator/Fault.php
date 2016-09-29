<?php

namespace Linkshare\Api\LinkLocator;

use SimpleXMLElement;

final class Fault
{
    /**
     * The fault.
     *
     * @var string
     */
    private $fault;

    /**
     * The fault message.
     *
     * @var string
     */
    private $message;

    /**
     * Fault constructor.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final public function __construct(SimpleXMLElement $xmlElement)
    {
        $this->setFault($xmlElement);
        $this->setMessage($xmlElement);
    }

    /**
     * Get the fault.
     *
     * @return string
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

        $this->fault = trim($xmlElement->faultstring);
    }

    /**
     * Get the message.
     *
     * @return string
     */
    final public function message()
    {
        return $this->message;
    }

    /**
     * Set the message.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setMessage(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->detail)) {
            return;
        }

        if (! isset($xmlElement->detail->linklocfault)) {
            return;
        }

        if (! isset($xmlElement->detail->linklocfault->message)) {
            return;
        }

        $this->message = trim($xmlElement->detail->linklocfault->message);
    }
}

<?php

namespace Linkshare\Exceptions;

use Exception;
use SimpleXMLElement;

class LinkshareApiAuthorizationException extends Exception
{
    const INVALID_XML             = 'Invalid XML provided.';
    const MESSAGE_TAG_MISSING     = '<message> missing.';
    const DESCRIPTION_TAG_MISSING = '<description> missing.';

    /**
     * LinkshareApiAuthorizationException constructor.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xml
     * @param int                                 $code
     * @param Exception|null                      $previous
     */
    public function __construct(SimpleXMLElement $xml, $code = 0, Exception $previous = null)
    {
        $message = $this->processMessage($xml);
        $code    = $this->processCode($xml, $code);

        return parent::__construct($message, $code, $previous);
    }

    /**
     * Process the exception message.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xml
     *
     * @return string
     */
    protected function processMessage(SimpleXMLElement $xml)
    {
        $message = static::INVALID_XML;

        if (! isset($xml->message)) {
            $message .= ' '.static::MESSAGE_TAG_MISSING;
        }

        if (! isset($xml->description)) {
            $message .= ' '.static::DESCRIPTION_TAG_MISSING;
        }

        if ($message === static::INVALID_XML) {
            $message = sprintf('%-s: %-s', $xml->message, $xml->description);
        }

        return $message;
    }

    /**
     * Process the exception code.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xml
     * @param $code
     *
     * @return int
     */
    protected function processCode(SimpleXMLElement $xml, $code)
    {
        if (isset($xml->code)) {
            $code = (int) $xml->code;
        }

        return $code;
    }
}

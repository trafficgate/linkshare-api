<?php

namespace Linkshare\Exceptions;

use Exception;

class MissingFieldException extends Exception
{
    const MISSING_FIELDS = 'Missing fields:';

    /**
     * MissingFieldException constructor.
     *
     * @param array|string   $fields
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($fields, $code = 0, Exception $previous = null)
    {
        $message = $this->processMessage($fields);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Generate message.
     *
     * @param array|string $fields
     *
     * @return string
     */
    private function processMessage($fields)
    {
        if (! is_array($fields)) {
            $fields = [$fields];
        }

        return static::MISSING_FIELDS.' '.implode(', ', $fields);
    }
}

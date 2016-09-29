<?php

namespace Linkshare\Exceptions;

use Exception;

class ResourceUnavailableException extends Exception
{
    const INVALID_JSON                    = 'Invalid JSON array provided.';
    const FAULT_INDEX_MISSING             = '"fault" index must exist.';
    const FAULT_MESSAGE_INDEX_MISSING     = '"fault" index must have "message" index.';
    const FAULT_DESCRIPTION_INDEX_MISSING = '"fault" index must have "description" index.';

    /**
     * ResourceUnavailableException constructor.
     *
     * @param array $json
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(array $json, $code = 0, Exception $previous = null)
    {
        if (! $this->validateJson($json)) {
            $message = self::INVALID_JSON.' '.static::FAULT_INDEX_MISSING;

            return parent::__construct($message, $code, $previous);
        };

        $fault = $json['fault'];

        $message = $this->processMessage($fault);
        $code    = $this->processCode($fault, $code);

        parent::__construct($message, $code, $previous);
    }

    /**
     * Validate the given json input.
     *
     * @param array $json
     * @return bool
     */
    protected function validateJson(array $json)
    {
        $validation = true;

        if (! isset($json['fault'])) {
            $validation = false;
        }

        return $validation;
    }

    /**
     * Process the exception message.
     *
     * @param array $fault
     * @return string
     */
    protected function processMessage(array $fault)
    {
        $message = self::INVALID_JSON;

        if (! isset($fault['message'])) {
            $message .= ' '.static::FAULT_MESSAGE_INDEX_MISSING;
        }

        if (! isset($fault['description'])) {
            $message .= ' '.static::FAULT_DESCRIPTION_INDEX_MISSING;
        }

        if ($message === self::INVALID_JSON) {
            $message = sprintf('%-s: %-s', $fault['message'], $fault['description']);
        }

        return $message;
    }

    /**
     * Process the exception code.
     *
     * @param array $fault
     * @param int $code
     * @return int
     */
    protected function processCode(array $fault, $code)
    {
        if (! isset($fault['code'])) {
            return $code;
        }

        return $fault['code'];
    }
}

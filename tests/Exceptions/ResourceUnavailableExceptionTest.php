<?php

namespace Linkshare\Exceptions;

use Linkshare\Exceptions\ResourceUnavailableException;
use PHPUnit_Framework_TestCase;
use TypeError;

class ResourceUnavailableExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateNewException()
    {
        $message     = 'test_message';
        $description = 'test_description';
        $code        = 100;

        $json = [
            'fault' => [
                'message'     => $message,
                'description' => $description,
                'code'        => $code,
            ],
        ];

        $exception = new ResourceUnavailableException($json);
        $this->assertEquals("{$message}: {$description}", $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    /**
     * @requires PHP 7.0
     * @expectedException TypeError
     */
    public function testCreateNewExceptionWithNull()
    {
        new ResourceUnavailableException(null);
    }

    public function testCreateNewExceptionWithNoFaultIndex()
    {
        $json = [];

        $exception = new ResourceUnavailableException($json);
        $this->assertEquals(
            ResourceUnavailableException::INVALID_JSON.
            ' '.ResourceUnavailableException::FAULT_INDEX_MISSING,
            $exception->getMessage()
        );
        $this->assertEquals(0, $exception->getCode());
    }

    public function testCreateNewExceptionWithNoFaultIndices()
    {
        $json = [
            'fault' => [],
        ];

        $exception = new ResourceUnavailableException($json);
        $this->assertEquals(
            ResourceUnavailableException::INVALID_JSON.
            ' '.ResourceUnavailableException::FAULT_MESSAGE_INDEX_MISSING.
            ' '.ResourceUnavailableException::FAULT_DESCRIPTION_INDEX_MISSING,
            $exception->getMessage()
        );
        $this->assertEquals(0, $exception->getCode());
    }

    public function testCreateNewExceptionWithNoFaultMessageIndex()
    {
        $description = 'test_description';

        $json = [
            'fault' => [
                'description' => $description,
            ],
        ];

        $exception = new ResourceUnavailableException($json);
        $this->assertEquals(
            ResourceUnavailableException::INVALID_JSON.
            ' '.ResourceUnavailableException::FAULT_MESSAGE_INDEX_MISSING,
            $exception->getMessage()
        );
        $this->assertEquals(0, $exception->getCode());
    }

    public function testCreateNewExceptionWithNoFaultDescriptionIndex()
    {
        $message     = 'test_message';

        $json = [
            'fault' => [
                'message'     => $message,
            ],
        ];

        $exception = new ResourceUnavailableException($json);
        $this->assertEquals(
            ResourceUnavailableException::INVALID_JSON.
            ' '.ResourceUnavailableException::FAULT_DESCRIPTION_INDEX_MISSING,
            $exception->getMessage()
        );
        $this->assertEquals(0, $exception->getCode());
    }

    public function testCreateNewExceptionWithNoFaultCodeIndex()
    {
        $message     = 'test_message';
        $description = 'test_description';

        $json = [
            'fault' => [
                'message'     => $message,
                'description' => $description,
            ],
        ];

        $exception = new ResourceUnavailableException($json);
        $this->assertEquals("{$message}: {$description}", $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
    }
}

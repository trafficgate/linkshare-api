<?php

namespace Linkshare\Api\ProductSearch;

use Linkshare\Api\ProductSearch\Error;
use Linkshare\Api\ProductSearchTestCase;
use SimpleXMLElement;
use TypeError;

class ErrorTest extends ProductSearchTestCase
{
    public function testCreateError()
    {
        $xmlElement = new SimpleXMLElement($this->getErrorsXml($this->error));
        $error      = new Error($xmlElement);

        $this->assertSame($this->error['id'], $error->id());
        $this->assertSame($this->error['text'], $error->text());
    }

    /**
     * @requires PHP 7.0
     * @expectedException TypeError
     */
    public function testCreateErrorWithNull()
    {
        new Error(null);
    }

    public function testCreateErrorWithEmptyXmlBody()
    {
        $xml = new SimpleXMLElement(
            '<result>'.
            '<Errors>'.
            '</Errors>'.
            '</result>'
        );

        $error = new Error($xml->Errors);
        $this->assertNull($error->id());
        $this->assertNull($error->text());
    }
}

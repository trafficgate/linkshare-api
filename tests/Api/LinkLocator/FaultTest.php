<?php

namespace Linkshare\Api\LinkLocator;

use Linkshare\Api\LinkLocator\Fault;
use Linkshare\Helpers\XMLHelper;
use Linkshare\Api\LinkLocatorTestCase;
use SimpleXMLElement;
use TypeError;

class FaultTest extends LinkLocatorTestCase
{
    public function testCreateFaultWithTidyXml()
    {
        $xml = new SimpleXMLElement(XMLHelper::tidy($this->getInvalidResultXml($this->fault)));

        $fault = new Fault($xml);
        $this->assertSame($this->fault['fault_string'], $fault->fault());
        $this->assertSame($this->fault['message'], $fault->message());
    }

    /**
     * @requires PHP 7.0
     * @expectedException TypeError
     */
    public function testCreateErrorWithNull()
    {
        new Fault(null);
    }

    public function testCreateFaultWithUntidyXml()
    {
        $xml = new SimpleXMLElement(
            $this->getInvalidResultXml($this->fault),
            $options = 0,
            $dataIsUrl = false,
            $ns = 'ns1',
            $isPrefix = true
        );

        $fault = new Fault($xml);
        $this->assertEquals($this->fault['fault_string'], $fault->fault());
        $this->assertNull($fault->message());
    }

    public function testCreateFaultWithEmptyXmlBody()
    {
        $xml = new SimpleXMLElement(
            XMLHelper::tidy(
                '<ns1:XMLFault xmlns:ns1="http://cxf.apache.org/bindings/xformat">'.
                '</ns1:XMLFault>'
            ),
            $options = 0,
            $dataIsUrl = false,
            $ns = 'ns1',
            $isPrefix = true
        );

        $fault = new Fault($xml);
        $this->assertNull($fault->fault());
        $this->assertNull($fault->message());
    }
}

<?php

namespace Linkshare\Exceptions;

use PHPUnit_Framework_TestCase;
use SimpleXMLElement;
use TypeError;

class LinkshareApiAuthorizationExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateNewException()
    {
        $message     = 'test_message';
        $description = 'test_description';
        $code        = 100;

        $xml = new SimpleXMLElement(
            '<ams:fault xmlns:ams="http://wso2.org/apimanager/security">'.
            "<ams:code>$code</ams:code>".
            "<ams:message>$message</ams:message>".
            "<ams:description>$description</ams:description>".
            '</ams:fault>',
            $options = 0,
            $dataIsUrl = false,
            $ns = 'ams',
            $isPrefix = true
        );

        $exception = new LinkshareApiAuthorizationException($xml);
        $this->assertEquals("{$message}: {$description}", $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    /**
     * @requires PHP 7.0
     * @expectedException TypeError
     */
    public function testCreateNewExceptionWithNoXml()
    {
        new LinkshareApiAuthorizationException(null);
    }

    public function testCreateNewExceptionWithNoXmlBody()
    {
        $xml = new SimpleXMLElement(
            '<ams:fault xmlns:ams="http://wso2.org/apimanager/security">'.
            '</ams:fault>',
            $options = 0,
            $dataIsUrl = false,
            $ns = 'ams',
            $isPrefix = true
        );

        $exception = new LinkshareApiAuthorizationException($xml);
        $this->assertEquals(
            LinkshareApiAuthorizationException::INVALID_XML.
            ' '.LinkshareApiAuthorizationException::MESSAGE_TAG_MISSING.
            ' '.LinkshareApiAuthorizationException::DESCRIPTION_TAG_MISSING,
            $exception->getMessage()
        );
        $this->assertEquals(0, $exception->getCode());
    }

    public function testCreateNewExceptionWithNoXmlMessageTag()
    {
        $description = 'test_description';
        $code        = 100;

        $xml = new SimpleXMLElement(
            '<ams:fault xmlns:ams="http://wso2.org/apimanager/security">'.
            "<ams:code>$code</ams:code>".
            "<ams:description>$description</ams:description>".
            '</ams:fault>',
            $options = 0,
            $dataIsUrl = false,
            $ns = 'ams',
            $isPrefix = true
        );

        $exception = new LinkshareApiAuthorizationException($xml);
        $this->assertEquals(
            LinkshareApiAuthorizationException::INVALID_XML.
            ' '.LinkshareApiAuthorizationException::MESSAGE_TAG_MISSING,
            $exception->getMessage()
        );
    }

    public function testCreateNewExceptionWithNoXmlDescriptionTag()
    {
        $message     = 'test_message';
        $code        = 100;

        $xml = new SimpleXMLElement(
            '<ams:fault xmlns:ams="http://wso2.org/apimanager/security">'.
            "<ams:code>$code</ams:code>".
            "<ams:message>$message</ams:message>".
            '</ams:fault>',
            $options = 0,
            $dataIsUrl = false,
            $ns = 'ams',
            $isPrefix = true
        );

        $exception = new LinkshareApiAuthorizationException($xml);
        $this->assertEquals(
            LinkshareApiAuthorizationException::INVALID_XML.
            ' '.LinkshareApiAuthorizationException::DESCRIPTION_TAG_MISSING,
            $exception->getMessage()
        );
    }

    public function testCreateNewExceptionWithNoXmlCodeTag()
    {
        $message     = 'test_message';
        $description = 'test_description';

        $xml = new SimpleXMLElement(
            '<ams:fault xmlns:ams="http://wso2.org/apimanager/security">'.
            "<ams:message>$message</ams:message>".
            "<ams:description>$description</ams:description>".
            '</ams:fault>',
            $options = 0,
            $dataIsUrl = false,
            $ns = 'ams',
            $isPrefix = true
        );

        $exception = new LinkshareApiAuthorizationException($xml);
        $this->assertEquals("{$message}: {$description}", $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
    }
}

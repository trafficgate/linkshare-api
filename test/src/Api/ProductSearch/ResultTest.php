<?php

namespace Linkshare\Test\Api\ProductSearch;

use Linkshare\Api\ProductSearch\Error;
use Linkshare\Api\ProductSearch\Item;
use Linkshare\Api\ProductSearch\Result;
use Linkshare\Test\Api\ProductSearchTestCase;
use SimpleXMLElement;
use TypeError;

class ResultTest extends ProductSearchTestCase
{
    public function testCreateValidResult()
    {
        $xml = new SimpleXMLElement(
            $this->getValidResultXml(
                $this->totalMatches,
                $this->totalPages,
                $this->pageNumber,
                $this->items
            )
        );

        $result = new Result($xml);
        $this->assertFalse($result->hasError());
        $this->assertEmpty($result->error());
        $this->assertEquals($this->totalMatches, $result->totalMatches());
        $this->assertEquals($this->totalPages, $result->totalPages());
        $this->assertEquals($this->pageNumber, $result->pageNumber());
        $this->assertCount(count($this->items), $result->items());
        foreach ($result->items() as $item) {
            $this->assertInstanceOf(Item::class, $item);
        }
    }

    /**
     * @requires PHP 7.0
     * @expectedException TypeError
     */
    public function testCreateResultWithNull()
    {
        new Result(null);
    }

    public function testCreateResultWithEmptyBody()
    {
        $xml    = new SimpleXMLElement('<result></result>');
        $result = new Result($xml);
        $this->assertNull($result->error());
        $this->assertNull($result->totalMatches());
        $this->assertNull($result->totalPages());
        $this->assertNull($result->pageNumber());
        $this->assertEmpty($result->items());
    }

    public function testCreateInvalidResult()
    {
        $xml = new SimpleXMLElement(
            $this->getInvalidResultXml($this->error)
        );

        $result = new Result($xml);
        $this->assertTrue($result->hasError());
        $this->assertInstanceOf(Error::class, $result->error());
        $this->assertNull($result->totalMatches());
        $this->assertNull($result->totalPages());
        $this->assertNull($result->pageNumber());
        $this->assertEmpty($result->items());
    }
}

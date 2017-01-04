<?php

namespace Linkshare\Test\Api\ProductSearch;

use Linkshare\Api\ProductSearch\Item;
use Linkshare\Api\ProductSearchTestCase;
use SimpleXMLElement;

class ItemTest extends ProductSearchTestCase
{
    public function testCreateWithValidXml()
    {
        $xml = new SimpleXMLElement('<result>'.$this->getItemXml($this->items).'</result>');
        $this->assertTrue(isset($xml->item));
        $this->assertGreaterThan(0, count($xml->item));
        $item = new Item($xml->item[0]);
        $this->assertEquals($this->items[0]['merchant_id'], $item->merchantId());
        $this->assertEquals($this->items[0]['merchant_name'], $item->merchantName());
        $this->assertEquals($this->items[0]['link_id'], $item->linkId());
        $this->assertEquals($this->items[0]['created_on'], $item->createdOn());
        $this->assertEquals($this->items[0]['sku'], $item->sku());
        $this->assertEquals($this->items[0]['product_name'], $item->productName());
        $this->assertEquals($this->items[0]['primary_categories'], $item->primaryCategories());
        $this->assertEquals($this->items[0]['secondary_categories'], $item->secondaryCategories());
        $this->assertEquals($this->items[0]['price_currency'], $item->retailPriceCurrency());
        $this->assertEquals($this->items[0]['price'], $item->retailPrice());
        $this->assertEquals($this->items[0]['upc_code'], $item->upcCode());
        $this->assertEquals($this->items[0]['short_description'], $item->shortDescription());
        $this->assertEquals($this->items[0]['long_description'], $item->longDescription());
        $this->assertEquals($this->items[0]['sale_price_currency'], $item->salePriceCurrency());
        $this->assertEquals($this->items[0]['sale_price'], $item->salePrice());
        $this->assertEquals($this->items[0]['keywords'], $item->keywords());
        $this->assertEquals($this->items[0]['link_url'], $item->linkUrl());
        $this->assertEquals($this->items[0]['image_url'], $item->imageUrl());
    }

    public function testCreateWithEmptyBodyXml()
    {
        $xml  = new SimpleXMLElement('<result><item></item></result>');
        $item = new Item($xml->item);
        $this->assertNull($item->merchantId());
        $this->assertNull($item->merchantName());
        $this->assertNull($item->linkId());
        $this->assertNull($item->createdOn());
        $this->assertNull($item->sku());
        $this->assertNull($item->productName());
        $this->assertNull($item->primaryCategories());
        $this->assertNull($item->secondaryCategories());
        $this->assertNull($item->retailPriceCurrency());
        $this->assertNull($item->retailPrice());
        $this->assertNull($item->upcCode());
        $this->assertNull($item->shortDescription());
        $this->assertNull($item->longDescription());
        $this->assertNull($item->salePriceCurrency());
        $this->assertNull($item->salePrice());
        $this->assertNull($item->keywords());
        $this->assertNull($item->linkUrl());
        $this->assertNull($item->imageUrl());
    }
}

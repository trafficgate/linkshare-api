<?php

namespace Linkshare\Test\Api;

use Carbon\Carbon;
use PHPUnit_Framework_TestCase;

class ProductSearchTestCase extends PHPUnit_Framework_TestCase
{
    public $error;
    public $totalMatches;
    public $totalPages;
    public $pageNumber;
    public $items;

    public function setUp()
    {
        $this->error = [
            'id'   => 100,
            'text' => 'test_text',
        ];

        $this->items = [
            [
                'merchant_id'          => 1,
                'merchant_name'        => 'test_merchant_1',
                'link_id'              => 1,
                'created_on'           => Carbon::now(),
                'sku'                  => 'test_sku_1',
                'product_name'         => 'test_product_1',
                'primary_categories'   => ['test_primary_category_1', 'test_primary_category_2'],
                'secondary_categories' => ['test_secondary_category_1', 'test_secondary_category_2'],
                'price_currency'       => 'test_price_currency_1',
                'price'                => 2.99,
                'upc_code'             => 'test_upc_code_1',
                'short_description'    => 'test_short_description_1',
                'long_description'     => 'test_long_description_1',
                'sale_price_currency'  => 'test_sale_price_currency_1',
                'sale_price'           => 1.99,
                'keywords'             => ['test_keyword_1', 'test_keyword_2'],
                'link_url'             => 'test_link_url_1',
                'image_url'            => 'test_image_url_1',
            ],
            [
                'merchant_id'          => 2,
                'merchant_name'        => 'test_merchant_2',
                'link_id'              => 2,
                'created_on'           => Carbon::now(),
                'sku'                  => 'test_sku_2',
                'product_name'         => 'test_product_2',
                'primary_categories'   => ['test_primary_category_1', 'test_primary_category_2'],
                'secondary_categories' => ['test_secondary_category_1', 'test_secondary_category_2'],
                'price_currency'       => 'test_price_currency_2',
                'price'                => 4.99,
                'upc_code'             => 'test_upc_code_2',
                'short_description'    => 'test_short_description_2',
                'long_description'     => 'test_long_description_2',
                'sale_price_currency'  => 'test_sale_price_currency_2',
                'sale_price'           => 3.99,
                'keywords'             => ['test_keyword_1', 'test_keyword_2'],
                'link_url'             => 'test_link_url_2',
                'image_url'            => 'test_image_url_2',
            ],
        ];

        $this->totalMatches = count($this->items);
        $this->totalPages   = 1;
        $this->pageNumber   = 1;
    }

    public function getValidResultXml($totalMatches, $totalPages, $pageNumber, array $items)
    {
        return
            '<result>'.
            "<TotalMatches>{$totalMatches}</TotalMatches>".
            "<TotalPages>{$totalPages}</TotalPages>".
            "<PageNumber>{$pageNumber}</PageNumber>".
            $this->getItemXml($items).
            '</result>';
    }

    public function getItemXml(array $items)
    {
        $itemXml = '';

        foreach ($items as $item) {
            $itemXml .=
                '<item>'.
                "<mid>{$item['merchant_id']}</mid>".
                "<merchantname>{$item['merchant_name']}</merchantname>".
                "<linkid>{$item['link_id']}</linkid>".
                '<createdon>'.$item['created_on']->format('Y-m-d/H:i:s').'</createdon>'.
                "<sku>{$item['sku']}</sku>".
                "<productname>{$item['product_name']}</productname>".
                '<category>'.
                '<primary>'.implode('~~', $item['primary_categories']).'</primary>'.
                '<secondary>'.implode('~~', $item['secondary_categories']).'</secondary>'.
                '</category>'.
                "<price currency=\"{$item['price_currency']}\">{$item['price']}</price>".
                "<upccode>{$item['upc_code']}</upccode>".
                '<description>'.
                "<short>{$item['short_description']}</short>".
                "<long>{$item['long_description']}</long>".
                '</description>'.
                "<saleprice currency=\"{$item['sale_price_currency']}\">{$item['sale_price']}</saleprice>".
                '<keywords>'.implode('~~', $item['keywords']).'</keywords>'.
                "<linkurl>{$item['link_url']}</linkurl>".
                "<imageurl>{$item['image_url']}</imageurl>".
                '</item>';
        }

        return $itemXml;
    }

    public function getInvalidResultXml(array $errors)
    {
        return
            '<result>'.
            $this->getErrorsXml($errors).
            '</result>';
    }

    public function getErrorsXml(array $error)
    {
        $errorsXml =
            '<Errors>'.
            "<ErrorID>{$error['id']}</ErrorID>".
            "<ErrorText>{$error['text']}</ErrorText>".
            '</Errors>';

        return $errorsXml;
    }
}

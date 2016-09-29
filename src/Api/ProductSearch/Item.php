<?php

namespace Linkshare\Api\ProductSearch;

use Carbon\Carbon;
use SimpleXMLElement;

final class Item
{
    const DATE_FORMAT = 'Y-m-d/H:i:s';

    /**
     * The advertiser ID.
     *
     * @var int
     */
    private $merchantId;

    /**
     * The advertiser name.
     *
     * @var string
     */
    private $merchantName;

    /**
     * The link ID.
     *
     * @var int
     */
    private $linkId;

    /**
     * The date of creation.
     *
     * @var Carbon
     */
    private $createdOn;

    /**
     * The SKU.
     *
     * @var string
     */
    private $sku;

    /**
     * The product name.
     *
     * @var string
     */
    private $productName;

    /**
     * The primary categories.
     *
     * @var string[]
     */
    private $primaryCategories;

    /**
     * The secondary categories.
     *
     * @var string[]
     */
    private $secondaryCategories;

    /**
     * The retail price.
     *
     * @var float
     */
    private $retailPrice;

    /**
     * The currency of the retail price.
     *
     * @var string
     */
    private $retailPriceCurrency;

    /**
     * The UPC code.
     *
     * @var string
     */
    private $upcCode;

    /**
     * The short description.
     *
     * @var string
     */
    private $shortDescription;

    /**
     * The long description.
     *
     * @var string
     */
    private $longDescription;

    /**php
     * The sale price.
     *
     * @var float
     */
    private $salePrice;

    /**
     * The currency of the sale price.
     *
     * @var string
     */
    private $salePriceCurrency;

    /**
     * The keywords associated with the product.
     *
     * The keywords that are attributed to the product.
     *
     * @var string[]
     */
    private $keywords;

    /**
     * The link URL.
     *
     * The link that the user clicks to be taken to the advertiser’s page and purchase the product listed.
     *
     * @var string
     */
    private $linkUrl;

    /**
     * The image URL.
     *
     * The link that points to an image of the product. This URL will be on the advertiser’s site.
     *
     * @var string
     */
    private $imageUrl;

    /**
     * Item constructor.
     *
     * @param SimpleXMLElement $xmlElement
     */
    final public function __construct(SimpleXMLElement $xmlElement)
    {
        $this->setMerchantId($xmlElement);
        $this->setMerchantName($xmlElement);
        $this->setLinkId($xmlElement);
        $this->setCreatedOn($xmlElement);
        $this->setSku($xmlElement);
        $this->setProductName($xmlElement);
        $this->setCategory($xmlElement);
        $this->setRetailPrice($xmlElement);
        $this->setUpcCode($xmlElement);
        $this->setDescription($xmlElement);
        $this->setSalePrice($xmlElement);
        $this->setKeywords($xmlElement);
        $this->setLinkUrl($xmlElement);
        $this->setImageUrl($xmlElement);
    }

    /**
     * Get the merchant ID.
     *
     * @return int
     */
    final public function merchantId()
    {
        return $this->merchantId;
    }

    /**
     * Set the merchant ID.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setMerchantId(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->mid)) {
            return;
        }

        $this->merchantId = (int) $xmlElement->mid;
    }

    /**
     * Get the merchant name.
     *
     * @return string
     */
    final public function merchantName()
    {
        return $this->merchantName;
    }

    /**
     * Set the merchant name.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setMerchantName(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->merchantname)) {
            return;
        }

        $this->merchantName = trim($xmlElement->merchantname);
    }

    /**
     * Get the link ID.
     *
     * @return int
     */
    final public function linkId()
    {
        return $this->linkId;
    }

    /**
     * Set the link ID.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setLinkId(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->linkid)) {
            return;
        }

        $this->linkId = (int) $xmlElement->linkid;
    }

    /**
     * Get the date of creation.
     *
     * @return Carbon
     */
    final public function createdOn()
    {
        return $this->createdOn;
    }

    /**
     * Set the date of creation.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setCreatedOn(SimpleXMLElement $xmlElement)
    {
        if (empty($xmlElement->createdon)) {
            return;
        }

        $this->createdOn = Carbon::createFromFormat(static::DATE_FORMAT, $xmlElement->createdon);
    }

    /**
     * Get the SKU.
     *
     * @return string
     */
    final public function sku()
    {
        return $this->sku;
    }

    /**
     * Set the SKU.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setSku(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->sku)) {
            return;
        }

        $this->sku = trim($xmlElement->sku);
    }

    /**
     * Get the product name.
     *
     * @return string
     */
    final public function productName()
    {
        return $this->productName;
    }

    /**
     * Set the product name.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setProductName(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->productname)) {
            return;
        }

        $this->productName = trim($xmlElement->productname);
    }

    /**
     * Set the primary and secondary categories.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setCategory(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->category)) {
            return;
        }

        $this->setPrimaryCategories($xmlElement);
        $this->setSecondaryCategories($xmlElement);
    }

    /**
     * Get the primary categories.
     *
     * @return \string[]
     */
    final public function primaryCategories()
    {
        return $this->primaryCategories;
    }

    /**
     * Set the primary categories.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setPrimaryCategories(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->category)) {
            return;
        }

        if (! isset($xmlElement->category->primary)) {
            return;
        }

        $primaryCategories = explode('~~', $xmlElement->category->primary);

        foreach ($primaryCategories as &$primaryCategory) {
            $primaryCategory = trim($primaryCategory);
        }
        unset($primaryCategory);

        $this->primaryCategories = $primaryCategories;
    }

    /**
     * Get the secondary categories.
     *
     * @return \string[]
     */
    final public function secondaryCategories()
    {
        return $this->secondaryCategories;
    }

    /**
     * Set the secondary categories.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setSecondaryCategories(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->category)) {
            return;
        }

        if (! isset($xmlElement->category->secondary)) {
            return;
        }

        $secondaryCategories = explode('~~', $xmlElement->category->secondary);

        foreach ($secondaryCategories as &$secondaryCategory) {
            $secondaryCategory = trim($secondaryCategory);
        }
        unset($secondaryCategory);

        $this->secondaryCategories = $secondaryCategories;
    }

    /**
     * Get the retail price.
     *
     * @return float
     */
    final public function retailPrice()
    {
        return $this->retailPrice;
    }

    /**
     * Set the retail price.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setRetailPrice(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->price)) {
            return;
        }

        $this->retailPrice = (float) $xmlElement->price;

        $this->setRetailPriceCurrency($xmlElement);
    }

    /**
     * Get the retail price currency.
     *
     * @return string
     */
    final public function retailPriceCurrency()
    {
        return $this->retailPriceCurrency;
    }

    /**
     * Set the retail price currency.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setRetailPriceCurrency(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->price)) {
            return;
        }

        if (! isset($xmlElement->price['currency'])) {
            return;
        }

        $this->retailPriceCurrency = trim($xmlElement->price['currency']);
    }

    /**
     * Get the UPC code.
     *
     * @return string
     */
    final public function upcCode()
    {
        return $this->upcCode;
    }

    /**
     * Set the UPC code.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setUpcCode(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->upccode)) {
            return;
        }

        $this->upcCode = trim($xmlElement->upccode);
    }

    /**
     * Set the long and short descriptions.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setDescription(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->description)) {
            return;
        }

        $this->setShortDescription($xmlElement);
        $this->setLongDescription($xmlElement);
    }

    /**
     * Get the short description.
     *
     * @return string
     */
    final public function shortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set the short description.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setShortDescription(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->description)) {
            return;
        }

        if (! isset($xmlElement->description->short)) {
            return;
        }

        $this->shortDescription = trim($xmlElement->description->short);
    }

    /**
     * Get the long description.
     *
     * @return string
     */
    final public function longDescription()
    {
        return $this->longDescription;
    }

    /**
     * Set the long description.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setLongDescription(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->description)) {
            return;
        }

        if (! isset($xmlElement->description->long)) {
            return;
        }

        $this->longDescription = trim($xmlElement->description->long);
    }

    /**
     * Get the sale price.
     *
     * @return float
     */
    final public function salePrice()
    {
        return $this->salePrice;
    }

    /**
     * Set the sale price.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setSalePrice(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->saleprice)) {
            return;
        }

        $this->salePrice = (float) $xmlElement->saleprice;

        $this->setSalePriceCurrency($xmlElement);
    }

    /**
     * Get the sale price currency.
     *
     * @return string
     */
    final public function salePriceCurrency()
    {
        return $this->salePriceCurrency;
    }

    /**
     * Set the sale price currency.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setSalePriceCurrency(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->saleprice)) {
            return;
        }

        if (! isset($xmlElement->saleprice['currency'])) {
            return;
        }

        $this->salePriceCurrency = trim($xmlElement->saleprice['currency']);
    }

    /**
     * Get the keywords.
     *
     * @return \string[]
     */
    final public function keywords()
    {
        return $this->keywords;
    }

    /**
     * Set the keywords.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setKeywords(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->keywords)) {
            return;
        }

        $keywords = explode('~~', $xmlElement->keywords);

        foreach ($keywords as &$keyword) {
            $keyword = trim($keyword);
        }
        unset($keyword);

        $this->keywords = $keywords;
    }

    /**
     * Get the link URL.
     *
     * @return string
     */
    final public function linkUrl()
    {
        return $this->linkUrl;
    }

    /**
     * Set the link URL.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setLinkUrl(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->linkurl)) {
            return;
        }

        $this->linkUrl = trim($xmlElement->linkurl);
    }

    /**
     * Get the image URL.
     *
     * @return string
     */
    final public function imageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set the image URL.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setImageUrl(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->imageurl)) {
            return;
        }

        $this->imageUrl = trim($xmlElement->imageurl);
    }
}

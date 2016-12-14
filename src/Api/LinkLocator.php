<?php

namespace Linkshare\Api;

use Carbon\Carbon;
use Exception;
use Linkshare\Api\LinkLocator\Response;
use Linkshare\Helpers\XMLHelper;
use SimpleXMLElement;

class LinkLocator extends AbstractLinkshareApi
{
    const API_NAME    = 'linklocator';
    const API_VERSION = '1.0';

    const SUB_API_GET_MERCHANT_BY_ID         = 'getMerchByID';
    const SUB_API_GET_MERCHANT_BY_NAME       = 'getMerchByName';
    const SUB_API_GET_MERCHANT_BY_CATEGORY   = 'getMerchByCategory';
    const SUB_API_GET_MERCHANT_BY_APP_STATUS = 'getMerchByAppStatus';
    const SUB_API_GET_CREATIVE_CATEGORIES    = 'getCreativeCategories';
    const SUB_API_GET_TEXT_LINKS             = 'getTextLinks';
    const SUB_API_GET_BANNER_LINKS           = 'getBannerLinks';
    const SUB_API_GET_DRM_LINKS              = 'getDRMLinks';
    const SUB_API_GET_PRODUCT_LINKS          = 'getProductLinks';

    const VALID_SUB_APIS = [
        self::SUB_API_GET_MERCHANT_BY_ID,
        self::SUB_API_GET_MERCHANT_BY_NAME,
        self::SUB_API_GET_MERCHANT_BY_CATEGORY,
        self::SUB_API_GET_MERCHANT_BY_APP_STATUS,
        self::SUB_API_GET_CREATIVE_CATEGORIES,
        self::SUB_API_GET_TEXT_LINKS,
        self::SUB_API_GET_BANNER_LINKS,
        self::SUB_API_GET_DRM_LINKS,
        self::SUB_API_GET_PRODUCT_LINKS,
    ];

    /**
     * The sub API.
     *
     * @var string
     */
    private $subApi;

    /**
     * Get the API url.
     *
     * This should return the fully constructed url for the API request.
     *
     * @return mixed
     */
    public function getApiUrl()
    {
        return parent::getApiUrl().'/'.$this->getSubApi().'/'.$this->getUrlQuery();
    }

    /**
     * Get the sub API.
     *
     * @return mixed
     */
    public function getSubApi()
    {
        return $this->subApi;
    }

    /**
     * Set the sub API.
     *
     * @param string $subApi
     */
    protected function setSubApi($subApi)
    {
        $this->subApi = $subApi;
    }

    /**
     * Return the API values in the form required for making an API call.
     *
     * @return string
     */
    protected function getUrlQuery()
    {
        return implode('/', $this->getData());
    }

    /**
     * {@inheritdoc}
     */
    public function get($method = 'GET', array $options = [])
    {
        $result = parent::get($method, $options);

        if (! is_string($result)) {
            throw new Exception('Return data was in an unexpected format.');
        }

        $xmlElement = new SimpleXMLElement(XMLHelper::tidy($result));
        $response   = new Response($xmlElement);

        return $response;
    }

    /**
     * Allows you to download an advertiser’s information by specifying
     * the LinkShare Advertiser ID for that advertiser.
     *
     * @param int $merchantId The LinkShare Advertiser ID
     *
     * @return $this
     */
    public function merchantById($merchantId)
    {
        $this->reset();
        $this->setSubApi(static::SUB_API_GET_MERCHANT_BY_ID);
        $this->setParameter(0, $merchantId);

        return $this;
    }

    /**
     * Allows you to download an advertiser’s information by specifying the name of the advertiser.
     *
     * @param string $name The name of the advertiser. It must be an exact match.
     *
     * @return $this
     */
    public function merchantByName($name)
    {
        $this->reset();
        $this->setSubApi(static::SUB_API_GET_MERCHANT_BY_NAME);
        $this->setParameter(0, $name);

        return $this;
    }

    /**
     * Allows you to download advertiser information by specifying the advertiser category.
     *
     * These are the same categories that you see when looking for advertisers in the
     * Programs section of the Publisher Dashboard.
     *
     * @param int $categoryId The category of the advertiser
     *
     * @return $this
     */
    public function merchantByCategory($categoryId)
    {
        $this->reset();
        $this->setSubApi(static::SUB_API_GET_MERCHANT_BY_CATEGORY);
        $this->setParameter(0, $categoryId);

        return $this;
    }

    /**
     * Allows you to download advertiser information by specifying
     * the Application Status ID for the Application Status that
     * you want to get the List of Merchants for.
     *
     * Application status options:
     *   approved
     *   approval extended
     *   wait
     *   temp removed
     *   temp rejected
     *   perm removed
     *   perm rejected
     *   self removed
     *
     * @param string $status
     *
     * @return $this
     */
    public function merchantByAppStatus($status)
    {
        $this->reset();
        $this->setSubApi(static::SUB_API_GET_MERCHANT_BY_APP_STATUS);
        $this->setParameter(0, $status);

        return $this;
    }

    /**
     * Provides you with the list of categories that advertisers place their creative into.
     *
     * Each advertiser has their own set of categories. You can use this information
     * to filter the creative feeds to obtain links from one of these categories.
     *
     * @param int $merchantId This is the LinkShare Advertiser ID
     *
     * @return $this
     */
    public function creativeCategories($merchantId)
    {
        $this->reset();
        $this->setSubApi(static::SUB_API_GET_CREATIVE_CATEGORIES);
        $this->setParameter(0, $merchantId);

        return $this;
    }

    /**
     * Provides you the available text links.
     *
     * To specify the links your request returns, you can filter it using
     * these parameters: MID, Category, Start Date, and End Date.
     *
     * @param int         $merchantId This is the Rakuten LinkShare Advertiser ID.
     *                                Optional, use -1 as the default value.
     * @param int         $categoryId This is the Creative Category ID.
     *                                It is assigned by the advertiser. Use the Creative Category
     *                                feed to obtain it (not the Advertiser Category Table listed
     *                                in the Publisher Help Center).
     *                                Optional, use -1 as the default value.
     * @param Carbon|null $startDate  This is the start date for the creative, formatted MMDDYYYY.
     *                                Optional, use null as the default value.
     * @param Carbon|null $endDate    This is the end date for the creative, formatted MMDDYYYY.
     *                                Optional, use null as the default value.
     * @param int         $campaignId Rakuten LinkShare retired this feature in August 2011.
     *                                Please enter -1 as the default value.
     * @param int         $page       This is the page number of the results.
     *                                On queries with a large number of results, the system
     *                                returns 10,000 results per page. This parameter helps
     *                                you organize them.
     *                                Optional, use 1 as a default value.
     *
     * @return $this
     */
    public function textLinks(
        $merchantId = -1,
        $categoryId = -1,
        Carbon $startDate = null,
        Carbon $endDate = null,
        $campaignId = -1,
        $page = 1
    ) {
        $this->reset();
        $this->setSubApi(static::SUB_API_GET_TEXT_LINKS);
        $this->setParameter(0, $merchantId);
        $this->setParameter(1, $categoryId);
        $this->setParameter(2, $startDate);
        $this->setParameter(3, $endDate);
        $this->setParameter(4, $campaignId);
        $this->setParameter(5, $page);

        return $this;
    }

    /**
     * Provides you the available banner links.
     *
     * To obtain specific banner links, you can filter this request using
     * these parameters: MID, Category, Size, Start Date, and End Date.
     *
     * @param int         $merchantId This is the Rakuten LinkShare Advertiser ID.
     *                                Optional, use -1 as the default value.
     * @param int         $categoryId This is the Creative Category ID.
     *                                It is assigned by the advertiser. Use the Creative Category
     *                                feed to obtain it (not the Advertiser Category Table listed
     *                                in the Publisher Help Center).
     *                                Optional, use -1 as the default value.
     * @param Carbon|null $startDate  This is the start date for the creative, formatted MMDDYYYY.
     *                                Optional, use null as the default value.
     * @param Carbon|null $endDate    This is the end date for the creative, formatted MMDDYYYY.
     *                                Optional, use null as the default value.
     * @param int         $size       This is the banner size code.
     *                                Optional, use -1 as the default value.
     * @param int         $campaignId Rakuten LinkShare retired this feature in August 2011.
     *                                Please enter -1 as the default value.
     * @param int         $page       This is the page number of the results.
     *                                On queries with a large number of results, the system
     *                                returns 10,000 results per page. This parameter helps
     *                                you organize them.
     *                                Optional, use 1 as a default value.
     *
     * @return $this
     */
    public function bannerLinks(
        $merchantId = -1,
        $categoryId = -1,
        Carbon $startDate = null,
        Carbon $endDate = null,
        $size = -1,
        $campaignId = -1,
        $page = 1
    ) {
        $this->reset();
        $this->setSubApi(static::SUB_API_GET_BANNER_LINKS);
        $this->setParameter(0, $merchantId);
        $this->setParameter(1, $categoryId);
        $this->setParameter(2, $startDate);
        $this->setParameter(3, $endDate);
        $this->setParameter(4, $size);
        $this->setParameter(5, $campaignId);
        $this->setParameter(6, $page);

        return $this;
    }

    /**
     * Provides you the available DRM links.
     *
     * To obtain specific DRM links, you can filter it using these
     * parameters: MID, Category, Start Date, and End Date.
     *
     * @param int         $merchantId This is the Rakuten LinkShare Advertiser ID.
     *                                Optional, use -1 as the default value.
     * @param int         $categoryId This is the Creative Category ID.
     *                                It is assigned by the advertiser. Use the Creative Category
     *                                feed to obtain it (not the Advertiser Category Table listed
     *                                in the Publisher Help Center).
     *                                Optional, use -1 as the default value.
     * @param Carbon|null $startDate  This is the start date for the creative, formatted MMDDYYYY.
     *                                Optional, use null as the default value.
     * @param Carbon|null $endDate    This is the end date for the creative, formatted MMDDYYYY.
     *                                Optional, use null as the default value.
     * @param int         $campaignId Rakuten LinkShare retired this feature in August 2011.
     *                                Please enter -1 as the default value.
     * @param int         $page       This is the page number of the results.
     *                                On queries with a large number of results, the system
     *                                returns 10,000 results per page. This parameter helps
     *                                you organize them.
     *                                Optional, use 1 as a default value.
     *
     * @return $this
     */
    public function drmLinks(
        $merchantId = -1,
        $categoryId = -1,
        Carbon $startDate = null,
        Carbon $endDate = null,
        $campaignId = -1,
        $page = 1
    ) {
        $this->reset();
        $this->setSubApi(static::SUB_API_GET_DRM_LINKS);
        $this->setParameter(0, $merchantId);
        $this->setParameter(1, $categoryId);
        $this->setParameter(2, $startDate);
        $this->setParameter(3, $endDate);
        $this->setParameter(4, $campaignId);
        $this->setParameter(5, $page);

        return $this;
    }

    /**
     * Provides you the various Individual Product links that are available.
     *
     * To obtain specific individual product links, you can filter the
     * request using the following parameters: MID and Category.
     *
     * @param int $merchantId This is the Rakuten LinkShare Advertiser ID.
     *                        Required.
     * @param int $categoryId This is the Creative Category ID.
     *                        It is assigned by the advertiser. Use the Creative Category
     *                        feed to obtain it (not the Advertiser Category Table listed
     *                        in the Publisher Help Center).
     *                        Required..
     * @param int $campaignId Rakuten LinkShare retired this feature in August 2011.
     *                        Please enter -1 as the default value.
     * @param int $page       This is the page number of the results.
     *                        On queries with a large number of results, the system
     *                        returns 10,000 results per page. This parameter helps
     *                        you organize them.
     *                        Optional, use 1 as a default value.
     *
     * @return $this
     */
    public function productLinks(
        $merchantId,
        $categoryId,
        $campaignId = -1,
        $page = 1
    ) {
        $this->reset();
        $this->setSubApi(static::SUB_API_GET_CREATIVE_CATEGORIES);
        $this->setParameter(0, $merchantId);
        $this->setParameter(1, $categoryId);
        $this->setParameter(2, $campaignId);
        $this->setParameter(3, $page);

        return $this;
    }
}

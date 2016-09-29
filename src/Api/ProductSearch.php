<?php

namespace Linkshare\Api;

use Exception;
use Linkshare\Api\ProductSearch\Result;
use SimpleXMLElement;

class ProductSearch extends AbstractLinkshareApi
{
    const API_NAME    = 'productsearch';
    const API_VERSION = '1.0';

    const API_PARAM_KEYWORD         = 'keyword';
    const API_PARAM_EXACT           = 'exact';
    const API_PARAM_ONE             = 'one';
    const API_PARAM_NONE            = 'none';
    const API_PARAM_CATEGORY        = 'category';
    const API_PARAM_MAXIMUM_RESULTS = 'max';
    const API_PARAM_PAGE_NUMBER     = 'pagenumber';
    const API_PARAM_MERCHANT_ID     = 'mid';
    const API_PARAM_SORT            = 'sort';
    const API_PARAM_SORT_TYPE       = 'sorttype';

    const VALID_SEARCH_METHODS = [
        self::API_PARAM_KEYWORD,
        self::API_PARAM_EXACT,
        self::API_PARAM_ONE,
        self::API_PARAM_NONE,
    ];

    const SORT_COLUMN_RETAIL_PRICE      = 'retailprice';
    const SORT_COLUMN_PRODUCT_NAME      = 'productname';
    const SORT_COLUMN_SHORT_DESCRIPTION = 'shortdesp';
    const SORT_COLUMN_CATEGORY          = 'categoryname';
    const SORT_COLUMN_MERCHANT_ID       = 'mid';
    const SORT_COLUMN_KEYWORD           = 'keyword';

    const VALID_SORT_COLUMNS = [
        self::SORT_COLUMN_RETAIL_PRICE,
        self::SORT_COLUMN_PRODUCT_NAME,
        self::SORT_COLUMN_SHORT_DESCRIPTION,
        self::SORT_COLUMN_CATEGORY,
        self::SORT_COLUMN_MERCHANT_ID,
        self::SORT_COLUMN_KEYWORD,
    ];

    const SORT_TYPE_ASC  = 'asc';
    const SORT_TYPE_DESC = 'desc';

    const VALID_SORT_TYPES = [
        self::SORT_TYPE_ASC,
        self::SORT_TYPE_DESC,
    ];

    /**
     * Get the API url.
     *
     * This should return the fully constructed url for the API request.
     *
     * @return mixed
     */
    public function getApiUrl()
    {
        return parent::getApiUrl().'?'.$this->getUrlQuery();
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

        $xmlElement          = new SimpleXMLElement($result);
        $productSearchResult = new Result($xmlElement);

        return $productSearchResult;
    }

    /**
     * @see get()
     * @param string $method
     * @param array $options
     * @return Result
     */
    public function search($method = 'GET', array $options = [])
    {
        return $this->get($method, $options);
    }

    /**
     * Specify the keyword parameter.
     *
     * @param string $keyword
     * @param string $searchMethod
     * @return $this
     * @throws Exception
     */
    public function keyword($keyword, $searchMethod = self::API_PARAM_KEYWORD)
    {
        if (! in_array($searchMethod, static::VALID_SEARCH_METHODS)) {
            throw new Exception('Search method must be one of ('.implode(', ', static::VALID_SEARCH_METHODS).').');
        }

        foreach (self::VALID_SEARCH_METHODS as $validSearchMethod) {
            $value = null;

            if ($searchMethod === $validSearchMethod) {
                $value = $keyword;
            }

            $this->setParameter($validSearchMethod, $value);
        }

        return $this;
    }

    /**
     * Specify the category parameter.
     *
     * @param string $category
     * @return $this
     */
    public function category($category)
    {
        return $this->setParameter(static::API_PARAM_CATEGORY, $category);
    }

    /**
     * Specify the maximum results parameter.
     *
     * @param int $maximumResults
     * @return $this
     */
    public function maximumResults($maximumResults)
    {
        return $this->setParameter(static::API_PARAM_MAXIMUM_RESULTS, $maximumResults);
    }

    /**
     * Specify the page number parameter.
     *
     * @param int $pageNumber
     * @return $this
     */
    public function pageNumber($pageNumber)
    {
        return $this->setParameter(static::API_PARAM_PAGE_NUMBER, $pageNumber);
    }

    /**
     * Specify the merchant ID parameter.
     *
     * @param int $merchantId
     * @return $this
     */
    public function merchantId($merchantId)
    {
        return $this->setParameter(static::API_PARAM_MERCHANT_ID, $merchantId);
    }

    /**
     * Specify the sort and sort type parameter.
     *
     * TODO Implement multiple column sort.
     *
     * @param string $sortColumn
     * @param string $sortType
     * @return $this
     * @throws Exception
     */
    public function sort($sortColumn, $sortType = self::SORT_TYPE_ASC)
    {
        if (! in_array($sortColumn, static::VALID_SORT_COLUMNS)) {
            throw new Exception('Sort column must be one of ('.implode(', ', static::VALID_SORT_COLUMNS).').');
        }

        if (! in_array($sortType, static::VALID_SORT_TYPES)) {
            throw new Exception('Sort type must be one of ('.implode(', ', static::VALID_SORT_TYPES).').');
        }

        $this->setParameter(static::API_PARAM_SORT, $sortColumn);
        $this->setParameter(static::API_PARAM_SORT_TYPE, $sortType);

        return $this;
    }
}

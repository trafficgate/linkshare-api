<?php

namespace Linkshare\Api;

use PHPUnit_Framework_TestCase;

class LinkLocatorTestCase extends PHPUnit_Framework_TestCase
{
    const SIMPLE_OFFER  = 'simple_offer';
    const COMPLEX_OFFER = 'complex_offer';

    public $fault;
    public $offers;
    public $commissionTerms;
    public $returns;
    public $response;

    public function setUp()
    {
        $this->fault = [
           'fault_string' => 'test_fault_string',
           'message'      => 'test_message',
        ];

        $this->offers = [
            static::SIMPLE_OFFER => [
                'also_name'        => 'test_also_name',
                'commission_terms' => 'flat : 0 and above 2.40',
                'offer_id'         => '1',
                'offer_name'       => 'test_offer_name1',
            ],
            static::COMPLEX_OFFER => [
                'also_name'        => 'test_also_name',
                'commission_terms' => 'sale : 0-1000 4% | 1000-2000 5.5% | 2000 and above 6%',
                'offer_id'         => '2',
                'offer_name'       => 'test_offer_name2',
            ],
        ];

        $this->commissionTerms = [
            static::SIMPLE_OFFER => [
                'flat' => [
                    [
                        'lower_bound'   => 0,
                        'upper_bound'   => null,
                        'amount'        => 2.40,
                        'is_percentage' => false,
                    ],
                ],
            ],
            static::COMPLEX_OFFER => [
                'sale' => [
                    [
                        'lower_bound'   => 0,
                        'upper_bound'   => 1000,
                        'amount'        => 4,
                        'is_percentage' => true,
                    ],
                    [
                        'lower_bound'   => 1000,
                        'upper_bound'   => 2000,
                        'amount'        => 5.5,
                        'is_percentage' => true,
                    ],
                    [
                        'lower_bound'   => 2000,
                        'upper_bound'   => null,
                        'amount'        => 6,
                        'is_percentage' => true,
                    ],
                ],
            ],
        ];

        $this->returns = [
            [
                'application_status' => 'test_application_status',
                'categories'         => '1 2 3 4 5',
                'merchant_id'        => '1',
                'merchant_name'      => 'test_merchant_name',
                'offer'              => $this->offers[static::SIMPLE_OFFER],
            ],
        ];

        $this->response = [
            $this->returns,
        ];
    }

    public function getValidResultXml(array $response, $subApi = 'getMerchByID')
    {
        $xml = "<ns1:{$subApi}Response xmlns:ns1=\"http://endpoint.linkservice.linkshare.com/\">";
        $xml .= $this->getMultiReturnXml($response);
        $xml .= "</ns1:${subApi}Response>";

        return $xml;
    }

    public function getMultiReturnXml(array $returns)
    {
        $xml = '';
        foreach ($returns as $return) {
            $xml .= $this->getSingleReturnXml($return);
        }

        return $xml;
    }

    public function getSingleReturnXml(array $return)
    {
        $xml = '<ns1:return>';
        $xml .= "<ns1:applicationStatus>{$return['application_status']}</ns1:applicationStatus>";
        $xml .= "<ns1:categories>{$return['categories']}</ns1:categories>";
        $xml .= "<ns1:mid>{$return['merchant_id']}</ns1:mid>";
        $xml .= "<ns1:name>{$return['merchant_name']}</ns1:name>";
        $xml .= $this->getOfferXml($return['offer']);
        $xml .= '</ns1:return>';

        return $xml;
    }

    public function getOfferXml(array $offer)
    {
        $xml = '<ns1:offer>';
        $xml .= "<ns1:alsoName>{$offer['also_name']}</ns1:alsoName>";
        $xml .= "<ns1:commissionTerms>{$offer['commission_terms']}</ns1:commissionTerms>";
        $xml .= "<ns1:offerId>{$offer['offer_id']}</ns1:offerId>";
        $xml .= "<ns1:offerName>{$offer['offer_name']}</ns1:offerName>";
        $xml .= '</ns1:offer>';

        return $xml;
    }

    public function getInvalidResultXml(array $fault)
    {
        $xml = '<ns1:XMLFault xmlns:ns1="http://cxf.apache.org/bindings/xformat">';
        $xml .= "<ns1:faultstring>{$fault['fault_string']}</ns1:faultstring>";
        $xml .= '<ns1:detail>';
        $xml .= '<LinkLocFault xmlns="http://endpoint.linkservice.linkshare.com/">';
        $xml .= "<message>{$fault['message']}</message>";
        $xml .= '</LinkLocFault>';
        $xml .= '</ns1:detail>';
        $xml .= '</ns1:XMLFault>';

        return $xml;
    }
}

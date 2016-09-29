<?php

namespace Linkshare\Test\Api\LinkLocator;

use Linkshare\Api\LinkLocator\Offer;
use Linkshare\Helpers\XMLHelper;
use Linkshare\Test\Api\LinkLocatorTestCase;
use SimpleXMLElement;

class OfferTest extends LinkLocatorTestCase
{
    public function createOfferXmlDataProvider()
    {
        return [
            [LinkLocatorTestCase::SIMPLE_OFFER],
            [LinkLocatorTestCase::COMPLEX_OFFER],
        ];
    }

    /**
     * @param $offerKey
     * @dataProvider createOfferXmlDataProvider
     */
    public function testCreateOfferWithValidXml($offerKey)
    {
        $xml = '<ns1:response xmlns:ns1="http://endpoint.linkservice.linkshare.com/">';
        $xml .= $this->getOfferXml($this->offers[$offerKey]);
        $xml .= '</ns1:response>';
        $xmlElement = new SimpleXMLElement(XMLHelper::tidy($xml));
        $offer      = new Offer($xmlElement->offer);

        $this->assertEquals($this->offers[$offerKey]['also_name'], $offer->alsoName());
        $this->assertEquals($this->commissionTerms[$offerKey], $offer->commissionTerms());
        $this->assertEquals($this->offers[$offerKey]['offer_id'], $offer->id());
        $this->assertEquals($this->offers[$offerKey]['offer_name'], $offer->name());
    }
}

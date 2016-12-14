<?php

namespace Linkshare\Api\LinkLocator;

use SimpleXMLElement;

class Offer
{
    /**
     * An alternate name for the advertiser, often an abbreviated version.
     *
     * @var string
     */
    private $alsoName;

    /**
     * The commission terms of the advertiser’s offer that you are participating in.
     *
     * The terms of tiered offers are pipe delimited. Here’s an example:
     *
     * <ns1:commissionTerms>sale : 0-10 0% | 10-20 1% | 20-30 2% </ns1:commissionTerms>
     *
     * @var array
     */
    private $commissionTerms;

    /**
     * The ID number of the advertiser’s offer you are participating in.
     *
     * @var int
     */
    private $id;

    /**
     * The name of the advertiser’s offer you are participating in.
     *
     * @var string
     */
    private $name;

    /**
     * Offer constructor.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final public function __construct(SimpleXMLElement $xmlElement)
    {
        $this->setAlsoName($xmlElement);
        $this->setCommissionTerms($xmlElement);
        $this->setId($xmlElement);
        $this->setName($xmlElement);
    }

    /**
     * Get the also name.
     *
     * @return string
     */
    final public function alsoName()
    {
        return $this->alsoName;
    }

    /**
     * Set the also name.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setAlsoName(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->alsoname)) {
            return;
        }

        $this->alsoName = trim($xmlElement->alsoname);
    }

    /**
     * Get the commission terms.
     *
     * @return array
     */
    final public function commissionTerms()
    {
        return $this->commissionTerms;
    }

    /**
     * Set the commission terms.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setCommissionTerms(SimpleXMLElement $xmlElement)
    {
        $this->commissionTerms = [];

        if (empty($xmlElement->commissionterms)) {
            return;
        }

        $commissionTermString = $xmlElement->commissionterms;

        // Determine the offer type
        $matches = [];
        preg_match('/^(?<offer_type>\w+) : /', $commissionTermString, $matches);

        $commissionType = $this->validateString($matches['offer_type']);

        // Determine the offers
        $terms = $this->splitOfferTerms($commissionTermString);
        foreach ($terms as $term) {
            $term = trim($term);

            $matches = $this->matchOfferTerm($term);

            $lowerBound   = null;
            $upperBound   = null;
            $amount       = null;
            $isPercentage = null;

            if (isset($matches['lower_bound'])) {
                $lowerBound = $this->validateFloat($matches['lower_bound']);
            }

            if (isset($matches['upper_bound'])) {
                $upperBound = $this->validateFloat($matches['upper_bound']);
            }

            if (isset($matches['amount'])) {
                $amount = $this->validateFloat($matches['amount']);
            }

            if (isset($matches['is_percentage'])) {
                $isPercentage = $this->validateBool($matches['is_percentage'] === '%');
            }

            $this->commissionTerms[$commissionType][] = [
                'lower_bound'   => $lowerBound,
                'upper_bound'   => $upperBound,
                'amount'        => $amount,
                'is_percentage' => $isPercentage,
            ];
        }
    }

    /**
     * Split an offer string into individual terms.
     *
     * @param string $terms
     *
     * @return array
     */
    final private function splitOfferTerms($terms)
    {
        $termsStartPosition = strpos($terms, ':') + 1;
        $termsString        = trim(substr($terms, $termsStartPosition));

        $splitTerms = explode('|', $termsString);

        if ($splitTerms === false) {
            $splitTerms = [];
        }

        return $splitTerms;
    }

    /**
     * Given an offer term, split it into individual parts for digestion.
     *
     * @param string $term
     *
     * @return array
     */
    final private function matchOfferTerm($term)
    {
        $matches = [];
        preg_match(
            '/^'.
            '(?<lower_bound>(([0-9]+)?\.?)[0-9]+)'.
            '('.
            '(-(?<upper_bound>(([0-9]+)?\.?)[0-9]+))'.
            '|'.
            '( and above)'.
            ')'.
            ' '.
            '(?<amount>(([0-9]+)?\.?)[0-9]+)(?<is_percentage>%)?'.
            '$/',
            $term,
            $matches
        );

        return $matches;
    }

    /**
     * Validate a given value is a string or return null.
     *
     * @param mixed $value
     *
     * @return string|null
     */
    final private function validateString($value)
    {
        $returnValue = (string) $value;

        if (! isset($value) || ! is_string($value)) {
            $returnValue = null;
        }

        return $returnValue;
    }

    /**
     * Validate a given value is a float or return null.
     *
     * @param mixed $value
     *
     * @return float|null
     */
    final private function validateFloat($value)
    {
        $returnValue = (float) $value;

        if (! isset($value) || ! is_numeric($value)) {
            $returnValue = null;
        }

        return $returnValue;
    }

    /**
     * Validate a given value is boolean or return null.
     *
     * @param mixed $value
     *
     * @return bool|null
     */
    final private function validateBool($value)
    {
        $returnValue = $value;

        if (! isset($value) || ! is_bool($value)) {
            $returnValue = null;
        }

        return $returnValue;
    }

    /**
     * Get the offer ID.
     *
     * @return int
     */
    final public function id()
    {
        return $this->id;
    }

    /**
     * Set the offer ID.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setId(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->offerid)) {
            return;
        }

        $this->id = (int) $xmlElement->offerid;
    }

    /**
     * Get the offer name.
     *
     * @return string
     */
    final public function name()
    {
        return $this->name;
    }

    /**
     * Set the offer name.
     *
     * @param SimpleXMLElement|SimpleXMLElement[] $xmlElement
     */
    final private function setName(SimpleXMLElement $xmlElement)
    {
        if (! isset($xmlElement->offername)) {
            return;
        }

        $this->name = trim($xmlElement->offername);
    }
}

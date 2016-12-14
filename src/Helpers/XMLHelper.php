<?php

namespace Linkshare\Helpers;

use DOMDocument;

class XMLHelper
{
    const UTF8 = 'UTF-8';

    /**
     * Clean up XML output.
     *
     * @param $xml
     * @param string $encoding
     *
     * @return string
     *
     * @see http://stackoverflow.com/questions/25312015/php-parse-xml-with-html-elements-inside
     */
    public static function tidy($xml, $encoding = self::UTF8)
    {
        // Add an XML declaration if it does not exist.
        if (! preg_match('/^<?xml/i', $xml)) {
            $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>".$xml;
        }

        $output = '';

        $doc = new DOMDocument();
        if (@$doc->loadHTML($xml)) {
            // Dom Document creates <html><body><myxml></body></html>, so we need to remove it
            foreach ($doc->getElementsByTagName('body')->item(0)->childNodes as $child) {
                $output .= $doc->saveXML($child);
            }
        }

        return $output;
    }
}

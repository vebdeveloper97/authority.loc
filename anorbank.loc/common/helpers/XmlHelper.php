<?php

namespace common\helpers;

use JsonException;

class XmlHelper
{
    /**
     * @param string $xml
     *
     * @return array
     * @throws JsonException
     */
    public static function xml2array(string $xml): array
    {
        $resp_xml = str_replace(['SOAP-ENV:', 'ag:', 'iiacs:', 'soapenv:', 'ns1:', 'ebppif1:'], '', $xml);

        return json_decode(json_encode(simplexml_load_string($resp_xml), JSON_THROW_ON_ERROR, 512), true, 512, JSON_THROW_ON_ERROR);
    }
}

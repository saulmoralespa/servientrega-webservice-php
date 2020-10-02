<?php


namespace Servientrega;


class SoapClientNG extends \SoapClient{

    public function __doRequest($request, $location, $action, $version, $one_way = null)
    {
        $xml = explode("\r\n", parent::__doRequest($request, $location, $action, $version, $one_way));
        var_dump($xml);
        $response = preg_replace( '/^(\x00\x00\xFE\xFF|\xFF\xFE\x00\x00|\xFE\xFF|\xFF\xFE|\xEF\xBB\xBF)/', "", $xml[0] );

        return $response;
    }
}
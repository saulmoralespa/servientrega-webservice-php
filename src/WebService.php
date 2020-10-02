<?php

namespace Servientrega;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Utils;

class WebService
{
    const URL_TRACKING_DISPATCHES = 'http://sismilenio.servientrega.com.co/wsrastreoenvios/wsrastreoenvios.asmx?wsdl';
    const NAMESPACE_GUIDES = 'http://tempuri.org/';

    const API_BASE_QUOTE_URL = 'http://web.servientrega.com:8058/CotizadorCorporativo/api/';

    //http://web.servientrega.com:8058/CotizadorCorporativo/api/autenticacion/login
    //http://web.servientrega.com:8058/CotizadorCorporativo/api/Cotizacion

    private $_login_user;
    private $_pwd;
    private $_billing_code;
    private $_id_cient;
    private $_name_pack;
    private $_url_guides = 'http://web.servientrega.com:8081/GeneracionGuias.asmx?wsdl';

    /**
     * WebService constructor.
     * @param $_login_user
     * @param $_pwd
     * @param $_billing_code
     * @param $_name_pack
     */
    public function __construct($_login_user, $_pwd, $_billing_code, $id_client, $_name_pack)
    {
        $this->_login_user = $_login_user;
        $this->_pwd = $_pwd;
        $this->_billing_code = $_billing_code;
        $this->_id_cient = $id_client;
        $this->_name_pack = $_name_pack;
    }

    public function client()
    {
        return new GuzzleClient([
            'base_uri' => self::API_BASE_QUOTE_URL
        ]);
    }

    public function getToken()
    {
        try{
            $pwd = $this->EncriptarContrasena(['strcontrasena' => $this->_pwd]);

            $response = $this->client()->post('autenticacion/login', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'login'          => $this->_login_user,
                    'password'       => $pwd->EncriptarContrasenaResult,
                    'codFacturacion' => $this->_billing_code
                ]
            ]);
            return self::responseJson($response);
        }catch (RequestException $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    public function liquidation(array $params)
    {
        try{
            $response = $this->client()->post('Cotizacion', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getToken()->token
                ],
                'json' => $params
            ]);
            return self::responseJson($response);
        }catch (RequestException $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function paramsHeader()
    {

        $pwd = $this->EncriptarContrasena(['strcontrasena' => $this->_pwd]);

        return [
            'login' => $this->_login_user,
            'pwd' => $pwd->EncriptarContrasenaResult,
            'Id_CodFacturacion' => $this->_billing_code,
            'Nombre_Cargue' => $this->_name_pack
        ];
    }


    public function setUrlGuides(String $url):void
    {
        $this->_url_guides = $url;
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function CargueMasivoExterno(array $params)
    {

        $body = [
            'envios' => [
                'CargueMasivoExternoDTO' => [
                    'objEnvios' => [
                        'EnviosExterno' => $params
                    ]
                ]
            ]
        ];

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function AnularGuias(array $params)
    {
        return $this->call_soap(__FUNCTION__, $params);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function GenerarGuiaSticker(array $params)
    {
        $body = array_merge($params, [
            'ide_CodFacturacion' => $this->_billing_code
        ]);

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function GenerarGuiaStickerTiendasVirtuales(array $params)
    {
        $body = array_merge($params, [
            'ide_CodFacturacion' => $this->_billing_code
        ]);

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function DesencriptarContrasena(array $params)
    {
        return $this->call_soap(__FUNCTION__, $params);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function EncriptarContrasena(array $params)
    {
        return $this->call_soap(__FUNCTION__, $params);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function ConsultarGuia(array $params)
    {
        return $this->call_soap(__FUNCTION__, $params, true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function EstadoGuia(array $params)
    {
        return $this->call_soap(__FUNCTION__, $params, true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function EstadoGuiaXML(array $params)
    {
        return $this->call_soap(__FUNCTION__, $params, true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function EstadoGuiasIdDocumentoCliente(array $params)
    {
        return $this->call_soap(__FUNCTION__, $params, true);
    }

    /**
     * @return array
     */
    private function optionsSoap()
    {
        return [
            "trace" => true,
            'exceptions' => false,
            "soap_version"  => SOAP_1_2,
            "connection_timeout"=> 60,
            "encoding"=> "utf-8",
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                    'ciphers'=>'AES256-SHA'
                ]
            ]),
            'cache_wsdl' => WSDL_CACHE_NONE
        ];
    }

    /**
     * @param $name_function
     * @param array $params
     * @param bool $tracking
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    private function call_soap($name_function, array $params, $tracking = false)
    {
        try {

            if (!$tracking) {
                $headerData = strpos($name_function, 'Contrasena') !== false ? '' : $this->paramsHeader();
                $client = new \SoapClient($this->_url_guides, $this->optionsSoap());
                $client->__setLocation($this->_url_guides);
                $header = new \SoapHeader(self::NAMESPACE_GUIDES, 'AuthHeader', $headerData);
                $client->__setSoapHeaders($header);
            } else {
                $client = new \SoapClient(self::URL_TRACKING_DISPATCHES, $this->optionsSoap());
            }

            if(strpos($name_function, 'EstadoGuia') !== false ){
                $params = array_merge($params, ['ID_Cliente' => $this->_id_cient]);
                $result = $client->$name_function($params);
                $resultGuide = $name_function . "Result";
                $result = simplexml_load_string($result->$resultGuide->any);
            }else{
                $result = $client->$name_function($params);
                self::checkErros($result);
            }

            return $result;

        } catch (\Exception $exception) {
            throw new  \Exception($exception->getMessage());
        }
    }

    /**
     * @param $result
     * @throws \Exception
     */
    private static function checkErros($result)
    {
        if (isset($result->arrayGuias->string) && is_array($result->arrayGuias->string))
            throw new \Exception(implode(PHP_EOL, $result->arrayGuias->string));
        if (isset($result->arrayGuias->string) && !$result->CargueMasivoExternoResult)
            throw new \Exception($result->arrayGuias->string);
        if (isset($result->AnularGuiasResult) && strpos($result->AnularGuiasResult, 'Debe Autenticarse') !== false)
            throw new \Exception($result->AnularGuiasResult);
    }

    public static function responseJson($response)
    {
        return Utils::jsonDecode(
            $response->getBody()->getContents()
        );
    }
}
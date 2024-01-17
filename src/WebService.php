<?php

namespace Servientrega;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Utils;

class WebService
{
    const URL_TRACKING_DISPATCHES = 'http://sismilenio.servientrega.com.co/wsrastreoenvios/wsrastreoenvios.asmx?wsdl';
    const NAMESPACE_GUIDES = 'http://tempuri.org/';
    protected string $urlGuides = 'http://web.servientrega.com:8081/GeneracionGuias.asmx?wsdl';
    protected string $urlQuote = 'http://web.servientrega.com:8058/CotizadorCorporativo/api/';

    public function __construct(
        private $user,
        private $pwd,
        private $billingCode,
        private $idClient,
        private $namePack = ''
    )
    {

    }

    protected function client(): GuzzleClient
    {
        return new GuzzleClient([
            'base_uri' => $this->urlQuote
        ]);
    }

    private function getTokenFilePath(): string
    {
        return dirname(__FILE__) . '/token.json';
    }

    /**
     * @throws GuzzleException
     * @throws \Exception
     */
    protected function getToken()
    {
        if (file_exists($this->getTokenFilePath())) {
            $data = json_decode(file_get_contents($this->getTokenFilePath()));
            $now = new \DateTime('now', new \DateTimeZone('UTC'));

            if ($data && isset($data->expiration) &&
                $now->format('Y-m-d\TH:i:s.u\Z') < $data->expiration
            ){
                return $data->token;
            }
        }

        $data = $this->auth();
        file_put_contents($this->getTokenFilePath(), $data);
        $response = Utils::jsonDecode($data);
        return $response->token;
    }

    public function setUrlGuides(String $url): static
    {
        $this->urlGuides = $url;
        return $this;
    }

    public function setUrlQuote(String $url): static
    {
        $this->urlQuote = $url;
        return $this;
    }

    public function invalidateToken(): void
    {
        if (file_exists($this->getTokenFilePath())) {
            file_put_contents($this->getTokenFilePath(), '');
        }
    }


    /**
     * @throws GuzzleException
     * @throws \Exception
     */
    private function auth(): string
    {
        try{
            $pwd = $this->EncriptarContrasena(['strcontrasena' => $this->pwd]);

            $response = $this->client()->post('autenticacion/login', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'login'          => $this->user,
                    'password'       => $pwd->EncriptarContrasenaResult,
                    'codFacturacion' => $this->billingCode
                ]
            ]);
            return $response->getBody()->getContents();
        }catch (RequestException $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @param array $params
     * @return object
     * @throws \Exception|GuzzleException
     */
    public function liquidation(array $params):object
    {
        try{
            $response = $this->client()->post('Cotizacion', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getToken()
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
    private function paramsHeader(): array
    {

        $pwd = $this->EncriptarContrasena(['strcontrasena' => $this->pwd]);

        return [
            'login' => $this->user,
            'pwd' => $pwd->EncriptarContrasenaResult,
            'Id_CodFacturacion' => $this->billingCode,
            'Nombre_Cargue' => $this->namePack
        ];
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function CargueMasivoExterno(array $params): object
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
    public function AnularGuias(array $params): object
    {
        return $this->call_soap(__FUNCTION__, $params);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function GenerarGuiaSticker(array $params): object
    {
        $body = array_merge($params, [
            'ide_CodFacturacion' => $this->billingCode
        ]);

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function GenerarGuiaStickerTiendasVirtuales(array $params): object
    {
        $body = array_merge($params, [
            'ide_CodFacturacion' => $this->billingCode
        ]);

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function DesencriptarContrasena(array $params): object
    {
        return $this->call_soap(__FUNCTION__, $params);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function EncriptarContrasena(array $params): object
    {
        return $this->call_soap(__FUNCTION__, $params);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function ConsultarGuia(array $params): object
    {
        return $this->call_soap(__FUNCTION__, $params, true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function EstadoGuia(array $params): object
    {
        return $this->call_soap(__FUNCTION__, $params, true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function EstadoGuiaXML(array $params): object
    {
        return $this->call_soap(__FUNCTION__, $params, true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function EstadoGuiasIdDocumentoCliente(array $params): object
    {
        return $this->call_soap(__FUNCTION__, $params, true);
    }

    /**
     * @return array
     */
    protected function optionsSoap(): array
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
    private function call_soap($name_function, array $params, bool $tracking = false):object
    {
        try {

            if (!$tracking) {
                $headerData = str_contains($name_function, 'Contrasena') ? '' : $this->paramsHeader();
                $client = new \SoapClient($this->urlGuides, $this->optionsSoap());
                $client->__setLocation($this->urlGuides);
                $header = new \SoapHeader(self::NAMESPACE_GUIDES, 'AuthHeader', $headerData);
                $client->__setSoapHeaders($header);
            } else {
                $client = new \SoapClient(self::URL_TRACKING_DISPATCHES, $this->optionsSoap());
            }

            if(str_contains($name_function, 'EstadoGuia')){
                $params = array_merge($params, ['ID_Cliente' => $this->idClient]);
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
    private static function checkErros($result): void
    {
        if (isset($result->arrayGuias->string) && is_array($result->arrayGuias->string))
            throw new \Exception(implode(PHP_EOL, $result->arrayGuias->string));
        if (isset($result->arrayGuias->string) && !$result->CargueMasivoExternoResult)
            throw new \Exception($result->arrayGuias->string);
        if (isset($result->AnularGuiasResult) && str_contains($result->AnularGuiasResult, 'Debe Autenticarse'))
            throw new \Exception($result->AnularGuiasResult);
    }

    public static function responseJson($response):Object
    {
        return Utils::jsonDecode(
            $response->getBody()->getContents()
        );
    }
}
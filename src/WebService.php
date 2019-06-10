<?php


namespace Servientrega;


class WebService
{
    const URL_GUIDES = 'http://web.servientrega.com:8081/GeneracionGuias.asmx?wsdl';
    const URL_TRACKING_DISPATCHES = 'http://sismilenio.servientrega.com.co/wsrastreoenvios/wsrastreoenvios.asmx?wsdl';
    const NAMESPACE_GUIDES = 'http://tempuri.org/';

    private $_login_user;
    private $_pwd;
    private $_billing_code;
    private $_name_pack;

    /**
     * WebService constructor.
     * @param $_login_user
     * @param $_pwd
     * @param $_billing_code
     * @param $_name_pack
     */
    public function __construct($_login_user, $_pwd, $_billing_code, $_name_pack)
    {
        $this->_login_user = $_login_user;
        $this->_pwd = $_pwd;
        $this->_billing_code = $_billing_code;
        $this->_name_pack = $_name_pack;
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
     * @param $name_function
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    private function call_soap($name_function, array $params, $tracking = false)
    {

        try{

            if (!$tracking){
                $headerData = strpos($name_function, 'Contrasena') !== false ? '' : $this->paramsHeader();
                $client = new \SoapClient(self::URL_GUIDES);
                $header = new \SoapHeader(self::NAMESPACE_GUIDES, 'AuthHeader', $headerData);
                $client->__setSoapHeaders($header);
            }else{
                $client = new \SoapClient(self::URL_TRACKING_DISPATCHES);
            }

            $result = $client->$name_function($params);
            $this->checkAuthentication($result);

            return $result;

        }catch (\Exception $exception){
            throw new  \Exception($exception->getMessage());
        }
    }

    /**
     * @param $result
     * @throws \Exception
     */
    private function checkAuthentication($result)
    {
        if (isset($result->arrayGuias->string) && strpos($result->arrayGuias->string, 'Acceso Incorrecto') !== false)
            throw new \Exception($result->arrayGuias->string);
        if (isset($result->AnularGuiasResult) && strpos($result->AnularGuiasResult, 'Debe Autenticarse') !== false)
            throw new \Exception($result->AnularGuiasResult);
    }
}
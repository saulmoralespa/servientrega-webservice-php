<?php

use PHPUnit\Framework\TestCase;
use Servientrega\WebService;

class ServientregaTest extends TestCase
{

    public $webservice;

    protected function setUp()
    {
        $dotenv = Dotenv\Dotenv::createMutable(__DIR__ . '/../');
        $dotenv->load();

        $login_user = $_ENV['USER'];
        $pwd = $_ENV['PASSWORD'];
        $billing_code = $_ENV['BILLING_CODE'];
        $id_client = $_ENV['ID_CLIENT'];
        $name_pack = 'Cargue SMP';

        $this->webservice = new WebService($login_user, $pwd, $billing_code, $id_client, $name_pack);
    }


    public function testGenerateGuia()
    {
        $params = array(
            'Num_Guia' => 0,
            'Num_Sobreporte' => 0,
            'Num_Piezas' => 1,
            'Des_TipoTrayecto' => 1,
            'Ide_Producto' => 2,
            'Ide_Destinatarios' => '00000000-0000-0000-0000-000000000000',
            'Ide_Manifiesto' => '00000000-0000-0000-0000-000000000000',
            'Des_FormaPago' => 2,
            'Des_MedioTransporte' => 1,
            'Num_PesoTotal' => 3,
            'Num_ValorDeclaradoTotal' => 50000,
            'Num_VolumenTotal' => 0,
            'Num_BolsaSeguridad' => 0,
            'Num_Precinto' => 0,
            'Des_TipoDuracionTrayecto' => 1,
            'Des_Telefono' => 7700380,
            'Des_Ciudad' => 'Rionegro (ANT)',
            'Des_Direccion' => 'CALLE 5 # 66-44',
            'Nom_Contacto' => 'Deiron Mancipe',
            'Des_VlrCampoPersonalizado1' => '',
            'Num_ValorLiquidado' => 0,
            'Des_DiceContener' => substr('FÓRMULA VM PRIME/ 100 tabl,LYNDORA,NITRO TECH RIPPED 4 LBS VAINILLA,ENTEROPHYLUS', 0, 50),
            'Des_TipoGuia' => 1,
            'Num_VlrSobreflete' => 0,
            'Num_VlrFlete' => 0,
            'Num_Descuento' => 0,
            'idePaisOrigen' => 1,
            'idePaisDestino' => 1,
            'Des_IdArchivoOrigen' => 1,
            'Des_DireccionRemitente' => '',
            'Num_PesoFacturado' => 0,
            'Est_CanalMayorista' => false,
            'Num_IdentiRemitente' => '',
            'Num_TelefonoRemitente' => '',
            'Num_Alto' => 1,
            'Num_Ancho' => 1,
            'Num_Largo' => 1,
            'Des_DepartamentoDestino' => 'Antioquia',
            'Des_DepartamentoOrigen' => '',
            'Gen_Cajaporte' => 0,
            'Gen_Sobreporte' => 0,
            'Nom_UnidadEmpaque' => 'GENERICA',
            'Des_UnidadLongitud' => 'cm',
            'Des_UnidadPeso' => 'kg',
            'Num_ValorDeclaradoSobreTotal' => 0,
            'Num_Factura' => 'FACT-001',
            'Des_CorreoElectronico' => 'example@gmail.com',
            'Num_Recaudo' => 0,
            'Est_EnviarCorreo' => false,
            'Tipo_Doc_Destinatario' => 'CC',
            'Ide_Num_Identific_Dest' => '1094163892'
        );

        $data = $this->webservice->CargueMasivoExterno($params);
        $this->assertObjectHasAttribute('CargueMasivoExternoResult', $data, true);
    }

    public function testCancelGuides()
    {
        $params = [
            'num_Guia' => '2052660119',
            'num_GuiaFinal' => '2052660119'
        ];

        $data = $this->webservice->AnularGuias($params);
        $this->assertSame('Operacion ejecutada exitosamente', $data->interno->ResultadoAnulacionGuias->Descripcion);
    }

    public function testGenerateSticker()
    {
        $params = [
            'num_Guia' => '292710983',
            'num_GuiaFinal' => '292710983',
            'sFormatoImpresionGuia' => 2,
            'Id_ArchivoCargar' => '0',
            'interno' => false

        ];

        $data = $this->webservice->GenerarGuiaSticker($params);
        $this->assertObjectHasAttribute('GenerarGuiaStickerResult', $data, true);
    }

    public function testGenerateStickerShopVirtuals()
    {
        $params = [
            'num_Guia' => '292710984',
            'num_GuiaFinal' => '292710984',
            'sFormatoImpresionGuia' => 2,
            'Id_ArchivoCargar' => '0',
            'consumoClienteExterno' => false,
            'correoElectronico' => 'cortuclas@gmail.com'
        ];

        $data = $this->webservice->GenerarGuiaStickerTiendasVirtuales($params);
        $this->assertObjectHasAttribute('GenerarGuiaStickerTiendasVirtualesResult', $data, true);
    }

    public function testEncryptPassword()
    {
        $params = [
            'strcontrasena' => 'BienVestido2017'
        ];
        $data = $this->webservice->EncriptarContrasena($params);
        $this->assertTrue(is_string($data->EncriptarContrasenaResult));
    }

    public function testDecryptPassword()
    {

        $params = [
          'strcontrasenaEncriptada' => 'BpSUh12jBIiWdACDozgOaQ=='
        ];
        $data = $this->webservice->DesencriptarContrasena($params);
        $this->assertTrue(is_string($data->DesencriptarContrasenaResult));
    }

    public function testGetGuide()
    {
        $params = [
            'NumeroGuia' => '292710915'
        ];

        $data = $this->webservice->ConsultarGuia($params);
        $this->assertObjectHasAttribute('ConsultarGuiaResult', $data, true);
    }

    public function testGetStatusGuide()
    {
        $params = [
           'guia' => '292710915'
        ];

        $data = $this->webservice->EstadoGuia($params);
    }

    public function testGetStatusGuideXML()
    {
        $params = [
            'guia' => '292710915'
        ];

        $data = $this->webservice->EstadoGuiaXML($params);
    }

    public function testGetStatusGuidesDocument()
    {
        $params = [
            'RelacionDocumentos' => '900917801'
        ];

        $data = $this->webservice->EstadoGuiasIdDocumentoCliente($params);
    }

    public function testGetToken()
    {
        $data = $this->webservice->getToken();
        $this->assertNotEmpty($data);
    }

    public function testLiquidation()
    {
        $params = [
            'IdProducto'          => 6,
            'NumeroPiezas'        => 1,
            'Piezas'              =>
                [
                    [
                        'Peso'  => 4,
                        'Largo' => 10,
                        'Ancho' => 5,
                        'Alto'  => 3,
                    ]
                ],
            'ValorDeclarado'      => 10000,
            'IdDaneCiudadOrigen'  => '76892000',
            'IdDaneCiudadDestino' => '76001000',
            'EnvioConCobro'       => true,
            'FormaPago'           => 2,
            'TiempoEntrega'       => 1,
            'MedioTransporte'     => 1,
            'NumRecaudo'          => 1
        ];

        $data = $this->webservice->liquidation($params);
        $this->assertNotEmpty($data);
    }
}
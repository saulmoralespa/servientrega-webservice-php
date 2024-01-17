<?php

use PHPUnit\Framework\TestCase;
use Servientrega\WebService;

class ServientregaTest extends TestCase
{

    public WebService $webservice;

    protected function setUp(): void
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
            'Nom_Contacto' => 'Prueba',
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
        $this->assertTrue(property_exists($data, 'CargueMasivoExternoResult'));
    }

    public function testCancelGuides()
    {
        $params = [
            'num_Guia' => '2191988034',
            'num_GuiaFinal' => '2191988034'
        ];

        $data = $this->webservice->AnularGuias($params);
        $this->assertTrue(property_exists($data, 'AnularGuiasResult'));
        $this->assertSame('Operacion ejecutada exitosamente', $data->interno->ResultadoAnulacionGuias->Descripcion);
    }

    public function testGenerateSticker()
    {
        $params = [
            'num_Guia' => '2191988034',
            'num_GuiaFinal' => '2191988034',
            'sFormatoImpresionGuia' => 2,
            'Id_ArchivoCargar' => '0',
            'interno' => false

        ];

        $data = $this->webservice->GenerarGuiaSticker($params);
        $this->assertTrue(property_exists($data, 'GenerarGuiaStickerResult'));
    }

    public function testGenerateStickerShopVirtuals()
    {
        $params = [
            'num_Guia' => '2191988031',
            'num_GuiaFinal' => '2191988031',
            'sFormatoImpresionGuia' => 2,
            'Id_ArchivoCargar' => '0',
            'consumoClienteExterno' => false,
            'correoElectronico' => 'cortuclas@gmail.com'
        ];

        $data = $this->webservice->GenerarGuiaStickerTiendasVirtuales($params);
        $this->assertTrue(property_exists($data, 'GenerarGuiaStickerTiendasVirtualesResult'));
    }

    public function testEncryptPassword()
    {
        $params = [
            'strcontrasena' => 'yourpassword'
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
            'NumeroGuia' => '2191988031'
        ];

        $data = $this->webservice->ConsultarGuia($params);
        $this->assertTrue(property_exists($data, 'ConsultarGuiaResult'));
    }

    public function testGetStatusGuide()
    {
        $params = [
           'guia' => '2191988031'
        ];

        $data = $this->webservice->EstadoGuia($params);
        $this->assertTrue(property_exists($data, 'NewDataSet'));
    }

    public function testGetStatusGuideXML()
    {
        $params = [
            'guia' => '2191988031'
        ];

        $data = $this->webservice->EstadoGuiaXML($params);
        $this->assertTrue(property_exists($data, 'EstadosGuias'));
    }

    public function testGetStatusGuidesDocument()
    {
        $params = [
            'RelacionDocumentos' => '2191988031'
        ];

        $data = $this->webservice->EstadoGuiasIdDocumentoCliente($params);
    }

    public function testLiquidation()
    {
        $params = [
            'IdProducto'          => 2,
            'NumeroPiezas'        => 1,
            'Piezas'              =>
                [
                    [
                        'Peso'  => 19,
                        'Largo' => 120,
                        'Ancho' => 70,
                        'Alto'  => 5,
                    ]
                ],
            'ValorDeclarado'      => 177400,
            'IdDaneCiudadOrigen'  => '05001000',
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

    public function testInvalidateToken()
    {
        $this->webservice->invalidateToken();
        $data = file_get_contents( dirname(__DIR__) . '/src/token.json');
        $this->assertEmpty($data);
    }
}
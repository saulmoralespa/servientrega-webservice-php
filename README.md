Servientrega Webservice PHP
============================================================

## Installation

You will need at least PHP 8.1. We match [officially supported](https://www.php.net/supported-versions.php) versions of PHP.

Use [composer](https://getcomposer.org/) package manager to install the lastest version of the package:

```bash
composer require saulmoralespa/servientrega-webservice-php
```

```php
// ... please, add composer autoloader first
include_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// import webservice class
use Servientrega\WebService;

$user = 'testajagroup';
$pwd = 'Colombia1';
$billingCode = 'SER408';
$idClient = '900917801';

//optional
$namePack = 'My shop';


$servientrega = new WebService($user, $pwd, $billingCode, $idClient, $namePack);

//Call this method each time you save credentials
//Token expire every 4 days
$servientrega->invalidateToken();;
```

### Shipping quoting

```php
$params = [
            'IdProducto'          => 2, //1 documento unitario  or 6 mercancía industrial 
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
  
try{
$data = $this->webservice->liquidation($params);
var_dump($data);
}catch (\Exception $exception){
 echo $exception->getMessage();
}
```

### CargueMasivoExterno

```php

$params = [
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
            'Des_Ciudad' => 'BOGOTA',
            'Des_Direccion' => 'CALLE 5 # 66-44',
            'Nom_Contacto' => 'TRIQUINET',
            'Des_VlrCampoPersonalizado1' => '',
            'Num_ValorLiquidado' => 0,
            'Des_DiceContener' => 'PAQUETE ESTANDAR',
            'Des_TipoGuia' => 0,
            'Num_VlrSobreflete' => 0,
            'Num_VlrFlete' => 0,
            'Num_Descuento' => 0,
            'idePaisOrigen' => 1,
            'idePaisDestino' => 1,
            'Des_IdArchivoOrigen' => 1,
            'Des_DireccionRemitente' => 'TERMINAL MARITIMO S.P.R.B. ED. ALCAZAR BG N. 2 Y 3',
            'Num_PesoFacturado' => 0,
            'Est_CanalMayorista' => false,
            'Num_IdentiRemitente' => '',
            'Num_TelefonoRemitente' => '',
            'Num_Alto' => 1,
            'Num_Ancho' => 1,
            'Num_Largo' => 1,
            'Des_DepartamentoDestino' => 'CUNDINAMARCA',
            'Des_DepartamentoOrigen' => '',
            'Gen_Cajaporte' => 0,
            'Gen_Sobreporte' => 0,
            'Nom_UnidadEmpaque' => 'GENERICA',
            'Des_UnidadLongitud' => 'cm',
            'Des_UnidadPeso' => 'kg',
            'Num_ValorDeclaradoSobreTotal' => 0,
            'Num_Factura' => 'FACT-001',
            'Des_CorreoElectronico' => 'youremail@gmail.com',
            'Num_Recaudo' => 0,
            'Est_EnviarCorreo' => false
        ];

try{
$data = $servientrega->CargueMasivoExterno($params);
var_dump($data);
}catch (\Exception $exception){
 echo $exception->getMessage();
}
```

### AnularGuias

```php
$params = [
            'num_Guia' => '292710910',
            'num_GuiaFinal' => '292710910'
        ];

try{
$data = $servientrea->AnularGuias($params);
var_dump($data);
}catch (\Exception $exception){
 echo $exception->getMessage();
}
```

### EncriptarContrasena

```php
$params = [
            'strcontrasena' => 'Colombia1'
        ];

try{
$data = $servientrea->EncriptarContrasena($params);
var_dump($data);
}catch (\Exception $exception){
 echo $exception->getMessage();
}
```

### DesencriptarContrasena

```php
$params = [
            'strcontrasenaEncriptada' => 'BpSUh12jBIiWdACDozgOaQ=='
        ];

try{
$data = $servientrea->DesencriptarContrasena($params);
var_dump($data);
}catch (\Exception $exception){
 echo $exception->getMessage();
}
```

### ConsultarGuia

```php
$params = [
            'NumeroGuia' => '292710915'
        ];

try{
$data = $servientrea->ConsultarGuia($params);
var_dump($data);
}catch (\Exception $exception){
 echo $exception->getMessage();
}
```

### EstadoGuia

```php
$params = [
           'guia' => '30007691'
        ];

try{
$data = $servientrea->EstadoGuia(($params);
var_dump($data);
}catch (\Exception $exception){
 echo $exception->getMessage();
}
```
### EstadoGuiaXML

```php
$params = [
           'guia' => '30007691'
        ];

try{
$data = $servientrea->EstadoGuiaXML(($params);
var_dump($data);
}catch (\Exception $exception){
 echo $exception->getMessage();
}
```
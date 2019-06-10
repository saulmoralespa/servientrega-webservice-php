Servientrega Webservice PHP
============================================================

## Installation

Use composer package manager


```bash
composer require saulmoralespa/servientrega-webservice-php
```

```php
// ... please, add composer autoloader first
include_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// import webservice class
use Servientrega\WebService;

$login_user = 'testajagroup';
$pwd = 'Colombia1';
$billing_code = 'SER408';
$name_pack = 'Cargue SMP';


$servientrega = new WebService($login_user, $pwd, $billing_code, $name_pack);

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
           'ID_Cliente' => 'SER408', or empty
           'guia' => '30007691'
        ];

try{
$data = $servientrea->EstadoGuia(($params);
var_dump($data);
}catch (\Exception $exception){
 echo $exception->getMessage();
}
```
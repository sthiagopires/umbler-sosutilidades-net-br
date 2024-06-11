<?php 
session_start();
include_once('config2.php');
include_once('config.php');
require 'classes/usuarios.class.php';


if(!isset($_SESSION['logado'])) {
	header("Location: login.php");
	exit;
}

$usuarios = new Usuarios($pdo);
$usuarios->setUsuario($_SESSION['logado']);



include 'contato.class.php';

$contato = new Contato();
$id= $_GET['id'];
if(!empty($_GET['id'])){
    
$info = $contato->getInfo($id);
if(empty($info['codigo_barras'])) {
    header("Location: index.php");
    exit; 
}
  
}


//require_once ('contador.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Courier New', Courier, monospace;
    
}

body{
    width: 100%;
    height: 100vh;
    
    
    
    
}

    

.empresa {
    padding-top:  10px;
    padding-left: 23px;
    text-align: left;
    margin-bottom: 2px;
}
.empresa2 {
    width: 200px;
    line-height: 1.5;
    margin-top: 0px;
    padding-left: 23px;
    text-align: left;
    margin-bottom: 3px;
}
.empresa02 {
    margin-left: 8px;
    width: 190px;
    border-bottom: 1px dashed black;
    margin-top: 2px;
    
}

.empresa03 {
    font-size: 8pt;
    margin-top: 2px;
    margin-bottom: 2px;
}
.empresa04, .divisor_pagamento, .divisor_pagamento2, .divisor_pagamento3 {
    margin-left: 8px;
    border-bottom: 1px dashed black;
    margin-top: 2px;
    width: 190px;
    margin-bottom: 3px;
}

.beneficiario, .pagador, .pagador2, .autenticacao2, .data_vencimento, .valor_documento, .valor_juros, .valor_cobrado{
    margin-bottom: 2px;
}
.beneficiario2, .pagador2,.cpf, .data_vencimento, .valor_documento, .valor_juros, .valor_cobrado, .mensagem {
    margin-top: 2px;
}
.divisor_pagamento2 {
    margin-left: 8px;
    margin-bottom: 1px;
    margin-top: 4px;
}
.divisor_pagamento3 {
    margin-left: 8px;
    margin-bottom: 10px;
}
.divisor_pagamento3 {
    margin-left: 8px;
    margin-bottom: 4px;
}
.mensagem, .mensagem2 {
    margin-top: 3px;
    margin-bottom: 4px;
    
}

.beneficiario2{
    font-size: 7.5pt;
}

.fechar2 {
    
    font-weight: bold; 
    top: 81%;
    right: 2%;
    background-color: #9400d3;
    color: #fff;
    width: 206px;
    height: 40px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
}

.fechar3 {
    
    font-weight: bold; 
    
    top: 90%;
    right: 2%;
    background-color: white;
    width: 206px;
    height: 40px;
    border-radius: 6px;
    border: #cccc solid 2px;
    cursor: pointer;
    
   
}

.modal{
    top:0;
    left: 0;
   margin: 0;
   padding: 0;
   word-break: break-all;
   font-weight: bold; /*LETRA*/
   font-size: 6.5pt;
   text-align: center;
   
   
   
   width: 57mm;
   
   
    }

.codigo_barras {
    padding-bottom: 2px;
}
.data_vencimento {
    padding-top: 2px;
}

@media only print{
    .fechar2, .fechar3{
        display: none;
    }
    body{
        margin: 0;
        padding: 0;

    }

    
}



    </style>
    
    <title>Comprovante Modal Final</title>
</head>
<body>
<?php if(strlen($info['codigo_barras']) == 47){
    $codigo = $info['codigo_barras'];
    $part_01= substr($codigo, 0, 24);
    $part_02= substr($codigo, 24);

    $codigo_saneago = "$part_01<br>$part_02";
    
}else{
    $codigo = $info['codigo_barras'];
        $part_01= substr($codigo, 0, 13);
        $part_02= substr($codigo, 14, 13);
        $part_03= substr($codigo, 27, 13);
        $part_04= substr($codigo, 27, 13);
    
    $codigo_saneago = "$part_01 $part_02<br>$part_03 $part_04";
}

//echo strlen($info['codigo_barras']);
?>

       
        <div class="modal">

        <p class="um"><span><?php echo date('d/m/Y', strtotime($info['data_pagamento'])) ?></span>&emsp;-&ensp;BANCO DO BRASIL&ensp;-&emsp;<span><?php echo date('H:i:s', strtotime($info['data_pagamento'])) ?></p>
        <p class="dois"><span>031300313</span>&nbsp;&emsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<span><?php echo $info['contagem']; ?></span></p>
                                <p class="pagamento">PAGAMENTO DE TITULOS</p>
        <p class="empresa">EMPRESA: SOS UTILIDADES LTDA</p>  
        <p class="empresa2">AGENCIA: CELCOIN S.A</p> 
        <p class="empresa02"></p> 
        <p class="empresa03"><?php echo $info['banco'];?></p>           
        <p class="empresa04"></p> 
        <p class="beneficiario">CODIGO DE BARRAS:</p>
        <p class="codigo_barras"><?php echo $codigo_saneago ?></p>       
        <p class="beneficiario">BENEFICIARIO:</p>
        <p class="beneficiario2"><?php echo $info['beneficiario']; ?></p>   
        <p class="pagador">PAGADOR:</p>
        <p class="pagador2"><?php echo $info['pagador']; ?></p></p>
        <p class="pagador">CPF:</p>          
        <p class="cpf"><?php echo $info['cpf']; ?></p>                          
        <p class="divisor_pagamento"alue="<?php $hoje = date('d/m/Y'); echo $hoje; ?>"></p> 
        <p class="data_vencimento">DATA DE VENCIMENTO: <?php echo $info['data_vencimento']; ?></p>
        <p class="data_vencimento2">DATA DO PAGAMENTO:<?php echo date('d/m/Y', strtotime($info['data_pagamento'])) ?></p>      
        <p class="valor_documento">VALOR DO DOCUMENTO: R$ <?php echo $info['valor_pagamento']; ?></p>   
        <p class="valor_juros">VALOR DO JUROS: R$ <?php echo $info['valor_juros']; ?></p>             
        <p class="valor_cobrado">&nbsp;VALOR COBRADO: R$ <?php echo $info['valor_cobrado']; ?></p>    
        <p class="divisor_pagamento2"></p> 
        <p class="divisor_pagamento3"></p> 
        <p class="autenticacao">AUTENTICAÇÃO:</p>
        <p  class="autenticacao2"><?php echo $info['autenticacao']; ?></p> 
        <p class="divisor_pagamento2"></p>   
        <p class="divisor_pagamento3"></p> 
        <p class="mensagem"><b>VALIDO COMO RECIBO DE PAGAMENTO</b></p> 
        <p class="mensagem2"><b>Central de Atendimento SOS</b><br> Informações, Reclamações.
            <br> Entre em contato no <br><b>WatsApp (64)9 9615-4762</b></p> 
                <button class="fechar2" id="fechar2" type="" name="imprimir" onclick="window.print()">Imprimir</button>
                <div><button navigator.share(comprovante.php) class="fechar3" id="fechar3">Enviar</button></div>
            
        </div>

       
    </body>
</html>
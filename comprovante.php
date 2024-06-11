<?php 
 session_start();
 ob_start();
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
if(!empty($_GET['id'])){
    $id= $_GET['id'];
$info = $contato->getInfo($id);
if(empty($info['codigo_barras'])) {
    $hoje = date('d/m/Y');
    header("Location: index.php?search=$hoje");
    exit; 
}
  
}else{
    $hoje = date('d/m/Y');
    header("Location: index.php?search=$hoje");
   exit;
}

//require_once ('contador.php');
?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    
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
        <p class="empresa03"><?php echo $info['banco_principal'];?></p>           
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
        <p class="data_vencimento">DATA DE VENCIMENTO: <?php echo date('d/m/Y',strtotime($info['data_vencimento'])); ?></p>
        <p class="data_vencimento2">DATA DO PAGAMENTO:<?php echo date('d/m/Y',strtotime($info['data_pagamento'])) ?></p>      
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
        <p  style="font-size: 11px;"class="mensagem"><b>.Usuario:<br><span  style="text-transform: uppercase;"><?php echo $info['usuario']; ?></span> </b></p> 
        <?php $hoje = date('d/m/Y'); ?>
            <button class="fechar2" id="fechar2" type="" name="imprimir"><a style="color: black; font-weight: bold; text-decoration: none;" href="index.php?search=<?php echo $hoje ?>">Voltar Inicio</a></button>
                <div><button  class="fechar3" id="fechar3"><a style="text-decoration: none;" href="compartilhar.php?id=<?php echo $info['id']?>">Imprimir</a></button></div>
        </div>

       
    </body>
</html>
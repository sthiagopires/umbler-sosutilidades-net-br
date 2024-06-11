
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


//var_dump($usuarios);
?>




<?php


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
<style>
    

    .btnn{
      background-color: #dddd;
      display: flex;
      flex-direction: column;
      height: 100%;
      justify-content: center;
      align-items: center; 
}

button {
  background-color: black;
  color: white;
  font-size: 18px;
  padding: 5px;
  width: 300px;
  height: 50px;
  border: none;
  border-radius: 10px;
  margin-bottom: 10px;
  
}

.voltar{
  font-size:  20px;
}


</style>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
   
    <title>Comprovante Modal Final</title>

</head>
<body>
<?php if(strlen($info['codigo_barras']) == 47){
    $codigo = $info['codigo_barras'];
    $part_01= substr($codigo, 0, 24);
    $part_02= substr($codigo, 24);

    $codigo_saneago = "$part_01";
    $codigo_saneago2 = "$part_02";
    
}else{
    $codigo = $info['codigo_barras'];
        $part_01= substr($codigo, 0, 13);
        $part_02= substr($codigo, 14, 13);
        $part_03= substr($codigo, 27, 13);
        $part_04= substr($codigo, 27, 13);
    
    $codigo_saneago = "$part_01 $part_02";
    $codigo_saneago2 = "$part_03 $part_04";
    
}



//echo strlen($info['codigo_barras']);


$titulo = date('d/m/Y',strtotime($info['data_pagamento']));
$titulo2 = date('H:i', strtotime($info['data_pagamento']));
$titulo3 = $info['contagem'];
$pag = "      ";
$pontos = "------------------------------";
$bancoo = $info['banco_principal'];
$beneficiario = $info['beneficiario'];
$pagador = $info['pagador'];
$cpf = $info['cpf'];
$vencimento = $info['data_vencimento'];
$datapagamento = date('d/m/Y', strtotime($info['data_pagamento']));
$valordocumento = number_format($info['valor_pagamento'], 2, ',', '.');
$valorjuros = number_format($info['valor_juros'], 2, ',', '.');
$valorcobrado = number_format($info['valor_cobrado'], 2, ',', '.');
$autenticacao = $info['autenticacao'];


?>

<div class="btnn">

<div><h1 style="color: red; font-size: 20px;">Imprimir Comprovante de Pagamento</h1></div><br><br>
    <div><button>Imprimir</button></div>
    

    <div style="font-size: 20px; font-weight: bold;" class="result"></div><br>
    <?php $hoje = date('d/m/Y'); ?>
    <div class="voltar" ><a href="index.php?search=<?php echo $hoje ?>">Voltar Inicio</a></div>
  </div>

    <script>
      let shareData = {
        
        text:'<?php echo $titulo?> Banco Do Brasil <?php echo $titulo2?>\n031300313<?php echo "                      "  .$titulo3?>\n<?php echo $pag."PAGAMENTO DE TITULOS"?>\n EMPRESA: SOS UTILIDADES LTDA\n AGENCIA: CELCOIN S.A\n <?php echo $pontos ?>\n <?php echo "          "  .$bancoo?>\n <?php echo $pontos ?>\n <?php echo $pag." CODIGO DE BARRAS:"?>\n<?php echo "  "  .$codigo_saneago ?> \n <?php echo"  " .$codigo_saneago2 ?>\n<?php echo $pag."    BENEFICIARIO:"?>\n <?php echo "    "  .$beneficiario?>\n<?php  echo $pag."      PAGADOR:"?>\n <?php echo "    "  .$pagador?>\n<?php echo $pag."        CPF:"?>\n <?php echo"       " .$cpf?><?php echo $pag ?>\n <?php echo $pontos ?>\nDATA DE VENCIMENTO: <?php echo $vencimento ?>\nDATA DO PAGAMENTO: <?php echo $datapagamento ?>\nVALOR DO DOCUMENTO: R$ <?php echo $valordocumento ?>\nVALOR DO JUROS: R$ <?php echo $valorjuros ?>\nVALOR COBRADO: R$ <?php echo $valorcobrado ?>\n <?php echo $pontos ?>\n <?php echo $pag  ?>   AUTENTICACAO: <?php echo $pag  ?>\n<?php $pag  ?> <?php echo $autenticacao ?> <?php $pag  ?>\n <?php echo $pontos ?>\nVALIDO COMO RECIBO DE PAGAMENTO\n   Central de Atendimento SOS\n     Informações Reclamacoes\n     Entre em contato no\n    WatsApp (64)9 9615-4762\n        Volte Sempre!!',
        
      }

      const btn = document.querySelector('button');
      const resultPara = document.querySelector('.result');

      btn.addEventListener('click', () => {
        navigator.share(shareData)
          .then(() =>
            resultPara.textContent = 'Parabéns successfully'
          )
          .catch((e) =>
            resultPara.textContent = 'Error: ' + e
          )
      });
    </script>
  </body>
</html>
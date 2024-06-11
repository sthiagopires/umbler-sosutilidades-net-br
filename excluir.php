<?php
session_start();
include_once('config2.php');
include_once('config.php');
require 'classes/usuarios.class.php';


if(!isset($_SESSION['logado'])) {
    $hoje = date('d/m/Y');
    header("Location: index.php?search=$hoje");
	exit;
}

$usuarios = new Usuarios($pdo);
$usuarios->setUsuario($_SESSION['logado']);

include 'contato.class.php';

$contato = new Contato();

   
   ?>
   <?php
   
   if(isset($_POST['excluir'])) {

    $hoje = date('d/m/Y');
    header("Location: index.php?search=$hoje");
   
    if($contato) { ?>
        
        <?php
		        $hoje = date('d/m/Y');
                header("Location: index.php?search=$hoje");
        die();
    }
	
}
if(isset($_POST['confirmar'])) {

   
    $id = intval($_GET['id']);
    $contato->excluir($id);
    if($contato) { ?>
        
        <?php
		header("Location: index.php");
        die();
    }
	
}

function formatar_data($data){
    return implode('/', array_reverse(explode('-', $data)));
}

?>
   
   <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Cliente</title>
</head>
<style>
	body{
		display: flex;
        width: auto;
        height: 100%;
		justify-content: center;
        text-align: center;
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
	}

    h3 {
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
<body>
    <div>
    <h3>Tem certeza que deseja excluir este pagamento?</h3><br><br>
	<?php 
	$id = intval($_GET['id']);
	
    $sql_cliente = "SELECT * FROM recibo WHERE id = '$id'";
    $query_cliente = $mysqli->query($sql_cliente) or die($mysqli->error);
    $info = $query_cliente->fetch_assoc();
/*
    var_dump($info['codigo_barras']);
	
	
   echo $codigo_barras22 = $info['codigo_barras']."<br><br>";

   echo  "82670000000-1 74741379622-4 28998828097-9 05450000000-2<br><br>";
        $part_01= substr($codigo_barras22, 0, 10);
        $part_02= substr($codigo_barras22, 11, 1);
        $part_03= substr($codigo_barras22, 12, 11);
        $part_04= substr($codigo_barras22, 23, 1);
        $part_05= substr($codigo_barras22, 24, 11);
        $part_06= substr($codigo_barras22, 35, 1);
        $part_07= substr($codigo_barras22, 36, 11);
        $part_08= substr($codigo_barras22, 47, 1);
    
   echo $codic = "$part_01-$part_02 $part_03-$part_04<br>$part_05-$part_06 $part_07-$part_08";

      */

//echo $codigo."<br>";
//echo $codigo_saneago."<br>";
	echo "<span style= 'text-transform: uppercase; color: blue; font-size: 17px;'>" .$info['banco_principal']. "</span><br><br>";
	echo "<span style= 'text-transform: uppercase; '>Codigo de Barras</span><br><span style= 'color: red; '> ". $info['codigo_barras'] . "</span><br><br>";
    echo "<span style= 'text-transform: uppercase; '>data pagamento</span><br><span style='color: red; '>  ". date('d/m/Y',strtotime($info['data_pagamento'])) . "</span><br><br>";
    echo "<span style= 'text-transform: uppercase; '>vencimento</span><br><span style='color: red; '>  ".formatar_data($info['data_vencimento']). "</span><br><br>";

	echo "<span style= 'text-transform: uppercase; '>valor</span><br><span style='color: red; '>R$  ". $info['valor_cobrado'] . "</span><br><br>";
	//echo "R$ ".$info['valor_cobrado']. "<br><br>";
	
	?>
    
    <form action="" method="post">
       
        <button style="width: 60px; cursor: pointer; height: 30px; border: none; margin-right:40px; background: green; color: white;" name="confirmar" value="1" type="submit">Sim</button>
		<button style="width: 60px; cursor: pointer; height: 30px; border: none; background: red; color: white;" name="excluir" type="submit">Não</button>
    </form>
    </div>
</body>
</html>
<?php

############################### CONEXAO NO BANCO DE DADOS ###########################################

$servidor = "mysql-ag-br1-20.conteige.cloud";
$usuario = "izeocp_sthiagopi";
$senha = "Stp020986";
$dbname = "izeocp_pagamento";

$mysqli = new mysqli($servidor, $usuario, $senha, $dbname);
if($mysqli->connect_errno) {
    die("Falha na conexão com o banco de dados");
}


?>

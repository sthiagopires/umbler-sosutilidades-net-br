<?php

// Definir fuso horário de São Paulo
date_default_timezone_set('America/Sao_Paulo');

    $servidor = "mysql-ag-br1-20.conteige.cloud";
	$usuario = "izeocp_sthiagopi";
	$senha = "Stp020986";
	$dbname = "izeocp_pagamento";

$mysqli = new mysqli($host, $user, $pass, $db);
if($mysqli->connect_errno) {
    die("Falha na conexão com o banco de dados");
}

function formatar_data($data){
    return implode('/', array_reverse(explode('-', $data)));
}
function formatar_telefone($telefone){
    $ddd = substr ($telefone, 0, 2);
    $parte1 = substr ($telefone, 2, 5);
    $parte2 = substr ($telefone, 7);
    return "($ddd) $parte1-$parte2";
}
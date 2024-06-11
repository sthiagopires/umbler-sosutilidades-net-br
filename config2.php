<?php
try {
	$pdo = new PDO("mysql:dbname=izeocp_pagamento;host=mysql-ag-br1-20.conteige.cloud", "izeocp_sthiagopi", "Stp020986");
} catch(PDOException $e) {
	echo "ERRO: ".$e->getMessage();
	exit;
}


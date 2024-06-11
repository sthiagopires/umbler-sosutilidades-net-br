<?php
	include_once('conexao.php');

function retorn($cpf, $mysqli){
	$result_cliente = "SELECT * FROM clientes WHERE cpf = '$cpf' LIMIT 1";
	$resultado_cliente = mysqli_query($mysqli, $result_cliente);
	if($resultado_cliente->num_rows){
		$row_clientes = mysqli_fetch_assoc($resultado_cliente);
		$valore['pagador'] = $row_clientes['pagador'];
		
	}else{
		$valore['pagador'] = 'Boleto Conveniado S.A.';
	}
	return json_encode($valore);
}

if(isset($_GET['cpf'])){
	echo retorn($_GET['cpf'], $mysqli);
}

function retorna($codigo_banco, $mysqli){
	$result_aluno = "SELECT * FROM bancos WHERE codigo_banco = '$codigo_banco' LIMIT 1";
	$resultado_aluno = mysqli_query($mysqli, $result_aluno);
	if($resultado_aluno->num_rows){
		$row_aluno = mysqli_fetch_assoc($resultado_aluno);
		$valores['banco_principal'] = $row_aluno['banco_principal'];
		$valores['beneficiario'] = $row_aluno['beneficiario'];
		
	}else{
		$valores['banco_principal'] = 'Boleto Conveniado S.A.';
		$valores['beneficiario'] = 'Boleto Conveniado S.A.';
	}
	return json_encode($valores);
}

if(isset($_GET['codigo_banco'])){
	echo retorna($_GET['codigo_banco'], $mysqli);
}






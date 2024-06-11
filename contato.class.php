<?php
class Contato {

	private $pdo;

	public function __construct() {
		$this->pdo = new PDO("mysql:dbname=izeocp_pagamento;host=mysql-ag-br1-20.conteige.cloud", "izeocp_sthiagopi", "Stp020986");
		// Definir fuso horário de São Paulo
		date_default_timezone_set('America/Sao_Paulo');
	}



	public function adicionar($codigo, $banco, $codigo_barras, $beneficiario, $pagador, $cpf, $data_vencimento,
	$data_pagamento, $valor_pagamento, $valor_juros, $valor_cobrado, $autenticacao) {
		
			$sql = "INSERT INTO recibo (nome, email) VALUES (:nome, :email)";
			$sql = $this->pdo->prepare($sql);
			$sql->bindValue(':codigo_banco', $codigo_banco);
			$sql->bindValue(':banco_principal', $banco_principal);
			$sql->bindValue(':codigo_barras', $codigo_barras);
			$sql->bindValue(':beneficiario', $beneficiario);
			$sql->bindValue(':pagador', $pagador);
			$sql->bindValue(':cpf', $cpf);
			$sql->bindValue(':data_vencimento', $data_vencimento);
			$sql->bindValue(':data_pagamento', $data_pagamento);
			$sql->bindValue(':valor_pagamento', $valor_pagamento);
			$sql->bindValue(':valor_juros', $valor_juros);
			$sql->bindValue(':valor_cobrado', $valor_cobrado);
			$sql->bindValue(':autenticacao', $autenticacao);
			$sql->execute();

			return true;
		
	}

	public function getInfo($id) {
		$sql = "SELECT * FROM recibo WHERE id = :id";
		$sql = $this->pdo->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			return $sql->fetch();
		}else{
			return array();
		}
	}

	public function getNome($id) {
		$sql = "SELECT codigo FROM recibo WHERE id = :id";
		$sql = $this->pdo->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$info = $sql->fetch();

			return $info['codigo_barras'];
		} else {
			return '';
		}
	}

	public function getAll() {
		$sql = "SELECT * FROM recibo ORDER BY data_pagamento DESC";
		$sql = $this->pdo->query($sql);

		if($sql->rowCount() > 0) {
			return $sql->fetchAll();
		} else {
			return array();
		}
	}

	

	public function editar($codigo, $banco, $codigo_barras, $beneficiario, $pagador, $cpf, $data_vencimento,
	$data_pagamento, $valor_pagamento, $valor_juros, $valor_cobrado, $autenticacao) {
		
			$sql = "UPDATE recibo SET codigo = :codigo, banco = :banco, codigo_barras = :codigo_barras,
			beneficiario = :beneficiario, pagador = :pagador, cpf = :cpf, data_vencimento = :data_vencimento,
			data_pagamento = :data_pagamento, valor_juros = :valor_juros, valor_cobrado = :valor_cobrado,
			autenticacao = :autenticacao WHERE id = :id";
			$sql = $this->pdo->prepare($sql);
			$sql->bindValue(':codigo_banco', $codigo_banco);
			$sql->bindValue(':banco_principal', $banco_principal);
			$sql->bindValue(':codigo_barras', $codigo_barras);
			$sql->bindValue(':beneficiario', $beneficiario);
			$sql->bindValue(':pagador', $pagador);
			$sql->bindValue(':cpf', $cpf);
			$sql->bindValue(':data_vencimento', $data_vencimento);
			$sql->bindValue(':data_pagamento', $data_pagamento);
			$sql->bindValue(':valor_pagamento', $valor_pagamento);
			$sql->bindValue(':valor_juros', $valor_juros);
			$sql->bindValue(':valor_cobrado', $valor_cobrado);
			$sql->bindValue(':autenticacao', $autenticacao);
			$sql->execute();

			return true;
		
	}

	public function excluir($id) {
		if($this->existeId($id)) {
			$sql = "DELETE FROM recibo WHERE id = :id";
			$sql = $this->pdo->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->execute();

			return true;
		} else {
			return false;
		}
	}

	private function existeId($id) {
		$sql = "SELECT * FROM recibo WHERE id = :id";
		$sql = $this->pdo->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}



}












<?php
class Usuarios {

	private $pdo;
	private $id;
	private $permissoes;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function fazerLogin($email, $senha, $usuario) {

		$sql = "SELECT * FROM login WHERE email = :email AND senha = :senha AND usuario = :usuario";
		$sql = $this->pdo->prepare($sql);
		$sql->bindValue(":email", $email);
		$sql->bindValue(":senha", $senha);
		$sql->bindValue(":usuario", $usuario);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$sql = $sql->fetch();

			$_SESSION['logado'] = $sql['id'];

			return true;
		}

		return false;
	}

	public function setUsuario($id) {
		$this->id = $id;

		$sql = "SELECT * FROM login usuario WHERE id = :id";
		$sql = $this->pdo->prepare($sql);
		$sql->bindValue(":id", $id);
		$sql->execute();

		if($sql->rowCount() > 0) {

			$sql = $sql->fetch();
			$this->permissoes = explode(',', $sql['permissoes']);

		}
	}

	public function getPermissoes() {
		return $this->permissoes;
	}

	public function temPermissao($p) {
		if(in_array($p, $this->permissoes)) {
			return true;
		} else {
			return false;
		}
	}



















}
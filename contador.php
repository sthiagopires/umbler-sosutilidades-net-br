
<?php

// Configura��es:

$contador = "reg.txt";	// patch do arquivo de grava��o
define ("adi", 1);	// quantidade a ser adicionada

// Fim de configura��es -------------------------------

$id = fopen($contador, "r+");
$conteudo = fread($id,filesize($contador));
fclose($id);
clearstatcache();

$conteudo += adi;

$id = fopen($contador, "r+");
fwrite($id, $conteudo, strlen($conteudo) + 5);
fclose($id);
clearstatcache();

//echo ($conteudo);
?>
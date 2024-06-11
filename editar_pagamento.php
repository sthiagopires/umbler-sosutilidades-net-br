<?php
session_start();
include_once('config2.php');
include_once('config.php');
require 'classes/usuarios.class.php';


if(!isset($_SESSION['logado'])) {
	header("Location: login.php");
	exit;
}

$usuarios = new Usuarios($pdo);
$usuarios->setUsuario($_SESSION['logado']);



?>

<?php
// LIMPEZA NO CAMPO DO TEXTO
include('conexao.php');
$id = intval($_GET['id']);
function limpa_codigo($str){ 
    return preg_replace("/[^0-9]/", "", $str); 
}

function formatar_data($data_vencimento, $data_pagamento){
    return implode('/', array_reverse(explode('-', $data_vencimento, $data_pagamento))); // FORMATAR DATA
}


if(count($_POST) > 0) {

   
    
    $erro = false;
    $usuario = $_POST['usuario'];
    $codigo_barras = limpa_codigo($_POST['codigo_barras']);
    $telefone = $_POST['telefone'];
    
    $banco_principal = $_POST['banco_principal'];
    $beneficiario = $_POST['beneficiario'];
    $pagador = $_POST['pagador'];
    $cpf = $_POST['cpf'];
    $data_vencimento = $_POST['data_vencimento'];
    $data_pagamento = $_POST['data_pagamento'];
    $valor_pagamento = $_POST['valor_pagamento'];
    $valor_juros = $_POST['valor_juros'];
    $valor_cobrado = $_POST['valor_cobrado'];
    $pago_confirmado = $_POST['pago_confirmado'];
    $autenticacao = $_POST['autenticacao'];

    $correspondente = $_POST['correspondente'];
    $pago = $_POST['pago'];

    

    if(strlen($codigo_barras) < 48){  
        
        
        if(isset($data_vencimento)){          
        $venc = substr("$codigo_barras", 33, -10);
        $codic = substr("$codigo_barras", 0, 3);
        
        
        
        
        $data = "1997-10-07";
        
        $data_vencimento= strtotime($data) + (86400*$venc) + 3600;
        
        $data_vencimento = date("d/m/Y", $data_vencimento);
        
        }    
        
        if(isset($valor_pagamento)){
        $valor = substr($codigo_barras, -6);
        $valor_pagamento = number_format((float)$valor/100, 2, '.', '');
        }
        
        //echo "$valor_pagamento <br>";
        
        if(isset($valor_juros)){
        $juros = Limpa_codigo($_POST['valor_juros']); 
        $valor_juros = number_format((float)$juros/100, 2, '.', '');       
        
        }
        
        
        
        if(isset($valor_cobrado)){
        $valor_cobrado = (float)$valor_pagamento + (float)$valor_juros;         
        
        }
        
        }
        $part_01= substr($codigo_barras, 0, 10); 
        $part_02= substr("$codigo_barras", 10, 11);
        $part_03= substr("$codigo_barras", 21, 12);
        $part_04= substr("$codigo_barras", 33);
        
        $codigo2 = "$part_01 $part_02<br>$part_03 $part_04";

        //Codigo do Bancos

        $codigo3 = substr($codigo_barras, 0, 4); 

        
        
        
        
        
        if(strlen($codigo_barras) > 47){
        
        
                
        $codic = substr("$codigo_barras", 0, 3);
        $part_00= "0";
        $part_01= substr($codigo_barras, 0, 11); 
        $part_02= substr("$codigo_barras", 11, 1);
        $part_03= substr("$codigo_barras", 12, 12);
        //$part_04= substr("$cod", 23, 13);
        $part_05= substr("$codigo_barras", 24, 12);
        $part_06= substr("$codigo_barras", 36, 12);
        
        
        // SUBISTITUIR O CODIGO 2 EM (NADA)NULL
        
        $part_02 = "";
        
        $codigo_barras = "$part_01$part_02$part_03$part_05$part_06$part_00";
        $codigo2 = "$part_01$part_02 $part_03<br>$part_05 $part_06$part_00";
        
        //echo "$part_01";
        
        //$part_02;
        //$part_02 = "";
        //echo "$cod_final  <br>";
        //echo strlen($cod_final);
        
        if(isset($valor_pagamento)){
        $part= substr($codigo_barras, 9, 6);
        $valor_pagamento = number_format((float)$part/100, 2, '.', '');
        }
        
        //echo "$valor_pagamento <br>";
        
        if(isset($valor_juros)){
        $juros = Limpa_codigo($_POST['valor_juros']); 
        $valor_juros = number_format((float)$juros/100, 2, '.', '');       
        
        }
        
        if(isset($valor_cobrado)){
        $valor_cobrado = (float)$valor_pagamento + (float)$valor_juros;
                
        
        }
        
        $data_vencimento= date("d/m/Y");
        
        }
        if(isset($_POST['codigo_barras'])){
            $codigo_barras = $_POST['codigo_barras'];          
            $cod = substr("$codigo_barras", 0, 3); 
        }

        $pag = Limpa_codigo($_POST['pago_confirmado']); 
        $pago_confirmado = number_format((float)$pag/100, 2, '.', ''); 

        $valor_pagamento = number_format($valor_pagamento, 2, '.', ''); 
        $valor_juros = number_format($valor_juros, 2, '.', ''); 
        $valor_cobrado = number_format($valor_cobrado, 2, '.', ''); 
        $pago_confirmado = number_format($pago_confirmado, 2, '.', ''); 


        if(!empty($data_vencimento)) { 
            $pedacos = explode('/', $data_vencimento);
            if(count($pedacos) == 3) {
                $data_vencimento2 = implode ('-', array_reverse($pedacos));
            }
        }

        if(!empty($data_pagamento)) { 
            $pedacos = explode('/', $data_pagamento);
            if(count($pedacos) == 3) {
                $data_pagamento = implode ('-', array_reverse($pedacos));
            }
        }

           
        if($erro) {
            echo "<div><b>ERRO: $erro</b></div>";
            
            //CADASTRANDO NO BANCO DE DADOS (INSERT INTO )
            
        }else{

            $sql_code = "UPDATE recibo SET 

            usuario = '$usuario',
            codigo_barras = '$codigo_barras',
            telefone = '$telefone',
            banco_principal = '$banco_principal', 
            
            beneficiario = '$beneficiario',
            pagador = '$pagador', 
            cpf = '$cpf', 
            data_vencimento = '$data_vencimento2', 
            data_pagamento = '$data_pagamento', 
            
            valor_pagamento = '$valor_pagamento',
            valor_juros = '$valor_juros', 
            valor_cobrado = '$valor_cobrado',
            pago_confirmado = '$pago_confirmado',
            correspondente = '$correspondente',
            pago = '$pago'
            WHERE id = '$id'";
            $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);
            if($deu_certo) {
                $hoje = date('d/m/Y');
                header("Location: index.php?search=$hoje");
                unset($_POST);
        }



           
        }

      //var_dump($correspondente);

      
       //echo $data_vencimento2."<br>";
       //echo $data_pagamento."<br>";
      // echo $telefone."<br>";
      // echo $codigo_barras."<br>";

      //var_dump($_POST);

    }
    $sql_cliente = "SELECT * FROM recibo WHERE id = '$id'";
    $query_cliente = $mysqli->query($sql_cliente) or die($mysqli->error);
    $info = $query_cliente->fetch_assoc();

    //var_dump($info);
       
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="bootstrap.min.js"></script>
		<script type="text/javascript" src="jquery.mask.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
        <script type="text/javascript" src="function.js"></script>
        <script type="module" src="app.js"></script>
    <link rel="stylesheet" href="style.css">

    <style>

<?php  if($usuarios->temPermissao('ADM')):?>
    .container {  
        padding-left: 25px;

    }

    <?php endif ?>
    </style>

    <title>Cadastro boleto</title>

    <?php

        ?>
   
</head>
<body>
    
<?php $data_Pagamento = date("d/m/Y",strtotime($info['data_pagamento'])); ?>
<?php $datavencimento = date("d/m/Y",strtotime($info['data_vencimento'])); ?>
<div class="container"><!-- CONTAINER INICIO  editar2.php?id=<?php // echo $info['id']?> -->

                    <form action=""  method="POST">
                        <h3>PAGAMENTO</h3>

                        <?php if($usuarios->temPermissao('ADM')):?> 
                    <div>
                        <input class="btn-medium" type="submit" value="celcoin" onclick="celcoin()">
                        <input class="btn-medium" type="submit" value="Banco do Brasil" onclick="banco_do_brasil()">
                        <input class="btn-medium" type="submit" value="Banco do Itaú" onclick="banco_itau()">
                        <input class="btn-medium" type="submit" value="Aguardando . . ." onclick="Aguardando()">
                        <input class="btn-medium" type="submit" value="Pago via PIX" onclick="pix()">
                    </div>                    
                    <?php  endif; ?>

                        <div><input value="<?php echo $info['codigo_barras']; ?>" class="campo-a" type="text" name="codigo_barras"/>
                            
                        <span><input type="button" value="Colar" class="colar"></span><span><input type="button" value="Copiar" class="copiar"></span>
                       
                         </div>

                         </label><br>
                <label>Telefone Cliente:
                <div><input class="campo-b" value="<?php echo formatar_telefone($info['telefone']); ?>" type="text" id="phone" name="telefone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);">
                <span><input type="button" value="Colar" class="colar2"></span><span><input type="button" value="Copiar" class="copiar2"></span></div>  
                </label>
                    
                        <!-- </label><br> -->
                        <div>
                            <input  hidden value="<?php echo $info['id']; ?>"  name="id" type="text">
                            <input hidden type="text" id="correspondente" name="correspondente" value="<?php echo $info['correspondente']; ?>">
                            <input hidden type="text" id="pago" name="pago" value="<?php echo $info['pago']; ?>">
                        </div><br>

                        <?php if($usuarios->temPermissao('ADM')):?> 
                        <label>Usuario Sistema:
                        <input  type="text" name="usuario" value="<?php echo $info['usuario']; ?>">
                        </label><br><br> 
                        <?php endif ?>
                        <?php if($usuarios->temPermissao('USER')):?> 
                         <label>Codigo Bancario:
                            <div><input autofocus maxlength="3" value="<?php echo $info['codigo_banco']; ?>"  name="codigo_banco" type="text"></div>
                         </label><br>
                         <?php endif ?>
                        <label>Banco Principal:
                            <div><input value="<?php echo $info['banco_principal']; ?>" name="banco_principal" type="text"></div>
                        </label><br>

                        <?php if($usuarios->temPermissao('USER')):?>         
                        <label>Beneficiario:
                            <div><input  value="<?php echo $info['beneficiario']; ?>" name="beneficiario" type="text"></div>
                         </label><br>
                         <?php endif ?>
        
                        <label>Pagador:
                            <div><input value="<?php echo $info['pagador']; ?>" name="pagador" type="text"></div>
                        </label><br>
        
                        <label>Pagador CPF:
                            <div><input value="<?php echo $info['cpf']; ?>" oninput="mascara(this)" name="cpf" type="text"></div>
                        </label><br>
        
                        <label style="color: red;">Data de Vencimento:
                    <div><input style="color: red;" id="outra_data" value="<?php echo $datavencimento ?>" name="data_vencimento" type="text"></div>
                        </label><br>
        
                        <label>Data de Pagamento:
                            <div><input id="outra_data1" name="data_pagamento" value="<?php  echo $data_Pagamento; ?>" type="text"></div>
                        </label><br>
        
                        <label>Valor do Pagamento:
                            <div><input value="<?php echo $info['valor_pagamento']; ?>" name="valor_pagamento" type="text"></div>
                        </label><br>
        
                        <label>Valor Juros:
                        
                           
                            <div><input size="12" onKeyUp="mascaraMoeda(this, event)" value="<?php echo $info['valor_juros'] ?>" name="valor_juros" type="text"></div>
                        </label><br>
        
                        <label>Valor Cobrado:
                            <div><input value="<?php echo $info['valor_cobrado']; ?>" name="valor_cobrado" type="text"></div>
                            <div><input size="12" onKeyUp="mascaraMoeda(this, event)" id="pago_confirmado" value="<?php echo $info['valor_cobrado'] ?>" name="pago_confirmado" type="text"></div>
                        </label><br>

                        <?php  if($usuarios->temPermissao('USER')):?>  
                        <label>Autenticação:
                            <div><input  value="<?php echo $info['autenticacao']; ?>"class="autentic"  name="autenticacao" type="text"></div>
                        </label><br>
                        <?php  endif; ?>
                        
                        <div><button  class="cadastro" type="submit">PAGAR</button></div>      
                        
                      </form>
                </div><!--CONTAINER FIM--> 


                <script>
         const btnCopiar = document.querySelector('input.copiar');
         const textAreaCampoA = document.querySelector('input.campo-a')
         const btnColar = document.querySelector('input.colar');

         // Aguardar o click do usuário no botão copiar
        btnCopiar.addEventListener('click', (e) => {
            //e.preventDefault();

            // textAreaCampoA.value = Receber os dados do campo A
            // A propriedade Clipboard grava a string de texto especificada na área de transferência do sistema.
            navigator.clipboard.writeText(textAreaCampoA.value);

            
        });

         // Aguardar o click do usuário no botão colar
         btnColar.addEventListener('click', async (e) => {
            //e.preventDefault();

            // Utilizado o readText para ler o conteúdo
            const response = await navigator.clipboard.readText();

            // Enviar o texto para o campo B
            textAreaCampoA.value = response;

            
        });
    </script>

<script>
         const btnCopiar2 = document.querySelector('input.copiar2');
         const textAreaCampoB = document.querySelector('input.campo-b')
         const btnColar2 = document.querySelector('input.colar2');

         // Aguardar o click do usuário no botão copiar
        btnCopiar2.addEventListener('click', (e) => {
            //e.preventDefault();

            // textAreaCampoA.value = Receber os dados do campo A
            // A propriedade Clipboard grava a string de texto especificada na área de transferência do sistema.
            navigator.clipboard.writeText(textAreaCampoB.value);


            
        });

         // Aguardar o click do usuário no botão colar
         btnColar2.addEventListener('click', async (e) => {
            //e.preventDefault();

            // Utilizado o readText para ler o conteúdo
            const response = await navigator.clipboard.readText();

            // Enviar o texto para o campo B
            textAreaCampoB.value = response;
                        
        });
    </script>

        <script type='text/javascript'>
			$(document).ready(function(){
				$("input[name='cpf']").blur(function(){
					var $pagador = $("input[name='pagador']");
					
					$.getJSON('function.php',{ 
						cpf: $( this ).val() 
					},function( json ){
						$pagador.val( json.pagador );
						
					});
				});
			});

			
		</script>

    <script type='text/javascript'>
			$(document).ready(function(){
				$("input[name='codigo_banco']").blur(function(){
					var $banco_principal= $("input[name='banco_principal']");
                    var $beneficiario= $("input[name='beneficiario']");
					
					$.getJSON('function.php',{ 
						codigo_banco: $( this ).val() 
					},function( json ){
						$banco_principal.val( json.banco_principal );
                        $beneficiario.val( json.beneficiario);
						
					});
				});
			});

			
		</script>
                               <!--MASCARA CPF  ACRECENTE NO INPUT////// oninput="mascara(this)"//////-->
<script>
        function mascara(i){
        
        var v = i.value;
        
        if(isNaN(v[v.length-1])){ // impede entrar outro caractere que não seja número
            i.value = v.substring(0, v.length-1);
            return;
        }
        
        i.setAttribute("maxlength", "14");
        if (v.length == 3 || v.length == 7) i.value += ".";
        if (v.length == 11) i.value += "-";

        }
        

</script>

<!--MASCARA PARA REAL ACRESCENTE NO INPUT///// size="12" onKeyUp="mascaraMoeda(this, event)"-->

<script>

String.prototype.reverse = function(){
  return this.split('').reverse().join(''); 
};

function mascaraMoeda(campo,evento){
  var tecla = (!evento) ? window.event.keyCode : evento.which;
  var valor  =  campo.value.replace(/[^\d]+/gi,'').reverse();
  var resultado  = "";
  var mascara = "##.###.###,##".reverse();
  for (var x=0, y=0; x<mascara.length && y<valor.length;) {
    if (mascara.charAt(x) != '#') {
      resultado += mascara.charAt(x);
      x++;
    } else {
      resultado += valor.charAt(y);
      y++;
      x++;
    }
  }
  campo.value = resultado.reverse();
}

</script>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
    <SCRIPT language="javascript">
     $(document).ready(function () {
        $('#outra_data').mask('99/99/9999');
        $('#outra_data1').mask('99/99/9999');
        $('#outra_data2').mask('99/99/9999');
        return false;
    });

    function mask(o, f) {
  setTimeout(function() {
    var v = mphone(o.value);
    if (v != o.value) {
      o.value = v;
    }
  }, 1);
}

function mphone(v) {
  var r = v.replace(/\D/g, "");
  r = r.replace(/^0/, "");
  if (r.length > 10) {
    r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
  } else if (r.length > 5) {
    r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
  } else if (r.length > 2) {
    r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
  } else {
    r = r.replace(/^(\d*)/, "($1");
  }
  return r;
}

    </SCRIPT>
    <script>

function celcoin() {
      document.getElementById('correspondente').value = "Celcoin Pagamentos SA";
      document.getElementById('pago').value = "Confirmado";
        }; 

function banco_do_brasil(){
      document.getElementById('correspondente').value = "Banco do Brasil";
      document.getElementById('pago').value = "Confirmado";
        };

function banco_itau(){
      document.getElementById('correspondente').value = "Banco do Itaú";
      document.getElementById('pago').value = "Confirmado";
        };

function Aguardando(){
      document.getElementById('correspondente').value = "";
      document.getElementById('pago').value = "Aguardando . . .";
      document.getElementById('pago_confirmado').value = "0.00";
        };

function pix(){
      document.getElementById('correspondente').value = "Pago via PIX";
      document.getElementById('pago').value = "Confirmado";
        };
        

</script>
<script>
    
</script>
</body>
</html>
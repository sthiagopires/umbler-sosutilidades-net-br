<?php
 session_start();
include_once('config2.php');
include_once('config.php');
include_once('contador.php');
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

//var_dump($_POST);

function limpa_codigo($str){ 
    return preg_replace("/[^0-9]/", "", $str); 
}

if(count($_POST) > 0) {

    include('conexao.php');
    
    $erro = false;
    $usuario = $_POST['usuario'];
    $contagem = $_POST['contagem'];
    $codigo_barras = limpa_codigo($_POST['codigo_barras']);
    $codigo_barras22 = limpa_codigo($_POST['codigo_barras']);
    $telefone = limpa_codigo($_POST['telefone']);
    $codigo_banco= $_POST['codigo_banco'];
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

        $codigo3 = substr($codigo_barras, 0, 3); 

        
        
        
        
        
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
        
        $codigo_barras = "$part_01$part_02$part_03$part_05$part_06$part_00<br>br>";
        $codigo2 = "$part_01$part_02 $part_03<br>$part_05 $part_06$part_00<br>br>";
        $codigo_barras22;
        
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

        

        $valor_pagamento = number_format($valor_pagamento, 2, '.', ''); 
        $valor_juros = number_format($valor_juros, 2, '.', ''); 
        $valor_cobrado = number_format($valor_cobrado, 2, '.', '');
        
        



        if(isset($data_vencimento)) { 
            $pedacos = explode('/', $data_vencimento);
            if(count($pedacos) == 3) {
                $data_vencimento2 = implode ('-', array_reverse($pedacos));
            }
        }

        if(isset($data_pagamento)) { 
            $pedacos = explode('/', $data_pagamento);
            if(count($pedacos) == 3) {
                $data_pagamento = implode ('-', array_reverse($pedacos));
            }
        }

        if(empty($telefone)) {
            $telefone = limpa_codigo($telefone);
            if(strlen($telefone) != 11)
                $erro = "O telefone deve ser preenchido no padrão (11) 98888-8888";
        }

        
        if(empty($_POST['pagador'])) {
            $erro = "Preencha o campo Pagador";
        }

        $busca_codigo = "SELECT * FROM recibo WHERE codigo_barras = '$codigo_barras' AND valor_cobrado = '$valor_cobrado'";
        $resultado_codigo = mysqli_query($mysqli,$busca_codigo);
        $total_codigo = mysqli_num_rows($resultado_codigo);
  
        if($total_codigo > 0){
            
            echo "<div style='padding-top: 2px; padding-bottom: 2px; color: red; display: flex; justify-content: center; border: 2px solid;'><b>-------PAGAMENTO NÃO REALIZADO!! <br>(391) Pagamento já realizado anteriormente.<br><br><a href='cadastrar_cliente.php'>-------VOLTAR AO MENU ANTERIOR!</a></b></div>";
          die;
        }

        $hoje = date('d/m/Y');
    
        if($erro) {
            echo "<div><b>Mensagem:</b></div>";
            echo "<div style='padding-top: 2px; padding-bottom: 2px; color: red; display: flex; justify-content: center; border: 1px solid;'><b>$erro</b></div>";
            
            //CADASTRANDO NO BANCO DE DADOS (INSERT INTO )
            
        }else{
            $sql_code = "INSERT INTO recibo (usuario, contagem, codigo_barras, telefone, codigo_banco, banco_principal, beneficiario, pagador, cpf, data_vencimento, data_pagamento, valor_pagamento, valor_juros, valor_cobrado, pago_confirmado, autenticacao, data_operacao) 
        VALUES ('$usuario', '$contagem', '$codigo_barras22', '$telefone', '$codigo_banco', '$banco_principal', '$beneficiario', '$pagador', '$cpf', '$data_vencimento2', '$data_pagamento', '$valor_pagamento', '$valor_juros', '$valor_cobrado', '$pago_confirmado', '$autenticacao', NOW())";
        $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);
        if($deu_certo) {
            unset($_POST);
            
            header("Location: index.php?search=$hoje");
            }
        }

    }
       //echo $data_vencimento2."<br>";
       //echo $data_pagamento."<br>";
      // echo $telefone."<br>";
      // echo $codigo_barras."<br>";

      //var_dump($_POST);

    
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
    <style></style>

    <title>Cadastro boleto</title>
   
</head>
<body>
    
            <!-- CONTAINER INICIO-->
        <div class="container">
             
        <!-- FORMULARIO-->
            <form action="" method="POST">
                <h3>PAGAMENTO</h3>
                
                <!-- <label>Usuario Sistema: -->
                <input hidden type="text" name="usuario" value=" <?php echo $_POST['usuario'] ?> ">
                <!-- </label><br> -->
                
                <!-- <label>contagem: -->
                <input hidden type="text" name="contagem" value="<?php $contagem = $conteudo; echo $contagem;?>">
                <?php // var_dump($contagem); ?>
                <!-- </label><br> -->

                <label><span>Codigo de Barras:</span>               
                    <div><input id="campo" value="<?php if(isset($_POST['codigo_barras'])) echo limpa_codigo($codigo_barras22);?>" placeholder="Cole o Codigo de Barras" class="campo-a" type="text" name="codigo_barras"/>
         
                <span><input type="button" value="Colar" class="colar"></span>
                <input onclick="limpar()" class="reset" value="Limpar" type="text">
            </div>
        
                </label><br>
                <label>Telefone Cliente:
                <div><input class="campo-b" value="<?php echo '(64)99';?>" type="text" id="phone" name="telefone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);">
                <span><input type="button" value="Colar" class="colar2"></span><span><input type="button" value="Copiar" class="copiar2"></span></div>  
                </label>

                </label><br>
                <label>Codigo Bancario:
                    <div><input autofocus value="<?php if(isset($codigo3)) echo $codigo3;?>"  maxlength="3" class="campo-a"  name="codigo_banco" type="text"></div>
                </label><br>

                <label>Banco Principal:
                    <div><input value="<?php if(isset($banco_principal)) echo $banco_principal;?>" name="banco_principal" type="text"></div>
                </label><br>

                <label>Beneficiario:
                    <div><input value="<?php if(isset($beneficiario)) echo $beneficiario;?>" name="beneficiario" type="text"></div>
                </label><br>

                <label>Pagador:
                    <div><input value="<?php if(isset($pagador)) echo $pagador;?>" required name="pagador" type="text"></div>
                </label><br>

                <label>Pagador CPF:
                    <div ><input value="000.000.000-00"   oninput="mascara(this)" name="cpf" type="text"></div>
                </label><br>

                <label>Data de Vencimento:
                    <div><input id="outra_data" value="<?php if(isset($data_vencimento)) echo $data_vencimento;?>" name="data_vencimento" type="text"></div>
                </label><br>

                <label>Data de Pagamento:
                    <div><input id="outra_data1" value="<?php $hoje = date('d/m/Y'); echo $hoje; ?>" name="data_pagamento" type="text"></div>
                </label><br>

                <label>Valor do Pagamento:
                    <div><input value="<?php if(isset($valor_pagamento)) echo $valor_pagamento;?>" size="12" onKeyUp="mascaraMoeda(this, event)" name="valor_pagamento" type="text"></div>
                </label><br>

                <label>Valor Juros: 
                <?php /* if(isset($valor_juros)){
                        $valor_juros = $_POST['valor_juros'];
                    }else{
                        $valor_juros = "0,00";
                    } */ ?>
                    <div><input value="<?php if(isset($valor_juros)) echo $valor_juros;?>" size="12" onKeyUp="mascaraMoeda(this, event)" placeholder="digite o valor do juros" name="valor_juros" type="text"></div>
                </label><br>

                <label>Valor Cobrado:
                    <div><input value="<?php if(isset($valor_cobrado)) echo $valor_cobrado;?>" size="12" onKeyUp="mascaraMoeda(this, event)" name="valor_cobrado" type="text"></div>
                    <div><input value="<?php if(isset($pago_confirmado)) echo "0.00";?>" size="12" onKeyUp="mascaraMoeda(this, event)" name="pago_confirmado" type="text"></div>
                </label><br>

                 <!-- <label>Autenticação: -->
                    
                     <?php $numero_de_bytes = 15;
                            $restultado_bytes = random_bytes($numero_de_bytes);
                            $resultado_final = bin2hex($restultado_bytes); ?>
                    <div><input hidden style="text-align: center;" class="autentic" value="<?php echo $resultado_final ?>" name="autenticacao" type="text"></div>
                <!--  </label><br> -->

                <div><button  class="cadastro" type="submit">CADASTRAR</button></div>      
                
              </form>
        </div><!--CONTAINER FIM-------------------------------------------------------------------------------------------------->

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
<!-- LIMPAR CAMPO DO INPUT VIA javascript  -->
<script>
   function limpar(){
        document.getElementById('campo').value=''; // Limpa o campo

    };
</script>
</body>
</html>
<?php
session_start();

if(!isset($_SESSION['logado'])) {
	header("Location: login.php");
    die();
}

include_once('config2.php');
include_once('config.php');
require 'classes/usuarios.class.php';


$usuarios = new Usuarios($pdo);
$usuarios->setUsuario($_SESSION['logado']);



?>
<!--
<?php // if($usuarios->temPermissao('ADD')): ?>
<a href="">Adicionar Documento</a><br/><br/>
<?php // endif; ?> -->

<!DOCTYPE html>
<html lang="pt-br">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.50">
        <title>Pagamentos sos utilidades</title>
<style>
        body{
                width: 100hv;
                height: 100%;
                padding: 0;
                margin: 0;
                font-family: Verdana, Geneva, Tahoma, sans-serif;
                
        }
        div.container {
                display: flex;
                justify-content: center;
                
               
        }

        table {
                table-layout: fixed;
                text-align: center;
                width: 100%;
                height: 100%;
        }

       
        a.editar{
               
            text-decoration: none;
            font-size: 16pt;
            border-radius: 5px;
            padding: 8px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: green;
            color: white;            
            
        }

        a.excluir{
            text-decoration: none;
            font-size: 16pt;
            border-radius: 5px;
            padding: 8px;
            padding-left: 10px;
            padding-right: 10px;
            background-color: red;
            color: white;            
            margin: 2px;
                    }

        a.comprovante{
            text-decoration: none;
            font-size: 15pt;
            border-radius: 5px;
            padding: 10px;
            padding-left: 5px;
            padding-right: 5px;
            background-color: blue;
            color: white;            
            
        }

       
        .flexx {
            width: 30px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .flex {
                padding: 10px;
        }

        thead {
                background-color: black;
                color: white;
                height: 40px;                
        }

        tbody tr:nth-child(even) {
                background-color: #ddd;
        }

    

      

       

        /* Menor que o padrão 960 (dispositivos e navegadores) */
@media only screen and (max-width: 959px) {
    body{
        font-size: 12pt;
    }
}

/* Tamanho retrato do tablet para o padrão 960 (dispositivos e navegadores) */
@media only screen and (min-width: 768px) and (max-width: 959px) {
  body{
        font-size: 12pt;
    }
}

/* Todos os tamanhos de celular (dispositivos e navegador) */
@media only screen and (max-width: 767px) {
    body{
        font-size: 18pt;
    }
}

/* Mobile Landscape Size to Tablet Portrait (dispositivos e navegadores) */
@media only screen and (min-width: 480px) and (max-width: 767px) {
   body{
        font-size: 18pt;
    }
}

/* Mobile Portrait Size to Mobile Landscape Size (dispositivos e navegadores) */
@media only screen and (max-width: 479px) {
    body{
        font-size: 18pt;
    }    
}

.edit {
        height: 50px;
        margin-top: 5px;
        width: auto;
}

.comprov {
    display: flex;
        margin-bottom: 10px;
        justify-content: center;
}

.delete {
        margin-bottom: 28px;
}

a.pagamento{
            text-decoration: none;
            font-size: 24px;
            border-radius: 5px;
            padding: 10px;
            background-color: green;
            color: white;            
            margin: 5px;
            margin-bottom: 10px;
        }
        div.pagamentos {
            display: flex;
            justify-content: center;
        }

.search {
            display: flex;
            justify-content: right;
           
        }

input {
            padding-left: 6px;
            border-radius: 5px;            
            margin-left: 25%;
            font-weight: bold;
            font-size: 18pt;
            color: blue;
            
        }

        button {
            border-radius: 5px;
            width: 200px;
            height: 40px;
            background-color: black;
            color: white;
            font-size: 18pt;
            margin-right: 25px;
        
        }

.bem-vindo {
            margin-left: 20px;
        }

        .soma {
            margin-left: 15px;
        }
.sair {
    display: flex;
    justify-content: right;
    padding-right: 20px;
	padding-top: 20px;
    font-size: 18px;
    font-weight: bold;
}
.voltar_login {
	padding-left: 20px;
}
</style>

<?php
    //Recebe os dados do formulário
    //$data = filter_input_array(INPUT_GET, FILTER_DEFAULT);
    ?>
    <?php
if(!empty($_GET['search']))
{
    $data = $_GET['search'];
    $pedacos = explode('/', $data);
    
        $data = implode ('-', array_reverse($pedacos));


        $sql_clientes = "SELECT * FROM recibo WHERE data_pagamento LIKE '%$data%' or valor_cobrado LIKE '%$data%' or banco_principal LIKE '%$data%' ORDER BY data_operacao DESC";
}
else
{
    $sql_clientes = "SELECT * FROM recibo  ORDER BY data_operacao DESC";
}

$query_clientes = $mysqli->query($sql_clientes) or die($mysqli->error);
$num_clientes = $query_clientes->num_rows;



//PESQUISA E SOMA A DATA DE HOJE POR PESQUISA
if (isset($data)){
$sql = "SELECT data_pagamento, Sum(valor_cobrado ) as valor_cobrado FROM recibo WHERE data_pagamento = '$data' AND valor_cobrado - pago_confirmado";
$query_cliente = $mysqli->query($sql) or die($mysqli->error);
$num_cliente = $query_cliente->num_rows;
}else{

//SE NÃO HOUVER PESQUISA SOMA TODO O PERIODO
$sql = "SELECT data_pagamento, Sum(valor_cobrado ) as valor_cobrado FROM recibo WHERE valor_cobrado - pago_confirmado";
$query_cliente = $mysqli->query($sql) or die($mysqli->error);
$num_cliente = $query_cliente->num_rows;
}




?>

</head>



<body>
    
<div class="sair" ><a href="sair.php" >Sair</a></div>


<div class="bem-vindo"> <h1>Bem vindo</h1> </div>

<div class="voltar_login" ><a href="login.php" >Voltar Login</a></div>

    <div class="search">   

    <?php 
    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
                date_default_timezone_set('America/Sao_Paulo');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
                $dataLocal = date('d/m/Y', time()); 
                ?>

        <input name="hoje" type="search" value="<?php echo $dataLocal?>" placeholder="Pesquisar" id="pesquisar">
        <button onclick="searchData()" class="btn">pesquisar</button>
    </div><br><br>

	<?php if($usuarios->temPermissao('ADM')):?>
    <?php 
        if($num_clientes < 10){ echo "<p class='soma'; style='font-size: 14pt;color: blue;'>Quantidades: <span style='color: red';>0$num_clientes</span></p>";
        }else{
        echo "<p class='soma'; style='font-size: 14pt;color: blue;'>Quantidades: <span style='color: red';>$num_clientes</span></p>";
        
        }
        ?>
<?php
            /*<?php echo date('d/m/Y') ?>*/
            while ($ADM = $query_cliente->fetch_assoc()) {  
                
                $valorpag1 = number_format((float)$ADM['valor_cobrado'], 2, ',', '.');
                
                 
                echo "<p class='soma'; style='font-size: 14pt; color: red; font-weight: bold;'>Valor Total: R$ $valorpag1<br><br>";
                
            }
            
 endif;
            
            ?><br><br>
<div class="pagamentos"> <a class="pagamento" href="cadastrar_cliente.php">Fazer Pagamentos</a><br><br></div>
        <div class="container">
       <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Convenio</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
						<?php if($usuarios->temPermissao('ADM')):?> 
						<th>pagamento</th><br>
						<?php  endif; ?>
                        <th>Ações</th><br>
                    </tr>
                </thead>
                <tbody>
                <?php
                    while($item = mysqli_fetch_assoc($query_clientes)) {
                        $valorCobrado = number_format($item['valor_cobrado'], 2, ',', '.'); 
                        
                        
                        ?>
                        
                        <tr>
                        <?php $dataPagamento = date("d/m/Y", strtotime($item['data_pagamento'])); ?>
                        <?php $dataPagamento2 = date('H:i:s', strtotime($item['data_operacao'])); ?>
                        <?php $datavencimento = date("d/m/Y", strtotime($item['data_vencimento'])); ?>
                            <td class="flex" style="color: green; font-weight: bold;"><?php echo $dataPagamento ?><br><?php echo $dataPagamento2 ?></td>
                            <td class="flex" style="color: blue; font-weight: bold; "><?php echo $item['banco_principal'] ?></td>
                            <td class="flex" style="color: red; font-weight: bold; "><?php echo $datavencimento ?></td>
                            <td class="flex" style="color: green; font-weight: bold; "><?php echo"R$ " .$valorCobrado ?></td>
							<?php if($usuarios->temPermissao('ADM')):?>
                            <td class="flex" style="color: blue; font-weight: bold; "><div style="color: red;"><hr><?php echo $item['usuario'] ?></div><hr><?php echo $item['correspondente']?><br><br><span style="color: green; font-weight: bold; "><div style="border: 2px solid DeepPink; padding: 5px; color: DeepPink; " ><?php echo $item['pago']?></div></span></td>
                            <?php  endif; ?>
							<td class="flexx" ><?php if($usuarios->temPermissao('ADM')):?> <div class="edit"><a class="editar" href="editar_pagamento.php?id=<?php echo $item['id'] ?>">Editar</a></div><div class="delete"><a class="excluir" href="excluir.php?id=<?php echo $item['id'] ?>">Excluir</a></div><?php  endif; ?><div class="comprov"><a class="comprovante" href="comprovante.php?id=<?php echo $item['id'] ?>">Comprovante</a><?php if($usuarios->temPermissao('USER')):?><br><br><?php endif ?></div></td>
                            
                        </tr>
                    <?php }?>
                        
                </tbody>
       </table>
       </div>
       
       <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
    <SCRIPT language="javascript">
     $(document).ready(function () {
        $('#pesquisar').mask('99/99/9999');

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
        var search = document.getElementById('pesquisar');
        
        

        search.addEventListener("keydown", function(event) {
            if (event.key === "Enter") 
            {
                searchData();
            }
        });

        function searchData()
        {
            window.location = 'index.php?search='+search.value;
        }
    </script>


</body>
</html>










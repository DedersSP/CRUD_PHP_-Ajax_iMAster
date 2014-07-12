<?php include '../config.php'; ?>

<?php
$msg = '';
if (isset($_REQUEST['msg'])) {
	$msg = $_REQUEST['msg'];
	echo $msg;
}

$sqlSelect = "select * from view_clientes";
$queryConsult = mysql_query($sqlSelect, $conn) or die(mysql_error()); 

?>

<!DOCTYPE html>
<html lang="pt-BR">
	<head>		
		<title>iMasters PHP and jQuery | Listagem Clientes</title>		
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../style.css">
		<script src="../jquery/jquery-1.11.1.min.js" type="text/javascript" charset="utf-8"></script>
		
	</head>
	<body>
		<header>
			<h1>Crud - iMasters PHP and jQuery</h1>
			<a href="../index.php">Home</a>
			<h3>Listagem Clientes</h3>
		</header>
		<div id="content">
			<table id="cli_listagem" cellspacing="5" cellpadding="5" width="600px">
				<tr>
					<th>Código</th>
					<th>Nome</th>					
					<th>CPF</th>
					<th>View Detalhes</th>
					<th>Opções</th>
				</tr>
				<?php while ($result = mysql_fetch_assoc($queryConsult)) {?>
				<tr>
					<td><?php echo $result['cli_id']; ?></td>
					<td><?php echo $result['cli_nome']; ?></td>					
					<td><?php echo ($result['cli_tipo'] == 1) ? $result['cli_cpf'] : $result['cli_cnpj']; ?></td>					
					<td align="center"><a class="link_detalhes" href="javascript:void(0);" rel="<?php echo $result['cli_id']; ?>">+</a></td>
					<td align="center">Editar - Excluir</td>
				</tr>
				<tr class="tr_detalhes" id="tr_detalhes_<?php echo $result['cli_id']; //cada linha terá um id diferente. ?>">
				    <td colspan="5">
    				    <div id="cliente_contatos" style="width: 240px; float: left;">
    					  Telefone: <?php echo $result['cont_telefone_fixo']; ?> <br />
    					  Celular: <?php echo $result['cont_telefone_fixo']; ?> <br />
    					  E-mail: <?php echo $result['cont_email1']; ?>
    					</div>
    					<div id="cliente_endereco" style="width: 340px; float: left;">
    					  <?php echo $result['end_logradouro']; ?>, <?php echo $result['end_numero']; ?> - <?php echo $result['end_complemento']; ?> - <?php echo $result['end_bairro']; ?><br />
    					  <?php echo $result['end_cidade']; ?> - <?php echo $result['end_uf']; ?> - <?php echo "CEP:".$result['end_complemento']; ?> 
    					</div>
					</td>
				</tr>
				<?php } ?>				
			</table>
		</div>
		<footer>
			<p>Copyright 2014 - MKT Reports - All Rights Reserved</p>
		</footer>
		
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
			    $('.tr_detalhes').hide();			    
    			   $('.link_detalhes').click(function(){    			          			        
    			         var iddetalhes = $(this).attr('rel');
    			         $('#tr_detalhes_'+iddetalhes).toggle();
    			         });
			});
		</script>		
	</body>
</html>
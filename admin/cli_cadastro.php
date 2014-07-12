<?php 
include '../config.php';
include '../function.php';
?>

<?php
$validado = true;
//valida se post existe
if (isset($_POST['postado'])) {
    $validado = true;
    $erro = 'Tem erro no preenchimento, favor verificar!<br>';    

//dados do cliente POST
$tipo_cliente = trim($_POST['tipo_cliente']);
$nome = strtoupper(trim($_POST['nome']));
$razao_social = strtoupper(trim($_POST['razao_social']));
$estado_civil = trim($_POST['estado_civil']);
$cpf = trim($_POST['cpf']);
$cnpj = trim($_POST['cnpj']);	

//dados de contato
$cont_telefone_fixo = trim($_POST['cont_telefone_fixo']);
$cont_telefone_celular1 = trim($_POST['cont_telefone_celular1']);
$cont_telefone_celular2 = trim($_POST['cont_telefone_celular2']);
$cont_email1 = strtolower(trim($_POST['cont_email1']));
$cont_email2 = strtolower(trim($_POST['cont_email2']));	

//endereço cliente
$end_logradouro = strtoupper(trim($_POST['end_logradouro']));
$end_numero = trim($_POST['end_numero']);
$end_bairro = strtoupper(trim($_POST['end_bairro']));
$end_complemento = strtoupper(trim($_POST['end_complemento']));
$end_cidade = strtoupper(trim($_POST['end_cidade']));
$end_uf = trim($_POST['end_uf']);
$end_cep = trim($_POST['end_cep']);


//Validações campos fo form
if (!$tipo_cliente) {
	$validado = FALSE;
    $erro .= "- Selecionar o Tipo de Cliente!<br>";
    
}
    
//Pessoal Fisica
if ($tipo_cliente == 1){
    //Valida Nome
    if (!preg_match('/^[A-Z a-z çÇ]+$/', $nome)) {
            $validado = FALSE;
            $erro .= "- Nome só aceita letras, não permite acentos e abreviações!<br>";
    }
    //Valida CPF
    if (!validaCPF($cpf)) {
            $validado = FALSE;
            $erro .= "- CPF Inválido.<br>";
    }
    }else {
            $nome = "";
            $cpf = "";
            $estado_civil = "";
    }

//Pessoal Juiridca
if ($tipo_cliente == 2){
    //Razão Social Valida
    if (!preg_match('/^.{5,}$/', $razao_social)) {
            $validado = FALSE;
            $erro .= "- O nome da Razão deve ter pelo menos 5 caracteres!<br>";
    }
    //CNPJ Valida
    if (!validarCNPJ($cnpj)) {
            $validado = FALSE;
            $erro .= "- CNPJ Inválido.<br>";
    } 
    }else {
            $razao_social = "";
            $cnpj = "";
    }

//Validando telefone
if (!$cont_telefone_fixo && !$cont_telefone_celular1 && !$cont_telefone_celular2) {
	$validado = FALSE;
    $erro .= "- Ao menos um telefone deve ser informado!<br>";
}

//Email 1
if (!validaEmail($cont_email1)) {
    $validado = FALSE;
	$erro .= "- E-mail 1 Inválido.<br>";
}

//Email 2
if ($cont_email2 != "") {
    if (!validaEmail($cont_email2)) {
        $validado = FALSE;
        $erro .= "- E-mail 2 Inválido.<br>";
    }
}
//Endereço validação
    if (!preg_match('/^.{5,}$/', $end_logradouro)) {
            $validado = FALSE;
            $erro .= "- Obrigatório informar o endereço!<br>";
    }

    if ($validado) {
        
        //para retirar ou escapar dados do cliente. Ele evita o sqlinject
        $tipo_cliente = addslashes($tipo_cliente);
        $nome = addslashes($nome);
        $razao_social = addslashes($razao_social);
        $estado_civil = addslashes($estado_civil);
        $cpf = addslashes($cpf);
        $cnpj = addslashes($cnpj);
        
        //dados de contato
        $cont_telefone_fixo = addslashes($cont_telefone_fixo);
        $cont_telefone_celular1 = addslashes($cont_telefone_celular1);
        $cont_telefone_celular2 = addslashes($cont_telefone_celular2);
        $cont_email1 = addslashes($cont_email1);
        $cont_email2 = addslashes($cont_email2);       
        
        //endereço cliente - escapar dados evitando / e etc.. Evita o sql inject
        $end_logradouro = addslashes($end_logradouro);
        $end_numero = addslashes($end_numero);
        $end_bairro = addslashes($end_bairro);
        $end_complemento = addslashes($end_complemento);
        $end_cidade = addslashes($end_cidade);
        $end_uf = addslashes($end_uf);
        $end_cep = addslashes($end_cep);        
        
        //inserir dados do cliente.
        $query_cliente_fisica = "INSERT INTO tbl_clientes 
                                (cli_nome,
                                 cli_estado_civil, 
                                 cli_cpf, 
                                 cli_tipo) 
                                 VALUES 
                                 ('$nome', 
                                 $estado_civil, 
                                 '$cpf', 
                                 $tipo_cliente)";
		$query_cliente_juridica = "INSERT INTO tbl_clientes 
                                (cli_nome,                                  
                                 cli_tipo, 
                                 cli_cnpj) 
                                 VALUES 
                                 ('$razao_social',                                 
                                 $tipo_cliente, 
                                 '$cnpj' )";
		if ($tipo_cliente == 1){
			$query = $query_cliente_fisica;
		}else{
			$query = $query_cliente_juridica;
		}

        $ret_cliente = mysql_query($query, $conn) or die (mysql_error());
        //está pegando o id desta conexão.
        $cliente_id = mysql_insert_id($conn);
        
        //inserir contato do cliente
        $query_contato = "INSERT INTO tbl_cliente_contato 
                                (cont_cli_id, 
                                cont_telefone_fixo, 
                                cont_telefone_celular1, 
                                cont_telefone_celular2, 
                                cont_email1, 
                                cont_email2) 
                                VALUES 
                                ($cliente_id, 
                                '$cont_telefone_fixo', 
                                '$cont_telefone_celular1', 
                                '$cont_telefone_celular2', 
                                '$cont_email1', 
                                '$cont_email2' )";
         $ret_contato = mysql_query($query_contato, $conn) or die (mysql_error());
        
        //inserir dados de endereço do cliente.
        $query_endereco = "INSERT INTO tbl_cliente_endereco
                                    (end_cliente_id,
                                    end_logradouro,
                                    end_numero,
                                    end_bairro,
                                    end_complemento,
                                    end_cidade,
                                    end_uf,
                                    end_cep)
                                    VALUES
                                    ($cliente_id,
                                    '$end_logradouro',
                                    '$end_numero',
                                    '$end_bairro',
                                    '$end_complemento',
                                    '$end_cidade',
                                    '$end_uf',
                                    '$end_cep')";
        $ret_endereco = mysql_query($query_endereco, $conn) or die (mysql_error());
        
        header("Location: cli_listagem.php?msg=Cadastro realizado com sucesso!");        
    }

}//Se post existe
?>
<!DOCTYPE html>
<html lang="pt-BR">
	
	<head>
		<title>iMasters PHP and jQuery | Cadastro Clientes</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" type="text/css" href="../style.css">		
		<script src="../jquery/jquery-1.11.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../jquery/jquery.maskedinput-1.3.1.js" type="text/javascript" charset="utf-8"></script>
	</head>
	
	<body>
		<header>
			<h1>Crud - iMasters PHP and jQuery</h1>
			<h3>Cadastro de Clientes</h3>
		</header>
		<div id="status"><?php if (!$validado){echo $erro; }?></div>
		<div id="content">
			<form method="post" action="" id="form_cli_cadastro">			
                <input type="hidden" name="postado" value="1" id="postado"/>
                
        		<fieldset id="fieldsetForm">				    
				<legend>Dados Cliente</legend>                    
                       				
					<label id="label_tipo_cliente">Tipo de Cliente:</label><br />
					<select name="tipo_cliente" id="tipo_cliente">
						<option value=""></option>
						<?php foreach ($arrayTipoCliente as $key => $label) { ?>
						<option value="<?php echo $key; ?>" <?php if (isset($tipo_cliente)){if ($tipo_cliente == $key){ ?> selected="selected" <?php }} ?>> <?php echo $label; ?> </option>
						<?php } ?>
					</select><br />
					
					<div id="dadosFisica">
					    
						<label id="label_nome">Nome:</label><br/>
						<input type="text" name="nome" value="<?php if (isset($nome)) {echo $nome;} ?>" id="nome" autofocus=""/><br />
						
						<label id="label_estado_civil">Estado Civil:</label><br />
						<select id="estado_civil" name="estado_civil">
							<option value=""></option>
							<?php foreach ($estadoCivil as $id => $label) { ?>
							<option value="<?php echo $id; ?>" <?php if (isset($estado_civil)){ if ($estado_civil == $id){ ?> selected="selected" <?php }} ?>><?php echo $label; ?></option>
							<?php } ?>
						</select><br />
						
						<label id="label_cpf">CPF:</label><br />
						<input type="text" name="cpf" value="<?php echo $cpf; ?>" id="cpf"/><br />
					</div>
					
					<div id="dadosJuridica">
					    
						<label id="label_razao_social">Razão Social:</label><br/>
						<input type="text" name="razao_social" value="<?php if (isset($razao_social)){ echo $razao_social; } ?>" id="razao_social" autofocus=""/><br />
						
						<label id="label_cnpj">CNPJ:</label><br />
						<input type="text" name="cnpj" value="<?php echo $cnpj; ?>"  id="cnpj"/><br />
					</div>
				</fieldset>
				
				<fieldset id="fieldsetForm">
					<legend>Contatos Cliente</legend>
					
					<label id="label_cont_telefone_fixo">Telefone Fixo:</label><br/>
					<input type="text" name="cont_telefone_fixo" value="<?php echo $cont_telefone_fixo; ?>" id="cont_telefone_fixo"/><br />
					
					<label id="label_cont_telefone_celular1">Telefone Celular 1:</label><br/>
					<input type="text" name="cont_telefone_celular1" value="<?php echo $cont_telefone_celular1; ?>" id="cont_telefone_celular1"/><br />
					
					<label id="label_cont_telefone_celular2">Telefone Celular 2:</label><br/>
					<input type="text" name="cont_telefone_celular2" value="<?php echo $cont_telefone_celular2; ?>" id="cont_telefone_celular2"/><br />
					
					<label id="label_cont_email1">E-mail 1:</label><br/>
					<input type="text" name="cont_email1" value="<?php if (isset($cont_email1)) { echo $cont_email1; }?>" id="cont_email1"/><br />
					
					<label id="label_cont_email2">E-mail 2:</label><br/>
					<input type="text" name="cont_email2" value="<?php if (isset($cont_email2)) { echo $cont_email2; }?>" id="cont_email2"/><br />
				</fieldset>
				
				<fieldset id="fieldsetForm">
					<legend>Endereço Cliente</legend>
					<label id="label_end_logradouro">Logradouro:</label><br/>
					<input type="text" name="end_logradouro" value="<?php if (isset($end_logradouro)) { echo $end_logradouro; }?>" id="end_logradouro"/>
					
					<label id="label_end_numero">Nº:</label>
					<input type="text" name="end_numero" value="<?php if (isset($end_numero)) { echo $end_numero; }?>" id="end_numero"/><br />
					
					<label id="label_end_bairro">Bairro:</label><br/>
					<input type="text" name="end_bairro" value="<?php if (isset($end_bairro)) { echo $end_bairro; }?>" id="end_bairro"/>
					
					<label id="label_end_complemento">Complemento:</label>
					<input type="text" name="end_complemento" value="<?php if (isset($end_complemento)) { echo $end_complemento; }?>" id="end_complemento"/><br />
					
					<label id="label_end_cidade">Cidade:</label><br/>
					<input type="text" name="end_cidade" value="<?php if (isset($end_cidade)){echo $end_cidade;} ?>" id="end_cidade"/>
					
					<label id="label_end_uf">Uf:</label>
					<select id="end_uf" name="end_uf">
						<option value=""></option>
						<?php foreach ($arrayUfs as $key => $values){ ?>
						<option value="<?php echo $key; ?>" <?php if (isset($end_uf)){ if ($end_uf == $key){ ?> selected = "selected" <?php }}  ?> ><?php echo $values; ?></option>
						<?php } ?>
					</select><br />
					
					<label id="label_end_cep">CEP:</label><br/>
					<input type="text" name="end_cep" value="<?php if (isset($end_cep)){echo $end_cep;} ?>" id="end_cep"/>				
				</fieldset>
				
					<input type="submit" name="btn_cadastrar" id="btn_cadastrar" value="Cadastrar" />
					<input type="submit" name="btn_atualizar" id="btn_atualizar" value="Atualizar" />
					<button name="btn_cancelar" id="btn_cancelar">Cancelar</button>
					
			</form>
			
		</div>
		<footer>
			<p>Copyright 2014 - MKT Reports - All Rights Reserved</p>
		</footer>
		<script type="text/javascript" charset="utf-8">
			
			$(document).ready (function(){			    
				$('#cnpj').mask('99.999.999/9999-99');
				$('#cpf').mask('999.999.999-99');
				$('#end_cep').mask('99999-999');				
				
				//mascara de entrada para celulares com 9 digitos.		
				$("#cont_telefone_fixo").mask("(99) 9999-9999?9",{placeholder:" "}).focus(function() {
				    $(this).keyup(function() {
				        numeros = $(this).val().replace(/\D/g, '');
        				      if(numeros.length == 11) { $(this).mask("(99) 99999-9999",{placeholder:" "}); }
        				            if(numeros.length == 10) { $(this).mask("(99) 9999-9999?9",{placeholder:" "}); }
                        });
                });
                
                $("#cont_telefone_celular1").mask("(99) 9999-9999?9",{placeholder:" "}).focus(function() {
                    $(this).keyup(function() {
                        numeros = $(this).val().replace(/\D/g, '');
                              if(numeros.length == 11) { $(this).mask("(99) 99999-9999",{placeholder:" "}); }
                                    if(numeros.length == 10) { $(this).mask("(99) 9999-9999?9",{placeholder:" "}); }
                        });
                });
                
                $("#cont_telefone_celular2").mask("(99) 9999-9999?9",{placeholder:" "}).focus(function() {
                    $(this).keyup(function() {
                        numeros = $(this).val().replace(/\D/g, '');
                              if(numeros.length == 11) { $(this).mask("(99) 99999-9999",{placeholder:" "}); }
                                    if(numeros.length == 10) { $(this).mask("(99) 9999-9999?9",{placeholder:" "}); }
                        });
                });
                
                				
				$('#tipo_cliente').change(function(){
					$('#dadosFisica').hide();
					$('#dadosJuridica').hide();					
					
					var tipoCliente = this.value;
					
					if (tipoCliente == 1) {
						$('#dadosFisica').show();
					} else if (tipoCliente == 2){
						$('#dadosJuridica').show();
					}
				});
				$('#tipo_cliente').change();
			});
			
		</script>
				
	</body>
</html>
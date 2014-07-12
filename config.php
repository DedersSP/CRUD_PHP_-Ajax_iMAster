<?php 
header('Content-Type: text/html; charset=utf-8');

//Definindo conexão com o banco de dados
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$bd = 'imaster_php';
$conn = mysql_connect($servidor, $usuario, $senha) or die(mysql_error());
$database = mysql_select_db($bd) or die(mysql_error($conn));

mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');


//Definindo o título do site
$site_titulo = "Meu site PHP - iMaster";

//Array Estado Civil
$estadoCivil = array(1 => 'Solteiro', 2 => 'Casado');

//Array Ufs
$arrayUfs = array(
'AM' => 'Amazonas',
'AC' => 'Acre',
'AL' => 'Alagoas',
'AP' => 'Amapá',
'CE' => 'Ceará',
'DF' => 'Distrito federal',
'ES' => 'Espirito santo',
'MA' => 'Maranhão',
'PR' => 'Paraná',
'PE' => 'Pernambuco',
'PI' => 'Piauí',
'RN' => 'Rio grande do norte',
'RS' => 'Rio grande do sul',
'RO' => 'Rondônia',
'RR' => 'Roraima',
'SC' => 'Santa catarina',
'SE' => 'Sergipe',
'TO' => 'Tocantins',
'PA' => 'Pará',
'BH' => 'Bahia',
'GO' => 'Goiás',
'MT' => 'Mato grosso',
'MS' => 'Mato grosso do sul',
'RJ' => 'Rio de janeiro',
'SP' => 'São paulo',
'RS' => 'Rio grande do sul',
'MG' => 'Minas gerais',
'PB' => 'Paraiba');

//aray tipo de cliente
$arrayTipoCliente = array(1 => 'Pessoa Fisica' ,2 => 'Pessoa Juridica');

//$queryInsert = "INSERT INTO tbl_clientes (cli_nome, cli_estado_civil, cli_rg) VALUE ('Andre Batista', '2', '27.391.103-X')";
//$rsVal = mysql_query($queryInsert) or die(mysql_error($conn));
//echo mysql_insert_id();

/*$queryUpdate = "UPDATE tbl_clientes SET 
cli_nome = 'Isabella Batista', 
cli_estado_civil = '1', 
cli_rg = '123.123.123-9' 
WHERE cli_id = 5";
$rsUpdate = mysql_query($queryUpdate) or die(mysql_error($conn));*/

//$selectDB = mysql_query("SELECT * FROM tbl_clientes");
//print_r(mysql_fetch_object($selectDB));

/*	echo "<table border=1>";
	echo "<tr><th>Nome</th>";
	echo "<th>RG</th></tr>";		
	
while ($result = mysql_fetch_assoc($selectDB)){
		
	echo "<tr><td>".$result["cli_nome"]."</td>";	
	echo "<td>".$result["cli_rg"]."</td></tr>";
	
}
	echo "</table>";*/
	
?>
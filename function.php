<?php 

//Valido Nome
function validaNome ($value) {
	if (preg_match("([a-zA-Z])", $value)) {
	    return TRUE;
	}else {
		return FALSE;
	}    
}


//validando e-mail com expressão regulares PHP
function validaEmail($value) {
	if (preg_match("/^[a-zA-Z0-9._%+-]+@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/", $value)) {
		return TRUE;
	}else{
		return FALSE;
	}
}

// Função que valida o CPF
function validaCPF($cpf){
	// Verifiva se o número digitado contém todos os digitos
    $cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
	
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')
	{
	return false;
    }
	else
	{   // Calcula os números para verificar se o CPF é verdadeiro
		// 21947362801
        for ($t = 9; $t < 11; $t++) { //9
            	
            for ($d = 0, $c = 0; $c < $t; $c++) { //0
                $d += $cpf{$c} * (($t + 1) - $c);  //20+9+72
            }
 
            $d = ((10 * $d) % 11) % 10;
 
            if ($cpf{$c} != $d) {
                return false;
            }
        }
 
        return true;
    }
}

//função valida cnpj
function validarCNPJ($cnpj){
  $cnpj = str_pad(str_replace(array('.','-','/','_'),'',$cnpj),14,'0',STR_PAD_LEFT);
  if (strlen($cnpj) != 14 || $cnpj == '00000000000000'){
    return false;
  }else{
    for($t = 12; $t < 14; $t++){
      for($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++){
        $d += $cnpj{$c} * $p;
        $p  = ($p < 3) ? 9 : --$p;
      }
      $d = ((10 * $d) % 11) % 10;
      
      if($cnpj{$c} != $d){
        return false;
      }
    }
    return true;
  }
}
?>
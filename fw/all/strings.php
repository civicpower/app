<?php
function sans_accents($string){
	return str_replace(
		array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'),
		array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'),
		$string);
}
function to_html($str){
	$str = str_replace("&lt;","<",$str);
	$str = str_replace("&gt;",">",$str);
	return $str;
}
function for_html($str){
	if(is_resource($str)){
		return '***RESOURCE***';
	}elseif(is_object($str)){
		return '***OBJECT***';
	}else{
        $str = htmlentities(my_concat($str), ENT_QUOTES, 'UTF-8');
        return $str;
	}
}
function in_guill($str){
	return str_replace('"','&#34;',$str);
}
function for_input($txt){
	$res=$txt;
	$res = str_replace(
		array("\\'","'","\"","\\\""),
		array("&#39;","&#39;","&#34;","&#34;"),
		$res
	);
	return $res;
}
function from_textarea($str){
	$str=str_replace("\n","<br />",$str);
	return $str;
}

function my_concat($arr,$separator='',$format='%s',$ifnotnull=false){
	$txt_return='';
    if(is_array($arr) || is_object($arr)){
		foreach ($arr as $key => $value) {
	        if (is_array($value) || is_object($value)){
	        	$txt_return.=my_concat($value,$separator,$format,$ifnotnull);
	        }else{
	        	if(($ifnotnull && strlen($value)>0) || !$ifnotnull){
			        $txt_return.=sprintf($format,$value).$separator;
			    }
		    }
	    }
	}else{
    	if(($ifnotnull && strlen($arr)>0) || !$ifnotnull){
			$txt_return.=sprintf($format,$arr).$separator;
		}
	}
    return $txt_return;
}
function rewriting( $texte,  $sep_mots, $max_caracteres=900 ){
   $car_speciaux = array( 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'È', 'É', 'Ê', 'Ë', 'è', 'é', 'ê', 'ë', 'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'Ù', 'Ú', 'Û', 'Ü', 'ù', 'ú', 'û', 'ü', 'ß', 'Ç', 'ç', 'Ð', 'ð', 'Ñ', 'ñ', 'Þ', 'þ', 'Ý', 'ÿ', 'Ÿ' );
   $car_normaux  = array( 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e', 'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i', 'O', 'O', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'o', 'o', 'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u', 'B', 'C', 'c', 'D', 'd', 'N', 'n', 'P', 'p', 'Y', 'Y', 'Y' );
   $texte = str_replace($car_speciaux, $car_normaux, $texte);
   $texte = preg_replace( "/[^A-Za-z0-9]+/", $sep_mots, $texte );
   $texte = trim( $texte, $sep_mots );
   $texte = substr( $texte, 0, $max_caracteres );
   $texte = trim( $texte, $sep_mots );
   $texte = strtolower( $texte );
   return $texte;
}
function simplize($txt){
	$txt=rewriting($txt, "-", 150);
	return $txt;
}
?>
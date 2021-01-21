<?php
function get_absolute_path($path) {
    $path = str_replace(array('/', '\\'), '/', $path);
    $parts = array_filter(explode('/', $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) continue;
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }
    return implode('/', $absolutes);
}
function curdir(){
	if(isset($GLOBALS['override_curdir']) && strlen($GLOBALS['override_curdir']) > 0){
		return $GLOBALS['override_curdir'];
	}
	return dirname($_SERVER['SCRIPT_FILENAME']);
}
function fileroot($f){
	return str_replace($_ENV['REP_ROOT'],'',$f);
}
function webdir($f=''){
	$f = fileroot($f);
	//$f = get_absolute_path($f);
	if(is_file($_SERVER['SCRIPT_NAME'])){
		$file=dirname($_SERVER['SCRIPT_NAME']);
	}else{
		$file=$_SERVER['SCRIPT_NAME'];
	}
	$uu = dirname(str_replace($_ENV['REP_ROOT'],'' ,$file));
	$uu=$_ENV['HTTP_MODE'].'://' . $_SERVER['SERVER_NAME'] . $uu;
	return file_match($uu,$f);
}
function file_match($debut,$fin){
	$tab1=explode('/',$debut);
	$tab2=explode('/',$fin);
	if(array_pop($tab1) == $tab2[1]){
		$res = dirname($debut).$fin;
	}else{
		// In case of symbolic link
		if ( isset($_ENV['PROJECT_FILE_GET_CONTENTS']) && ($_ENV['PROJECT_FILE_GET_CONTENTS']==TRUE) ) {
			$res = $debut.str_replace(trim($_SERVER['DOCUMENT_ROOT'], '/'),"",$fin);		
			$res 	 = explode('//',$res);
			$res_new = "";
			foreach ($res as $value) {
				if($value==$_ENV['HTTP_MODE'].":") {
					# :/ because HTTP_MODE do not have ":" and / will be duplicated in the other condition
					$res_new = $_ENV['HTTP_MODE'].":/";
				}
				else {
					$res_new .= "/".$value;
				}
			}
			$res = $res_new;
			unset($res_new,$value);
		}
		else {
			$res = $debut.(preg_match('~/$~',$debut)?preg_replace('~^/~','',$fin):$fin);
		}
	}
	return $res;
}
function rediriger_vers($file,$method=null){
	if( (is_null($method) && headers_sent($file_num,$line_num)) || $method==1 ){
		global $fw;
		$fw->add_onload('window.location="'.str_replace('"','',$file).'";');
		//flush();
	}elseif(is_null($method) || $method==2){
		header("Location: " . $file);
	}
}
function icone($name,$options=''){
	$alt='';
	if(!eregi('alt=',$options)){
		$alt='alt="'.in_guill($name).'"';
	}
	return '<img '.$alt.' class="bo_icon" src="'.in_guill(webdir('/images/icones/'.$name.'.gif')).'" '.$options.' />';
}
?>
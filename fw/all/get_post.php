<?php
function something_exists($array,$what=null){
	if(!is_null($what) && is_array($what)){
		$res = true;
		if(count($what)>0){
			foreach($what as $k=>$v){
				$res &= something_exists($array,$v);
			}
		}else{
			$res=false;
		}
		return $res;
	}else{
		$res=false;
		if(isset($array)){
			if(is_array($array)){
				if(!is_null($what)){
					if(count($array)>0){
						if(isset($array[$what])){
							if(is_array($array[$what])){
								$res=count($array[$what])>0;
							}else{
								$res=strlen($array[$what])>0;
							}
						}
					}
				}else{
					if(count($array)>0){
						$res=true;
					}
				}
			}
		}
		return $res;
	}
}
function get_exists(){
	$tab=func_get_args();
	return something_exists($_GET,$tab);
}
function post_exists(){
	$tab=func_get_args();
	return something_exists($_POST,$tab);
}
function request_exists(){
	$tab=func_get_args();
	return something_exists($_REQUEST,$tab);
}
function session_exists(){
	$tab=func_get_args();
	return something_exists($_SESSION,$tab);
}
function files_exists(){
	$tab=func_get_args();
	return something_exists($_FILES,$tab);
}
function cookie_exists(){
	$tab=func_get_args();
	return something_exists($_COOKIE,$tab);
}
function all_exists($what){
	return
		something_exists($_GET,$what)
		|| something_exists($_POST,$what)
		|| something_exists($_REQUEST,$what)
		|| something_exists($_FILES,$what)
		|| something_exists($_SESSION,$what)
		|| something_exists($_COOKIE,$what)
	;
}
function gsomething($array,$what=null){
	$res=null;
	if(something_exists($array,$what)){
		if(is_null($what)){
			$res = $array;
		}else{
			$res = $array[$what];
		}
	}
	return $res;
}
function gall($what=null){
	$res=gget($what);if(is_null($res)){
	$res=gpost($what);if(is_null($res)){
	$res=grequest($what);if(is_null($res)){
	$res=gfiles($what);if(is_null($res)){
	$res=gsession($what);if(is_null($res)){
	$res=gcookie($what);
	}}}}}
	return $res;
}
function gget($what=null){
	return gsomething($_GET,$what);
}
function gpost($what=null){
	return gsomething($_POST,$what);
}
function grequest($what=null){
	return gsomething($_REQUEST,$what);
}
function gfiles($what=null){
	return gsomething($_FILES,$what);
}
function gfile($what=null){
	return gsomething($_FILES,$what);
}
function gcookie($what=null){
	return gsomething($_COOKIE,$what);
}
function gsession($what=null){
	return gsomething($_SESSION,$what);
}
?>
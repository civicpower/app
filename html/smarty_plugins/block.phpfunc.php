<?php

function smarty_block_phpfunc($params, $content, &$smarty, $open){
    if(!$open){
	if(isset($params['function'])){
	    if(function_exists($params['function'])){
		$function = $params['function'];
		$content = $function($content);
	    }
	}
	return $content;
    }
}

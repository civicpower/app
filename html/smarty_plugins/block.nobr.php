<?php

function smarty_block_nobr($params, $content, &$smarty, $open){
	if(!$open){
		$content = preg_replace("~<br[^>]+>~","",$content);
		return $content;
	}
}

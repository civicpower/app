<?php

function smarty_block_trim($params, $content, &$smarty, $open){
	if(!$open){
		return trim($content);
	}
}

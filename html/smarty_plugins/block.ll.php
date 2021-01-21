<?php

function smarty_block_ll($params, $content, &$smarty, $open){
	if(!$open){
		$init = '';
		if(defined('INITLINK_SMARTY') && strlen(INITLINK_SMARTY)>0){
			$init = INITLINK_SMARTY;
		}
		$href=$content;
		$href=simplize($href);
		return '<a href="'.$init.'/'.$href.'">' . trim($content) . '</a>';
	}
}

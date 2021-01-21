<?php

function smarty_block_seo_upper($params, $content, &$smarty, $open){
    if(!$open){
		$content = strtoupper($content);
		return $content;
    }
}


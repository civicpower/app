<?php

function smarty_block_seo_title($params, $content, &$smarty, $open){
    if(!$open){
		$content = trim($content);
		$content = explode("\n",$content);
		$res = array();
		$i = 0;
		foreach($content as $k => $v){
		    $v = trim($v);
		    if(preg_match("~^[\-]+$~",$v)){
				$i++;
		    }else{
				$res[$i][] = $v;
		    }
		}
		foreach($res as $k => $v){
		    shuffle($v);
		    $res[$k] = array_shift($v);
		}
		$seo_title = implode(' - ',$res);
		$smarty->seo_title = $seo_title;
		$smarty->assign('seo_title',$seo_title);
    }
}


<?php

function smarty_block_seo_refresh($params, $content, &$smarty, $open){
	if(!$open){
		$res = 0;
		$content = strtolower(trim($content));
		if(is_numeric($content)){
			$res = $content;
		}else{
			$content = explode(' ',$content);
			if(count($content) == 2){
				$val1 = array_shift($content);
				$val2 = array_shift($content);
				if(is_numeric($val1)){
					$multiples = array(
						'seconde' => 1,
						'second' => 1,
						'seconds' => 1,
						'sec' => 1,
						's' => 1,
						'min' => 60,
						'minute' => 60,
						'minutes' => 60,
						'm' => 60,
						'hour' => 60*60,
						'hours' => 60*60,
						'h' => 60*60,
						'heure' => 60*60,
						'heures' => 60*60,
						'jour' => 60*60*24,
						'jours' => 60*60*24,
						'day' => 60*60*24,
						'days' => 60*60*24,
						'month' => 60*60*24*30,
						'months' => 60*60*24*30,
						'mois' => 60*60*24*30,
						'annee' => 60*60*24*365,
						'annees' => 60*60*24*365,
						'year' => 60*60*24*365,
						'years' => 60*60*24*365,
					);
					if(isset($multiples[$val2]) && is_numeric($multiples[$val2])){
						$val2 = $multiples[$val2];
						$res = $val1 * $val2;
					}
				}
			}
		}
		$smarty->seo_refresh_seconds = $res;
	}
}

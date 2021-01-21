<?php
// Load all modules in all rep
	module('all');
// Module loader
	function module($filename,$rep = null){
		if(is_null($rep)){
			$rep = dirname(__FILE__);
		}
		$f = $rep . '/' . $filename;
		$error=false;
		if(file_exists($f)){
			if(is_file($f)){
				if(preg_match("~\.php$~",$filename)){
					require_once($f);
				}
			}elseif(is_dir($f)){
				$dir = @opendir($f);
				$tab = array();
				while($file = @readdir($dir)){
					if($file != '.' && $file != '..'){
						$d = $f . '/' . $file;
						if(file_exists($d)){
							$tab[] = $filename . '/' . $file;
						}
					}
				}
				sort($tab);
				foreach($tab as $mod){
					module($mod);
				}
			}else{
				$error=true;
			}
		}else{
			$error=true;
		}
		if($error){
			$str[]= 'Fichier:' . __FILE__ . '<br />';
			$str[]= 'Ligne:' . __LINE__ . '<br />';
			$str[]= 'filename:' . $filename . '<br />';
			$str[]= 'rep:' . $rep . '<br />';
			$str="\n" . implode('',$str);
			#echo $str;
			return $str;
		}
	}
?>
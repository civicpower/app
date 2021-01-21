<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('log_errors','1');
function ErrHandler($errno,$err_string='',$err_file,$err_line){
	global $mailerrorlog;
	$autorized=array();
	$autorized[]='^Undefined index:';
	if($errno == 8){
		$autorized[]='^Trying to get property';
	}
	if(is_array($autorized)){
		foreach($autorized as $v){
			if(preg_match("~$v~i",$err_string)){
				return false;
			}
		}
	}
	ob_end_clean();// Clear buffer
	switch ($errno) {
	    case E_USER_ERROR:
	    case E_ERROR:
			$type='ERROR';
	        break;
	    case E_USER_WARNING:
	    case E_WARNING:
			$type='WARNING';
	        break;
	    case E_USER_NOTICE:
	    case E_NOTICE:
			$type='NOTICE';
	        break;
	    default:
			$type='INCONNU';
	        break;
    }
	echo '<div style="font-family:verdana;font-size:10pt;background-color:#ffc7c7;color:#000000;border:3px solid #ff0000;padding:10px;">';
    echo $type.' ['.$errno.']'."<hr />";
	echo '<b>' . $err_string . '</b>'."<br />";
    echo 'LINE: <b>'.$err_line.'</b>'."<br />";
    echo 'FILE: <b>'.$err_file.'</b>'."<br />";
    echo '<div style="background-color:white;font-size:10pt;font-family:courier new;">'."\n";
	$str = explode("<br />",str_replace('<br />','</font><br /><font>',highlight_file($err_file,true)));
    array_unshift($str,'');
	$autour=3;
    $deb=$err_line-$autour;
    if($deb<1){
    	$deb=1;
    }
    $fin=$err_line+$autour;
    if($fin>count($str)){
    	$fin=count($str);
    }
    for($i=$deb;$i<=$fin;$i++){
		$bg='';
		if($err_line==$i){
	    	$bg='background-color:#D0D0D0;';
	    }
    	echo '<div style="padding:0;margin:0;'.$bg.'" title="'.$i.'">'.preg_replace("~\r~",'',$str[$i]).'</div>'."\n";
    }
    echo '</div>'."<br />";
    echo 'PHP_VERSION: '.PHP_VERSION."<br />";
    echo 'PHP_OS: '.PHP_OS."<br />";
    echo 'IP: '.$_SERVER['REMOTE_ADDR']."<br />";
    echo 'DATE: '.date("d/m/Y H:i:s")."<br />";
	echo '</div>';
	$trace = array_reverse(debug_backtrace());
	$echo = ob_get_clean();
	$show = false;
	if ( isset($_ENV['ERROR_SHOW_START']) && ($_ENV['ERROR_SHOW_START']>0) ) {
		if ( isset($_ENV['ERROR_SHOW_LENGTH']) && ($_ENV['ERROR_SHOW_LENGTH']>0) ) {
			$now = time();
			if($now>=$_ENV['ERROR_SHOW_START'] && $now<=$_ENV['ERROR_SHOW_START']+$_ENV['ERROR_SHOW_LENGTH']){
				$show = true;
			}
		}
	}
	if(!$show){
		if ( isset($_ENV['MAIL_ERROR_LOG']) && is_string($_ENV['MAIL_ERROR_LOG']>0) && strlen($_ENV['MAIL_ERROR_LOG']>0) ) {
			$entete = "MIME-Version: 1.0\r\n";
			$entete .= "Content-type: text/html; charset=UTF-8\r\n";
			mail($_ENV['MAIL_ERROR_LOG'],'[ERROR '.$_ENV['SITE_URL'].'] '.$err_string,$err_file."\n".$err_line."\n".$err_string."\n".$echo."\n".'<pre>'.print_r($trace,true).'</pre>',$entete);
		}
	}

	if($show){
		echo $echo;
	}else{
		echo 'Error '.time();
		# Where does the error came from?
		$trace   = debug_backtrace();
		$i = 0;
		foreach ($trace as $caller) {
			if (isset($caller['function'])) {
				error_log($i.") caller: ".$caller['function']);
			}
			$i--;
		}
		$caller  = $trace[0];
		error_log("********* data *********\n", 3, $_ENV['LOG_ERROR']);
        ob_start();                    // start buffer capture
        var_dump( $caller );           // dump the values
        $contents = ob_get_contents(); // put the buffer into a variable
        ob_end_clean();                // end capture
        error_log( $contents, 3, $_ENV['LOG_ERROR']); 
        error_log("********* /data *********\n", 3, $_ENV['LOG_ERROR']);
		
		error_log("err_file   -> ".$err_file."\n", 3, $_ENV['LOG_ERROR']);
		error_log("err_line   -> ".$err_line."\n", 3, $_ENV['LOG_ERROR']);
		error_log("err_string -> ".$err_string."\n", 3, $_ENV['LOG_ERROR']);
		error_log("errno      -> ".$errno."\n", 3, $_ENV['LOG_ERROR']);
		unset($i,$trace,$caller,$contents);
	}
	exit();
	return true;
}
set_error_handler('ErrHandler');
?>
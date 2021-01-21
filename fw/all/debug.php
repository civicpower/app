<?php

/* 
 * @access      -
 * @param       content & logger file
 * @author      C2
 * Purpose      debug
 */
function vardump($message="",$log=""){
return FALSE;
    if ($message<>"") {
        // Default
            if (!$log) { 
            	if ( isset($_ENV['LOG_ERROR']) && ($_ENV['LOG_ERROR']==TRUE) ) {
                    $log = $_ENV['LOG_ERROR'];
                }
                else { $log = '/var/log/php-errors.log'; }
            }
        // Echo DUMP
            error_log(strftime('%Y-%m-%d %I:%M %P', strtotime('now')));
            error_log("********* data *********\n", 3, $log);
            ob_start();                    // start buffer capture
            var_dump( $message );           // dump the values
            $contents = ob_get_contents(); // put the buffer into a variable
            ob_end_clean();                // end capture
            error_log( $contents, 3, $log); 
            error_log("********* /data *********\n", 3, $log);
    }
}

?>
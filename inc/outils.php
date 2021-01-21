<?php
if(true){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
if (file_exists(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../"."fw/outils.php"))) {
	// General tools
		require_once(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../"."fw/outils.php"));
		if (file_exists(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../"."inc/project.php"))) {
			// Project oriented tools
				require_once(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../"."inc/project.php"));
				if (file_exists(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../"."inc/project-civicpower.php"))) {
					// Main specific toolbox
						require_once(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../"."inc/project-civicpower.php"));
					// Global HTML lib
						if(!isset($no_fw) || !$no_fw){
							$fw=new htmlpage(true,false);
							// Facebook integration (WIP)
								if(defined('FB_API_KEY')){
								    $fw->set_fb_appid(FB_API_KEY);
							    }
						}
					return TRUE;
				}
		}
}
return FALSE;
?>
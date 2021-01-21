<?php
function project_css_js(&$fw){
	$fw->add_js( 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');
    $fw->css_tab[] = 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css';
    $fw->add_js('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js');
	$fw->add_css('project.css?j='.date('dmyH'));
	$fw->add_css('project-cp.css?j='.date('dmyH'));
	$fw->add_css('font-montserrat.css?j='.date('dmyH'));
	$fw->add_js('project.js?j='.date('dmyH'));
}
function project_file_get_contents($file){
	$file =	str_replace("//", "/",$_SERVER['DOCUMENT_ROOT'].	
				str_replace("//", "/",
					str_replace($_ENV['HTTP_MODE'].'://'.$_SERVER["SERVER_NAME"]
					,""
					,$file)
				)
			);
    return file_get_contents($file);
}
?>
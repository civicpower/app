<?php
/**
 * toolboxPublish
 * @access 				public
 * @author 				C2
 * purpose 				a collection of publication tools

	[function] 					[description]
	meta_property 				define meta property
	meta_schemaorg 				define schema.org model for the page
	doctype 					doctype tag
	html 						html tag
	head 						head tag
		title 					title tag
		meta_charset 				meta_charset tag
		meta_name 					meta_name tag
		dns_prefetch 				dns-prefetch tag
		preconnect 					preconnect tag
		meta_http_equiv 			meta_http_equiv tag
		manifest 					manifest tag
		script 						script tag
	body 						body tag
		noscript 					noscript tag
		favicon 					favicon tag
	minify_css 					minify css
	minify_js 					minify js
	minify_html 				minify html
	minify 						minify_html launcher
	addModelEl 					include a CSS or JS tag with potential preload
*/

# composer require matthiasmullie/minify
use MatthiasMullie\Minify;

	class toolboxPublish {

	    // Get the power
			public $civicpower;
		    function __construct() {
		        $this->civicpower = $GLOBALS['civicpower'];
		    }
	    
		/**
		 * Meta Schema.org
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		SEO
		 * @roadmap
		 		2019/07 	born
		*/
		public function meta_schemaorg ($tag="") {
			// Just in case
				$tag = strtolower($tag);
			// Various cases
				switch (true) {
					case ( (strpos($tag, 'review-aggregate')!== false) || (strpos($tag, 'rating')!== false) ):
						return '
						<div class="text-center"><div itemscope itemtype="http://data-vocabulary.org/Review-aggregate"><span itemprop="itemreviewed">Reassurez-moi.fr</span> est noté
			                                    <strong><span itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating"><span itemprop="average">9.5</span>/<span itemprop="best">10</span></span></strong>
			                                    selon <span itemprop="votes">768</span> avis clients
			                                    <span itemprop="reviewer"><a href="https://www.avis-verifies.com/avis-clients/reassurez-moi.fr" rel="nofollow noopener" target="_blank">Avis
			                                            Vérifiés</a></span></div></div>
			            ';
						break;
					
					default:
						# code...
						break;
				}			
		} // function meta_schemaorg


		/**
		 * Manifest
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		page type
		 * @roadmap
		 		2019/08 	born
		*/
		public function manifest () {
			return
			'<link rel="manifest" href="/manifest.json">';
		} // function manifest


		/**
		 * Charset
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		page type
		 * @roadmap
		 		2019/08 	born
		*/
		public function meta_http_equiv () {
			return
			'<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />';
		} // function meta_http_quiv


		/**
		 * Meta Title
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		SEO
		 * @roadmap
		 		2019/08 	born
		*/
		public function title ($metaPages_title="") {
			return '<title>'.$metaPages_title.'</title>';
		} // function title


		/**
		 * Meta Description
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		SEO
		 * @roadmap
		 		2019/08 	born
		*/
		public function meta_description ($metaPages_description="") {
			return '<meta name="description" content="'.$metaPages_description.'" />';
		} // function meta_description


		/**
		 * Meta Keywords
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		SEO
		 * @roadmap
		 		2019/08 	born
		*/
		public function meta_keywords ($metaPages_keywords="") {
			return '<meta name="keywords" content="'.$metaPages_keywords.'" />';
		} // function meta_keywords


		/**
		 * Charset
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		page type
		 * @roadmap
		 		2019/08 	born
		*/
		public function meta_charset () {
			return '<meta charset="utf-8">';
		} // function meta_charset


		/**
		 * Preconnect
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		optim
		 * @roadmap
		 		2019/08 	born
		 					$metaPages['noindex']
		*/
		public function meta_name ($module="") {
			// Init
				$return = "";
			// SEO exception
				if ( ($module=='annonce' and ( isset($_GET['ville']) or isset($_GET['vue'])) )
					or ( isset($metaPages['noindex']) && ($metaPages['noindex']<>"") )
				) {
					// Cette page risque d'être prise pour du duplicate-content alors on ne la référence pas
					$return .= "\n\t\t<meta name=\"robots\" content=\"noindex,follow\">\n";
				}
			// typeNavigateur not Mobile
				if(!empty($_SESSION['typeNavigateur']) && $_SESSION['typeNavigateur'] == 'mobile'){
					$return .= 
					'<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
				}

			// typeNavigateur not Mobile
				if(!empty($_SESSION['typeNavigateur']) && $_SESSION['typeNavigateur'] == 'mobile'){
					$return .= 
					'<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
				}

			// Ajax Secuity token
				if ( defined('CRSF_SECURITY') && (CRSF_SECURITY==true) ) {
					if ( isset($_SESSION['csrf_token']) && ($_SESSION['csrf_token']<>"") ) {
						$return .= '<meta name="csrf-token" content="'.$_SESSION['csrf_token'].'">';
					}
					else {
						// TODO ERROR
					}
				}				

			// Common tags
				$return .=
				'<meta name="apple-mobile-web-app-capable" content="yes" />	
				<meta name="msapplication-TileColor" content="#da532c">
				<meta name="msapplication-config" content="/images/icons/browserconfig.xml">
				<meta name="theme-color" content="#ffffff">
				<!-- mobile status bar color -->
					<meta name="theme-color" content="#ffffff">
					<meta name="msapplication-navbutton-color" content="#ffffff">
					<meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
				<meta name="google-site-verification" content="IMGTSqvPENM62miR6grl2UgXS37H3HGQXKzEg3ljpKQ" />
				';
			// Return
				return $return;
		} // function meta_name


		/**
		 * Preconnect
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		optim
		 * @roadmap
		 		2019/08 	born
		*/
		public function preconnect () {
			return
			'<link rel="preconnect" href="https://fonts.googleapis.com/" crossorigin />';
		} // function preconnect


		/**
		 * DNS Prefetch
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		optim
		 * @roadmap
		 		2019/08 	born
		*/
		public function dns_prefetch () {
			return
			"<link rel='dns-prefetch' href='//fonts.googleapis.com' />
			<link rel='dns-prefetch' href='//ajax.googleapis.com' />
			<link rel='dns-prefetch' href='//stackpath.bootstrapcdn.com' />
			<link rel='dns-prefetch' href='//cdnjs.cloudflare.com' />
			<link rel='dns-prefetch' href='//code.jquery.com' />
			<link rel='dns-prefetch' href='//s.w.org' />
			";
		} // function dns_prefetch

		/**
		 * Meta Property
		 * @access 			public
		 * @param 			
		 * @return 			meta property tag for a specific page
		 * @author 			C2
		 * @purpose 		SEO
		 * @roadmap
		 		2019/07 	born
		*/
		public function meta_property ($params=array()) {
			// Transporteur?
				if ( isset($params['charte']) && ($params['charte']=="charte-transporteur") ) {
					return '<meta property="og:image" content="//www.trouvetontransport.com/images/trouve-ton-transport-logo-facebook.jpg" />';
				}
				else {
					// Just in case
						if ( !isset($params['charte']) || ($params['page']=="") ) { $params['page'] = $_SERVER['REQUEST_URI']; }
					// Various cases
						switch (true) {
							case ( (strpos($params['page'], 'index.php')!== false) || (strpos($params['page'], '/')!== false) ):
								return
								'
								<meta property="og:locale" content="fr_FR" />
								<meta property="og:type" content="website" />
								<meta property="og:title" content="Réservez et commandez votre transport - Trouve ton transport" />
							    <meta property="og:url" content="https://www.trouvetontransport.com/" />
							    <meta property="og:image" content="/images/trouve-ton-transport-logo-facebook.jpg">
							    <meta property="og:description" content="Réservez et commandez votre transport en ligne en 1min. Paiement sécurisé, livraison assurée jusqu\'à 8000€. " />
								';
								break;
							default:
								return
								'
								<meta property="og:locale" content="fr_FR" />
								<meta property="og:type" content="website" />
								<meta property="og:title" content="Réservez et commandez votre transport - Trouve ton transport" />
							    <meta property="og:url" content="https://www.trouvetontransport.com/" />
							    <meta property="og:image" content="/images/trouve-ton-transport-logo-facebook.jpg">
							    <meta property="og:description" content="Réservez et commandez votre transport en ligne en 1min. Paiement sécurisé, livraison assurée jusqu\'à 8000€. " />
								';
								break;
						}
				}
		} // function meta_property


		/**
		* Include a CSS or JS
		* @category 	function
		* @package 		Siimple
		* @purpose 		include a CSS or JS
		* @param
			type 		: css/js
			name 		: what we want to include
			version 	: version of the item
			public_dir  : if we want to specify the directory of the file css/js
		* @doc
						https://addyosmani.com/blog/script-priorities/
						https://bitsofco.de/async-vs-defer/
						https://flaviocopes.com/javascript-async-defer/
		* @roadmap
			2019/01 	: css_preload && js_preload
				https://github.com/filamentgroup/loadCSS/blob/master/README.md
				&&
				https://pegasaas.com/how-to-defer-render-blocking-css/
				<link rel="preload" href="path/to/mystylesheet.css" as="style" onload="this.onload=null;this.rel='stylesheet'"><noscript><link rel="stylesheet" href="path/to/stylesheet.css"></noscript>
			2019/06 	:
				- parameters
					integrity
					crossorigin
					public_dir 	: to be set to have root (/js/model.js), by default will be SITE_PUBLIC_DIR aka /public/js/...
				- return FALSE if file do not exist
				- avoid same tag 2 times with $_SESSION['loaded']
		*/
		public function addModelEl ( $param=array() ) {

			// Grab param
				// js or css type preload, defer, asyn, etc.
					$type 			= (isset($param['type']) ? $param['type'] : "");
				// tag url or file
					$name 			= (isset($param['name']) ? $param['name'] : "");
				// ?version
					$version 		= (isset($param['version']) ? $param['version'] : "");
				// tag integrity=
					$integrity 		= (isset($param['integrity']) ? $param['integrity'] : "");
				// tag crossorigin
					$crossorigin 	= (isset($param['crossorigin']) ? $param['crossorigin'] : "");
				// if not / for example might be /public/
					$public_dir 	= (isset($param['public_dir']) ? $param['public_dir'] : "");
				// don't want the site url before the file name
					$no_url 		= (isset($param['no_url']) ? $param['no_url'] : "");
				// css media="only screen and (min-width: 960px) and (max-width: 1279px)"
					$media 			= (isset($param['media']) ? $param['media'] : "");

			// Welcome
				if (DEBUG>2) { error_log("Enter addModelEl"); }
				if (DEBUG>3) {
					error_log("   -> type: ".$type);
					error_log("   -> name: ".$name);
					error_log("   -> version: ".$version);
					error_log("   -> integrity: ".$integrity);
					error_log("   -> crossorigin: ".$crossorigin);
					error_log("   -> public_dir: ".$public_dir);
					error_log("   -> no_url: ".$no_url);
					error_log("   -> media: ".$media);
				}

			// Type : need to allow the type_array to avoid multiple load of the same tag
				if ($type<>"") {
					// Precise tag type
						switch (true) {
							case (  strpos(strtolower($type), 'js') !== false ):
								$type_array = "js";
								break;
							case (  strpos(strtolower($type), 'css') !== false ):
								$type_array = "css";
								break;
							default:
								// Debug
									if (DEBUG>3) { error_log("   -> unknown type"); }
								// Return Ko
									return FALSE;
								break;
						}
					// Scan if this tag is already there
						if ( isset($_SESSION['loaded']{$type_array}) && count($_SESSION['loaded']{$type_array})>0 ) {
							foreach ($_SESSION['loaded'] as $key => $value) {
								if ($value == $type_array."-".$name) {
									// Debug
										if (DEBUG>3) { error_log("   -> already loaded ".$name); }
									// Return Ko
										return FALSE;
								}
							}
						}
				}

			// url or local file?
				if(substr($name, 0, 4) == 'http') {
					$path_url =	$name;
				}
				else {
					// Path
						$path				=	!strstr($name,'/') ? 'css/'.$name.'.'.$type : $name;
					// Construct abs & url path
						$path_abs			=	str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$public_dir."/".$path);
						if ($no_url=="") {
							$path_url			=	SITE_URL.$path;
						}
						else {
							$path_url			=	$path;
						}
					// Debug
						if (DEBUG>3) {
							error_log("   -> path_abs: ".$path_abs);
							error_log("   -> path_url: ".$path_url);
						}
					// Version
						if(!empty($version)) {
							$version   		=	'?'.$version;
						}
					// File
						if (file_exists($path_abs)) {
							$version	=	'?'.date ("YmdHis", filemtime($path_abs));
						}
						else {
							// Debug
								error_log("   -> (!)addModelEl/can't open file: ".$path_abs." type: ".$type." name: ".$name);
							// Init Toolbox
						      if (!isset($toolbox)) { include_once(INC_DIR.'/classes/toolbox.php'); $toolbox = new toolbox(); }
						    // Log
								$trace = debug_backtrace();
								$toolbox->siimio_error_logs(
								json_encode(
								  array(
								     'event'        => (isset($trace[0]['file']) ? $trace[0]['file'] : "error")
								    ,'detail' 		=> "file: ".$path_abs." type: ".$type." name: ".$name  // text
								    ,'_SERVER'      => (isset($_SERVER) ? $_SERVER : "")
								  )
								)
								,(isset($trace[0]['file']) ? $trace[0]['file'] : "")
								);
							// Return
								return FALSE;
						}
				}

			// Kind of tag
				switch($type) {
					case 'css'	:
						// Debug
							if (DEBUG>3) { error_log("   -> case css"); }
						// Do
							return
								'<link rel="stylesheet" type="text/css" href="'.$path_url.$version.'"'
								.((isset($integrity)&&($integrity<>"")) ? ' integrity="'.$integrity.'"' : "")
								.((isset($crossorigin)&&($crossorigin<>"")) ? ' crossorigin="'.$crossorigin.'"' : "")
								.((isset($media)&&($media<>"")) ? ' media="'.$media.'"' : "")
								.' />';
						// Load
							array_push($_SESSION['loaded'], $type_array."-".$name);
						break;
					// <link rel="preload" href="/bucket/84d9c0e09170a3a0600bb982d010761f7706bc04/css/icons.css" as="style" onload="this.onload=null;this.rel='stylesheet'"> <noscript><link rel="stylesheet" href="/bucket/84d9c0e09170a3a0600bb982d010761f7706bc04/css/icons.css"></noscript>
					case 'css_preload'	:
						// Debug
							if (DEBUG>3) { error_log("   -> case css_preload"); }
						// Do
							return
								'<link rel="stylesheet" type="text/css" href="'.$path_url.$version.'"'
								.((isset($integrity)&&($integrity<>"")) ? ' integrity="'.$integrity.'"' : "")
								.((isset($crossorigin)&&($crossorigin<>"")) ? ' crossorigin="'.$crossorigin.'"' : "")
								.((isset($media)&&($media<>"")) ? ' media="'.$media.'"' : "")
								.' />'
								.'<link rel="preload" type="text/css" href="'.$path_url.$version.'"'
								.((isset($integrity)&&($integrity<>"")) ? ' integrity="'.$integrity.'"' : "")
								.((isset($crossorigin)&&($crossorigin<>"")) ? ' crossorigin="'.$crossorigin.'"' : "")
								.' as="style" onload="this.onload=null;this.rel=\'stylesheet\'">'
								.'<noscript><link rel="stylesheet" type="text/css" href="'.$path_url.$version.'"'
								.((isset($integrity)&&($integrity<>"")) ? ' integrity="'.$integrity.'"' : "")
								.((isset($crossorigin)&&($crossorigin<>"")) ? ' crossorigin="'.$crossorigin.'"' : "")
								.((isset($media)&&($media<>"")) ? ' media="'.$media.'"' : "")
								.'></noscript>';
						// Load
							array_push($_SESSION['loaded'], $type_array."-".$name);
						break;
					case 'js_preload'	:
						// Debug
							if (DEBUG>3) { error_log("   -> case js_preload"); }
						// Do
							//$inBlocks->hash{'js'}	.=	"\n\t".'<script src="'.$path_url.$version.'"></script>';
							return
								'<script src="'.$path_url.$version.'">'
								.((isset($integrity)&&($integrity<>"")) ? ' integrity="'.$integrity.'"' : "")
								.((isset($crossorigin)&&($crossorigin<>"")) ? ' crossorigin="'.$crossorigin.'"' : "")
								.'</script>'
								.'<link rel="preload" href="'.$path_url.$version.'" as="script">';
						// Load
							array_push($_SESSION['loaded'], $type_array."-".$name);
						break;
					case 'js'	:
						// Debug
							if (DEBUG>3) { error_log("   -> case js"); }
						// Do
							return 
								'<script src="'.$path_url.$version.'"'
								.' type="text/javascript"'
								.((isset($integrity)&&($integrity<>"")) ? ' integrity="'.$integrity.'"' : "")
								.((isset($crossorigin)&&($crossorigin<>"")) ? ' crossorigin="'.$crossorigin.'"' : "")
								.'>'
								.'</script>';
						// Load
							array_push($_SESSION['loaded'], $type_array."-".$name);
						break;
					case 'js_async'	:
						// Debug
							if (DEBUG>3) { error_log("   -> case js"); }
						// Do
							return 
								'<script async src="'.$path_url.$version.'"'
								.' type="text/javascript"'
								.((isset($integrity)&&($integrity<>"")) ? ' integrity="'.$integrity.'"' : "")
								.((isset($crossorigin)&&($crossorigin<>"")) ? ' crossorigin="'.$crossorigin.'"' : "")
								.'>'
								.'</script>';
						// Load
							array_push($_SESSION['loaded'], $type_array."-".$name);
						break;
					case 'js_defer'	:
						// Debug
							if (DEBUG>3) { error_log("   -> case js"); }
						// Do
							return 
								'<script defer src="'.$path_url.$version.'"'
								.' type="text/javascript"'
								.((isset($integrity)&&($integrity<>"")) ? ' integrity="'.$integrity.'"' : "")
								.((isset($crossorigin)&&($crossorigin<>"")) ? ' crossorigin="'.$crossorigin.'"' : "")
								.'>'
								.'</script>';
						// Load
							array_push($_SESSION['loaded'], $type_array."-".$name);
						break;
					case 'js_protect'	:
						// Debug
							if (DEBUG>3) { error_log("   -> case js_protect"); }
						// Do
							return
								'<script src="'
								.$path_url.$version.'"'
								.' type="text/javascript"'
								.((isset($integrity)&&($integrity<>"")) ? ' integrity="'.$integrity.'"' : "")
								.((isset($crossorigin)&&($crossorigin<>"")) ? ' crossorigin="'.$crossorigin.'"' : "")
								.'>'
								.'</script>';
						// Load
							array_push($_SESSION['loaded'], $type_array."-".$name);
		       			break;
				}

			// Return
				unset($type, $path_abs, $path_url, $integrity, $name, $version, $crossorigin, $public_dir);
		} // function addModelEl


		/**
		 * Minify JS
		 * @access 			public
		 * @param 			
		 * @return 			minified or not code
		 * @author 			C2
		 * @purpose 		good idea to minify JS
		 * @roadmap
		 		2019/07 	born from SIIMPLE
		 					arg_file = can return a result without echo if file is a param in entry
		 					arg_file might contain JS code is code!==""
		 * TO BE TESTED $file
		*/
		public function minify_js ($arg_file="", $code="") {

			// Init
				// Force yes/no JS with get argument
					$_GET['minifyjs'] = (isset($_GET['minifyjs']) ? $_GET['minifyjs'] : "");
				// File we accept
					// File we accept
					if ( ( defined('FILES_JS')&&(FILES_JS<>"") ) ) {
						$valid_files = FILES_JS;
					}
					else {
						if (isset($arg_file)&&($arg_file<>"") ) { echo $arg_file; }
						else { return(FALSE); }
					}
				// Flag
					$flag = 0;
			// Code or file/HTTP?
				if ($code=="") {
					// File is not defined
						if ($arg_file=="") {
							if (strpos($_SERVER['REQUEST_URI'], '.js')!== false) {
								// Define where is the file
									$file = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']);
								// case /dir/file.js?1563251224
									$file_tmp 	= explode('?', $file);
									if ( isset($file_tmp[1]) ) { $file = $file_tmp[0]; }
								// Debug
									if (DEBUG>3) { error_log("   -> Look at ".$file); }
							}
							else {
								// Debug
									if (DEBUG>3) { error_log("   -> Not a .js file: ".$_SERVER['REQUEST_URI']); }
								// Return Ko
									return FALSE;
							}
						}
					// File is an argument
						else { $file = $arg_file; }
					
					// Just in case
						if (!file_exists($file)) {
							// Debug
								if (DEBUG>3) { error_log("   -> (!)JS file do not exist: ".$file); }
							// Return Ko
								return FALSE;
						}

					// Find file name
						// Do
							$file_js = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
						// case file.js?1563251224
							$file_tmp_r 	= explode('?', $file_js);
							if ( isset($file_tmp_r[1]) ) { $file_js = $file_tmp_r[0]; }
						// Debug
							if (DEBUG>3) { error_log("      -> JS file is ".$file_js); }

					// JS file with relative path (/js/test.js) & avoid /js/test.js?123123
						$uri_tmp_r 	= explode('?', $_SERVER['REQUEST_URI']);
						if ( isset($uri_tmp_r[1]) ) { $uri_js = $uri_tmp_r[0]; }
						else { $uri_js = $_SERVER['REQUEST_URI']; }
				}

			// Platform ($_GET['minifyjs'] = 0 -> force NO / $_GET['minifyjs'] = 1 -> force Yes)
				if ( defined('MINIFY_JS') && (MINIFY_JS==true) || ($_GET['minifyjs']==1) ) {
					if ($_GET['minifyjs']!==0) {
						// Minify
							if ( ( isset($file) && ($file<>"") ) || ($code<>"") ) {
								// Minify!
									if ( ( defined('VENDOR_DIR') && (VENDOR_DIR<>"") ) && (file_exists(str_replace("//", "/", ($_SERVER['DOCUMENT_ROOT'].VENDOR_DIR.'/JavaScriptPacker/JavaScriptPacker.php')))) ) {
										// Load Lib
											require_once str_replace("//", "/", ($_SERVER['DOCUMENT_ROOT'].VENDOR_DIR.'/JavaScriptPacker/JavaScriptPacker.php'));
										// File
											if ($code=="") {
												// Do if file is in the white list
													foreach ($valid_files as $key => $value) {
														//error_log("   -> uri: ".$uri_js." whitelist: ".$value);
														//error_log("   -> file: ".$file);
														if ($uri_js==$value) {
															// Just in case
																if (file_exists($file)) {
																$nope = 0;
																if ($nope==1) {
																	// ORIGINAL CV version
																	// Encoding Dean Edwards JavaScript's Packer (https://github.com/meenie/javascript-packer/blob/master/class.JavaScriptPacker.php)
																		$myPacker 	= new JavaScriptPacker(file_get_contents($file), 62, true, false);
																		$packed 	= $myPacker->pack();
																		//$packed = \JShrink\Minifier::minify( file_get_contents($file) );
																}
																elseif ($nope==2) {
																	// https://github.com/tedious/JShrink/tree/master/src/JShrink
																	// 1 (not base62/Dean Edwards) -> WORKS with rAthus.js
																		$packed = \JShrink\Minifier::minify(file_get_contents($file), array('flaggedComments' => false));
																}
																elseif ($nope==3) {
																	// 2 (https://github.com/tholu/php-packer Dean Edwards)
																		$packer = new Tholu\Packer\Packer(file_get_contents($file), 'Normal', true, false, true);
																		$packed = $packer->pack();
																}
																elseif ($nope==4) {
																	// 3 Google https://developers.google.com/closure/compiler/docs/api-tutorial1 via https://github.com/dpup/php-closure/
																		require_once($_SERVER['DOCUMENT_ROOT'].VENDOR_DIR."/php-closure-master/php-closure.php");
																		$c = new PhpClosure();
																		$packed = $c->add( $file )
																		  //->add("popup.js")
																		  ->advancedMode()
																		  ->hideDebugInfo()
																		  //->verbose()
																		  ->useClosureLibrary()
																		  //->useCodeUrl(SITE_URL.'/js/')
																		  ->useCodeUrl('https://preview.trouvetontransport.me'.'/js/')
																		  ->cacheDir('') 	// no cachedir = echo
																		  ->write();
																}
																elseif ($nope==5) {
																	// 3bis
																		if (!defined('LIB_DIR')) {
																			define('LIB_DIR', str_replace("//", "/",$_SERVER['DOCUMENT_ROOT'].VENDOR_DIR."/php-closure-master2/lib/")
																			);
																		}
																		require_once(
																			str_replace("//", "/",
																				$_SERVER['DOCUMENT_ROOT'].VENDOR_DIR."/php-closure-master2/lib/third-party/php-closure.php")
																			);
																		$c = new PhpClosure();
																		$packed = $c->add( $file )
																		  //->addDir($_SERVER['DOCUMENT_ROOT'].'/js/') // new
																		  //->add('popup.js')
																		  //->add('popup.soy') // new
																		  ->advancedMode()
																		  ->cacheDir($_SERVER['DOCUMENT_ROOT'].'/tmp/js-cache/')
																		  ->localCompile() // new
																		  ->write();
																		$packed = (isset($packed['code']) ? $packed['code'] : "");
																}
																elseif ($nope==5) {
																	// http://searchturbine.com/php/phpwee
																	include_once( str_replace("//", "/",$_SERVER['DOCUMENT_ROOT'].VENDOR_DIR."/phpwee-php-minifier-master/src/JsMin/JsMin.php") );
																	error_log( str_replace("//", "/",$_SERVER['DOCUMENT_ROOT'].VENDOR_DIR."/phpwee-php-minifier-master/src/JsMin/JsMin.php") );
																	//$c = new JSMin();
																	$packed = JsMin::minify( file_get_contents($file) );
																}
																elseif ($nope==6) {
																	// https://github.com/matthiasmullie/minify (composer require matthiasmullie/minify)
																	$minifier = new Minify\JS();
																	$minifier->add( file_get_contents($file) );
																	$packed = $minifier->minify();
																	//$packed = $minifier->gzip();
																}
																else {
																	// apt-get install node-uglify
																		exec("uglifyjs ".$file." -o ".$_SERVER['DOCUMENT_ROOT']."/tmp/js-cache/".$file_js." -c -m --mangle-props", $output, $error);
																		//error_log("uglifyjs ".$file." -o ".$_SERVER['DOCUMENT_ROOT']."/tmp/js-cache/".$file_js." -c -m --mangle-props");
																		//error_log("********* DUMP/packed *********");
																		//ob_start(); var_dump( $packed[0] ); $contents = ob_get_contents(); ob_end_clean(); error_log( $contents ); 
																		//error_log("********* DUMP/error *********");
																		//ob_start(); var_dump( $error ); $contents = ob_get_contents(); ob_end_clean(); error_log( $contents ); 
																		$packed = implode("", $output);
																		if ($error) {

																			// TODO TRY BACKUP SOLUTION

																		}
																		else {
																			if ( (DEBUG>3) || ( defined('DEBUG_MINIFY') && (DEBUG_MINIFY<>"") ) ) { error_log("      -> (JS)uglifyjs minify OK: ".$file); }
																		}

																		//$packed = $packed[0];
																		//die;
																}

																	// Debug
																		if ( (DEBUG>3) || ( defined('DEBUG_MINIFY') && (DEBUG_MINIFY<>"") ) ) { error_log("      -> (JS)minify: ".$file); }
																	// Flag
																		$flag = 1;
																} // file_exists
															// Ko
																else {
																	// Flag
																		$flag = 3;
																}
															// Done
																break;
														} // if
													} // foreach
											} // if
										// Code
											else {
												// Do
													$myPacker 	= new JavaScriptPacker($arg_file, 62, true, false);
													$packed 	= $myPacker->pack();
												// Debug
													if ( (DEBUG>3) || ( defined('DEBUG_MINIFY') && (DEBUG_MINIFY<>"") ) ) { error_log("      -> (JS)minify-code"); }
												// Flag
													$flag = 2;
											}
									}
									else {
										// Read the file 
											if (file_exists($file)) {
												$packed = file_get_contents($file);
											}
											else {
												// Flag
													$flag = 3;
											}
										// Debug
											if (DEBUG>3) { error_log("      -> VENDOR_DIR to be defined or lib not there"); }
									}
								// Just in case
									if ( !isset($packed) || ($packed=="") ) {
										// Debug
											if ( (DEBUG>3) || ( defined('DEBUG_MINIFY') && (DEBUG_MINIFY<>"") ) ) {
												switch ($flag) {
													case 0: error_log("         -> JS file is not in white list");
														$packed = 	file_get_contents($file);
													break;
													case 1: error_log("         -> (!)JS Error with white listed file"); break;
														$packed = 	file_get_contents($file);
													case 2: error_log("         -> (!)JS Error with code"); 
														break;
													case 3: error_log("         -> (!)JS file do not exist"); 
														break;
													default: error_log("         -> (!)JS Error unknown"); break;
												}
											}
									}
							}
							else {
								// Debug
									if (DEBUG>3) { error_log("   -> (!)No JS file"); }
								// Return Ko
									return FALSE;
							}
					}
					else {
						// Do not minify
							$packed = file_get_contents($file);
						// Debug
							if (DEBUG>3) { error_log("      -> (JS)hard ignore: ".$file); }
					}
				}
				else {
					// Return original code
						if ($code<>"") {
							$packed = $arg_file;
						}
						else {
							// Do not minify
								$packed = file_get_contents($file);
						}
					// Debug
						if (DEBUG>3) { error_log("      -> (JS)minify flag off, ignore ".(isset($file) ? $file : " code")); }
				}
			// Free memory
				unset($file, $file_js, $file_tmp_r, $uri_tmp_r, $valid_files);
			// Echo or just return
				if ( isset($packed) && ($packed<>"") ) {
					if ($arg_file=="") {
						if (!headers_sent()) {
							header("Content-type: application/javascript");
						}
						echo $packed;
						return TRUE;
					}
					else { return $packed; }
				}
				else {
					// Debug
						if (DEBUG>3) { error_log("   -> (!)No js to return"); }
error_log("   -> (!)No js to return");
exit;
					// Return Ko
						return FALSE;
				}
		} // function minify_js


		/**
		 * Minify CSS
		 * @access 			public
		 * @param 			
		 * @return 			minified or not code
		 * @author 			C2
		 * @purpose 		good idea to minify CSS
		 * @roadmap
		 		2019/07 	born from SIIMPLE
		 					arg_file = can return a result without echo if file is a param in entry and not a HTTP hit seen by the framework
		 					arg_file might contain JS code is code!==""
		 		2019/07		$code aka it's not a file but directly code
		 * TO BE TESTED $file
		*/
		public function minify_css ($arg_file="", $code="") {

			// Init
				// Force yes/no CSS with get argument
					$_GET['minifycss'] = (isset($_GET['minifycss']) ? $_GET['minifycss'] : "");
				// File we accept
					if ( ( defined('FILES_CSS')&&(FILES_CSS<>"") ) ) {
						$valid_files = FILES_CSS;
					}
					else {
						if (isset($arg_file)&&($arg_file<>"") ) {
								echo $arg_file;
						}
						else { return(FALSE); }
					}
			// Code or file/HTTP?
				if ($code=="") {
					// File is not defined
						if ($arg_file=="") {
							if (strpos($GLOBALS['URL_FILENAME'], '.css')!== false) {
								// Define where is the file
									$file = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']);
								// case /dir/file.css?1563251224
									$file_tmp 	= explode('?', $file);
									if ( isset($file_tmp[1]) ) { $file = $file_tmp[0]; }
								// Debug
									if (DEBUG>3) { error_log("   -> Look at ".$file); }
								// Just in case
									if (!file_exists($file)) {
										// Debug
											if (DEBUG>3) { error_log("   -> File do not exist: ".$file); }
										// Return Ko
											return FALSE;
									}
							}
							else {
								// Debug
									if (DEBUG>3) { error_log("   -> Not a .css file: ".$_SERVER['REQUEST_URI']); }
								// Return Ko
									return FALSE;
							}
						}
					// File is an argument
						else { $file = $arg_file; }
					
					// Find file name
						// Do
							$file_css = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
						// case file.css?1563251224
							$file_tmp_r 	= explode('?', $file_css);
							if ( isset($file_tmp_r[1]) ) { $file_css = $file_tmp_r[0]; }
						// Debug
							if (DEBUG>3) { error_log("      -> CSS file is ".$file_css); }

					// CSS file with relative path (/css/test.css) & avoid /css/test.css?123123
						$uri_tmp_r 	= explode('?', $_SERVER['REQUEST_URI']);
						if ( isset($uri_tmp_r[1]) ) { $uri_css = $uri_tmp_r[0]; }
						else { $uri_css = $_SERVER['REQUEST_URI']; }
				}

			// Platform ($_GET['minifycss'] = 0 -> force NO / $_GET['minifycss'] = 1 -> force Yes)
				if ( defined('MINIFY_CSS') && (MINIFY_CSS==true) || ($_GET['minifycss']==1) ) {
					if ($_GET['minifycss']!==0) {
						// Minify
							if ( ( isset($file) && ($file<>"") ) || ($code<>"") ){
								// Do I minify?
										if ( ( defined('VENDOR_DIR') && (VENDOR_DIR<>"") ) && (file_exists(str_replace("//", "/", ($_SERVER['DOCUMENT_ROOT'].VENDOR_DIR.'/minit-min-master/Minify_CSS_Compressor/Compressor.php')))) ) {
											// Load Lib
												require_once str_replace("//", "/", ($_SERVER['DOCUMENT_ROOT'].VENDOR_DIR.'/minit-min-master/Minify_CSS_Compressor/Compressor.php'));
											
											// File
												if ($code=="") {
													foreach ($valid_files as $key => $value) {
														if ($uri_css==$value) {
															// Just in case
																if (file_exists($file)) {
																	// Do!
																		$css = Minify_CSS_Compressor::process( file_get_contents($file) );
																	// Debug
																		if ( (DEBUG>3) || ( defined('DEBUG_MINIFY') && (DEBUG_MINIFY<>"") ) ) { error_log("      -> (CSS)minify: ".$file); }
																} // file_exists
															// Ko
																else {
																	// Debug
																		if ( (DEBUG>3) || ( defined('DEBUG_MINIFY') && (DEBUG_MINIFY<>"") ) ) { error_log("      -> (!)CSS file do not exist: ".$file); }
																}
															// Done
																break;
														} // if
													} // foreach
												} // if
											// Code
												else {
													// Do
														$css = Minify_CSS_Compressor::process($arg_file);
													// Debug
														if ( (DEBUG>3) || ( defined('DEBUG_MINIFY') && (DEBUG_MINIFY<>"") ) ) { error_log("      -> (CSS)minify-code"); }
												}

										}
									// Result?
										if ( !isset($css) || ($css=="") ) {
											// Just read the file
												if ($code=="") {
													$css = file_get_contents($file);
												}
												else {
													// TODO (code in error)
													return FALSE;
												}
											// Debug
												if (DEBUG>3) { error_log("      -> (CSS)ignore file case ".(isset($file) ? " : ".$file : "")); }
										}
							}
							else {
								// Debug
									if (DEBUG>3) { error_log("   -> (!)No css file"); }
								// Return Ko
									return FALSE;
							}
					}
					else {
						// Minify
							$css = 	file_get_contents($file);
						// Debug
							if (DEBUG>3) { error_log("      -> (CSS)hard ignore: ".$file); }
					}
				}
				else {
					// Return original code
						if ($code<>"") {
							$css = $arg_file;
							//error_log("load original code css");
						}
						else {
							// Do not Minify
								$css = file_get_contents($file);
						}
					// Debug
						if (DEBUG>3) { error_log("      -> (CSS)ignore: ".(isset($file) ? $file : " code")); }
				}
			// Free memory
				unset($file, $file_css, $file_tmp_r, $uri_tmp_r, $valid_files);
			// Echo or just return
				if ( isset($css) && ($css<>"") ) {
					if ($arg_file=="") {
						if (!headers_sent()) {
							header("Content-type: text/css");
						}
						echo $css;
						exit;
					}
					else { return $css; }
				}
				else {
					// Debug
						if (DEBUG>3) { error_log("   -> (!)No css to return"); }
error_log("   -> (!)No css to return");
exit;
					// Return Ko
						return FALSE;
				}
		} // minify_css


		/**
		* HTML minify
		* @category   CategoryName
		* @package    PackageName
		* @author     -
		* @copyright  2018 SIIMPLE/ETICAR
		* @license    - private -
	  	* @link       https://www.colis-voiturage.fr
		* @see        -
		* @since      File available since Release 1.0
		* @purpose 	  minify HTML pages
		* @comment 	  not uglify but only minify
		* @return 	  html
		* @param 	  html
		* @examples
				2019/01/29 		: born
		*/
		public function minify_html ($input) {

			// Case empty
			    if(trim($input) === "") return $input;
		    // Remove extra white-space(s) between HTML attribute(s)
			    $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
			        return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
			    }, str_replace("\r", "", $input));
		    // Minify inline CSS declaration(s)
			    if(strpos($input, ' style=') !== false) {
			        $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
			            return '<' . $matches[1] . ' style=' . $matches[2] . $this->minify_css($matches[3], 1) . $matches[2];
			        }, $input);
			    }
			    if(strpos($input, '</style>') !== false) {
			      $input = preg_replace_callback('#<style(.*?)>(.*?)</style>#is', function($matches) {
			        return '<style' . $matches[1] .'>'. $this->minify_css($matches[2], 1) . '</style>';
			      }, $input);
			    }
			    if(strpos($input, '</script>') !== false) {
			      $input = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function($matches) {
			        return '<script' . $matches[1] .'>'. $this->minify_js($matches[2], 1) . '</script>';
			      }, $input);
			    }
			    unset($matches);

		    // Return result
			    return preg_replace(
			        array(
			            // t = text
			            // o = tag open
			            // c = tag close
			            // Keep important white-space(s) after self-closing HTML tag(s)
			            '#<(img|input)(>| .*?>)#s',
			            // Remove a line break and two or more white-space(s) between tag(s)
			            '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
			            '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
			            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
			            '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
			            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
			            '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
			            '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
			            '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
			            // Remove HTML comment(s) except IE comment(s)
			            '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
			        ),
			        array(
			            '<$1$2</$1>',
			            '$1$2$3',
			            '$1$2$3',
			            '$1$2$3$4$5',
			            '$1$2$3$4$5$6$7',
			            '$1$2$3',
			            '<$1$2',
			            '$1 ',
			            '$1',
			            ""
			        ),
			    $input);
		} // minify_html


		/**
		* Minify
		* @category   CategoryName
		* @package    PackageName
		* @author     -
		* @copyright  2018 SIIMPLE/ETICAR
		* @license    - private -
	  	* @link       https://www.colis-voiturage.fr
		* @see        -
		* @since      File available since Release 1.0
		* @purpose 	  main function called by TTT pages
		* @comment 	  not uglify but only minify
		* @return 	  html
		* @param 	  html
		* @examples
				2019/07 		: born
		*/
		public function minify ($contents="") {
			if ($contents) {
				if ( defined('MINIFY_HTML') && (MINIFY_HTML==true) ) {
					if ( defined('DEBUG') && (DEBUG==true) ) { error_log("   -> try minify"); }
					if ( ( $result = $this->minify_html($contents) ) !== NULL ) {
						if ( defined('DEBUG') && (DEBUG==true) ) { error_log("      -> minify SUCCESS"); }
						echo $result;
						return TRUE;
					}
					else {
						if ( defined('DEBUG') && (DEBUG==true) ) { error_log("      -> minify FAILED"); }
						echo ($contents);
						return FALSE;
					}
				}
				else {
					if ( defined('DEBUG') && (DEBUG==true) ) { error_log("      -> minify IGNORE"); }
					echo ($contents);
					return TRUE;
				}
			}
			else {
				// TODO GAME OVER
			}
		} // minify_html


		/**
		 * API call
		 * @access 			public
		 * @param 			
		 					security key
		 					url
		 					verb
		 * @return 			data
		 * @author 			C2
		 * @purpose 		WP SEO call API
		 * @roadmap
		 		2020/11 	born
		*/
		public function call_civicpower_api ($params=array('')) {

			// We need those parameters
				$must_have = array('protocol','verb','apikey','host');
				foreach ($must_have as $value) {
					if ( !isset($params[$value]) || ($params[$value]=="") ) { return FALSE; }
				}
				if ( (strtolower($params['protocol'])<>"http") && (strtolower($params['protocol'])<>"https") ) { return FALSE; }
				if (!isset($params['method'])||($params['method']=="")) {
					$params['method']=="post";
				}

	        // URL type https://api-preview.siim.io/api/v1/drivers?external_driver_id=39658
	          $url = $params['protocol']
		          ."://"
		          .str_replace("//", "/", $params['host'].$params['path'])
		          .$params['verb'];

	        // Curl call
	          // Configure
	             // create curl resource
	                  $ch = curl_init();
	             // configure httpheader
	                  $headers = array(
	                       "content-type: application/json; charset=utf-8"
	                      ,'api-key'.": ".$params['apikey']
	                      ,'user-ip'.": ".$this->civicpower->toolboxLog->get_ip()
	                      ,'user-session'.": ".$this->civicpower->toolboxLog->get_usersession()
	                      ,'user-referer'.": ".$this->civicpower->toolboxLog->get_userreferer()
	                      ,'user-agent'.": ".$this->civicpower->toolboxLog->get_useragent()
	                      //,$session_compte
	                      ,( (isset($_SESSION['utilisateur'])&&($_SESSION['utilisateur'])<>"")
	                      	? 'session-utilisateur'.": ".$this->civicpower->toolboxLog->get_usersession().": ".json_encode($_SESSION)
	                      	: ""
	                      )
	                      ,( (isset($_COOKIE)&&($_COOKIE)<>"")
	                      	? 'cookie-utilisateur'.": ".json_encode($_COOKIE)
	                      	: ""
	                      )
	                  );
	                  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	                  curl_setopt($ch, CURLOPT_HEADER, 0);
	             // configure
	                  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	                  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	             // configure method (https://www.php.net/manual/en/function.curl-setopt.php)
                    switch (true) {
                      case ( strpos(strtoupper($params['method']), 'GET')!== false ):
                        // Set method
                          curl_setopt($ch, CURLOPT_HTTPGET, true);
                        // Configure data
                          $url .= '?' . http_build_query($params['data']);
                        // Configure url
                          curl_setopt($ch, CURLOPT_URL, $url);
                        break;
                      case ( strpos(strtoupper($params['method']), 'PUT')!== false ):
                        // Set method
                          curl_setopt($ch, CURLOPT_PUT, true);
                        // Configure url
                          curl_setopt($ch, CURLOPT_URL, $url);
                        break;
                      case ( strpos(strtoupper($params['method']), 'PATCH')!== false ):
                        // Set method
                          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                        // Configure url
                          curl_setopt($ch, CURLOPT_URL, $url);
                          // Test JSON
                          $json = json_encode( (isset($params['data']) ? $params['data'] : ""), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                          json_decode($json);
                          if (!json_last_error() === JSON_ERROR_NONE) {
                            error_log("   -> ".$caller['function']."/".json_encode(array('error' => 400,'message' => 'Broken JSON')));
                            return FALSE;
                          }
                        // Configure data
                           curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                        break;
                      //case ( strpos(strtoupper($params['method']), 'POST')!== false ):
                      default:
                        // Set method POST
                          curl_setopt($ch, CURLOPT_POST, true);
                        // Test JSON
                          $json = json_encode( (isset($params['data']) ? $params['data'] : ""), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                          json_decode($json);
                          if (!json_last_error() === JSON_ERROR_NONE) {
                            error_log("   -> ".$caller['function']."/".json_encode(array('error' => 400,'message' => 'Broken JSON')));
                            return FALSE;
                          }
                        // Configure data
                          curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                        // Configure url
                          curl_setopt($ch, CURLOPT_URL, $url);
                        break;
                    }

	        	// Execute call
					$output = curl_exec($ch);
					if ($GLOBALS['DEBUG']>3) {
						error_log("********* call_civicpower_api/output/DUMP *********");
						ob_start(); var_dump( $output ); $contents = ob_get_contents(); ob_end_clean(); error_log( $contents );
					}
					if (curl_getinfo($ch)["http_code"]<>"200") {
						$this->civicpower->toolbox->debugMailer(
							array('variables' 	=> get_defined_vars()
								 ,'subject' 	=> "Can't reach curl call: ".curl_getinfo($ch)["http_code"]
								 ,'message' 	=> "Request: ".$url
									."<BR>for: ".serialize(( (isset($params['data']) ? $params['data'] : "")))
									."<BR>"."from url: ".$GLOBALS['URL']
							) 
						);
					}

	       		// Close curl resource to free up system resources
	            	curl_close($ch);

	          	// Return
	              $output = json_decode($output, true);
	              if (!json_last_error() === JSON_ERROR_NONE) {
	                error_log("   -> ".$caller['function']."/".json_encode(array('error' => 400,'message' => 'Broken output JSON')));
	                return FALSE;
	              }
	              if ($GLOBALS['DEBUG']>3) {
	                error_log("********* output/json_decode/DUMP *********");
	                ob_start(); var_dump( $output ); $contents = ob_get_contents(); ob_end_clean(); error_log( $contents );
	              }

	          // Error? return_type = 0 = no error
	              if ( isset($output['return_type']) && ($output['return_type']<>0)) {
	                  if ($GLOBALS['DEBUG']>3) {
	                    error_log("return_type: ".$output['return_type']);
	                    error_log("return_code: ".$output['return_code']);
	                    error_log("return_message: ".$output['return_message']);
	                    error_log("return_content: ".$output['return_content']);
	                  }
	                  return FALSE;
	              }
	              else { return $output; }
		}


		/**
		 * Wrapper call for BC APIs
		 * @access 			public
		 * @param 			verb
		 					params
		 * @return 			result
		 * @author 			C2
		 * @purpose 		Call API for BC in DB datas
		 * @roadmap
		 		2020/12 	born
		*/
		public function call_civicpower_api_bc ($data="") {
			if ( 
				(defined('API_KEY')) && (API_KEY<>"") 
				&&
				(defined('API_URL')) && (API_URL<>"") 
			) {
			    if ( isset($data['verb'])&&($data['verb']<>"") ) {
			        if ( isset($data['params'])&&($data['params']<>"") ) {           
			            return $this->call_civicpower_api(
			                array(
			                    'protocol' 		=> "https"
			                   ,'method' 		=> "post"
			                   ,'host' 			=> API_URL
			                   ,'path' 			=> '/PHAPI_1.0/JSON/'
			                   ,'verb'          => $data['verb']
			                   ,'apikey'  		=> ( ( defined('API_KEY')&&(API_KEY<>"") ) ? API_KEY : false)
			                   ,'data'        	=> $data['params']
			                )
			            );
			        }
			    }
			}
		}

	} // class toolboxPublish

?>
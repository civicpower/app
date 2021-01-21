<?php
class toolboxDatabase {
/**
 * Colllection of tools for DB access
 * @access public
 * @author 				C2
 * @roadmap
 		2019/08 		adaptation from CV to TTT
 							DB_HOST, DB_USER, DB_PASSWD, DB_DATABASE
 							toolboxDatabaseModel -> toolboxDatabase
 * purpose 				a collection of tools for ttt's security

	[function] 						[description]
	dbConnect() 					initial DB connection
	dbSwitch() 						change DB
	dbHash() 						select data dans return the first result in an associative array
	dbHash_information_schema()		create dbHash_information_schema from dbHash
									to protect dbHash against SQL injection
	dbAll()							select data dans return all the results in an associative array
	dbMulti() 						multiple SQL instructions queries
	dbArray()						select data dans return the first result in an array
	dbDo() 							execute a query
	dbOneValue() & dbOne()			execute a query & return first result
	getAffectedRows() 				list of affected rows
	dbGetLastInsId() 				get last id updated
	dbDisconnect() 					disconnect DB
	cleanSqlStr()					clean query
	escapeStr() 					remove ESC in query


	TTT
		get_user_devis				get list of devis received by the user

*/

	public $civicpower;
    public function __construct() {
        $this->civicpower = $GLOBALS['civicpower'];
        if(!$this->dbConnect(array('HOST'        => MYSQL_HOST
                ,'LOGIN'        => MYSQL_LOGIN
                ,'PASSWORD'     => MYSQL_PASS
                ,'DATABASE'     => MYSQL_BASE)))
			die('Mysql connexion error');
		else
		{
			$this->dbDo('SET NAMES utf8');
		}
    }

	// allow toolboxDatabase::getInstance()->game_over( instead of $civicpower->toolboxDatabase
	// https://stackoverflow.com/questions/1449362/origin-explanation-of-classgetinstance
	public static $_instance;
    public static function getInstance() {
        if ( !(self::$_instance instanceof self) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	/**
	* dbConnect
	* @category 	function
	* @package 		Cols-voiturage
	* @author 		phosphore && C2
	* @purpose 		DB connection
	* @param 		
		cfg 		array of configuration
		type 		mysqli (default) or PDO
	* @usage
			$civicpower->toolboxDatabaseModel->dbDo($query)
	*/
	public function	dbConnect(
		$cfg = array('HOST' => ''
			 ,'LOGIN' 	 	=> ''
			 ,'PASSWORD' 	=> ''
			 ,'DATABASE' 	=> ''
		)
		,$type = ""
	) {

		// if (!isset($toolbox)) {include_once str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/inc/fw/module/toolbox.php');$toolbox = new toolbox();}
		// error_log($toolbox->vardump($cfg));

		if (strtolower($type)=="pdo") {
			try {
			    $civicpower = new PDO("mysql:host=".DB_HOST.";dbname=".DB_DATABASE.";charset=utf8", DB_USER, DB_PASSWD);
			    $civicpower->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $civicpower->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			}
			catch(Exception $e)
			{
	            ini_set('error_log', LOG_ERROR_SQL);
	            error_log("   -> can't sqlObj/connect "
            		.$e->getMessage())
						.(defined('DB_HOST') ? 'DB_HOST: '.DB_HOST : "'DB_HOST' error ")
                    .(defined('DB_USER') ? 'DB_USER: '.DB_USER : "'DB_USER' error ")
                    .(defined('DB_PASSWD') ? 'DB_PASSWD: '.DB_PASSWD : "'DB_PASSWD' error ")
                    .(defined('DB_DATABASE') ? 'DB_DATABASE: '.DB_DATABASE : "'DB_DATABASE' error ");
			    return FALSE;
			}
			$civicpower->query('SET CHARACTER SET utf8');
			return TRUE;
		}
		else {
			//if ($civicpower = mysqli_connect( $cfg{'HOST'}, $cfg{'LOGIN'},  $cfg{'PASSWORD'},$cfg{'DATABASE'}) ) {
			if (civicpower::$link = mysqli_connect( $cfg{'HOST'}, $cfg{'LOGIN'},  $cfg{'PASSWORD'},$cfg{'DATABASE'}) ) {
				mysqli_set_charset(civicpower::$link, 'utf8');
				//return $civicpower;
				return civicpower::$link;
			}
			else {
				return FALSE;
			}
		}
	}

	/**
	* dbRotate
	* @category 	function
	* @package 		Civicpower
	* @author 		phosphore && C2
	* @purpose 		DB rotate
	* @param 		
		query 		query itself
		base 		base we want to replicate
		target 		base newly created
	* @usage
			$civicpower->toolboxDatabaseModel->dbDo($query)
	*/
	public function	dbRotate($arguments=array('')) {

		// Just in case
			if ( (!isset($arguments['base'])) || ($arguments['base']=="") ) { return FALSE; }
			if ( (!isset($arguments['table_origin'])) || ($arguments['table_origin']=="") ) { return FALSE; }
			if ( (!isset($arguments['table_target'])) || ($arguments['table_target']=="") ) { return FALSE; }

		// Try Database
            if (!$this->dbHash_information_schema("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$arguments['base']."'")) { 	
                $query = "CREATE DATABASE IF NOT EXISTS ".$arguments['base'].";";
                if (!is_null($this->dbDo($query))) { return FALSE; }
            }
        // Try Table
            if ($this->dbHash_information_schema("SELECT table_name FROM information_schema. tables WHERE table_schema = '".$arguments['base']."' AND table_name = '".$arguments['table_origin']."';")==0) {
                if ($arguments['table_origin']){
	    			if ($file = file_get_contents(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT'].ASSET_SQL.$arguments['table_origin'].".sql"), 'r')) {
	    				//$this->dbMulti("START TRANSACTION;");
						if ($this->dbMulti($file, $arguments['base'], "commit") == FALSE) {
							return FALSE;
						}
	    			}
		        }
            }
        // Try Table rotated
            $query  = "SELECT count(*) as count
                       FROM information_schema.TABLES
                       WHERE (TABLE_SCHEMA = '".$arguments['base']."') AND (TABLE_NAME = '".$arguments['table_target']."')";
            $result = $this->dbHash_information_schema($query);
            if ($result['count']==0) {
                $query = "CREATE TABLE ".$arguments['base'].".".$arguments['table_target']." LIKE ".$arguments['base'].".".$arguments['table_origin'];
                if (!is_null($this->dbDo($query))) {
                    return FALSE;
                }
            }
	        return $arguments['table_target'];
	}


	/**
	* dbCreateOntheFly
	* @category 	function
	* @package 		TTT
	* @author 		C2
	* @purpose 		Create the DB & table if don't exist
	* @param 		db && table we target
	* @roadmap
		2019/09 	create
	*/
	public function	dbCreateOntheFly($params="") {

		// Just in case
			if ( !isset($params['database']) || ($params['database']=="") || !isset($params['table']) || ($params['table']=="") || !isset($params['model']) || ($params['model']=="") ) { return FALSE; }

		// Global ok?
        	if (!defined('BASE_LOG')) { define('BASE_LOG', 'serveurtpsttt-logs'); }

		// Debug
			if ($debug>3) { error_log("   -> Enter dbCreateOntheFly"); }

		// Do we have the database?
            $query  = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$params['database']."'";
			$result = $this->dbHash_information_schema($query);
            if (!$result) {
                $query = "CREATE DATABASE IF NOT EXISTS ".$params['database'].";";
                if (!is_null($this->civicpower->toolboxDatabaseModel->dbDo($query))) {
                    // Gameover
						$this->civicpower->toolbox->game_over(
							array(
								 'app'   => 'TTT/dbCreateOntheFly'
								,'title' => "database creation fail"
								,'data'  => json_encode($data)
								,'class' => get_class($this)
							)
						);
                    // Return ko
                        return FALSE;
                }
            }

        // Do we have the table?
            $query  = "SELECT count(*) as count
                       FROM information_schema.TABLES
                       WHERE (TABLE_SCHEMA = '".$params['database']."') AND (TABLE_NAME = '".$params['table']."')";
            if (!$this->civicpower->toolboxDatabaseModel->dbHash_information_schema($query)) {
                $query = "CREATE TABLE ".$params['database'].".".$params['table']." LIKE ".BASE_LOG.".".$params['model'];
                        ;
                if (!is_null($result = $this->civicpower->toolboxDatabaseModel->dbDo($query))) {
                    // Gameover
						$this->civicpower->toolbox->game_over(
							array(
								 'app'   => 'TTT/dbCreateOntheFly'
								,'title' => "table creation fail"
								,'data'  => json_encode($data)
								,'class' => get_class($this)
							)
						);
                    // Return ko
                        return FALSE;
                }
            }

        // Return ok
            return TRUE;
	} // dbCreateOntheFly
	
	
	/**
	* dbHash
	* @category 	function
	* @package 		Cols-voiturage
	* @author 		phosphore & C2
	* @purpose 		DB send an array
	* @param 		$query 	->	requête
					renvoi le résultat sous forme de tableau associatif
	* @roadmap
		2018/12 	log_security
		2020/12 	silent = no error
	*/
	public function dbHash($query="", $silent="") {

		// Security
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=111111111111111111111111111%22%20UNION%20SELECT%201,CONCAT(CHAR(100,100,100),CHAR(91,88,93),GROUP_CONCAT(t.email,CHAR(9),t.password),CHAR(91,88,88,93)),3,4%20FROM%20(SELECT%20email,password%20FROM%20colisvoiturageco.`users`%20WHERE%20`email`%20LIKE%20CHAR(37,64,37)%20LIMIT%2095270,5)t%20%20limit%200,1%20--%20/*%20order%20by%20%22as
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=UNION SELECT 1,CONCAT(CHAR(100,100,100),CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),COLUMN_NAME,CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,78,65,77,69,93),TABLE_NAME,CHAR(91,84,65,66,76,69,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93),TABLE_SCHEMA,CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93)),3,4 FROM information_schema.COLUMNS WHERE `COLUMN_NAME` LIKE CHAR(37,109,97,105,108,37) AND ( `DATA_TYPE`=CHAR(99,104,97,114) OR `DATA_TYPE`=CHAR(118,97,114,99,104,97,114) OR `DATA_TYPE`=CHAR(116,101,120,116))  limit 30,1 -- /* order by "as
			if ( strrpos(strtoupper($query), "INFORMATION_SCHEMA") ) {
				// Log
					//$this->toolboxLogModel->log_security($this, $query);
				// Return ko
					return FALSE;
			}
			else {
				// Get the request in debug buffer
					$this->error{'Requete'}[]	=	'dbHash : '.$query.$this->debugContext();
				// Query Error?
					if(!($requete = mysqli_query (civicpower::$link, $query))) {
						// Do we echo error?
							if (!$silent) {
								// Echo
									error_log("##################   [dbHash] MYSQL ERROR   ################");
						            error_log("dbHash error:".$query);
						            //error_log("dbHash error:".mysqli_error($civicpower).' - '.mysqli_errno($civicpower));
						            error_log("dbHash error:".mysqli_error(civicpower::$link).' - '.mysqli_errno(civicpower::$link));
						            error_log("############################################################");
								// Mail Admin
									//$this->makeMailDb( mysqli_errno($civicpower), mysqli_error($civicpower), $query);
								// Return
									//error_log("dbHash FALSE");
							}
						// Error
							return false;
					}
					else { return mysqli_fetch_array($requete,MYSQLI_ASSOC); }
			}
	} // function dbHash


	/**
	* dbHash
	* @category 	function
	* @package 		Cols-voiturage
	* @author 		C2
	* @purpose 		Specific call with INFORMATION_SCHEMA acceptance.
					Idea is to avoid this kind of call for all other requests for security reasons. 
	* @param 		$query 	->	requête
					renvoi le résultat sous forme de tableau associatif
	* @roadmap
		2018/12 	create dbHash_information_schema from dbHash
					to protect dbHash against SQL injection
	*/
	public function dbHash_information_schema($query="") {
		// Init
			if ($query=="") { return FALSE; }
		// Get the request in debug buffer
			$this->error{'Requete'}[]	=	'dbHash : '.$query.$this->debugContext();
		// Query Error?
			if(!($requete = mysqli_query (civicpower::$link, $query))) {
				// Echo
					error_log("##################   [dbHash] MYSQL ERROR   ################");
		            error_log("dbHash error:".$query);
		            error_log("dbHash error:".mysqli_error(civicpower::$link).' - '.mysqli_errno(civicpower::$link));
		            error_log("############################################################");
				// Mail Admin
					//$this->makeMailDb( mysqli_errno($civicpower), mysqli_error($civicpower), $query);
				// Return
					//error_log("dbHash FALSE");
					return false;
			}
			else {  return mysqli_fetch_array($requete,MYSQLI_ASSOC); }
	} // function dbHash_information_schema
	
	
	/**
	* dbAll
	* @category 	function
	* @package 		Cols-voiturage
	* @author 		phosphore & C2
	* @purpose 		DB select multi-dimension
	* @param 		$query 	->	requête
					$type: 	-> MYSQL_ASSOC <= tableau associatif, MYSQL_NUM <= avec index num, et la valeur par défaut est MYSQL_BOTH <= assoc et numérique
					renvoi chaque ligne de résultat sous forme de tableau associatif
	* @roadmap
		2018/12 	log_security
		2020/12 	return
	*/
	public function dbAll($query="",$type = MYSQLI_ASSOC, $return="") {
		// Just in case
			if ($query=="") { return FALSE; }
		// Init
			$tab = array();

		// Security
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=111111111111111111111111111%22%20UNION%20SELECT%201,CONCAT(CHAR(100,100,100),CHAR(91,88,93),GROUP_CONCAT(t.email,CHAR(9),t.password),CHAR(91,88,88,93)),3,4%20FROM%20(SELECT%20email,password%20FROM%20colisvoiturageco.`users`%20WHERE%20`email`%20LIKE%20CHAR(37,64,37)%20LIMIT%2095270,5)t%20%20limit%200,1%20--%20/*%20order%20by%20%22as
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=UNION SELECT 1,CONCAT(CHAR(100,100,100),CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),COLUMN_NAME,CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,78,65,77,69,93),TABLE_NAME,CHAR(91,84,65,66,76,69,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93),TABLE_SCHEMA,CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93)),3,4 FROM information_schema.COLUMNS WHERE `COLUMN_NAME` LIKE CHAR(37,109,97,105,108,37) AND ( `DATA_TYPE`=CHAR(99,104,97,114) OR `DATA_TYPE`=CHAR(118,97,114,99,104,97,114) OR `DATA_TYPE`=CHAR(116,101,120,116))  limit 30,1 -- /* order by "as
			if ( strrpos(strtoupper($query), "INFORMATION_SCHEMA") ) {
				// Log
					//$this->toolboxLogModel->log_security($this, $query);
				// Return ko
					return FALSE;
			}
			else {
				// Get the request in debug buffer
					$this->error{'Requete'}[]	=	'dbAll : '.$query.$this->debugContext();
				// Query Error?
					//if(!($requete = mysqli_query ($civicpower, $query, MYSQLI_USE_RESULT))) {
					if(!($requete = mysqli_query (civicpower::$link, $query, MYSQLI_USE_RESULT))) {
						
						// Echo
							error_log("################    [dbAll] MYSQL ERROR    #################");
				            error_log("dbAll error:".$query);
				            ///error_log("dbAll error:".mysqli_error($civicpower).' - '.mysqli_errno($civicpower));
				            error_log("dbAll error:".mysqli_error(civicpower::$link).' - '.mysqli_errno(civicpower::$link));
				            error_log("############################################################");
						// Mail Admin
							//$this->makeMailDb( mysqli_errno($civicpower), mysqli_error($civicpower), $query);
				        // Return
				            if ($return) {
				            	return (
									array('error' => mysqli_error(civicpower::$link)
										 ,'errno' => mysqli_errno(civicpower::$link)
									)
				            	);
				            }
				            else {
				            	return false;
				            }
					}
					else {
						while ($data = mysqli_fetch_array($requete,$type)) {
							$tab[] = $data;
						}
						return $tab;
					}
			}
	}
	

	/**
	* dbMulti
	* @category 	function
	* @package 		ttt
	* @author 		C2
	* @purpose 		Multiple SQL request management
	* @param 		$query 		->	requete
					database 	->	select database I want (if applicable)
					commit 		->  commit & rollback management
	* @return 		
					Ok : TRUE or value in return of the requet
					Go : FALSE
	* @roadmap
		2018/12 	log_security
		2020/12 	database management, commit management
	*/
	public function dbMulti($query="", $database="", $commit=FALSE) {
		// Just in case
			if ($query=="") {
				//error_log("END ERROR NO QUERY");
				return FALSE;
			}
			if ($commit) {
				$query = "START TRANSACTION;".$query;
			}
		// Security
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=111111111111111111111111111%22%20UNION%20SELECT%201,CONCAT(CHAR(100,100,100),CHAR(91,88,93),GROUP_CONCAT(t.email,CHAR(9),t.password),CHAR(91,88,88,93)),3,4%20FROM%20(SELECT%20email,password%20FROM%20colisvoiturageco.`users`%20WHERE%20`email`%20LIKE%20CHAR(37,64,37)%20LIMIT%2095270,5)t%20%20limit%200,1%20--%20/*%20order%20by%20%22as
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=UNION SELECT 1,CONCAT(CHAR(100,100,100),CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),COLUMN_NAME,CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,78,65,77,69,93),TABLE_NAME,CHAR(91,84,65,66,76,69,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93),TABLE_SCHEMA,CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93)),3,4 FROM information_schema.COLUMNS WHERE `COLUMN_NAME` LIKE CHAR(37,109,97,105,108,37) AND ( `DATA_TYPE`=CHAR(99,104,97,114) OR `DATA_TYPE`=CHAR(118,97,114,99,104,97,114) OR `DATA_TYPE`=CHAR(116,101,120,116))  limit 30,1 -- /* order by "as
			if ( strrpos(strtoupper($query), "INFORMATION_SCHEMA") ) {
				// Log
					//$this->toolboxLogModel->log_security($this, $query);
				// Return ko
					//error_log("END ERROR INFORMATION_SCHEMA");
					return FALSE;
			}
			else {
				// Get the request in debug buffer
					$this->error{'Requete'}[] = 'dbMulti : '.$query.$this->debugContext();
				// Open stream
					$mysqli_error = 0;
					if (defined('DB_HOST')) {
						if ( !isset($database)||($database=="") ) { $database = DB_DATABASE; }
						$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWD, $database);
					}
					elseif (defined('MYSQL_HOST')) {
						if ( !isset($database)||($database=="") ) { $database = MYSQL_BASE; }
						$mysqli = new mysqli(MYSQL_HOST, MYSQL_LOGIN, MYSQL_PASS, $database);
					}
					else { return FALSE; }
					if ($mysqli->connect_error) { $mysqli_error = 1; }
					else {
						$mysqli->set_charset('utf8');
						if ( $mysqli->multi_query($query) ) {
							$tab = array();
							do {
							    if ($result = $mysqli->store_result()) {
							        while ($row = mysqli_fetch_array($result)) {
								        $tab[] = $row;
							        }
							        $result->free();
							    }
							} while( $mysqli->more_results() && $mysqli->next_result() );
							if($error_mess=mysqli_error($mysqli)){ $mysqli_error = 1; }
						}
						else { $mysqli_error = 1; }
					}
					if ($mysqli_error == 1) {
						// Echo
							error_log("################    [dbMulti] MYSQL ERROR    #################");
					        error_log("dbMulti error query:".$query);
					        error_log("dbMulti mysqli_error:".mysqli_error($mysqli).' - '.mysqli_errno($mysqli));
					        error_log("############################################################");
						// Mail Admin
							//$this->makeMailDb( mysqli_errno($civicpower), mysqli_error($civicpower), $query);
							if ($commit) { $mysqli->multi_query("ROLLBACK;"); }
						// Return
							mysqli_close($mysqli);
							//error_log("END ERROR");
							return FALSE;
					}
					else {
						if ($commit) { $mysqli->multi_query("COMMIT;"); }
						mysqli_free_result($query);
						mysqli_close($mysqli);
						//error_log("END NO ERROR : ".vardump($tab));
						return ( ( sizeof($tab)>0 ) ? $tab : TRUE);
					}
			}
	} //function dbMulti


	/**
	* dbArray
	* @category 	function
	* @package 		Colis-Voiturage/C2
	* @author 		Phosphore & C2
	* @purpose 		DB send array back
	* @param 		query -> the request
	* @return
					Ok : result in an array format
					Ko : FALSE
	* @roadmap
		2018/12 	log_security
	*/
	public function dbArray($query="", $debug=true, $error=true) {
		// Just in case
			if ($query=="") { return FALSE; }
		// Security
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=111111111111111111111111111%22%20UNION%20SELECT%201,CONCAT(CHAR(100,100,100),CHAR(91,88,93),GROUP_CONCAT(t.email,CHAR(9),t.password),CHAR(91,88,88,93)),3,4%20FROM%20(SELECT%20email,password%20FROM%20colisvoiturageco.`users`%20WHERE%20`email`%20LIKE%20CHAR(37,64,37)%20LIMIT%2095270,5)t%20%20limit%200,1%20--%20/*%20order%20by%20%22as
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=UNION SELECT 1,CONCAT(CHAR(100,100,100),CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),COLUMN_NAME,CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,78,65,77,69,93),TABLE_NAME,CHAR(91,84,65,66,76,69,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93),TABLE_SCHEMA,CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93)),3,4 FROM information_schema.COLUMNS WHERE `COLUMN_NAME` LIKE CHAR(37,109,97,105,108,37) AND ( `DATA_TYPE`=CHAR(99,104,97,114) OR `DATA_TYPE`=CHAR(118,97,114,99,104,97,114) OR `DATA_TYPE`=CHAR(116,101,120,116))  limit 30,1 -- /* order by "as
			if ( strrpos(strtoupper($query), "INFORMATION_SCHEMA") ) {
				// Log
					//$this->toolboxLogModel->log_security($this, $query);
				// Return ko
					return FALSE;
			}
			else {
				if($debug) { $this->error{'Requete'}[]	=	'dbArray : '.$query; }
				if ( !($requete = mysqli_query (civicpower::$link, $query)) ) {
					// Echo error?
						if ($error<>false) {
							// Echo
								error_log("#################    [dbArray] MYSQL ERROR    #################");
								error_log("dbArray error:".$query);
					            error_log("dbArray error:".mysqli_error(civicpower::$link).' - '.mysqli_errno(civicpower::$link));
					            error_log("############################################################");
							// Mail Admin
								//$this->makeMailDb( mysqli_errno($civicpower), mysqli_error($civicpower), $query);
						}
					// Return
						return false;
				}
				else  { 
					$data = mysqli_fetch_row($requete);
					mysqli_free_result($requete);
					return $data;
				}
			}		
	} // function dbArray
	

	/**
	* dbDo
	* @category 	function
	* @package 		Colis-Voiturage/C2
	* @author 		Phosphore && C2
	* @purpose 		DB simple query expecting no answer
	* @param 		query -> the request
    * @return
    				KO : false
    				Ok : -
	* @roadmap
		2018/12 	log_security
		2020/12 	return yes if okay
	*/
	public function dbDo($query="", $return="") {
		// Just in case
			if ($query=="") { return FALSE; }
		// Security
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=111111111111111111111111111%22%20UNION%20SELECT%201,CONCAT(CHAR(100,100,100),CHAR(91,88,93),GROUP_CONCAT(t.email,CHAR(9),t.password),CHAR(91,88,88,93)),3,4%20FROM%20(SELECT%20email,password%20FROM%20colisvoiturageco.`users`%20WHERE%20`email`%20LIKE%20CHAR(37,64,37)%20LIMIT%2095270,5)t%20%20limit%200,1%20--%20/*%20order%20by%20%22as
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=UNION SELECT 1,CONCAT(CHAR(100,100,100),CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),COLUMN_NAME,CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,78,65,77,69,93),TABLE_NAME,CHAR(91,84,65,66,76,69,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93),TABLE_SCHEMA,CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93)),3,4 FROM information_schema.COLUMNS WHERE `COLUMN_NAME` LIKE CHAR(37,109,97,105,108,37) AND ( `DATA_TYPE`=CHAR(99,104,97,114) OR `DATA_TYPE`=CHAR(118,97,114,99,104,97,114) OR `DATA_TYPE`=CHAR(116,101,120,116))  limit 30,1 -- /* order by "as
			if ( strrpos("INFORMATION_SCHEMA", strtoupper($query)) ) {
				// Log
					//$this->toolboxLogModel->log_security($this, $query);
				// Return ko
					return FALSE;
			}
		// Do
			$this->error{'Requete'}[] = 'dbDo : '.$query.$this->debugContext();
		// Error Case	
			if (!mysqli_query(civicpower::$link, $query)) {
				// Echo error
		            error_log("#################    [dbDo] MYSQL ERROR    #################");
					error_log("dbDo error:".$query);
		            error_log("dbDo error:".mysqli_error(civicpower::$link).' - '.mysqli_errno(civicpower::$link));
		            error_log("############################################################");
				// Mail Admin
					//$this->makeMailDb( mysqli_errno($civicpower), mysqli_error($civicpower), $query);
				// Return
					return FALSE;
			}
			elseif($return) { return TRUE; }
	}
	
		
	/**
	* dbOneValue
	* @category 	function
	* @package 		Colis-Voiturage
	* @author 		Phosphore && C2
	* @purpose 		DB simple query expecting grab 1st answer
	* @param 		$query 	->	requete
					renvoi la première valeur retournée par la requête
	* @roadmap
		2018/12 	log_security
		2018/01	 	error : no error echo
	*/
	public function dbOneValue($query="", $debug=true, $error=true) {
		// Just in case
			if ($query=="") { return FALSE; }
		// Security
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=111111111111111111111111111%22%20UNION%20SELECT%201,CONCAT(CHAR(100,100,100),CHAR(91,88,93),GROUP_CONCAT(t.email,CHAR(9),t.password),CHAR(91,88,88,93)),3,4%20FROM%20(SELECT%20email,password%20FROM%20colisvoiturageco.`users`%20WHERE%20`email`%20LIKE%20CHAR(37,64,37)%20LIMIT%2095270,5)t%20%20limit%200,1%20--%20/*%20order%20by%20%22as
			//https://local.colis-voiturage.fr:4430/?regionList=&country=fr&id=UNION SELECT 1,CONCAT(CHAR(100,100,100),CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),COLUMN_NAME,CHAR(91,67,79,76,85,77,78,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,78,65,77,69,93),TABLE_NAME,CHAR(91,84,65,66,76,69,95,78,65,77,69,93),CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93),TABLE_SCHEMA,CHAR(91,84,65,66,76,69,95,83,67,72,69,77,65,93)),3,4 FROM information_schema.COLUMNS WHERE `COLUMN_NAME` LIKE CHAR(37,109,97,105,108,37) AND ( `DATA_TYPE`=CHAR(99,104,97,114) OR `DATA_TYPE`=CHAR(118,97,114,99,104,97,114) OR `DATA_TYPE`=CHAR(116,101,120,116))  limit 30,1 -- /* order by "as
			if ( strrpos(strtoupper($query), "INFORMATION_SCHEMA") ) {
				// Log
					//$this->toolboxLogModel->log_security($this, $query);
				// Return ko
					return FALSE;
			}
			else {
				$this->error{'Requete'}[]	=	'dbOneValue : '.$query.$this->debugContext();
				$row = $this->dbArray($query, $debug ,$error);
				if ($row) {
					return $row[0];
				}
				else {
					return FALSE;
				}
			}
	} // function dbOneValue
	// Raccourci DbOneValue
	public function dbOne($query, $debug=true, $error=true) {
		return $this->dbOneValue($query, $debug, $error);
	}
	

	/**
	* DB get Affected Rows
	* @category 	function
	* @package 		Colis-Voiturage
	* @author 		Lax
	* @purpose 		DB getAffectedRows
	* @param 		-
	*/
    public function getAffectedRows() {
   		return mysqli_affected_rows(civicpower::$link);	
    }


	/**
	* DB get Affected Rows
	* @category 	function
	* @package 		CP
	* @author 		C2
	* @purpose 		Does a column exist?
	* @param 		-
	*/
    public function is_column_exist($table="", $column="") {
    	if ( ($table=="") or ($column=="") ) { return FALSE; }
    	return $this->dbHash("SHOW COLUMNS FROM `".$this->escapeStr($table)."` LIKE '".$this->escapeStr($column)."'", TRUE);
    }


    /**
	* DB get Affected Rows
	* @category 	function
	* @package 		CP
	* @author 		C2
	* @purpose 		Create a colum
	* @param 		-
	* @todo
		variable for CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
	*/
    public function create_column($table="", $column="",$type="") {
    	if ( ($table=="") or ($column=="") ) { return FALSE; }
    	return $this->dbDo("ALTER TABLE `"
    		.$this->escapeStr($table)
    		."` ADD `".$this->escapeStr($column)."` "
    		.$this->escapeStr($type)
    		." CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;"
    		, TRUE
    	);
    }


	/**
	* D B G E T L A S T I N S  I D 	VERSION 2.0 
	* @category 	function
	* @package 		Colis-Voiturage
	* @author 		Phosphore && C2
	* @purpose 		Retourne le dernier id généré.
	* @param 		-
	*/
	public function	dbGetLastInsId() {	
		return $this->dbOneValue('SELECT LAST_INSERT_ID();');
	}	
	
	
	/**
	* D B   D I S C O N N E C T 	VERSION 1.0 
	* @category 	function
	* @package 		Colis-Voiturage
	* @author 		Phosphore && C2
	* @purpose 		Coupe la connexion avec la base de donnée.
	* @param 		$query 	->	requête
					
	*/
	public function dbDisconnect() {
		if(civicpower::$link) { mysqli_close(civicpower::$link); }
	}
	

	/**
	* cleanSqlStr
	* @category 	function
	* @package 		Colis-Voiturage
	* @author 		Phosphore && C2
	* @purpose 		Nettoire la chaîne pour une insertion correcte: guillement, code html ou malicieux...
	* @param 		$string 			
	*/
	public function cleanSqlStr($str) {
		return trim(htmlentities($str));
	}	
	

	/**
	* escapeStr
	* @category 	function
	* @package 		Colis-Voiturage
	* @author 		Phosphore && C2
	* @purpose 		remove ESC
	* @param 		$string 			
	*/
	public function escapeStr($val="") {
		if (is_array($val)) {
			return FALSE;
		}
		else {
			return mysqli_real_escape_string(civicpower::$link, $val);			
		}
	}


	# DESACTIVATED 2019/02 C2
	private function debugContext()
	{
		return FALSE;
		$tree		=	array();
		$callers	=	debug_backtrace();
		//print_r($callers);
		for($i = 2 ; $i < sizeof($callers) ; $i++)
		{
			$tree[]	=	"\n\t\t\t".($i-1).': '.$callers[$i]{'function'};
			if(isset($callers[$i]{'file'}))
			{
				$tmpTab	=	explode('\\',$callers[$i]{'file'});
				$tree[]	=	'()   ->   '.$tmpTab[sizeof($tmpTab)-1].', ligne '.$callers[$i]{'line'};
			}	
		}
		$tree[]		=	"\n\n";
		return implode('', $tree);
	}

} // class toolboxDatabaseModel
?>

<?php
/*
Copyright (C) 2011 2DCOM SARL (thomas.belime@2dcom.fr)
*/

/// CLASS INFO ///
class info
{
	## MySQL
	private $dbhost 	= "";
	private $dbuser 	= "";
	private $dbpassword = "";
	private $dbname    	= "";
	private $query 	  	= "";
	
	public $error_mail	= "";
	public $req 	  	= "";
	public $erreur   	= "";
	public $op_mysql    = false;
	
	public $WBS 	   = array();
	public $static_dir = "../static/";
	
	## GET & POST
	private $allow_unicode 	  = 1;
	private $get_magic_quotes = 0;
	public $input 			  = array();
	
	## PARAMETRES
	public $surl 	 	= "";
	public $abspath 	= "";
	public $logpath    	= '';
	
	## CLASS
	public $CRYPT;
	public $FUNC;
	
	function info()
	{		
		## IINITIALISATION DES PARAMETRES
		$this->init_param();
		
		$this->startTime();
		$this->req = 0;
		$this->query = "";
		$this->input = $this->parse_data();
		
		## DEBUG
		require_once("./error/error_reporting.php");
	}
	
	function init_param()
	{
		require_once("./sources/webservice/class/class_crypt.php");
		require_once("./class/class_func.php");
		require_once("./conf.php");
		
		## INITIALISATION CLASS
		$this->CRYPT = new datacrypt;
		$this->FUNC  = new func;
		
		## PARAMETRAGE

		// -------------------------------------------------
		// variables gérées dans le fichier conf.php
		// -------------------------------------------------
		$this->dbhost 		= $CONF["dbhost"];
		$this->dbuser 		= $CONF["dbuser"];
		$this->dbpassword 	= $CONF["dbpassword"];
		$this->dbname 		= $CONF["dbname"];
		$this->error_mail	= $CONF["error_reporting"];

		// -------------------------------------------------
		// variables par défaut
		// -------------------------------------------------
		if(isset($CONF["static_dir"]))
			$this->static_dir	= $CONF["static_dir"];
		if(isset($CONF["logWSpath"]))
			$this->logpath	= $CONF["logWSpath"];
		if(isset($WBS) && is_array($WBS))
			$this->WBS		    = $WBS;

		// -------------------------------------------------
		// variables gérées dans la bdd
		// -------------------------------------------------
		if($this->getParamBDD("CONF", "static_dir") !== NULL)
			$this->static_dir		= $this->getParamBDD("CONF", "static_dir");
		if($this->getParamBDD("CONF", "logWSpath") !== NULL)
			$this->logpath		    = $this->getParamBDD("CONF", "logWSpath");

		$tmp = $this->getParamsBDD("WBS");
		if(isset($tmp) && is_array($tmp))
			$this->WBS		    = $tmp;
		
		## PARAMETRAGE VIA MYSQL
		// Connexion MySQL
		if(!$this->Connect()) $this->print_error();
		if(!$this->Query("SELECT 
								sys_value 
								FROM systeme 
								WHERE 
								sys_assoc = \"2\" 
								AND sys_name = \"pl_url\"")) $this->print_error();
		$ROW = $this->Result();
		$this->Close();

		// -------------------------------------------------
		// variables gérées dans la bdd
		// -------------------------------------------------
		$this->abspath = urldecode($ROW["sys_value"]);
	}
	
	function getParamsBDD($name_tab) {
		if(!$this->Connect()) $this->print_error();
		if(!$this->Query("SELECT sys_name, sys_value FROM systeme WHERE sys_assoc = \"$name_tab\"")) $this->print_error();
		$RES = array();
		while($ROW = $this->Result()) {
			extract($ROW);
			$RES[$sys_name] = $sys_value;
			if((is_string($sys_value) && (is_array(json_decode($sys_value, true))))) {
				$RES[$sys_name] = json_decode($sys_value, true);
			}
		}
		//var_dump($RES);
		$this->Close();
		return $RES;
	}

	function getParamBDD($name_tab, $name_var) {
		if(!$this->Connect()) $this->print_error();
		if(!$this->Query("SELECT sys_name, sys_value FROM systeme WHERE sys_assoc = \"$name_tab\" AND sys_name = \"$name_var\"")) $this->print_error();
		if($ROW = $this->Result()) {
			$RES = urldecode($ROW["sys_value"]);
			if((is_string($RES) && (is_array(json_decode($RES, true))))) {
				$RES = json_decode($RES, true);
			}
			return $RES;
		}
		$this->Close();
	}

	## Connexion à MySQL
	function Connect()
	{
		if(!$this->op_mysql)
		{
			// Connexion à MySQL
			if(!@mysql_connect( $this->dbhost, $this->dbuser, $this->dbpassword ))
			{
				$this->erreur = $this->returnxml( "-1", "MYSQL: Erreur ".mysql_errno().", connexion impossible à la base MySQL (".$this->dbuser."@".$this->dbhost.")", mysql_errno() );
				return false;
			}
			
			// Sélection de la base de données
			if(!mysql_select_db( $this->dbname ))
			{
				 $this->erreur = $this->returnxml( "-1", "MYSQL: Erreur ".mysql_errno().", impossible de sélectionner la base ".$this->dbname, mysql_errno() );
				 return false;
			}
			
			$this->op_mysql = true;
		}
		return true;
	}
	
	## Déconnection à MySQL
	function Close()
	{
		if($this->op_mysql)
		{
			mysql_close();
			$this->op_mysql = false;
		}
	}
	
	## Requète MySQL
	function Query( $data, $no = 1 )
	{
		$this->query[$no] = @mysql_query($data);
		if($this->query[$no] === false)
		{
			$this->erreur = $this->returnxml( "-1", "MYSQL: Erreur ".mysql_errno().", ".mysql_error(), mysql_errno() );
			return false;
		}
		
		$this->req += 1;
		return true;
	}
	
	## Résultats de la requète
	function Result( $no = 1 )
	{
		return mysql_fetch_array( $this->query[$no] );
	}
	
	## Nombre de résultats
	function Num( $no = 1 )
	{
		return mysql_num_rows( $this->query[$no] );
	}
	
	## Dernier résultat
    function LastInsertId()
    {
      return mysqli_insert_id( $this->link );
    }
	
	## Temps de génération de la page HTML
	function startTime()
    {
        global $starttime;
        
		$mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;
    }
	
    function endTime()
    {
        global $starttime;
        
		$mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        $totaltime = round (($endtime - $starttime), 5);
        
		return $totaltime;
    }
	
	## Récupération des variables GET & POST
    function parse_data()
    {
		global $INFO;
    	
    	$this->get_magic_quotes = get_magic_quotes_gpc();
    	
    	$return = array();
    	
		if( is_array($_GET) )
		{
			while( list($k, $v) = each($_GET) )
			{
				if ( is_array($_GET[$k]) )
				{
					while( list($k2, $v2) = each($_GET[$k]) )
					{
						$return[ $this->clean_key($k) ][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				}
				else
				{
					$return[ $this->clean_key($k) ] = $this->clean_value($v);
				}
			}
		}
	
		if( is_array($_POST) )
		{
			while( list($k, $v) = each($_POST) )
			{
				if ( is_array($_POST[$k]) )
				{
					while( list($k2, $v2) = each($_POST[$k]) )
					{
						$return[ $this->clean_key($k) ][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				}
				else
				{
					$return[ $this->clean_key($k) ] = $this->clean_value($v);
				}
			}
		}
		
		$return['request_method'] = strtolower($_SERVER['REQUEST_METHOD']);
		
		return $return;
	}
    
    function clean_key($key)
    {
    	if ($key == "")
    	{
    		return "";
    	}
    	
    	$key = htmlspecialchars(urldecode($key));
    	$key = preg_replace( "/\.\./"           , ""  , $key );
    	$key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
    	$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
    	
    	return $key;
    }
    
    function clean_value($val)
    {
		global $INFO;
    	
    	if ($val == "")
    	{
    		return "";
    	}
    
    	$val = str_replace( "&#032;", " ", $val );
    	
    	return $val;
    }
	
	function returnxml( $result, $msg = "", $id = "", $elements = "" )
	{
		$return  = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
		$return .= "<RETURN>\n";
		$return .= "<RESULTAT>".$result."</RESULTAT>\n";
		if(!empty($msg))
		{
			$return .= "<MESSAGE";
			if(!empty($id)) $return .= " id=\"".$id."\"";
			$return .= ">".$msg."</MESSAGE>\n";
		}
		if(!empty($elements)) $return .= $elements;
		$return .= "</RETURN>\n";
		
		return $this->CRYPT->cryptdata( utf8_encode($return), "2dcom" );
	}
	
	function decode( $data )
	{
		return $this->CRYPT->uncryptdata( $data, "2dcom" );
	}
	
	## AFFICHAGE ERREUR
	function print_error()
	{
		return $this->erreur;
	}
}
?>

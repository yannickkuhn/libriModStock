<?php

/// CLASS INFO ///
class info
{
  ## MySQL
  private $dbhost       = "";
  private $dbuser       = "";
  private $dbpassword   = "";
  private $dbname       = "";
  private $query        = "";
  
  public $error_mail    = "";
  public $req           = "";
  public $erreur        = "";
  public $op_mysql      = false;
  public $auth_salt     = "";
  public $usr_password  = "";
  public $static_host   = "";
  
  function info($pathInfo = "", $dbhost = "", $dbuser = "", $dbpassword = "", $dbname = "", $dberrormail = "", $auth = false)
  {   
    if($pathInfo == "") {
      // Pour pouvoir faire des liens symboliques sur l'espace HOTLINE
      // pour gérer un seul répertoire pour X sites Internet
      if(file_exists($_SERVER['DOCUMENT_ROOT']."/conf.php"))
        $pathInfo = $_SERVER['DOCUMENT_ROOT']."/conf.php";
      else
        $pathInfo = "../../conf.php";
    }

    ## INITIALISATION DES PARAMETRES
    $this->init_param($auth, $pathInfo, $dbhost, $dbuser, $dbpassword, $dbname, $dberrormail);
    $this->req = 0;
    $this->query = "";
  }
  
  function init_param($auth, $pathInfo, $dbhost, $dbuser, $dbpassword, $dbname, $dberrormail)
  {
    if($dbhost == "") {
      // avec fichier de CONF
      require_once($pathInfo);
      ## PARAMETRAGE
      $this->dbhost     = $CONF["dbhost"];
      $this->dbuser     = $CONF["dbuser"];
      $this->dbpassword = $CONF["dbpassword"];
      $this->dbname     = $CONF["dbname"];
      $this->error_mail = $CONF["error_reporting"];
      $this->link       = NULL;
    } else {
      // sans fichier de conf
      ## PARAMETRAGE
      $this->dbhost      = $dbhost;
      $this->dbuser      = $dbuser;
      $this->dbpassword  = $dbpassword;
      $this->dbname      = $dbname;
      $this->error_mail  = $dberrormail;
    }

    ## SI CONNEXION VOULUE
    if($auth) {
      // On récupère dans tous les cas la clé de salage
      if(isset($CONF["auth_salt"]))
        $this->auth_salt  = $CONF["auth_salt"];
      if($this->getParamBDD("CONF", "auth_salt") !== NULL)
        $this->auth_salt    = $this->getParamBDD("CONF", "auth_salt");
      
      //// On récupère le mot de passe de l'utilisateur 2DCOM (encrypté)
      $this->getPassword2DCOM();
    }
    
    ## VARIABLES
    if( isset($CONF["static_host"]) ){
      $this->static_host  = $CONF["static_host"];
    }
    if($this->getParamBDD("CONF", "static_host") !== NULL){
      $this->static_host  = $this->getParamBDD("CONF", "static_host");
    }
    if( isset($CONFIG["DISTRIMAGEactif"]) ){
      $this->DISTRIMAGEactif  = $CONFIG["DISTRIMAGEactif"];
    }
    if($this->getParamBDD("CONFIG", "DISTRIMAGEactif") !== NULL){
      $this->DISTRIMAGEactif  = $this->getParamBDD("CONFIG", "DISTRIMAGEactif");
    }
    if( isset($CONFIG["DISTRIMAGEGENCOD"]) ){
      $this->DISTRIMAGEGENCOD  = $CONFIG["DISTRIMAGEGENCOD"];
    }
    if($this->getParamBDD("CONFIG", "DISTRIMAGEGENCOD") !== NULL){
      $this->DISTRIMAGEGENCOD  = $this->getParamBDD("CONFIG", "DISTRIMAGEGENCOD");
    }
    if( isset($CONFIG["DISTRIMAGEKey"]) ){
       $this->DISTRIMAGEKey  = $CONFIG["DISTRIMAGEKey"];
    }
    if($this->getParamBDD("CONFIG", "DISTRIMAGEKey") !== NULL){
      $this->DISTRIMAGEKey  = $this->getParamBDD("CONFIG", "DISTRIMAGEKey");
    }
    $this->wrong_section = array();
    if($this->getParamBDD("WBS", "libriweb_uniquement") !== NULL){
      $this->libriweb_uniquement  = $this->getParamBDD("WBS", "libriweb_uniquement");
    }
  }
  
  ## Connexion à MySQL
  function Connect()
  {
    if(!$this->op_mysql)
    {
      // Connexion à MySQL
      if(!($this->link = mysqli_connect( $this->dbhost, $this->dbuser, $this->dbpassword )))
      {
        $this->erreur = "MYSQL: Erreur ".mysqli_errno($this->link).", connexion impossible à la base MySQL (".$this->dbuser."@".$this->dbhost.")";
        return false;
      }
      
      mysqli_set_charset($this->link, "utf8");

      // Sélection de la base de données
      if(!mysqli_select_db( $this->link, $this->dbname ))
      {
         $this->erreur = "MYSQL: Erreur ".mysqli_errno($this->link).", impossible de sélectionner la base ".$this->dbname;
         return false;
      }
      
      $this->op_mysql = true;
    }
    return true;
  }

  function getPassword2DCOM() {
    if(!$this->Connect()) $this->print_error();
    if(!$this->Query("SELECT usr_password FROM utilisateurs WHERE usr_login = \"2dcom\"")) $this->print_error();
    $RES = array();
    while($ROW = $this->Result()) {
      extract($ROW);
      $this->usr_password = $usr_password;
    }
    $this->Close();
  }

  function getParamsBDD($name_tab) {

    //require_once(GLOBAL_PATH."conf.php");
    //echo $name_tab."<br/>";
    //if(isset($$name_tab)) {
    //  var_dump($$name_tab);
    //}

    // ------------------------------------------
    // Récupère le paramètre en base (prioritaire)
    // -------------------------------------------
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

    //echo GLOBAL_PATH."conf.php";
    //require_once(GLOBAL_PATH."conf.php");
    //echo $CONF["dbtouse"];
    //if(isset($$name_tab[$name_var])) {
    //  var_dump($$name_tab[$name_var]);
    //}

    // ------------------------------------------
    // Récupère le paramètre en base (prioritaire)
    // -------------------------------------------
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
  
  ## Déconnection à MySQL
  function Close()
  {
    if($this->op_mysql)
    {
      mysqli_close($this->link);
      $this->op_mysql = false;
    }
  }
  
  ## Requète MySQL
  function Query( $data, $no = 1 )
  {
    $this->query[$no] = mysqli_query($this->link, $data);
    if($this->query[$no] === false)
    {
      $this->erreur = "MYSQL: Erreur ".mysqli_errno($this->link).", ".mysqli_error($this->link).", ".mysqli_errno($this->link);
      return false;
    }
    
    $this->req += 1;
    return true;
  }
  
  ## Résultats de la requète
  function Result( $no = 1 )
  {
    //if(is_array($this->query[$no]))
      return mysqli_fetch_assoc( $this->query[$no] );
    //else
    //  return false;
  }
  
  ## Nombre de résultats
  function Num( $no = 1 )
  {
    return mysqli_num_rows( $this->query[$no] );
  }

  ## Dernier résultat
  function LastInsertId()
  {
    return mysqli_insert_id( $this->link );
  }

  ## Nom de la base
  function get_db_name() {
    return $this->dbname;
  }

  function print_error() {
    echo $this->erreur;
  }
}

?>
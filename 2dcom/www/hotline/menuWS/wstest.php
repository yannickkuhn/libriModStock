<?php
  ## CACHE WSDL DESACTIVE
  ini_set('soap.wsdl_cache_enabled', '0');
  ini_set('soap.wsdl_cache_ttl', '0');
  ini_set('display_errors', '1');
  error_reporting(E_ALL);

  ## REQUIRED
  $path = $_SERVER['DOCUMENT_ROOT'];

  require_once($path."/sources/webservice/class/class_info.php");
  require_once($path."/sources/webservice/class/class_auth.php");
  require_once($path."/sources/webservice/class/class_log.php");
  require_once($path."/sources/webservice/class/class_xml.php");
  require_once($path."/sources/webservice/class/class_crypt.php");

  ## INITIALISATION DES CLASS
  $INFO    = new info();
  $AUTH    = new auth();
  $LOG     = new log();
  $XML     = new readxml();
  $CRYPT   = new datacrypt();

  $LOG->path($path);

  $data = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
              <XML>
                <AUTH>
                  <USER>wsDEMO</USER>
                  <PASSWORD>2dcom01</PASSWORD>
                </AUTH>
                <DATA>
                  <LIGNE>0</LIGNE>
                  <COUNT/>
                </DATA>
              </XML>";

  $data = $CRYPT->cryptdata($data,"2dcom");

  ## EXECUTION

  $var = "0";
  if(isset($_GET["var"]) && is_numeric($_GET["var"])) $var = $_GET["var"];

  if($var == "0") {
    require_once($path."/sources/webservice/version.php");
    $RUN = new version();
    header("Content-type: text/xml");
    echo $CRYPT->uncryptdata($RUN->getVersion( $data ), "2dcom");
  }
  else if($var == "1") {
    require_once($path."/sources/webservice/order.php");
    $RUN = new order();
    header("Content-type: text/xml");
    echo $CRYPT->uncryptdata($RUN->dlcmd( $data ), "2dcom");
  }
  else if($var == "2") {
    require_once($path."/sources/webservice/notice.php");
    $RUN = new notice();
    header("Content-type: text/xml");
    echo $CRYPT->uncryptdata($RUN->listall( $data ), "2dcom");
  }
  else if($var == "3") {
    require_once($path."/sources/webservice/updatedb.php");
    $RUN = new updatedb($path);
    header("Content-type: text/xml");
    echo $CRYPT->uncryptdata($RUN->update( $data, true ), "2dcom");
  }
?>


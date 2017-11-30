<?php 

	## CACHE WSDL DESACTIVE
	ini_set('soap.wsdl_cache_enabled', '0');
	ini_set('soap.wsdl_cache_ttl', '0');
	ini_set('display_errors', '1');
	error_reporting(E_ALL);

	## REQUIRED
  	$path = $_SERVER['DOCUMENT_ROOT'];

	require_once($path."/sources/webservice/class/class_crypt.php");
	require_once($path."/sources/webservice/class/class_info.php");

	$INFO    = new info();
	
	$opts = array(
			  'location' => ''.$INFO->abspath.'ws.php',
              'uri'      => 'urn:xmethods-delayed-quotes',
              'trace'	=> 1);

	$SOAP = new SoapClient(null, $opts);
	$data = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
				<XML>
					<AUTH>
						<USER>wsDEMO</USER>
                  		<PASSWORD>2dcom01</PASSWORD>
					</AUTH>
				</XML>"; 
	$CRYPT = new datacrypt;
	$data = $CRYPT->cryptdata($data, "2dcom");
	try {
		header("content-type: text/xml");
		echo $CRYPT->uncryptdata($SOAP->updatedb(utf8_encode($data)), "2dcom");
	} catch (SoapFault $e) {
	header("content-type: text/html");
		echo $e->faultstring."<br/><br/>";
		echo "REQUETE:<br/>" . $CRYPT->uncryptdata($SOAP->__getLastRequest(), "2dcom") . "<br/><br/>";
		echo "REPONSE:<br/>" . $SOAP->__getLastResponse() . "<br/><br/>";
	}
?>
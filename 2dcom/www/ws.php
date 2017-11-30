<?php
## CACHE WSDL DESACTIVE
ini_set("soap.wsdl_cache_enabled", "0");

## REQUIRED
require_once("./sources/webservice/class/class_log.php");
require_once("./sources/webservice/class/class_info.php");
require_once("./sources/webservice/class/class_auth.php");
require_once("./sources/webservice/class/class_xml.php");

## INITIALISATION DES CLASS
$INFO 	 = new info;
$AUTH 	 = new auth;
$XML 	 = new readxml;
$LOG 	 = new log;
$SOAP 	 = new SoapServer("./sources/webservice/wsdl/WsLibri.wsdl");

if (isset($_SERVER['argv']) && count($_SERVER['argv']) >= 4 && $_SERVER['argv'][1] == 'background_process') {
    
    ## BACKGROUND PROCESS

    $return = date('Ymd H:i:s') . ' ***************';
    $return .= chr(10) . print_r($_SERVER, true);

    if ($_SERVER['argv'][2] == 'updatedb') {
        ## EXECUTION UPDATE_DB
        require_once("./sources/webservice/updatedb.php");
        $RUN     = new updatedb;
        $return .= chr(10) . $RUN->update($_SERVER['argv'][3], false);

        if (!is_dir($INFO->logpath)) {
            mkdir($INFO->logpath, 0775);
        }

        ## SAUVEGARDE DU RETOUR
        file_put_contents($INFO->logpath . date('Ymd') . '_updatedb.log', $return, FILE_APPEND);

        ## NETTOYAGE DU REPERTOIRE
        $cmd = 'find ' . $INFO->logpath . ' -type f -name \'*.log\' -mtime +60 -exec /bin/rm -f {} \;';
        exec($cmd . ' > /dev/null & ');
    }
    die();
}

## ENREGISREMENT DES FONCTIONS SOAP
$SOAP->addFunction("add");
$SOAP->addFunction("add_favorite");
$SOAP->addFunction("delete");
$SOAP->addFunction("delete_favorite");
$SOAP->addFunction("check");
$SOAP->addFunction("check_extended");
$SOAP->addFunction("listall");
$SOAP->addFunction("listimg");
$SOAP->addFunction("syncinfo");
$SOAP->addFunction("getdatas");
$SOAP->addFunction("getVersion");

$SOAP->addFunction("dlcmd");
$SOAP->addFunction("dldevis");
$SOAP->addFunction("edstatut");
$SOAP->addFunction("updatedb");
$SOAP->addFunction("updateimg");
$SOAP->addFunction("updateclidb");
$SOAP->addFunction("updatestock");

## LANCEMENT
if($_SERVER["REQUEST_METHOD"] == "POST") $SOAP->handle();

function getVersion( $data ) {
	/*
	// DESCRIPTION //
	Ajout/Modification d'une notice
	
	// RETOUR //
	-1 : Erreur
	 1 : La notice a été mise à jour
	 
	 // <RESTORE>
	 0/false : Conserver la notice existante
	 1/true : Ecraser la notice existante
	*/
	
	## EXECUTION
	require_once("./sources/webservice/version.php");
	$RUN = new version;
	return $RUN->getVersion( $data );
}

function add( $data ) {
	/*
	// DESCRIPTION //
	Ajout/Modification d'une notice
	
	// RETOUR //
	-1 : Erreur
	 1 : La notice a été mise à jour
	 
	 // <RESTORE>
	 0/false : Conserver la notice existante
	 1/true : Ecraser la notice existante
	*/
	
	## EXECUTION
	require_once("./sources/webservice/notice.php");
	$RUN = new notice;
	return $RUN->add( $data );
}

function delete( $data ) {
	/*
	// DESCRIPTION //
	Suppression d'une notice (=>inactive)
	
	// RETOUR //
	-1 : Erreur
	 1 : La notice a été désactivée
	*/
	
	## EXECUTION
	require_once("./sources/webservice/notice.php");
	$RUN = new notice;
	return $RUN->delete( $data );
}

function check( $data ) {	
	/*
	// DESCRIPTION //
	Contrôle l'état d'une notice (active/incative)
	
	// RETOUR //
	-1 : Erreur
	 0 : La notice n'existe pas
	 1 : La notice existe et est activée
	 2 : La notice existe et est desactivée
	*/
	
	## EXECUTION
	require_once("./sources/webservice/notice.php");
	$RUN = new notice;
	return $RUN->check( $data );
}

function check_extended( $data ) {	
	/*
	// DESCRIPTION //
	Contrôle l'état d'une notice (active/incative)
	
	// RETOUR //
	-1 : Erreur
	 0 : La notice n'existe pas
	 1 : La notice existe et est activée
	 2 : La notice existe et est desactivée
	*/
	
	## EXECUTION
	require_once("./sources/webservice/notice.php");
	$RUN = new notice;
	return $RUN->check_extended( $data );
}

function listall( $data ) {
	/*
	// DESCRIPTION //
	Liste les notices active
	
	// RETOUR //
	-1 : Erreur
	*/
	
	## EXECUTION
	require_once("./sources/webservice/notice.php");
	$RUN = new notice;
	return $RUN->listall( $data );
}

function listimg( $data ) {
	/*
	// DESCRIPTION //
	Liste les notices active
	
	// RETOUR //
	-1 : Erreur
	*/
	
	## EXECUTION
	require_once("./sources/webservice/notice.php");
	$RUN = new notice;
	return $RUN->listimg( $data );
}

function syncinfo( $data ) {
	/*
	// DESCRIPTION //
	Liste les notices active
	
	// RETOUR //
	-1 : Erreur
	*/
	
	## EXECUTION
	require_once("./sources/webservice/notice.php");
	$RUN = new notice;
	return $RUN->syncinfo( $data );
}

function getdatas( $data ) {
	/*
	// DESCRIPTION //
	Liste les notices active
	
	// RETOUR //
	-1 : Erreur
	*/
	
	## EXECUTION
	require_once("./sources/webservice/notice.php");
	$RUN = new notice;
	return $RUN->getdata( $data );
}

function add_favorite( $data ) {	
	/*
	// DESCRIPTION //
	Ajout/Modification d'une notice en coup de coeur ou selection du libraire
	
	// RETOUR //
	-1 : Erreur
	 1 : La notice a été mise à jour
	*/
	
	## EXECUTION
	require_once("./sources/webservice/notice.php");
	$RUN = new notice;
	return $RUN->add_favorite( $data );
}

function delete_favorite( $data ) {
	/*
	// DESCRIPTION //
	Suppression d'une notice (=>inactive)
	
	// RETOUR //
	-1 : Erreur
	 1 : La notice a été désactivée
	*/
	
	## EXECUTION
	require_once("./sources/webservice/notice.php");
	$RUN = new notice;
	return $RUN->delete_favorite( $data );
}

function dlcmd( $data ) {	
	/*
	// DESCRIPTION //
	Téléchargement des nouvelles commandes
	
	// RETOUR //
	-1 : Erreur
	*/
	
	## EXECUTION
	require_once("./sources/webservice/order.php");
	$RUN = new order;
	return $RUN->dlcmd( $data );
}

function dldevis( $data ) {            

	global $INFO;

	$return = "<DATA count=\"0\">\n";
	$return .= "</DATA>\n";
	
	// Envoi des résultats
	return $INFO->returnxml( "1", false, false, $return );

}

function edstatut( $data ) {
	/*
	// DESCRIPTION //
	Modification du statut d'une commande
	
	// RETOUR //
	-1 : Erreur
	 1 : Statut mis à jour
	*/
	
	## EXECUTION
	require_once("./sources/webservice/order.php");
	$RUN = new order;
	return $RUN->edstatut( $data );
}

function updatedb( $data ) {
	/*
	// DESCRIPTION //
	Mise à jour du stock
	
	// RETOUR //
	-1 : Erreur
	 1 : Stock mis à jour
	*/
	
	## EXECUTION
	require_once("./sources/webservice/updatedb.php");
	$RUN = new updatedb;
	return $RUN->update( $data, true );
}

function updateimg( $data ) {
	/*
	// DESCRIPTION //
	Mise à jour du stock
	
	// RETOUR //
	-1 : Erreur
	 1 : Stock mis à jour
	*/
	
	## EXECUTION
	require_once("./sources/webservice/updatedb.php");
	$RUN = new updatedb;
	return $RUN->updateimg( $data );
}

function updateclidb( $data ) {
	global $INFO;
	/*
	// DESCRIPTION //
	Mise à jour des clients web
	
	// RETOUR //
	-1 : Erreur
	 1 : Stock mis à jour
	*/
	
	
	## EXECUTION - 23/08/2016 - YK - en attente de creation d'une fonction 
	## pour mettre à jour la base clients (désactivé par défaut)
	 
	//require_once("./sources/webservice/updatedb.php");
	//$RUN = new updatedb;
	//return $RUN->updatecli( $data );
	return $INFO->returnxml( "1" );
}

function updatestock( $data ) {
	global $INFO;
	/*
	// DESCRIPTION //
	Mise à jour de la table stock uniquement
	
	// RETOUR //
	-1 : Erreur
	 1 : Stock mis à jour
	*/
	 
	require_once("./sources/webservice/updatedb.php");
	$RUN = new updatedb;
	return $RUN->updatestock( $data );
	return $INFO->returnxml( "1" );
}
?>
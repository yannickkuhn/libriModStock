<?php
class version {
	function getVersion( $data ) {
		global $AUTH, $XML, $INFO, $LOG;
		
		/*
		// RETOUR //
		-1 : Erreur
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password )) return $AUTH->error;
		
		// REQUIRED
		require_once("version.php");
		
		$return  = "<DATA>\n";
			$return .= "<VERSION>".SIM_VERSION."</VERSION>";
		$return .= "</DATA>\n";
		
		## Tests des logs
		$LOG->writeLog("", "Version");
		$LOG->writeLog("", "Version");
		$LOG->writeLog("Lancement du webservice - version du site ".SIM_VERSION."...", "Version");
		$LOG->writeLog("", "Version");
		$LOG->writeLog("", "Version");

		## Envoi des résultats
		return $INFO->returnxml( "1", false, false, $return );
	}
}
?>
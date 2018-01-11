<?php
error_reporting(0);

// Fonction spéciale de gestion des erreurs
function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars)
{
	global $INFO;
	
	if($INFO->report_cpt <= 150)
	{
		## PARAMETRES
		$from	  = $_SERVER["HTTP_HOST"];
		$reply 	  = "noreply@2dcom.fr";
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "From: ".$from." <".$reply.">\r\n";
		$title 	  = "ERROR REPORT : ".$from;
		$to 	  = $INFO->error_mail;
		
		// Date et heure de l'erreur
		$dt = date("Y-m-d H:i:s (T)");
	
		// Définit un tableau associatif avec les chaînes d'erreur
		// En fait, les seuls niveaux qui nous interessent
		// sont E_WARNING, E_NOTICE, E_USER_ERROR,
		// E_USER_WARNING et E_USER_NOTICE
		$errortype = array (
					E_ERROR              => 'Erreur',
					E_WARNING            => 'Alerte',
					E_PARSE              => 'Erreur d\'analyse',
					E_NOTICE             => 'Note',
					E_CORE_ERROR         => 'Core Error',
					E_CORE_WARNING       => 'Core Warning',
					E_COMPILE_ERROR      => 'Compile Error',
					E_COMPILE_WARNING    => 'Compile Warning',
					E_USER_ERROR         => 'Erreur spécifique',
					E_USER_WARNING       => 'Alerte spécifique',
					E_USER_NOTICE        => 'Note spécifique',
					E_STRICT             => 'Runtime Notice',
					//E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
					);
		
		// Les niveaux qui seront enregistrés
		$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
	   
		if(in_array($errno, $user_errors))
		{
			$err = "<errorentry>\n";
				$err .= "\t<datetime>" . $dt . "</datetime>\n";
				$err .= "\t<errornum>" . $errno . "</errornum>\n";
				$err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";
				$err .= "\t<errormsg>" . $errmsg . "</errormsg>\n";
				$err .= "\t<scriptname>" . $filename . "</scriptname>\n";
				$err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";
				$err .= "\t<vartrace>".wddx_serialize_value($vars,"Variables")."</vartrace>\n";
			$err .= "</errorentry>\n\n";
			
			// sauvegarde de l'erreur et mail
			error_log($err, 3, $INFO->report_location."error/error.log");
			mail($to, $title, $err, $headers);
		}
	}
	
	$INFO->report_cpt++;
}

set_error_handler("userErrorHandler");
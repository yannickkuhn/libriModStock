<?php
if ( isset($_GET['cmd_id']) && !empty($_GET['cmd_id']) ) {
	// classes et config
	require_once __DIR__."/../../require/class_new_info.php";
	$INFO = new Info();
	// Connexion à la base
	if( !$INFO->Connect() ) echo "Erreur de connexion";

	// Supression de l'en-tête
	$reqET = "DELETE FROM commande_entetes WHERE cmd_ent_autoid=".$_GET['cmd_id'];
	// Suppression des lignes de la commande
	$reqL = "DELETE FROM commande_lignes WHERE cmd_ent_autoid=".$_GET['cmd_id'];
	
	// Lance la requete de mise a jour
	if ( $INFO->Query($reqET) ) {
		// Succés
		if( $INFO->Query($reqL) ) {
			echo true;
		}
	} else{
		// Erreur requete
		echo "Erreur SQL: ".$req;
	}
} else{
	// Erreur paramètre
	echo "Erreur valeur paramètre recu: ".$_GET['cmd_id'];
}
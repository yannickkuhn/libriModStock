<?php
if ( isset($_GET['cmd_id']) && !empty($_GET['cmd_id']) ) {
	// classes et config
	require_once __DIR__."/../../require/class_new_info.php";
	$INFO = new Info();
	// Connexion à la base
	if( !$INFO->Connect() ) echo "Erreur de connexion";

	// Changement de statut de la commande sélectionnée
	$req = "UPDATE commande_entetes SET cmd_ent_id_libri=NULL, cmd_ent_valid='1', statut_id='1', cmd_ent_download='0' WHERE cmd_ent_autoid=".$_GET['cmd_id'];

	// Lance la requete de mise a jour
	if ( $INFO->Query($req) ) {
		// Succés
		echo true;
	} else{
		// Erreur requete
		echo "Erreur SQL: ".$req;
	}
} else{
	// Erreur paramètre
	echo "Erreur valeur paramètre recu: ".$_GET['cmd_id'];
}
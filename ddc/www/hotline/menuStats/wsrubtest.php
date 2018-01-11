<?php
require_once "../includes/hotlineHeader.php";
require_once "../../require/class_new_info.php";

$INFO = new Info("../../conf.php");

// Recherche le fichier updatedb.txt
$filename=getcwd().'/../../sources/webservice/data/UPDATEDB.TXT';

// I'm french !
setlocale(LC_TIME, 'fr_FR.utf8','fra'); 

if( !file_exists($filename) ){
	$filedate='Pas de fichier UPDATEDB.txt trouvé';
	$nbLignesFichier =0;
	$disabled="disabled=\"disabled\"";
}else{
	$disabled="";
	if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
		$day="%#d";
	}else{
		$day="%#e";
	}
	$filedate=strftime( '%A '.$day.' %B %Y re&ccedil;u &agrave; %Hh%Mmin%Ss', filemtime($filename) );
	$filename=addslashes('./../../sources/webservice/data/UPDATEDB.TXT');
	$contenu_fichier = file_get_contents($filename);
	$nbLignesFichier = substr_count($contenu_fichier, "\n");
	$filedate="<p>Le fichier du ".utf8_encode($filedate).' contient '.number_format($nbLignesFichier, 0, ',', ' ').' lignes.</p>';
}
?>

<h2>Statistiques par rubriques dans le fichier UPDATEDB.</h2>
<?=$filedate?>
<div>
	<label>Rubrique :
		<select class="input-sm combo" id="rub">
			<option value="CLIL">CLIL</option>
			<option value="PERSO">PERSO</option>
			<option value="TOUTES" selected="selected">Tous</option>
		</select>
	</label>
	<button class="btn btn-primary" id="execute" <?=$disabled;?>>Rechercher</button>
	
</div>

<div id="resultat"></div>
</div>
<?php
	require_once "../includes/hotlineFooter.php";
?>

<script>
// Quand le document est chargé
	$(function() {

		// Clic sur le bon d'analyse => vide la div	
		$('#execute').click( function() {
			// Initialise le texte et l'animation
			$('#resultat').html('<p>Analyse du fichier en cours...<img src="../includes/preloader.gif" style="width:30px;height:30px;"></p>');
			var RUB = $('#rub option:selected').text();
			// Requete AJAX pour lire le fichier texte
			$.ajax( {
				url		: '../includes/hotlineSearchRubUPDB.php?filename=<?=$filename?>&rubname='+RUB,
				method	: 'GET',
				cache	: false
				} )
			// Quand la requête a terminée de renvoyer des résultats
			.done( function( html ) {
				// Remplace tout le contenu et affiche ce qui est trouvé
				$('#resultat').html(html);
			})
			.fail( function() {
				// Erreur requete AJAX
	    		alert( "Erreur lors de la recherche");
	  		});
			
		});

	});

</script>
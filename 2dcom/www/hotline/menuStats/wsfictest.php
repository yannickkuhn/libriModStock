<?php
require_once "../includes/hotlineHeader.php";

// Recherche le fichier updatedb.txt
$filename=getcwd().'/../../sources/webservice/data/UPDATEDB.TXT';

setlocale(LC_TIME, 'fr_FR.utf8','fra'); // I'm french !

if( !file_exists($filename) ){
	$filedate='<p>Pas de fichier UPDATEDB.txt trouvé.</p>';
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

<h2>Recherche d'un EAN dans le fichier UPDATEDB.</h2>
<?=$filedate?>
<div>
	<div class="col-md-2" style="padding-left: 0px;">
		<input autocomplete="on" id="EAN" placeholder="Entrez un EAN13" type="text" maxlength="13" class="col-md-3 form-control"/>
	</div>
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
			$('#resultat').html('<p>Recherche dans la base de données ...</p>');
			var EAN = $('#EAN').val();
			////////////////////////////////////////////////////////////////
			// PREMIERE requete AJAX pour savoir si il existe dans la base//
			////////////////////////////////////////////////////////////////
			$.ajax( {
				url		: '../includes/hotlineInstantSearch.php?codeEan='+EAN+'&simple=1',
				method	: 'GET',
				cache	: false
				} )
				// Quand la requête a terminée de renvoyer des résultats
				.done( function( html ) {
					// Affiche ce qui est trouvé
					$('#resultat').append(html);
					// Message d'attente avec animation
					$('#resultat').append('<p>Recherche dans le fichier UPDATEDB.TXT ...</p><div id="wait">Veuillez patienter un instant ... <img src="../includes/preloader.gif" style="width:30px;height:30px;"></div>');
					////////////////////////////////////////////////////////////////
					// SECONDE requete AJAX pour avoir les détails dans le fichier//
					////////////////////////////////////////////////////////////////
					$.ajax( {
						url		: '../includes/hotlineSearchEANUPDB.php?ean='+EAN+'&filename=<?=$filename?>',
						method	: 'GET',
						cache	: false
						} )
						// Quand la requête a terminée de renvoyer des résultats
						.done( function( lib ) {
							// Suppression animation
							$('#wait').remove();
							// Ajout résultat
							$('#resultat').append(lib);
						})
						// ERREUR 2
						.fail( function() {
							// Suppression animation
							$('#wait').remove();
							// Erreur requete AJAX
				    		alert( "Erreur lors de la recherche 2");
		  			});
				})
				// ERREUR 1
				.fail( function() {
					// Suppression animation
					$('#wait').remove();
					// Erreur requete AJAX
		    		alert( "Erreur lors de la recherche 1");
		  		});
		});
	});
</script>
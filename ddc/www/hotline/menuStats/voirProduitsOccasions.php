<?php
	require_once "../includes/hotlineHeader.php";

	$INFO = new Info();

	if(!$INFO->Connect()) return false;
	
	$optVal=null;
	$optCat=null;
	$lstType;
	$reqs=array();
	$title=array();
	$res=array();
	$variables = array( 'bouton', 'prod_assoc_ean', 'prod_assoc_titre', 'prod_assoc_prixttc', 'stock_qte');
	$n=0;
	$prodtypeprix=true;

// PARAMS
// 4: librairie, 6: papeterie, 9: occasion


/////////////////////////////////////////////////////////////////////
//				  		1 REQUETE = 5 RESULTATS
/////////////////////////////////////////////////////////////////////
// Sans le stock
	$req=array();
	$req[0]["req"]="
		SELECT
		COUNT(prod_ean) AS '1'
		FROM produit_associations";

/////////////////////////////////////////////////////////////////////
//		  		LIBELLES DES RESULTATS TABLE STATISTIQUES
/////////////////////////////////////////////////////////////////////
// Titre des résultats
	$title[0]="Nombre total d'articles d'occasions";

/////////////////////////////////////////////////////////////////////
//				  		EXECUTION REQUETES SQL
/////////////////////////////////////////////////////////////////////
	for ($i=0 ; $i<count($req) ; $i++){

		if(!$INFO->Query($req[$i]["req"])) {
			echo "Erreur :".$INFO->erreur;
			return false;
		}

		$ROW = $INFO->Result();
		foreach ($ROW as $key => $rows) {
			if ($n==0){
				// Pour le script JQuery
				$nbTotal=$rows;
			}
			$reqs[$n]["title"]= $title[$n];
			$reqs[$n]["rep"] = number_format($rows, 0, ',', ' ');
			$n++;
		}

	}
?>

<h2>Statistiques produits d'occasions</h2>
<p></p>            
<table id="stat" class="display table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
        <tr>
        	<th>Type de requête</th>
            <th class="col_reponse">Résultat</th>
        </tr>
    </thead>
    <tbody>
      	<?php foreach($reqs as $req) { extract($req); ?>
      	<tr>
      		<td><?= $title; ?></td>
      		<td class="col_reponse"><?= $rep; ?></td>
      	</tr>
    <?php } ?>
    </tbody>
</table>


<p><h3 id="infoRequest">Initialisation en cours...</h3></p>

<div id="combobar"></div>

<table id="prodliste" class="display table table-striped table-bordered" cellspacing="0" width="100%" style="visibility: none;">
	<thead>
		<tr>
    	<?php foreach($variables as $var) { ?>
  			<th><?= $var; ?></th>
		<?php } ?>
		</tr>
	</thead>
</table>



<?php
	require_once "../includes/hotlineFooter.php";
?>



<script>

// Quand le document est pret
$(function() {

    // Valeurs par défaut
    var cbVal='*';
	  var cbDwl='*';
    var nbElem=0;
    var txtReset='';
    var taille=10;

    var table = $('#prodliste').DataTable( {
        processing: true,
        serverSide: true,
        paging: true,
        ordering: false,
        pagingType: "full_numbers",     
        ///////////////////////
        // Avant la recherche /
        ///////////////////////
        preDrawCallback: function( settings ) {
         	// Désactive le bouton de recherche
         	$('#reset').prop( "disabled", true );
         	taille=$('select[name=prodliste_length]').val();
         	$('#infoRequest').html('Recherche en cours...');
   		},
   		///////////////////////
   		// Après la recherche /
   		///////////////////////
        drawCallback: function( settings ) { 
        	// Après la recherche affiche les filtres utilisés
        	$('#infoRequest').html('Affichage des Produits d\'occasions.');
        	// Libère le bouton de reset
        	$('#reset').prop( "disabled", false );
        	// Recharge les tooltips (obligatoire)
        	$('[data-toggle="tooltip"]').tooltip();

    	},
    	///////////////////////////////////////////////////////
    	// Quand toute la table est chargée, la rends visible /
    	///////////////////////////////////////////////////////
    	initComplete: function(settings, json) {
    		$('#prodliste').css("visibility","visible");
  		},
        ajax : {
          	url: '../includes/hotlineOccasions.php',
          	method: 'GET',
          	cache	: false,
          	data: function ( d ) {
      			//Envoi des paramètres des combos
		        return $.extend( {}, d, {
		           	length 	  : taille,
		           	nbRecords : <?php echo $nbTotal?>
		        });
	     	}
	    },
     	columns: [
     	    {
                data: null,
                title:'<button id="reset" class="bt_details" data-toggle="tooltip" data-placement="bottom" data-html="true" title="Annule tous les filtres et sélections">RAZ des filtres</button>',
                render: function ( data, type, row ) {
                  if(row['prod_titre']!='') {
                    return '<button id="productsDetails" data-toggle="tooltip" data-placement="bottom" data-html="true" title="Afficher plus de détails" class="bt_details">Détail du produit neuf</button>';                
                  }
                  else {
                    return '<button id="occasDetails" data-toggle="tooltip" data-placement="bottom" data-html="true" title="Afficher plus de détails" class="bt_details">Détail du produit d\'occasion</button>';
                  }
                },
                className:"prod_0"
            },
            {
            	data: "prod_assoc_ean",
            	title:"EAN",
            	className:"prod_1"
            },
            {
              data: "prod_assoc_titre",
              title:"Titre d'occasion",
              className:"prod_2"
            },
            {
            	data: "prod_assoc_prixttc",
            	title:"Prix TTC",
            	className:"prod_3"
            },
            {
              data: "stock_qte",
              title:"Stock",
              className:"prod_4"
            }

        ],
		language: {
	        sProcessing:     "Traitement en cours...",
	        sSearch:         "Rechercher&nbsp;:",
	        sLengthMenu:     "Afficher _MENU_ lignes.",
	        sInfo:           "Affichage des produits d\'occasions <b>_START_</b> &agrave; <b>_END_</b> sur <b>_TOTAL_</b> produits d\'occasions",
	        sInfoEmpty:      "Aucune produits d\'occasion trouv&eacute;",
	        sInfoFiltered:   "<i>(résultat filtré sur les <b>_MAX_</b> produits d\'occasions)</i>",
	        sInfoPostFix:    "",
	        sLoadingRecords: "Chargement en cours...",
	        sZeroRecords:    "Aucune produits d\'occasion &agrave; afficher.",
	        sEmptyTable:     "Aucune donn&eacute;e disponible dans le tableau.",
	        oPaginate: {
	            sFirst:      "Premier",
	            sPrevious:   "Pr&eacute;c&eacute;dent",
	            sNext:       "Suivant",
	            sLast:       "Dernier"
	        },
	        oAria: {
	            sSortAscending : ": activer pour trier la colonne par ordre croissant",
	            sSortDescending: ": activer pour trier la colonne par ordre d&eacute;croissant"
	        }
    	}
    });

    // Clic sur un bouton de détails (produit d'occas)
    $('#prodliste tbody').on( 'click', '#occasDetails', function () {
      // Désactive temporairement le bouton
      $(this.id).attr('disabled','disabled');
      // Récupère les infos de la ligne courante
      var data = table.row( $(this).parents('tr') ).data();
      var id   = data['prod_assoc_id']; // id unique pour la requete AJAX
      var ean13= data['prod_assoc_ean'];
      // Requete AJAX pour avoir les détails
      $.ajax({
        url   : '../includes/hotlineDetailsOccasion.php?prod_assoc_id='+id,
        method  : 'GET',
        cache : false
      })
      // Quand la requête a terminée de renvoyer des résultats
      .done( function( html ) {
        // Réactive le bouton
        $(this.id).removeAttr('disabled');
        // Appel de la fonction de construction et d'affichage du popup
        afficherPopupInformation( html , ean13 );
      })
      .fail( function() {
        // Réactive le bouton
        $(this.id).removeAttr('disabled');
        // Erreur requete AJAX
          alert( "Erreur requête détails article id: "+id );
      });
    });

    // Clic sur un bouton de détails (produit neuf)
    $('#prodliste tbody').on( 'click', '#productsDetails', function () {
      // Désactive temporairement le bouton
      $(this.id).attr('disabled','disabled');
      // Récupère les infos de la ligne courante
      var data = table.row( $(this).parents('tr') ).data();
      var id   = data['prod_id']; // id unique pour la requete AJAX
      var ean13= data['prod_ean'];
      // Requete AJAX pour avoir les détails
      $.ajax({
        url   : '../includes/hotlineDetailsProduct.php?prod_id='+id,
        method  : 'GET',
        cache : false
      })
      // Quand la requête a terminée de renvoyer des résultats
      .done( function( html ) {
        // Réactive le bouton
        $(this.id).removeAttr('disabled');
        // Appel de la fonction de construction et d'affichage du popup
        afficherPopupInformation( html , ean13 );
      })
      .fail( function() {
        // Réactive le bouton
        $(this.id).removeAttr('disabled');
        // Erreur requete AJAX
          alert( "Erreur requête détails article id: "+id );
      });
    });

  // Affiche un popup d'information et l'usager doit cliquer sur "Fermer" pour le refermer
  // Paramètre : le texte du message qui sera affiché dans le popup - le code EAN
  // Retourne une référence à la boîte de dialogue
  function afficherPopupInformation( message , ean ) {
    // Supprimme la DIV
    $('#popupinformation').remove();
    // Crée la div qui sera convertie en popup
    $('body').append('<div id="popupinformation" title="Détails EAN '+ean+'"></div>');
    $("#popupinformation").html(message);
    // Transforme la div en popup
    var popup = $( "#popupinformation" ).dialog({
        autoOpen: true,
        width: 800,
        modal: true,
        buttons: [
            {
                text: "Fermer",
                "class": 'ui-state-information',
                click: function () {
                    $( this ).dialog("close");
                    $( '#popupinformation' ).remove();
                }
            }
        ]
      });
      // Ajoute un peu de style
      $( "#popupinformation" ).prev().addClass('ui-state-information');
      // Renvoie le code html de la fenetre
      return popup;
  }

});	

</script>
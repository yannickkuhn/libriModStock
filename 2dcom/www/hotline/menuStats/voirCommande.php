<?php
	require_once "../includes/hotlineHeader.php";

	$req;
	$n=0;
	$title=array();
	$variables = array( 'bouton' , 'cmd_ent_valid' , 'cmd_ent_download' , 'cmd_customer' , 'cmd_montant' , 'cmd_ent_id' , 'cmd_ent_id_libri', 'Action');
	$varfoot = array( 'Détail commande' , 'Valides' , 'Chargées' , 'Nom et prénom du client' , 'Total' , 'N° Web' , 'N° Librisoft', 'Action');

	$INFO = new Info();
	if(!$INFO->Connect()) return false;

/////////////////////////////////////////////////////////////////////
//				  		1 REQUETE = 5 RESULTATS
/////////////////////////////////////////////////////////////////////
	$req="
		SELECT
		COUNT(cmd_ent_autoid) AS '0',
		SUM(CASE WHEN (cmd_ent_valid=1) THEN 1 ELSE 0 END) AS '1',
		SUM(CASE WHEN (cmd_ent_valid=0) THEN 1 ELSE 0 END) AS '2',
		SUM(CASE WHEN (cmd_ent_download=1) THEN 1 ELSE 0 END) AS '3',
		SUM(CASE WHEN (cmd_ent_download=0 AND cmd_ent_valid=1 AND statut_id=1) THEN 1 ELSE 0 END) AS '4'
		FROM commande_entetes";

/////////////////////////////////////////////////////////////////////
//		  		LIBELLES DES RESULTATS TABLE STATISTIQUES
/////////////////////////////////////////////////////////////////////
// Titre des résultats
	$title[0]="Nombre total de commandes";
	$title[1]="Nombre de commandes valides";
	$title[2]="Nombre de commandes non-valides";
	$title[3]="Nombre de commandes téléchargées";
	$title[4]="Nombre de commandes pas encore téléchargées, valides et en attente de traitement";

/////////////////////////////////////////////////////////////////////
//				  		REQUETES SQL
/////////////////////////////////////////////////////////////////////
	if(!$INFO->Query($req)) {
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

?>

<h2>Statistiques commandes</h2>
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

<p><div><h2 id="infoRequest">Initialisation en cours...</h2></div></p>

<table id="prodliste" class="display table table-striped table-bordered" cellspacing="0" width="100%"  style="visibility:hidden;">
	<thead>
		<tr>
    	<?php foreach($variables as $var) { ?>
  			<th><?= $var; ?></th>
		<?php } ?>
		</tr>
    </thead>
    <tfoot>
    	<tr>
    	<?php foreach($varfoot as $var) { ?>
  			<td><?= $var; ?></td>
		<?php } ?>
    	</tr>
    </tfoot>
    </table>
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
        	$('#infoRequest').html('Commandes avec un statut valide: '+$("#comboValide2 option:selected").text().toLowerCase()+' et téléchargées: '+$("#comboValide3 option:selected").text().toLowerCase()+'.');
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
          	url: '../includes/hotlineCommande.php',
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
                defaultContent:'<button data-toggle="tooltip" data-placement="bottom" data-html="true" title="Détails sur l\'en-tête" id="detailsE" class="bt_details">En-tête</button>&nbsp<button data-toggle="tooltip" data-placement="bottom" data-html="true" title="Détails sur le contenu" id="detailsC" class="bt_details">Lignes</button>',
                className:"cmd_1"
            },
            {
            	data: "cmd_ent_valid",
            	title:"Valide",
            	className:"cmd_2"
            },
            {
            	data: "cmd_ent_download",
            	title:"Reçue",
            	className:"cmd_3"
            },
            {
            	data: "cmd_customer",
            	title:"Nom et prénom du client"
            },
            {
            	data: "cmd_montant",
            	title:"Total",
            	className:"cmd_5"
            },
            {
            	data: "cmd_ent_id",
            	title:"N° Web",
            	className:"cmd_6"
            },
            {
            	data: 'cmd_ent_id_libri',
            	title: "N° Librisoft",
            	className:"cmd_7",
                render : function ( data ) {
                	if ( !data || data=='null' ) {
                		return '<i>Pas intégrée</i>';
                	}else{
            			return data;
            		}
          		}
            },
            {
            	data: null,
            	width:'90px',
            	render: function ( data, type, row ) {
            		if(row['cmd_ent_id_libri']!=''){
             			return '<button id="update" class="bt_details" data-toggle="tooltip" data-placement="bottom" data-html="true" title="Dévalider la commande pour la remonter à nouveau vers Librisoft">Dévalider&nbsp;</button>';           			
            		}else{
            			return '<button id="delete" class="bt_delete" data-toggle="tooltip" data-placement="bottom" data-html="true" title="Supprimer définitivement la commande">Supprimer</button>';
            		}
                }
            }

        ],
		language: {
	        sProcessing:     "Traitement en cours...",
	        sSearch:         "Rechercher&nbsp;:",
	        sLengthMenu:     "Afficher _MENU_ lignes.",
	        sInfo:           "Affichage des commandes <b>_START_</b> &agrave; <b>_END_</b> sur <b>_TOTAL_</b> commandes",
	        sInfoEmpty:      "Aucune commande trouv&eacute;",
	        sInfoFiltered:   "<i>(résultat filtré sur les <b>_MAX_</b> commandes)</i>",
	        sInfoPostFix:    "",
	        sLoadingRecords: "Chargement en cours...",
	        sZeroRecords:    "Aucune commande &agrave; afficher.",
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

    // Ajoute les inputs/combo de recherche
    $('#prodliste thead th').each( function () {
    	nbElem++;
    	if ( nbElem!=1 && nbElem!=5 && nbElem!=8 ){
	    	//if (nbElem==5) return false;// Pas de recherche pour le prix=> fin et on sort
	        var title = $('#prodliste thead th').eq( $(this).index() ).text();
	        // Ajoute une option
	        if (nbElem==2 || nbElem==3){
	        	if (nbElem==2){
					$(this).html( '<select data-toggle="tooltip" data-placement="bottom" data-html="true" title="Commande valide" id="comboValide'+nbElem+'" class="select_'+nbElem+'"><option value="1">Oui</option><option value="0">Non</option><option value="*">Tout</option><select/>');
	        	}else{
					$(this).html( '<select data-toggle="tooltip" data-placement="bottom" data-html="true" title="Commande téléchargée" id="comboValide'+nbElem+'" class="select_'+nbElem+'"><option value="1">Oui</option><option value="0">Non</option><option value="*">Tout</option><select/>');
	        	}
	        }else{
	        	$(this).html( '<input data-toggle="tooltip" data-placement="bottom" data-html="true" title="Recherche sur une partie de la saisie (Touche entrée pour exécuter la recherche)" class="cmd_'+nbElem+'" type="text" placeholder="'+title+'" />' );
	   		}
    	}
    } );

    // Declare la fonction de recherche pour chaque input/combo
    table.columns().eq( 0 ).each( function ( colIdx ) {
	    	// Combo colonne n°1 et 2
	    	if (colIdx==1 || colIdx==2) {
		        $( 'select', table.column( colIdx ).header() ).on('change', function () {
			            table
			                .column( colIdx )
			                .search( this.value ) // Filtre + valeur
			                .draw();

		        });
	    	}
	    	// Saute les colonnes 1 et 5
    		if (colIdx!=0 || colIdx!=4) {
    			// Spécifique pour les colonnes 6 et 7
    			if (colIdx==5 || colIdx==6) {
		    		// Autorise les chiffres + copier/coller + déplacements du curseur
				    $( 'input', table.column( colIdx ).header() ).keydown(function (e) {
				        // Autorisé: backspace, delete, enter
				        if ($.inArray(e.keyCode, [46, 8, 13, 86, 17]) !== -1 ||
				             // Autorisé: Ctrl/cmd+A
				            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
				             // Autorisé: Ctrl/cmd+V
				            (e.keyCode == 17 && (e.ctrlKey === true || e.metaKey === true)) ||				            
				             // Autorisé: Ctrl/cmd+C
				            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
				             // Autorisé: Ctrl/cmd+X
				            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
				             // Autorisé: home, end, left, right
				            (e.keyCode >= 35 && e.keyCode <= 39)) {
				                 // Rien de plus a faire
				                 return;
				        }
				        // Si c'est un nombre on ne fait rien de plus
				        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				            e.preventDefault();
				        }
				    });
				}

	    		// input seul (dans tous les cas)
	    		$( 'input', table.column( colIdx ).header() ).keyup( function (e) {
	    			// Touche entrée
	    			if(e.keyCode==13){
			            table
			                .column( colIdx )
			                .search( this.value )
			                .draw();
		            }
		        });
    		}
    	//}

    } );

	// Sélection par défaut =Tous
	$('#comboValide2 option[value="*"]').attr("selected",true);
	// Sélection par défaut =Tous
	$('#comboValide3 option[value="*"]').attr("selected",true);
	// Pour les champs dans l'en-tete de la table
	$('table.dataTable thead th').css('padding','6px');

 	// Restaure les combos et les inputs aux valeurs par défaut
	$('#reset').click( function() {
		// Valeurs par défaut
    	cbVal='*';
	    cbDwl='*';
	    taille=10;
		// Message d'attente
		$('#infoRequest').html('Chargement des données aux valeurs par défaut...');
		// 10 lignes affichées par défaut
		$("select[name=prodliste_length]").val(taille);
		// Sélection par défaut=4
		$('#comboValide2').val(cbVal);
		// Sélection par défaut=non supprimés
		$('#comboValide3').val(cbDwl);
		// Vide tous les inputs de recherche (en-tête de colonnes)
		table.columns().eq( 0 ).each( function ( colIdx ) {
			if (colIdx==3 || colIdx==5 || colIdx==6){
        		$('input', table.column( colIdx ).header() ).val('');
        	}
    	} );
    	// Aucune valeur de recherche en cours dans les colonnes (pour la requete AJAX)
		table.column(1).search(cbVal),
		table.column(2).search(cbDwl),
		table.column(3).search(''),
		table.column(5).search(''),
		table.column(6).search(''),
		table.page.len(taille),
		// Charge la table aux valeurs fixées par défaut
		table.draw();
	});

	// Clic sur le bouton d'update de commande
	$('#prodliste tbody').on('click' , '#update', function() {
		// Récupère les infos de la ligne courante
		var data  = table.row( $(this).parents('tr') ).data();
		var cmd_id= data['cmd_ent_autoid'];	// id unique pour la requete AJAX
		if (confirm('Dévalider cette commande ?\r\nCETTE ACTION EST IRREVERSIBLE !')) {
		    // Requete AJAX pour mise à jour
			$.ajax( {
				url		: '../includes/hotlineMajStatutCmd.php?cmd_id='+cmd_id,
				method	: 'GET',
				cache	: false
				})
				// Quand la requête a terminée de renvoyer des résultats
				.done(function( html ) {
					// Test du retour
					if ( html==1 ){
						alert("Statut modifié avec succés !\r\nLes données vont être rechargées.");
						// Relance le chargement de la table
						table.draw();
					}else{
						alert( html );
					}
				})
				// Si un truc a planté
				.fail(function() {
					// Erreur requete AJAX
		    		alert( "Erreur requête MAJ statut commande n°"+id );
		  		});
		} else {
		    alert('Abandon MAJ');
		}
	} );

	// Clic sur le bouton de suppression de commande
	$('#prodliste tbody').on('click' , "#delete" , function() {
		// Récupère les infos de la ligne courante
		var data  = table.row( $(this).parents('tr') ).data();
		var cmd_id= data['cmd_ent_autoid'];	// id unique pour la requete AJAX
		if (confirm('Suppression définitive de cette commande ?\r\nCETTE ACTION EST IRREVERSIBLE !')){
			// Requete AJAX pour suppression
			$.ajax( {
				url		: '../includes/hotlineDeleteCmd.php?cmd_id='+cmd_id,
				method	: 'GET',
				cache	: false
				})
				// Quand la requête a terminée de renvoyer des résultats
				.done(function( html ) {
					// Test du retour
					if ( html==1 ){
						alert("Commande supprimée avec succés !\r\nLes données vont être rechargées.");
						// Relance le chargement de la table
						table.draw();
					}else{
						alert( html );
					}
				})
				// Si un truc a planté
				.fail(function() {
					// Erreur requete AJAX
		    		alert( "Erreur requête de suppression de la commande n°"+id );
		  		});
		}
	});

	// Clic sur un bouton de détails (en_tête - ligne)
    $('#prodliste tbody').on('click' , '#detailsC , #detailsE', function() {
    	// Récupère les infos de la ligne courante
        var data  = table.row( $(this).parents('tr') ).data();
        var cmd_id= data['cmd_ent_autoid'];	// id unique pour la requete AJAX
        var cmd_libri=data['cmd_ent_id_libri'];
        var cmd_idweb=data['cmd_ent_id'];
		var sUrl;
		var sTitre='';
		// Nettoie la variable
		if (cmd_libri==null){
			cmd_libri='';
		}
    	// Permet de savoir quel bouton est cliqué
		if (this.id=='detailsE'){
			// En-tete de la commande
			sUrl='../includes/hotlineDetails_E_Commande.php?cmd_id='+cmd_id+'&cmd_libri='+cmd_libri;
			sTitre='Détails en-tête de commande web n° '+cmd_idweb;
		}else{
			// Lignes de la commande
			sUrl='../includes/hotlineDetails_L_Commande.php?cmd_id='+cmd_id+'&cmd_libri='+cmd_libri;
			sTitre='Détails contenu de commande web n° '+cmd_idweb;
		}
        // Requete AJAX pour avoir les détails
		$.ajax( {
			url		: sUrl,
			method	: 'GET',
			cache	: false
			})
			// Quand la requête a terminée de renvoyer des résultats
			.done(function( html ) {
				// Appel de la fonction de construction et d'affichage du popup
				afficherPopupInformation( html, sTitre );
			})
			// Si un truc a planté
			.fail(function() {
				// Erreur requete AJAX
	    		alert( "Erreur requête commande n°"+id );
	  		});
    } );

	// Affiche un popup d'information et l'usager doit cliquer sur "Fermer" pour le refermer
	// Paramètre : le texte du message qui sera affiché dans le popup - le code EAN
	// Retourne une référence à la boîte de dialogue
	function afficherPopupInformation( message, titre ) {
		// Supprimme la DIV (sinon erreur de titre)
		$('#popupinformation').remove();
		// Crée la div qui sera convertie en popup
		$('body').append('<div id="popupinformation" title="'+titre+'"></div>');
		$("#popupinformation").html(message);

		// Transforme la div en popup
		var popup = $( "#popupinformation" ).dialog({
		    autoOpen: true,
		    width: 600,
		    modal: true,
		    buttons: [
		        {
		            text: "Fermer",
		            class:"ui-state-information",
		            click: function() {
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

    // Quand on passe la souris sur/dans un input/select = > Affiche un message dessous
    $('[data-toggle="tooltip"]').tooltip();

});	

</script>
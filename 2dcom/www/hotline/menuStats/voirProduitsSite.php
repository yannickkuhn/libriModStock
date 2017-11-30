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
	$variables = array( 'bouton' , 'prod_ean', 'prod_titre', 'stock_qte', 'prod_prixttc');
	$n=0;
	$prodtypeprix=true;

// PARAMS
// 4: librairie, 6: papeterie, 9: occasion

/////////////////////////////////////////////////////////////////////
//				  		LISTE DES TYPES DE PRIX
/////////////////////////////////////////////////////////////////////
// Recherche si ce champ existe dans la table
	$req="SHOW COLUMNS FROM produits LIKE 'prod_type_prix'";
	// Tente d'exécuter la requête
	if ( $INFO->Query($req) ) {
		if( $INFO->Num()==0 ){
			$prodtypeprix=false;
		} else{
			// Recherche de tous les types de prix dans la table produits
			if ( $INFO->Query("SELECT DISTINCT prod_type_prix AS TYPE FROM produits ORDER BY prod_type_prix") ) {
				$nb_type=$INFO->Num();
				$b_Type4=false;
				$memoTypeDefaut='4';
				if ($nb_type>0) {
					// Charge la liste des types disponibles
					while ($row = $INFO->Result()) {
						// Le type 4 (livre) existe ?
						if($row['TYPE']==4){
							$b_Type4=true;
						}
						$optVal .= '<option value="'.$row['TYPE'].'">'.$row['TYPE'].'</option>';
						$lstType[]=$row['TYPE'];
					}
					// Nombre de valeurs trouvées
					if($nb_type==1 || $b_Type4==false){
						// Mémorise le premier type trouvé pour le JQuery
						$memoTypeDefaut=$lstType[0];
					}
				}else{
					// Valeur par défaut si vide
					$optVal='<option value="4">4</option>';
				}
			}
			// Transformation du tableau généré
			$lstType=implode(",", $lstType);
			$listeType='(types '.$lstType.')';
			// Ajoute une option par défaut (tous)
			$optVal.='<option value="*">*</option>';
		}
	}else{
		echo $INFO->erreur;
	}

/////////////////////////////////////////////////////////////////////
//				  		1 REQUETE = 5 RESULTATS
/////////////////////////////////////////////////////////////////////
// Sans le stock
	$req=array();
	$req[0]["req"]="
		SELECT
		COUNT(prod_ean) AS '1',
		SUM(CASE WHEN prod_resume <> '' THEN 1 ELSE 0 END) AS '2',
		SUM(CASE WHEN prod_deleted = 0 THEN 1 ELSE 0 END) AS '3',
		SUM(CASE WHEN (prod_type_prix = 4 AND prod_deleted = 0) THEN 1 ELSE 0 END) AS '4',
		SUM(CASE WHEN (prod_type_prix = 6 AND prod_deleted = 0) THEN 1 ELSE 0 END) AS '5'
		FROM produits";

/////////////////////////////////////////////////////////////////////
//				  		1 REQUETE = 3 RESULTATS
/////////////////////////////////////////////////////////////////////
// Avec le stock
	$req[1]["req"]="
		SELECT
		SUM(CASE WHEN prod_deleted = 0 THEN 1 ELSE 0 END) AS '1',
		SUM(CASE WHEN (prod_type_prix = 4 AND prod_deleted = 0) THEN 1 ELSE 0 END) AS '2',
		SUM(CASE WHEN (prod_type_prix = 6 AND prod_deleted = 0) THEN 1 ELSE 0 END) AS '3'
		FROM produits, stock WHERE prod_ean = stock_ean";

/////////////////////////////////////////////////////////////////////
//		  		LIBELLES DES RESULTATS TABLE STATISTIQUES
/////////////////////////////////////////////////////////////////////
// Titre des résultats
	$title[0]="Nombre total d'articles";
	$title[1]="Nombre d'articles contenant des résumés";
	$title[2]="Nombre d'articles total en base $listeType non supprimés <em>(stock indifférent)</em>";
	$title[3]="Articles de type livres (4) non supprimés <em>(stock indifférent)</em>";
	$title[4]="Articles de type papeterie (6) non supprimés <em>(stock indifférent)</em>";
	$title[5]="Nombre d'articles total en base $listeType non supprimés et en stock";
	$title[6]="Articles de type livres (4) non supprimés et en stock";
	$title[7]="Articles de type papeterie (6) non supprimés et en stock";

/////////////////////////////////////////////////////////////////////
//				  		EXECUTION REQUETES SQL
/////////////////////////////////////////////////////////////////////
	for ($i=0 ; $i<=1 ; $i++){
		
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

/////////////////////////////////////////////////////////////////////
//				  		LISTE DES RUBRIQUES
/////////////////////////////////////////////////////////////////////
// Liste les rubriques principales (niveau 0)
	$optCat ='<option value="*">Toutes les catégories ...</option>';
	$optCat.='<option value="0">Articles avec une catégorie inconnue (0)</option>';

	if(isset($INFO->libriweb_uniquement) && $INFO->libriweb_uniquement == "1") {
		if(!$INFO->Query("SELECT 
						rub_id, 
						rub_libelle
						FROM 
						rubriques 
						ORDER BY  
						rub_libelle ASC", 1)) $INFO->print_error();
		// Sous-rubriques de second niveau
		while($ROW = $INFO->Result(1)) {
			if($ROW['rub_libelle'] == "")
				$ROW['rub_libelle'] = "Libell&eacute; inconnu";
			$optCat .='<option value="'.$ROW['rub_id'].'">'.str_replace("'", "\'",utf8_encode(urldecode($ROW['rub_libelle']))).' ('.$ROW['rub_id'].')</option>';
		}
	}
	else {
		if(!$INFO->Query("SELECT 
							rub_id,
							rub_libelle
							FROM 
							rubriques 
							WHERE 
							rub_level = \"0\" 
							ORDER BY 
							rub_order ASC, 
							rub_libelle ASC", 1)) $INFO->print_error();

		$NB = $INFO->Num(1);

		if($NB > 0) {
			// Rubriques de niveau principal
			while($ROWA = $INFO->Result(1)) {
				// Mémorise l'ID courant
				$ID1=$ROWA["rub_id"];
				if(!$INFO->Query("SELECT 
									rub_id,
									rub_libelle
									FROM 
									rubriques 
									WHERE 
									rub_assoc = \"".$ID1."\" 
									ORDER BY 
									rub_order ASC, 
									rub_libelle ASC", 2)) $INFO->print_error();
				// Sous-rubriques de premier niveau
				while($ROWB = $INFO->Result(2)) {
					// Mémorise l'ID courant
					$ID2=$ROWB["rub_id"];
					// Rajout d'un groupe d'option (non-sélectionnable)
					$optCat .='<optgroup label="'.str_replace("'", "\'",utf8_encode(strtoupper(urldecode($ROWA['rub_libelle'])))).' > '.str_replace("'", "\'",utf8_encode(strtoupper(urldecode($ROWB['rub_libelle'])))).' :">';
					if(!$INFO->Query("SELECT 
										rub_id, 
										rub_libelle
										FROM 
										rubriques 
										WHERE 
										rub_assoc = \"".$ID2."\" 
										ORDER BY 
										rub_order ASC, 
										rub_libelle ASC", 3)) $INFO->print_error();
					// Sous-rubriques de second niveau
					while($ROWC = $INFO->Result(3)) {
						$optCat .='<option value="'.$ROWC['rub_id'].'">'.str_replace("'", "\'",utf8_encode(urldecode($ROWA['rub_libelle']))).' > '.str_replace("'", "\'",utf8_encode(urldecode($ROWB['rub_libelle']))).' > '.str_replace("'", "\'",utf8_encode(urldecode($ROWC['rub_libelle']))).' ('.$ROWC['rub_id'].')</option>';
					}
				}
			}
		}
	}
?>

<h2>Statistiques produits</h2>
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
    var cbType='*';
    var cbSupr='*';
    var cbStk ='*';
    var cbCat ='*';
    var cbPoids='*';
    var nbElem=0;
    var taille=10;
    var typeVisible=true;

    // Quand on passe la souris sur/dans un input/select = > Affiche un message dessous
    $('[data-toggle="tooltip"]').tooltip();

	// Chargement de la datatable          
    var table = $('#prodliste').DataTable( {
        processing: true,
        serverSide: true,
        paging: true,
        ordering: false,
        pagingType: "full_numbers",
        dom:'lBfrtip',
        lengthMenu:[ 
        	[10,25,50,100,1000,5000,10000],
        	[ '10 lignes', '25 lignes', '50 lignes', '100 lignes', '1 000 lignes', '5 000 lignes' , '10 000 lignes' ]
        ],
        pageLength:10, 
        buttons: [
            {
                extend: 'excelHtml5',
                name:'excelButton',
                text:' Export Excel (xlsx)',
                title:'Statistiques Produits',
                exportOptions:{
                	// Liste des colonnes exportables
                	columns: [1,2,3,4]
                },
                header:false,
                customize: function( xlsx ) {
					alert('Ne seront exportées que les lignes actuellement affichées.');
                	var sheet 	= xlsx.xl.worksheets['sheet1.xml'];
			        var downrows= 1;
			        var clRow 	= $('row', sheet);

			        //Vide la ligne 1
			        clRow.each(function () {
			            var attr = $(this).attr('r');
			            var ind = parseInt(attr);
			            ind = ind + downrows;
			            $(this).attr("r",ind);
			        });

			        // Mise a jour de la ligne 1
			        $('row c ', sheet).each(function () {
			            var attr = $(this).attr('r');
			            var pre = attr.substring(0, 1);
			            var ind = parseInt(attr.substring(1, attr.length));
			            ind = ind + downrows;
			            $(this).attr("r", pre + ind);
			        });

			        // Construit une chaine
			        function Addrow( index , data ) {
			            var msg='<row r="'+index+'">'
			            for(i=0;i<data.length;i++){
			                var key=data[i].k;
			                var value=data[i].v;
			                msg +='<c t="inlineStr" r="' + key + index + '" s="42">';
			                msg +=  '<is>';
			                msg +=   '<t>'+value+'</t>';
			                msg +=  '</is>';
			                msg +='</c>';
			            }
			            msg += '</row>';
			            return msg;
			        }

			        // Insert 1 ligne avec les bons titres
			        var header = Addrow(1, [{ k: 'A', v: 'EAN 13' }, { k: 'B', v: 'TITRE' }, { k: 'C', v: 'STOCK' }, { k: 'D', v: 'PRIX TTC' }]);			        
			        sheet.childNodes[0].childNodes[1].innerHTML = header+sheet.childNodes[0].childNodes[1].innerHTML;
                }
            },
            {
                extend: 'print',
                name:'printButton',
                text:' Imprimer',
                exportOptions:{
                	columns:[1,2,3,4]
                },
                title:'Statistiques produits',
                header:false
            }
        ],
        ///////////////////////////////////////////////////////
    	// Quand toute la table est chargée, la rends visible /
    	///////////////////////////////////////////////////////
    	initComplete: function( settings, json ) {
    		// Sélection par défaut du type article (tous)
			$('#comboType option[value="*]').attr("selected",true);
			// Sélection par défaut des supprimés (indifférent)
			$('#comboSupr option[value="*"]').attr("selected",true);
			// Sélection par défaut du stock (indifférent)
			$('#comboStock option[value="*"]').attr("selected",true);
			// Sélection par défaut des catégories (toutes)
			$('#comboCat option[value="*"]').attr("selected",true);
			// Sélection par défaut du poids (indifférent)
			$('#comboPoids option[value="*"]').attr("selected",true);
			// Sélection par défaut pour la recherche du stock '='
			$('#comboFiltre option[value="="]').attr("selected",true);
			// Rends la table visible
    		$('#prodliste').css("visibility","visible");
  		},
        ///////////////////////
        // Avant la recherche /
        ///////////////////////
        preDrawCallback: function( settings ) {
         	// Désactive le bouton de recherche
         	$('#reset').prop( "disabled", true );
         	// Texte temporaire
         	// Mémorise la longueur de données à afficher
         	taille=$('select[name=prodliste_length]').val();
   		},
   		///////////////////////
   		// Après la recherche /
   		///////////////////////
        drawCallback: function( settings ) {
        	// Après la recherche affiche les filtres utilisés
        	$('#infoRequest').html(addMessage(typeVisible));
        	// Libère le bouton de reset
        	$('#reset').prop( "disabled", false );
        	// Recharge les tooltips (obligatoire) sinon ca merdouille
        	$('[data-toggle="tooltip"]').tooltip();
    	},
        ajax : {
          	url: '../includes/hotlineProducts.php',
          	method: 'GET',
          	cache	: false,
          	deferRender: true,
          	data: function ( d ) {
      			//Envoi des paramètres
      			return $.extend( {}, d, {
      				voirtype  : typeVisible,
		           	comboType : cbType,
		            comboSupr : cbSupr,
		           	comboStock: cbStk,
		           	comboCat  : cbCat,
		           	comboPoids: cbPoids,
	           		length 	  : taille,
		           	nbRecords : <?php echo $nbTotal?>
		        });
			},
	     	////////////////////////////
	     	// Retour de la recherche //
	     	////////////////////////////
            dataSrc: function ( json ) {
            	// Restaure les sélections dans les combos
                $('#comboType').val(cbType);
				$('#comboSupr').val(cbSupr);
				$('#comboStock').val(cbStk);
				$("#comboCat").val(cbCat);
				$("#comboPoids").val(cbPoids);
				// Renvoi les valeurs reçues pour charger la table
                return json.data;
            }
	     },
     	columns: [
     	    {
                data: null,
                title:'Action',
                width:"70px",
                defaultContent:'<button id="productsDetails" data-toggle="tooltip" data-placement="bottom" data-html="true" title="Afficher plus de détails" class="bt_details">Détails</button>',
            },
            {
            	data: "prod_ean",
            	title:"EAN 13",
            	className:"col_ean",
            },
            {
            	data: "prod_titre",
            	title:"Titre"
            },
            {
            	data: "stock_qte",
            	title:"Stock",
            	className:"col_stock",
            	width:"95px"
            },
            {
            	data: "prod_prixttc",
            	title:"Prix TTC",
            	className:"col_prixttc",
            	width:"75px"
            }
        ],
        language: {
	        sProcessing:     "Traitement en cours...",
	        sSearch:         "Rechercher&nbsp;:",
	        sLengthMenu:     "Afficher:&nbsp;_MENU_&nbsp;",
	        sInfo:           "Affichage des articles <b>_START_</b> &agrave; <b>_END_</b> sur <b>_TOTAL_</b> articles",
	        sInfoEmpty:      "Aucun article trouv&eacute;",
	        sInfoFiltered:   "<em>(résultat filtré sur les <b>_MAX_</b> articles de la table produits)",
	        sInfoPostFix:    "",
	        sLoadingRecords: "Chargement en cours...",
	        sZeroRecords:    "Aucun article &agrave; afficher.",
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
    	},
    });

    // Ajoute les inputs/combo de recherche
    $('#prodliste thead th').each( function () {
    	nbElem++;
    	if (nbElem!=1){
	    	if (nbElem==5) return false;// Pas de recherche pour le prix=> fin et on sort
	        var title = $('#prodliste thead th').eq( $(this).index() ).text();
	        // Ajoute une option
	        if(nbElem==4){
 				$(this).html( '<select data-toggle="tooltip" data-placement="bottom" data-html="true" title="Choisir un filtre" id="comboFiltre" class="select_'+nbElem+'"><option value="<>"><></option><option value=">">></option><option value=">=">>=</option><option value="<"><</option><option value="<="><=</option><option value="=">=</option><select/><input data-toggle="tooltip" data-placement="bottom" data-html="true" title="Entrée pour lancer la recherche" id="input_'+nbElem+'" class="input_'+nbElem+'" type="text" placeholder="'+title+'" />');
	        }else{
	        	$(this).html( '<input data-toggle="tooltip" data-placement="bottom" data-html="true" title="Recherche sur une partie du texte saisit (Touche entrée pour exécuter la recherche)" class="input_'+nbElem+'" type="text" placeholder="'+title+'" />' );
	        }
    	}
    } );

    // Declare la fonction de recherche pour chaque input/combo
    table.columns().eq( 0 ).each( function ( colIdx ) {
    	
    	if (colIdx==3){

    		// Vérifie qu'un nombre est saisit + copier/coller + déplacements du curseur
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

    		// Input colonne n°4
	        $( 'input', table.column( colIdx ).header() ).keyup( function(e) {
	        	// Touche entrée
	        	if (e.keyCode==13){
		        	// Déclenche la maj seulement si une valeur de stock est indiquée
		        	if ($( 'input', table.column( colIdx ).header() ).val()!='') {
			            table
			                .column( colIdx )
			                .search( $( 'select', table.column( colIdx ).header() ).val() + this.value ) // Filtre + valeur
			                .draw();
		            }else{
			            table
			                .column( colIdx )
			                .search('')
			                .draw();
		            }
	        	}
	        });

	    	// Combo colonne n°4
	        $( 'select', table.column( colIdx ).header() ).on('change', function () {
	        	// Déclenche la maj seulement si une valeur de stock est indiquée
	        	if ($( 'input', table.column( colIdx ).header() ).val()!='') {
		            table
		                .column( colIdx )
		                .search( this.value + $( 'input', table.column( colIdx ).header() ).val() ) // Filtre + valeur
		                .draw();
	            }else{
		            table
		                .column( colIdx )
		                .search('')
		                .draw();
	            }
	        });

    	} else {

    		if (colIdx!=0 || colIdx!=4){
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
    	}
    } );

    // Combo type d'article
    <?php
    	if ($prodtypeprix==true){
    ?>
    		$('#combobar').append('<label>Type :<select data-toggle="tooltip" data-placement="bottom" data-html="true" title="Liste des types disponibles (* => tous)" class="form-control input-sm combo" id="comboType"><?php echo $optVal;?></select></label>');    
    <?php
    	}else{
    ?>
    		typeVisible=false;
    <?php
    	}
    ?>
	// Ajout des combos de recherche
    $('#combobar').append('&nbsp;<label>Statut :<select data-toggle="tooltip" data-placement="bottom" data-html="true" title="Statut des articles" class="form-control input-sm combo" id="comboSupr"><option value="0">Non supprimés</option><option value="1">Supprimés</option><option value="*">Indifférent</option></select></label>');
    $('#combobar').append('&nbsp;<label>Stock : <select data-toggle="tooltip" data-placement="bottom" data-html="true" title="Type de stock" class="form-control input-sm combo" id="comboStock"><option value="1">En stock</option><option value="0">Pas en stock</option><option value="*">Indifférent</option></select></label>');
    $('#combobar').append('&nbsp;<label>Poids :&nbsp&nbsp<select data-toggle="tooltip" data-placement="bottom" data-html="true" title="Filtrer sur le poids" class="form-control input-sm combo" id="comboPoids"><option value="1">Supérieur à 0</option><option value="0">Egal à 0</option><option value="*">Indifférent</option></select></label>');    
    $('#combobar').append('&nbsp;<label>Catégories :<select data-toggle="tooltip" data-placement="bottom" data-html="true" title="Liste des catégories" class="form-control input-sm combo" id="comboCat"><?php echo $optCat;?></select></label>');
    $('.dt-buttons').append('<a id="reset" class="dt-button buttons-excel buttons-html5" tabindex="0" aria-controls="prodliste" href="#"><span data-toggle="tooltip" data-placement="bottom" data-html="true" title="Annule tous les filtres et sélections"> RAZ filtres</span></a>');

	// Un peu de CSS sur les combos
	$('#comboType').css("width","50px");
	$('#comboSupr').css("width","130px");
	$('#comboStock').css("width","127px");
	$('#comboCat').css("width","575px");
	$('#comboPoids').css("width","120px");
	$('select[name=prodliste_length]').css("width","128px");
	// Pour les inputs dans l'en-tete de la table
	$('table.dataTable thead th').css('padding','6px');
	// Surcharge le CSS original pour que les boutons (Excel, Print, RAZ) soient à de même hauteur que les combos avec un espacement différent
	$('.button.dt-button, div.dt-button, a.dt-button').css({"padding":"0.41em 1em","margin-right":"0.33em"});

	// Changement dans une des combos de recherche => demande du chargement de la table
	$("#comboType , #comboSupr , #comboStock, #comboCat, #comboPoids").change( function() {
		// Message d'attente
		$('#infoRequest').html('Recherche en cours ...');
		// Mémorise les sélections
	    cbType= $("#comboType").val();
        cbSupr= $("#comboSupr").val();
        cbStk = $("#comboStock").val();
        cbCat = $("#comboCat").val();
        cbPoids=$("#comboPoids").val();
        taille= $("select[name=prodliste_length]").val();
		// Demande les données
		table.draw();
	});

 	// Restaure les combos et les inputs aux valeurs par défaut
	$('#reset').click( function() {
		// Valeurs par défaut
    	cbType ='*';
	    cbSupr ='*';
	    cbStk  ='*';
	    cbCat  ='*';
	    cbPoids='*';
	    taille =10;
		// Message d'attente
		$('#infoRequest').html('Chargement des données aux valeurs par défaut...');
		// 10 lignes affichées par défaut
		$("select[name=prodliste_length]").val(taille);
		// Sélection par défaut=4
		$('#comboType').val(cbType);
		// Sélection par défaut=non supprimés
		$('#comboSupr').val(cbSupr);
		// Sélection par défaut=en stock
		$('#comboStock').val(cbStk);
		// Sélection par défaut
		$('#comboFiltre').val('=');
		// Sélection par défaut=toutes catégories
		$('#comboCat').val(cbCat);
		// Sélection poids connu
		$('#comboPoids').val(cbPoids);
		// Vide tous les inputs de recherche (en-tête de colonnes)
		table.columns().eq( 0 ).each( function ( colIdx ) {
			// Passe la colonne qui contient les boutons et celle du prix (pas d'input)
			if (colIdx!=0 || colIdx!=4){
        		$('input', table.column( colIdx ).header()).val('');
        	}
    	} );
    	// Aucune valeur de recherche en cours dans les colonnes (pour la requete AJAX)
		table.column(1).search(''),
		table.column(2).search(''),
		table.column(3).search(''),
		table.page.len(taille),
		// Charge la table aux valeurs fixées par défaut
		table.draw();
	});

	// Clic sur un bouton de détails
    $('#prodliste tbody').on( 'click', 'button', function () {
    	// Désactive temporairement le bouton
    	$(this.id).attr('disabled','disabled');
    	// Récupère les infos de la ligne courante
        var data = table.row( $(this).parents('tr') ).data();
        var id   = data['prod_id'];	// id unique pour la requete AJAX
        var ean13= data['prod_ean'];
        // Requete AJAX pour avoir les détails
		$.ajax( {
			url		: '../includes/hotlineDetailsProduct.php?prod_id='+id,
			method	: 'GET',
			cache	: false
			} )
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
    } );

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

	// renvoie la recherche en cours
	function addMessage( bTypeVisible ) {
    	var type;
    	var categorie;
    	var stock;
    	var poids;
    	var statut;
    	// Selon le type
    	if (bTypeVisible==true){
        	if ( $("#comboType").val()!='*' ){
        		type='Articles de type '+$("#comboType option:selected").text().toLowerCase()+', ';
        	}else{
        		type="Tous les types d'articles, ";
        	}
    	} else {
    		type='Articles ';
    	}
    	// Selon le statut
    	if ( $("#comboSupr").val()!='*' ){
    		statut=$("#comboSupr option:selected").text().toLowerCase()+', ';
    	} else{
    		statut='tous status, ';
    	}
		// Selon la catégorie sélectionnée
		if ($("#comboCat").val()!='*'){
			categorie=' catégorie '+$("#comboCat").val()+'.';
		}else{
			categorie=' toutes catégories confondues.';
		}
		// selon le type de stock
		switch ( $("#comboStock").val() ) {
			case '0' :
				stock=' pas en stock,';
				break;
			case '1' :
				stock=' en stock,';
				break;
			case '*' :
				stock=' tous stock confondus,';
				break;
		}
		// Selon le poids recherché
		switch ( $("#comboPoids").val() ) {
		   	case '0' :
		      	poids=" ayant un poids égal à zéro,";
		      	break;
		   	case '1' :
		      	poids=" ayant un poids supérieur à zéro,";
		      	break;
		   	case '*' :
		      	poids=" quel que soit le poids,";
		      	break;
		}
    	return type+statut+stock+poids+categorie;
	}

});	
</script>
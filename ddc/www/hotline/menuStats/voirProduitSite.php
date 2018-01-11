<?php
/// CLASS INFO ///
require_once "../includes/hotlineHeader.php";
require_once "../includes/hotlineFunctions.php";
?>

<h2>Interroger un code EAN</h2>
<p></p>          

<form method="post">
	<div class="row">
	  <div class="col-md-8">
	  	<input type="text" id="rechercheEan" class="form-control" name="ean" value="" autocomplete="off"/>
	  	<div id="collapse1" class="panel-collapse collapse in">
	  		<div id="resultat"></div>
	  	</div>
	  </div>
	  <div class="col-md-4">
	  	<button type="submit" class="btn btn-primary">Rechercher</button>
	  </div>
	  
	</div>
</form>

<?php

require_once "../includes/hotlineFooter.php";

	function format_date( $date ) {
		$date = explode("-", $date);
		$date = ($date[1])."/".($date[2])."/".($date[0]);
		return $date;
	}

	function afficheInput($var1, $val1) {
		echo '<div class="form-group row">
				<div class="col-md-6">
					<label for="'.$var1.'" class="col-sm-3 col-form-label">'.$var1.'</label>
					<div class="col-sm-9">
				  		<input type="text" id="rechercheEan" class="form-control" 
				  			name="'.$var1.'" value="'.$val1.'"/>
				  	</div>
				</div>
			  </div>';
	}

	function afficheDeuxInput($var1, $val1, $var2, $val2) {
		echo '<div class="form-group row">
				<div class="col-md-6">
					<label for="'.$var1.'" class="col-sm-3 col-form-label">'.$var1.'</label>
					<div class="col-sm-9">
				  		<input type="text" id="rechercheEan" class="form-control" 
				  			name="'.$var1.'" value="'.$val1.'"/>
				  	</div>
				</div>
				<div class="col-md-6">
					<label for="'.$var2.'" class="col-sm-3 col-form-label">'.$var2.'</label>
					<div class="col-sm-9">
				  		<input type="text" id="rechercheEan" class="form-control" 
				  			name="'.$var2.'" value="'.$val2.'"/>
				  	</div>
				</div>
			  </div>';
	}

	$INFO = new Info();
	if(!$INFO->Connect()) return false;

	if(isset($_POST) && !empty($_POST) && !empty($_POST["ean"])) {

		$ean = explode(' - ',$_POST["ean"]);

	    $req = "
	    		SELECT 
	    		*
	    		FROM
	    		produits
	    		LEFT JOIN stock ON prod_ean = stock_ean
	    		LEFT JOIN disponibilite ON prod_dispo = dispo_id
	    		WHERE
	    		prod_ean = \"".$ean[0]."\"
	          ";

	    if(!$INFO->Query($req)) {
	      echo "Erreur :".$INFO->erreur;
	      return false;
	    }
	    $nb  = $INFO->Num();
	    $ROW = $INFO->Result();

	    echo "<div>&nbsp;</div>";

	    if($nb > 0) {

	    	if($ROW["prod_dispo"] == "1" && $ROW["stock_qte"] > 0) {
	    		echo '<div class="alert alert-success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Le produit est disponible et en stock !</div>';
	    	}
	    	if($ROW["prod_poids"] <= 0) {
	    		echo '<div class="alert alert-warning"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Attention : le poids de cette fiche est vide, il est possible qu\'il ne s\'affiche pas sur le site !</div>';
	    	}
	    	if($ROW["prod_dispo"] > 1 && $ROW["stock_qte"] <= 0) {
	    		echo '<div class="alert alert-warning"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Attention au code de disponibilité de la fiche produit !</div>';
	    	}

	    	echo "<h2>Fiche produit</h2><div>&nbsp;</div>";
	    	afficheInput("Code EAN 13", $ROW["prod_ean"]);
	    	afficheInput("Titre", $ROW["prod_titre"]);
	    	afficheDeuxInput("Auteur", $ROW["prod_auteurs"], "Editeur", $ROW["prod_editeur"]);

	    	echo "<h3>Détail</h3><div>&nbsp;</div>";
	    	afficheDeuxInput("Distributeur", $ROW["prod_glndistrib"], "", $ROW["prod_namedistrib"]);
	    	afficheInput("Collection", $ROW["prod_collection"]);

	    	// disponibilite
	    	if(isset($ROW["stock_qte"]) && $ROW["stock_qte"] > 0) {
	    		if($ROW["prod_dispo"] == 1)
	    			$ROW["dispo_libelle"] = "Disponible (En stock)";
	    		else
	    			$ROW["dispo_libelle"] .= " (En stock)";
	    	}

	    	afficheDeuxInput("Code dispo", $ROW["prod_dispo"], "", $ROW["dispo_libelle"]);
	    	afficheInput("Stock", $ROW["stock_qte"]. " produit(s) en stock");

	    	afficheInput("Date", format_date($ROW["prod_parution"]));
	    	
	    }
	    else {
	    	echo "Produit introuvable !";
	    }
	}
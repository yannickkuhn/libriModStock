<?php

require_once "../includes/hotlineHeader.php";
require_once "../includes/hotlineFunctions.php";
?>
<script type="text/javascript">
    $(document).ready(function() {
    	var table = madatatable();
    	table.order( [[ 2, "asc" ]] )
    		 .draw();
    } );
</script>

<?php 

  $INFO = new Info();
  if(!$INFO->Connect()) return false;

  // 2/ Activer/Désactiver
  // -----------------------------
  if(isset($_POST) && !empty($_POST) && !empty($_POST["enable"])) {

    //var_dump($_POST);

    if($_POST["enable"] == "disable")
      $_POST["enable"] = 0;
    else if($_POST["enable"] == "enable")
      $_POST["enable"] = 1; 

    $req = "UPDATE assoc_paiement_expedition
          SET ape_actif = \"".$_POST["enable"]."\"
          WHERE ape_id=\"".$_POST["apeid"]."\"
          ";

    if(!$INFO->Query($req)) {
      echo "Erreur :".$INFO->erreur;
      return false;
    }

    echo '<div class="alert alert-success">
    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Statut de l\'ACL '.$_POST["apeid"].' modifié avec succès - OK
    </div>';

    // suppression des données envoyées (rafraichissement)
    $_POST = array();
  }

  // 3/ recherche des blocs (pour l'affichage)
  // ---------------------------------------------

  $blocs = array();
  $req = "SELECT * 
          FROM `assoc_paiement_expedition`, expedition, paiement 
          WHERE `ape_expd_fixid`=expd_fixid
          AND `ape_pai_fixid`=pai_fixid
          AND `ape_typeCli`=0       
          AND `ape_TypeProd`=0
          AND `ape_statut_id`=0
          LIMIT 0, 1000
          ";

  if(!$INFO->Query($req)) {
    echo "Erreur :".$INFO->erreur;
    return false;
  }
  $nb  = $INFO->Num();
  $i = 0;
  while($ROW = $INFO->Result()) {
    $blocs[$i] = $ROW;
    $i++;
  }

  // Déconnexion MySQL
  $INFO->Close();
?>

<h2>Pour les produits physiques</h2>
<p></p>            
<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
      <tr>
        <td>Statut du client</td>
        <td>Type de Paiement</td>
        <td>Moyen d'Expedition</td>
        <td>Activer/Inactif</td>
      </tr>
    </thead>
    <tbody>
      <?php foreach($blocs as $bloc) { ?>
        <tr>
          <?php extract($bloc); ?>
          <?php if(!isset($ape_actif)) $ape_actif = false; ?>
          <td><?php echo urldecode($ape_statut_id)?"Etranger":"Particulier" ?></td>
          <td><?php echo urldecode($pai_libelle) ?></td>
          <td><?php echo urldecode($expd_libelle) ?></td>
          <td> 
            <form method="post">
              <input type="hidden" name="apeid" value="<?php echo $ape_id; ?>"/>
              <input type="hidden" name="enable" value="<?php echo $ape_actif?"disable":"enable"; ?>"/>
              <button type="submit" class="btn btn-primary"><span class="glyphicon <?php echo (bool)$ape_actif?"glyphicon-eye-close":"glyphicon-eye-open"; ?>" aria-hidden="true"></span> - <?php echo (bool)$ape_actif?"Désactiver":"Activer"; ?></button>
            </form>
             
          </td>
        </tr>
      <?php } ?>
    </tbody>
</table>

<?php require_once "../includes/hotlineFooter.php"; ?>


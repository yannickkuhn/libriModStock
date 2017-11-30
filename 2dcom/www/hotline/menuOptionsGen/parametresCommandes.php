<?php
require_once "../includes/hotlineHeader.php";
require_once "../includes/hotlineFunctions.php";
?>
<script type="text/javascript">
    $(document).ready(function() {
    	var table = madatatable();
    	table.order( [[ 2, "desc" ]] )
    		 .draw();
    } );
</script>

<?php 

  $INFO = new Info();
  if(!$INFO->Connect()) return false;

  // 1/ réactivation des commandes
  // -----------------------------

  if(isset($_POST) && !empty($_POST) && !empty($_POST["idcmd"])) {
     $req = "UPDATE commande_entetes
          SET cmd_ent_valid = \"1\", cmd_ent_pai_statut = \"1\" 
          WHERE cmd_ent_id=\"".$_POST["idcmd"]."\"
          ";

    if(!$INFO->Query($req)) {
      echo "Erreur :".$INFO->erreur;
      return false;
    }

    echo '<div class="alert alert-success">
    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Commande '.$_POST["idcmd"].' réactivée avec succès - OK
    </div>';

    // suppression des données envoyées (rafraichissement)
    $_POST = array();
  }

  // 2/ recherche des commandes (pour l'affichage)
  // ---------------------------------------------

  $commandes = array();
  $req = "SELECT 
          *
          FROM commande_entetes
          WHERE cmd_ent_valid = \"0\"
          AND cmd_ent_pai_statut = \"0\"
          LIMIT 0, 1000
          ";

  if(!$INFO->Query($req)) {
    echo "Erreur :".$INFO->erreur;
    return false;
  }
  $nb  = $INFO->Num();
  $i = 0;
  while($ROW = $INFO->Result()) {
    $commandes[$i] = $ROW;
    $i++;
  }

  //var_dump($commandes);

  function formatNb($nb) {
    setlocale(LC_MONETARY, 'fr_FR');
    if (function_exists('money_format'))
      return money_format('%!n &euro;', $nb); // 1 234,57 €
    else
      return  number_format($nb, 2, ',', ' ')." €";
  }

  // Déconnexion MySQL
  $INFO->Close();
?>

<h2>Réactivation des commandes</h2>
<p></p>            
<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
      <tr>
        <td>Commande numéro</td>
        <td>Client concerné</td>
        <td>Date de la commande</td>
        <td>Montant TTC</td>
        <td>Réactivation</td>
      </tr>
    </thead>
    <tbody>
      <?php foreach($commandes as $commande) { ?>
        <tr>
          <?php extract($commande); ?>
          <td><?php echo $cmd_ent_id; ?></td>
          <td><?php echo urldecode($cmd_ent_billingNom)." ".urldecode($cmd_ent_billingPrenom); ?></td>
          <td><?php echo $cmd_ent_date." à ".$cmd_ent_heure; ?></td>
          <td><?php echo formatNb($cmd_ent_montant_hfp+$cmd_ent_montant_fp); ?></td>
          <td><?php echo fenetreModale($cmd_ent_id, $cmd_ent_billingNom." ".$cmd_ent_billingPrenom); ?></td>
        </tr>
      <?php } ?>
    </tbody>
</table>

<?php require_once "../includes/hotlineFooter.php"; ?>


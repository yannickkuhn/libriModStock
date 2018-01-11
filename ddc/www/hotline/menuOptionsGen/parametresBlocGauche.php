<?php


require_once "../includes/hotlineHeader.php";
require_once "../includes/hotlineFunctions.php";
?>
<script type="text/javascript">
    $(document).ready(function() {
    	var table = madatatable();
    	table.order( [[ 1, "asc" ]] )
    		 .draw();
    } );
</script>

<?php 

  $INFO = new Info();
  if(!$INFO->Connect()) return false;

  // 1/ recherche des blocs (pour modifier les ordres)
  // ---------------------------------------------

  $blocs = array();
  $req = "SELECT 
          *
          FROM blocs_gauche
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

  // 2/ Activer/Désactiver
  // -----------------------------
  if(isset($_POST) && !empty($_POST) && !empty($_POST["enable"])) {

    //var_dump($_POST);

    if($_POST["enable"] == "disable")
      $_POST["enable"] = 0;
    else if($_POST["enable"] == "enable")
      $_POST["enable"] = 1; 

    $req = "UPDATE blocs_gauche
          SET bg_display = \"".$_POST["enable"]."\"
          WHERE bg_id=\"".$_POST["blocid"]."\"
          ";

    if(!$INFO->Query($req)) {
      echo "Erreur :".$INFO->erreur;
      return false;
    }

    echo '<div class="alert alert-success">
    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Statut du bloc '.$_POST["bg_id"].' modifié avec succès - OK
    </div>';

    // suppression des données envoyées (rafraichissement)
    $_POST = array();
  }


  // 2/ ordre des blocs
  // -----------------------------

  if(isset($_POST) && !empty($_POST) && !empty($_POST["updown"])) {
     //var_dump($_POST);
     $blocid = $_POST["blocid"];
     $bgordre = $_POST["ordre"];

     //var_dump($blocs);

     if($_POST["updown"] == "up") {
      if($bgordre > 1) {
        ## Monter le bloc concerné
        $req = "UPDATE blocs_gauche
          SET bg_ordre = $bgordre-1
          WHERE bg_id=\"$blocid\"
          ";
        //echo $req;
        if(!$INFO->Query($req)) {
          echo "Erreur :".$INFO->erreur;
          return false;
        }
        ## Descendre le bloc du dessus
        ## -----------------------------
        # 1/Chercher le bloc du dessus
        foreach($blocs as $bloc) {
          if($bloc["bg_ordre"] == $bgordre-1) {
            $blociddessus = $bloc["bg_id"];
          }
        }
        # 2/Mettre à jour le bloc du dessus
        $req = "UPDATE blocs_gauche
          SET bg_ordre = \"".$bgordre."\"
          WHERE bg_id=\"".$blociddessus."\"
          ";
        //echo $req;
        if(!$INFO->Query($req)) {
          echo "Erreur :".$INFO->erreur;
          return false;
        }
      }
     }

     if($_POST["updown"] == "down") {
      if($bgordre < $nb) {
        ## Descendre le bloc concerné
        $req = "UPDATE blocs_gauche
          SET bg_ordre = $bgordre+1
          WHERE bg_id=\"$blocid\"
          ";
        //echo $req;
        if(!$INFO->Query($req)) {
          echo "Erreur :".$INFO->erreur;
          return false;
        }
        ## Monter le bloc du dessous
        ## -----------------------------
        # 1/Chercher le bloc du dessous
        foreach($blocs as $bloc) {
          if($bloc["bg_ordre"] == $bgordre+1) {
            $blociddessous = $bloc["bg_id"];
          }
        }
        # 2/Mettre à jour le bloc du dessus
        $req = "UPDATE blocs_gauche
          SET bg_ordre = \"".$bgordre."\"
          WHERE bg_id=\"".$blociddessous."\"
          ";
        //echo $req;
        if(!$INFO->Query($req)) {
          echo "Erreur :".$INFO->erreur;
          return false;
        }
      }
     }   
  }

  // 3/ recherche des blocs (pour l'affichage)
  // ---------------------------------------------

  $blocs = array();
  $req = "SELECT 
          *
          FROM blocs_gauche
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

<h2>Modifier l'ordre/l'affichage des blocs de gauche</h2>
<p></p>            
<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
      <tr>
        <td>Nom du bloc</td>
        <td>Ordre</td>
        <td>Remonter</td>
        <td>Descendre</td>
        <td>Activer/Inactif</td>
      </tr>
    </thead>
    <tbody>
      <?php foreach($blocs as $bloc) { ?>
        <tr>
          <?php extract($bloc); ?>
          <td><?php echo urldecode($bg_nom) ?></td>
          <td><?php echo $bg_ordre; ?></td>
          <td>
            <form method="post">
              <input type="hidden" name="blocid" value="<?php echo $bg_id; ?>"/>
              <input type="hidden" name="ordre" value="<?php echo $bg_ordre; ?>"/>
              <input type="hidden" name="updown" value="up"/>
              <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-up"></span></button>
            </form>
          </td>
          <td>
            <form method="post">
              <input type="hidden" name="blocid" value="<?php echo $bg_id; ?>"/>
              <input type="hidden" name="ordre" value="<?php echo $bg_ordre; ?>"/>
              <input type="hidden" name="updown" value="down"/>
              <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-down"></span></button>
            </form>
          </td>
          <td> 
            <form method="post">
              <input type="hidden" name="blocid" value="<?php echo $bg_id; ?>"/>
              <input type="hidden" name="enable" value="<?php echo $bg_display?"disable":"enable"; ?>"/>
              <button type="submit" class="btn btn-primary"><span class="glyphicon <?php echo (bool)$bg_display?"glyphicon-eye-close":"glyphicon-eye-open"; ?>" aria-hidden="true"></span> - <?php echo (bool)$bg_display?"Désactiver":"Activer"; ?></button>
            </form>
             
          </td>
        </tr>
      <?php } ?>
    </tbody>
</table>

<?php require_once "../includes/hotlineFooter.php"; ?>


<?php
require_once "../includes/hotlineHeader.php";
require_once "../includes/hotlineFunctions.php";
?>

<script type="text/javascript">
    $(document).ready(function() {
      var table = madatatable();
    } );
</script>

<?php 

  $INFO = new Info();
  if(!$INFO->Connect()) return false;

  // 1/ modification du paramètre en BDD
  // -----------------------------------

  //var_dump($_POST);

  if(isset($_POST) && !empty($_POST) && !empty($_POST["idparam"])) {
     $req = "UPDATE systeme
             SET sys_value = \"".$_POST["valueparam"]."\", sys_datemodif = CURRENT_TIMESTAMP, sys_operator = \"HOTLINE\"
             WHERE sys_id=\"".$_POST["idparam"]."\"
            ";
     //echo $req;

    if(!$INFO->Query($req)) {
      echo "Erreur :".$INFO->erreur;
      return false;
    }

    echo '<div class="alert alert-success">
    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Paramètre '.$_POST["nameparam"].' modifié avec succès - OK
    </div>';

    // suppression des données envoyées (rafraichissement)
    $_POST = array();
  }

  // 2/ recherche des parametres (pour l'affichage)
  // ---------------------------------------------

  $parametres = array();
  $req = "SELECT 
          *
          FROM systeme
          LIMIT 0, 1000
          ";

  if(!$INFO->Query($req)) {
    echo "Erreur :".$INFO->erreur;
    return false;
  }
  $nb  = $INFO->Num();
  $i = 0;
  while($ROW = $INFO->Result()) {
    $parametres[$i] = $ROW;
    $i++;
  }



  // Déconnexion MySQL
  $INFO->Close();
?>

<h2>Modification des paramètres en base</h2>
<p></p>            
<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
      <tr>
        <td>Nom du paramètre</td>
        <td>Valeur</td>
        <td>Type</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach($parametres as $parametre) { ?>
        <tr>
          <?php extract($parametre); ?>
          <td><?php echo $sys_name; ?></td>
          <td class="sys_value"><?php echo $sys_value; ?></td>
          <td><?php echo $sys_assoc; ?></td>
          <td>
          	<div class="col-md-1 text-right">
            	<?php echo modifParametre($sys_id, $sys_name, $sys_value); ?>
            </div>
          </td>
          <td><span style="font-size:1.5em; text-align: left;" rel="tooltip" class="btn btn-secondary glyphicon glyphicon-question-sign tooltip_<?php echo $sys_id; ?>" data-toggle="tooltip" data-placement="left" data-original-title="<?php echo htmlspecialchars($sys_helper); ?>"></span></td>
          <td><a href="editTooltip.php?id=<?php echo $sys_id; ?>" class="btn btn-warning glyphicon glyphicon-cog btn-sm"></button></td>
        </tr>
        <script>
          $("[rel=tooltip]").tooltip({html:true});
        </script>
      <?php } ?>	
    </tbody>
</table>

<?php require_once "../includes/hotlineFooter.php"; ?>

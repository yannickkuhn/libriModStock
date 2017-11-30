<?php
require_once "../includes/hotlineHeader.php";
require_once "../includes/hotlineFunctions.php";
?>

<?php
	$INFO = new Info();
  if(!$INFO->Connect()) return false;

  // 1/ modification du paramètre en BDD
  // -----------------------------------

  //var_dump($_POST);

  if(isset($_POST) && !empty($_POST)) {
     $req = "UPDATE systeme
             SET sys_helper = \"".addslashes($_POST["lg_sys_helper"])."\", sys_datemodif = CURRENT_TIMESTAMP, sys_operator = \"HOTLINE\"
             WHERE sys_id=\"".$_POST["lg_sys_id"]."\"
            ";
     //echo $req;

    if(!$INFO->Query($req)) {
      echo "Erreur :".$INFO->erreur;
      return false;
    }

    echo '<div class="alert alert-success">
    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Paramètre '.$_POST["lg_sys_name"].' modifié avec succès - OK
    </div>';

    // suppression des données envoyées (rafraichissement)
    $_POST = array();
  }

  // 2/ recherche des parametres (pour l'affichage)
  // ---------------------------------------------

  $parametre = array();
  $req = "SELECT 
          *
          FROM systeme
          WHERE sys_id = \"".$_GET["id"]."\"
          LIMIT 0, 1000
          ";

  if(!$INFO->Query($req)) {
    echo "Erreur :".$INFO->erreur;
    return false;
  }
  $nb  = $INFO->Num();
  $i = 0;
  if($ROW = $INFO->Result()) {
    $parametre = $ROW;
    $i++;
  }



  // Déconnexion MySQL
  $INFO->Close();
?>

 <form method="post">
  <div class="form-group">

  	<input type="hidden" name="lg_sys_id" value="<?php echo $_GET["id"]; ?>">

    <label for="lg_sys_name">Nom du paramètre:</label>
    <input type="input" class="form-control" id="lg_sys_name" 
    		name="lg_sys_name" value="<?php echo $parametre["sys_name"]; ?>">
  </div>
  <div class="form-group">

    <label for="lg_sys_name">Valeur du paramètre:</label>
    <input type="input" class="form-control" id="lg_sys_value" 
        name="lg_sys_value" value="<?php echo htmlspecialchars($parametre["sys_value"]); ?>" disabled>
  </div>
  <div class="form-group">
    <label for="pwd">Aide (à saisir):</label>
    <textarea type="input" class="form-control" id="lg_sys_helper" name="lg_sys_helper" rows="30"><?php 
    	if(isset($parametre["sys_helper"]) && !empty($parametre["sys_helper"])) { echo $parametre["sys_helper"]; } 
    	else { echo "<h2>Descriptif</h2><p></p><h2>Notes</h2><p></p>"; }
    ?></textarea>
  </div>
  <!--<div class="checkbox">
    <label><input type="checkbox"> Remember me</label>
  </div>-->
  <button type="submit" class="btn btn-default">Mettre à jour</button>
</form>

<script>
	CKEDITOR.replace('lg_sys_helper', {
		height: '350px'
	});
</script>

<?php require_once "../includes/hotlineFooter.php"; ?>
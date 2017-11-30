<?php

  // ------
  // cette fonction sert pour l'identification
  // général de cet espace superAdmin 
  // ------
  function identification() {
    if(isset($_POST["lg_deconnexion"]) && $_POST["lg_deconnexion"] == 1) {
      unset($_SESSION["identification"]);
    }
    if(isset($_SESSION["identification"]) && $_SESSION["identification"] == 1) {
      return 1;
    }
    else {
      header("Location:../includes/hotlineIdentification.php");
      return 0;
    }
  }

  // ------
  // cette fonction sert pour le fichier
  // modifParametresBDD.php pour éditer la valeur d'un
  // paramètre dans la table système de la base
  // de données
  // ------
  function modifParametre($idparam, $nameparam, $valueparam) {
    echo '<button type="button" class="btn btn-primary glyphicon glyphicon-pencil btn-sm" data-toggle="modal" data-target="#myModal_'.$idparam.'"></button>
          <div class="modal fade" id="myModal_'.$idparam.'" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title">Confirmation</h4>
                </div>
                <form method="post">
                  <div class="modal-body">
                    <p>Valeur de modification du paramètre "'.$nameparam.'" ?</p>
                    <input type="hidden" name="nameparam" value="'.$nameparam.'"/>
                    <input type="text" name="valueparam" value="'.$valueparam.'"/>
                  </div>
                  <div class="modal-footer">
                      <input type="hidden" name="idparam" value="'.$idparam.'"/>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      <input type="submit" class="btn btn-primary" value="Modifier"/>
                    </div>
                </form>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
        </div>';
  }


  // ------
  // cette fonction sert pour le fichier
  // reactiverCommandes.php pour confirmer 
  // la réactivation d'une commande
  // ------
  function fenetreModale($cmd_ent_id, $cmd_ent_nomprenom) {
    echo '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal_'.$cmd_ent_id.'">Réactiver</button>
          <div class="modal fade" id="myModal_'.$cmd_ent_id.'" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title">Confirmation</h4>
                </div>
                <form method="post">
                  <div class="modal-body">
                    <p>Confirmez-vous la réactivation de la commande "'.$cmd_ent_id.'" au nom de "'.$cmd_ent_nomprenom.'" ?</p>
                  </div>
                  <div class="modal-footer">
                      <input type="hidden" name="idcmd" value="'.$cmd_ent_id.'"/>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      <input type="submit" class="btn btn-primary" value="Confirmer la réactivation"/>
                    </div>
                </form>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
        </div>';
  }
?>

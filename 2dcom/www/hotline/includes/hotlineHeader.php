<?php 
  session_start();
  require_once __DIR__."/../../require/class_new_info.php";
  require_once __DIR__."/../includes/hotlineFunctions.php"; 
  identification();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Outil Hotline</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
  <style type="text/css">
    .tooltip-inner {
      max-width: 700px !important;
      text-align: left !important;
      line-height:18px !important;
        /*white-space: nowrap;
        word-break: break-all;*/
      }
  </style>
  <style type="text/css">
    body {
      padding-top: 20px;
      padding-bottom: 20px;
    }
    .navbar {
      margin-bottom: 20px;
    }
    h1 {
      margin-bottom: 30px;
    }
    h2 {
      margin-bottom: 40px;
    }
    h3 {
      margin-bottom: 30px;
      margin-top: 30px;
    }
    p {
      margin-top: 40px;
    }
    .sys_value {
      /* These are technically the same, but use both */
      overflow-wrap: break-word;
      word-wrap: break-word;

      -ms-word-break: break-all;
      /* This is the dangerous one in WebKit, as it breaks things wherever */
      word-break: break-all;
      /* Instead use this non-standard one: */
      word-break: break-word;

      /* Adds a hyphen where the word breaks, if supported (No Blink) */
      -ms-hyphens: auto;
      -moz-hyphens: auto;
      -webkit-hyphens: auto;
      hyphens: auto;
  }
  /*
  Spécifique pour la table des articles
  */
  .col_ean, .col_prixttc, .col_stock, .col_reponse{
    text-align: center;
  }
  .col_ean {
    width: 100px;
  }
  .dataTables_filter {
    /*Masque l'input de recherche*/
    display: none;
  }
  .sorting_disabled {
    /*Centrage de l'input de recherche dans la colonne*/
    text-align: center;
  }
  .cmd_1{
    text-align: center;
    width: 115px;
  }
  .cmd_2, .cmd_3 {
    width: 30px;
    text-align: center;
  }
  .cmd_4{
    width: 100%;
  }
  .cmd_5{
    width: 60px;
    text-align: center;
  }
  .cmd_6, .cmd_7{
    width: 95px;
    text-align: center;
  }
  .input_2{
    /*Input de recherche EAN13*/
    width: 100%;
  }
  .input_3{
    /*Input de recherche titre*/
    width: 100%;
  }
  .input_4{
    /*Input de recherche stock*/
    width: 50px;
  }
  .select_2,.select_3{
    /*Combo de recherche stock*/
    height: 26px;
  }
  .select_4{
    /*Combo de recherche stock*/
    width: 40px;
    height: 26px;
  }
  .popup_erreur{
    color:red;
  }
  .bt_details {
    width: auto;
    display: inline;
    text-align: center;
    color: #337ab7;
    border: 1px solid #ddd;
    background-color: white;
  }
  .bt_details:hover {
    color: white;
    background-color: #337ab7;
  }
  .bt_delete {
    width: auto;
    display: inline;
    text-align: center;
    color: red;
    border: 1px solid #ddd;
    background-color: white;
  }
  .bt_delete:hover {
    color: white;
    background-color: red;
  }
  </style>
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../bower_components/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../bower_components/datatables.net-buttons-dt/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="../bower_components/tooltipster/dist/css/tooltipster.bundle.min.css" rel="stylesheet">
    <link href="../bower_components/jquery-ui/themes/base/jquery-ui.min.css" rel="stylesheet">
</head>
<body>

<div class="container">

  <!-- Static navbar -->
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">SuperAdmin LIBRIWEB</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="../index.php"><span class="glyphicon" aria-hidden="true"></span> Index</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Options générales <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="../menuOptionsGen/parametresGenerales.php"><span class="glyphicon" aria-hidden="true"></span>Paramétrage système</a></li>
                <li><a href="../menuOptionsGen/parametresBlocGauche.php"><span class="glyphicon" aria-hidden="true"></span>Blocs à gauche (ordre)</a></li>
                <li><a href="../menuOptionsGen/parametresACL.php"><span class="glyphicon" aria-hidden="true"></span>ACL (Moyens de paiements actifs)</a></li>
                <li><a href="../menuOptionsGen/parametresCommandes.php"><span class="glyphicon" aria-hidden="true"></span>Réactiver les commandes WEB</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Statistiques <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="../menuStats/voirProduitsSite.php"><span class="glyphicon" aria-hidden="true"></span>Statistiques produits</a></li>
                <li><a href="../menuStats/voirProduitsOccasions.php"><span class="glyphicon" aria-hidden="true"></span>Statistiques occasions</a></li>
                <li><a href="../menuStats/voirProduitSite.php">Interroger caractéristiques d'un EAN</a></li>
                <li><a href="../menuStats/voirCommande.php">Statistiques commandes</a></li>
              </ul>
            </li>
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tests WEBSERVICE <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="../menuWS/wstest.php?var=0" target="_blank">Récupérer la version (sans SOAP, getVersion)</a></li>
                <li><a href="../menuWS/wstest.php?var=1" target="_blank">Récupérer les commandes (sans SOAP, dlcmd)</a></li>
                <li><a href="../menuWS/wstest.php?var=2" target="_blank">Récupérer les produits (sans SOAP, listall)</a></li>
                <li><a href="../menuWS/wsclitest.php" target="_blank">Mettre à jour la base d'articles (avec SOAP, updatedb)</a></li>
                <li><a href="../menuWS/wsfictest.php">Rechercher un EAN dans UPDATEDB</a></li>
                <li><a href="../menuWS/wsrubtest.php">Statistiques rubriques dans UPDATEDB</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div><!--/.container-fluid -->
    </nav>

    <div class="jumbotron">
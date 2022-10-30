<?php
require_once 'fonctions/bd.php';
require_once 'fonctions/afficher.php';
require_once 'fonctions/compte.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    <title>Mini Pinterest</title>
  </head>
  <body>
    <h1>Détails sur la photo :</h1>
       <?php
        afficherInfosCompte();//Affiche le formulaire de connexion/Déconnexion et le temps de connexion
        afficherDetail();//Affiche les détails d'une photo

        //echo '<pre>'.print_r($_POST,TRUE).'</pre>';

        if(isset($_POST['modifier']))
        {
          formulaireModifierDetail($_POST['modifier']);//Affiche le formulaire pour modifier une photo
        }

        if(isset($_POST['formulaireM']))
        {
          modifierDetail($_POST['photo'], $_POST['description'], $_POST['categorie']);//Modifie les informations de la photo
        }

        if(isset($_POST['supprimer']))
        {
          supprimerDetail($_POST['supprimer']);//Supprime une photo
        }

       ?>
  </body>
</html>

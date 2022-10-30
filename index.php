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
    <h1>Mini Pinterest</h1>
       <?php
       afficherInfosCompte();//Affiche le formulaire de connexion/Déconnexion et le temps de connexion
       SelectionnerCategorie();//Affiche le selecteur de categorie

       if(isset($_POST['ajouter']))
       {
          formulaireAjouterDetail();//Affiche le formulaire d'ajout d'une image
       }

       if(isset($_POST['formulaireA']))
       {
          ajouterDetail($_FILES['photo'], $_POST['description'], $_POST['categorie']);//Ajout d'une image sur le serveur
       }
       
       afficherPhoto();//Affiche les photos du serveur
       ?>
  </body>
</html>

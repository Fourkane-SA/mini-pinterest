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
       <?php
        afficherInfosCompte();//Affiche le formulaire de connexion/Déconnexion et le temps de connexion
        gestionAdmin();//Affiche les informations de la base de donnée pour l'admin
       ?>
  </body>
</html>

<?php

    function SelectionnerCategorie()
    //Fonction pemettant l'affichage d'un menu pour choisir la catégorie
    //de photos à regarder.
    {
        if($_SESSION['logged']!=''){
            echo '<a href="profile.php">Compte</a></br></br>';
        }
        $connexion = getConnexion(); //Récupération de la connexion
        $resultat = mysqli_query($connexion, 'SELECT nomCat FROM categorie;'); //Récupération du nom des catégories

        if($resultat == FALSE)//Si il n'y as aucune catégorie...
        {
            printf("<p>Il n'y a aucune catégorie dans la base.</p>");
        }
        else//Si il y a des catégories...
        {
            echo '<p class = "centrer">Choisisez une catégorie:</p>';
            echo '<form class = "centrer" action="index.php" method=get>';
            echo '<select name="categories" id="select-cat">';
            echo '<option value="">Toutes les images</option>';
            while ($row = mysqli_fetch_assoc($resultat)) //Permet d'énumérer les catégories et de les affichées 
            {
                $nomCat = $row['nomCat'];
                echo '<option value="'.$nomCat.'">'.$nomCat.'</option>';
            }
            echo '</select>';
            echo '<input type="submit" value=ok>';
            echo '</form>';
            echo '</br>';
        }
        if(isset($_SESSION['logged'])){
            if($_SESSION['logged']!=''){
                echo    '<div class = "centrer">
                            <form action="index.php" method="post">
                                <input name="ajouter" type="hidden" value="">
                                <input type="submit" value="Ajouter">
                            </form>
                        </div><br><br>'; //Envoie du formulaire pour le bouton ajouter
            }
        }
    }

    function afficherPhoto()
    //Fonction permettant l'affichage des photos sur la page principale
    {
        $connexion = getConnexion();
        $req = 'SELECT nomFich FROM photo;';//Récupération du nom des photos

        if(isset($_GET['categories']))//Si une catégorie est sélectionnée...
        {
            if($_GET['categories']!=null)//Si le champ catégorie n'est pas nul...
            {
                echo '<h2>Liste des photos de la catégorie '.$_GET['categories'].'</h2>';
                $req = 'SELECT p.nomFich FROM photo p JOIN categorie c ON p.catId = c.catId WHERE c.nomCat = "'.$_GET['categories'].'";';
                //On renvoie toute les photo qui ont la même catégorie que celle demandé par l'utilisateur.
            } 
            else 
            {
                echo '<h2>Liste de toutes les photos</h2>';
            }
        }
        else 
        {
            echo '<h2>Liste de toutes les photos</h2>';
        }

        echo '</br>';
        $resultat = mysqli_query($connexion, $req);//On éxécute la requète

        if($resultat == FALSE) 
        {
            printf("<p>Il n'y a aucune photo dans la base.</p>");
        }
        else 
        {
            echo '<table>';
            while ($row = mysqli_fetch_assoc($resultat)) 
            {
                $nomFich = $row['nomFich'];
                $req2 = 'SELECT c.priver FROM compte c JOIN photo p ON c.idUtilisateur = p.idUtilisateur WHERE p.nomFich = "'.$nomFich.'";';
                //Récupération des photos en privée
                $res = mysqli_query($connexion, $req2);

                while ($row = mysqli_fetch_assoc($res)) 
                {
                    $cptpriver = $row['priver'];
                }

                if(!$cptpriver)
                {
                    echo '<tr>
                            <td>
                                <a href="details.php?image='.$nomFich.'">
                                <img src="Photos/'.$nomFich.' "width = 75% >
                                </a>
                            </td>
                        </tr>';
                    echo '</a>'; //Affichage des photos non privées
                }
            }
        }
    }

    function formulaireAjouterDetail()
    //Formulaire d'ajout de photos
    {
        $connexion = getConnexion();
        $resultat = mysqli_query($connexion, 'SELECT nomCat FROM categorie;');

	    echo '<form class = "centrer" action="index.php" method="post" enctype="multipart/form-data">';
		echo '<p>Photo : <input type="file" name="photo" id="photoUpload" required></p>';
        echo '<p>Description : <input type="text" name="description" required></p>';
        echo '<p>Categorie : <select name="categorie" size="1" required>';
        echo '<option value="">Choisir une categorie</option>';

            while ($row = mysqli_fetch_assoc($resultat)) 
            {
                $nomCat = $row['nomCat'];
                echo '<option value="'.$nomCat.'">'.$nomCat.'</option>';
            }

        echo '</select></p>';
        echo '<p><input type="submit" name="formulaireA" value="Valider"></p>';
	    echo '</form>';    
    }

    function ajouterDetail($photo, $description, $categorie)
    //Fonction pour Ajouter une photo
    {
        $connexion = getConnexion();
        echo '<div class = "centrer">';

        // Vérifie si le fichier a été uploadé sans erreur.

        if($photo["error"] == 0)//Si il n'y pas d'erreur d'upload de la photo
        {
            $valable = true;

            $autorise = array("jpg", "jpeg", "JPEG", "png", "PNG");
            $filename = $photo["name"];
            $filetype = $photo["type"];
            $filesize = $photo["size"];

            // Vérifie l'extension du fichier
            $extension = end(explode('/', $filetype));
            if(!in_array($extension, $autorise))
            {
                $valable = false;
                echo "<p>Erreur : Veuillez sélectionner un format de fichier valide.</p>";
            }

            // Vérifie la taille du fichier - 100Ko maximum
            $maxsize = 100 * 1024;
            if($filesize > $maxsize)
            {
                $valable = false;
                echo "<p>Erreur: La taille du fichier est supérieure à la limite autorisée.</p>";
            }

            $categorieActu = mysqli_query($connexion, 'SELECT nomCat FROM categorie');
            $tabCategorie = array();
            $i=0;
            
            while ($resultat = mysqli_fetch_array($categorieActu ))
            //Boucle permettant d'assigner un numéro de catégorie
            //par rapport au nom de la catégorie
            {
                $tabCategorie[] = $resultat[0];

                if( $tabCategorie[$i] == $categorie) 
                {
                    $idCat=$i+1;
                }
                $i++;
            }

            $idUtilisateur = mysqli_query($connexion, 'SELECT idUtilisateur FROM compte WHERE utilisateur = "'.$_SESSION['logged'].'";');
            $idUtilisateur = mysqli_fetch_assoc($idUtilisateur);
            echo $idUtilisateur['idUtilisateur'];
            if($valable)
            {
                move_uploaded_file($photo['tmp_name'], './Photos/'.$filename); //Déplace la photo du dossier temporaire dans le dossier voulu.
                setlocale (LC_TIME, 'fr_FR.utf8','fra');
                $date = (strftime("%A %d %B %H:%M:%S"));
                $requete = 'INSERT INTO `photo` (`nomFich`, `description`, `catId`, `idUtilisateur`, `heurePublication`)
                            VALUES ("'.$filename.'", "'.$description.'", "'.$idCat.'", "'.$idUtilisateur['idUtilisateur'].'", "'.$date.'")'; //Ajout de la photo dans la bdd

                mysqli_query($connexion, $requete);
            }
        }
    }

    function formulaireModifierDetail($image)
    {
        $connexion = getConnexion();

        $nomPhoto = mysqli_query($connexion, 'SELECT nomFich FROM photo WHERE nomFich = "'.$image.'";'); //Récupération du nom de l'image
        $nomPhotoT = mysqli_fetch_assoc($nomPhoto);

        $descriPhoto = mysqli_query($connexion, 'SELECT `description` FROM photo WHERE nomFich = "'.$image.'";');//Récupération de la description de l'image
        $descriPhotoT = mysqli_fetch_assoc($descriPhoto);

        $resultat = mysqli_query($connexion, 'SELECT nomCat FROM categorie;');

        echo '<p> Nom du fichier : '.$nomPhotoT['nomFich'].'</p>';
        
        echo '<form action="details.php" method="post">';
        echo '<p><input type="hidden" name="photo" value="'.$nomPhotoT['nomFich'].'" required></p>';
        echo '<p>Description : <input type="text" name="description" value ="'.$descriPhotoT['description'].'" required></p>';
        echo '<p>Categorie : <select name="categorie" size="1" required>';
        echo '<option value="">Choisir une categorie</option>';

            while ($row = mysqli_fetch_assoc($resultat)) 
            {
                $nomCat = $row['nomCat'];
                echo '<option value="'.$nomCat.'">'.$nomCat.'</option>';
            }

        echo '</select></p>';
        echo '<p><input type="submit" name="formulaireM" value="Valider"></p>';
        echo '</form>';//Formulaire de modification pour la photo
    }

    function modifierDetail($image, $descri, $categorie)
    {
        $connexion = getConnexion();
        mysqli_query($connexion, 'UPDATE photo SET `description` = "'.$descri.'" WHERE nomFich = "'.$image.'";'); //Changement de la description dans la bdd

        $categorieActu = mysqli_query($connexion, 'SELECT nomCat FROM categorie');
        $tabCategorie = array();
        $i=0;
        
        while ($resultat = mysqli_fetch_array($categorieActu ))
        {
            $tabCategorie[] = $resultat[0];

            if( $tabCategorie[$i] == $categorie) 
            {
                $idCat=$i+1;
            }
            $i++;
        }

        mysqli_query($connexion, 'UPDATE photo SET catId = "'.$idCat.'" WHERE nomFich = "'.$image.'";');//Chnagement de la catégorie de la photo

        header('Location: http://localhost/mini-pinterest/details.php?image='.$image); //retour à la page de la photo
        exit();
    }

    function supprimerDetail($image)
    {
        $connexion = getConnexion();

        $idUtilisateur = mysqli_query($connexion, 'SELECT idUtilisateur FROM compte WHERE utilisateur = "'.$_SESSION['logged'].'";');
        //Récupération de l'id de l'utilisateur.
        $Utilisateur = mysqli_fetch_assoc($idUtilisateur);
        $idPhoto = mysqli_query($connexion, 'SELECT idUtilisateur FROM photo WHERE nomFich = "'.$image.'";');
        //Récupération de l'id de la photo.
        $Photo = mysqli_fetch_assoc($idPhoto);
        
        if($Utilisateur['idUtilisateur'] == $Photo['idUtilisateur'] || $Utilisateur['idUtilisateur'] == 0)
        //Si la photo a été upload par l'utilisateur connecté ou l'utilisateur est l'admin...
        //on supprime la photo
        {
            $nomPhoto = mysqli_query($connexion, 'SELECT nomFich FROM photo WHERE nomFich = "'.$image.'";');
            $nomPhoto = mysqli_fetch_assoc($nomPhoto);
            $nomPhoto = str_replace(' ','',$nomPhoto['nomFich']);

            if( mysqli_query($connexion, 'DELETE FROM photo WHERE nomFich = "'.$nomPhoto.'";'))
            {
                unlink('./Photos/'.$nomPhoto);//On enlève la photo du dossier
                header('Location: http://localhost/mini-pinterest/index.php');//On retourne à l'index
                exit();
            }
        }

        else 
        //sinon on affiche un message d'erreur
        {
            $message='Vous n&aposavez pas les droits pour supprimer cette photo.';
            echo '<script type="text/javascript">window.alert("'.$message.'");</script>';
        }
    }

    function afficherDetail()
    //fonction d'affichage de la page détail.
    {
        echo '<div class = "centrer">';
        
        if(!isset($_POST['modifier']))
        {
            echo '<img src="Photos/'.$_GET['image'].' "width = 50% >';
            $connexion = getConnexion();
            $req = 'SELECT description, nomFich, heurePublication FROM photo WHERE nomFich = "'.$_GET['image'].'";';
            $resultat = mysqli_query($connexion, $req);

            while ($row = mysqli_fetch_assoc($resultat)) 
            {
                echo '<p> Description : '.$row['description'].'</p>';
                echo '<p> Nom du fichier : '.$row['nomFich'].'</p>';
                echo '<p> Date de publication :'.$row['heurePublication'].'</p>';

            }

            $req = 'SELECT c.nomCat FROM categorie c JOIN photo p ON c.catId = p.catId WHERE p.nomFich = "'.$_GET['image'].'";';
            $resultat = mysqli_query($connexion, $req);
            
            while ($row = mysqli_fetch_assoc($resultat)) 
            {
                $nomCat = $row['nomCat'];
                echo '<p> Categorie : '.$nomCat.'</p>';
            }

            echo '<a href="index.php"><input type="button" value = "Retour à la page d&apos;accueil"> </a>';
            echo '<a href="index.php?categories='.$nomCat.'"><input type="button" value = "Voir les images de même categorie"> </a>';

            echo '<br><br>';
            $req = 'SELECT c.utilisateur FROM compte c JOIN photo p ON c.idUtilisateur = p.idUtilisateur WHERE p.nomFich = "'.$_GET['image'].'";';
            $resultat = mysqli_query($connexion, $req);
            $resultat = mysqli_fetch_assoc($resultat);
            if($_SESSION['logged']==$resultat['utilisateur'] || $_SESSION['logged']=='admin'){
                echo '<form action="details.php" method="post">
                    <input name="modifier" type="hidden" value="'.$_GET['image'].'">
                    <input type="submit" value="Modifier">
                    </form>';//Envoie du formulaire pour modifier

                echo '<br>';

                echo '<form action="details.php" method="post">
                        <input name="supprimer" type="hidden" value="'.$_GET['image'].'">
                        <input type="submit" value="Supprimer">
                    </form>';//Envoie du formulaire pour supprimer

                echo '</div>';
            }
        }
    }
?>
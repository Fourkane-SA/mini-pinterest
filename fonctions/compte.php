<?php
    error_reporting(E_ALL ^ E_NOTICE);
    require_once 'fonctions/bd.php';
    require_once 'fonctions/afficher.php';
    
    function VerifieConnection($user,$password){//Verifie que l'user/mot de passe est correct
        $connexion = getConnexion();
        $req = 'SELECT * FROM compte WHERE utilisateur = "'.$user.'";';
        $resultat = mysqli_query($connexion, $req);
        while ($row = mysqli_fetch_assoc($resultat)) {
            $mdp=$row['motDePasse'];//Selectionne le mdp chiffré dans la bdd
            if(password_verify($password,$mdp)){//Permet de verifier que le mot de passe chiffré dans la bdd correspond à celui rentré
                $_SESSION['logged'] = $row['utilisateur'];
                $req2 = 'UPDATE compte SET heureConnexion = now() WHERE utilisateur = "'.$_SESSION['logged'].'";';
                $res = mysqli_query($connexion, $req2);
                header('Location: profile.php');
                //echo $_SESSION['logged'];
            }
            else {
                header('Location: index.php');
            }
        }
        return $_SESSION['logged']!='';
    }

    function connection(){//Formulair de connexion
        echo '
            <form class="cadre-compte" action="profile.php" method="post">
                login : </br><input type="text" name="user"> </input> </br> </br>
                mot de passe : </br><input type="password" name="mdp"> </input> </br> </br>
                <input type="submit" value="Submit">
            </form>';
    }

    function deconnection(){//Bouton de deconnexion
        
        //echo '<a href="index.php">Accueil</a>';
        echo '<form class="cadre-compte" action="profile.php" method="post">
                <input name="deconnection" type="hidden" value="ok">
                <input type="submit" value="Deconnection">
            </form>';
    }

    function photoUtilisateur(){//Affiche toutes les photos de l'utilisateur
        $connexion = getConnexion();
        $req = 'SELECT p.nomFich FROM photo p JOIN compte c on p.idUtilisateur = c.idUtilisateur WHERE c.utilisateur = "'.$_SESSION['logged'].'";';
        $resultat = mysqli_query($connexion, $req);
        echo '<table>';
                while ($row = mysqli_fetch_assoc($resultat)) {
                    $nomFich = $row['nomFich'];
                    echo '<tr>
                            <td>
                                <a href="details.php?image='.$nomFich.'">
                                <img src="Photos/'.$row['nomFich'].' "width = 75% >
                                </a>
                            </td>
                        </tr>';
                    echo '</a>';
                }

    }

    function profile(){//Page de profil de l'utilisateur connecté
        if ($_POST['deconnection']=="ok"){
            $_SESSION['logged']='';
            header('Location: index.php');
        }
        if($_SESSION['logged'] == '' && !VerifieConnection($_POST['user'],$_POST['mdp'])){//Si l'utilisateur n'est pas connecté
            header('Location: index.php');
        }
        else {
            echo '<h1> Bienvenue '.$_SESSION['logged'].' </h1>';
            if($_SESSION['logged']=='admin'){
                echo '<a href="index.php">Accueil</a> | <a href="admin.php">Administration</a>
                | <a href="options.php">Options</a></br></br>';
            }
            else {
                echo '<a href="index.php">Accueil</a> | <a href="options.php">Options</a></br></br>';
            }
            photoUtilisateur();
        }
    }

    function infosMembres(){//Affiche les infos de la bdd (nombres photos/utilisateurs, heure de connexion)
        $connexion = getConnexion();
        $req = 'SELECT COUNT(*) AS CNT FROM compte';
        $resultat = mysqli_query($connexion, $req);
        while ($row = mysqli_fetch_assoc($resultat)) {
            $nbutilisateurs = $row['CNT'];
            echo 'Il y a '.$nbutilisateurs.' utilisateurs </br>';
        }
        for($i=0; $i<$nbutilisateurs; $i++){
            $req = 'SELECT utilisateur, heureConnexion FROM compte WHERE idUtilisateur = "'.$i.'";';
            $resultat = mysqli_query($connexion, $req);
            while ($row = mysqli_fetch_assoc($resultat)) {
                echo '<p> Utilisateur : '.$row['utilisateur'].'</br>';
                echo 'Date de derniere connexion : '.$row['heureConnexion'].'</br>';
            }
            $req = 'SELECT COUNT(*) AS CNT FROM photo WHERE idUtilisateur = "'.$i.'";';
            $resultat = mysqli_query($connexion, $req);
            while ($row = mysqli_fetch_assoc($resultat)) {
                echo  'nombre de photos : '.$row['CNT'].'</br> </br>';
            }
        }
        $req = 'SELECT COUNT(*) AS CNT FROM categorie';
        $resultat = mysqli_query($connexion, $req);
        while ($row = mysqli_fetch_assoc($resultat)) {
            echo 'Il y a '.$row['CNT'].' categories : ';
        }
        $req = 'SELECT nomCat FROM categorie';
        $resultat = mysqli_query($connexion, $req);
        while ($row = mysqli_fetch_assoc($resultat)) {
            echo $row['nomCat'].' ';
        }
    }

    function listePhotos(){//Liste toutes les photos pour l'admin
        echo '<h3> Liste des photos : </h3>';
        $connexion = getConnexion();
        $req = 'SELECT p.nomFich, p.description, c.utilisateur, p.heurePublication FROM photo p JOIN compte c ON p.idUtilisateur = c.idUtilisateur ORDER BY p.idUtilisateur';
        $resultat = mysqli_query($connexion, $req);
        while ($row = mysqli_fetch_assoc($resultat)) {
            $nomFich = $row['nomFich'];
            $description = $row['description'];
            $utilisateur = $row['utilisateur'];
            $heure = $row['heurePublication'];
            echo '<p>Fichier : <a href = "details.php?image='.$nomFich.'">'.$nomFich.'</a> </br>
                    Description : '.$description.'</br>
                    Utilisateur : '.$utilisateur.'</br>
                    Date de publication: '.$heure.'</br> 
                </p>';
        }
    }
    function gestionAdmin(){
        if($_SESSION['logged']=='admin'){
            echo '<h1> Administration </h1>';
            echo '<a href="index.php">Accueil</a> | <a href="profile.php">Profile</a>
                | <a href="options.php">Options</a></br></br>';
            infosMembres();
            listePhotos();
        } else {
            echo '<a href="index.php">Accueil</a> | <a href="profile.php">Profile</a>
                | <a href="options.php">Options</a></br></br>';
            echo '<p> Vous n&apos;avez pas les droits </p>';
        }
    }

    function verifieMotDePasse(){//Vérifie le mot de passe
        $connexion = getConnexion();
        $req = 'SELECT motDePasse FROM compte WHERE utilisateur="'.$_SESSION['logged'].'";';
        $resultat = mysqli_query($connexion, $req);
        $resultat = mysqli_fetch_assoc($resultat);
        $mdp=$resultat['motDePasse'];
        return password_verify($_POST['password'], $mdp);
    }

    function miseAJourLogin(){//Mets à jour le login
        $connexion = getConnexion();
        $req = 'SELECT COUNT(*) AS CPT FROM compte WHERE utilisateur = "'.$_POST['user'].'";';
        $resultat = mysqli_query($connexion, $req);
        $resultat = mysqli_fetch_assoc($resultat);
        $logindispo=$resultat['CPT'];
        if($logindispo == 0 && $_POST['user'] != "admin"){//Ne modifie pas le login s'il est déjà disponible dans la base de donnée ou si c'est l'admin
            $req = 'UPDATE compte SET utilisateur = "'.$_POST['user'].'" WHERE utilisateur = "'.$_SESSION['logged'].'";';
            mysqli_query($connexion, $req);
            $_SESSION['logged']=$_POST['user'];
            echo '<p> Mise à jour du login </p></br>';
        }
    }

    function miseAJourMDP(){//Mets à jour le mot de passe
        $connexion = getConnexion();
        $req = 'UPDATE compte SET motDePasse = "'.password_hash($_POST['new_password'],PASSWORD_DEFAULT).'" WHERE utilisateur = "'.$_SESSION['logged'].'";';
        mysqli_query($connexion, $req);
        echo '<p>Mise à jour du mot de passe </p></br>';
    }

    function miseAJourStatut(){//Mets à jour le statut (compte privé/public)
        $connexion = getConnexion();
        $req = 'UPDATE compte SET priver = "'.$_POST['statut'].'" WHERE utilisateur = "'.$_SESSION['logged'].'";';
        mysqli_query($connexion, $req);
    }
    function formulaireMisaAJourDonnee(){//Formulaire pour mettre à jour les données du compte
        $connexion = getConnexion();
            $req = 'SELECT priver FROM compte WHERE utilisateur = "'.$_SESSION['logged'].'";';
            $resultat = mysqli_query($connexion, $req);
            $resultat = mysqli_fetch_assoc($resultat);
            $statut = $resultat['priver'];
            echo '<form action ="options.php" method="post">';
                echo '</br></br>Informations personnelles : </br></br>';
                echo '<label for = "login"> Pseudo <label></br>';
                echo '<input type = "text" id="login" name="user" value = "'.$_SESSION['logged'].'" required>';
                echo '</br></br>';
                echo '<label for = "cmdp"> Mot de passe actuel </label></br>';
                echo '<input type = "password" id="cmdp" name="password">';
                echo '</br></br>';
                echo '<label for = "mdp"> Nouveau mot de passe <label></br>';
                echo '<input type = "password" id="mdp" name="new_password">';
                echo '</br></br>';
                echo '<p>Statut du compte :</p>';
                if($statut){//Si le compte est privé
                    echo '<input type="radio" id="pub" name="statut" value="0">';
                    echo '<label for="pub">Public</label>';
                    echo '<input type="radio" id="pri" name="statut" value="1" checked >';
                    echo '<label for="pub">Privé</label></br></br>';
                } else {
                    echo '<input type="radio" id="pub" name="statut" value="0"checked>';
                    echo '<label for="pub">Public</label>';
                    echo '<input type="radio" id="pri" name="statut" value="1">';
                    echo '<label for="pub">Privé</label></br></br>';
                }
                
                echo '<button type="submit">Modifier</button>';
            echo '</form>';

    }

    function gestionCompte(){//Permet de gérer les informations personnelles
        $connexion = getConnexion();
        if(isset($_POST['password'])){
            if(verifieMotDePasse()){
                if(isset($_POST['user'])){
                    miseAJourLogin();
                }
                if(isset($_POST['new_password'])){
                    miseAJourMDP();
                }
                if(isset($_POST['statut'])){
                    miseAJourStatut();
                }
            } else {
                echo '<p> Le mot de passe est incorrect <p></br>';
            }
        }

        if($_SESSION['logged']!=''){
            echo '<h1>Gerez vos informations personnelles :</h1>';
            echo '<a href="index.php">Accueil</a> | <a href="profile.php">Profile</a></br></br>';
            echo '<div class ="centrer">';
            echo '<p> Nombre de photos : ';
            $connexion = getConnexion();
            $req = 'SELECT COUNT(*) AS CMP FROM photo p JOIN compte c ON p.idUtilisateur = c.idUtilisateur WHERE c.utilisateur = "'.$_SESSION['logged'].'";';
            $resultat = mysqli_query($connexion, $req);
            while ($row = mysqli_fetch_assoc($resultat)) {
                echo $row['CMP'].'</p></br>';
            }
            formulaireMisaAJourDonnee();
            echo '</div>';
            
            
        } else {
            echo '<a href="index.php">Accueil</a> | <a href="profile.php">Connexion</a></br></br>';
            echo '<p> Vous devez être connectés pour acceder à cette page </p>';
        }
    }

    function afficherInfosCompte(){//Affiche les informations du compte
        if($_SESSION['logged']!=''){
            echo '<div class ="cadre-compte">';
                echo '<p>'.$_SESSION['logged'].'</br>';
                echo 'Temps de connexion :</br>';
                $connexion = getConnexion();
                $req = 'SELECT TIMEDIFF(now(), heureConnexion) AS tps FROM compte WHERE utilisateur = "'.$_SESSION['logged'].'";';
                $resultat = mysqli_query($connexion, $req);
                while ($row = mysqli_fetch_assoc($resultat)) {
                    echo $row['tps'];
                }
                echo '</br>';
                deconnection();
            echo '</div>';
        } else {
            echo '<div class ="cadre-compte">';
                connection();
            echo '</div>';
        }
    }
?>
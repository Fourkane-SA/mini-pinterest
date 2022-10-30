<?php
session_start();
$dbHost = "localhost";
$dbUser = "root";
$dbPwd = "";
$dbName = "mini-pinterest";

function getConnexion()
//Connection à la bdd
{
	$connexion = mysqli_connect("localhost", "root", "", "mini-pinterest");

	if (mysqli_connect_errno()) 
	{
		printf("Échec de la connexion : %s\n", mysqli_connect_error());
		exit();
	}
	return $connexion;
}


function CloseConnexion($connexion)//Ferme la connexion
{
	mysqli_close($connexion);
}

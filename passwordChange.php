<?php
session_start();
session_regenerate_id();

//Si l'utilisateur n'est pas connecté renvoi directement sur la page de connexion
if(!isset($_SESSION['username'])){
	header("Location: Connexion.php");
}

//Vérifie si les données nécessaires au changement sont bien indiquées
if(isset($_POST['password']) && isset($_POST['verifpassword']) && isset($_POST['newpassword'])){

	//Connexion à la base de données
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=UTF8','root','');
	}
	catch(Exception $e){
		die('Erreur : ' .$e->getMessage());
	}

	//On attribue les valeurs à des variables afin de les réutilisées
	$password = htmlspecialchars($_POST['password']);
	$verifpassword = htmlspecialchars($_POST['verifpassword']);
	$newpassword = htmlspecialchars($_POST['newpassword']);
	$username = htmlspecialchars($_SESSION['username']);

	//On vérifie que les données indiqués ne sont pas vides
	if($password != "" && $verifpassword != "" && $newpassword != ""){

		//On vérifie si dans la base de donnée il a un utilisateur correspondant aux données indiquées
		$req = $bdd->prepare('SELECT mdp_uti FROM utilisateur WHERE nom_uti = ?');
		$req->execute(array($username));
		$result = $req->fetchAll();

		$password_hash = $result[0]['mdp_uti'];

		//Si la requête nous renvoi une ligne alors c'est que le mot de passe correspond
		if(password_verify($password, $password_hash)){

			//Verifie si le nouveau mot de passe et le nouveau mot de passe répété est le même
			if($newpassword == $verifpassword){

				//Hash de mot de passe
				$newpassword_hash = password_hash($newpassword, PASSWORD_DEFAULT);

				//Requête permettant de modifier le mot de passe de l'utilisateur
				$requete = $bdd->prepare('UPDATE utilisateur SET mdp_uti = ? WHERE nom_uti = ?');
				$requete->execute(array($newpassword_hash, $username));

				//Renvoi sur la page de profile avec un message de validation de changement de mot de passe
				header("Location: profile.php?changer=1");
			}
			else{
				//Renvoi sur la page de profil avec un message d'erreur qui indique que les nouveaux mot de passes sont différents
				header("Location: profile.php?erreur=3");
			}
		}
		else{
			//Renvoi sur la page de profil avec un message d'erreur indiquant que l'ancien mot de passe n'est pas correcte
			header("Location: profile.php?erreur=2");
		}
	}
	else{
		//Si les données sont vides renvoi un message d'erreur
		header("Location: profile.php?erreur=1");
	}
}
else{
	//Renvoi sur la page de profile
	header("Location: profile.php");
}

?>
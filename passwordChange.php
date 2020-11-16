<?php
session_start();
session_regenerate_id();
if(!isset($_SESSION['username'])){
	header("Location: Connexion.php");
}

if(isset($_POST['password']) && isset($_POST['verifpassword']) && isset($_POST['newpassword'])){
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=UTF8','root','');
	}
	catch(Exception $e){
		die('Erreur : ' .$e->getMessage());
	}

	$password = htmlspecialchars($_POST['password']);
	$verifpassword = htmlspecialchars($_POST['verifpassword']);
	$newpassword = htmlspecialchars($_POST['newpassword']);
	$username = htmlspecialchars($_SESSION['username']);

	if($password != "" && $verifpassword != "" && $newpassword != ""){
		$requete = $bdd->prepare("SELECT * FROM utilisateur WHERE nom_uti = ? AND mdp_uti = ?");
		$requete->execute(array($username, $password));
		$count = $requete->rowCount();
		if($count == 1){
			if($newpassword == $verifpassword){
				$requete = $bdd->prepare('UPDATE utilisateur SET mdp_uti = ? WHERE nom_uti = ?');
				$requete->execute(array($newpassword, $username));
				header("Location: profile.php?changer=1");
			}
			else{
				header("Location: profile.php?erreur=3");
			}
		}
		else{
			header("Location: profile.php?erreur=2");
		}
	}
	else{
		header("Location: profile.php?erreur=1");
	}
}
else{
	header("Location: profile.php");
}

?>
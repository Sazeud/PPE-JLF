<?php
session_start();

if(isset($_POST['username']) && isset($_POST['password'])){
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=UTF8','root','');
	}
	catch(Exception $e){
		die('Erreur : ' .$e->getMessage());
	}

	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	if($username != "" && $password != ""){
		$req = $bdd->prepare('SELECT * FROM utilisateur WHERE nom_uti = ? AND mdp_uti = ?');
		$req->execute(array($username,$password));
		$count = $req->rowCount();
		if($count != 0){
			$_SESSION['username'] = $username;
			header('Location: index.php');
		}
		else{
			header('Location: Connexion.php?erreur=1');
		}
	}
	else{
		header('Location: Connexion.php?erreur=2');
	}
}
else{
	header('Location: Connexion.php');
}
?>
<?php
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['verifpassword']))
{
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');
	}
	catch(Exception $e){
		die('Erreur : ' .$e->getMessage());
	}

	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$verifpassword = htmlspecialchars($_POST['verifpassword']);

	if($username != "" && $password != "" && $verifpassword != ""){
		$req = $bdd->prepare("SELECT * FROM utilisateur WHERE nom_uti = ? ");
		$req->execute(array($username));
		$count = $req->rowCount();
		if($password == $verifpassword){
			if($count == 0){
				$requete = $bdd->prepare("INSERT INTO utilisateur(nom_uti, mdp_uti) VALUES (?,?)");
				$requete->execute(array($username,$password));
				header('Location: Inscription.php?creer=1');
			}
			else{
				header('Location: Inscription.php?erreur=3');
			}
		}
		else{
			header('Location: Inscription.php?erreur=2');
		}
	}
	else{
		header('Location: Inscription.php?erreur=1');
	}
}
else{
	header('Location: Inscription.php');
}
?>
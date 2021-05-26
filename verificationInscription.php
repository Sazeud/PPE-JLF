<?php
//Verification si les valeurs sont bien attribuées
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['verifpassword']))
{
	//Connexion à la base de donnée
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');
	}
	catch(Exception $e){
		die('Erreur : ' .$e->getMessage());
	}

	//Création des variables avec les valeurs mises en POST
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$verifpassword = htmlspecialchars($_POST['verifpassword']);

	//Verification que les variables ne sont pas vides
	if($username != "" && $password != "" && $verifpassword != ""){

		//Requête permettant de verifier si le nom d'utilisateur est déjà existant
		$req = $bdd->prepare("SELECT * FROM utilisateur WHERE nom_uti = ? ");
		$req->execute(array($username));
		$count = $req->rowCount();

		//On vérifie que le mot de passe et le mot de passe vérifié est le même
		if($password == $verifpassword){

			//S'il n'y a pas de compte utilisateur avec ce pseudo on peut réaliser la requête
			if($count == 0){

				//Requête d'ajout de l'utilisateur dans la base de donnée
				$requete = $bdd->prepare("INSERT INTO utilisateur(nom_uti, mdp_uti, pt_fid) VALUES (?,?,0)");
				$requete->execute(array($username,$password));

				//Renvoi vers la page d'inscription avec le message de validation de création de compte
				header('Location: Inscription.php?creer=1');
			}
			else{
				//Renvoi vers la page d'inscription avec le message d'erreur (nom d'utilisateur déjà crée)
				header('Location: Inscription.php?erreur=3');
			}
		}
		else{
			//Renvoi vers la page d'inscription avec le message d'erreur (les mots de passes ne correspondent pas)
			header('Location: Inscription.php?erreur=2');
		}
	}
	else{
		//Renvoi vers la page d'inscription avec le message d'erreur (données non renseignées)
		header('Location: Inscription.php?erreur=1');
	}
}
else{
	header('Location: Inscription.php');
}
?>
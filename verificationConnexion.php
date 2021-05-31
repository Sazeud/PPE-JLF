<?php
session_start();
session_regenerate_id();

//Verifie que les données sont bien crées
if(isset($_POST['username']) && isset($_POST['password'])){

	//Connexion à la basse de donnée
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=UTF8','root','');
	}
	catch(Exception $e){
		die('Erreur : ' .$e->getMessage());
	}

	//Attribue les données dans des variables
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	//On vérifie que le nom d'utilisateur et le mot de passe ne sont pas vides
	if($username != "" && $password != ""){

		//On vérifie si dans la base de donnée il a un utilisateur correspondant aux données indiquées
		$req = $bdd->prepare('SELECT mdp_uti FROM utilisateur WHERE nom_uti = ?');
		$req->execute(array($username));
		$result = $req->fetchAll();

		$password_hash = $result[0]['mdp_uti'];

		//Si la requête renvoi une ligne alors on peut avancer
		if(password_verify($password, $password_hash)){

			//On crée une variable session qui stock l'username afin de pouvoir le réutiliser sur d'autres pages
			$_SESSION['username'] = $username;

			//Si la variable Session numTraversee existe alors cela indique qu'il était sur la page de réservation, suite à la connexion on le renvoit sur cette page
			if(isset($_SESSION['numTraversee'])){
				header('Location: reservation.php?reservation='.$_SESSION['numTraversee']);
			}
			//Sinon on le renvoit à l'accueil
			else{
				header('Location: index.php');
			}
		}
		//S'il n'y a pas de compte correspondant alors on le renvoit sur la page de connexion avec un message d'erreur
		else{
			header('Location: Connexion.php?erreur=1');
		}
	}
	//Si le mot de passe ou le nom d'utilisateur est vide alors renvoi sur la page de connexion avec un message d'erreur
	else{
		header('Location: Connexion.php?erreur=2');
	}
}
else{
	header('Location: Connexion.php');
}
?>
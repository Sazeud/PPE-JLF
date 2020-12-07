<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Titre de la page</title>
		<link rel="stylesheet" href="bootstrap.css">
		<link rel="stylesheet" href="reservation.css">
	</head>
		<?php
			session_start();
			session_regenerate_id();
			//Connexion à la base de donnée
			try{
				$bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');
			}
			catch(Exception $e){
				die('Erreur : ' .$e->getMessage());
			}
		?>	
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand" href="index.php">MarieTeam</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
				</ul>
				<span class="navbar-text">
				Page d'Accueil
				</span>
			<?php if(!isset($_SESSION['username'])){?>
				<form class="form-inline my-2 my-lg-0">
				<a class="nav-link" href="Connexion.php">Connexion/Inscription<span class="sr-only">(current)</span></a>
				</form>
			<?php }
			else if(isset($_SESSION['username'])){?>
				<form class="form-inline my-2 my-lg-0">
				<a class="nav-link" href="profile.php"><?php echo $_SESSION['username']; ?></a>
				<a class="nav-link" href="Deconnexion.php">Deconnexion<span class="sr-only">(current)</span></a>
			</form>
			<?php } ?>
			</div>
		</nav>

		<div class="reservation">
			<div class="titre">
				<h5>Réservation :<h5>
			</div>
			<div class="donnees">
				<?php
					if(isset($_POST['reservation'])){
						$sql = 'SELECT DATE_FORMAT(T.date, \'%d/%m/%Y\') as dateT, T.heure, L.idPort, L.idPort_ARRIVEE FROM traversee as T, liaison as L WHERE numTrav = ? AND T.code = L.code';
						$stm = $bdd->prepare($sql);
						$stm->execute(array($_POST['reservation']));
						$result = $stm->fetchAll();

						$dateT = $result[0]['dateT'];
						$heure = $result[0]['heure'];
						$portDep = $result[0]['idPort'];
						$portArr = $result[0]['idPort_ARRIVEE'];

						$sql = 'SELECT nom FROM port WHERE idPort = ?';
						$stm = $bdd->prepare($sql);
						$stm->execute(array($portDep));
						$result = $stm->fetchAll();

						$nomPortDep = $result[0]['nom'];

						$sql = 'SELECT nom FROM port WHERE idPort = ?';
						$stm = $bdd->prepare($sql);
						$stm->execute(array($portArr));
						$result = $stm->fetchAll();

						$nomPortArr = $result[0]['nom']; ?>
						<br>
						<p>Liaison <?php echo htmlspecialchars($nomPortDep).' - '.htmlspecialchars($nomPortArr); ?><br>
						Traversée n°<?php echo htmlspecialchars($_POST['reservation']).' le '.htmlspecialchars($dateT).' à '.htmlspecialchars($heure); ?><br>
						Saisissez les informations nécessaires à la réservation :
						</p>
						
						<form action="billet.php" method="post">
							<p>
								Nom : 
								<input type="text" name="nom"><br>
								Prénom :
								<input type="text" name="prenom"><br>
								Adresse :
								<input type="text" name="adresse"><br>
								Code Postal :
								<input type="text" name="cp" class="codepost">
								Ville :
								<input type="text" name="ville">
							</p>
						</form>
				<?php	}
				?>
			</div>
		</div>
	</body>
</html>
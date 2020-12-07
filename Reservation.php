<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Marie Team | Page de réservation</title>
		<link rel="stylesheet" href="bootstrap.css">
		<link rel="stylesheet" href="reservation.css">
	</head>
		<?php
			session_start();
			session_regenerate_id();
			$_SESSION['numTraversee'] = $_GET['reservation'];
			//Connexion à la base de donnée
			try{
				$bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');
			}
			catch(Exception $e){
				die('Erreur : ' .$e->getMessage());
			}
		?>	
	<body>
		<?php if(isset($_SESSION['username'])){ ?>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand" href="index.php">MarieTeam</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
				</ul>
				<span class="navbar-text">
				Page de réservation
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
					if(isset($_GET['reservation'])){
						$sql = 'SELECT DATE_FORMAT(T.date, \'%d/%m/%Y\') as dateT, T.heure, L.idPort, L.idPort_ARRIVEE, T.date FROM traversee as T, liaison as L WHERE numTrav = ? AND T.code = L.code';
						$stm = $bdd->prepare($sql);
						$stm->execute(array($_GET['reservation']));
						$result = $stm->fetchAll();

						$dateT = $result[0]['dateT'];
						$heure = $result[0]['heure'];
						$portDep = $result[0]['idPort'];
						$portArr = $result[0]['idPort_ARRIVEE'];
						$date = $result[0]['date'];

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
						Traversée n°<?php echo htmlspecialchars($_GET['reservation']).' le '.htmlspecialchars($dateT).' à '.htmlspecialchars($heure); ?><br>
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

							<table class="reserver">
								<thead>
									<tr>
										<th>Catégories</th>
										<th>Tarif en €</th>
										<th>Quantité</th>
									</tr>	
								</thead>
								<?php
									$sql = 'SELECT dateDeb FROM periode WHERE dateDeb <= ? AND dateFin >= ?';
									$stm = $bdd->prepare($sql);
									$stm->execute(array($date,$date));
									$result = $stm->fetchAll();

									$dateDeb = $result[0]['dateDeb'];

									$sql = 'SELECT * FROM tarifer WHERE dateDeb = ?';
									$stm = $bdd->prepare($sql);
									$stm->execute(array($dateDeb));
									$result = $stm->fetchAll();

								?>
								<tbody>
									<tr>
										<td>Adulte</td>
										<td><?php echo htmlspecialchars($result[0]['tarif']); ?></td>
										<td><input type="number" name="nAdulte" class="b-nombre"></td>
									</tr>
									<tr>
										<td>Junior 8 à 18 ans</td>
										<td><?php echo htmlspecialchars($result[1]['tarif']); ?></td>
										<td><input type="number" name="nJunior" class="b-nombre"></td>
									</tr>
									<tr>
										<td>Enfant 0 à 7 ans</td>
										<td><?php echo htmlspecialchars($result[2]['tarif']); ?></td>
										<td><input type="number" name="nEnfant" class="b-nombre"></td>
									</tr>
									<tr>
										<td>Voiture long.inf.4m</td>
										<td><?php echo htmlspecialchars($result[3]['tarif']); ?></td>
										<td><input type="number" name="nVoitInf4" class="b-nombre"></td>
									</tr>
									<tr>
										<td>Voiture long.inf.5m</td>
										<td><?php echo htmlspecialchars($result[4]['tarif']); ?></td>
										<td><input type="number" name="nVoitInf5" class="b-nombre"></td>
									</tr>
									<tr>
										<td>Fourgon</td>
										<td><?php echo htmlspecialchars($result[5]['tarif']); ?></td>
										<td><input type="number" name="nFourgon" class="b-nombre"></td>
									</tr>
									<tr>
										<td>Camping Car</td>
										<td><?php echo htmlspecialchars($result[6]['tarif']); ?></td>
										<td><input type="number" name="nCampingCar" class="b-nombre"></td>
									</tr>
									<tr>
										<td>Camion</td>
										<td><?php echo htmlspecialchars($result[7]['tarif']); ?></td>
										<td><input type="number" name="nCamion" class="b-nombre"></td>
									</tr>
								</tbody>
							</table>
							<input type="submit" value="Réserver" class="b-reserver">
						</form>
				<?php	}
				?>
			</div>
		</div>
		<?php	
		}else{
			header('Location: Connexion.php');
		}
		?>
	</body>
</html>
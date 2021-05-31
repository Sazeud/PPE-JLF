<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Marie Team | Reservation</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/one-page-wonder.css" rel="stylesheet">
  <link href="css/footer.css" rel="stylesheet">

</head>

  <?php
			session_start();
			session_regenerate_id();
			$_SESSION['numTraversee'] = $_GET['reservation'];
			$_SESSION['reduction'] = 1;
			//Connexion à la base de donnée
			try{
				$bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');
			}
			catch(Exception $e){
				die('Erreur : ' .$e->getMessage());
			}
		?>

<body>
		<!-- Verifie si l'utilisateur est connecté -->
		<?php if(isset($_SESSION['username'])){ ?>
			
  

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
    	<div>
        <a href="#top" class="logo">
          <img src="img/logo.png"/>
        </a>
      </div>
      <a class="navbar-brand" href="index.php">MarieTeam</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">

        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
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
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header id="home" class="masthead text-center text-white">

    <section >
    <div class="container bg-secondary py-5">
			<div class="titre">
				<h5>Réservation :<h5>
			</div>
			<div class="donnees">
				<?php
					//On verifie que le numéro de réservation est bien établi
					if(isset($_GET['reservation'])){

						//On récupère les différentes informations de la traversée afin de pouvoir les afficher
						$sql = 'SELECT DATE_FORMAT(T.date, \'%d/%m/%Y\') as dateT, T.heure, L.idPort, L.idPort_ARRIVEE, T.date FROM traversee as T, liaison as L WHERE numTrav = ? AND T.code = L.code';
						$stm = $bdd->prepare($sql);
						$stm->execute(array($_GET['reservation']));
						$result = $stm->fetchAll();

						$dateT = $result[0]['dateT'];
						$heure = $result[0]['heure'];
						$portDep = $result[0]['idPort'];
						$portArr = $result[0]['idPort_ARRIVEE'];
						$date = $result[0]['date'];

						//On récupère le nom du port de départ
						$sql = 'SELECT nom FROM port WHERE idPort = ?';
						$stm = $bdd->prepare($sql);
						$stm->execute(array($portDep));
						$result = $stm->fetchAll();

						$nomPortDep = $result[0]['nom'];

						//On récupère le nom du port d'arrivé
						$sql = 'SELECT nom FROM port WHERE idPort = ?';
						$stm = $bdd->prepare($sql);
						$stm->execute(array($portArr));
						$result = $stm->fetchAll();

						$nomPortArr = $result[0]['nom']; 

						//Requête qui permet de récupérer les différentes places restantes en fonction des catégorie sur la traversée
						$sql = 'SELECT placesA, placesB, placesC FROM traversee WHERE numTrav = ?';
						$stm = $bdd->prepare($sql);
						$stm->execute(array($_GET['reservation']));
						$res = $stm->fetchAll();

						if(isset($_GET['erreur'])){
							if($_GET['erreur'] == 'A'){ ?>
								<!-- Erreur en cas de réservation avec plus de passagers que de places disponibles -->
								<br><center><p style="color:red">Impossible de réserver, vous avez demandé plus de places qu'il n'en reste ! (<?php echo $res[0]['placesA']?> places restantes de type A)</p></center>
					<?php		}
							else if($_GET['erreur'] == 'B'){ ?>
								<!-- Erreur en cas de réservations de véhicule de type B alors qu'il n'y a plus de places  -->
								<br><center><p style="color:red">Impossible de réserver, vous avez demandé plus de places qu'il n'en reste ! (<?php echo $res[0]['placesB']?> places restantes de type B)</p></center>
					<?php		}
							else if($_GET['erreur'] == 'C'){ ?>
								<!-- Erreur en cas de réservations de véhicule de type C alors qu'il n'y a plus de places  -->
								<br><center><p style="color:red">Impossible de réserver, vous avez demandé plus de places qu'il n'en reste ! (<?php echo $res[0]['placesC']?> places restantes de type C)</p></center>
					<?php		}
						}
						?>
						<!-- Affichage des infos de traversées -->
						<p>Liaison <?php echo htmlspecialchars($nomPortDep).' - '.htmlspecialchars($nomPortArr); ?><br>
						Traversée n°<?php echo htmlspecialchars($_GET['reservation']).' le '.htmlspecialchars($dateT).' à '.htmlspecialchars($heure); ?><br>
						Saisissez les informations nécessaires à la réservation :
						</p>
						
						<!-- Formulaire pour donner les informations de réservation de l'utilisateur -->
						<form action="billet.php" method="post">
							<p>
								Nom : 
								<input type="text" name="nom" required><br>
								Prénom :
								<input type="text" name="prenom" required><br>
								Adresse :
								<input type="text" name="adresse" required><br>
								Code Postal :
								<input type="text" name="cp" class="codepost" required>
								Ville :
								<input type="text" name="ville" required>
							</p>

							<!-- Tableau présentant les différents choix de passagers et de véhicules -->
							<table class="reserver col-lg-12 col-md-12 col-sm-12 text-center">
								<thead>
									<tr>
										<th>Types</th>
										<th>Catégories</th>
										<th>Tarif en €</th>
										<th>Quantité</th>
									</tr>	
								</thead>
								<?php
									//Requête permettant d'obtenir la date de début de la période dans laquelle cette réservation est
									$sql = 'SELECT dateDeb FROM periode WHERE dateDeb <= ? AND dateFin >= ?';
									$stm = $bdd->prepare($sql);
									$stm->execute(array($date,$date));
									$result = $stm->fetchAll();

									$dateDeb = $result[0]['dateDeb'];

									//On récupère tout les tarif liés à la période obtenu
									$sql = 'SELECT * FROM tarifer WHERE dateDeb = ?';
									$stm = $bdd->prepare($sql);
									$stm->execute(array($dateDeb));
									$result = $stm->fetchAll();
								?>

								<!-- On remet les données par catégorie avec le tarif et une case permettant de choisir le choix -->
								<tbody>
									<tr>
										<td>A</td>
										<td>Adulte</td>
										<td><?php echo htmlspecialchars($result[0]['tarif']); ?></td>
										<td><input type="number" name="nAdulte" class="b-nombre" min="0" max="<?php echo $res[0]['placesA']?>" value = "0"></td>
									</tr>
									<tr>
										<td>A</td>
										<td>Junior 8 à 18 ans</td>
										<td><?php echo htmlspecialchars($result[1]['tarif']); ?></td>
										<td><input type="number" name="nJunior" class="b-nombre" min="0" max="<?php echo $res[0]['placesA']?>" value = "0"></td>
									</tr>
									<tr>
										<td>A</td>
										<td>Enfant 0 à 7 ans</td>
										<td><?php echo htmlspecialchars($result[2]['tarif']); ?></td>
										<td><input type="number" name="nEnfant" class="b-nombre" min="0" max="<?php echo $res[0]['placesA']?>" value = "0"></td>
									</tr>
									<?php 
										//On vérifie qu'il reste des places de catégorie B afin d'afficher ou non la catégorie
										if($res[0]['placesB'] != 0){
									?>
									<tr>
										<td>B</td>
										<td>Voiture long.inf.4m</td>
										<td><?php echo htmlspecialchars($result[3]['tarif']); ?></td>
										<td><input type="number" name="nVoitInf4" class="b-nombre" min="0" max="<?php echo $res[0]['placesB']?>" value = "0"></td>
									</tr>
									<tr>
										<td>B</td>
										<td>Voiture long.inf.5m</td>
										<td><?php echo htmlspecialchars($result[4]['tarif']); ?></td>
										<td><input type="number" name="nVoitInf5" class="b-nombre" min="0" max="<?php echo $res[0]['placesB']?>" value = "0"></td>
									</tr>
									<?php 
									}
										//On vérifie qu'il reste des places de catégorie C afin d'afficher ou non la catégorie
										if($res[0]['placesC'] != 0){
									?>
									<tr>
										<td>C</td>
										<td>Fourgon</td>
										<td><?php echo htmlspecialchars($result[5]['tarif']); ?></td>
										<td><input type="number" name="nFourgon" class="b-nombre" min="0" max="<?php echo $res[0]['placesC']?>" value = "0"></td>
									</tr>
									<tr>
										<td>C</td>
										<td>Camping Car</td>
										<td><?php echo htmlspecialchars($result[6]['tarif']); ?></td>
										<td><input type="number" name="nCampingCar" class="b-nombre" min="0" max="<?php echo $res[0]['placesC']?>" value = "0"></td>
									</tr>
									<tr>
										<td>C</td>
										<td>Camion</td>
										<td><?php echo htmlspecialchars($result[7]['tarif']); ?></td>
										<td><input type="number" name="nCamion" class="b-nombre" min="0" max="<?php echo $res[0]['placesC']?>" value = "0"></td>
									</tr>
								<?php }?>
								</tbody>
							</table><br>
							<p>Vos points de fidélité : <?php 
								//Requête permettant de récupérer le nombre de points de fidélité de l'utilisateur
								$sql = 'SELECT pt_fid FROM utilisateur WHERE nom_uti = ?';
								$stm = $bdd->prepare($sql);
								$stm->execute(array($_SESSION['username']));
								$result = $stm->fetchAll();

								$points_fid = $result[0]['pt_fid'];

								//Affichage du nombre de points de fidélité
								echo $points_fid;
							?></p>
							<!-- On vérifie que l'utilisateur a plus de 100 points de fidélité pour afficher le bouton permettant d'obtenir un rabais -->
							<?php if($points_fid >= 100){ ?>
									<p>Vous pouvez utiliser vos points de fidélité pour obtenir un rabais :</p>
									<input type="checkbox" name="fidelite" class="rad"> Utiliser mes points</label><br>
								<?php } 
							?>
							<input type="submit" value="Réserver" class="b-reserver">
						</form>
				<?php	}
				?>
			</div>
		</div>
		<?php	
		}else{
			//Si l'utilisateur n'est pas connecté on renvoit à la page connexion
			header('Location: Connexion.php');
		}
		?>
  </section>
    
  </header>


    <!-- Footer -->
        <footer class="py-4 bg-dark fixed-bottom ">
            <div class="container" >
            <p class="m-0 text-center text-white small">MarieTEAM présenté par JLF</p>
            </div>
        </footer>

  <!-- JS -->
        <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-migrate-1.4.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>        
        <script type="text/javascript" src="js/SmoothScroll.js"></script>
        <script type="text/javascript" src="js/jquery.scrollTo.min.js"></script>
        <script type="text/javascript" src="js/jquery.localScroll.min.js"></script>

</body>

</html>
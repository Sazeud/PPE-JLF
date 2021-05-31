<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Marie Team | Gestionnaire</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/one-page-wonder.css" rel="stylesheet">
  <link href="css/footer.css" rel="stylesheet">
</head>

<!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
        <div>
        <a href="#top" class="logo">
          <img src="img/logo.png"/>
        </a>
      </div>
      <a class="navbar-brand" href="profile.php">Profil</a>
      <a class="navbar-brand" href="index.php">MarieTeam</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>
<body>

    <header id="home" class="masthead text-center text-white">

    <section class="profil" >
        <div class="container bg-secondary py-5">
 
 <?php
$bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');

	if($_GET['choix'] == "AjoutLiaison"){?>
	<h1 class="gestionnaire">Ajouter une liasion :</h1>
	<form action="profile.php" method="POST">
		<i class="col-lg-4 col-md-6 col-sm-12 text-center py-4">code</i>
		<input type="text" name="code">
		<i class="col-lg-4 col-md-6 col-sm-12 text-center py-4">distance</i>
		<input type="text" name="distance">
		<i class="col-lg-4 col-md-6 col-sm-12 text-center py-4">idPort</i>
		<input type="text" name="idPort">
		<i class="col-lg-4 col-md-6 col-sm-12 text-center py-4">idPort_ARRIVEE</i>
		<input type="text" name="idPort_ARRIVEE">
		<i class="col-lg-4 col-md-6 col-sm-12 text-center py-4"4>idSecteur</i>
		<input type="text" name="idSecteur">
		<input type="hidden" name="choix" value="1">
        <br>
		<input type="submit">
	</form>
	<?php
	$sql = 'SELECT * FROM liaison';
            $stm = $bdd->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
 	?>
 	</br><table class="col-lg-12 col-md-12 col-sm-12 text-center py-4">
 		<thead>
 		<tr>
 			<th colspan="5">Liaisons</th>
 			<tr><td>code</td><td>distance</td><td>idPort</td><td>idPort_ARRIVEE</td><td>idSecteur</td></tr>
 		</tr>
 		</thead>
 		<tbody>

 		<?php foreach($result as $value){ ?>
 		<tr><td><?php echo($value[0]) ?></td><td><?php echo($value[1]) ?></td><td><?php echo($value[2]) ?></td><td> <?php echo($value[3]) ?></td><td><?php echo($value[4]) ?></td></tr>
        <?php } ?>
 		</tbody>
 	</table>

<?php }
	else if($_GET['choix'] == "ModifLiaison"){?>
	<h1 class="gestionnaire">Modifier une liasion :</h1>
	<form action="profile.php" method="POST">
		<i class="col-lg-4 col-md-6 col-sm-12 text-center py-4">Code Liaison :</i>
		<input type="text" name="codeLiaison"></br></br>
		<i class="col-lg-4 col-md-6 col-sm-12 text-center py-4">distance</i>
		<input type="text" name="distance">
		<i class="col-lg-4 col-md-6 col-sm-12 text-center py-4">idPort</i>
		<input type="text" name="idPort">
		<i class="col-lg-4 col-md-6 col-sm-12 text-center py-4">idPort_ARRIVEE</i>
		<input type="text" name="idPort_ARRIVEE">
		<i class="col-lg-4 col-md-6 col-sm-12 text-center py-4">idSecteur</i>
		<input type="text" name="idSecteur">
		<input type="hidden" name="choix" value="2">
        <br>
		<input type="submit">
	</form>
	<?php
	$sql = 'SELECT * FROM liaison';
            $stm = $bdd->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
 	?>
 	</br><table class="col-lg-12 col-md-12 col-sm-12 text-center py-4">
 		<thead>
 		<tr>
 			<th colspan="5">Liaisons</th>
 			<tr><td>code</td><td>distance</td><td>idPort</td><td>idPort_ARRIVEE</td><td>idSecteur</td></tr>
 		</tr>
 		</thead>
 		<tbody>

 		<?php foreach($result as $value){ ?>
 		<tr><td><?php echo($value[0]) ?></td><td><?php echo($value[1]) ?></td><td><?php echo($value[2]) ?></td><td> <?php echo($value[3]) ?></td><td><?php echo($value[4]) ?></td></tr>
        <?php } ?>
 		</tbody>
 	</table>
	<?php }
 	else if($_GET['choix'] == "InfoReservation"){
 	$sql = 'SELECT COUNT(*) AS nbReservation FROM reservation';
            $stm = $bdd->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

            $nbTotReserv = $result[0]['nbReservation'];
    ?>
    <i>Il y a </i><?php echo($nbTotReserv)?><i> réservations.</i>

    <?php
 	$sql = 'SELECT * FROM reservation';
            $stm = $bdd->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
 	?>
 	<table class="col-lg-12 col-md-12 col-sm-12 text-center py-4">
 		<thead>
 		<tr>
 			<th colspan="8">Réservations</th>
 			<tr><td>numReserv</td><td>nom</td><td>adr</td><td>cp</td><td>ville</td><td>prix</td><td>numTrav</td><td>codeuti</td></tr>
 		</tr>
 		</thead>
 		<tbody>

 		<?php foreach($result as $value){ ?>
 		<tr><td><?php echo($value[0]) ?></td><td><?php echo($value[1]) ?></td><td><?php echo($value[2]) ?></td><td> <?php echo($value[3]) ?></td><td><?php echo($value[4]) ?></td><td> <?php echo($value[5]) ?></td><td><?php echo($value[6]) ?></td><td><?php echo($value[7]) ?></td></tr>
        <?php } ?>
 		</tbody>
 	</table>
<?php }
 	else if($_GET['choix'] == "ReservationPeriode"){
 	$sql = 'SELECT DATE_FORMAT(dateDeb, \'%d/%m/%Y\') as dateDeb_Reo, DATE_FORMAT(dateFin, \'%d/%m/%Y\') as dateFin_Reo, dateDeb,dateFin FROM periode';
            $stm = $bdd->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
 	?>
 	<i>Période :</i>
 	<form action="gestionnaire.php" method="_GET">
 	<select name ="date">
 	<option value="">Choisir une période</option>
 	<?php 
 	foreach($result as $value){
 	 ?>
 	<option value="<?php echo $value['dateDeb'].$value['dateFin'] ?>"><?php echo $value['dateDeb_Reo']." / ".$value['dateFin_Reo'] ?></option>
 	
<?php } ?></select>
<input type="hidden" name="choix" value="ReservationPeriode">
<input type="submit" value="Ok"></form>
<?php } 
if(isset($_GET['date'])){
$dateDeb = substr($_GET['date'],0,10);
$dateFin = substr($_GET['date'],10,10);
$sql = 'SELECT SUM(R.prix) FROM traversee as T, reservation as R WHERE R.numTrav = T.numTrav AND T.date > ? AND T.date < ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($dateDeb,$dateFin));
            $result = $stm->fetchAll();
			
			$ca=$result[0][0];
			if($ca == null){
			$ca = 0;
		}

$sql = 'SELECT COUNT(R.numReserv) AS nbReservation FROM reservation as R, traversee as T WHERE R.numTrav = T.numTrav AND T.date > ? AND T.date < ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($dateDeb,$dateFin));
            $result = $stm->fetchAll();

            $nbTotReserv = $result[0]['nbReservation'];

 $sql = 'SELECT SUM(E.quantite) FROM traversee as T, reservation as R, enregistrer as E WHERE R.numTrav = T.numTrav AND E.numReserv = R.numReserv AND T.date > ? AND T.date < ? AND numType > 0 AND numtype < 4';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($dateDeb,$dateFin));
            $result = $stm->fetchAll();
        	
        	$passager = $result[0][0];
        	if($passager == null){
			$passager = 0;
		}

$sql = 'SELECT SUM(E.quantite) FROM traversee as T, reservation as R, enregistrer as E WHERE R.numTrav = T.numTrav AND E.numReserv = R.numReserv AND T.date > ? AND T.date < ? AND numType > 3 AND numtype < 6';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($dateDeb,$dateFin));
            $result = $stm->fetchAll();
        	
        	$vehB = $result[0][0];
        	if($vehB == null){
			$vehB = 0;
		}

$sql = 'SELECT SUM(E.quantite) FROM traversee as T, reservation as R, enregistrer as E WHERE R.numTrav = T.numTrav AND E.numReserv = R.numReserv AND T.date > ? AND T.date < ? AND numType > 5 AND numtype < 9';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($dateDeb,$dateFin));
            $result = $stm->fetchAll();
        	
        	$vehC = $result[0][0];
        	if($vehC == null){
			$vehC = 0;
		}

?> </br><table class="col-lg-12 col-md-12 col-sm-12 text-center py-4">
 		<thead>
 		<tr>
 			<th colspan="6">Réservation Période</th>
 			<tr><td>Periode</td><td>CA</td><td>NbTotalReserv</td><td>Passager</td><td>VéhiculeB</td><td>VéhiculeC</td></tr>
 		</tr>
 		</thead>
 		<tbody>
 		<tr><td><?php echo($dateDeb."/".$dateFin) ?></td><td><?php echo($ca) ?></td><td><?php echo($nbTotReserv) ?></td><td> <?php echo($passager) ?></td><td><?php echo($vehB) ?></td><td><?php echo($vehC) ?></td></tr>
 		</tbody>
 	</table>
<?php }?>

</div>
</section>
</header>
<!-- Footer -->
        <footer class="py-5 bg-dark">
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
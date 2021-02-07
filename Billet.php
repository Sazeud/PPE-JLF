<!DOCTYPE html>
<html>
    <head>
        <title>Marie Team | Billet</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="bootstrap.css">
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
            Page du billet test
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
    <?php
        if(isset($_SESSION['username'])){
    ?>
    <div class="billet">   
            <p>Votre réservation : </p>
        <?php
            $sql = 'SELECT DATE_FORMAT(T.date, \'%d/%m/%Y\') as dateT, T.heure, L.idPort, L.idPort_ARRIVEE, T.date FROM traversee as T, liaison as L WHERE numTrav = ? AND T.code = L.code';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['numTraversee']));
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

            $nomPortArr = $result[0]['nom'];

            echo "Laison : ".$nomPortDep.' - '.$nomPortArr.'<br>';
            echo "Traversée n° : ".$_SESSION['numTraversee']." le : ".$dateT." à : ".$heure.'<br>'.'<br>';
            echo "Réservation enregistré sous le n° : ".'<br>';


            //récupère les données saisies sur la page Reservation
            echo 'Nom : '.$_POST["nom"].'<br>';
            echo 'Adresse : ' .$_POST["adresse"].'<br>';
            echo 'Code Postal : ' .$_POST["cp"].'<br>';
            echo 'Ville : ' .$_POST["ville"].'<br>'.'<br>';               
        ?>
        <p>Nombres de personnes et véhicules comprient dans votre réservation :</p>

        <?php
            if($_POST['nAdulte'] != null ){ 
                echo "Adulte : ".htmlspecialchars($_POST['nAdulte']).'<br>';
            }
            if($_POST['nJunior'] != null){
                echo "Junior : ".htmlspecialchars($_POST['nJunior']).'<br>';
            }
            if($_POST['nEnfant'] != null){
                echo "Enfant : ".htmlspecialchars($_POST['nEnfant']).'<br>';
            }
            if($_POST['nVoitInf4'] != null){
                echo "Voiture Inférieur à 4m : ".htmlspecialchars($_POST['nVoitInf4']).'<br>';
            }
            if($_POST['nVoitInf5'] != null){
                echo "Voiture Inférieur à 5m : ".htmlspecialchars($_POST['nVoitInf5']);
            }
            if($_POST['nFourgon'] != null){
                echo "Fourgon : ".htmlspecialchars($_POST['nFourgon']).'<br>';
            }
            if($_POST['nCampingCar'] != null){
                echo "CampingCar : ".htmlspecialchars($_POST['nCampingCar']).'<br>';
            }
            if($_POST['nCamion'] != null){
                echo "Camion : ".htmlspecialchars($_POST['nCamion']).'<br>';
            }
        ?>
    </div>
        <?php } 
            else{
                header('Location: reservation.php?reservation='.$_SESSION['numTraversee']);
            }
        ?>
    </body>
</html>
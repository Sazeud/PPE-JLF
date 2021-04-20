<!DOCTYPE html>
<html>
    <head>
        <title>Marie Team | Billet</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="billet.css">
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

            $sql = 'SELECT placesA, placesB, placesC FROM traversee WHERE numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['numTraversee']));
            $result = $stm->fetchAll();

            $totPlacesA = $_POST['nAdulte'] + $_POST['nEnfant'] + $_POST['nJunior'];
            if(isset($_POST['nVoitInf4']) && isset($_POST['nVoitInf5'])){
                $totPlacesB = $_POST['nVoitInf4'] + $_POST['nVoitInf5'];
            }
            else{
                $totPlacesB = 0;
            }
            if(isset($_POST['nFourgon']) && isset($_POST['nCampingCar']) && isset($_POST['nCamion'])){
                $totPlacesC = $_POST['nFourgon'] + $_POST['nCampingCar'] + $_POST['nCamion'];
            }
            else{
                $totPlacesC = 0;
            }
            $numTrav = $_SESSION['numTraversee'];

            if($totPlacesA > $result[0]['placesA']){
                header("Location: reservation.php?reservation=$numTrav&erreur=A");
            }
            else if($totPlacesB > $result[0]['placesB']){
                header("Location: reservation.php?reservation=$numTrav&erreur=B");
            }
            else if($totPlacesC > $result[0]['placesC']){
                header("Location: reservation.php?reservation=$numTrav&erreur=C");
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
            Page du billet
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
    <h1>Votre Réservation</h1>
    <div class="billet">   
        <?php
            $sql = 'SELECT code_uti FROM utilisateur WHERE nom_uti = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['username']));
            $result = $stm->fetchAll();

            $codeuti = $result[0]['code_uti'];

            $sql = 'SELECT pt_fid FROM utilisateur WHERE code_uti = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($codeuti));
            $result = $stm->fetchAll();

            $pts_fidelite = $result[0]['pt_fid'];

            if(isset($_POST['fidelite']) AND $pts_fidelite >= 100 AND $_SESSION['reduction'] == 1){

                $pts_fidelite = $pts_fidelite - 100;

                $sql = 'UPDATE utilisateur SET pt_fid = ? WHERE code_uti = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($pts_fidelite, $codeuti));

                $_SESSION['reduction'] = 2;
            }

            $sql = 'SELECT * FROM reservation WHERE codeuti = ? AND numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($codeuti,$_SESSION['numTraversee']));
            $count = $stm->rowCount();

            $ajout = 0;

            if($count == 0){
                $numBon = false;

                 while(!$numBon){
                    $numTest = random_int(1, 100000);

                    $sql = 'SELECT * FROM reservation WHERE numReserv = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numTest));
                    $countNum = $stm->rowCount();

                    if($countNum ==0){
                        $numReservation = $numTest;
                        $numBon = true;
                    }             
                }

                $sql = 'INSERT INTO reservation (numReserv,nom,adr,cp,ville,numTrav,codeuti) VALUES (?,?,?,?,?,?,?)';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($numReservation,$_POST["nom"],$_POST["adresse"],$_POST["cp"],$_POST["ville"],$_SESSION['numTraversee'],$codeuti));

                $ajout = 1;
            }

            $sql = 'SELECT numReserv FROM reservation WHERE codeuti = ? AND numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($codeuti, $_SESSION['numTraversee']));
            $result = $stm->fetchAll();

            $numReservation = $result[0]['numReserv'];

            $sql = 'SELECT DATE_FORMAT(T.date, \'%d/%m/%Y\') as dateT, T.date as dateP, T.heure, L.idPort, L.idPort_ARRIVEE, T.date FROM traversee as T, liaison as L WHERE numTrav = ? AND T.code = L.code';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['numTraversee']));
            $result = $stm->fetchAll();

            $dateT = $result[0]['dateT'];
            $dateP = $result[0]['dateP'];
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

            echo "<h2>".$nomPortDep.' - '.$nomPortArr.'</h2><br>';
            echo "<center>Traversée n° : ".$_SESSION['numTraversee'].", le ".$dateT." à ".$heure.'</center><br>';


            //récupère les données saisies sur la page Reservation
            ?>
                <p>Différentes informations par rapport à votre réservation :</p>
            <?php
            echo "<ul><li>Réservation enregistré sous le n° : ".$numReservation.'</li>';
            echo '<li>Prénom : '.$_POST["prenom"].'</li>';
            echo '<li>Nom : '.$_POST["nom"].'</li>';
            echo '<li>Adresse : ' .$_POST["adresse"].'</li>';
            echo '<li>Code Postal : ' .$_POST["cp"].'</li>';
            echo '<li>Ville : ' .$_POST["ville"].'</li></ul>';    


            $sql='SELECT T.dateDeb FROM tarifer as T,periode as P WHERE T.dateDeb = P.dateDeb AND ? > P.dateDeb AND ? < P.dateFin';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($dateP, $dateP));
            $result = $stm->fetchAll();

            $periode = $result[0]['dateDeb'];  

            $sql = 'SELECT code FROM traversee WHERE numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['numTraversee']));
            $result = $stm->fetchAll();

            $numLiaison = $result[0]['code'];
            $totalPaye = 0;
        ?>
        <p>Nombres de personnes et véhicules que comprend votre réservation :</p>
        <ul>
        <?php
            $sql = 'SELECT placesA, placesB, placesC FROM traversee WHERE numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['numTraversee']));
            $result = $stm->fetchAll();

            $placesA = $result[0]['placesA'];
            $placesB = $result[0]['placesB'];
            $placesC = $result[0]['placesC'];

            if($_POST['nAdulte'] > 0 && isset($_POST['nAdulte'])){ 
                echo "<li>Adulte : ".htmlspecialchars($_POST['nAdulte']).'</li>';

                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 1';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                $totalPaye = $totalPaye + $_POST['nAdulte']*$result[0]['tarif'];

                $sql = 'SELECT * FROM enregistrer WHERE numType = 1 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                $newPlacesA = $placesA - $_POST['nAdulte'];

                if($count == 0 && $ajout == 1){
                    $sql = 'INSERT INTO enregistrer VALUES (1,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nAdulte']));

                    $sql = 'UPDATE traversee SET placesA = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesA, $numTrav));
                }
            }
            if($_POST['nJunior'] > 0 && isset($_POST['nJunior'])){
                echo "<li>Junior : ".htmlspecialchars($_POST['nJunior']).'</li>';

                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 2';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                $totalPaye = $totalPaye + $_POST['nJunior']*$result[0]['tarif'];

                $sql = 'SELECT * FROM enregistrer WHERE numType = 2 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                $newPlacesA = $placesA - $_POST['nJunior'];

                if($count == 0 && $ajout == 1){
                    $sql = 'INSERT INTO enregistrer VALUES (2,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nJunior']));

                    $sql = 'UPDATE traversee SET placesA = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesA, $numTrav));
                }
            }
            if($_POST['nEnfant'] > 0 && isset($_POST['nEnfant'])){
                echo "<li>Enfant : ".htmlspecialchars($_POST['nEnfant']).'</li>';

                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 3';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                $totalPaye = $totalPaye + $_POST['nEnfant']*$result[0]['tarif'];

                $sql = 'SELECT * FROM enregistrer WHERE numType = 3 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                $newPlacesA = $placesA - $_POST['nEnfant'];

                if($count == 0 && $ajout == 1){
                    $sql = 'INSERT INTO enregistrer VALUES (3,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nEnfant']));

                    $sql = 'UPDATE traversee SET placesA = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesA, $numTrav));
                }
            }
            if($_POST['nVoitInf4'] > 0 && isset($_POST['nVoitInf4'])){
                echo "<li>Voiture Inférieur à 4m : ".htmlspecialchars($_POST['nVoitInf4']).'</li>';

                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 4';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                $totalPaye = $totalPaye + $_POST['nVoitInf4']*$result[0]['tarif'];

                $sql = 'SELECT * FROM enregistrer WHERE numType = 4 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                $newPlacesB = $placesB - $_POST['nVoitInf4'];

                if($count == 0 && $ajout == 1){
                    $sql = 'INSERT INTO enregistrer VALUES (4,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nVoitInf4']));

                    $sql = 'UPDATE traversee SET placesB = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesB, $numTrav));
                }
            }
            if($_POST['nVoitInf5'] > 0 && isset($_POST['nVoitInf5'])){
                echo "<li>Voiture Inférieur à 5m : ".htmlspecialchars($_POST['nVoitInf5']).'</li>';

                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 5';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                $totalPaye = $totalPaye + $_POST['nVoitInf5']*$result[0]['tarif'];

                $sql = 'SELECT * FROM enregistrer WHERE numType = 5 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                $newPlacesB = $placesB - $_POST['nVoitInf5'];

                if($count == 0 && $ajout == 1){
                    $sql = 'INSERT INTO enregistrer VALUES (5,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nVoitInf5']));

                    $sql = 'UPDATE traversee SET placesB = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesB, $numTrav));
                }
            }
            if(isset($_POST['nFourgon']) && $_POST['nFourgon'] > 0 ){
                echo "<li>Fourgon : ".htmlspecialchars($_POST['nFourgon']).'</li>';

                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 6';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                $totalPaye = $totalPaye + $_POST['nFourgon']*$result[0]['tarif'];

                $sql = 'SELECT * FROM enregistrer WHERE numType = 6 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                $newPlacesC = $placesC - $_POST['nFourgon'];

                if($count == 0 && $ajout == 1){
                    $sql = 'INSERT INTO enregistrer VALUES (6,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nFourgon']));

                    $sql = 'UPDATE traversee SET placesC = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesB, $numTrav));
                }
            }
            if(isset($_POST['nCampingCar']) && $_POST['nCampingCar'] > 0){
                echo "<li>CampingCar : ".htmlspecialchars($_POST['nCampingCar']).'</li>';

                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 7';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                $totalPaye = $totalPaye + $_POST['nCampingCar']*$result[0]['tarif'];

                $sql = 'SELECT * FROM enregistrer WHERE numType = 7 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                $newPlacesC = $placesC - $_POST['nCampingCar'];

                if($count == 0 && $ajout == 1){
                    $sql = 'INSERT INTO enregistrer VALUES (7,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nCampingCar']));

                    $sql = 'UPDATE traversee SET placesC = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesB, $numTrav));
                }
            }
            if(isset($_POST['nCamion']) && $_POST['nCamion'] > 0){
                echo "<li>Camion : ".htmlspecialchars($_POST['nCamion']).'</li>';

                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 8';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                $totalPaye = $totalPaye + $_POST['nCamion']*$result[0]['tarif'];

                $sql = 'SELECT * FROM enregistrer WHERE numType = 8 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                $newPlacesC = $placesC - $_POST['nCamion'];

                if($count == 0 && $ajout == 1){
                    $sql = 'INSERT INTO enregistrer VALUES (8,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nCamion']));

                    $sql = 'UPDATE traversee SET placesC = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesB, $numTrav));
                }
            }
            echo "</ul>";

            $sql = 'SELECT prix FROM reservation WHERE numReserv = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($numReservation));
            $result = $stm->fetchAll();

            $prix = $result[0]['prix'];

            if(isset($_POST['fidelite'])){
                $nouveauTotal = $totalPaye - ($totalPaye * 0.25);
                echo 'Vous avez payé un total de : '.$nouveauTotal.' euros en obtenant une réducation de '.$totalPaye*0.25.' grâce à vos points de fidélités';
                if($prix == 0){
                    $sql = 'UPDATE reservation SET prix = ? WHERE numReserv = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($nouveauTotal, $numReservation));
                }
            }
            else{
                echo 'Vous avez payé un total de : '.$totalPaye.' euros';
                if($prix == 0){
                    $sql = 'UPDATE reservation SET prix = ? WHERE numReserv = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($totalPaye, $numReservation));
                }
            }

            $pointGagne = 0;

            if($ajout == 1){
                $sql = 'SELECT DATE(NOW()) as d';
                $stm = $bdd->prepare($sql);
                $stm->execute();
                $result = $stm->fetchAll();

                $dateActuelle = $result[0]['d'];

                $dateAct = new DateTime($dateActuelle);
                $dateT = new DateTime($date);

                $diffdate = $dateAct->diff($dateT);

                if(($diffdate->m) >= 2){
                    $newFid = $pts_fidelite + 25;

                    $sql = 'UPDATE utilisateur SET pt_fid = ? WHERE code_uti = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newFid, $codeuti));

                    $pointGagne = 1;
                }
            }

            $sql = 'SELECT pt_fid FROM utilisateur WHERE code_uti = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($codeuti));
            $result = $stm->fetchAll();

            $pts_fidelite = $result[0]['pt_fid'];

            echo '<br>Il vous reste un total de '.$pts_fidelite.' points de fidélités.';

            if($pointGagne == 1){
                echo '<br>Vous avez gagné 25 points de fidélités grâce à cette réservation';
            }
        ?>
        <br><br>
        <p>Vous pouvez revenir à <a href="index.php">l'accueil</a> pour faire d'autres réservations ou suivre vos réservations sur votre <a href="profile.php">profil</a>.</p>
    </div>
        <?php } 
            else{
                header('Location: reservation.php?reservation='.$_SESSION['numTraversee']);
            }
        ?>
    </body>
</html>
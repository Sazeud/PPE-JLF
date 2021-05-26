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

            //On récupère le nombre de places restantes dans chaque catégorie pour la traversée concernée
            $sql = 'SELECT placesA, placesB, placesC FROM traversee WHERE numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['numTraversee']));
            $result = $stm->fetchAll();

            //Nombre de passagers totale de la réservation
            $totPlacesA = $_POST['nAdulte'] + $_POST['nEnfant'] + $_POST['nJunior'];

            //On vérifie que les variables sont bien déclarées
            if(isset($_POST['nVoitInf4']) && isset($_POST['nVoitInf5'])){
                //Nombre totale de véhicules de type B
                $totPlacesB = $_POST['nVoitInf4'] + $_POST['nVoitInf5'];
            }
            else{
                //Nombre totale de véhicules de type B si les variables ne sont pas crées
                $totPlacesB = 0;
            }

            //On vérifie que les variables sont bien déclarées
            if(isset($_POST['nFourgon']) && isset($_POST['nCampingCar']) && isset($_POST['nCamion'])){
                //Nombre totale de véhicules de type 
                $totPlacesC = $_POST['nFourgon'] + $_POST['nCampingCar'] + $_POST['nCamion'];
            }
            else{
                //Nombre totale de véhicules de type C si les variables ne sont pas crées
                $totPlacesC = 0;
            }
            $numTrav = $_SESSION['numTraversee'];

            //On vérifie que le nombre de places réservés par catégorie n'est pas supérieur au nombre de places restantes
            if($totPlacesA > $result[0]['placesA']){
                //Renvoi vers la page réservation avec le message d'erreur places de catégorie A insuffisante
                header("Location: reservation.php?reservation=$numTrav&erreur=A");
            }
            else if($totPlacesB > $result[0]['placesB']){
                //Renvoi vers la page réservation avec le message d'erreur places de catégorie B insuffisante
                header("Location: reservation.php?reservation=$numTrav&erreur=B");
            }
            else if($totPlacesC > $result[0]['placesC']){
                //Renvoi vers la page réservation avec le message d'erreur places de catégorie C insuffisante
                header("Location: reservation.php?reservation=$numTrav&erreur=C");
            }
	?>

    <!-- Barre de navigation du site MarieTeam -->
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
        //On vérifie que l'utilisateur est bien connecté
        if(isset($_SESSION['username'])){
    ?>
    <h1>Votre Réservation</h1>
    <div class="billet">   
        <?php
            //On récupère le code utilisateur du compte
            $sql = 'SELECT code_uti FROM utilisateur WHERE nom_uti = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['username']));
            $result = $stm->fetchAll();

            $codeuti = $result[0]['code_uti'];

            //On récupère ses points de fidélité
            $sql = 'SELECT pt_fid FROM utilisateur WHERE code_uti = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($codeuti));
            $result = $stm->fetchAll();

            $pts_fidelite = $result[0]['pt_fid'];

            //On vérifie si l'utilisateur a utilisé ses points de fidélité pour réaliser une réduction
            if(isset($_POST['fidelite']) AND $pts_fidelite >= 100 AND $_SESSION['reduction'] == 1){

                $pts_fidelite = $pts_fidelite - 100;

                //On enlève les 100 points de fidélité du compte utilisateur
                $sql = 'UPDATE utilisateur SET pt_fid = ? WHERE code_uti = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($pts_fidelite, $codeuti));

                //On passe la variable a 2 pour montrer que la réduction a été réalisée
                $_SESSION['reduction'] = 2;
            }

            //Vérifie que la réservation n'a pas déjà été faite par le même utilisateur
            $sql = 'SELECT * FROM reservation WHERE codeuti = ? AND numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($codeuti,$_SESSION['numTraversee']));
            $count = $stm->rowCount();

            $ajout = 0;

            //Si le count est à zero alors on peut créer la réservation
            if($count == 0){
                $numBon = false;

                //On crée aléatoirement le numéro de réservation
                while(!$numBon){
                    $numTest = random_int(1, 100000);

                    //On vérifie que le numéro de réservation n'existe pas
                    $sql = 'SELECT * FROM reservation WHERE numReserv = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numTest));
                    $countNum = $stm->rowCount();

                    //Si le numéro n'existe pas alors on le stock dans une variable et on sort de la boucle
                    if($countNum ==0){
                        $numReservation = $numTest;
                        $numBon = true;
                    }             
                }

                //On insére la nouvelle réservation 
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

            //On récupère les différentes informations de traversée afin de les afficher sur le billet
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

            //On récupère le nom du port de départ
            $sql = 'SELECT nom FROM port WHERE idPort = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($portDep));
            $result = $stm->fetchAll();

            $nomPortDep = $result[0]['nom'];

            //On récupère le nom du port d'arrivée
            $sql = 'SELECT nom FROM port WHERE idPort = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($portArr));
            $result = $stm->fetchAll();

            $nomPortArr = $result[0]['nom'];

            //On affiche les informations par rapport à la réservation
            echo "<h2>".$nomPortDep.' - '.$nomPortArr.'</h2><br>';
            echo "<center>Traversée n° : ".$_SESSION['numTraversee'].", le ".$dateT." à ".$heure.'</center><br>';

            ?>
                <p>Différentes informations par rapport à votre réservation :</p>
            <?php
            echo "<ul><li>Réservation enregistré sous le n° : ".$numReservation.'</li>';
            echo '<li>Prénom : '.$_POST["prenom"].'</li>';
            echo '<li>Nom : '.$_POST["nom"].'</li>';
            echo '<li>Adresse : ' .$_POST["adresse"].'</li>';
            echo '<li>Code Postal : ' .$_POST["cp"].'</li>';
            echo '<li>Ville : ' .$_POST["ville"].'</li></ul>';    

            //On récupère la date de début de la période
            $sql='SELECT T.dateDeb FROM tarifer as T,periode as P WHERE T.dateDeb = P.dateDeb AND ? > P.dateDeb AND ? < P.dateFin';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($dateP, $dateP));
            $result = $stm->fetchAll();

            $periode = $result[0]['dateDeb'];  

            //On récupère le code de liaison
            $sql = 'SELECT code FROM traversee WHERE numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['numTraversee']));
            $result = $stm->fetchAll();

            $numLiaison = $result[0]['code'];
            $totalPaye = 0;
        ?>
        <!-- Informations concernant les passagers et véhicules sur la réservation -->
        <p>Nombres de personnes et véhicules que comprend votre réservation :</p>
        <ul>
        <?php
            //On récupère le nombre de places restantes par catégorie pour la traversée
            $sql = 'SELECT placesA, placesB, placesC FROM traversee WHERE numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['numTraversee']));
            $result = $stm->fetchAll();

            $placesA = $result[0]['placesA'];
            $placesB = $result[0]['placesB'];
            $placesC = $result[0]['placesC'];

            //On verifie si la variable est initialisée et supérieure à 0 concernant le nombre d'adulte
            if($_POST['nAdulte'] > 0 && isset($_POST['nAdulte'])){
                echo "<li>Adulte : ".htmlspecialchars($_POST['nAdulte']).'</li>';

                //On récupère le tarif pour cette catégorie 
                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 1';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                //On ajoute au total à payer le tarif de la catégorie
                $totalPaye = $totalPaye + $_POST['nAdulte']*$result[0]['tarif'];

                //On verifie s'il n'y a pas déjà des données par rapport à ce numéro de réservation
                $sql = 'SELECT * FROM enregistrer WHERE numType = 1 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                //On crée une variable qui concerne le nombre de places restant dans la traversée
                $newPlacesA = $placesA - $_POST['nAdulte'];

                //On vérifie que l'ajout n'a pas été fait avant de le faire
                if($count == 0 && $ajout == 1){
                    //On insère dans enregistrer le nombre d'adulte de cette réservation
                    $sql = 'INSERT INTO enregistrer VALUES (1,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nAdulte']));

                    //On met à jour le nombre de places A
                    $sql = 'UPDATE traversee SET placesA = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesA, $numTrav));
                }
            }

            //On verifie si la variable est initialisée et supérieure à 0 concernant le nombre de junior
            if($_POST['nJunior'] > 0 && isset($_POST['nJunior'])){
                echo "<li>Junior : ".htmlspecialchars($_POST['nJunior']).'</li>';

                //On récupère le tarif pour cette catégorie 
                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 2';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                //On ajoute au total à payer le tarif de la catégorie
                $totalPaye = $totalPaye + $_POST['nJunior']*$result[0]['tarif'];

                //On verifie s'il n'y a pas déjà des données par rapport à ce numéro de réservation
                $sql = 'SELECT * FROM enregistrer WHERE numType = 2 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                //On crée une variable qui concerne le nombre de places restant dans la traversée
                $newPlacesA = $placesA - $_POST['nJunior'];

                //On vérifie que l'ajout n'a pas été fait avant de le faire
                if($count == 0 && $ajout == 1){
                    //On insère dans enregistrer le nombre de junior de cette réservation
                    $sql = 'INSERT INTO enregistrer VALUES (2,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nJunior']));

                    //On met à jour le nombre de places A
                    $sql = 'UPDATE traversee SET placesA = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesA, $numTrav));
                }
            }

            //On verifie si la variable est initialisée et supérieure à 0 concernant le nombre d'enfant
            if($_POST['nEnfant'] > 0 && isset($_POST['nEnfant'])){
                echo "<li>Enfant : ".htmlspecialchars($_POST['nEnfant']).'</li>';

                //On récupère le tarif pour cette catégorie 
                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 3';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                //On ajoute au total à payer le tarif de la catégorie
                $totalPaye = $totalPaye + $_POST['nEnfant']*$result[0]['tarif'];

                //On verifie s'il n'y a pas déjà des données par rapport à ce numéro de réservation
                $sql = 'SELECT * FROM enregistrer WHERE numType = 3 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                //On crée une variable qui concerne le nombre de places restant dans la traversée
                $newPlacesA = $placesA - $_POST['nEnfant'];

                //On vérifie que l'ajout n'a pas été fait avant de le faire
                if($count == 0 && $ajout == 1){
                    //On insère dans enregistrer le nombre d'enfant de cette réservation
                    $sql = 'INSERT INTO enregistrer VALUES (3,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nEnfant']));

                    //On met à jour le nombre de places A
                    $sql = 'UPDATE traversee SET placesA = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesA, $numTrav));
                }
            }

            //On verifie si la variable est initialisée et supérieure à 0 concernant le nombre de véhicule inférieur à 4m
            if($_POST['nVoitInf4'] > 0 && isset($_POST['nVoitInf4'])){
                echo "<li>Voiture Inférieur à 4m : ".htmlspecialchars($_POST['nVoitInf4']).'</li>';

                //On récupère le tarif pour cette catégorie 
                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 4';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                //On ajoute au total à payer le tarif de la catégorie
                $totalPaye = $totalPaye + $_POST['nVoitInf4']*$result[0]['tarif'];

                //On verifie s'il n'y a pas déjà des données par rapport à ce numéro de réservation
                $sql = 'SELECT * FROM enregistrer WHERE numType = 4 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                //On crée une variable qui concerne le nombre de places restant dans la traversée
                $newPlacesB = $placesB - $_POST['nVoitInf4'];

                //On vérifie que l'ajout n'a pas été fait avant de le faire
                if($count == 0 && $ajout == 1){
                    //On insère dans enregistrer le nombre de véhicule inférieur à 4m de cette réservation
                    $sql = 'INSERT INTO enregistrer VALUES (4,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nVoitInf4']));

                    //On met à jour le nombre de places A
                    $sql = 'UPDATE traversee SET placesB = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesB, $numTrav));
                }
            }

            //On verifie si la variable est initialisée et supérieure à 0 concernant le nombre de véhicule inférieur à 5m
            if($_POST['nVoitInf5'] > 0 && isset($_POST['nVoitInf5'])){
                echo "<li>Voiture Inférieur à 5m : ".htmlspecialchars($_POST['nVoitInf5']).'</li>';

                //On récupère le tarif pour cette catégorie 
                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 5';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                //On ajoute au total à payer le tarif de la catégorie
                $totalPaye = $totalPaye + $_POST['nVoitInf5']*$result[0]['tarif'];

                //On verifie s'il n'y a pas déjà des données par rapport à ce numéro de réservation
                $sql = 'SELECT * FROM enregistrer WHERE numType = 5 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                //On crée une variable qui concerne le nombre de places restant dans la traversée
                $newPlacesB = $placesB - $_POST['nVoitInf5'];

                //On vérifie que l'ajout n'a pas été fait avant de le faire
                if($count == 0 && $ajout == 1){
                    //On insère dans enregistrer le nombre de véhicule inférieur à 4m de cette réservation
                    $sql = 'INSERT INTO enregistrer VALUES (5,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nVoitInf5']));

                    //On met à jour le nombre de places B
                    $sql = 'UPDATE traversee SET placesB = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesB, $numTrav));
                }
            }

            //On verifie si la variable est initialisée et supérieure à 0 concernant le nombre de fourgon
            if(isset($_POST['nFourgon']) && $_POST['nFourgon'] > 0 ){
                echo "<li>Fourgon : ".htmlspecialchars($_POST['nFourgon']).'</li>';

                //On récupère le tarif pour cette catégorie 
                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 6';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                //On ajoute au total à payer le tarif de la catégorie
                $totalPaye = $totalPaye + $_POST['nFourgon']*$result[0]['tarif'];

                //On verifie s'il n'y a pas déjà des données par rapport à ce numéro de réservation
                $sql = 'SELECT * FROM enregistrer WHERE numType = 6 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                //On crée une variable qui concerne le nombre de places restant dans la traversée
                $newPlacesC = $placesC - $_POST['nFourgon'];

                //On vérifie que l'ajout n'a pas été fait avant de le faire
                if($count == 0 && $ajout == 1){
                    //On insère dans enregistrer le nombre de véhicule inférieur à 4m de cette réservation
                    $sql = 'INSERT INTO enregistrer VALUES (6,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nFourgon']));

                    //On met à jour le nombre de places B
                    $sql = 'UPDATE traversee SET placesC = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesB, $numTrav));
                }
            }

            //On verifie si la variable est initialisée et supérieure à 0 concernant le nombre de camping car
            if(isset($_POST['nCampingCar']) && $_POST['nCampingCar'] > 0){
                echo "<li>CampingCar : ".htmlspecialchars($_POST['nCampingCar']).'</li>';

                //On récupère le tarif pour cette catégorie 
                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 7';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                //On ajoute au total à payer le tarif de la catégorie
                $totalPaye = $totalPaye + $_POST['nCampingCar']*$result[0]['tarif'];

                //On verifie s'il n'y a pas déjà des données par rapport à ce numéro de réservation
                $sql = 'SELECT * FROM enregistrer WHERE numType = 7 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                //On crée une variable qui concerne le nombre de places restant dans la traversée
                $newPlacesC = $placesC - $_POST['nCampingCar'];

                //On vérifie que l'ajout n'a pas été fait avant de le faire
                if($count == 0 && $ajout == 1){
                    //On insère dans enregistrer le nombre de véhicule inférieur à 4m de cette réservation
                    $sql = 'INSERT INTO enregistrer VALUES (7,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nCampingCar']));

                    //On met à jour le nombre de places C
                    $sql = 'UPDATE traversee SET placesC = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesB, $numTrav));
                }
            }

            //On verifie si la variable est initialisée et supérieure à 0 concernant le nombre de camion
            if(isset($_POST['nCamion']) && $_POST['nCamion'] > 0){
                echo "<li>Camion : ".htmlspecialchars($_POST['nCamion']).'</li>';

                //On récupère le tarif pour cette catégorie 
                $sql = 'SELECT tarif FROM tarifer WHERE dateDeb = ? AND code = ? AND numType = 8';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($periode,$numLiaison));
                $result = $stm->fetchAll();

                //On ajoute au total à payer le tarif de la catégorie
                $totalPaye = $totalPaye + $_POST['nCamion']*$result[0]['tarif'];

                //On verifie s'il n'y a pas déjà des données par rapport à ce numéro de réservation
                $sql = 'SELECT * FROM enregistrer WHERE numType = 8 AND numReserv = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_SESSION['numTraversee']));
                $count = $stm->rowCount();

                //On crée une variable qui concerne le nombre de places restant dans la traversée
                $newPlacesC = $placesC - $_POST['nCamion'];

                //On vérifie que l'ajout n'a pas été fait avant de le faire
                if($count == 0 && $ajout == 1){
                    //On insère dans enregistrer le nombre de véhicule inférieur à 4m de cette réservation
                    $sql = 'INSERT INTO enregistrer VALUES (8,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($numReservation,$_POST['nCamion']));

                    //On met à jour le nombre de places C
                    $sql = 'UPDATE traversee SET placesC = ? WHERE numTrav = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newPlacesB, $numTrav));
                }
            }
            echo "</ul>";

            //On récupère le prix de la réservation
            $sql = 'SELECT prix FROM reservation WHERE numReserv = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($numReservation));
            $result = $stm->fetchAll();

            $prix = $result[0]['prix'];

            //Si les points de fidélités sont utilisés, on applique la réduction
            if(isset($_POST['fidelite'])){
                $nouveauTotal = $totalPaye - ($totalPaye * 0.25);
                echo 'Vous avez payé un total de : '.$nouveauTotal.' euros en obtenant une réducation de '.$totalPaye*0.25.' grâce à vos points de fidélités';
                //On met à jour le prix s'il est égal à 0
                if($prix == 0){
                    $sql = 'UPDATE reservation SET prix = ? WHERE numReserv = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($nouveauTotal, $numReservation));
                }
            }
            //Sinon on met le prix de base
            else{
                echo 'Vous avez payé un total de : '.$totalPaye.' euros';
                //On met à jour le prix s'il est égal à 0
                if($prix == 0){
                    $sql = 'UPDATE reservation SET prix = ? WHERE numReserv = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($totalPaye, $numReservation));
                }
            }

            $pointGagne = 0;

            //Si la réservation est réalisée on procède au gain de points de fidélité
            if($ajout == 1){
                //Récupération de la date du jour
                $sql = 'SELECT DATE(NOW()) as d';
                $stm = $bdd->prepare($sql);
                $stm->execute();
                $result = $stm->fetchAll();

                $dateActuelle = $result[0]['d'];

                $dateAct = new DateTime($dateActuelle);
                $dateT = new DateTime($date);

                //On compare la date de la traversée et la date du jour
                $diffdate = $dateAct->diff($dateT);

                //Si la différence est supérieure ou égale à 2 mois alors l'utilisateur gagne 25 points de fidélité
                if(($diffdate->m) >= 2){
                    $newFid = $pts_fidelite + 25;

                    //Mise à jour des points de fidélité
                    $sql = 'UPDATE utilisateur SET pt_fid = ? WHERE code_uti = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($newFid, $codeuti));

                    $pointGagne = 1;
                }
            }

            //On récupère le nombre de points de fidélités pour les afficher
            $sql = 'SELECT pt_fid FROM utilisateur WHERE code_uti = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($codeuti));
            $result = $stm->fetchAll();

            $pts_fidelite = $result[0]['pt_fid'];

            echo '<br>Il vous reste un total de '.$pts_fidelite.' points de fidélités.';

            //Si vous avez gagné des points de fidélités cela est affiché
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
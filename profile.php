<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Marie Team | Profil</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/one-page-wonder.css" rel="stylesheet">
  <link href="css/footer.css" rel="stylesheet">
  <link href="profile.css" rel="stylesheet">
    <?php
        session_start();
        session_regenerate_id();
        if(!isset($_SESSION['username']))
        {
            header("Location: Connexion.php?page=profile");
        }

        try{
            $bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');
        }
        catch(Exception $e){
            die('Erreur : ' .$e->getMessage());
        }
    ?>
    <head>
       <meta charset="utf-8">
       <title>Profile de <?php echo $_SESSION['username']; ?></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <!-- Barre de navigation -->
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
        <section class="container bg-secondary py-5">
        <?php 
    //Si on a choisit de voir nos réservations, cela vérifie que la variable est bien attribuée avec le choix reservation
    if(isset($_GET['choix']) && $_GET['choix'] == "reservation"){?>
        <div id="tableau" class="col-lg-12 col-md-12 col-sm-12 text-center py-4">
            <h1>Mes réservations</h1>
            <table>
                <thead>
                  <tr>
                    <th>Num.Reservation</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Date Départ</th>
                    <th>Heure Départ</th>
                  </tr>
                </thead>
        <?php  
            //On récupère le code utilisateur du compte 
            $sql = 'SELECT code_uti FROM utilisateur WHERE nom_uti = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_SESSION['username']));
            $result = $stm->fetchAll();

            $codeUti = $result[0]['code_uti'];

            //Requête permettant de récupérer les différents numéros de réservation de l'utilisateur
            $sql = 'SELECT numReserv FROM reservation WHERE codeuti = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($codeUti));
            $result = $stm->fetchAll();

            foreach($result as $row){

            $numReserv = $row['numReserv'];

            //On récupère les données à afficher dans le tableau des réservations avec le numéro de traversée
            $sql = 'SELECT numTrav FROM reservation WHERE numReserv = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($numReserv));
            $result = $stm->fetchAll();

            $numTrav = $result[0]['numTrav'];

            //On récupère le code de liaison
            $sql = 'SELECT code FROM traversee WHERE numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($numTrav));
            $result = $stm->fetchAll();

            $codeLiaison = $result[0]['code'];

            //On récupère l'id du port de départ et d'arrivée
            $sql = 'SELECT idPort, idPort_ARRIVEE FROM liaison WHERE code = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($codeLiaison));
            $result = $stm->fetchAll();

            $idPortDep = $result[0]['idPort'];
            $idPortArr = $result[0]['idPort_ARRIVEE'];

            //On utilise les id pour récupérer le nom du port de départ et le nom du port d'arrivée
            $sql = 'SELECT nom FROM port WHERE idPort = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($idPortDep));
            $result = $stm->fetchAll();

            $PortDep = $result[0]['nom'];

            $sql = 'SELECT nom FROM port WHERE idPort = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($idPortArr));
            $result = $stm->fetchAll();

            $PortArr = $result[0]['nom'];

            //On récupère la date de la traversée
            $sql = 'SELECT DATE_FORMAT(date, \'%d/%m/%Y\') as date_reo FROM  traversee WHERE numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($numTrav));
            $result = $stm->fetchAll();

            $dateTrav = $result[0]['date_reo'];

            //Puis l'heure de la traversée
            $sql = 'SELECT heure FROM traversee WHERE numTrav = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($numTrav));
            $result = $stm->fetchAll();

            $heure = $result[0]['heure'];
        ?>
            <!-- On affiche toutes ces informations dans le tableau des réservations -->
                <tr>
                    <td><?php echo $numReserv?></td>
                    <td><?php echo $PortDep?></td>
                    <td><?php echo $PortArr?></td>
                    <td><?php echo $dateTrav?></td>
                    <td><?php echo $heure?></td>
                    <form action ="ReservationSuppression.php" method="POST">
                        <input type="hidden" name="suppression" value="<?php echo $numReserv?>">
                        <td class="sansbordure"><input type="submit" value="Annuler Réservation"></td>
                    </form>
                </tr>
        <?php
                }
            }
        else{ ?>
                <div id="container">
                <form action="passwordChange.php" method="POST" id="form">
                    <h1>Profil</h1><br>
                        
                    <label><b>Nom d'utilisateur : <?php echo $_SESSION['username']; ?></b></label><br><br>

                    <label><b>Nombre de points de fidélité : </b></label><br>
                    <?php 
                        $sql = 'SELECT pt_fid FROM utilisateur WHERE nom_uti = ?';
                        $stm = $bdd->prepare($sql);
                        $stm->execute(array($_SESSION['username']));
                        $result = $stm->fetchAll();

                        $points = $result[0]['pt_fid'];
                        echo '<center><p>'.$points.'</p></center>';
                    ?>
                    <label><a class="lien" href="profile.php?choix=reservation">Gérer mes réservations</a></label><br>
                    <label><b>Changer de mot de passe :</b></label><br>

                    <label><b>Mot de passe</b></label>
                    <input type="password" placeholder="Entrer votre mot de passe" name="password" required><br>

                    <label><b>Nouveau mot de passe</b></label>
                    <input type="password" placeholder="Entrer à nouveau votre mot de passe" name="newpassword" required><br>

                    <label><b>Confirmer nouveau mot de passe</b></label>
                    <input type="password" placeholder="Entrer votre nouveau mot de passe" name="verifpassword" required><br>

                    <input type="submit" id='submit' value='Changer'><br><br><br>
                    <?php
                        if(isset($_GET['changer'])){
                            $creer = $_GET['changer'];
                            if($creer == 1){
                                echo "<p>Le mot de passe a été changé avec succès</p>";
                            }
                        }
                        else if(isset($_GET['erreur'])){
                            $erreur = $_GET['erreur'];
                            if($erreur == 1){
                                echo "<p style='color:red'>Veuillez entrer les mot de passes!</p>";
                            }
                            else if($erreur == 2){
                                echo "<p style='color:red'>Le mot de passe indiqué est incorrecte!</p>";
                            }
                            else if($erreur == 3){
                                echo "<p style='color:red'>Les nouveaux mots de passes ne correspondent pas!</p>";
                            }
                        }
                     ?>
                </form>
            </div>
            <?php if($_SESSION['username'] == "admin"){ ?>
            <div id="dashboard" class="dashboard">
            <form action="gestionnaire.php" method="GET">
                <input type="submit" name="choix" value="AjoutLiaison">
            </form>
            <form action="gestionnaire.php" method="GET">
                <input type="submit" name="choix" value="ModifLiaison">
            </form>
            <form action="gestionnaire.php" method="GET">
                <input type="submit" name="choix" value="InfoReservation">
            </form>
            <form action="gestionnaire.php" method="GET">
                <input type="submit" name="choix" value="ReservationPeriode">
            </form>
            </div>
            <?php }
                if(isset($_POST['choix']) && $_POST['choix'] == 1){
                    $sql = 'INSERT INTO liaison (code,distance,idPort,idPort_ARRIVEE,idSecteur) VALUES (?,?,?,?,?)';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($_POST["code"],$_POST["distance"],$_POST["idPort"],$_POST["idPort_ARRIVEE"],$_POST["idSecteur"]));
                }
                if(isset($_POST['choix']) && $_POST['choix'] == 2){
                    $sql = 'UPDATE liaison SET distance = ?, idPort = ?, idPort_ARRIVEE = ?, idSecteur = ? WHERE code = ? ';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($_POST["distance"],$_POST["idPort"],$_POST["idPort_ARRIVEE"],$_POST["idSecteur"],$_POST["codeLiaison"]));
                }
            }?>
        </table>
      </section>
    </header>

  <!-- Footer -->
  <footer class="py-5 bg-dark ">
    <div class="container" >
      <p class="m-0 text-center text-white small">MarieTEAM présenté par JLF</p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- JS -->
        <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-migrate-1.4.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>        
        <script type="text/javascript" src="js/SmoothScroll.js"></script>
        <script type="text/javascript" src="js/jquery.scrollTo.min.js"></script>
        <script type="text/javascript" src="js/jquery.localScroll.min.js"></script>
        <script type="text/javascript" src="js/jquery.viewport.mini.js"></script>
        <script type="text/javascript" src="js/jquery.countTo.js"></script>
        <script type="text/javascript" src="js/jquery.appear.js"></script>
        <script type="text/javascript" src="js/jquery.sticky.js"></script>
        <script type="text/javascript" src="js/jquery.parallax-1.1.3.js"></script>
        <script type="text/javascript" src="js/jquery.fitvids.js"></script>
        <script type="text/javascript" src="js/owl.carousel.min.js"></script>
        <script type="text/javascript" src="js/isotope.pkgd.min.js"></script>
        <script type="text/javascript" src="js/imagesloaded.pkgd.min.js"></script>
        <script type="text/javascript" src="js/jquery.magnific-popup.min.js"></script>
        <script type="text/javascript" src="js/wow.min.js"></script>
        <script type="text/javascript" src="js/masonry.pkgd.min.js"></script>
        <script type="text/javascript" src="js/jquery.simple-text-rotator.min.js"></script>
        <script type="text/javascript" src="js/jquery.lazyload.min.js"></script>
        <script type="text/javascript" src="js/all.js"></script>

</body>

</html>
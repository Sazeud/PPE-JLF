<!DOCTYPE html>
<html>
    <?php
        session_start();
        session_regenerate_id();

        //On verifie que l'utilisateur est bien connectée à un compte
        if(!isset($_SESSION['username']))
        {
            header("Location: Connexion.php?page=profile");
        }

        //Connexion à la base de donnée
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
        <link rel="stylesheet" href="profile.css" media="screen" type="text/css" />
    </head>

    <!-- Barre de navigation du site marieteam -->
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
              Profil
            </span>
          <?php 
          if(isset($_SESSION['username'])){?>
            <form class="form-inline my-2 my-lg-0">
                <a class="nav-link" href="Deconnexion.php">Deconnexion<span class="sr-only">(current)</span></a>
            </form>
      <?php } ?>
      </div>
    </nav>
    <?php  
    //Si on a choisit de voir nos réservations, cela vérifie que la variable est bien attribuée avec le choix reservation
    if(isset($_GET['choix']) && $_GET['choix'] == "reservation"){?>
        <div id="tableau">
            <h1>Mes réservations</h1>
            <table class="table">
                <thead>
                  <tr>
                    <th>Num.Reservation</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Date Départ</th>
                    <th>Heure Départ</th>
                  </tr>
                </thead>
              <tbody>
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
            <!-- Informations de profil -->
            <div id="container">
            <form action="passwordChange.php" method="POST" id="form">
                <h1>Profil</h1><br>

                <!-- Affichage du nom d'utilisateur -->
                <label><b>Nom d'utilisateur : <?php echo $_SESSION['username']; ?></b></label><br><br>

                <!-- Affichage du nombre de points de fidélité -->
                <label><b>Nombre de points de fidélité : </b></label><br>
                <?php 
                    //Requête permettant de récupérer les points de fidélité à partir du nom d'utilisateur
                    $sql = 'SELECT pt_fid FROM utilisateur WHERE nom_uti = ?';
                    $stm = $bdd->prepare($sql);
                    $stm->execute(array($_SESSION['username']));
                    $result = $stm->fetchAll();

                    $points = $result[0]['pt_fid'];
                    echo '<center><p>'.$points.'</p></center>';
                ?>

                <!-- Voir ses réservations -->
                <label><a href="profile.php?choix=reservation">Gérer mes réservations</a></label>

                <!-- Formulaire de changement de mot de passe -->
                <label><b>Changer de mot de passe :</b></label><br>

                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer votre mot de passe" name="password" required>

                <label><b>Nouveau mot de passe</b></label>
                <input type="password" placeholder="Entrer à nouveau votre mot de passe" name="newpassword" required>

                <label><b>Confirmer nouveau mot de passe</b></label>
                <input type="password" placeholder="Entrer votre nouveau mot de passe" name="verifpassword" required>

                <input type="submit" id='submit' value='Changer'>
                <?php
                    //On vérifie que la variable est attribué 
                    if(isset($_GET['changer'])){
                        $creer = $_GET['changer'];
                        //Si créer = 1 alors le mot de passe a été changé
                        if($creer == 1){
                            echo "<p>Le mot de passe a été changé avec succès</p>";
                        }
                    }

                    //Verification en cas d'erreur, si la variable est affecté on verifie
                    else if(isset($_GET['erreur'])){
                        $erreur = $_GET['erreur'];
                        //Si des données ne sont pas fournies
                        if($erreur == 1){
                            echo "<p style='color:red'>Veuillez entrer les mot de passes!</p>";
                        }
                        //Si l'ancien mot de passe n'est pas le bon
                        else if($erreur == 2){
                            echo "<p style='color:red'>Le mot de passe indiqué est incorrecte!</p>";
                        }
                        //Si les nouveaux mot de passes sont différents
                        else if($erreur == 3){
                            echo "<p style='color:red'>Les nouveaux mots de passes ne correspondent pas!</p>";
                        }
                    }
                } ?>
            </form>
        </div>
    </body>
</html>
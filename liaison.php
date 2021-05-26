<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Marie Team | Page de liaison</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="liaison.css">
  </head>
  <body>
    <?php 
      session_start();
      session_regenerate_id();

      //Connexion à la base de donnée
      try{
        $bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');
      }
      catch(Exception $e){
        die('Erreur : '.$e->getMessage());
      }
    ?>

    <!-- Barre de navigation du site web MarieTeam -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="index.php">MarieTeam</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        </ul>
        <span class="navbar-text">
          Page des liaisons
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

    <!-- Formulaire permettant d'indiquer le secteur de la liaison recherché -->
    <div class="choix">
      <form method="get" action="liaison.php">
        <select name="secteur">
                <?php

                //Requête permettant de récupérer le nom de chaque secteur
                $sql = 'SELECT nom FROM secteur';
                $stm = $bdd->prepare($sql);
                $stm->execute();
                $result_secteur = $stm->fetchAll();

                foreach($result_secteur as $row){?>
                  <!-- Les noms sont affichés sous forme de liste -->
                  <option value=<?php echo htmlspecialchars($row['nom']);?>><?php echo htmlspecialchars($row['nom']);?></option>
                <?php
                }
              ?>
        </select><br><br>
        <input type="submit" value="Afficher" />
      </form>
    </div>
    <?php 
      //On vérifie que le secteur est bien indiqué pour afficher la suite
      if(isset($_GET['secteur'])){
    ?>
    <!-- Formulaire permettant d'indiquer la liaison recherché -->
    <div class ="choix">
      <form method="get" action="liaison.php">
        <div class="liaison">
        <select name="liaison">
                <?php

                //Requête récupérant l'id du secteur sélectionné
                $sql = 'SELECT idSecteur FROM secteur WHERE nom = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_GET['secteur']));
                $result = $stm->fetchAll();

                $idSec = $result[0]['idSecteur'];

                //On récupère les informations de chaque liaison de ce secteur
                $sql = 'SELECT code, idPort, idPort_ARRIVEE FROM liaison WHERE idSecteur = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($idSec));
                $result = $stm->fetchAll();

                foreach($result as $row){

                  //On récupère le nom du port de départ
                  $sql = 'SELECT nom FROM port WHERE idPort = ?';
                  $stm = $bdd->prepare($sql);
                  $stm->execute(array($row['idPort']));
                  $donnee = $stm->fetchAll();

                  $nomPortDep = $donnee[0]['nom'];

                  //On récupère le nom du port d'arrivée
                  $sql = 'SELECT nom FROM port WHERE idPort = ?';
                  $stm = $bdd->prepare($sql);
                  $stm->execute(array($row['idPort_ARRIVEE']));
                  $donnee = $stm->fetchAll();

                  $nomPortArr = $donnee[0]['nom'];
                  ?>

                  <!-- Le port de départ et le port d'arrivée de chaque liaison est affichée -->
                  <option value=<?php echo htmlspecialchars($row['code']);?>><?php echo htmlspecialchars($nomPortDep);?> - <?php echo htmlspecialchars($nomPortArr);?></option>
                <?php
                }
              ?>
        </select>
        </div>
        <?php

          //On récupère la date du jour
          $sql = 'SELECT DATE(NOW()) as d';
          $stm = $bdd->prepare($sql);
          $stm->execute();
          $result = $stm->fetchAll();

          $dateA = $result[0]['d'];
        ?>

        <!-- Formulaire permettant d'indiquer la date voulue -->
        <div class="date">
          <input name="date" type="date" value="<?php echo $dateA ?>">
        </div>
        <input type="submit" value="Afficher" />
        <input type="hidden" name="secteur" value="<?php echo htmlspecialchars($_GET['secteur']);?>">
      </form>
    </div>
    <?php
     }

     //On vérifie que le code de liaison est bien indiqué
     if(isset($_GET['liaison'])){

      //Requête permettant de récupérer l'id du port de Départ et du port d'arrivé
      $sql = 'SELECT idPort, idPort_ARRIVEE FROM liaison WHERE code = ?';
      $stm = $bdd->prepare($sql);
      $stm->execute(array($_GET['liaison']));
      $result = $stm->fetchAll();

      $idP1 = $result[0]['idPort'];
      $idP2 = $result[0]['idPort_ARRIVEE'];

      //Requête permettant de récupérer le nom du port de départ
      $sql = 'SELECT nom FROM port WHERE idPort = ?';
      $stm = $bdd->prepare($sql);
      $stm->execute(array($idP1));
      $donnee = $stm->fetchAll();

      $nomPortDep = $donnee[0]['nom'];

      //Requête permettant de récupérer le nom du port d'arrivée
      $sql = 'SELECT nom FROM port WHERE idPort = ?';
      $stm = $bdd->prepare($sql);
      $stm->execute(array($idP2));
      $donnee = $stm->fetchAll();

      $nomPortArr = $donnee[0]['nom'];
    ?>
      <!-- Tableau listant toutes les traversées d'une liaison à la date donnée -->
      <div class="table">
        <?php
          echo "<h2>".$nomPortDep." - ".$nomPortArr."</h2>";
        ?>
        <table>
          <thead>
           <tr>
            <th colspan="3">Traversée</th>
            <th colspan="3">Place disponibles</th> 
           </tr>
          </thead>
          <tbody>
            <tr>
              <td>N°</td>
              <td>Heure</td>
              <td>Bateau</td>
              <td>A<br>Passager</td>
              <td>B<br>Véh.inf.2m</td>
              <td>C<br>Véh.sup.2m</td>
            </tr>
            <?php 

              //Requête permettant de récupérer les données de toutes les traversées correspondant aux critères demandés
              $sql = 'SELECT T.numTrav, T.heure, B.nom, T.placesA, T.placesB, T.placesC FROM traversee as T, bateau as B WHERE T.code = ? AND T.idBateau = B.idBateau AND date = ? ORDER BY heure';
              $stm = $bdd->prepare($sql);
              $stm->execute(array($_GET['liaison'], $_GET['date']));
              $result = $stm->fetchAll();
              
              foreach($result as $row){ 
                //On vérifie qu'il y a encore des places disponibles
                if($row['placesA'] != 0){?>
                  <tr>
                    <!-- On met toutes les données dans le tableau -->
                    <td><?php echo htmlspecialchars($row['numTrav']);?></td>
                    <td><?php echo htmlspecialchars($row['heure']);?></td>
                    <td><?php echo htmlspecialchars($row['nom']);?></td>
                    <td><?php echo htmlspecialchars($row['placesA']);?></td>
                    <td><?php echo htmlspecialchars($row['placesB']);?></td>
                    <td><?php echo htmlspecialchars($row['placesC']);?></td>
                      <form action="reservation.php" method="get">
                    <td class="sansbordure"><input type="radio" id="<?php echo htmlspecialchars($row['numTrav']); ?>" name="reservation" value="<?php echo htmlspecialchars($row['numTrav']); ?>" required></td>
                  </tr>
              <?php }
                }
            ?>
            </tbody>
        </table><br>
        <center><input type="submit" value="Réserver maintenant"></center>
        </form>
      </div>
    <?php 
    }
    ?>
  </body>
</html>
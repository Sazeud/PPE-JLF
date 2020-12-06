<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Liaison</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="liaison.css">
  </head>
  <body>
    <?php 
      session_start();
      session_regenerate_id();

      try{
        $bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');
      }
      catch(Exception $e){
        die('Erreur : '.$e->getMessage());
      }
    ?>
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
    <div class="container">
      <form method="get" action="liaison.php">
        <select name="secteur">
                <?php
                $sql = 'SELECT nom FROM secteur';
                $stm = $bdd->prepare($sql);
                $stm->execute();
                $result_secteur = $stm->fetchAll();
                foreach($result_secteur as $row){?>
                  <option value=<?php echo htmlspecialchars($row['nom']);?>><?php echo htmlspecialchars($row['nom']);?></option>
                <?php
                }
              ?>
        </select><br>
        <input type="submit" value="Afficher" />
      </form>
    </div>
    <?php 
      if(isset($_GET['secteur'])){
    ?>
    <div class ="container">
      <form method="get" action="liaison.php">
        <select name="liaison">
                <?php
                $sql = 'SELECT idSecteur FROM secteur WHERE nom = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($_GET['secteur']));
                $result = $stm->fetchAll();

                $idSec = $result[0]['idSecteur'];

                $sql = 'SELECT code, idPort, idPort_ARRIVEE FROM liaison WHERE idSecteur = ?';
                $stm = $bdd->prepare($sql);
                $stm->execute(array($idSec));
                $result = $stm->fetchAll();

                foreach($result as $row){
                  $sql = 'SELECT nom FROM port WHERE idPort = ?';
                  $stm = $bdd->prepare($sql);
                  $stm->execute(array($row['idPort']));
                  $donnee = $stm->fetchAll();

                  $nomPortDep = $donnee[0]['nom'];

                  $sql = 'SELECT nom FROM port WHERE idPort = ?';
                  $stm = $bdd->prepare($sql);
                  $stm->execute(array($row['idPort_ARRIVEE']));
                  $donnee = $stm->fetchAll();

                  $nomPortArr = $donnee[0]['nom'];
                  ?>
                  <option value=<?php echo htmlspecialchars($row['code']);?>><?php echo htmlspecialchars($nomPortDep);?> - <?php echo htmlspecialchars($nomPortArr);?></option>
                <?php
                }
              ?>
        </select><br>
        <input type="submit" value="Afficher" />
        <input type="hidden" name="secteur" value="<?php echo htmlspecialchars($_GET['secteur']);?>">
      </form>
    </div>
    <?php
     }
     if(isset($_GET['liaison'])){
      $sql = 'SELECT idPort, idPort_ARRIVEE FROM liaison WHERE code = ?';
      $stm = $bdd->prepare($sql);
      $stm->execute(array($_GET['liaison']));
      $result = $stm->fetchAll();

      $idP1 = $result[0]['idPort'];
      $idP2 = $result[0]['idPort_ARRIVEE'];

      $sql = 'SELECT nom FROM port WHERE idPort = ?';
      $stm = $bdd->prepare($sql);
      $stm->execute(array($idP1));
      $donnee = $stm->fetchAll();

      $nomPortDep = $donnee[0]['nom'];

      $sql = 'SELECT nom FROM port WHERE idPort = ?';
      $stm = $bdd->prepare($sql);
      $stm->execute(array($idP2));
      $donnee = $stm->fetchAll();

      $nomPortArr = $donnee[0]['nom'];
    ?>
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
              $sql = 'SELECT T.numTrav, T.heure, B.nom, B.idBateau FROM traversee as T, bateau as B WHERE T.code = ? AND T.idBateau = B.idBateau ORDER BY heure';
              $stm = $bdd->prepare($sql);
              $stm->execute(array($_GET['liaison']));
              $result = $stm->fetchAll();
              
              foreach($result as $row){ ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['numTrav']);?></td>
                    <td><?php echo htmlspecialchars($row['heure']);?></td>
                    <td><?php echo htmlspecialchars($row['nom']);?></td>
                    <?php 
                      $sql = 'SELECT C.capaciteMax FROM contenir as C, bateau as B WHERE C.idBateau = ? AND C.idBateau = B.idBateau ORDER BY C.lettre';
                      $stm = $bdd->prepare($sql);
                      $stm->execute(array($row['idBateau']));
                      $donnee = $stm->fetchAll(); ?>

                      <form action="Reservation.php" method="post">
                      <?php
                      foreach($donnee as $ligne){ ?>
                        <td><?php echo htmlspecialchars($ligne['capaciteMax']);?></td>
                      <?php }
                    ?>
                    <td class="sansbordure"><input type="radio" id="<?php echo htmlspecialchars($row['numTrav']); ?>" name="reservation" value="<?php echo htmlspecialchars($row['numTrav']); ?>"></td>
                  </tr>
              <?php }
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
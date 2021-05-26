<!DOCTYPE html> 
  <head> 
  	<meta charset="UTF-8">
    <title>Marie Team | Page d'accueil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
      die('Erreur : ' .$e->getMessage());
    }

    ?>

    <!-- Barre de navigation du site MarieTeam -->
  	<nav class="navbar navbar-expand-lg navbar-light bg-light">
	  <a class="navbar-brand" href="index.php">MarieTeam</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	    <ul class="navbar-nav mr-auto">
	    </ul>
	    <span class="navbar-text">
	      Page d'Accueil
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
	<br><br><br><br>

  
	<div class="container">
      <h1>Tableaux des prochaines traversées</h1>
      <!-- Formulaire permettant de voir les liaisons partant du port de départ indiqué -->
      <p>Veuillez entrer un port de départ afin de voir les prochaines traversées disponibles :</p>
      <form action="index.php" method="GET">
        <label><b>Liaison :</b></label> 
        <input type="text" name="port">
        <input type="submit" id='submit' value='Rechercher'>
      </form>

      <?php 
      //Cela ne s'affiche que si le port de départ est indiqué
      if(isset($_GET['port'])){

        //Requête permettant de vérifier si le port existe dans la base de donnée
        $req = $bdd->prepare('SELECT * FROM port WHERE nom = ?');
        $req->execute(array($_GET['port']));
        $count = $req->rowCount();

        //Si count est différent de 0 alors le port existe
        if($count != 0){

          //Requête permettant d'avoir le nom exacte avec les majuscules et accent
          $sql = 'SELECT nom FROM port WHERE nom = ?';
          $stm = $bdd->prepare($sql);
          $stm->execute(array($_GET['port']));
          $stm->execute();
          $result = $stm->fetchAll();

          $nom = $result[0]["nom"];

          echo "<br>";
          echo "<h2>Prochaines liaisons partant de ".htmlspecialchars($nom)."</h2>"; ?>

          <!-- Tableau présentant les liaisons partant du port indiqué -->
          <table class="table">
            <thead>
              <tr>
                <th>Code de liaison</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Date Départ</th>
                <th>Heure Arrivée</th>
              </tr>
            </thead>
          <tbody>

          <?php
          //On récupère la date du jour afin d'afficher seulement les prochaines liaisons pas celles déjà passées
          $sql = 'SELECT DATE(NOW()) as d';
          $stm = $bdd->prepare($sql);
          $stm->execute();
          $result = $stm->fetchAll();

          $dateActuelle = $result[0]['d'];

          //On récupère toutes les liaisons partant du port indiqué et avec une date supérieur à la date du jour
          $sql = 'SELECT L.code, P.idPort, idPort_ARRIVEE, DATE_FORMAT(date, \'%d/%m/%Y\') AS date_reorganise, date, heure, S.idSecteur FROM liaison as L, traversee as T, port as P, secteur as S WHERE P.nom= ? AND P.idPort = L.idPort AND L.code = T.code AND S.idSecteur = L.idSecteur AND date >= ? ORDER BY date_reorganise ,heure LIMIT 5';
          $stm = $bdd->prepare($sql);
          $stm->execute(array($_GET['port'],$dateActuelle));
          $stm->execute();
          $result = $stm->fetchAll();

          //Pour chaque liaison on récupère les données qui nous intéresse afin de les afficher
          foreach($result as $row){
            $codeLiaison = $row['code'];
            $idPortDep = $row['idPort'];
            $idPortArr = $row['idPort_ARRIVEE'];

            //On récupère le nom du port de départ
            $sql = 'SELECT nom FROM port WHERE idPort = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($idPortDep));
            $stm->execute();
            $donnee = $stm->fetchAll();

            $PortDep = $donnee[0]["nom"];

            //On récupère le nom du port d'arrivé'
            $sql = 'SELECT nom FROM port WHERE idPort = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($idPortArr));
            $stm->execute();
            $donnee = $stm->fetchAll();

            $PortArr = $donnee[0]["nom"];
            ?>
              <!-- Les données sont affichés dans le tableau -->
              <tr>
                <td><a href="liaison.php?liaison=<?php echo htmlspecialchars($codeLiaison);?>&date=<?php echo htmlspecialchars($row['date'])?>">L<?php echo htmlspecialchars($codeLiaison); ?></a></td>
                <td><?php echo htmlspecialchars($PortDep); ?></td>
                <td><?php echo htmlspecialchars($PortArr); ?></td>
                <td><?php echo $row['date_reorganise']; ?></td>
                <td><?php echo htmlspecialchars($row['heure']); ?></td>
              </tr>
            <?php
          }?>
          </tbody>
      </table>
    </div>
    <?php
        }
        
        //Si le port n'existe pas alors un message d'erreur est envoyé
        else if($count == 0){?>
          <p>Le port que vous avez entré n'existe pas ! Veuillez réessayer</p>
          <?php
        }
      }
      ?>
  </body> 
</html>
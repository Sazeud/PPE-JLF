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
      <p>Veuillez entrer un port de départ afin de voir les prochaines traversées disponibles :</p>
      <form action="index.php" method="GET">
        <label><b>Liaison :</b></label> 
        <input type="text" name="port">
        <input type="submit" id='submit' value='Rechercher'>
      </form>

      <?php 
      if(isset($_GET['port'])){
        $req = $bdd->prepare('SELECT * FROM port WHERE nom = ?');
        $req->execute(array($_GET['port']));
        $count = $req->rowCount();
        if($count != 0){
          $sql = 'SELECT nom FROM port WHERE nom = :nomPort';
          $stm = $bdd->prepare($sql);
          $stm->bindParam(":nomPort",$_GET['port']);
          $stm->execute();
          $result = $stm->fetchAll();

          $nom = $result[0]["nom"];

          echo "<br>";
          echo "<h2>Prochaines liaisons partant de ".htmlspecialchars($nom)."</h2>"; ?>

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
          $sql = 'SELECT L.code, P.idPort, idPort_ARRIVEE, DATE_FORMAT(date, \'%d/%m/%Y\') AS date_reorganise, heure, S.idSecteur FROM liaison as L, traversee as T, port as P, secteur as S WHERE P.nom= :portDep AND P.idPort = L.idPort AND L.code = T.code AND S.idSecteur = L.idSecteur ORDER BY date_reorganise ,heure LIMIT 5';
          $stm = $bdd->prepare($sql);
          $stm->bindParam(":portDep", $_GET['port']);
          $stm->execute();
          $result = $stm->fetchAll();

          foreach($result as $row){
            $codeLiaison = $row['code'];
            $idPortDep = $row['idPort'];
            $idPortArr = $row['idPort_ARRIVEE'];

            $sql = 'SELECT nom FROM port WHERE idPort = :idPortDep';
            $stm = $bdd->prepare($sql);
            $stm->bindParam(":idPortDep",$idPortDep);
            $stm->execute();
            $donnee = $stm->fetchAll();

            $PortDep = $donnee[0]["nom"];

            $sql = 'SELECT nom FROM port WHERE idPort = :idPortArr';
            $stm = $bdd->prepare($sql);
            $stm->bindParam(":idPortArr",$idPortArr);
            $stm->execute();
            $donnee = $stm->fetchAll();

            $PortArr = $donnee[0]["nom"];
            ?>
              <tr>
                <td><a href="liaison.php?liaison=<?php echo htmlspecialchars($codeLiaison);?>&<?php echo htmlspecialchars($row['idSecteur']);?>">L<?php echo htmlspecialchars($codeLiaison); ?></a></td>
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
        else if($count == 0){?>
          <p>Le port que vous avez entré n'existe pas ! Veuillez réessayer</p>
          <?php
        }
      }
      ?>
  </body> 
</html>
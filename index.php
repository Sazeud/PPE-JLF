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

//On récupère les 5 prochaines liaisonssss
$req = $bdd->query('SELECT code, portdepart, portarrivee FROM liaison,traversee ORDER BY traversee.date DESC LIMIT 0,5');
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
      <h1>Tableaux des prochaines liaisons</h1>
      <table class="table">
        <thead>
          <tr>
            <th>Code de liaison</th>
            <th>Départ</th>
            <th>Arrivée</th>
          </tr>
        </thead>
        <tbody>
        <?php 
        	while($donnees = $req->fetch())
        	{
        ?>
          <tr>
            <td><a href="liaison.php?code=<?php echo $donnees['code']; ?>">L<?php echo htmlspecialchars($donnees['code']); ?></a></td>
            <td><?php echo htmlspecialchars($donnees['portdepart']); ?></td>
            <td><?php echo htmlspecialchars($donnees['portarrivee']); ?></td>
          </tr>
        <?php
    		}
    		$req->closeCursor();
    	?>
          <tr>
            <td><a href="#">L23</a></td>
            <td>Lorient</td>
            <td>Port-Tudy</td>
          </tr>
          <tr>
            <td><a href="#">L45</a></td>
            <td>Vannes</td>
            <td>Le Palais</td>
          </tr>
          <tr>
          	<td><a href="#">L2</a></td>
          	<td>Sauzon</td>
          	<td>Quiberon</td>
        </tbody>
      </table>
    </div>
  </body> 
</html>
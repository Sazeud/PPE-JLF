<!DOCTYPE html>
<html>
    <?php
        session_start();
        session_regenerate_id();
        if(!isset($_SESSION['username']))
        {
            header("Location: Connexion.php");
        }
        ?>
    <head>
       <meta charset="utf-8">
       <title>Profile de <?php echo $_SESSION['username']; ?></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="profile.css" media="screen" type="text/css" />
    </head>
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
              Profile
            </span>
          <?php 
          if(isset($_SESSION['username'])){?>
            <form class="form-inline my-2 my-lg-0">
                <a class="nav-link" href="Deconnexion.php">Deconnexion<span class="sr-only">(current)</span></a>
            </form>
      <?php } ?>
      </div>
    </nav>
        <div id="container">
            <form action="verificationConnexion.php" method="POST" id="form">
                <h1>Profile</h1><br>
                    
                <label><b>Nom d'utilisateur : <?php echo $_SESSION['username']; ?></b></label><br><br>

                <label><b>Nombre de points de fidélité : </b></label>
            </form>
        </div>
    </body>
</html>
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
            <form action="passwordChange.php" method="POST" id="form">
                <h1>Profile</h1><br>
                    
                <label><b>Nom d'utilisateur : <?php echo $_SESSION['username']; ?></b></label><br><br>

                <label><b>Nombre de points de fidélité : </b></label><br><br>

                <label><b>Changer de mot de passe :</b></label><br>

                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer votre mot de passe" name="password" required>

                <label><b>Nouveau mot de passe</b></label>
                <input type="password" placeholder="Entrer à nouveau votre mot de passe" name="newpassword" required>

                <label><b>Confirmer nouveau mot de passe</b></label>
                <input type="password" placeholder="Entrer votre nouveau mot de passe" name="verifpassword" required>

                <input type="submit" id='submit' value='Changer'>
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
    </body>
</html>
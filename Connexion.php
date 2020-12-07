  
<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <title>Page de Connexion</title>
        <link rel="stylesheet" href="connexion.css" media="screen" type="text/css" />
    </head>
    <?php 
        session_start();
        session_regenerate_id();
    ?>
    <body>
        <div id="container">
            <form action="verificationConnexion.php" method="POST">
                <h1>Connexion</h1>
                
                <label><b>Nom d'utilisateur</b></label>
                <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" required>

                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer le mot de passe" name="password" required>

                <input type="submit" id='submit' value='Connexion'>
                <p><center><a href="Inscription.php">Inscription</a> si vous n'avez pas de compte<center></p>
                <?php
                    if(isset($_GET['erreur'])){
                        $erreur = $_GET['erreur'];
                        if($erreur == 1 || $erreur == 2){
                            echo "<p style='color:red'>Nom d'utilisateur ou Mot de passe incorrecte !</p>";
                        }
                    }
                ?>
            </form>
        </div>
    </body>
</html>
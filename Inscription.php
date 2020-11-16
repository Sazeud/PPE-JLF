<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
        <link rel="stylesheet" href="connexion.css" media="screen" type="text/css" />
    </head>
    <body>
        <div id="container">
            
            <form action="verificationInscription.php" method="POST">
                <h1>Inscription</h1>
                
                <label><b>Nom d'utilisateur</b></label>
                <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" required>

                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer le mot de passe" name="password" required>

                <label><b>Confirmer mot de passe</b></label>
                <input type="password" placeholder="Entrer à nouveau le mot de passe" name="verifpassword" required>

                <input type="submit" id='submit' value='Inscription' >
                <p><center><a href="Connexion.php">Connexion</a> si vous avez un compte<center></p>
                 <?php
                    if(isset($_GET['erreur'])){
                        $err = $_GET['erreur'];
                        if($err==1){
                            echo "<p style='color:red'>Données non renseignées</p>";
                        }
                        else if($err==2){
                            echo "<p style='color:red'>Les mots de passes ne correspondent pas</p>";
                        }
                        else if($err==3){
                            echo "<p style='color:red'>Ce nom d'utilisateur est déjà utilisé</p>";
                        }
                    }
                    else if(isset($_GET['creer'])){
                        $cree = $_GET['creer'];
                        if($cree == 1){
                            echo "<p>Votre compte a bien été crée ! <a href='Connexion.php'>Me connecter</a></p>";
                        }
                    }
                ?>
            </form>
        </div>
    </body>
</html>
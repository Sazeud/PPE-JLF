<!DOCTYPE html>
<html>
    <head>
        <title>Page de traitement</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="form.css">
    </head>
    <body>

    <div class="billet">       
        <?php

            
            echo "Votre Reservation  : ".'<br>';

            // EN CONSTRUCTION a recuperer de la page liaison 
            echo "Laison : ".'<br>';
            echo "Traversée n° : "."le : "."à : ".'<br>'.'<br>';
            echo "Réservation enregistrer sous le n° : ".'<br>';


            //récupère les données saisies sur la page Reservation
            echo 'Nom : '.$_POST["nom"].'<br>';
            echo 'Adresse : ' .$_POST["adresse"].'<br>';
            echo 'Code Postal : ' .$_POST["cp"].'<br>';
            echo 'Ville : ' .$_POST["ville"].'<br>'.'<br>';

            //Creation des variables prennant les valeurs numériques des input 
            $nbAdulte = intval($_POST["adulte"]);
            $nbJunior = intval($_POST["junior"]);
            $nbEnfant = intval($_POST["enfant"]) ;
            $nbVoitureLI4 = intval($_POST["voiLongInf4"]) ;
            $nbVoitureLI5 = intval($_POST["voiLongInf5"]) ;
            $nbFourgon = intval($_POST["fourgon"]) ;   
            $nbCampingCar = intval($_POST["campingCar"]) ;    
            $nbCamion = intval($_POST["camion"]) ;                 

            //Affichage des valeurs saisies uniquement si différent de 0 
            if ($nbAdulte != 0) {
            	echo "Adulte  : ".$nbAdulte.'<br>';
            }
            
            if ($nbJunior != 0) {
            	echo "Junior 8 à 18 ans  :".$nbJunior.'<br>';
            }

            
            if ($nbEnfant != 0) {
            	echo "Enfant 0 à 7 ans  : ".$nbEnfant.'<br>';
            }

            if ($nbVoitureLI4 != 0) {
            	echo "Voiture long.inf.4m  : ".$nbVoitureLI4.'<br>';
            }


            if ($nbVoitureLI5 != 0) {
            	echo "Voiture long.inf.5m  : ".$nbVoitureLI5.'<br>';
            }


            if ($nbFourgon != 0) {
            	echo "Fourgon  : ".$nbFourgon.'<br>';
            }


            if ($nbCampingCar != 0) {
            	echo "Camping car  : ".$nbCampingCar.'<br>';
            }


            if ($nbCamion != 0) {
            	echo "Camion  : ".$nbCamion.'<br>';
            }
        ?>
    </div>
    </body>
</html>
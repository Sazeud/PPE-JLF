<?php
    session_start();
    session_regenerate_id();

    //Verifie que l'utilisateur est bien connecté sinon le renvoi sur la page de connexion
    if(!isset($_SESSION['username']))
    {
        header("Location: Connexion.php?page=profile");
    }

    //Connexion à la base de donnée
    try{
        $bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');
    }
    catch(Exception $e){
        die('Erreur : ' .$e->getMessage());
    }

    //Verifie que la donnée suppression est bien attribuée
    if(isset($_POST['suppression'])){

        //On fait une requête permettant de récupérer le code de l'utilisateur
        $sql = 'SELECT code_uti FROM utilisateur WHERE nom_uti = ?';
        $stm = $bdd->prepare($sql);
        $stm->execute(array($_SESSION['username']));
        $result = $stm->fetchAll();

        $codeuti = $result[0]['code_uti'];

        //Requête permettant de vérifier qu'il y a bien une réservation avec le numéro de réservation en POST et le code utilisateur
        $sql = 'SELECT * FROM reservation WHERE codeuti = ? AND numReserv = ?';
        $stm = $bdd->prepare($sql);
        $stm->execute(array($codeuti, $_POST['suppression']));
        $count = $stm->rowCount();

        //Vérifie qu'il existe bien une réservation
        if($count != 0){
            //Supprime la réservation de la table enregistrer
            $sql = 'DELETE FROM enregistrer WHERE numReserv = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_POST['suppression']));

            //Supprime la réservation de la table réservation
            $sql = 'DELETE FROM reservation WHERE numReserv = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_POST['suppression']));
        }
        header('Location: profile.php?choix=reservation');
    }
?>
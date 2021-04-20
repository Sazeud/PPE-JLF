<?php
    session_start();
    session_regenerate_id();
    if(!isset($_SESSION['username']))
    {
        header("Location: Connexion.php?page=profile");
    }

    try{
        $bdd = new PDO('mysql:host=localhost;dbname=marieteam;charset=utf8','root','');
    }
    catch(Exception $e){
        die('Erreur : ' .$e->getMessage());
    }

    if(isset($_POST['suppression'])){
        $sql = 'SELECT code_uti FROM utilisateur WHERE nom_uti = ?';
        $stm = $bdd->prepare($sql);
        $stm->execute(array($_SESSION['username']));
        $result = $stm->fetchAll();

        $codeuti = $result[0]['code_uti'];

        $sql = 'SELECT * FROM reservation WHERE codeuti = ? AND numReserv = ?';
        $stm = $bdd->prepare($sql);
        $stm->execute(array($codeuti, $_POST['suppression']));
        $count = $stm->rowCount();

        if($count != 0){
            $sql = 'DELETE FROM enregistrer WHERE numReserv = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_POST['suppression']));

            $sql = 'DELETE FROM reservation WHERE numReserv = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($_POST['suppression']));
        }
    }
?>
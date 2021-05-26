<?php

//Permet de se déconnecter du compte utilisateur
session_start();
unset($_SESSION['username']);
session_destroy();

//Renvoi sur la page d'accueil
header("Location: index.php");
?>
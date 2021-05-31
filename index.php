<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Marie Team | Page d'accueil</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/one-page-wonder.css" rel="stylesheet">

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

  <!-- Barre de navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
      <div>
        <a href="#top" class="logo">
          <img src="img/logo.png"/>
        </a>
      </div>
      <a class="navbar-brand" href="index.php">MarieTeam</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">

        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
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
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header id="home" class="masthead text-center text-white">
    <div class="masthead-content">
      <div class="container">
        <h1 class="masthead-heading mb-0">MarieTeam</h1>
        <h2 class="masthead-subheading mb-0">Trouvez la croisière de vos rèves !</h2>

        <!-- Scroll Down -->
                        <div class="local-scroll ">
                            <a href="#traversee" class="scroll-down btn btn-primary btn-xl rounded-pill mt-5"><span>Voir les traversées</span></a>
                        </div>
        <!-- End Scroll Down -->
      </div>
    </div>
  </header>

  <section id="traversee">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 order-lg-2">
          <div class="p-5">
            <img class="img-fluid rounded-circle" src="img/bateau1.webp" alt="">
          </div>
        </div>
        <div class="col-lg-6 order-lg-1">
          <div class="p-5">
            <h2 class="display-5">Pour une expérience inoubliable...</h2>
            <p>Nos bateaux de croisière sont équipés de nombreux services: restaurant, piscine, toboggan, animations, salon de massage, salle de sport, bars, salle de jeu etc...</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="p-5">
            <img class="img-fluid rounded-circle" src="img/famille1.jpg" alt="">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="p-5">
            <h2 class="display-5">Créez des souvenirs marquants</h2>
            <p>Emmenez votre famille vivre une aventure unique en son genre.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="container col-lg-6 col-md-4 text-center liaison"  id="tab">
      <h1>Tableaux des prochaines traversées</h1>
      <!-- Formulaire permettant de voir les liaisons partant du port de départ indiqué -->
      <p>Veuillez entrer un port de départ afin de voir les prochaines traversées disponibles :</p>
      <form action="index.php#tab" method="GET">
        <label><b>Liaison :</b></label> 
        <input type="text" name="port">
        <input type="submit" id='submit' value='Rechercher'>
      </form>

  <?php 
      //Cela ne s'affiche que si le port de départ est indiqué
      if(isset($_GET['port'])){

        //Requête permettant de vérifier si le port existe dans la base de donnée
        $req = $bdd->prepare('SELECT * FROM port WHERE nom = ?');
        $req->execute(array($_GET['port']));
        $count = $req->rowCount();

        //Si count est différent de 0 alors le port existe
        if($count != 0){

          //Requête permettant d'avoir le nom exacte avec les majuscules et accent
          $sql = 'SELECT nom FROM port WHERE nom = ?';
          $stm = $bdd->prepare($sql);
          $stm->execute(array($_GET['port']));
          $stm->execute();
          $result = $stm->fetchAll();

          $nom = $result[0]["nom"];

          echo "<br>";
          echo "<h2>Prochaines liaisons partant de ".htmlspecialchars($nom)."</h2>"; ?>

          <!-- Tableau présentant les liaisons partant du port indiqué -->
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
          //On récupère la date du jour afin d'afficher seulement les prochaines liaisons pas celles déjà passées
          $sql = 'SELECT DATE(NOW()) as d';
          $stm = $bdd->prepare($sql);
          $stm->execute();
          $result = $stm->fetchAll();

          $dateActuelle = $result[0]['d'];

          //On récupère toutes les liaisons partant du port indiqué et avec une date supérieur à la date du jour
          $sql = 'SELECT L.code, P.idPort, idPort_ARRIVEE, DATE_FORMAT(date, \'%d/%m/%Y\') AS date_reorganise, date, heure, S.idSecteur FROM liaison as L, traversee as T, port as P, secteur as S WHERE P.nom= ? AND P.idPort = L.idPort AND L.code = T.code AND S.idSecteur = L.idSecteur AND date >= ? ORDER BY date_reorganise ,heure LIMIT 5';
          $stm = $bdd->prepare($sql);
          $stm->execute(array($_GET['port'],$dateActuelle));
          $stm->execute();
          $result = $stm->fetchAll();

          //Pour chaque liaison on récupère les données qui nous intéresse afin de les afficher
          foreach($result as $row){
            $codeLiaison = $row['code'];
            $idPortDep = $row['idPort'];
            $idPortArr = $row['idPort_ARRIVEE'];

            //On récupère le nom du port de départ
            $sql = 'SELECT nom FROM port WHERE idPort = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($idPortDep));
            $stm->execute();
            $donnee = $stm->fetchAll();

            $PortDep = $donnee[0]["nom"];

            //On récupère le nom du port d'arrivé'
            $sql = 'SELECT nom FROM port WHERE idPort = ?';
            $stm = $bdd->prepare($sql);
            $stm->execute(array($idPortArr));
            $stm->execute();
            $donnee = $stm->fetchAll();

            $PortArr = $donnee[0]["nom"];
            ?>
              <!-- Les données sont affichés dans le tableau -->
              <tr>
                <td><a href="liaison.php?liaison=<?php echo htmlspecialchars($codeLiaison);?>&date=<?php echo htmlspecialchars($row['date'])?>">L<?php echo htmlspecialchars($codeLiaison); ?></a></td>
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
        
        //Si le port n'existe pas alors un message d'erreur est envoyé
        else if($count == 0){?>
          <p>Le port que vous avez entré n'existe pas ! Veuillez réessayer</p>
          <?php
        }
      }
      ?>
    </div>

  <!-- Footer -->
  <footer class="py-5 bg-dark ">
    <div class="container" >
      <p class="m-0 text-center text-white small">MarieTEAM présenté par JLF</p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- JS -->
        <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-migrate-1.4.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>        
        <script type="text/javascript" src="js/SmoothScroll.js"></script>
        <script type="text/javascript" src="js/jquery.scrollTo.min.js"></script>
        <script type="text/javascript" src="js/jquery.localScroll.min.js"></script>
        <script type="text/javascript" src="js/jquery.viewport.mini.js"></script>
        <script type="text/javascript" src="js/jquery.countTo.js"></script>
        <script type="text/javascript" src="js/jquery.appear.js"></script>
        <script type="text/javascript" src="js/jquery.sticky.js"></script>
        <script type="text/javascript" src="js/jquery.parallax-1.1.3.js"></script>
        <script type="text/javascript" src="js/jquery.fitvids.js"></script>
        <script type="text/javascript" src="js/owl.carousel.min.js"></script>
        <script type="text/javascript" src="js/isotope.pkgd.min.js"></script>
        <script type="text/javascript" src="js/imagesloaded.pkgd.min.js"></script>
        <script type="text/javascript" src="js/jquery.magnific-popup.min.js"></script>
        <script type="text/javascript" src="js/wow.min.js"></script>
        <script type="text/javascript" src="js/masonry.pkgd.min.js"></script>
        <script type="text/javascript" src="js/jquery.simple-text-rotator.min.js"></script>
        <script type="text/javascript" src="js/jquery.lazyload.min.js"></script>
        <script type="text/javascript" src="js/all.js"></script>

</body>

</html>

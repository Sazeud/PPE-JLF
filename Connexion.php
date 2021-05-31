<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Marie Team | Page de Connexion</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/one-page-wonder.css" rel="stylesheet">
  <link href="css/footer.css" rel="stylesheet">

</head>

  <?php 
        session_start();
        session_regenerate_id();
    ?>

<body>
  

  <!-- Navigation -->
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

    <section class="profil">
    <div class="container bg-secondary" >
        <div class="p-5">
            <!-- Formulaire de connexion au compte utilisateur -->
            <form action="verificationConnexion.php" method="POST">
                <h1>Connexion</h1>
                
                <div class="col-lg-12 col-md-12 col-sm-12 text-center py-4">
                    <label><b>Nom d'utilisateur</b></label>
                    <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" required>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 text-center py-4">
                    <label><b>Mot de passe</b></label>
                    <input type="password" placeholder="Entrer le mot de passe" name="password" required>
                </div>

                    <input type="submit" id='submit' value='Connexion' class="py-1">
                    <p><a class="lien" href="Inscription.php" >Inscription</a> si vous n'avez pas de compte</p>

                    <?php
                    //Message renvoyé en cas d'erreur 
                        if(isset($_GET['erreur'])){
                            $erreur = $_GET['erreur'];
                            if($erreur == 1 || $erreur == 2){
                                echo "<p style='color:red'>Nom d'utilisateur ou Mot de passe incorrecte !</p>";
                            }
                        }
                    ?>
            </form>
        </div>
    </div>
  </section>
    
  </header>


    <!-- Footer -->
        <footer class="py-5 bg-dark bottom ">
            <div class="container" >
            <p class="m-0 text-center text-white small">MarieTEAM présenté par JLF</p>
            </div>
        </footer>

  <!-- JS -->
        <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-migrate-1.4.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>        
        <script type="text/javascript" src="js/SmoothScroll.js"></script>
        <script type="text/javascript" src="js/jquery.scrollTo.min.js"></script>
        <script type="text/javascript" src="js/jquery.localScroll.min.js"></script>

</body>

</html>
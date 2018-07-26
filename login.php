<?php
include_once "User.php";
try {
    $bdd = new PDO("mysql:host=localhost;dbname=pool_php_rush", "root", "root");
} 
catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
if (isset($_COOKIE["email"]) && isset($_COOKIE["password"])) {
    $login = USER::is_logged_in($bdd, $_COOKIE["email"], $_COOKIE["password"]);
    if ($login) {
        header("Location: index.php");
        exit();
    }
    // else{
        //     header("Location: logout.php");
        //     exit();
        // }
    }
    else{
        if (!empty($_POST)) {
            User::log_in($bdd, $_POST);
        }
    }
        ?>
        
        <!DOCTYPE html>
        <html>
        <head>
        <title>Welcome to Nomad Electronics</title>
        <meta charset="utf8"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        </head>
        <header>
            <nav>
                <div class="nav-wrapper">
                   <a href="index.php" class="brand-logo center">Nomad Electronics</a>
                </div>
            </nav>
        </header>
        <body>
            <div class="container">
                <div class="container">
                    <form action="login.php" method="post">
                    <div class="row">
                    <h3 class="center-align">Login</h3>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <p>
                                <label for="Email" class="floatLabel"></label>
                                <input type="email" name="email" placeholder="email" required>
                            </p>
                        </div>
                        <div class="col s12">
                            <p>
                                 <label for="password" class="floatLabel"></label>
                                <input type="password" name="password" placeholder="password" required>
                            </p>
                        </div>
                    </div>
                </div>
                    <p class="center-align" >
                    <button class="btn waves-effect waves-light" type="submit" name="submit">Login
                    <i class="material-icons right">send</i>
                </p>
            </div>
                    </form>
        <h6 class="center-align"><a href="inscription.php">Not registered yet?</a></h6>
    </body>
    <footer class="page-footer blue-grey">
          <div class="container">
            <div class="row">
              <div class="col s12 ">
                  <div class="center-align">
                    <h5 class="white-text">Contact</h5>
                    </div>
                <ul class="center-align">
                  <li><a href="https://www.facebook.com/nomadgoods" class="grey-text text-lighten-3" href="#!">Facebook</a></li>
                  <li><a href="https://www.instagram.com/nomadgoods/?hl=en" class="grey-text text-lighten-3" href="#!">Instagram</a></li>
                  <li><a href="https://twitter.com/nomadgoods?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor" class="grey-text text-lighten-3" href="#!">Twitter</a></li>
                  <li><a href="mailto:customer-service@gmail.com" class="grey-text text-lighten-3" href="#!">Contact us</a></li>  
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container center-align">
            © 2018 PHP Masters(wannabes) ALL RIGHT RESERVED 
            </div>
          </div>
        </footer>
    </html>
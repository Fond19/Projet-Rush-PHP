<?php
include_once("User.php");


try {
    $bdd = new PDO("mysql:host=localhost;dbname=pool_php_rush", "root", "root");
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
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
    <body class="center-align">
    <br><br><br>
    <?php if (isset($_COOKIE["email"]) && isset($_COOKIE["password"])){
            if (USER::is_logged_in($bdd, $_COOKIE["email"], $_COOKIE["password"]) && USER::is_admin($bdd, $_COOKIE["email"])){
                if (isset($_GET["id"]) && $_GET["id"]!= "")
                        USER::display_user($bdd, $_GET["id"]);
                else {
                    echo "<p>You do not have the rights to display user info.</p>";
                }
            }
            else{ 
                echo "<p>You do not have the rights to display user info.</p>";
            }
        }
        else{
        echo "<p>You do not have the rights to display user info.</p>";
    }
    ?>
        <div class="center-align" >
                    <button onclick="location.href='admin.php';" class="btn waves-effect waves-light pulse" type="submit" name="submit">Back
                </div>
                <br><br><br><br><br><br><br><br>
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
<?php 

include_once("User.php");
try {
    $bdd = new PDO("mysql:host=localhost;dbname=pool_php_rush", "root", "root");
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}

if (isset($_COOKIE["email"]) && isset($_COOKIE["password"])) {
    $login = USER::is_logged_in($bdd, $_COOKIE["email"], $_COOKIE["password"]);
    if ($login) {
        header("Location: index.php");
        exit();
    }
    else{
        header("Location: logout.php");
        exit();
    }
}
else{
    if (!empty($_POST)){
    $user = new USER($bdd, $_POST);
}
?>

<!DOCTYPE html>
<html>
<head>
        <title>Registration</title>
        <meta charset="utf8"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
        </head>
        <header>
            <nav>
                <div class="nav-wrapper">
                   <a href="index.php" class="brand-logo center">Nomad Electronics</a>
                </div>
            </nav>
        </header>
        <body>
            <div class="container" class="valign-wrapper">
                <div class="container">
                    <div class="row">
                    <h3 class="center-align">Welcome !</h3>
                    </div>
                    <form method="post">
                    Username:   <input type="text" name="username" placeholder="username" required>
                    <br><br>
                    Email:   <input type="email" name="email" placeholder="email" required>
                    <br><br>
                    Password:   <input type="password" name="password" placeholder="password" required>
                    <br><br>
                    Password confirmation:   <input type="password" name="password_confirmation" placeholder="password_confirmation" required>
                    <br><br>
                    <p class="center-align">
                        <button class="btn waves-effect waves-light"  type="submit" name="submit">Submit</button>
                        <button onclick="location.href='login.php'" class="btn waves-effect waves-light" type="submit" name="submit">Login</button> 
                    </p>
                    </form>
                </div>
            </div>

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
<?php
}

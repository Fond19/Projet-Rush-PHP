<?php 

include_once("User.php");
try {
    $bdd = new PDO("mysql:host=localhost;dbname=pool_php_rush", "root", "root");
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}

if (isset($_COOKIE["email"]) && isset($_COOKIE["password"])) {
    $login = USER::is_logged_in($bdd, $_COOKIE["email"], $_COOKIE["password"]);
    if ($login) 
    {
        if (USER::is_admin($bdd, $_COOKIE["email"])){
            if (!empty($_POST))
            $user = new USER($bdd, $_POST, 1);
            ?>
<!DOCTYPE html>
<html>
<head>
        <title>Registration</title>
        <meta charset="utf8"/>
        </head>
        <body>
    <h1>Registration form:</h1>
    <form method="post">
        Username:   <input type="text" name="username" placeholder="username" required>
        <br><br>
        Email:   <input type="email" name="email" placeholder="email" required>
        <br><br>
        Admin: <input type="checkbox" name="admin" unchecked>
        <br><br>
        Password:   <input type="password" name="password" placeholder="password" required>
        <br><br>
        Password confirmation:   <input type="password" name="password_confirmation" placeholder="password_confirmation" required>
        <br><br>
        <input type="submit" name="submit" value="SUBMIT">
        <input type="button" value="BACK" onclick="location.href='admin.php'">
    </form>
    </body>
</html>
<?php
    }
    else
        header("Location: index.php");
        exit();

    }
    else{
        header("Location: logout.php");
        exit();
    }
}
else{
    header("Location: login.php");
    exit();
}    
?>
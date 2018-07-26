<?php
include_once "User.php";

try {
    $bdd = new PDO("mysql:host=localhost;dbname=pool_php_rush", "root", "root");
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
?>

<!DOCTYPE html>
<html>
<head>
        <title>Admin</title>
        <meta charset="utf8"/>
        </head>
    <body>
    <?php if (isset($_COOKIE["email"]) && isset($_COOKIE["password"])) {
    if (USER::is_logged_in($bdd, $_COOKIE["email"], $_COOKIE["password"]) && USER::is_admin($bdd, $_COOKIE["email"])) {
        if (isset($_GET["id"]) && $_GET["id"] != "") {
            $req = $bdd->prepare("SELECT username, email, admin FROM users WHERE id = :id;");
            $req->execute(array(":id" => $_GET["id"]));
            $res = $req->fetch();
            if (!$res) {
                echo "<p>This user doesn't exist.</p>";
                echo "<p><a href='admin.php'>Back</a></p>";
            } else {
                ?>
<form method="post">
        Username:   <input type="text" name="username" value="<?php echo $res["username"] ?>" required>
        <br><br>
        Email:   <input type="email" name="email" value="<?php echo $res["email"] ?>" required>
        <br><br><?php
if ($res["admin"]) {
                    echo "Admin:   <input type='checkbox' name='admin' checked>";
                } else {
                    echo "Admin:   <input type='checkbox' name='admin' unchecked>";
                }

                ?>
        <br><br>
        <input type="submit" name="submit" value="SUBMIT">
        <input type="button" value="BACK" onclick="location.href='admin.php'">
        <br><br>
 </form>
<?php
if (!empty($_POST)) {
                    USER::edit_user($bdd, $_GET["id"], $_POST);
                }

            }
        } else {
            echo "<p>You do not have the rights to edit user info.</p>";
            echo "<p><a href='admin.php'>Back</a></p>";
        }
    } else {
        echo "<p>You do not have the rights to edit user info.</p>";
        echo "<p><a href='index.php'>Back</a></p>";
    }
} else {
    echo "<p>You do not have the rights to edit user info.</p>";
    echo "<p><a href='login.php'>Back</a></p>";
}
?>
    </body>        
</html>
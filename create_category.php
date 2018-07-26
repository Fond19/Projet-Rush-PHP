<?php

include_once "User.php";
include_once "Categories.php";
try {
    $bdd = new PDO("mysql:host=localhost;dbname=pool_php_rush", "root", "root");
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}

if (isset($_COOKIE["email"]) && isset($_COOKIE["password"])) {
    $login = USER::is_logged_in($bdd, $_COOKIE["email"], $_COOKIE["password"]);
    if ($login) {
        if (USER::is_admin($bdd, $_COOKIE["email"])) {
            ?>
<!DOCTYPE html>
<html>
<head>
        <title>Create a category</title>
        <meta charset="utf8"/>
        </head>
        <body>
    <h1>Create a category:</h1>
    <p>Select a parent category if necessary.</p>
    <form method="post">
    Parent category:   <select name="parent">
    <option value="none" selected></option>
    <?php
$req = $bdd->query("SELECT name FROM categories;");
            while ($res = $req->fetch()) {
                echo "<option value = '" . $res["name"] . "'>" . $res["name"] . "</option>";
            }

            ?>
    </select>
        <br><br>
        Category name:   <input type="text" name="name" required>
        <br><br>
        <input type="submit" name="submit" value="SUBMIT">
        <input type="button" value="BACK" onclick="location.href='admin.php'">
    </form>
    <?php
if (!empty($_POST)) {
                $category = new Category($bdd, $_POST);
            }

            ?>
    </body>
</html>
<?php
} else {
            header("Location: index.php");
        }

        exit();

    } else {
        header("Location: logout.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
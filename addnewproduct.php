<?php
include_once "User.php";
include_once "Product.php";
include_once "Image.php";

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
        <title>Add new product</title>
        <meta charset="utf8"/>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="dropdown.js"></script>
        </head>
        <body>
    <h1>Add a new product:</h1>
    <form method="post" enctype="multipart/form-data">
        Name:   <input type="text" name="name" value="<?php
if (isset($_POST["name"])) {
                echo $_POST["name"];
            }

            ?>" required>
        <br><br>
        Price:   <input type="number" min='0' step='0.01' name="price" value = "<?php
if (isset($_POST["price"])) {
                echo $_POST["price"];
            }

            ?>" placeholder="price in €" required>
        <br><br>
        Category: <select name="category0" id="category0">
        <option value="" selected="selected"></option>
        <?php
$req = $bdd->query("SELECT name, id, parent_id FROM categories ORDER BY name ASC;");

            while ($res = $req->fetch()) {
                if (is_null($res["parent_id"])) {
                    //if (isset($_POST["category0"]) && $res["name"] = $_POST["category0"])
                    //echo "<option value='" . $res["name"] . "' selected='selected'>" . $res["name"] . "</option>";
                    // else
                    echo "<option value='" . $res["name"] . "'>" . $res["name"] . "</option>";
                }

            }
            $req->closeCursor();
            ?>
        </select>
        <?php
// if (!empty($_POST)) {
            //     if (isset($_POST["category" . $cat])) {
            //         while (isset($_POST["category" . $cat])) {
            //             $canAdd = false;
            //             while (!$canAdd) {
            //                 $req = $bdd->prepare("SELECT name, id FROM categories WHERE parent_id = (SELECT id FROM categories WHERE name = :parent) ORDER BY name ASC;");
            //                 $req->execute(array(":parent" => $_POST["category" . $cat]));
            //                 if (!$req->fetch()) {
            //                     $canAdd = true;
            //                     break;
            //                 } else {
            //                     $req->closeCursor();
            //                     $req = $bdd->prepare("SELECT name, id FROM categories WHERE parent_id = (SELECT id FROM categories WHERE name = :parent) ORDER BY name ASC;");
            //                     $req->execute(array(":parent" => $_POST["category" . $cat]));
            //                     echo $label . "category: <select name='category" . ($cat + 1) . "'>";
            //                     while ($res = $req->fetch()) {
            //                         echo "<option value='" . $res["name"] . "'>" . $res["name"] . "</option>";
            //                     }
            //                     echo "</select>";
            //                     $label .= "sub-";
            //                     $cat++;
            //                 }
            //             }}
            //         if ($canAdd) {
            //             //
            //         }

            //     }
            // }
            ?>
        <br><br>
        <div id="sub_category"></div><br>
        Add an image:
        <input name="upload" type="file" id="upload" accept="image/jpg, image/gif, image/png', image/jpeg" /><br><br>

        <input type="submit" name="submit" value="SUBMIT">
        <input type="button" value="BACK" onclick="location.href='admin.php'">

    </form>
    <?php
if (isset($_POST) && isset($_POST["category1"])) {
                if (isset($_FILES)) {
                    $_POST["imageName"] = IMAGE::upload($_POST, $_FILES);
                }
                $product = new PRODUCT($bdd, $_POST, "");
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
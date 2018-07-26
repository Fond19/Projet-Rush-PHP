<?php
include_once "User.php";
include_once "Product.php";

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
            $req = $bdd->prepare("SELECT name, price, category_id FROM products WHERE id = :id;");
            $req->execute(array(":id" => $_GET["id"]));
            $res = $req->fetch();
            if (!$res) {
                echo "<p>This product doesn't exist.</p>";
                echo "<p><a href='admin.php'>Back</a></p>";
            } else {
                ?>
<form method="post">
        Name:   <input type="text" name="name" value="<?php echo $res["name"] ?>" required>
        <br><br>
        Price:   <input type="number" min='0' step='0.01' name="price" value="<?php echo $res["price"] ?>" required>
        <br><br>
        <?php
$subcat_id = $res["category_id"];
                $req2 = $bdd->prepare("SELECT name, parent_id FROM categories WHERE id = :id;");
                $req2->execute(array(":id" => $subcat_id));
                $res2 = $req2->fetch();
                $cat_id = $res2["parent_id"];
                $subcat = $res2["name"];
                $req2->closeCursor();
                $req2 = $bdd->prepare("SELECT name FROM categories WHERE id = :id;");
                $req2->execute(array(":id" => $cat_id));
                $res2 = $req2->fetch();
                $cat = $res2["name"];
                $req2->closeCursor();
                ?>
        Category: <select name="category0">
        <?php
$req2 = $bdd->query("SELECT name, id FROM categories WHERE parent_id IS NULL;");

                while ($res2 = $req2->fetch()) {
                    if ($res2["name"] == $cat)
                    echo "<option value='" . $res2["name"] . "' selected>" . $res2["name"] . "</option>";
                    else
                    echo "<option value='" . $res2["name"] . "'>" . $res2["name"] . "</option>";
                }
                $req2->closeCursor();
                ?>
        </select><br><br>
        Sub-category: <select name="category1">
        <?php
$req2 = $bdd->prepare("SELECT name, id FROM categories WHERE parent_id = :id;");
$req2->execute(array(":id"=>$cat_id));

                while ($res2 = $req2->fetch()) {
                    if ($res2["name"] == $subcat)
                    echo "<option value='" . $res2["name"] . "' selected>" . $res2["name"] . "</option>";
                    else
                    echo "<option value='" . $res2["name"] . "'>" . $res2["name"] . "</option>";
                }
                $req2->closeCursor();
                ?>
        </select><br><br>

        <input type="submit" name="submit" value="SUBMIT">
        <input type="button" value="BACK" onclick="location.href='admin.php'">
        <br><br>
 </form>
<?php
if (!empty($_POST)) {
                    PRODUCT::edit_product($bdd, $_GET["id"], $_POST, 1);
                }

            }
        } else {
            echo "<p>You do not have the rights to edit product info.</p>";
            echo "<p><a href='admin.php'>Back</a></p>";
        }
    } else {
        echo "<p>You do not have the rights to edit product info.</p>";
        echo "<p><a href='index.php'>Back</a></p>";
    }
} else {
    echo "<p>You do not have the rights to edit product info.</p>";
    echo "<p><a href='login.php'>Back</a></p>";
}
?>
    </body>
</html>
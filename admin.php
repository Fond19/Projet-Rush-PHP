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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
        </head>
        <header>
            <nav>
                <div class="nav-wrapper">
                   <a href="index.php" class="brand-logo center">Nomad Electronics</a>
                </div>
            </nav>
        </header>
<body class="center-align">
    <div class="container">
        <div class="row">
            <h3 class="center-align">Administrator</h3>
        </div>
    </div>
<?php if (isset($_COOKIE["email"]) && isset($_COOKIE["password"])) {
    if (USER::is_logged_in($bdd, $_COOKIE["email"], $_COOKIE["password"]) && USER::is_admin($bdd, $_COOKIE["email"])) {?>
        <h4>User</h4>

        <div><button onclick="location.href='createUser.php';" class="btn waves-effect waves-light z-depth-3" type="submit" name="submit">Create New User</div></br>
        <div class="container">
            <table class='centered highlight'>
            <tr>
                <td><strong>User</strong></td>
                <td><strong>Action</strong></td>
            </tr>
            <?php
            $req = $bdd->query("SELECT COUNT(id) AS users FROM users;");
            $users = $req->fetch();
            $req->closeCursor();
            $pages = ceil($users["users"] / 5);
            if (!isset($_GET["page_user"]) || $_GET["page_user"] == 1) {
                $limit = 0;
            } elseif ($_GET["page_user"] <= $pages) {
                $limit = ($_GET["page_user"] - 1) * 5;
            } else {
                echo "<p>This page doesn't exist.</p>";
            }

            $req = $bdd->query("SELECT username,id FROM users ORDER BY id LIMIT " . $limit . ", 5");

            while ($res = $req->fetch()) {
                echo "<tr><td>";
                echo $res['username'];
                echo "</td><td><a href='display.php?id=" . $res['id'] . "'>Display<br/></a>&nbsp<a href='edit.php?id=" . $res['id'] . "'>
                Edit<br/></a>&nbsp<a href='delete.php?id=" . $res['id'] . "'>Delete<br/></a></td></tr>";
            }
            $req->closeCursor();
            echo "</table>";
        echo "<p>Page: ";
        for ($page = 1; $page <= $pages; $page++){
        if (!isset($_GET["page_prod"]))
        echo " <a href='admin.php?page_user=" . $page . "'>" . $page . "</a> ";
        else
        echo " <a href='admin.php?page_user=" . $page . "&page_prod=" . $_GET["page_prod"] . "'>" . $page . "</a> ";
        }

        ?>
        </div>
        <h4>Product</h4>
        <div><button onclick="location.href='addnewproduct.php';" class="btn waves-effect waves-light z-depth-3" type="submit" name="submit">Add New Product</button> 
        <button onclick="location.href='create_category.php';" class="btn waves-effect waves-light z-depth-3" type="submit" name="submit">Add New Category</div></br>
        <div class="container">
        <table class='centered highlight'>
        <tr>
        <td><strong>Product</strong></td>
        <td><strong>Action</strong></td>
        </tr>
        <?php
        $req = $bdd->query("SELECT COUNT(id) AS products FROM products;");
        $products = $req->fetch();
        $req->closeCursor();
        $pages = ceil($products["products"] / 5);
        if (!isset($_GET["page_prod"]) || $_GET["page_prod"] == 1) {
            $limit = 0;
        } elseif ($_GET["page_prod"] <= $pages) {
            $limit = ($_GET["page_prod"] - 1) * 5;
        } else {
            echo "<p>This page doesn't exist.</p>";
        }

$req = $bdd->query("SELECT name,id FROM products ORDER BY id LIMIT " . $limit . ", 5");

        while ($res = $req->fetch()) {
            echo "<tr><td>";
            echo $res['name'];
            echo "</td><td><a href='display_product.php?id=" . $res['id'] . "'>Display<br/></a>&nbsp<a href='edit_product.php?id=" . $res['id'] . "'>
             Edit<br/></a>&nbsp<a href='delete_product.php?id=" . $res['id'] . "'>Delete<br/></a></td></tr>";
        }
        $req->closeCursor();
        echo "</table>";
        echo "<p>Page: ";
        for ($page = 1; $page <= $pages; $page++){
            if (!isset($_GET["page_user"]))
            echo " <a href='admin.php?page_prod=" . $page . "'>" . $page . "</a> ";
            else
            echo " <a href='admin.php?page_prod=" . $page . "&page_user=" . $_GET["page_user"] . "'>" . $page . "</a> ";
            }
        ?>

        </div>
        <div class="center-align"><button onclick="location.href='index.php';" class="btn waves-effect waves-light pulse" type="submit" name="submit">Back to index</div></br>
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
} else {
        echo "<p>You do not have the rights.</p>";
        echo "<p><a href='index.php'>Back</a></p>";
    }
} else {
    echo "<p>You do not have the rights.</p>";
    echo "<p><a href='login.php'>Back</a></p>";
}
?>

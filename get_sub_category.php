<?php
include_once "User.php";
include_once "Product.php";

try {
    $bdd = new PDO("mysql:host=localhost;dbname=pool_php_rush", "root", "root");
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}

$req = $bdd->prepare("SELECT name FROM categories WHERE parent_id = (SELECT id FROM categories WHERE name = :name);");
$req->execute(array(":name" => $_GET["category"]));
?>Sub-category: <select name="category1" id="category1">
<?php
while ($res = $req->fetch()) {
    echo "<option value='" . $res["name"] . "'>" . $res["name"] . "</option>";
}?>
</select>

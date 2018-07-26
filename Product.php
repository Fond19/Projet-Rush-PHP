<?php
class Product
{

    public function __construct($db, $data)
    {
        $error = $this->check_data($data);
        if (is_array($error)) {
            foreach ($error as $value) {
                echo $value . "\n";
            }
            return false;
        } else {
            if ($this->product_exist($db, $data)) {
                return false;
            } else {
                $req = $db->prepare("SELECT id FROM categories WHERE name = :name;");
                $req->execute(array(":name" => $data["category1"]));
                $res = $req->fetch();
                $cat_id = $res["id"];
                $req->closeCursor();
                if (isset($data["imageName"])) {
                    $req = $db->prepare("INSERT INTO products(name,price,image,category_id) VALUES(:name,:price,:image,:cat)");
                    $data['name'] = htmlspecialchars($data['name']);
                    $data['price'] = htmlspecialchars($data['price']);
                    $req->execute(array(":name" => $data['name'],
                        ":price" => $data['price'],
                        ":image" => $data['imageName'],
                        ":cat" => $cat_id,
                    ));
                } else {
                    $req = $db->prepare("INSERT INTO products(name,price,category_id) VALUES(:name,:price,:cat)");
                    $data['name'] = htmlspecialchars($data['name']);
                    $data['price'] = htmlspecialchars($data['price']);
                    $req->execute(array(":name" => $data['name'],
                        ":price" => $data['price'],
                        ":cat" => $cat_id,
                    ));
                }
                header("location:admin.php");
                exit();
            }
        }

    }

    public static function display_product($db, $id)
    {
        $req = $db->prepare("SELECT products.name, products.price, products.image, categories.name AS 'cat', categories.parent_id FROM products INNER JOIN categories ON products.category_id = categories.id WHERE products.id = :id;");
        $req->execute(array(":id" => $id));
        $res = $req->fetch();

        if (!$res) {
            echo "<p>This product doesn't exist.</p>";
        } else {
            $name = $res["name"];
            $price = $res["price"];
            $cat2 = $res["cat"];
            $image = $res["image"];
            $parent_id = $res["parent_id"];
            if ($parent_id != null) {
                $req->closeCursor();
                $req = $db->prepare("SELECT name FROM categories WHERE id = :id;");
                $req->execute(array(":id" => $parent_id));
                $res = $req->fetch();
            }
            if ($parent_id == null) {
                $cat1 = "";
            } else {
                $cat1 = $res["name"] . " > ";
            }
            if ($image != 0) {
                echo "<p><img src='uploads/" . $image . "' height='300px'/></p>";
            }

            echo "<p><strong>Name</strong>: " . $name . "</p>";
            echo "<p><strong>Price</strong>: " . $price . "</p>";
            echo "<p><strong>Category</strong>: " . $cat1 . $cat2 . "</p>";

        }

    }

    public static function edit_product($db, $id, $data)
    {
        if (!SELF::product_exist($db, $data, $id)) {
            $req2 = $db->prepare("SELECT id FROM categories WHERE name = :name;");
            $req2->execute(array(":name" => $data["category1"]));
            $res2 = $req2->fetch();
            $cat_id = $res2["id"];
            $req = $db->prepare("UPDATE products SET name = :name, price = :price, category_id = :cat_id WHERE id = :id;");
            $req->execute(array(":name" => $data["name"],
                ":price" => $data["price"],
                ":cat_id" => $cat_id,
                ":id" => $id));
            echo "<p>Product updated.</p>";
            echo "<p><a href='admin.php'>Back</a></p>";
        } else {
            echo "<p><a href='admin.php'>Back</a></p>";
            return false;
        }

    }

    public function delete_product($db, $id)
    {

        $req = $db->prepare("DELETE FROM products WHERE id = :id;");
        $req->execute(array(":id" => $id));
        $res = $req->fetch();
        echo "<p>Product deleted.</p>";

    }

    public function check_data($data)
    {
        $error = array();
        $i = 0;
        $error[0] = 'OK';

        if (strlen($data['name']) < 3) {
            $error[$i] = "Name is too short";
            $i++;
        }

        // if (!is_int($data['price']) && !is_float($data['price'])) {
        //     $error[$i] = "Price must be a number";
        //     $i++;
        // }

        // elseif ($data['price']<0) {
        //     $error[$i] = "Price must be positive";
        //     $i++;
        // }

        if ($error[0] == 'OK') {
            return true;
        } else {
            return $error;
        }

    }

    public function product_exist($db, $data, $id = "")
    {

        if ($id == "") {
            $query = $db->prepare("SELECT name FROM products WHERE name=:name;");
            $query->execute(array(":name" => htmlspecialchars($data['name'])));
        } else {
            $query = $db->prepare("SELECT name FROM products WHERE name=:name AND id != :id;");
            $query->execute(array(":name" => htmlspecialchars($data['name']), ":id" => $id));
        }

        $res = $query->fetch();

        if ($res == false) {
            return false;

        } else {
            echo "Product already exists";
            return true;
        }
    }

}

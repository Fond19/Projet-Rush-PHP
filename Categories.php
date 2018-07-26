<?php
class Category
{
    public function __construct($db, $data)
    {
        if ($data['name'] != "") {
            $data['name'] = htmlspecialchars($data['name']);
            $this->cat_exists($db, $data);
            if ($data["parent"] == "none") {
                $data["parent"] = null;
            } else {
                $req = $db->prepare("SELECT id FROM categories WHERE name = :name");
                $req->execute(array(":name" => $data["parent"]));
                $res = $req->fetch();
                $data["parent"] = $res["id"];
                $req->closeCursor();
            }
            $req = $db->prepare("INSERT INTO categories(name, parent_id) VALUES(:name, :parent)");
            $req->execute(array(":name" => $data["name"], ":parent" => $data["parent"]));
            header("location:admin.php");
            exit();
        } else {
            echo "<p>The category name cannot be empty</p>";
            return false;
        }
    }

    public function cat_exists($db, $data)
    {
        $query = $db->prepare("SELECT name FROM categories WHERE name=:name;");
        $query->execute(array(":name" => $data['name']));

        $res = $query->fetch();

        if ($res == false) {
            return false;

        } else {
            echo "Category already exists";
            return true;
        }
    }
}

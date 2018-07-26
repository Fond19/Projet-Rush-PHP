<?php
include_once "User.php";
try {
    $bdd = new PDO("mysql:host=localhost;dbname=pool_php_rush", "root", "root");
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
if (isset($_COOKIE["email"]) && isset($_COOKIE["password"])) {
    $login = USER::is_logged_in($bdd, $_COOKIE["email"], $_COOKIE["password"]);
    if (!$login) {
        header("Location: login.php");
        exit();
    } else {
        ?>

        <!DOCTYPE html>
        <html>
        <head>
        <title>Welcome to Nomad Electronics</title>
        <meta charset="utf8"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
            <br><br>
            <h3>Search</h3>
            <form method="get">
        <input type="text" name="search" placeholder="Search for..." required>
        <button class="btn waves-effect waves-light z-depth-3" type="submit" name="submit">Go</button>         
    </form>
        <?php
         if(isset($_GET['search'])){

        $href="index.php?search=".$_GET['search']."&";
        echo "
        <table>
        <tr>
        <th><a href='".$href."sort=name'> ▲</a> &nbsp  Product <a href='".$href."sort=nameDESC'> &nbsp  ▼ </a></th>
        <th><a href='".$href."sort=price'>▲</a> &nbsp  Price <a href='".$href."sort=priceDESC'> &nbsp  ▼ </a></th>
        <th><a href='".$href."sort=category'> ▲</a>&nbsp  Category<a href='".$href."sort=categoryDESC'> &nbsp  ▼ </a></th>
        </tr>";
        
        
       
            
            $_GET['search']=strtolower(trim($_GET['search']));
            
            $keyword="%".$_GET['search']."%";
                            
            if (isset($_GET['sort'])){
            
            switch($_GET['sort']){
                case "name":
                $req=$bdd->prepare("SELECT name,price,id,category_id FROM products WHERE name LIKE :name ORDER BY name ;");
                break;
                case "nameDESC":
                $req=$bdd->prepare("SELECT name,price,id,category_id FROM products WHERE name LIKE :name ORDER BY name DESC;");
                break;
                case "price":
                $req=$bdd->prepare("SELECT name,price,id,category_id FROM products WHERE name LIKE :name ORDER BY price;");
                break;
                case "priceDESC":
                $req=$bdd->prepare("SELECT name,price,id,category_id FROM products WHERE name LIKE :name ORDER BY price DESC;");
                break;
                case "category":
                $req=$bdd->prepare("SELECT products.name,products.price,products.id,products.category_id FROM products 
                INNER JOIN categories ON products.category_id=categories.id WHERE products.name LIKE :name ORDER BY categories.name;");
                break;
                case "categoryDESC":
                $req=$bdd->prepare("SELECT products.name,products.price,products.id,products.category_id FROM products 
                INNER JOIN categories ON products.category_id=categories.id WHERE products.name LIKE :name ORDER BY categories.name DESC;");
                break;
            }
        }
        else

        $req=$bdd->prepare("SELECT name,price,id,category_id FROM products WHERE name LIKE :name;");
            
            
            $req->execute(array(":name"=>$keyword));
                                    
            while ($res = $req->fetch()) {
            
                echo "<tr><td><a href='display_product.php?id=" . $res['id'] . "'>" . $res['name']."</a></td>";
                echo "<td>".$res['price']."</td>";

                $req2=$bdd->prepare("SELECT name,id FROM categories WHERE id = :id;");  
                
                $req2->execute(array(":id"=>$res['category_id']));
                $res2=$req2->fetch();
            
                echo "<td>".$res2['name']."</td></tr>";
                
            }
       
        ?>
         </table>
         
         <?php }
         else{?>
         <ul>
         <li><a href="index.php?cat=">All products</a></li>
         
         <?php $req=$bdd->query("SELECT name,id FROM categories WHERE parent_id IS NULL ");
         while($res=$req->fetch()){
            echo "<li><strong>".$res['name']."</strong><ul>";
        
            $req2=$bdd->prepare("SELECT name,id FROM categories WHERE parent_id = :id");
            $req2->execute(array(":id"=>$res['id']));
            while($res2=$req2->fetch())
                echo "<li><a href='index.php?cat=".$res2['id']."'>".$res2['name']."</a></li>";
        
            echo"</ul></li>";
         }
        
         ?>

        </ul>

         <?php
          if(isset($_GET) && isset($_GET['cat'])){
              echo"<table class='centered highlight'>
              <tr>
              <td><strong>Product</strong></td>
              <td><strong>Price</strong></td>
              <td><strong>Category</strong></td>
              </tr>";
             if($_GET['cat']==""){
                $req3=$bdd->query("SELECT products.name,products.price,products.id,categories.name AS categ FROM products 
                INNER JOIN categories ON products.category_id=categories.id");
               
             }
             else{
                $req3=$bdd->prepare("SELECT products.name,products.price,products.id,categories.name AS categ FROM products 
                INNER JOIN categories ON products.category_id=categories.id WHERE categories.id=:id;");
                $req3->execute(array(":id"=>$_GET['cat']));
             }
             while($res3=$req3->fetch()){
             echo "<tr><td><a href='display_product.php?id=" . $res3['id'] . "'>" . $res3['name']."</a></td>";
             echo "<td>".$res3['price']."</td><td>".$res3['categ']."</td></tr>";
          }
          echo"</table>";
        }
          } ?>

                <p class="center-align">
                    <?php if (USER::is_admin($bdd, $_COOKIE["email"]))
                    {
                    ?> <button onclick="location.href='admin.php'" class="btn waves-effect waves-light z-depth-3" type="submit" name="submit">Admin Settings</button>
                    <?php
                    }
                    ?> 
                    <button onclick="location.href='logout.php'" class="btn waves-effect waves-light z-depth-3" type="submit" name="submit">Logout</button> 
                </p>
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
}
else{
header("Location: login.php");
exit();
}

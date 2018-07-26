<?php
class User
{
    protected $username;
    protected $email;
    protected $password;
    protected $id;

    private static $i = 0;

    public function __construct($db, $data, $isadmin=NULL)
    {
        
        $error = $this->check_data($data);
        if (is_array($error)) {
            foreach ($error as $value) {
                echo $value . "\n";
            }
            return false;
        } else {
            if ($this->user_exist($db, $data)) {
                return false;
            } else {
                $this->username = $data['username'];
                $this->email = $data['email'];
                $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
                self::$i++;
                $this->id = SELF::$i;
                if(isset($data["admin"]))
                    $admin = 1;
                else
                    $admin = NULL;
                $req = $db->prepare("INSERT INTO users(username,password,email, admin) VALUES(:username,:password,:email, :admin)");
                $data['username'] = htmlspecialchars($data['username']);
                $data['password'] = $this->password;
                $data['email'] = htmlspecialchars($this->email);
                $req->execute(array(":username" => $this->username,
                    ":password" => $this->password,
                    ":email" => $this->email,
                    ":admin" => $admin,
                ));
                if ($isadmin == NULL)
                    header("location:login.php");
                else
                    header("location:admin.php");
                exit();

            }
        }

    }

    public static function display_user($db, $id)
    {
        $req = $db->prepare("SELECT username, email, admin FROM users WHERE id = :id;");
        $req->execute(array(":id" => $id));
        $res = $req->fetch();

        if (!$res) {
            echo "<p>This user doesn't exist.</p>";
        } else {
            echo "<p><strong>Username</strong>: " . $res['username'] . "</p>";
            echo "<p><strong>Email</strong>: " . $res['email'] . "</p>";
            if ($res["admin"]) {
                echo "<p><strong>Admin</strong>: YES</p>";
            } else {
                echo "<p><strong>Admin</strong>: NO</p>";
            }
        }
    }

    public static function edit_user($db, $id, $data)
    {
        if (!SELF::user_exist($db, $data, $id)) {
            if (isset($data["admin"])) {
                $admin = 1;
            } else {
                $admin = null;
            }
            $req = $db->prepare("UPDATE users SET username = :username, email = :email, admin = :admin WHERE id = :id;");
            $req->execute(array(":username" => $data["username"],
                ":email" => $data["email"],
                ":admin" => $admin,
                ":id" => $id));
            echo "<p>User updated.</p>";
            echo "<p><a href='admin.php'>Back</a></p>";
        } else {
            echo "<p><a href='admin.php'>Back</a></p>";
            return false;
        }

    }

    public function delete_user($db, $id)
    {
        $req = $db->prepare("SELECT admin FROM users WHERE id = :id;");
        $req->execute(array(":id" => $id));
        $res = $req->fetch();

        if (!$res) {
            echo "<p>This user doesn't exist.</p>";
        } elseif ($res['admin'] == null) {

            $req->closeCursor();
            $req = $db->prepare("DELETE FROM users WHERE id = :id;");
            $req->execute(array(":id" => $id));
            $res = $req->fetch();
            echo "<p>User deleted.</p>";
        } else {
            echo "<p>You cannot delete an admin.</p>";
        }
    }

    public function is_admin($db, $email)
    {
        $req = $db->prepare("SELECT admin FROM users WHERE email = :email;");
        $req->execute(array(":email" => $email));
        $res = $req->fetch();
        if ($res["admin"]) {
            return true;
        } else {
            return false;
        }
    }

    public function check_data($data)
    {
        $error = array();
        $i = 0;
        $error[0] = 'OK';

        if (strlen($data['username']) < 3) {
            $error[$i] = "Username is too short";
            $i++;
        }

        if (strlen($data['password']) < 6) {
            $error[$i] = "Password is too short";
            $i++;
        }

        if ($data['password'] != $data['password_confirmation']) {
            $error[$i] = "Password doesn't match";
            $i++;
        }

        if ($error[0] == 'OK') {
            return true;
        } else {
            return $error;
        }

    }

    public function user_exist($db, $data, $id = "")
    {
        if ($id == "") {
            $query = $db->prepare("SELECT username FROM users WHERE username=:username;");
            $query->execute(array(":username" => $data['username']));} else {
            $query = $db->prepare("SELECT username FROM users WHERE username=:username AND id != :id;");
            $query->execute(array(":username" => $data['username'],
                ":id" => $id));
        }
        $res = $query->fetch();

        if ($res == false) {
            $query->closeCursor();

            if ($id == "") {
                $query = $db->prepare("SELECT email FROM users WHERE email=:email;");
                $query->execute(array(":email" => $data['email']));} else {
                $query = $db->prepare("SELECT email FROM users WHERE email=:email AND id != :id;");
                $query->execute(array(":email" => $data['email'],
                    ":id" => $id));
            }
            $res = $query->fetch();
            if ($res == false) {
                return false;
            } else {
                echo "Email already linked to another username";
                return true;
            }
        } else {
            echo "Username already exist";
            return true;
        }
    }

    public static function is_logged_in($db, $email, $password)
    {
        $req = $db->prepare("SELECT password FROM users WHERE email = :email;");
        $req->execute(array(":email" => $email));
        $res = $req->fetch();
        if (!$res) {
            echo "Incorrect email/password";
            return false;
        } else {
            if ($res["password"] == $password) {
                return true;
            } else {
                echo "Incorrect email/password";
                return false;
            }
        }
    }

    public static function log_in($db, $data)
    {
        $query = $db->prepare("SELECT email, password FROM users WHERE email=:email;");
        $query->execute(array(":email" => $data['email']));
        $res = $query->fetch();
        if (!$res) {
            echo "Invalid email/password.";
            return false;
        } else {
            if (password_verify($data["password"], $res["password"])) {
                setcookie("email", $data["email"], time() + 365 * 24 * 3600, null, null, false, true);
                setcookie("password", $res["password"], time() + 365 * 24 * 3600, null, null, false, true);
                header("Location: index.php");
                exit();
                return true;
            } else {
                echo "Invalid email/password.";
                return false;
            }

        }
    }
}

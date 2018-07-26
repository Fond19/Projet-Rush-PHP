<?php

unset($_COOKIE["email"]);
unset($_COOKIE["password"]);
setcookie("email", " ", time()-3600);
setcookie("password", " ", time()-3600);
header("Location: login.php");
exit();

?>
<?php
require ('config.php');
session_start();
if (isset($_POST["login"])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = addslashes(trim($_POST['username']));
        $password = md5(addslashes(trim($_POST['password'])));

        $result = $db->prepare('SELECT * FROM userlist WHERE username LIKE :username AND password LIKE :password LIMIT 1');
        $result->execute([':username' => $username, ':password' => $password]);
        $count = $result->fetch();

        if ($count) {
            $dbusername = $count['username'];
            $dbpassword = $count['password'];
            if (($username == $dbusername) && ($password == $dbpassword)) {
                $_SESSION['sessionUsername'] = $username;
                header("Location: /");
            }
        } else {
            header("Location: login.php");
            header("Location: login.php?msg=Invalid%20username%20or%20password!");
        }

    } else {
        header("Location: login.php");
        header("Location: login.php?msg=All%20fields%20are%20required!");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login File Manager</title>
    <link href="style.css" media="screen" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800'
          rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container mlogin">
    <div id="login">
        <h1>Login</h1>
        <form action="" id="loginform" method="post" name="loginform">
            <p><label for="user_login">User name<br>
                    <input class="input" id="username" name="username" size="20"
                           type="text" value=""></label></p>
            <p><label for="user_pass">Password<br>
                    <input class="input" id="password" name="password" size="20"
                           type="password" value=""></label></p>
            <p class="submit"><input class="button" name="login" type="submit" value="Log In"></p>
            <p class="regtext">Not registered yet?&nbsp;<a href="register.php">Registration</a>!</p>
        </form>
    </div>
</div>
<footer class="footer">
    <a href="https://www.instagram.com/tromomito/"><img src="images/instagram.jpg" width="50"
                                                            height="50"></a><br>
    &copy;  2020 All right reserved!
</footer>
</body>
</html>







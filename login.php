<?php
require ('config.php');
session_start();
if (isset($_POST["login"], $_POST['username'], $_POST['password'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = addslashes(trim($_POST['username']));
        $password = addslashes(trim($_POST['password']));
        $result = $db->prepare('SELECT * FROM userlist WHERE username LIKE :username LIMIT 1');
        $result->execute([':username' => $username]);
        $count = $result->fetch();
        if ($count) {
            if (password_verify($password, $count['password'])) {
                $_SESSION['sessionUsername'] = $username;
                header("Location: /");
                exit;
            }
            else {
                header("Location: login.php");
                header("Location: login.php?msg=Invalid%20password!");
                exit;
            }
        }
         else {
            header("Location: login.php");
            header("Location: login.php?msg=Invalid%20username!");
            exit;
        }

    } else {
        header("Location: login.php");
        header("Location: login.php?msg=All%20fields%20are%20required!");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login File Manager</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="style.css" media="screen" rel="stylesheet">
</head>
<body>
<div class="header">
</div>
<div class="container mlogin">
    <?php echo $text; ?>
    <div id="login">
        <h1>Please sign in</h1>
        <form action="" id="loginform" method="post" name="loginform">
            <p><label for="user_login">User name<br>
                    <input class="input" id="username" name="username" size="20"
                           type="text"></label></p>
            <p><label for="user_pass">Password<br>
                    <input class="input" id="password" name="password" size="20"
                           type="password" value=""></label></p>
            <button class="btn btn-bg btn-success" name="login" type="submit"><i class="glyphicon glyphicon-log-in
"></i> Sign in</button>
            <p class="regtext">Not registered yet?&nbsp;<a href="register.php">Registration</a>!</p>
            <p class="regtext">Forgot your password?&nbsp;<a href="recovery.php">Recovery password</a>!</p>
        </form>
    </div>
</div>
    <footer class="footer">
    <ul class="hr">
        <li>
            <a href="https://www.instagram.com/tromomito/"><img src="images/instagram.png" width="30"
                                                                height="30"></a>
        </li>
        <li>
            <a href="https://twitter.com/tromomito"><img src="images/twitter.png" width="30"
                                                         height="30"></a>
        </li>
        <li>
            <a href="mailto:tromomito@gmail.com"><img src="images/gmail.png" width="30"
                                                      height="30"></a>
        </li>
    </ul>
    &copy; <?php echo date("Y"); ?> All right reserved!
    </footer>
</body>
</html>







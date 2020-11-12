<?php
require('config.php');
if (isset($_POST['password'], $_POST['password2'])) {
    if (!empty($_POST['password']) && !empty($_POST['password2'])) {
        if ($_POST['password'] != $_POST['password2']) {
            header("Location: newpassword.php");
            header("Location: newpassword.php?msg=Passwords%20do%20not%20match!");
            exit;
        }
        $password = password_hash((addslashes(trim($_POST['password']))), PASSWORD_BCRYPT);
        $hash = $_GET['hash'];
        $edit = $db->prepare('UPDATE userlist SET password = :password, hash = NULL WHERE hash LIKE :hash');
        $edit->execute([
            ':hash' => $hash,
            ':password' => $password
        ]);
        header("Location: newpassword.php");
        header("Location: newpassword.php?msg=Password%20changed%20successful!");
        exit;
    } else {
        header("Location: newpassword.php");
        header("Location: newpassword.php?msg=All%20fields%20are%20required!");
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New password File Manager</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="style.css" media="screen" rel="stylesheet">
</head>
<body>
<div class="header">
</div>
<div class="container mnewpassword">
    <?php echo $text; ?>
    <div id="newpassword">
        <h1>New password</h1>
        <br>
        <hr/>
        <h3 align="center">Enter your new password!</h3>
        <form action="" id="newpasswordform" method="post" name="newpasswordform">
            <p><label for="user_pass">Password<br>
                    <input class="input" id="password1" name="password" size="32" type="password" value=""></label></p>
            <p><label for="user_pass2">Repeat password<br>
                    <input class="input" id="password2" name="password2" size="32" type="password" value=""></label></p>
            <button class="btn btn-bg btn-success" name="change" type="submit"><i class="glyphicon
            glyphicon-ok-sign"></i> change</button>
            <p class="regtext">Remember your password?&nbsp;<a href="login.php">Sign in</a>!</p>
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


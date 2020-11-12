<?php
require ('config.php');
if (isset($_POST["recovery"])) {
    if (!empty($_POST['email'])){
        $email = addslashes(trim($_POST['email']));
        $result = $db->prepare('SELECT * FROM userlist WHERE email LIKE :email');
        $result->execute([':email' => $email]);
        $count = $result->fetch();
        if ($count) {
                $hash = md5($email.time());
                $subject = "Recovery password for File Manager!";
                $headers  = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=utf-8\r\n";
                $headers .= "To: <$email>\r\n";
                $headers .= "From: <tromomito@gmail.com>\r\n";
                $message = '
                <html>
                <head>
                <title>Подтвердите Email</title>
                </head>
                <body>
                <p>Что бы восстановить пароль перейдите по <a href="http://filemanager.loc:8888/newpassword.php?hash=' . $hash . '">recovery password</a></p>
                </body>
                </html>
                ';
                $edit = $db->prepare("UPDATE userlist SET hash = :hash WHERE email = :email");
                $edit->execute([
                    ':hash' => $hash,
                    ':email' => $email,
                ]);
                mail($email, $subject, $message, $headers);
                header("Location: recovery.php");
                header("Location: recovery.php?msg=Check%20your%20email!");
                exit;

        }
        else {
            header("Location: recovery.php");
            header("Location: recovery.php?msg=Invalid%20email!%20Try%20another%20one.");
            exit;
        }

    }
    else {
        header("Location: recovery.php");
        header("Location: recovery.php?msg=Please%20enter%20your%20email!");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Recovery password File Manager</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="style.css" media="screen" rel="stylesheet">
</head>
<body>
<div class="header">
</div>
<div class="container mrecovery">
    <?php echo $text; ?>
    <div id="recovery">
        <h1>Recovery password</h1>
        <br>
        <hr />
        <h3 align="center">Enter your email please!</h3>
        <form action="recovery.php" id="recoveryform" method="post" name="recoveryform">
            <p><label for="user_email">E-mail<br>
                    <input class="input" id="email" name="email" size="32" type="email" value=""></label></p>
            <button class="btn btn-bg btn-success" name="recovery" type="submit"><i class="glyphicon glyphicon-envelope
"></i> Recovery</button>
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

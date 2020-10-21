<?php require ('config.php');
$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';

if (isset($_POST['register'])) {
    if (!empty($_POST['full_name']) && !empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $result = $db->prepare('SELECT * FROM userlist WHERE username = :username');
        $result->execute(['username' => $username]);
        if ($result !== true) {
            $data = $db->prepare('INSERT INTO userlist (full_name, email, username,password) VALUES (:full_name, :email, :username, :password)');
            $data->bindParam(':full_name', $full_name);
            $data->bindParam(':email', $email);
            $data->bindParam(':username', $username);
            $data->bindParam(':password', $password);
            $data->execute();
            header("Location: http://filemanager.loc:8888/register.php");
            header("Location: register.php?msg=Account%20Successfully%20Created!");

        } else {
            header("Location: http://filemanager.loc:8888/register.php");
            header("Location: register.php?msg=That%20username%20already%20exists!%20Please%20try%20another%20one!");
        }
    } else {
        header("Location: http://filemanager.loc:8888/register.php");
        header("Location: register.php?msg=All%20fields%20are%20required!");
    }
}
if ($msg) {
     echo "<h2 class='error'><b>$msg</b></h2>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Registration File Manager</title>
    <link href="style.css" media="screen" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800'
          rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container mregister">
    <div id="login">
        <h1>Registration</h1>
        <form action="register.php" id="registerform" method="post" name="registerform">
            <p><label for="user_login">Full name<br>
                    <input class="input" id="full_name" name="full_name" size="32" type="text" value=""></label></p>
            <p><label for="user_pass">E-mail<br>
                    <input class="input" id="email" name="email" size="32" type="email" value=""></label></p>
            <p><label for="user_pass">Username<br>
                    <input class="input" id="username" name="username" size="20" type="text" value=""></label></p>
            <p><label for="user_pass">Password<br>
                    <input class="input" id="password" name="password" size="32" type="password" value=""></label></p>
            <p class="submit"><input class="button" id="register" name="register" type="submit"
                                     value="Register now"></p>
            <p class="regtext">Already Registered?&nbsp;<a href="login.php">Enter username please</a>!</p>
        </form>
</body>
</html>


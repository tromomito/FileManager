<?php
function processDir($path)
{
    $dir_handle = opendir($path);
    while (false !== ($name = readdir($dir_handle))) {
        if ($name == '..') {
            $pathArr = explode('/', $path);
            $pathNoLast = array_slice($pathArr, 0, count($pathArr) - 1);
            echo '<tr><td></td><td><a href="index.php?path=' . urlencode(implode('/', $pathNoLast)) . '">' . $name . '</a></td><td></td><td></td><td></td><td></td></tr>';
            continue;
        } elseif ($name == '.') {
            continue;
        }
        if (is_dir($path . '/' . $name)) {
            $nextPath = $path . '/' . $name;
            echo '<tr><td><a href="index.php?delPath='. urlencode($nextPath).'" class="delete_btn" onclick="return confirm(\'Are you sure?\')">DELETE</a></td><td>[dir] <a href="index.php?path=' . urlencode
                ($nextPath) . '">' . $name . '</a></td><td></td><td></td><td></td><td></td></tr>';
        } elseif (is_file($path . '/' . $name)) {
            $filePath = $path . '/' . $name;
            $stat = stat($filePath);
            echo '<tr bgcolor="#bdbebd"><td><a href="index.php?delPath='. urlencode($filePath).'" class="delete_btn" onclick="return confirm(\'Are you sure?\')">DELETE</a></td><td>[file]<a href="' . urlencode
                ($filePath) . '">' . $name . '</a> </td><td>' . ($stat['size']) . ' b' . '</td><td>' . $stat['gid'] .
                '</td><td>' . date('d.m.Y', $stat['mtime']) . '</td><td>' . $stat['mode'] . '</td></tr>';
        }
    }
    closedir($dir_handle);
}

$curPath = isset($_GET['path']) ? urldecode($_GET['path']) : getcwd();
$delPath = isset($_GET['delPath']) ? urldecode($_GET['delPath']) : false;

if ($delPath) {
    if (is_dir($delPath)) {
        rmdir($delPath);
    } elseif (is_file($delPath)) {
        unlink($delPath);
    }
    $pathArr = explode('/', $delPath);
    $pathNoLast = array_slice($pathArr, 0, count($pathArr) - 1);
    header("Location: index.php?path=" . urlencode(implode('/', $pathNoLast)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newFolder = isset($_POST['nameFolder']) ? trim($_POST['nameFolder']) : '';
    if ($newFolder) {
        mkdir($curPath . '/' . $newFolder, 0700);
    }
    header("Location:" . $_SERVER['REQUEST_URI']);
}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>File manager</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <style type="text/css">
        .create_btn {
            text-decoration: none;
            padding: 2px 5px;
            background: #5F9EA0;
            color: white;
            border-radius: 3px;
        }
        .delete_btn {
            text-decoration: none;
            padding: 3px;
            font-size: 10px;
            color: white;
            background: #C44737;
            border: none;
            border-radius: 5px;
        }

        .form {
            width: 45%;
            margin: 50px auto;
            text-align: left;
            padding: 20px;
            border: 1px solid #bbbbbb;
            border-radius: 5px;
        }
    </style


</head>
<body>


<form class="form" method="post">
    <a href="http://filemanager.loc:8888"><img src="images/home.png" width="50" height="50"></a>
    <br>
    <br>
    <label for="nameFolder">Please enter "Folder name"!</label><br>
    <input type="text" size="30" name="nameFolder">
    <input class="create_btn" type="submit" value="Create folder">
    <table border="4" bgcolor="#ffffff" bordercolor="#000000">

        <tr>
            <th>Delete</th>
            <th>File</th>
            <th>Size Byte</th>
            <th>Owner</th>
            <th>Date</th>
            <th>Inode</th>
        </tr>

        <?php processDir($curPath); ?>

    </table>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

<!-- Option 2: jQuery, Popper.js, and Bootstrap JS
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
-->
</body>
</html>

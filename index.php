<?php
session_start();
$text = '';
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
            echo '<tr bgcolor="#f0f8ff"><td><a href="index.php?delPath='. urlencode($filePath).'" class="delete_btn" onclick="return confirm(\'Are you sure?\')">DELETE</a></td><td>[file]<a href="' . urlencode
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

 if (isset($_SESSION["sessionUsername"])) :
     $text .= '
            <h3 style="float: right" align="right">Welcome ' . $_SESSION['sessionUsername'] . '!</h3>
            <p align="right"><a href="logout.php"><img src="images/signout.png" width="30"
                                                       height="30"></a></p>';

            endif;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>File manager</title>
    <link href="style.css" media="screen" rel="stylesheet">
</head>
<body>
<div class="container mindex">
    <hr />
    <form method="post">
        <div style="clear: both">
            <a href="http://filemanager.loc:8888"><img src="images/home.png" width="50" height="50"></a>
        <?php echo $text; ?>
        </div>
        <hr />
        <label for="nameFolder">Please enter "Folder name"!</label><br>
        <input type="text" name="nameFolder">
        <input class="create_btn" type="submit" value="Create folder"><br>
        <br>
        <hr />
        <br>
        <table border="4" bordercolor="#000000">
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
        <br>
        <hr />
        <footer class="footer">
            <a href="https://www.instagram.com/tromomito/"><img src="images/instagram.jpg" width="50"
                                                                height="50"></a><br>
            &copy; 2020 All right reserved!
        </footer>
</body>
</html>

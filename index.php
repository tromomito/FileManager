<?php
require('config.php');
session_start();

if (!isset($_SESSION["sessionUsername"])) {
    header("Location:login.php");
    exit;
}
$favor = '';
function processDir($path)
{
    $dir_handle = opendir($path);
    while (false !== ($name = readdir($dir_handle))) {
        if ($name == '..') {
            $pathArr = explode('/', $path);
            $pathNoLast = array_slice($pathArr, 0, count($pathArr) - 1);
            echo '<tr><td></td><td></td><td></td><td>[dir]<a href="index.php?path=' . urlencode(implode('/', $pathNoLast)) . '">' . $name . '</a></td><td></td><td></td><td></td><td></td><td></td></tr>';
            continue;
        } elseif ($name == '.') {
            continue;
        }
        if (is_dir($path . '/' . $name)) {
            $nextPath = $path . '/' . $name;
            echo '<tr><td></td><td></td><td><a href="index.php?delPath=' . urlencode($nextPath) . '" class="delete_btn" onclick="return confirm(\'Are you sure?\')">DELETE</a></td><td>[dir] <a href="index.php?path=' . urlencode
                ($nextPath) . '">' . $name . '</a></td><td><a href="index.php?addToFavor=' . urlencode($nextPath) . '"><img src="images/add_favourite.png" class="imgFav"></a></td><td>Folder</td><td></td><td></td><td></td></tr>';
        } elseif (is_file($path . '/' . $name)) {
            $filePath = $path . '/' . $name;
            $stat = stat($filePath);
            echo '<tr bgcolor="#efefef"><td></td><td><a href="index.php?downPath=' . urlencode
                ($filePath) . '" class="downloadBtn" onclick="return confirm(\'Are you sure you want to download this file?\')">DOWNLOAD</a></td><td><a href="index.php?delPath=' . urlencode($filePath) . '" class="delete_btn" onclick="return confirm(\'Are you sure?\')">DELETE</a></td><td>[file]<a href="' . urlencode
                ($filePath) . '">' . $name . '</a></td><td><a href="index.php?addToFavor=' . urlencode($filePath) . '"><img src="images/add_favourite.png" class="imgFav"></a></td><td>' . ($stat['size']) . ' b' . '</td><td>' . $stat['gid'] .
                '</td><td>' . date('d.m.Y', $stat['mtime']) . '</td><td>' . $stat['mode'] . '</td></tr>';
        }
    }
    closedir($dir_handle);
}

$curPath = isset($_GET['path']) ? urldecode($_GET['path']) : getcwd();
$delPath = isset($_GET['delPath']) ? urldecode($_GET['delPath']) : false;
$downPath = isset($_GET['downPath']) ? urldecode($_GET['downPath']) : false;
$addToFavor = isset($_GET['addToFavor']) ? urldecode($_GET['addToFavor']) : false;

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

if ($downPath) {
    if (is_file($downPath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($downPath) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($downPath));
        readfile($downPath);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFolder = isset($_POST['nameFolder']) ? trim($_POST['nameFolder']) : '';
    if ($newFolder) {
        mkdir($curPath . '/' . $newFolder, 0700);
        header("Location:" . $_SERVER['REQUEST_URI']);
        exit;
    }

}

if (isset($_POST['uploadedFileBtn'])) {
    copy($_FILES['uploadedFile']['tmp_name'], $curPath . '/' . basename($_FILES['uploadedFile']['name']));
    header("Location:" . $_SERVER['REQUEST_URI']);
    exit;
}

if (isset($_SESSION["sessionUsername"])) :
    $text2 .= '
            <h4 style="float: right" align="right">Welcome ' . $_SESSION['sessionUsername'] . '!</h4>
            <p align="right"><a href="logout.php"><img src="images/signout.png" width="30"
                                                       height="30"></a></p>';

endif;


if ($addToFavor) {
    setcookie("favorites", serialize($addToFavor), time()+3600);
    var_dump($_COOKIE);exit;
    if (isset($_COOKIE['favorites'])) {
    $favor .= '<img src="images/star.jpg" class="imgFav">';
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>File manager</title>
    <link rel="icon" type="image/png" sizes="18x18" href="images/cherep.png">
    <link href="style.css" media="screen" rel="stylesheet">
</head>
<body>
<div class="header">
    <?php echo $text; ?>
</div>
<div class="container mindex">
    <hr />
    <form method="post" enctype="multipart/form-data">
    <div style="clear: both">
            <a href="http://filemanager.loc:8888"><img src="images/home.png" width="50" height="50"></a>
        <?php echo $text2; ?>
        </div>
        <hr />
        <label for="nameFolder">Please enter "Folder name"!</label><br>
        <input type="text" name="nameFolder">
        <input class="create_btn" type="submit" value="Create folder"><br>
        <hr />
        <label for="uploadedFile">Upload your file.</label><br><br>
        <input type="file" name="uploadedFile">
        <input class="uploadBtn" type="submit" name="uploadedFileBtn" value="Upload"><br>
        <hr />
        <br>
        <table class="tableFM">
            <tr>
                <th>Favorites</th>
                <th>Download</th>
                <th>Delete</th>
                <th>File</th>
                <th>Add to favorites</th>
                <th>Size Byte</th>
                <th>Owner</th>
                <th>Date</th>
                <th>Inode</th>
            </tr>
            <?php processDir($curPath); ?>
        </table>
    </form>
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

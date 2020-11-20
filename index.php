<?php
require('config.php');
session_start();

if (!isset($_SESSION["sessionUsername"])) {
    header("Location:login.php");
    exit;
}

function processDir($path, $cookie_array)
{
    $dir_handle = opendir($path);
    while (false !== ($name = readdir($dir_handle))) {
        if ($name == '..') {
            $pathArr = explode('/', $path);
            $pathNoLast = array_slice($pathArr, 0, count($pathArr) - 1);
            echo '
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td>
        <a href="index.php?path=' . urlencode(implode('/', $pathNoLast)) . '">' . $name . '</a>
    </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>';
            continue;
        } elseif ($name == '.') {
            continue;
        }
        if (is_dir($path . '/' . $name)) {
            $nextPath = $path . '/' . $name;
            echo '
<tr>
    <td>
        <a href="index.php?downPath=' . urlencode
                ($nextPath) . '" class="btn btn-sm btn-success" onclick="return confirm(\'Are you sure you want to download this dir?\')">
        <i class="glyphicon glyphicon-save-file"></i> DOWNLOAD</a>
    </td>
    <td>
        <a href="index.php?delPath=' . urlencode($nextPath) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="glyphicon glyphicon-trash"></i> DELETE</a>
    </td>
    <td>
        <a href="index.php?path=' . urlencode($path) . '&renamePath=' . urlencode($nextPath) . '" class="btn btn-sm 
            btn-primary"><i class="glyphicon glyphicon-retweet"></i> RENAME</a>
    </td>
    <td>
        <a href="index.php?path=' . urlencode
                ($nextPath) . '">' . $name . '</a>
    </td>
    <td>
        <a href="index.php?addToFavor=' . urlencode($nextPath) . '">
            <img src="images/' . (in_array($nextPath, $cookie_array) ? 'star.jpg' : 'add_favourite.png') . '" class="imgFav"></a>
    </td>
    <td>Folder</td>
    <td></td>
    <td></td>
    <td></td>
</tr>';
        } elseif (is_file($path . '/' . $name)) {
            $filePath = $path . '/' . $name;
            $stat = stat($filePath);
            $size = $stat['size'];
            if ($size > 1024 * 1024 * 1024) {
                $size = round($size / (1024 * 1024 * 1024), 2) . ' Gb';
            } elseif ($size > 1024 * 1024) {
                $size = round($size / (1024 * 1024), 2) . ' Mb';
            } elseif ($size > 1024) {
                $size = round($size / (1024), 2) . ' Kb';
            } else {
                $size = $size / 1 . ' b';
            }
            echo '
<tr bgcolor="#efefef">
    <td>
        <a href="index.php?downPath=' . urlencode
                ($filePath) . '" class="btn btn-sm btn-success" onclick="return confirm(\'Are you sure you want to download this file?\')">
        <i class="glyphicon glyphicon-save-file"></i> DOWNLOAD</a>
     </td>
    <td>
        <a href="index.php?delPath=' . urlencode($filePath) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="glyphicon glyphicon-trash"></i> DELETE</a>
    </td>
    <td>
        <a href="index.php?path=' . urlencode($path) . '&renamePath=' . urlencode($filePath) . '" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-retweet"></i> RENAME</a>
    </td>
    <td>
        <a href="' . urlencode
                ($filePath) . '">' . $name . '</a>
    </td>
    <td>
        <a href="index.php?addToFavor=' . urlencode($filePath) . '">
        <img src="images/' . (in_array($filePath, $cookie_array) ? 'star.jpg' : 'add_favourite.png') . '" class="imgFav"></a>
        </td>
    <td>' . $size . '</td>
    <td>' . $stat['gid'] . '</td>
    <td>' . date('d.m.Y', $stat['mtime']) . '</td>
    <td>' . $stat['mode'] . '</td>
</tr>';
        }
    }
    closedir($dir_handle);
}

$curPath = isset($_GET['path']) ? urldecode($_GET['path']) : getcwd();
$delPath = isset($_GET['delPath']) ? urldecode($_GET['delPath']) : false;
$downPath = isset($_GET['downPath']) ? urldecode($_GET['downPath']) : false;
$addToFavor = isset($_GET['addToFavor']) ? urldecode($_GET['addToFavor']) : false;
$renamePath = isset($_GET['renamePath']) ? urldecode($_GET['renamePath']) : false;



if ($delPath) {
    if (is_dir($delPath)) {
        rmdir($delPath);
    } elseif (is_file($delPath)) {
        unlink($delPath);
    }
    $pathArr = explode('/', $delPath);
    $pathNoLast = array_slice($pathArr, 0, count($pathArr) - 1);
    header("Location: index.php?path=" . urlencode(implode('/', $pathNoLast)));
    exit;
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
    elseif (is_dir($downPath)) {
        exec("cd $downPath && zip -r $downPath.zip ./*");
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($downPath . '.' . 'zip') . '"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($downPath . '.' . 'zip'));
        readfile($downPath . '.' . 'zip');
        unlink($downPath . '.' . 'zip');
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
            <h4>Welcome ' . $_SESSION['sessionUsername'] . '! <a href="logout.php"><img src="images/signout.png" width="40" height="40"></a></h4>';

endif;

if ($renamePath) {
    $script = '$("#renameModal").modal("show");';
    if (isset($_POST['new_name'])) {
        $newName = trim($_POST['new_name']);
        $pathArrRen = explode('/', $renamePath);
        array_pop($pathArrRen);
        array_push($pathArrRen, $newName);
        $newArrRen = implode('/',$pathArrRen);
        $newRename = rename($renamePath, $newArrRen);
        $pathArr = explode('/', $renamePath);
        $pathNoLast = array_slice($pathArr, 0, count($pathArr) - 1);
        header("Location: index.php?path=" . urlencode(implode('/', $pathNoLast)));
        exit;
    }
}


$cookie_array = unserialize($_COOKIE['favorites']);

if ($addToFavor) {
    $removed = false;
    foreach ($cookie_array as $k => $value) {
        if ($value == $addToFavor) {
            unset($cookie_array[$k]);
            $removed = true;
        }
    }
    if (!$removed) {
        $cookie_array[] = $addToFavor;
    }
    setcookie('favorites', serialize($cookie_array), time() + 3600);
    $pathArr = explode('/', $addToFavor);
    $pathNoLast = array_slice($pathArr, 0, count($pathArr) - 1);
    header("Location: index.php?path=" . urlencode(implode('/', $pathNoLast)));
    exit;
}
$cookie_array = unserialize($_COOKIE['favorites']);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>File manager</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="style.css" media="screen" rel="stylesheet">
</head>
<body>
<div class="header">
    <div class="in_line">
    <h4><a href="http://filemanager.loc:8888"><img src="images/home.png" width="50" height="50"></a>File
        manager</h4>
        <?php echo $text2; ?>
    </div>
</div>
<div class="container mindex">
    <?php echo $text; ?>
    <form method="post" enctype="multipart/form-data">
        <h3><span class="label label-default">Please enter "Folder name"!</span></h3><br>
        <input type="text" name="nameFolder">
        <button class="btn btn-bg btn-primary" type="submit"><i class="glyphicon glyphicon-folder-close"></i> Create
            folder</button><br>
        <hr />
        <h3><span class="label label-default">Upload your file.</span></h3><br>
        <input type="file" name="uploadedFile"><br>
        <button class="btn btn-bg btn-primary" type="submit" name="uploadedFileBtn"><i class="glyphicon
        glyphicon-open"></i> Upload</button><br>
        <hr />
        <br>
        <table class="tableFM">
            <tr>
                <th>Download</th>
                <th>Delete</th>
                <th>Rename</th>
                <th>File</th>
                <th>Add to favorites</th>
                <th>Size Byte</th>
                <th>Owner</th>
                <th>Date</th>
                <th>Inode</th>
            </tr>
            <?php processDir($curPath, $cookie_array); ?>
        </table>
    </form>
</div>
<div class="modal fade bs-example-modal-sm" id="renameModal" tabindex="-1" role="dialog"
     aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rename file or folder</h5>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                    New name: <input type="text" name="new_name">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Rename</button>
                    </div>
                </form>
            </div>
        </div>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script type="text/javascript">
    <?= $script ?>
</script>

</body>
</html>
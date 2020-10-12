<?php
function processDir($path)
{
    $dir_handle = opendir($path);
    while (false !== ($name = readdir($dir_handle))) {
        if ($name == '..') {
            $pathArr = explode('/', $path);
            $pathNoLast = array_slice($pathArr, 0, count($pathArr) - 1);
            echo '<tr><td><a href="index.php?path=' . urlencode(implode('/', $pathNoLast)) . '">' . $name . '</a></td></tr>';
            continue;
        } elseif ($name == '.') {
            continue;
        }
        if (is_dir($path . '/' . $name)) {
            $nextPath = $path . '/' . $name;
            echo '<tr><td><a href="index.php?path=' . urlencode($nextPath) . '">' . $nextPath . '</a></td></tr>';
        } elseif (is_file($path . '/' . $name)) {
            $filePath = $path . '/' . $name;
            $stat = stat($filePath);
            echo '<tr><td><a href="' . urlencode($filePath) . '">' . $name . '</a> </td><td>' . $stat['size'] . '</td><td>' . $stat['gid'] . '</td><td>' .date('d.m.Y', $stat['mtime'])  . '</td><td>' . $stat['mode'] . '</td></tr>';
        }
    }
    closedir($dir_handle);
}
$curPath = isset($_GET['path']) ? urldecode($_GET['path']) : getcwd();
?>
<table border="3">

    <tr>
        <th>File</th>
        <th>Size Byte</th>
        <th>Owner</th>
        <th>Date</th>
        <th>Inode</th>
    </tr>

    <?php processDir($curPath); ?>

</table>

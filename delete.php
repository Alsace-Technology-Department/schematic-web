<?php
require "config/configuration.php";
$delete = Config::get('allow-delete');
if (!$delete) {
    die;
}
if (preg_match('/^\{?[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}\}?$/', $_SERVER['QUERY_STRING']) == 0) {
    header('Location: https://schematic.alsace.team/');
    exit();
}
$schematicFileType = pathinfo($_FILES["schematicFile"]["name"],PATHINFO_EXTENSION);

$target_dir = "uploads/";
// 两种文件都要删除
$target_file = $target_dir . basename($_SERVER['QUERY_STRING']) . ".schem";
$target_file0 = $target_dir . basename($_SERVER['QUERY_STRING']) . ".schematic";
if (file_exists($target_file)) {
    unlink($target_file);
    echo "File deleted!";
} else if(file_exists($target_file0)){
    unlink($target_file);
    echo "File deleted!";
} else {
    echo "File not found!";
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
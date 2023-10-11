<?php
require "config/configuration.php";
$ips = $schematic_settings = Config::get('required_ips');
if (count($ips) > 0 && !in_array($_SERVER['REMOTE_ADDR'], $ips)) {
    header('Location: https://schematic.alsace.team/');
    exit();
}
if (strlen($_SERVER['QUERY_STRING']) != 36 && strlen($_SERVER['QUERY_STRING']) != 32) {
  echo "Invalid request";
  exit;
}
if (preg_match('/^\{?[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}\}?$/', $_SERVER['QUERY_STRING']) == 0) {
    echo "Invalid request";
    exit;
}
if (sizeof($_FILES) == 0) {
  echo "文件还没有上传完成";
  exit;
}

$schematicFileType = pathinfo($_FILES["schematicFile"]["name"],PATHINFO_EXTENSION);
$target_dir = "uploads/"; #上传路径
#TODO: 上传到ns
$target_file = $target_dir . basename($_SERVER['QUERY_STRING']) . "." . $schematicFileType;
$uploadOk = 1; #1代表成功，0代表失败

if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["schematicFile"]["tmp_name"]); #判断文件类型
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "文件不是schem文件";
        $uploadOk = 0;
    }
}
// 检测文件是否存在
if (file_exists($target_file)) {
    echo "文件不存在";
    $uploadOk = 0;
}
// 检测文件大小
if ($_FILES["schematicFile"]["size"] > Config::get('size')) {
    echo "文件最大为" + Config::get('size')/1048576 + "MB";
    $uploadOk = 0;
}
// 限制文件类型
if($schematicFileType != "schematic") {
    echo "仅允许上传.schematic文件。";
    $uploadOk = 0;
}
// 检测uploadOK是否为1
if ($uploadOk == 0) {
    echo "请重新上传文件";
// 全部成功则上传
} else {
    if (move_uploaded_file($_FILES["schematicFile"]["tmp_name"], $target_file)) {
        header('Location: index.php?upload=' . $_SERVER['QUERY_STRING'] . "&type=" . $schematicFileType);
        echo "Success!";
    } else {
        var_dump($_FILES);
        echo $_FILES['schematicFile']['error'];
        echo "\n";
        echo $target_file;
        echo "\n";
        echo "FAILURE2";
    }
}
?>
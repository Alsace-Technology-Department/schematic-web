<?php
require "config/configuration.php";
parse_str($_SERVER['QUERY_STRING'], $arr);
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="twitter:title" content="schematic upload">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <meta name="keywords" content="schematic,schem,fawe,fawe download,worldedit,dovecity">
    <meta name="description" content="schematicWeb是自助上传下载schematic文件的一个系统">

    <title>schematicWeb</title>
    <link rel="stylesheet" href="assets/css/mdui.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>

<body>
    <div id="banner" mdui-headroom>
        <div class="mdui-toolbar">
            <span class="mdui-typo-title mdui-p-l-3"><a href="https://www.alsace.team" class="mdui-text-color-black-text">阿尔萨斯工业</a></span>
            <div class="mdui-toolbar-spacer"></div>
            <a href="https://alsaceteam.feishu.cn/wiki/Pm87wSa3oikct9kqdTNcJm0Pnke" target="_blank" class="mmdui-ripple mdui-ripple-black">服务器命令指南</a>
            <a href="https://alsaceteam.feishu.cn/wiki/F8AHwoD18iq45fk3ieSc6pcGnYy" target="_blank" class="mmdui-ripple mdui-ripple-black">工业园用户协议</a>
        </div>
    </div>

    <section class="mdui-container mdui-m-t-3">
        <div class="container-info">
            <div class="container-warpper">
                <!-- 这里会判断是服务器生成的链接还是玩家从浏览器打开的默认链接 -->
                <?php
                // 判断是否为mc服务器发送的请求
                if (isset($arr["key"])) {
                    $uuid = htmlspecialchars($arr["key"]);
                    $type = isset($arr["type"]) ? $arr["type"] : "schematic";
                    // 判断密钥是否合法
                    if ((strlen($uuid) == 36 || strlen($uuid) == 32) && preg_match('/^\{?[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}\}?$/', $uuid) != 0) {
                        $target_dir = "uploads/";  #存放schem的文件路径
                        $target_file = $target_dir . basename($uuid) . "." . $type;
                        // 检测文件是否存在
                        if (file_exists($target_file)) {
                            echo "<div class='mdui-clearfix'><div class='mdui-btn mdui-btn-raised mdui-float-left mdui-m-l-5'><a href='uploads/" . $uuid . "." . $type . "'>请点击此处下载您的schem文件</a></div>";
                            // 这里会判断是否开启“删除文件选项”
                            if (Config::get('allow-delete')) {
                                echo "<div class='mdui-btn mdui-btn-raised mdui-float-right mdui-m-r-5'><a href='delete.php?" . $uuid . "'>点击此处删除您导出的schem文件</a></div></div>";
                            }
                        } else {
                            echo "<div class='mdui-btn mdui-btn-raised mdui-float-right mdui-m-r-5'>文件已删除或不存在</div></div>";
                        }
                    } else {
                        echo "<h1>无效密钥</h1>";
                    }
                } else {
                    echo "<h1 class='header'>上传您的schematic文件至服务器</h1>";
                }
                ?>

                <div class="box">
                    <div id="main">
                        <div class="box-center">
                            <h2>如何下载您的schem文件</h2>
                            <table>
                                <tr>
                                    <td><b>进入服务器</b></td>
                                    <td>alsace.work(1.12.2),更高版本请使用<a href="https://schem.alsace.team">schematic cloud</a></td>
                                </tr>
                                <tr>
                                    <td><b>在游戏中copy您的选区</b></td>
                                    <td>//copy</td>
                                </tr>
                                <tr>
                                    <td><b>下载文件</b></td>
                                    <td>//download</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div id="main">
                        <div class="box-center">
                            <h2>如何上传您的schem文件</h2>
                            <table>
                                <tr>
                                    <td><b>点击右方的的上传按钮</b></td>
                                    <td><?php
                                        if (count(Config::get('required_ips')) == 0) {
                                            echo "<form id='myform' action='upload.php' method='post' enctype='multipart/form-data'><input type='file' accept='.schematic,.schem' name='schematicFile' onchange='upload()' id='FileInput'></form>";
                                        }
                                        ?></a></td>
                                </tr>
                                <tr>
                                    <td><b>在1.12综合服中输入</b></td>
                                    <td id="url"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
   <script src="assets/js/mdui.min.js"></script>
    <script>
        // 用户或服务器执行上传文件
        function upload() {
            var form = document.getElementById("myform");
            form.action = "upload.php?" + 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0,
                    v = c == 'x' ? r : r & 0x3 | 0x8;
                return v.toString(16);
            });
            form.submit();
        }

        search = window.location.search.substring(1);
        if (search.length > 0) {
            split = search.split("&");
            // var split3 = split[1].split("=");
            for (var i in split) {
                term = split[i];
                console.log(term);
                split2 = term.split("=");
                switch (split2[0]) {
                    case "key":
                        // 服务器上传
                        break;
                    case "ip":
                        document.getElementById("ip").innerHTML = split2[1];
                        break;
                    case "upload":
                        // document.getElementById("url").innerHTML = "//schematic load url:" + split2[1] + "." + split3[1]; 这个是带扩展名的,但是fawe似乎不想读取这个扩展名
                        document.getElementById("url").innerHTML = "//schematic load url:" + split2[1];
                        break;
                }
            }
        }
    </script>
</body>

</html>
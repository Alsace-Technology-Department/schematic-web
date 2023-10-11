<?php
class Config
{
    private static $info = array();
    public static function set($key, $value)
    {
        self::$info[$key] = $value;
    }
    public static function get($key)
    {
        return self::$info[$key];
    }
    public static function getAll()
    {
        return self::$info;
    }
}

$config = array();

########################################################################################################################
$config['required_ips']   = array();

$config['size']  = 5242880; # 限制上传文件大小

$config['allow-delete']  = true; # 是否允许删除上传的文件
########################################################################################################################

foreach ($config as $var => $val) {
    Config::set($var, $val);
}
unset($config);

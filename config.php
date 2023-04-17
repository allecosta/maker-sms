<?php

ob_start();
ini_set('date.timezone', 'America/Sao_Paulo');
date_default_timezone_set('America/Sao_Paulo');

session_start();

require_once 'initialize.php';
require_once 'controllers/DBConnection.php';
require_once 'controllers/SystemSettings.php';

$db = new DBConnection();
$conn = $db->conn;

function redirect($url = "") 
{
    if (!empty($url)) {
        echo '<script>location.href="'. BASE_URL . $url .'"</script>';
    }
}

function validateImage($file) 
{
    if (!empty($file)) {
        $ex = explode("?", $file);
        $file = $ex[0];
        $param = isset($ex[1]) ? "?" . $ex[1] : "";

        if (is_file(BASE_APP . $file)) {
            return BASE_URL . $file . $param;
        } else {
            return BASE_URL . 'dist/img/no-image-available.png';
        }
    } else {
        return BASE_URL . 'dist/img/no-image-available.png';
    }
}

function isMobileDevice() 
{
    $aMobileUA = [
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    ];

    foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
        if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }

        return false;
    }
}

ob_end_flush();

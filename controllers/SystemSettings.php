<?php

if (!class_exists('DBConnection')) {
    require_once '../config.php';
    require_once 'DBConnection.php';
}

class SystemSettings extends DBConnection 
{
    public function __construct()
    {
        parent::__construct();
    }

    function checkConnection() 
    {
        return ($this->conn);
    }

    function loadSystemInfo() 
    {
        $query = $this->conn->query("SELECT * FROM system_info");

        while ($row = $query->fetch_assoc()) {
            $_SESSION['system_info'][$row['meta_field']] = $row['meta_value'];
        }
    }

    function updateSystemInfo() 
    {
        $query = $this->conn->query("SELECT * FROM system_info");

        while ($row = $query->fetch_assoc()) {
            if (isset($_SESSION['system_info'][$row['meta_field']])) {
                unset($_SESSION['system_info'][$row['meta_field']]);
            }

            $_SESSION['system_info'][$row['meta_field']] = $row['meta_value'];
        }

        return true;
    }

    function updateSettingsInfo() 
    {
        $data = "";

        foreach ($_POST as $key => $value) {
            if (!in_array($key, ['content'])) {
                if (isset($_SESSION['system_info'][$key])) {
                    $value = str_replace("'", "&apos;", $value);
                    $query = $this->conn->query("
                        UPDATE 
                            system_info SET meta_value = '{$value}' 
                        WHERE 
                            meta_field = '{$key}' ");
                } else {
                    $query = $this->conn->query("
                        INSERT INTO 
                            system_info 
                        SET 
                            meta_value = '{$value}', meta_field = '{$key}' ");
                }
            }
        }

        if (isset($_POST['content'])) {
            foreach ($_POST['content'] as $key => $value) {
                file_put_contents("../{$key}.html", $value);
            }
        }

        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != "") {
            $fileName = 'uploads/logo-' . (time()) . '.png';
            $dirPath = BASE_APP . $fileName;
            $upload = $_FILES['img']['tmp_name'];
            $type = mime_content_type($upload);
            $allowed = ['image/png', 'image/jpeg'];

            if (!in_array($type, $allowed)) {
                $resp['msg'] .= " Mas a imagem não foi carregada devido a um tipo de arquivo inválido.";
            } else {
                $newHeight = 200;
                $newWidth = 200;

                list($width, $height) = getimagesize($upload);

                $tImage = imagecreatetruecolor($newWidth, $newHeight);

                imagealphablending($tImage, false);
                imagesavealpha($tImage, true);

                $gdImage = ($type == 'image/png') ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);

                imagecopyresampled($tImage, $gdImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                if ($gdImage) {
                    if (is_file($dirPath)) {unlink($dirPath);}

                    $uploadIMG = imagepng($tImage, $dirPath);

                    imagedestroy($gdImage);
                    imagedestroy($tImage);

                } else {
                    $resp['msg'] .= " Mas a imagem falhou ao carregar devido a um motivo desconhecido.";
                }
            }

            if (isset($uploadIMG) && $uploadIMG == true) {
                if (isset($_SESSION['system_info']['logo'])) {
                    $query = $this->conn->query("
                        UPDATE 
                            system_info 
                        SET 
                            meta_value = '{$fileName}' 
                        WHERE 
                            meta_field = 'logo' ");

                    if (is_file(BASE_APP . $_SESSION['system_info']['logo'])) {
                        unlink((BASE_APP . $_SESSION['system_info'] ['logo']));
                    }
                } else {
                    $query = $this->conn->query("
                        INSERT INTO 
                            system_info 
                        SET 
                            meta_value = '{$fileName}', meta_field = 'logo' ");
                }

                unset($uploadIMG);
            }
        }

        if (isset($_FILES['cover']) && $_FILES['cover']['tmp_name'] != "") {
            $fileName = 'uploads/cover-' . time() . '.png';
            $dirPath = BASE_APP . $fileName;
            $upload = $_FILES['cover']['tmp_name'];
            $type = mime_content_type($upload);
            $allowed = ['image/png', 'image/jpeg'];

            if (!in_array($type, $allowed)) {
                $resp['msg'] .= " Mas a imagem não foi carregada devido a um tipo de arquivo inválido.";
            } else {
                $newHeight = 720;
                $newWidth = 1280;

                list($width, $height) = getimagesize($upload);

                $tImage = imagecreatetruecolor($newWidth, $newHeight);
                $gdImage = ($type == 'image/png') ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);

                imagecopyresampled($tImage, $gdImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                if ($gdImage) {
                    if (is_file($dirPath)) {unlink($dirPath);}

                    $uploadedIMG = imagepng($tImage, $dirPath);

                    imagedestroy($gdImage);
                    imagedestroy($tImage);
                } else {
                    $resp['msg'] .= " Mas a imagem falhou ao carregar devido a um motivo desconhecido.";
                }
            }

            if (isset($uploadedIMG) && $uploadedIMG == true) {
                if (isset($_SESSION['system_info']['cover'])) {
                    $query = $this->conn->query("
                        UPDATE 
                            system_info 
                        SET 
                            meta_value = '{$fileName}' 
                        WHERE 
                            meta_field = 'cover' ");

                    if (is_file(BASE_APP . $_SESSION['system_info']['cover'])) {
                        unlink(BASE_APP . $_SESSION['system_info']['cover']);
                    } else {
                        $query = $this->conn->query("
                            INSERT INTO 
                                system_info 
                            SET 
                                meta_value = '{$fileName}', meta_field = 'cover' ");
                    }

                    unset($uploadedIMG);
                }
            }
        }

        $update = $this->updateSystemInfo();
        $flash = $this->setFlashData("success", "Informações do sistema atualizadas com sucesso!");

        if ($update && $flash) {
            return true;
        }
    }

    function setFlashData($flash = "", $value = "") 
    {
        if (!empty($field) && !empty($value)) {
            $_SESSION['flashdata'][$flash] = $value;

            return true;
        }
    }

    function setUserData($field = "", $value = "") 
    {
        if (!empty($field) && !empty($value)) {
            $_SESSION['userdata'][$field] = $value;
        }
    }

    function userData($field = "") 
    {
        if (!empty($field)) {
            if (isset($_SESSION['userdata'][$field])) {
                return $_SESSION['userdata'][$field];
            } else {
                return null;
            }
        } else {
            return false;
        }
    }

    function chkFlashData($flash = "") 
    {
        if (isset($_SESSION['flashdata'][$flash])) {
            return true;
        } else {
            return false;
        }
    }

    function flashData($flash = "") 
    {
        if (!empty($flash)) {
            $tmp = $_SESSION['flashdata'][$flash];

            unset($_SESSION['flashdata']);

            return $tmp;
        } else {
            return false;
        }
    }

    function sessDes() 
    {
        if (isset($_SESSION['userdata'])) {
            unset($_SESSION['userdata']);

            return true;   
        }

        return true;
    }

    function info($field = "") 
    {
        if (!empty($field)) {
            if (isset($_SESSION['system_info'][$field])) {
                return $_SESSION['system_info'][$field];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

$_settings = new SystemSettings();
$_settings->loadSystemInfo();
$action = !isset($_GET['f']) ? "none" : strtolower($_GET['f']);
$sysset = new SystemSettings();

switch ($action) {
    case 'updateSettings':
        echo $sysset->updateSettingsInfo();
        break;
    default:
        break;
}
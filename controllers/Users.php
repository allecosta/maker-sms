<?php 

require_once '../config.php';

class Users extends DBConnection 
{
    private $settings;

    public function __construct()
    {
        global $_settings;

        $this->settings = $_settings;

        parent::__construct();
    }

    public function saveUsers() 
    {
        extract($_POST);

        $oID = $id;
        $data = "";

        if (isset($oldPassword)) {
            if (md5($oldPassword) != $this->settings->userData('password')) {
                return 4;
            }
        }

        $chk = $this->conn->query("
            SELECT 
                * 
            FROM 
                users 
            WHERE 
                username = '{$username}' ". ($id > 0 ? " AND id != '{$id}' " : ""))->num_rows;
        
        if ($chk > 0) {
            return 3;
            exit;
        }

        foreach ($_POST as $key => $value) {
            if (in_array($key, ['firstname', 'middlename', 'lastname', 'username', 'type'])) {
                if (!empty($data)) {$data .= ", ";}

                $data .= " {$key} = '{$value}' ";
            }
        }

        if (!empty($password)) {
            $password = md5($password);

            if (!empty($data)) {$data .= " , ";}

            $data .= " `password` = '{$password}' ";
        }

        if (empty($id)) {
            $query = $this->conn->query("INSERT INTO users SET {$data}");

            if ($query) {
                $id = $this->conn->insert_id;
                $this->settings->setFlashData("success", "Detalhes do usuário salvo com sucesso!");
                $resp['status'] = 1;
            } else {
                $resp['status'] = 2;
            }
        } else {
            $query = $this->conn->query("UPDATE users SET $data WHERE id = {$id}");

            if ($query) {
                $this->settings->setFlashData("success", "Detalhes do usuário atualizado com sucesso!");

                if ($id == $this->settings->userData("id")) {
                    foreach ($_POST as $key => $value) {
                        if ($key != "id") {
                            if (empty($data)) {$data .= " , ";}

                            $this->settings->setUserdata($key, $value);
                        }
                    }
                }

                $resp['status'] = 1;

            } else {
                $resp['status'] = 2;
            }
        }

        if ($resp['status'] == 1) {
            $data = "";

            foreach ($_POST as $key => $value) {
                if (!in_array($key, ['id', 'firstname', 'middlename', 'lastname', 'username', 'password', 'type', 'oldpassword'])) {
                    if (!empty($data)) {$data .= ", ";}

                    $value = $this->conn->real_escape_string($value);
                    $data .= " ('{$id}', '{$key}', '{$value}')";
                }
            }

            if (!empty($data)) {
                $this->conn->query("DELETE * FROM user_meta WHERE user_id = '{$id}' ");
                $save = $this->conn->query("INSERT INTO user_meta (user_id, meta_field, meta_value) VALUES {$data}");


                if (!$save) {
                    $resp['status'] = 2;

                    if (empty($oID)) {
                        $this->conn->query("DELETE * FROM users WHERE id = '{$id}' ");
                    }
                }
            }
        }

        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != "") {
            $fileName = "uploads/avatar-" . $id . " .png";
            $dirPath = BASE_APP . $fileName;
            $upload = $_FILES['img']['tmp_name'];
            $type = mime_content_type($upload);
            $allowed = ['image/png', 'image/jpeg'];

            if (!in_array($type, $allowed)) {
                $resp['msg'] .= " Mas a imagem não foi carregada devido a um tipo de arquivo inválido.";
            } else {
                $new_height = 200;
                $new_width = 200;

                list($width, $height) = getimagesize($upload);

                $tImage = imagecreatetruecolor($new_width, $new_height);

                imagealphablending($tImage, false);
                imagesavealpha($tImage, true);

                $gdIMG = ($type == "image/png") ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);

                imagecopyresampled($tImage, $gdIMG, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                if ($gdIMG) {
                    if (is_file($dirPath)) {unlink($dirPath);}

                    $uploadedIMG = imagepng($tImage, $dirPath);

                    imagedestroy($gdIMG);
                    imagedestroy($tImage);
                } else {
                    $resp['msg'] .= " Mas a imagem falhou ao carregar devido a um motivo desconhecido.";
                }
            }

            if (isset($uploadedIMG)) {
                $this->conn->query("
                    UPDATE SET 
                        avatar = CONCAT('{$fileName}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) 
                    WHERE
                        id = '{$id}' ");

                if ($id == $this->settings->userData("id")) {
                    $this->settings->setUserData("avatar", $fileName);
                }
            }
        }

        if (isset($resp['msg'])) {$this->settings->setFlashData("success", $resp['msg']);}

        return $resp['status'];
    }

    public function deleteUsers() 
    {
        extract($_POST);

        $avatar = $this->conn->query("SELECT avatar FROM users WHERE is = '{$id}'")->fetch_array()['avatar'];
        $query = $this->conn->query("DELETE FROM users WHERE id = $id");

        if ($query) {
            $avatar = explode("?", $avatar)[0];
            $this->settings->setFlashData("success", "Detalhes do usuário excluido com sucesso!");

            if (is_file(BASE_APP . $avatar)) {unlink(BASE_APP . $avatar);}

            $resp['status'] = "success";
        } else {
            $resp['status'] = "failed";
        }

        return json_encode($resp);
    }

    public function savesUsers() 
    {
        extract($_POST);

        $data = "";

        foreach ($_POST as $key => $value) {
            if (!in_array($key, ['id', 'password'])) {
                if (!empty($data)) {$data .= ", ";}

                $data .= " `{$key}` = '{$value}' ";
            }
        }

        if (!empty($password)) {$data .= ", `password` = '". md5($password) . "' ";}

        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != "") {
            $fileName = "uploads/" . strtotime(date('y-m-d H:i')) . "_" . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], "../" . $fileName);

            if ($move) {
                $data .= " , avatar = '{$fileName}' ";

                if (isset($_SESSION['userdata']['avatar']) && is_file("../" . $_SESSION['userdata']['avatar'])) {
                    unlink("../" . $_SESSION['userdata']['avatar']);
                }
            }
        }

        $save = $this->conn->query("UPDATE students SET {$data} WHERE id = $id");

        if ($save) {
            $this->settings->setFlashData("success", "Detalhes do usuário atualizado com sucesso;");

            foreach ($_POST as $key => $value) {
                if (!in_array($key, ["id", "password"])) {
                    if (!empty($data)) {$data .= " , ";}

                    $this->settings->setUserData($key, $value);
                }
            }

            if (isset($fileName) && isset($move)) {$this->settings->setUserData("avatar", $fileName);}

            return 1;
        } else {
            $resp['error'] = $sql;

            return json_encode($resp);
        }
    }
}

$users = new Users();
$action = !isset($_GET['f']) ? "none" : strtolower($_GET['f']);

switch ($action) {
    case "save":
        echo $users->saveUsers();
        break;
    // case "fsave":
    //     echo $users->saveFusers();
    //     break;
    case "ssave":
        echo $users->savesUsers();
        break;
    case "delete":
        echo $users->deleteUsers();
        break;
    default:
        break;
}
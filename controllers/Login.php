<?php

require_once '../config.php';

class Login extends DBConnection 
{
    private $settings;

    public function __construct()
    {
        global $_settings;
        $this-> settings = $_settings;

        parent::__construct();
        ini_set('display_error', 1);
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index() 
    {
        echo "<h1>Acesso Negado</h1> <a href=". BASE_URL .">Retorne.</a>";
    }

    public function login() 
    {
        extract($_POST);

        $query = $this->conn->query("
            SELECT 
                * 
            FROM 
                users 
            WHERE 
                username = '$username' AND password = password_hash('$password')"
            );

        if ($query->num_rows > 0) {
            foreach ($query->fetch_array() as $key => $value) {
                if (!is_numeric($key) && $key != 'password') {
                    $this->settings->setUserdata($key, $value);
                }
            }
            
            $this->settings->setUserData("login_type", 1);

            return json_encode(['status' => 'success']);
            
        } else {
            return json_encode([
                'status' => 'incorrect', 
                'last_query' => "SELECT * FROM users WHERE username = '$username' AND password = password_hash('$password')"
            ]);
        } 
    }

    public function logout() 
    {
        if ($this->settings->sessDes()) {
            redirect('admin/login.php');
        }
    }

    public function loginUser() 
    {
        extract($_POST);

        $query = $this->conn->query("
            SELECT 
                * 
            FROM
                users 
            WHERE
                username = '$username' AND password = password_hash('$password') AND type = 2"
            );
        
        if ($query->num_rows > 0) {
            foreach ($query->fetch_array() as $key => $value) {
                $this->settings->setUserData($key, $value);
            }

            $this->settings->setUserData('login_type', 2);
            $resp['status'] = "success";
        } else {
            $resp['status'] = "incorrect";
        }

        if ($this->conn->error) {
            $resp['status'] = "failed";
            $resp['_error'] = $this->conn->error;
        }

        return json_encode($resp);

    }

    public function logoutUser() 
    {
        if ($this->settings->sessDes()) {
            redirect('./');
        }
    }
}

$action = !isset($_GET['f']) ? "none" : strtolower($_GET['f']);
$auth = new Login();

switch ($action) {
    case 'login':
        echo $auth->login();
        break;
    case 'loginUser':
        echo $auth->loginUser();
        break;
    case 'logout':
        echo $auth->logout();
        break;
    case 'logoutUser':
        echo $auth->logoutUser();
        break;
    default:
        echo $auth->index();
        break;
}
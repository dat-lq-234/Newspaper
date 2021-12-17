<?php
session_start();
class app_libs_UserIdentity{
    public $username;
    public $password;

    protected $id;

    public function __construct($username = "", $password="")
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function encryptPassword(){
        return md5($this->password);
    }

    public function login(){
        $db = new app_model_user();
        $query = $db->buildQueryParam([
            "where" => "username =:username AND password=:password",
            "params" =>[
                ":username" =>trim($this->username),
                ":password" =>trim($this->password)
              //  ":password" =>$this->encryptPassword()
            ]
        ]
        )->selectOne();
        if ($query){
            $_SESSION["userId"] = $query["id"];
            $_SESSION["username"] = $query["username"];
            return true;
        }else{
            return false;
        }
    }

    public function logout(){
        unset($_SESSION["userId"]);
        unset($_SESSION["username"]);
    }

    public function getSession($name){
        if ($name!== NULL){
            return isset($_SESSION[$name])? $_SESSION[$name]: NULL;
        }
        return $_SESSION;
    }

    public function isLogIn(){
        if ($this->getSession("userId")){
            return true;
        }
        else{
            return false;
        }
    }
    public function getId(){
        return $this->getSession("userId");
    }

    public function register($username, $password){
        $db = new app_model_user();
        $query = $db->buildQueryParam([
            "where" => "username =:username",
            "params" =>[
                ":username" => trim($this->username),
            ]
            ])->selectOne();
        if(!$query){
            $qAddUser = $db->registerAccount($username, $password);
            // $qAddUser = $db->buildQueryParam([
            // "field" => "(username, password)",
            // "value" => [$this->username, $this->password]
            // //"value" => [$this->username, md5($this->password)]
            // ])->insert();
            if($qAddUser){
                $_SESSION["username"] = $this->username;
                $_SESSION["userId"] = $qAddUser;

            }else{
                echo "err qAdd";
            }
            
            return true;
        }else{
            return false;
        }
    }
    
}
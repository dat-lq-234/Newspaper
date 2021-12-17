<?php
class app_libs_Router{
    const PARAM_NAME = "r";
    const HOME_PAGE = "home";
    const INDEX_PAGE = "index";
    

    public static $sourcePath;

    public function __construct($sourcePath = "")
    {
        if ($sourcePath){
            self::$sourcePath = $sourcePath;
        }
    }

    public function getGet($name = NULL){
        if ($name!== NULL){
            return isset($_GET[$name])? $_GET[$name]: NULL;
        }
        return $_GET;
    }
    public function getPOST($name = NULL){
        if ($name!== NULL){
            return isset($_POST[$name])? $_POST[$name]: NULL;
        }
        return $_POST;
    }

    public function router(){
        $url = $this->getGet(self::PARAM_NAME);
       
        if (!$url|| !is_string($url) || $url == self::INDEX_PAGE){
            $url = self::HOME_PAGE;
        }
        $path = self::$sourcePath."/".$url.".php";
        
        if (file_exists($path)){
            return require_once $path;
        }else {
            return $this->pageNotFound();
        }
    }

    public function pageNotFound(){
        echo "Page not found";
    }

    public function createUrl($url, $param=[]){
        if($url){
            $param[self::PARAM_NAME] = $url;
            return $_SERVER['PHP_SELF'].'?'.http_build_query($param);
        }
    }

    public function redirect($url){
        $u = $this->createUrl($url);
        header("Location:$u");
    }
    public function homePage(){
        $this->redirect(self::HOME_PAGE);
    }
    public function logInPage(){
        $this->redirect("logIn");
    }

}


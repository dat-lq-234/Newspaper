<?php
$router = new app_libs_Router();

$account = trim($router->getPOST("account"));
$password = trim($router->getPOST("password"));

$identity = new app_libs_UserIdentity();
if($identity->isLogIn()){
    $router->homePage();
}
if($router->getPOST("submit") && $account && $password){
    $identity->username = $account;
    $identity->password = $password;
    if ($identity->login()){
       // $router->homePage();
       echo "Login success";
    }else{
        echo "User is incorrect";
    }
}else{
    echo "wrong submit";
}
?>
<html>

    <body>
       <div>Log In</div>
                <form action="<?php echo $router->createUrl('logIn')?>" method="post">
                    <h1>Đăng nhập vào website</h1>
                    <div class="input-box">
                        <i ></i>
                        <input type="text" placeholder="Nhập username" name ="account">
                    </div>
                    <div class="input-box">
                        <i ></i>
                        <input type="password" placeholder="Nhập mật khẩu" name = "password">
                    </div>
                    <div class="btn-box">
                        <input type="submit" name = "submit" value="Login">
                    </div>
                </form>
    
    </body>
</html>
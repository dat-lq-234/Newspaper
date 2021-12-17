<?php
$router = new app_libs_Router();
$username = trim($router->getPOST("username"));
$password = (trim($router->getPOST("password")));

$u = new app_libs_UserIdentity();

if ($router->getPOST("submit")){

    if($username && $password){
        $u->username = $username;
        $u->password = $password;
        if($u->register($username, $password)){
            echo $_SESSION["userId"];
            echo "<br> Register success!";
        }else{
            echo "User Invalid";
            }
    }else{
    echo "err";
    }
} 
?>


<html>
<body>
    <form action="<?php echo $router->createUrl('register')?>" method="post">
                    <h1>REGISTER</h1>
                    <div class="input-box">
                        <i ></i>
                        <input type="text" placeholder="Nhập username" name ="username">
                    </div>
                    <div class="input-box">
                        <i ></i>
                        <input type="password" placeholder="Nhập mật khẩu" name = "password">
                    </div>
                    <div class="btn-box">
                        <input type="submit" name = "submit" value="Register">
                    </div>
                </form>

</body>

</html>
<?php 
header('content-type:text/html;charset=utf8');

session_start();//开启session
//(1)登录验证
if(isset($_SESSION['user'])&&!empty($_SESSION['user'])){
    //检测scssion存在且不为空
    header('location:index.php');
    exit;   
}

//用户登录,一个简单的用户查询
//对表单进行提交处理,接收数据
if(!empty($_POST['username'])){

    include_once './lib/fun.php';//将数据库的信息加载过来

//POST获取用户输入的值,表单验证
    $username=trim($_POST['username']);//为用户体验更加好,验证码允许出现空格trim()
    $password=trim($_POST['password']);

//对表单进行进一步验证
    if(!$username){
        //echo '用户名不能为空';exit;
        msg(2,'用户名不能为空');
    }
    if(!$password){
        //echo '密码不能为空';exit;
        msg(2,'密码不能为空');
    }
//数据库连接
    $con=mysqlInit('localhost','root','123456','liangkaiyu_mall');
    if(!$con){
        echo '数据库连接失败!';exit;
    }else{
        //echo '数据库连接成功!';
    }
//根据用户查询数据(一行一行查询)
    $sql="select * from liangkaiyu_user where username='$username' limit 1";
    //echo $sql;die;
    $query=mysqli_query($con,$sql);
    $result=mysqli_fetch_assoc($query);
    //从结果集中取出数据(以关联数组形式获取和显示数据)
    //print_r($result);die;
    
    if(is_array($result)&&!empty($result)){
        //var_dump($result);die;
        if(createPassword($password)===$result['password']){

            //echo '登录成功';exit;
            //登录的用户信息要写进session里面,就是说要进行会话处理
            $_SESSION['user']=$result;
            //var_dump($_SESSION['user']);die;
            //var_dump($_SESSION);die;
            //var_dump($result);die;//将结果集里面的关联数组写进session里面
            header('location:index.php');
            exit;


        }else{
            //header('location:msg.php?type=2&msg=密码不正确url=login.php');
            //echo '密码错误,请确认后在输入';exit;
            msg(2,'密码错误,请确认后在输入');
        }
    }else{
        //echo '用户不存在,请重新输入';die;
        msg(2,'用户不存在,请重新输入');
    }
    
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|用户登录</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
    <link rel="stylesheet" type="text/css" href="./static/css/login.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="center-login">
            <div class="login-banner">
                <a href="#"><img src="./static/image/login_banner.png" alt=""></a>
            </div>
            <div class="user-login">
                <div class="user-box">
                    <div class="user-title">
                        <p>用户登录</p>
                    </div>
                    <form class="login-table" name="login" id="login-form" action="login.php" method="post">
                        <div class="login-left">
                            <label class="username">用户名</label>
                            <input type="text" class="yhmiput" name="username" placeholder="Username" id="username">
                        </div>
                        <div class="login-right">
                            <label class="passwd">密码</label>
                            <input type="password" class="yhmiput" name="password" placeholder="Password" id="password">
                        </div>
                        <div class="login-btn">
                            <button type="submit">登录</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span> ©2017 POWERED BY IMOOC.INC</p>
</div>

</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script>
    $(function () {
        $('#login-form').submit(function () {
            var username = $('#username').val(),
                password = $('#password').val();
            if (username == '' || username.length <= 0) {
                layer.tips('用户名不能为空', '#username', {time: 2000, tips: 2});
                $('#username').focus();
                return false;
            }

            if (password == '' || password.length <= 0) {
                layer.tips('密码不能为空', '#password', {time: 2000, tips: 2});
                $('#password').focus();
                return false;
            }


            return true;
        })

    })
</script>

</script>
</html>
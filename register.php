<?php 
header('content-type:text/html;charset=utf8');
//对表单进行提交处理,接收数据
if(!empty($_POST['username'])){

    include_once './lib/fun.php';//将数据库的信息加载过来

    $username=trim($_POST['username']);//为用户体验更加好,验证码允许出现空格trim()
    $password=trim($_POST['password']);
    $repassword=trim($_POST['repassword']);

//对表单进行进一步验证
    if(!$username){
        //echo '用户名不能为空';exit;
        msg(2,'用户名不能为空');
    }
    if(!$password){
        //echo '密码不能为空';exit;
        msg(2,'密码不能为空');
    }
    if(!$repassword){
        //echo '确认密码不能为空';exit;
        msg(2,'确认密码不能为空');
    }
    if($password!==$repassword){
        //echo '两次密码输入不一致,请重新输入';exit;
        msg(2,'两次密码输入不一致,请重新输入');
    }

//数据库连接
    $con=mysqlInit('localhost','root','123456','liangkaiyu_mall');
    if(!$con){
        echo '数据库连接失败!';exit;
    }else{
        //echo '数据库连接成功!';
    }


//数据库查询
//插入表单用户数据前,先查询用户是否在数据库存在(id>=1用户存在,count)
    //SELECT count('id') FROM `liangkaiyu_user` WHERE 'username'='liang'
    $sql="SELECT COUNT(`id`) AS total FROM `liangkaiyu_user` WHERE `username`='{$username}'";
    //发指令,取数据
    //echo $sql;die;
    $query=mysqli_query($con,$sql);
    $result=mysqli_fetch_assoc($query);
    //var_dump($result);die;
    //验证用户名是否存在
    if(isset($result['total'])&&$result['total']>0){
        echo '晚来一步,你的用户名被抢注了!';die;
    }


//密码进行加密处理md5()
    $password=createPassword($password);

unset($sql,$query);//释放变量
//数据插入数据库(sql语句要特别注意)
    $sql="INSERT `liangkaiyu_user`(`username`,`password`,`create_time`) VALUES('{$username}','{$password}','{$_SERVER['REQUEST_TIME']}')";
    //echo $sql;
    $query=mysqli_query($con,$sql);
    //print_r(mysqli_fetch_array($query));
    if($query){
        //echo "恭喜你,注册成功！欢迎你成为大家庭的一员";exit;
        msg(1,'恭喜你,注册成功！欢迎你成为大家庭的一员','login.php');
    }else{
        //echo mysqli_error($con);exit;
        msg(2,mysqli_error($con));
    }
}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|用户注册</title>
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
                        <p>用户注册</p>
                    </div>
                    <form class="login-table" name="register" id="register-form" action="register.php" method="post">
                        <div class="login-left">
                            <label class="username">用户名</label>
                            <input type="text" class="yhmiput" name="username" placeholder="Username" id="username">
                        </div>
                        <div class="login-right">
                            <label class="passwd">密码</label>
                            <input type="password" class="yhmiput" name="password" placeholder="Password" id="password">
                        </div>
                        <div class="login-right">
                            <label class="passwd">确认</label>
                            <input type="password" class="yhmiput" name="repassword" placeholder="Repassword"
                                   id="repassword">
                        </div>
                        <div class="login-btn">
                            <button type="submit">注册</button>
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

<!--创建js对表单进行简单的验证,使用jquery获取input框的value值,在进行验证-->
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script>
    $(function () {
        $('#register-form').submit(function () {
            var username = $('#username').val(),
                password = $('#password').val(),
                repassword = $('#repassword').val();
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

            if (repassword == '' || repassword.length <= 0 || (password != repassword)) {
                layer.tips('两次密码输入不一致', '#repassword', {time: 2000, tips: 2});
                $('#repassword').focus();
                return false;
            }

            return true;
        })

    })
</script>

</html>



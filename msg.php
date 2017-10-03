<?php
//提供更好的用户体验

//URL type参数处理(容错判断:type不等于1时,强制等于1) 1操作成功 2操作失败
//http://localhost/mall/msg.php?type=1
$type=isset($_GET['type'])&&in_array(intval($_GET['type']),array(1,2))?intval($_GET['type']):1;

$title=$type==1?'操作成功':'操作失败';

//获取msg
//http://localhost/mall/msg.php?type=2&msg=登录成功
$msg=isset($_GET['msg'])?trim($_GET['msg']):'操作成功';

//获取url
//http://localhost/mall/msg.php?type=2&&msg=密码不正确url=login.php
$url=isset($_GET['url'])?trim($_GET['url']):'';
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/done.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="image_center">

        <!--为了提供php与html交互,php提供了短标签-->
            <?php if($type==1):?>
                <span class="smile_face">:)</span>
            <?php else: ?>           
                <span class="smile_face">:(</span>
            <?php endif; ?>
        </div>
        <div class="code">
            <!--操作成功-->
            <?php echo $msg ?>
        </div>
        <div class="jump">
            页面在 <strong id="time" style="color: #009f95">3</strong> 秒 后跳转
        </div>
    </div>

</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</body>

<!--js读秒时间的处理-->
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        var time = 3;

        var url="<?php echo $url ?>"||null;//js读取PHP变量,反之默认为null

        setInterval(function () {
            if (time > 1) {
                time--;
                console.log(time);
                $('#time').html(time);
            }
            else {
                $('#time').html(0);
                if(url){
                    location.href=url;
                }else{
                    history.go(-1);
                }           
            }
        }, 1000);

    })
</script>
</html>

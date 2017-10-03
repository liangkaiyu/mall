<?php 
header('content-type:text/html;charset=utf8');
/*
session_start();
if(!isset($_SESSION['user'])||empty($_SESSION['user'])){
    //不存在或者为空
    header('location:login.php');
    exit;   
}
echo '商品中心';
*/
//(1)登录验证处理
include_once './lib/fun.php';
if($login=checkLogin()){
    $user=$_SESSION['user'];
}

//(2)查询商品(分页的思想,传入url值)$_GET()
//检查page参数(max()page参数至少大于1)
//http://localhost/mall/index.php?page=1
$page=isset($_GET['page'])?intval($_GET['page']):1;
$page=max($page,1);
$pageSize=2;//每页显示条数,也叫每次查询条数

//page=1 limit 0,2(从0开始的两条)
//page=2 limit 2,2
//page=3 limit 4,2

$offset=($page-1)*$pageSize;//$offset偏移量
$con=mysqlInit('localhost','root','123456','liangkaiyu_mall');


//分页必须从数据库查到$total
$sql="select count(`id`) as total from `liangkaiyu_goods`";
$query=mysqli_query($con,$sql);
$result=mysqli_fetch_assoc($query);
$total=isset($result['total'])?$result['total']:0;
unset($sql,$query,$result);//释放变量

//只查询需要的字段,防止空间浪费
$sql="select * from `liangkaiyu_goods` order by `id` asc,`view` desc limit {$offset},{$pageSize} ";
//echo $sql;die;
$query=mysqli_query($con,$sql);


$goods=array();//循环出商品信息
while($result=mysqli_fetch_assoc($query)){
    $goods[]=$result;
}
//var_dump($goods);die;

//(3)分页处理(拿到page拿到total)
//echo pages(20,$page,$pageSize,6);
//echo getUrl();
//echo pageUrl($page+1);
$pages=pages($total,$page,$pageSize,6);
//echo $pages;
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|首页</title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/index.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
        <!--使用短标签-->
            <?php if($login): ?>
                <li><span>管理员:<?php echo $user['username'] ?></span></li>
                <li><a href="publish.php">发布</a></li>
                <li><a href="login_out.php">退出</a></li>
            <?php else: ?>
                <li><a href="login.php">登录</a></li>
                <li><a href="register.php">注册</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="content">
    <div class="banner">
        <img class="banner-img" src="./static/image/welcome.png" width="732px" height="372" alt="图片描述">
    </div>
    <div class="img-content">
        <ul>
        <!--短标签嵌入php,变量代替原有单个html标签-->
        <?php foreach ($goods as $v):?>
            <li>
                <img class="img-li-fix" src="<?php echo $v['pic'] ?>" alt="<?php echo $v['name'] ?>">
                <div class="info">
                <!--跳转详情页面detail.php-->
                    <a href="detail.php?id=<?php echo $v['id'] ?>"><h3 class="img_title"><?php echo $v['name'] ?></h3></a>
                    <p>
                       <?php echo $v['des'] ?>
                    </p>
                    <div class="btn">
                        <a href="edit.php?id=<?php echo $v['id'] ?>" class="edit">编辑</a>
                        <a href="delete.php?id=<?php echo $v['id'] ?>" class="del">删除</a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
<!--
            <li>
                <img class="img-li-fix" src="./static/image/wumingnvlang.jpg" alt="">
                <div class="info">
                    <a href=""><h3 class="img_title">无名女郎</h3></a>
                    <p>
                       图片描述可以分为多种，一种是单一说明，就比如直接的告诉读者这篇文章要介绍什么样子的内容，一些配图可以分为含蓄类型的，这样的配图一般会 图片描述可以分为多种。 
                    </p>
                    <div class="btn">
                        <a href="#" class="edit">编辑</a>
                        <a href="#" class="del">删除</a>
                    </div>
                </div>
            </li>
            <li>
                <img class="img-li-fix" src="./static/image/wumingnvlang.jpg" alt="">
                <div class="info">
                    <a href=""><h3 class="img_title">无名女郎</h3></a>
                    <p>
                       图片描述可以分为多种，一种是单一说明，就比如直接的告诉读者这篇文章要介绍什么样子的内容，一些配图可以分为含蓄类型的，这样的配图一般会 图片描述可以分为多种。 
                    </p>
                    <div class="btn">
                        <a href="#" class="edit">编辑</a>
                        <a href="#" class="del">删除</a>
                    </div>
                </div>
            </li>
            <li>
                <img class="img-li-fix" src="./static/image/wumingnvlang.jpg" alt="">
                <div class="info">
                    <a href=""><h3 class="img_title">无名女郎</h3></a>
                    <p>
                       图片描述可以分为多种，一种是单一说明，就比如直接的告诉读者这篇文章要介绍什么样子的内容，一些配图可以分为含蓄类型的，这样的配图一般会 图片描述可以分为多种。 
                    </p>
                    <div class="btn">
                        <a href="#" class="edit">编辑</a>
                        <a href="#" class="del">删除</a>
                    </div>
                </div>
            </li>
            <li>
                <img class="img-li-fix" src="./static/image/wumingnvlang.jpg" alt="">
                <div class="info">
                    <a href=""><h3 class="img_title">无名女郎</h3></a>
                    <p>
                       图片描述可以分为多种，一种是单一说明，就比如直接的告诉读者这篇文章要介绍什么样子的内容，一些配图可以分为含蓄类型的，这样的配图一般会 图片描述可以分为多种。 
                    </p>
                    <div class="btn">
                        <a href="#" class="edit">编辑</a>
                        <a href="#" class="del">删除</a>
                    </div>
                </div>
            </li>
            <li>
                <img class="img-li-fix" src="./static/image/wumingnvlang.jpg" alt="">
                <div class="info">
                    <a href=""><h3 class="img_title">无名女郎</h3></a>
                    <p>
                       图片描述可以分为多种，一种是单一说明，就比如直接的告诉读者这篇文章要介绍什么样子的内容，一些配图可以分为含蓄类型的，这样的配图一般会 图片描述可以分为多种。 
                    </p>
                    <div class="btn">
                        <a href="#" class="edit">编辑</a>
                        <a href="#" class="del">删除</a>
                    </div>
                </div>
            </li>
-->
        </ul>
    </div>
<!--
    <div class="page-nav">
        <ul>
            <li><a href="#">首页</a></li>
            <li><a href="#">上一页</a></li>
            <li>...</li>
            <li><a href="#">5</a></li>
            <li><a href="#">6</a></li>
            <li><span class="curr-page">7</span></li>
            <li><a href="#">8</a></li>
            <li><a href="#">9</a></li>
            <li>...</li>
            <li><a href="#">下一页</a></li>
            <li><a href="#">尾页</a></li>
        </ul>
    </div>
-->
<?php echo $pages ?>
</div>

<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</body>

<!--简单的js交互告诉用户是否删除-->
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        $('.del').on('click',function () {
            if(confirm('确认删除该画品吗?'))
            {
               window.location = $(this).attr('href');
            }
            return false;
        })
    })
</script>


</html>

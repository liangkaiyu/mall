<?php
//(1)登录验证处理 
header('content-type:text/html;charset=utf8');
include_once './lib/fun.php';
session_start();
if(!isset($_SESSION['user'])||empty($_SESSION['user'])){
    //检测session不存在或者为空
    //header('location:index.php');exit;
    msg(2,'请在我的隔壁登录','login.php');   
}

//(2)校验url中商品id
//http://localhost/mall/delete.php?id=1
$goodsId=isset($_GET['id'])&&is_numeric($_GET['id'])?intval($_GET['id']):'';
if(!$goodsId){
    msg(2,'参数非法','index.php');
}

//(2.1)一定要验证商品id是否存在,在去操控数据库的
//(3)根据商品id查询商品信息(扩展:权限管理)
$con=mysqlInit('localhost','root','123456','liangkaiyu_mall');
$sql="select `id` from `liangkaiyu_goods` where `id`={$goodsId}";
$query=mysqli_query($con,$sql);
if(!$goods=mysqli_fetch_assoc($query)){
    msg(2,'商品不存在','index.php');
}

//(4)数据库删除操作
$sql="delete from `liangkaiyu_goods` where `id`={$goodsId} limit 1";
if($query=mysqli_query($con,$sql)){
	msg(1,'删除成功','index.php');
}else{
	msg(2,'删除失败','index.php');
}




 ?>
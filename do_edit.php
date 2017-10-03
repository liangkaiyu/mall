<?php 
//编辑商品
//(1)登录验证处理
header('content-type:text/html;charset=utf8');
include_once './lib/fun.php';
session_start();
//var_dump($_SESSION['user']);die;???
if(!isset($_SESSION['user'])||empty($_SESSION['user'])){
    //检测session不存在或者为空
    //header('location:index.php');exit;
    msg(2,'请在我的隔壁登录','login.php');   
}

//(2)验证表单是否提交post处理
//对表单name进行提交处理,接收数据
if(!empty($_POST['name'])){
	$con=mysqlInit('localhost','root','123456','liangkaiyu_mall');

	//(划重点)通过隐藏域获取id,校验id
	if(!$goodsId=intval($_POST['id'])){
		msg(2,'参数非法');
	}
	//根据id校验商品信息
	$sql="select * from `liangkaiyu_goods` where `id`={$goodsId}";
	$query=mysqli_query($con,$sql);
	if(!$goods=mysqli_fetch_assoc($query)){
    	msg(2,'商品不存在','index.php');
	}

//(2.1)处理表单数据
    //防止mysqli_real_escape_string(过滤)报错
    $name=mysqli_real_escape_string($con,trim($_POST['name']));
    $price=intval($_POST['price']);
    $des=mysqli_real_escape_string($con,trim($_POST['des']));
    $content=mysqli_real_escape_string($con,trim($_POST['content']));

    //更新数组
    $update=array(
    	'name'=>$name,
    	'price'=>$price,
    	'des'=>$des,
    	'content'=>$content
    );


    //检验商品图片,当用户没有选择图片(仅当用户选择上传图片,才进行图片上传处理)选了,做更新处理
    //var_dump($_FILES['file']);die;
    if($_FILES['file']['size']>0){
    	$pic=imgUpload($_FILES['file']);
    	$update['pic']=$pic;
    }
//(3)商品编辑入库处理
//通过post得到表单数据,通常把它发在数组里面,通过逻辑处理,拼装成sql
    //只更新被更改的信息(对比数据库数据跟用户表单数据)
    foreach ($update as $k => $v) {
    	if($goods[$k]==$v){
    		unset($update[$k]);
    	}
    }

    //对比两个数组,如果没有需要更新的字段
    if(empty($update)){
    	msg(1,'操作成功','edit.php?id='.$goodsId);
    }
    //var_dump($update);exit;

    //更新sql=update `liangkaiyu_goods` set `name`='value',`price`='value' where
    $updatesql='';
    foreach ($update as $k => $v) {
    	$updatesql.="`{$k}`='{$v}',";
    }
    $updatesql=rtrim($updatesql,',');//rtrim()去掉做右边多余逗号
    //var_export($updatesql);die;

    $sql="update `liangkaiyu_goods` set {$updatesql} where `id`={$goodsId}";
    //echo $sql;die;
    //当更新成功
    if($query=mysqli_query($con,$sql)){
    	msg(1,'更新成功','index.php?id='.$goodsId);
    }else{
    	msg(2,'更新失败','edit.php?id='.$goodsId);
    }


}else{
	msg(2,'访问非法','index.php');
}

//任务:增加商品编辑时update_time
 ?>
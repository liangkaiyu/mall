<?php 
header('content-type:text/html;charset=utf8');
include_once './lib/fun.php';
//(2)校验url中商品id(验证商品是否存在)
//http://localhost/mall/delete.php?id=1
$goodsId=isset($_GET['id'])&&is_numeric($_GET['id'])?intval($_GET['id']):'';
if(!$goodsId){
    msg(2,'参数非法','index.php');
}
//(3)根据商品id查询商品信息(扩展:权限管理)
$con=mysqlInit('localhost','root','123456','liangkaiyu_mall');
$sql="select * from `liangkaiyu_goods` where `id`={$goodsId}";
$query=mysqli_query($con,$sql);
if(!$goods=mysqli_fetch_assoc($query)){
    msg(2,'商品不存在','index.php');
}
//var_dump($goods);die;商品信息放在goods关联数组中

//(4)根据用户id查询发布人(用户id=user_id)[获取用户信息]
//查询表与表间关联信息
unset($sql,$query);
$sql="select * from `liangkaiyu_user` where `id`='{$goods['user_id']}'";
//echo $sql;die;
$query=mysqli_query($con,$sql);
$user=mysqli_fetch_assoc($query);
//var_dump($user);

//(5)更新浏览次数
unset($sql,$query);
$sql="update `liangkaiyu_goods` set `view`=`view`+1 where `id`={$goods['id']}";
//echo $sql;die;
mysqli_query($con,$sql);
//var_dump(mysqli_affected_rows($con));

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|<?php echo $goods['name'] ?></title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css" />
    <link rel="stylesheet" type="text/css" href="./static/css/detail.css" />
</head>
<body class="bgf8">
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <!--<li><a href="#">登录</a></li>-->
            <li><span>管理员:<?php echo $user['username'] ?></span></li>
            <li><a href="#">注册</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="section" style="margin-top:20px;">
        <div class="width1200">
            <div class="fl"><img src="<?php echo $goods['pic'] ?>" width="720px" height="432px"/></div>
            <div class="fl sec_intru_bg">
                <dl>
                    <dt><?php echo $goods['name'] ?></dt>
                    <dd>
                        <p>发布人：<span><?php echo $user['username'] ?></span></p>
                        <p>发布时间：<span><?php echo date('Y年m月d日',$goods['create_time']) ?></span></p>
                        <p>修改时间：<span><?php echo date('Y年m月d日',$goods['update_time']) ?></span></p>
                        <p>浏览次数：<span><?php echo $goods['view'] ?></span></p>
                    </dd>
                </dl>
                <ul>
                    <li>售价：<br/><span class="price"><?php echo $goods['price'] ?></span>元</li>
                    <li class="btn"><a href="javascript:;" class="btn btn-bg-red" style="margin-left:38px;">立即购买</a></li>
                    <li class="btn"><a href="javascript:;" class="btn btn-sm-white" style="margin-left:8px;">收藏</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="secion_words">
        <div class="width1200">
            <div class="secion_wordsCon">
            <?php echo $goods['content'] ?>
<!--
                西方绘画在发展历程中，画家总是将当时的科学成就引进艺术创造之中。由于光学和色彩学研究成果问世，后来又经查理士·亨利把光和色彩直接与美学相结合，运用到艺术法则上，这使追求创新的画家们深受影响和启发，他们尝试着纯粹的“外光”描绘，以及新的色彩关系分析，并把这种自然科学的法则和他们的艺术观点结合起来进行创作。他们认为自然界的一切物体都是光的照射作用，才显现出它的物象；而一切物象又是不同色彩的结合，太阳光是由七种原色组合而成。如果离开了光和色彩便没有这个世界。他们还认为：画家要认识这个世界，主要是从“光”和“色彩”的观点上去认识，“光”和“色彩”既然成为这个世界的中心，也是画家认识世界的中心，所以画家的任务也就在于如何去表现光和色彩的效果。“光”为“色”之母，有光才有色，世界上任何具体的物象和事件只是传达光和色彩的媒介罢了，它本身的意义是次要的！这种艺术观念成为他们的主导思想，从而支配他们的创作活动。
由于他们把“光”和“色彩”看成是画家追求的主要目的，就不可避免地将画家对客观事物的认识停留在感觉阶段，停止在“瞬间”的印象上，这就导致创作中竭力描绘事物的瞬间印象，表现感觉的现象，从而否定事物的本质和内容。在他们看来世界万物在阳光下一律是平等的。雷诺阿曾说过：“自然之中，决无贫贱之分。在阳光底下，破败的茅屋可以看成与宫殿一样，高贵的皇帝和穷困的乞丐是平等的。”这种艺术观念导致他们在创作中全力以赴地描绘“光”。只重艺术的形式，忽视乃至否定艺术的内容。如支持印象主义的左拉所说：“绘画所给予人们的是感觉，而不是思想。”所以我们在印象派的画中所看到的是充满阳光的色块组合，充满空气感。总的说来印象派创作只重感觉，忽视思想本质，以瞬间现象取代之；以习作代替创作；以素材代替题材；以偶然代替必然；以次要代替主要的。既然是凭感觉，那必然是主观的，所以印象派所描绘的是主观化了的客观事物。这标志着与传统艺术观念、艺术表现方法和艺术效果的决裂。所以说印象派是西方绘画史上一个划时代的艺术流派。不可否认印象派画家在阳光探索和色彩分析上有重要发现，在对光与色的表现上丰富了绘画的表现技巧，他们倡导走出画室，面对自然进行写生，以迅速的手法把握瞬间的印象，使画面出现不寻常的新鲜生动的感觉，揭示了大自然的丰富灿烂景象，这是对艺术创造的一大贡献。他们的艺术是属于现实主义范畴的，是追求民主、自由、平等思想在艺术中的反映。他们的艺术创造是具有革新和进步意义的。
-->
        
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</div>
</body>
</html>


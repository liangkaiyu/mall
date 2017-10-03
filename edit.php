<?php
//要修改的显示页面edit.php(编辑商品信息的展示),真正的表单处理放在do_edit.php处理 
//每个商品需要通过一个url来传递,比如id=？
//publish.php用户只有登录后才能进行编辑,登录后进入列表页处理

//(1)登录验证处理
header('content-type:text/html;charset=utf8');
include_once './lib/fun.php';
session_start();
if(!isset($_SESSION['user'])||empty($_SESSION['user'])){
    //检测session不存在或者为空
    //header('location:index.php');exit;
    msg(2,'请在我的隔壁登录','login.php');   
}
//登录完成有个登录信息的填写
$user=$_SESSION['user'];

//(2)校验url中商品id
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


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|编辑画品</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><span>管理员:<?php echo $user['username'] ?></span></li>
            <li><a href="#">退出</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="addwrap">
        <div class="addl fl">
            <header>编辑画品</header>
            <form name="publish-form" id="publish-form" action="do_edit.php" method="post"
                  enctype="multipart/form-data">
                <div class="additem">
                    <label id="for-name">画品名称</label><input type="text" name="name" id="name" placeholder="请输入画品名称" value="<?php echo $goods['name'] ?>">
                </div>
                <div class="additem">
                    <label id="for-price">价值</label><input type="text" name="price" id="price" placeholder="请输入画品价值" value="<?php echo $goods['price'] ?>" >
                </div>
                <div class="additem">
                    <!-- 使用accept html5属性 声明仅接受png gif jpeg格式的文件-->
                    <label id="for-file">画品</label><input type="file" accept="image/png,image/gif,image/jpeg" id="file" name="file">
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-des">画品简介</label>
                    <textarea id="des" name="des" placeholder="请输入画品简介"><?php echo $goods['des'] ?></textarea>
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-content">画品详情</label>
                    <div style="margin-left: 120px" id="container">
                        <textarea id="content" name="content"><?php echo $goods['content']?></textarea>
                    </div>

                </div>
                <div style="margin-top: 20px">
<!--隐藏商品id,用于提交商品信息-->
                    <input type="hidden" name="id" value="<?php echo $goods['id'] ?>">
                    <button type="submit">发布</button>
                </div>

            </form>
        </div>
        <div class="addr fr">
            <img src="./static/image/index_banner.png">
        </div>
    </div>

</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script src="./static/js/kindeditor/kindeditor-all-min.js"></script>
<script src="./static/js/kindeditor/lang/zh_CN.js"></script>
<script>
    var K = KindEditor;
    K.create('#content', {
        width      : '475px',
        height     : '400px',
        minWidth   : '30px',
        minHeight  : '50px',
        items      : [
            'undo', 'redo', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'clearhtml',
            'fontsize', 'forecolor', 'bold',
            'italic', 'underline', 'link', 'unlink', '|'
            , 'fullscreen'
        ],
        afterCreate: function () {
            this.sync();
        },
        afterChange: function () {
            //编辑器失去焦点时直接同步，可以取到值
            this.sync();
        }
    });
</script>

<script>
    $(function () {
        $('#publish-form').submit(function () {
            var name = $('#name').val(),
                price = $('#price').val(),
                file = $('#file').val(),
                des = $('#des').val(),
                content = $('#content').val();
            if (name.length <= 0 || name.length > 30) {
                layer.tips('画品名应在1-30字符之内', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }
            //验证为正整数
            if (!/^[1-9]\d{0,8}$/.test(price)) {
                layer.tips('请输入最多9位正整数', '#price', {time: 2000, tips: 2});
                $('#price').focus();
                return false;
            }

/*注意:这里不需要对图片进行处理
            if (file == '' || file.length <= 0) {
                layer.tips('请选择图片', '#file', {time: 2000, tips: 2});
                $('#file').focus();
                return false;
            }
*/
            if (des.length <= 0 || des.length >= 100) {
                layer.tips('画品简介应在1-100字符之内', '#content', {time: 2000, tips: 2});
                $('#des').focus();
                return false;
            }

            if (content.length <= 0) {
                layer.tips('请输入画品详情信息', '#container', {time: 2000, tips: 3});
                $('#content').focus();
                return false;
            }
            return true;

        })
    })
</script>

</html>

<?php 
//公共类库的存储

//数据库连接初始化
function mysqlInit($host,$username,$password,$dbname){
	$con=mysqli_connect($host,$username,$password);
	if(!$con){
		//echo mysqli_error($con);exit;
		return false;
	}
	//选库
	mysqli_select_db($con,$dbname);
	//mysqli_select_db($con,'$dbname');多了对单引号,相信我,会让你怀疑人生的
	//设置字符集
	mysqli_set_charset($con,'utf8');

	return $con;

}

//密码进行加密处理md5()
function createPassword($password){
	//如果密码为空
	if(!$password){
		return false;
	}else{
		return md5(md5($password)).'liangkaiyu';
	}

}

//封装一个函数做跳转处理(消息提示$type=1成功,2失败)
function msg($type,$msg=null,$url=null){
	$toUrl="location:msg.php?type={$type}";
	//当msg为空时url不写入
	$toUrl.=$msg?"&msg={$msg}":'';
	//当url为空时toUrl不写入
	$toUrl.=$url?"&url={$url}":'';
	header($toUrl);
	exit;
}


//publish.php图片上传处理???
function imgUpload($file){
	//检查上传文件是否合法(已经获取这个文件)
    if(!is_uploaded_file($file['tmp_name'])){
        msg(2,'请上传符合规范的图像');
    }

    //上传处理move_uploaded_file(filename, destination)
    $uploadPath='./static/file';//上传目录(物理地址)
    $uploadUrl='./static/file';//上传目录访问Url(url地址)
    //$fileDir=date('Y/md',$now);//上传文件夹
    $fileDir=date('Y/md',$_SERVER['REQUEST_TIME']);

    //检查上传目录是否存在
    if(!is_dir($uploadPath.$fileDir)){
        mkdir($uploadPath.$fileDir,0755,true);//递归创建目录
    }

    //拿到文件的扩展名
    $ext=strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));

    //上传图像名称(唯一性)
    $img=uniqid().mt_rand(1000,9999).'.'.$ext;


//最后得到整个图像目录(物理地址:服务器,url地址:浏览器)
    //域名:可以通过server获取,这里直接获取'http://localhost/mall/'
    $imgPath=$uploadPath.$fileDir.$img;
    $imgUrl='http://localhost/mall'.$uploadUrl.$fileDir.$img;

    //var_dump($imgPath,$imgUrl);die;
//文件上传处理
    if(!move_uploaded_file($file['tmp_name'],$imgPath)){
        msg(2,'服务器君原地爆炸了,请稍后再试');
    }
    //echo $imgUrl;die;
    //获取浏览器可访问的图像地址
    //http://localhost/mall/./static/file2017/0518591d70e40fd408983.jpg
    return $imgUrl;
}


//检测用户是否登录
function checkLogin(){
    session_start();
    if(!isset($_SESSION['user'])||empty($_SESSION['user'])){
    //检测session不存在或者为空
    //msg(2,'请在我的隔壁登录','login.php');   
        return false;
    }
    return true;
}


//分页处理???
//$total:总的条数,$currentPage:(获取url)当前页面的页数,$pageSize:每页显示的条数,$show:指定分页显示到底几个按钮
function pages($total,$currentPage,$pageSize,$show=6){
    $pageStr='';//定义一个null值
    //仅当总数大于每页显示条数,才进行分页处理
    if($total>$pageSize){
        //总页数$totalPage
        $totalPage=ceil($total/$pageSize);//向上取整,获取总页数
        //对当前页进行处理(容错处理)
        $currentPage=$currentPage>$total?$totalPage:$currentPage;

        //分页其实显示页面(分页起始页)???
        $from=max(1,($currentPage-intval($show/2)));
        //分页结束页
        $to=$from+$show-1;
        
        $pageStr.='<div class="page-nav">';//.=定义连续变量
        $pageStr.='<ul>';
        //仅当当前页>1的时候存在首页和上一页
        if($currentPage>1){
            $pageStr.="<li><a href='".pageUrl(1)."'>首页</a></li>";
            $pageStr.="<li><a href='".pageUrl($currentPage-1)."'>上一页</a></li>";

        }

        //当结束页大于总页
        if($to>$totalPage){
            $to=$totalPage;
            $from=max(1,$to-$show+1);
        }

        if($from>1){
            $pageStr.='<li>...</li>';
        }

        for($i=$from;$i<=$to;$i++){
            if($i!=$currentPage){
                $pageStr.="<li><a href='".pageUrl($i)."'>{$i}</a></li>";
            }else{
                $pageStr.="<li><span class='curr-page'>{$i}</span></li>";
            }
        }

        if($to>$totalPage){
            $pageStr.='<li>...</li>';
        }


        if($currentPage<$totalPage){
            $pageStr.="<li><a href='".pageUrl($currentPage+1)."'>下一页</a></li>";
            $pageStr.="<li><a href='".pageUrl($totalPage)."'>尾页</a></li>";
        }
        $pageStr.='</ul>';
        $pageStr.='</div>';

    }
    return $pageStr;
}


//(获取当前url)根据当前url+page值,生成一个要跳转URL的函数
function getUrl(){
    $url='';
    //https是443接口,http是80接口
    $url.= $_SERVER['SERVER_PORT']==443?'https://':'http://';
    //获取域名
    $url.= $_SERVER['HTTP_HOST'];
    //获取域名后面的参数
    $url.= $_SERVER['REQUEST_URI'];
    return $url;
}


//点击page替换相应url(根据page生成url)
function pageUrl($page,$url=''){
    $url=empty($url)?getUrl():$url;
    //查询url中是否存在问号(划重点)从?截取字符串substr,从而获取域名后面的值
    $pos=strpos($url,'?');
    //var_dump($pos);
    if($pos==false){
        $url.='?page='.$page;
    }else{
        $queryString=substr($url,$pos+1);
        //解析$queryString为数组parse_str
        parse_str($queryString,$queryArr);
        if(isset($queryArr['page'])){
            unset($queryArr['page']);
        }
        $queryArr['page']=$page;
        //将queryArr重新拼装成queryString
        $queryStr=http_build_query($queryArr);
        //var_dump($str);

        //http://localhost/mall/index.php?page=7&y=123
        //拼接http://localhost/mall/index.php?y=123&page=8
        $url=substr($url,0,$pos).'?'.$queryStr;
    }
    return $url;
}



 ?>
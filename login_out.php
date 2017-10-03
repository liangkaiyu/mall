<?php 
//退出登录
include_once './lib/fun.php';
session_start();
//释放user
unset($_SESSION['user']);
msg(1,'退出登录成功','index.php');
 ?>
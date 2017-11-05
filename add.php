<?php

include './tpl/tpl_func.php';
include './common/home.php';

$time=date('Y-m-d H:i:s',time());
$username=isset($_POST['username'])?$_POST['username']:'';
$password=isset($_POST['password'])?$_POST['password']:'';

$data=[
	'username'=>$username,
	'password'=>$password,
	'time'=>$time
];
//insert($link,$table,$data)

$res=insert($conn,'user',$data);
if($res){

	$userInfo=select($conn,'user','*');
	echo json_encode($userInfo[count($userInfo)-1]);
}


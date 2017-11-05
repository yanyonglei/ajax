<?php

include './common/home.php';

$page=$_POST['page'];
$total=total($conn,'user','*','id');

$offset=5;

$pages=ceil($total/$offset);

$start=($page-1)*$offset;

$userInfo=select($conn,'user','*',"id limit $start,$offset");

if($userInfo){
	echo json_encode($userInfo);
}

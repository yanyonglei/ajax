<?php
//函数的上传功能
//
/*
功能：文件上传函数 
	@$key  string 上传的文件名
	@path string 路径
	@maxsize int 上传文件的最大限制
	@allowMime array mime 类型集合数组
	@allowSubFix array 后缀集合数组
	@isRandName bool 随机命名
	@ return array  上传文件与否的 反馈信息集合
 */

function upload($key,$path,$maxsize,$allowMime,$allowSubFix,$isRandName=false){

	//$_FILES 获取fom表单的内容
	$error=$_FILES[$key]['error'];

	if ($error) {
		switch ($error) {
			case 1:
			 	$msg='上传文件的php.ini 中upload_max_filesize的限制值';
			 	break; 	
			case 2:
			 	$msg='上传文件的大小超出html 内的指定值';
			 	break;
			case 3:
				$msg="文件只有部分上传";
				break;
			case 4:
				$msg='文件没有被上传';
				break;
			case 6:
				$msg="找不到临时文件";
				break;
			case 7:
				$msg="文件写入失败";
			 	break;
		}
		return [0,$msg];
	}

	//判断文件的大小是否超过了限制值
	if ($_FILES[$key]['size']>$maxsize) {
		return [0,'超过了自定义的文件大小'];
	}

	//判断文件文件的类型mime
	if (!in_array($_FILES[$key]['type'], $allowMime)) {
		return [0,'不准许的mime类型'];
	}


	//判断文件准许的后缀
	$info=pathinfo($_FILES[$key]['name']);


	$subFix=$info['extension'];

	//判断文件的后缀名
	if (!in_array($subFix, $allowSubFix)) {
		return [0,'不准许的后缀名'];
	}

	//判断是否启用随机文件名
	if ($isRandName) {
		$newName=uniqid().'.'.$subFix;
	}else{
		$newName=$_FILES[$key]['name'];
	}

	//拼接路径
	$path=rtrim($path,'/').'/';
	
	//判断路径是否存在
	if (!file_exists($path)) {
		$path=date('Y/m/d').'/';

		if (!file_exists($path)) {
			//创建新的目录
			mkdir($path,0777,true);
		}
	}

	//判断是否是上传文件
	if(!is_uploaded_file($_FILES[$key]['tmp_name'])){
		return [0,'不是上传文件'];
	}


	if (move_uploaded_file($_FILES[$key]['tmp_name'],$path.$newName)) {
		return [1,$path.$newName,$_FILES[$key]['tmp_name']];
	}else{
		return [0,'文件移动失败'];
	}
}
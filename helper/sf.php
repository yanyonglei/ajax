<?php

/*
	*@suofang功能:图片压缩函数
	*@source resource 图片的源文件
	*@width int 压缩后的宽度
	*@height int 压缩后的高度
	*@path string 压缩保存的文件路径
	*@return string 文件路径

 */


	function suofang($source ,$width, $height,$path){
		//打开图片
		$sourceRes=open($source);
		//去取图片信息
		$sourceInfo=getInfo($source);

		$newSize=getNewSize($width,$height,$sourceInfo);
			
		//处理gif变黑的问题
		$newRes=kidOfImage($sourceRes,$newSize,$sourceInfo);

		$path=rtrim($path,'/' ). '/' .uniqid().'.jpg';
		//header('Content-type:image/jpg');
		//保存图片到 path下
		imagejpeg($newRes,$path);
		imagedestroy($newRes);
		return $path;


	}

	//获取文件信息参数
	function getInfo($res){

		$res=getimagesize($res);
		//var_dump($res);
		$info['width']=$res[0];
		$info['height']=$res[1];
		return $info;
	}

function open($path){

 	if (!file_exists($path)) {
 		return false;
 	}

 	$info=getimagesize($path);

 	switch ($info['mime']) {
 		case 'image/jpeg':
 		case 'image/jpg':
 		case 'image/pjpeg':
 			$res=imagecreatefromjpeg($path);
 			break;
 		case 'image/png':
 			$res=imagecreatefrompng($path);
 			break;

 		case 'image/gif':
 			imagecreatefromgif($path);
 			break;
 		case 'image/wbmp':
 		case 'image/bmp':
 			imagecreatefromwbmp($path);
 			break;
 	}
 	return $res;
 }

//处理gif变黑
function kidOfImage($srcImg,$size, $imgInfo){
	//传入新的尺寸，创建一个指定尺寸的图片
	$newImg = imagecreatetruecolor($size["width"], $size["height"]);		
	//定义透明色
	$otsc = imagecolortransparent($srcImg);
	if( $otsc >= 0 && $otsc < imagecolorstotal($srcImg)) {
		 //取得透明色
		 $transparentcolor = imagecolorsforindex( $srcImg, $otsc );
			 //创建透明色
			 $newtransparentcolor = imagecolorallocate(
			 $newImg,
			 $transparentcolor['red'],
				 $transparentcolor['green'],
			 $transparentcolor['blue']
		 );
		//背景填充透明
		 imagefill( $newImg, 0, 0, $newtransparentcolor );
		 
		 imagecolortransparent( $newImg, $newtransparentcolor );
	}

	imagecopyresized( $newImg, $srcImg, 0, 0, 0, 0, $size["width"], $size["height"], $imgInfo["width"], $imgInfo["height"] );
	imagedestroy($srcImg);
	//最终新资就解决了透明色的题 
	return $newImg;
}
//等比缩放
function getNewSize($width, $height,$imgInfo){	
	//将原图片的宽度给数组中的$size["width"]
	$size["width"]=$imgInfo["width"]; 
	//将原图片的高度给数组中的$size["height"]		
	$size["height"]=$imgInfo["height"];        
	
	if($width < $imgInfo["width"]){
		//缩放的宽度如果比原图小才重新设置宽度
		$size["width"]=$width;             
	}

	if($height < $imgInfo["height"]){
		//缩放的高度如果比原图小才重新设置高度
		$size["height"]=$height;            
	}

	if($imgInfo["width"]*$size["width"] > $imgInfo["height"] * $size["height"]){
		$size["height"]=round($imgInfo["height"]*$size["width"]/$imgInfo["width"]);
	}else{
		$size["width"]=round($imgInfo["width"]*$size["height"]/$imgInfo["height"]);
	}

	return $size;
}		



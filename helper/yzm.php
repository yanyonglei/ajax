<?php
/*
 	功能:验证码的制作
	@width: int 宽度
	@height:int 高度
	@num int  字符的个数
	@type string 字符类型
	@imageType string 图片类型
	@return string  验证码字符串
*/
 function verfiy($width=100,$height=25,$num=4,$type=3,$imageType='png')
 {

 		//准备画布
 		$image=imagecreatetruecolor($width,$height);


		
 		//准备为颜色
 		//
 		//
 		//准备字符串
 		$string='';

 		switch ($type) {
 			//代表纯数字
 			case 1:
 				$str='1234567890';
 				$string=substr(str_shuffle($str), 0,$num);
 				break;
 				//纯英文字母
 			case 2:
 				$arr=range('a', 'z');

 				shuffle($arr);
 				$tmp=array_slice($arr, 0,$num);
 				$string=join('',$tmp);
 				break;
 				//组合 
 			case 3:
 				for($i=0;$i<$num;$i++){
 					$rand=mt_rand(0,2);

 					switch ($rand) {
 						case  0:
 							$char=mt_rand(48,57);
 							break;
 						
 						case  1:
 							$char=mt_rand(97,122);
 							break;
 						
 						case  2:
 							$char=mt_rand(65,90);
 							break;
 					}

 					$string.=sprintf('%c',$char);

 				}
 				break;
 		}
 		//填充画布颜色
 	
 		imagefilledrectangle($image,0,0,$width,$height,lightColor($image));
 		
 		//将字符串写在画布上
 		for ($i=0; $i < $num; $i++) { 

 			$x=ceil($width/$num)*$i;

 			$y=mt_rand(0,$height-20);
 			imagechar($image, 5, $x, $y, $string[$i], darkColor($image));
 		}
	
 		//干扰线
 		for ($i=0; $i < $num; $i++) { 
 			imagearc($image,
 				mt_rand(10,$width),mt_rand(10,$height),
 				mt_rand(10,$width),mt_rand(10,$height),
 				mt_rand(0,20),mt_rand(0,20)
 				,darkColor($image));
 		}
		
 		

 		//干扰点
 		for ($i=0; $i < $num*$height; $i++) { 
 			//像素点函数
 			imagesetpixel($image, mt_rand(0,$width), mt_rand(0,$height), lightColor($image));
 		}
		
 		
		header("content-type:image/$imageType");

 		 $func='image'.$imageType;

 		if (function_exists($func)) {
 			$func($image);
 		}else{
 			exit('不支持...');
 		}

 		imagedestroy($image);
 		return $string;

 }
 
 //浅色
 function lightColor($image){


 	$color=imagecolorallocate($image,
	 	 mt_rand(130,255),  
	 	 mt_rand(130,255), 
	 	  mt_rand(130,255)
 	  );

 	return $color;
 }


//深色
 function darkColor($image){
 	$color=imagecolorallocate($image, 
 		mt_rand(0,120), 
 		mt_rand(0,120),
 		mt_rand(0,120)
 		);
 	return $color;
 }
 


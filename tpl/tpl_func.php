<?php
/****
	*@param string $tplPath 模板文件的路径 html 文件
	*@paran string $tplVal 文件的值
	*
 */

include './config/config.php';

function display($tplPath,$tplVal=null){
	
	//组合模板文件(html文件)的路径，拼接模板文件 xx.html
	$tplFilePath=rtrim(TPL_PATH,'/').'/'.$tplPath;
	//echo $tplFilePath;
	//检测模板文件是否存在
	if(!file_exists($tplFilePath)){
		exit('模板文件不存在');
	}

	//开始编译模板文件
	$php=compile($tplFilePath);

	//开始拼接缓存文件
	$cacheFilePath=rtrim(TPL_CACHE,'/').'/'.str_replace('.','_',$tplPath).'.php';

	//判断文件夹
	//检测目录的权限
	if (!check_cache_dir(TPL_CACHE)) {
		exit('没有权限写入');
	}
	
	//写入文件 php文件写入缓存目录
	file_put_contents($cacheFilePath , $php);
	
	//分配变量
	if (is_array($tplVal)) {
		extract($tplVal);
		include $cacheFilePath;
	}	
}
/*
*功能:检查缓存目录
*@param $path string 
*@param return boolean 
 */
function  check_cache_dir($path)
{
	//判断是否是目录 或者目录是否存在
	if (!is_dir($path) || !file_exists($path)) {
		return mkdir($path , 0777 , true);
	}
	
	//可以读写
	if (!is_writeable($path) || !is_readable($path)) {
		return chmod($path , 0777);
	}
	
	return true;
}


/****
	*@param string $path 模板文件
	*@paran string $tplVal 文件的值
	*
 */
function compile($path){
	//读取html 文件的内容
	$file=file_get_contents($path);

	//var_dump($file);

	$keys = [
		'{$%%}' => '<?=$\1;?>',
		'#$%%#' => '<?=$\1; ?>',
		'{if %%}' => '<?php if (\1): ?>',
		'{else}' => '<?php else: ?>',
		'{elseif %%}'   	=> '<?php elseif(\1):?>',
		'{else if %%}'  	=> '<?php elseif(\1):?>',
		'{/if}' => '<?php endif; ?>',
		'{include %%}' => '<?php include "\1"; ?>',
		'{switch %%}' => '<?php switch \1: ?>',
		'{case %%}' => '<?php case \1: ?>',
		'{break}' => '<?php break; ?>',
		'{default}' => '<?php default; ?>',
		'{/switch}' => '<?php endswitch; ?>',
		'{foreach %%}' => '<?php foreach (\1): ?>',
		'{/foreach}'   	=> 	'<?php endforeach; ?>',
		'{for %%}' => '<?php for \1: ?>',
		'{/for}' => '<?php endfor; ?>',
		'__%%__' 	 => '<?=\1;?>',
		'{while %%}'		=> '<?php while(\1):?>',
		'{/while}'			=> '<?php endwhile;?>',
		'{continue}'		=> '<?php continue;?>',
		'{$%%++}'			=> '<?php $\1++;?>',
		'{$%%--}'			=> '<?php $\1--;?>',
		'{/*}'				=> '<?php /*',
		'{*/}'				=> '*/?>',
		'{wn}'				=> '<?php ',
		'{/wn}'				=> '?>',
		'{$%% = $%%}'		=> '<?php $\1 = $\2;?>',
		'{{%%(%%)}}' => 	'<?=\1(\2);?>',
		'{echo %%}'			=> '<?php echo \1;?>'

	];
	
	foreach ($keys as $key => $value) {
		$pattern='#'.str_replace('%%','(.+)',preg_quote($key,'#')).'#ismU';


		$replace=$value;

		if (stripos($pattern,'include')) {
			$file=preg_replace_callback($pattern, 'parseInclude', $file);
		}

		$file=preg_replace($pattern, $replace, $file);

	}

	
	return $file;
	
}


/*
*功能:包含 include 的处理
*@param $data string 路径
*@return string
 */


function parseInclude($data)
{
	
	//var_dump($data);
	$path = str_replace(['\'' , '\''] , '' , $data[1]);
	//echo $path;
	
	display($path);
	
	$cacheFileName = rtrim(TPL_CACHE , '/') . '/' . str_replace('.' , '_' , $path) . '.php';
	
	return "<?php include '$cacheFileName';?>";
}


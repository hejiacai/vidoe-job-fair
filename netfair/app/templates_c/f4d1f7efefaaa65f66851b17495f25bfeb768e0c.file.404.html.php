<?php /* Smarty version Smarty-3.0.7, created on 2020-03-23 15:53:53
         compiled from "../config/404.html" */ ?>
<?php /*%%SmartyHeaderCode:165015e786b119e1568-31361579%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f4d1f7efefaaa65f66851b17495f25bfeb768e0c' => 
    array (
      0 => '../config/404.html',
      1 => 1571362445,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '165015e786b119e1568-31361579',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_modifier_date_format')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.date_format.php';
?><!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="Keywords" content="" />
<meta name="Description" content="" />
<title>404</title>
<!–[if lt IE9]> 
<script src="<?php echo smarty_function_version(array('file'=>'html5.js'),$_smarty_tpl);?>
"></script>  
<![endif]–>
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'icons.css'),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'front.css'),$_smarty_tpl);?>
" />
</head>
<body>
<header>
	<div class="headerCon">
    	<div class="headL">
        	<h1><a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
"></a></h1>                        
        </div>
        <div class="headR">
        	
        </div>
        <div class="clear"></div>
    </div>
</header>
<section class="wr404">
    <dl>
        <dt></dt>
        <dd>
            <p class="tit">您所访问的页面太调皮&nbsp;&nbsp;跑丢了...</p>
            <p>你可以<a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
" class="font14">返回首页</a></p>
            <p>或者你想去其他地方逛逛：<a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/qiuzhi/">找工作</a>|<a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/xiaoyuan/">校园招聘</a>|<a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/zhaopinhui/">现场招聘会</a>|<a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/hrcollege/">HR学院</a>|<a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/login">企业服务</a></p>
            <p>给我们留言或直接与我们取得联系，服务热线：<span class="red strong"><?php echo $_smarty_tpl->getVariable('phone400')->value;?>
</span></p>
        </dd>
    </dl>
</section>
<footer style="position:fixed;bottom:0;left:0;width:100%;background:none;border:0;_position:absolute;">

    	<p>&copy;<?php echo smarty_modifier_date_format(time(),'%Y');?>
&nbsp;Huibo.com&nbsp;版权所有</p>
</footer>
</body>
<script type="text/javascript">
	    (function() {
	        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	        var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
	        ga.src = _bdhmProtocol + 'hm.baidu.com/h.js?2f28019f05c22eb44df412a051d35c52';
	        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	    })();   
</script>
</html>

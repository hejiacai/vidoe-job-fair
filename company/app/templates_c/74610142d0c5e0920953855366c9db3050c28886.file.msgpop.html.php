<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:19:13
         compiled from "app\templates\msgpop.html" */ ?>
<?php /*%%SmartyHeaderCode:281915e7033a1d6eb23-86047649%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '74610142d0c5e0920953855366c9db3050c28886' => 
    array (
      0 => 'app\\templates\\msgpop.html',
      1 => 1584332296,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '281915e7033a1d6eb23-86047649',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><?php include_once ('app/controller/msgpop.php');?>

<style>
	.msg-tip, .msg-icon, .msg-pop {font-size: 14px;}
	.msg-tip {width: 60px; height: 60px; position: fixed;right: 45px;bottom: 144px;text-align: center;}
	.msg-tip img{width: 0px; height: 0px; }
	.msg-icon {width: 0px; height: 12px; position: fixed;right: 103px;bottom: 174px;display: none;}
	.msg-pop {width: 0px;position: fixed;bottom: 95px; z-index:1000;height:117px;right: 108px;border: 0px solid #67bce5;background-color: #fff;font-family: "微软雅黑";}
	.msg-detail {background-color: #fff;text-align: left;padding: 20px;height:77px;display: none;}
	.msg-detail b {color: #ea4645;}
	.msg-detail ul li {list-style: disc;}
	.msg-title {background-color: #efefef;height: 30px;line-height: 30px;text-align: left;}
	.msg-title span {margin-left: 10px;}
	.msg-title a {text-decoration: none; margin-right: 10px;color: #333;float: right;}
	.msg-detail .btnResume {color:#fff;margin:16px 0px 0px 75px;background-color: #67bce5;display: inline-block;width: 93px;height: 30px;line-height: 30px;text-align: center;}
	.msg-detail .btnCancel {background-color: #f5f5f5;color: #898989; margin-left: 20px;display: inline-block;width: 92px;height: 30px;line-height: 30px;text-align: center;}
	.msg-detail .btnResume:hover {background-color: #00aaff}
	.msg-detail i {margin:0px 7px 3px 0px; display: inline-block;width: 4px;height: 4px;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/dian.png) no-repeat 0 center;}
</style>
<div class="msg-tip"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/tips.png" /></div>
<div class="msg-icon"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/beve.png" /></div>
<div class="msg-pop">
	<div class="msg-detail">
		<p style="margin-bottom: 10px;text-align:center">有<b><?php echo $_smarty_tpl->getVariable('total')->value;?>
位新注册求职者</b>可能适合你正在招聘职位</p>
		<?php  $_smarty_tpl->tpl_vars['msg'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('newmsg')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['msg']->key => $_smarty_tpl->tpl_vars['msg']->value){
?>
		<p style="color:#676767;text-align:center"><i style="text-align:left;margin-left: -50px;#margin-left:0px;#vertical-align:7px;"></i><span style="width:160px;display:inline-block;text-align:left;#width:200px"><?php echo $_smarty_tpl->tpl_vars['msg']->value['name'];?>
</span><?php echo $_smarty_tpl->tpl_vars['msg']->value['count'];?>
人合适</p>
		<?php }} ?>
		<a href="<?php echo smarty_function_get_url(array('rule'=>"/nominate/index/",'data'=>"uniqid=".($_smarty_tpl->getVariable('uniqid')->value)),$_smarty_tpl);?>
" target="_blank" class="btnResume">查看简历</a><a href="javascript:void(0)" class="btnCancel">忽略</a>
	</div>
</div>
<script type="text/javascript">

	try{
		hbjs.use(factory);	
	} catch (e){
		factory($);
	}

	function factory($){

		$(".btnCancel, .btnResume").on("click", function (e) {
			$(".msg-pop,.msg-icon,.msg-tip img").fadeOut();
			$.post("<?php echo smarty_function_get_url(array('rule'=>"/nominate/hide/"),$_smarty_tpl);?>
");
		});
	
		var count_msg=<?php echo count($_smarty_tpl->getVariable('newmsg')->value);?>
;
	
		<?php if (!empty($_smarty_tpl->getVariable('newmsg',null,true,false)->value)){?>
		$(".msg-tip img").animate({
			opacity : 0.7,
			width : "60px",
			height : "60px"
		}, 500, "linear", function () {
			$(".msg-icon").show();
			$(".msg-detail").css({height:($(".msg-detail").height()+(count_msg*21))+"px"});
			$(".msg-pop").css({height:($(".msg-pop").height()+(count_msg*21))+"px"});
			$(".msg-icon").animate({width:"6px"},function(){
				$(".msg-pop").css({border:"2px solid #67bce5"});
				$(".msg-pop").animate({width:"324px"},function(){
					$(".msg-detail").fadeIn("fast");
				});
			});
		});
	
		$(".msg-tip img").animate({
			opacity : 1,
			width : "50px",
			height : "50px"
		}, 300);
	
		<?php }?>
	}
</script>
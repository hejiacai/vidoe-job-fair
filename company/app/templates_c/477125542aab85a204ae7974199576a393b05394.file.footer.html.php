<?php /* Smarty version Smarty-3.0.7, created on 2020-03-16 18:22:30
         compiled from "app\templates\footer.html" */ ?>
<?php /*%%SmartyHeaderCode:265535e6f5366667657-48430684%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '477125542aab85a204ae7974199576a393b05394' => 
    array (
      0 => 'app\\templates\\footer.html',
      1 => 1584332296,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '265535e6f5366667657-48430684',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
?><?php include_once ('app/controller/footer.php');?>

<div id="sus" class="sus"><a class="backTop hbFntWes" title="返回顶部" href="javascript:void(0);" style="display: none;">&#xf0d8;</a></div>
<footer>
	<div class="footerNav">
		<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/about",'domain'=>"main"),$_smarty_tpl);?>
">关于汇博</a>|<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/about/contact",'domain'=>"main"),$_smarty_tpl);?>
">联系我们</a>
																				  <!--|<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/about/fee",'domain'=>"main"),$_smarty_tpl);?>
">招聘服务</a>|<a target="_blank" href="">帮助中心</a>-->
																				  |<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/qiye/ent49no58.html",'domain'=>"main"),$_smarty_tpl);?>
">诚聘英才</a>|<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/about/brand",'domain'=>"main"),$_smarty_tpl);?>
">品牌合作</a>|<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/about/links",'domain'=>"main"),$_smarty_tpl);?>
">友情链接</a>
																				  <!--|<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/about/sitemap/",'domain'=>"main"),$_smarty_tpl);?>
">网站地图</a>-->
																				  |<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/about/law/",'domain'=>"main"),$_smarty_tpl);?>
">法律声明</a>|<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/about/privacy/",'domain'=>"main"),$_smarty_tpl);?>
">隐私政策</a>|<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/about/useagreement/",'domain'=>"main"),$_smarty_tpl);?>
">使用协议</a>
	</div>
	<div class="copyright">
		<p>&copy;<?php echo $_smarty_tpl->getVariable('year')->value;?>
&nbsp;<?php echo $_smarty_tpl->getVariable('huibo_title')->value;?>
&nbsp;版权所有&nbsp;&nbsp;|&nbsp;&nbsp;增值电信业务经营许可证：渝B2-20060063&nbsp;&nbsp;|&nbsp;&nbsp;人才中介服务许可证：渝新委发[2012] 124号&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=50010502001182" rel="nofollow" style="color:#666666;">渝公网安备
																																																																																  50010502001182号</a>
		</p>
	</div>
	<a rel="nofollow" key="549264da3b05a3da0fbd60db" logo_size="124x47" logo_type="realname" href="//www.anquan.org">
		<script src="//static.anquan.org/static/outer/js/aq_auth.js"></script>
	</a>
</footer>

<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
"/>
<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'version.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript">
	window.CONFIG = {
		HOST: '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
',
		COMBOPATH: '/js/v2/'
	}
</script>
<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'hbjs.js,jquery.min.js,util.js,class.js,shape.js,event.js,aspect.js,attribute.js,cookie.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'global.js'),$_smarty_tpl);?>
"></script>


<script type="text/javascript">

	try {
		hbjs.use(factory);
	} catch (e) {
		factory($);
	}
	if (typeof window.console.log === "undefined") {
		window.console.log = function (str) {
		};
	}

	function factory($) {
		$(window).scroll(function () {
			if ($(document).scrollTop() > 120) {
				$('#sus').find('a.backTop').css({'display': 'inline-block'});
			} else {
				$('#sus').find('a.backTop').css({'display': 'none'});
			}
		});
		$('#sus').find('a.backTop').click(function () {
			$('html,body').animate({scrollTop: 0});
		});
	}
</script>
<?php if ($_smarty_tpl->getVariable('_usertype')->value=='c'&&$_smarty_tpl->getVariable('_userid')->value){?>
	<a href="javascript:void(0);" id="feedback" class="feedback" style="cursor: pointer">
	</a>
<?php }else{ ?>
	<a target="_blank" id="" class="feedback" href="<?php echo smarty_function_get_url(array('rule'=>'/about/message'),$_smarty_tpl);?>
"></a>
<?php }?>

<!-- 合同管理 -->
<?php if ($_smarty_tpl->getVariable('have_editioncontract')->value&&$_smarty_tpl->getVariable('is_main')->value){?><a href="<?php echo smarty_function_get_url(array('rule'=>'/account/EditionContract'),$_smarty_tpl);?>
" class="contract" target="_blank"></a><?php }?>

<style>
	.contract {
		position: fixed;
		display: block;
		margin-right: 516px;
		right: 50%;
		bottom: 102px;
		width:77px;
		height: 108px;
		background: url('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/contract_img.png') no-repeat;
	}
	.feed_back_dialog .ui_dialog_title {
		text-align: left;
	}
	.feedback img {
		display:block;
	}

	.floatRT {
		display: none;
	}

	.feedback {
		position: fixed;
		margin-left: 516px;
		left: 50%;
		bottom: 50px;
		width:34px;
		height: 106px;
		border-radius: 2px;
		background: url('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/help_imgx02_cur.png') no-repeat;
	}
	.feedbackHelp{
		bottom: 166px;
		background: url('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/help_imgx01.png') no-repeat;
	}
	/*.feedback:hover{background: url('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/help_imgx02_cur.png') no-repeat;}*/
	.feedbackHelp:hover{background: url('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/help_imgx01_cur.png') no-repeat;}
</style>
<script type="text/javascript">
	var Feedback, Dialog;
	hbjs.use('@css3, @jobFlexSlider,@imageEditor, @confirmBox, @jobsort, @orderActions', function (m) {
		var imageEditor = m['widge.imageEditor'],
				ConfirmBox = m['widge.overlay.confirmBox'],
				util = m['base.util'],
				cookie = m['tools.cookie'],
				orderAction = m['product.orderActions'],
				$ = m['jquery'].extend(m['cqjob.jobsort'], m['cqjob.jobFlexSlider']),
				fontSize = 18,
				pWidth = 70,
				imgEditor;
		Dialog = m['widge.overlay.hbDialog'];
		Feedback = new Dialog({
			close: 'x',
			idName: 'feed_back_dialog',
			title: '意见反馈',
			width: 463,
			initHeight: 474,
			autoHeight: false,
			content: '<?php echo smarty_function_get_url(array('rule'=>"/index/MakeFeedBack/"),$_smarty_tpl);?>
',
			isAjax: false
		});
		//意见反馈 isAjax:false展示加载动画
		$("#feedback").click(function () {
			Feedback.set('initHeight', 180);
			Feedback.setContent({
				content: '<?php echo smarty_function_get_url(array('rule'=>"/index/MakeFeedBack/"),$_smarty_tpl);?>
',
			});
			Feedback._addLoading();
			Feedback._body.find('.ui_dialog_loading').css({position: 'absolute', width: '99%'});
			Feedback._close.css({background: 'none', 'line-height': 1.5,'font-family': 'Verdana,"宋体"'});
			Feedback.on('loadComplete', function () {
				Feedback._removeLoading();
			});
			Feedback.show();
		});
	});
</script>

<?php $_template = new Smarty_Internal_Template("statistics.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
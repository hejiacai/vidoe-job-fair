<?php /* Smarty version Smarty-3.0.7, created on 2020-03-26 10:43:07
         compiled from "app\templates\./public/shuanxuan/footer.html" */ ?>
<?php /*%%SmartyHeaderCode:111785e7c16bba884b8-31155750%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c46755f06e0317880df028b5c2121877feb7147c' => 
    array (
      0 => 'app\\templates\\./public/shuanxuan/footer.html',
      1 => 1585190566,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '111785e7c16bba884b8-31155750',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><?php include_once ('app/controller/footer.php');?>

<div id="sus" class="sus"><a class="backTop hbFntWes" title="返回顶部" href="javascript:void(0);" style="display: none;">&#xf0d8;</a></div>
<footer class="footerBm" style="text-align: center;">
	<div class="footerNav"><a target="_blank" href="<?php echo $_smarty_tpl->getVariable('domainUrl')->value;?>
<?php echo smarty_function_get_url(array('rule'=>"/about"),$_smarty_tpl);?>
">关于汇博</a>|<a target="_blank" href="<?php echo $_smarty_tpl->getVariable('domainUrl')->value;?>
<?php echo smarty_function_get_url(array('rule'=>"/about/contact"),$_smarty_tpl);?>
">联系我们</a>|<a target="_blank" href="<?php echo $_smarty_tpl->getVariable('domainUrl')->value;?>
/qiye/ent49no58.html">诚聘英才</a>|<a target="_blank" href="<?php echo $_smarty_tpl->getVariable('domainUrl')->value;?>
<?php echo smarty_function_get_url(array('rule'=>"/about/brand"),$_smarty_tpl);?>
">品牌合作</a>|<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>'/about/media','domain'=>'main'),$_smarty_tpl);?>
">媒体合作</a>|<a target="_blank" href="<?php echo $_smarty_tpl->getVariable('domainUrl')->value;?>
<?php echo smarty_function_get_url(array('rule'=>"/about/links"),$_smarty_tpl);?>
">友情链接</a><!--|<a target="_blank" href="<?php echo $_smarty_tpl->getVariable('domainUrl')->value;?>
<?php echo smarty_function_get_url(array('rule'=>"/about/sitemap/"),$_smarty_tpl);?>
">网站地图</a>-->|<a target="_blank" href="<?php echo $_smarty_tpl->getVariable('domainUrl')->value;?>
<?php echo smarty_function_get_url(array('rule'=>"/about/law/"),$_smarty_tpl);?>
">法律声明</a>|<a target="_blank" href="<?php echo $_smarty_tpl->getVariable('domainUrl')->value;?>
<?php echo smarty_function_get_url(array('rule'=>"/about/privacy/"),$_smarty_tpl);?>
">隐私政策</a></div>
    <div class="copyright">
    	<p>&copy;<?php echo $_smarty_tpl->getVariable('year')->value;?>
&nbsp;<?php echo $_smarty_tpl->getVariable('huibo_title')->value;?>
&nbsp;版权所有&nbsp;&nbsp;|&nbsp;&nbsp;增值电信业务经营许可证：渝B2-20060063&nbsp;&nbsp;|&nbsp;&nbsp;人才中介服务许可证：渝新委发[2012] 124号&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=50010502001182" rel="nofollow" style="color:#666666;">渝公网安备 50010502001182号</a></p>
        <p>招聘单位无权收取任何费用,请求职人员加强自我保护意识,按劳动法规保护自身权益,警惕虚假招聘,避免上当受骗!</p>
    </div>
</footer>
<script type="text/javascript">
function factory($){
	$(window).scroll(function(){
        if ($(document).scrollTop() > 120){
            $('#sus').find('a.backTop').css({'display':'inline-block'});
        }else{
            $('#sus').find('a.backTop').css({'display':'none'});
        }
    });
    $('#sus').find('a.backTop').click(function(){
        $('html,body').animate({ scrollTop: 0 });
    });   
}
</script>
<?php $_template = new Smarty_Internal_Template("./public/shuanxuan/statistics.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

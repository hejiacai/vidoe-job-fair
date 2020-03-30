<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:19:36
         compiled from "app\templates\register/register_head.html" */ ?>
<?php /*%%SmartyHeaderCode:190965e7033b8bbbcd2-26440246%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a05ad75007b15a3b6e0e107f94941447e53d512f' => 
    array (
      0 => 'app\\templates\\register/register_head.html',
      1 => 1584332291,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '190965e7033b8bbbcd2-26440246',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><?php include_once ('app/controller/head.php');?>


<style type="text/css">
.banner{width:100%; height:79px; overflow:hidden; position:relative; z-index:1;}
header,.hdCon{ background:#f0f0f0;}
.hdCon{ height:30px;}
.hdL .logo{ height:30px; line-height:30px; color:#8e8e8e; font-family:"宋体"; font-size:12px;}
.hdR ul li,.hdR ul li a.lnk,.hdR ul .navLst a{ height:30px; line-height:30px;}
.hdR ul .navLst i{ margin:10px 5px 0 0;}
.hdR ul li.wemChatlist{width:100px;}
.hdR ul li.wemChatlist a,.hdR ul li.wemChatlist a:hover{ height:30px; line-height:32px; color:#888;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/weChat06.png) 0 center no-repeat}
.hdR ul li.navLst a:hover,.hdR ul li.wemChatlist a:hover,.hdR ul li.tcomInfo a:hover,.hdR ul li.thelpInfo a:hover{ color: #666;}
.compNavbg{width:100%; overflow:hidden; background:#fff; margin-bottom:20px;}
.compNav{width:1000px; height:50px; overflow:hidden; margin:0 auto; text-align:left;}
.compLogo{ display:block; float:left;width:144px; height:30px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/compLogo.png) no-repeat; margin:10px 0;}
.compList{ float:left; padding-left:60px;}
.compList li{ float:left;}
.compList li a{ display:block; width:105px; text-align:center;height:50px; line-height:50px; font-size:16px; font-family:"微软雅黑"; color:#2a2623;position: relative;}
.compList li a:hover{text-decoration: none;color:#4c4b49;}
.compList li.cut a{ font-weight:bold; color:#004d92; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany02.png) center bottom no-repeat;}
.compList li.cut2 a{ font-weight:bold; color:#004d92; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/weChat07.png) center bottom no-repeat;}
.comPost{ display:block; float:right;width:110px; height:30px; line-height:30px; font-family:"微软雅黑"; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany03.png) 15px center no-repeat #66bce4; text-indent:40px; margin-top:10px; color:#fff; border-radius:4px;font-size:14px;}
.comPost:hover{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany03.png) 15px center no-repeat #5ca8cc; color:#fff;text-decoration: none;}
.comPost:focus,.comPost:active{background-color: #4aa2f2 }
.subcompNavbg{width:100%; overflow:hidden; background:#2b6fad; height:36px; text-align:left;}
.subcompMage{width:1000px; margin:0 auto;}
.subcompNav{ float:left; overflow:hidden;}
.subcompNav li{ float:left; display:none;}
.subcompNav li a{ display:block; float:left; color:#fff; color:#d2e5f6; font-family:"微软雅黑"; height:16px; line-height:16px; padding:0 20px; border-right:1px solid #23598c;border-left:1px solid #368ad9; margin-top:11px; font-size: 14px;}
.subcompNav li a.first{ border-left:none;}
.subcompNav li a.last{ border-right:none;}
.subcompNav li a.cut{ font-weight:bold; color:#fff;}
.subcompNav li a:hover{color:#fff;text-decoration: none;}
.subcompNav li.tabList3{ margin-left:97px;_margin-left:48px;}
.subcompNav li.tabList4{ margin-left:217px; _margin-left:108px;}
.subcompRt{ float:right;}
.subcompRt a{ display:block; height:36px; padding-left:20px; line-height:36px; color:#d2e5f6; float:left; font-family:"微软雅黑"; font-size: 14px; }
.subcompRt a.compHome{ background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany04.png) left center no-repeat; margin-right:20px;}
.subcompRt a.compHome:hover{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany04cut.png) left center no-repeat;color:#fff;}
.subcompRt a.compSch{ background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany05.png) left center no-repeat;}
.subcompRt a.compSch:hover{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany05cut.png) left center no-repeat;color:#fff;}
.hdR ul li a em.num{left:25px; top:2px;}
.comPostBg,.comPostBgcut{ width:110px; height:50px; overflow:hidden; float:right;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/weChat07.png) center bottom no-repeat;}

.notice_icon {background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/bubble.png);width:23px; height:21px; font-size: 12px; font-family: "宋体"; line-height: 18px; color:#fff; position: absolute;top:5px; right:4px; text-align: center;}
</style>
<header id="header" class="header_nav">
    <div class="hdCon">
        <div class="hdL">
            <span class="logo">欢迎您登录汇博网</span>
        </div>
        <div class="hdR" id="hdR">
            <ul>
                <li class="thelpInfo" id="helpBox">
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/login/dologout'),$_smarty_tpl);?>
" class="lnk">退出登录</a>
                </li>
                <li class="tcomInfo" id="boxCompanyInfo">
                    <?php if ($_smarty_tpl->getVariable('companyInfo')->value["is_audit"]!=1&&$_smarty_tpl->getVariable('companyInfo')->value["is_audit"]!=4){?>
                        <a href="javascript:;" class="lnk showNotAudit" onclick="return auditDialogShow()" title='<?php echo $_smarty_tpl->getVariable('companyInfo')->value["company_name"];?>
' style="padding:0 19px 0 0;"><?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->getVariable('companyInfo')->value['company_name'],17,'utf-8','','...');?>
</a>
                    <?php }else{ ?>
                	<a href="<?php echo $_smarty_tpl->getVariable('companyInfo')->value['company_url'];?>
" target="_blank" class="lnk" title='<?php echo $_smarty_tpl->getVariable('companyInfo')->value["company_name"];?>
' style="padding:0 19px 0 0;"><?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->getVariable('companyInfo')->value['company_name'],17,'utf-8','','...');?>
</a>
                    <?php }?>
                </li>
                <li class="navLst"><a href="<?php echo smarty_function_get_url(array('rule'=>'/account/weixin/'),$_smarty_tpl);?>
" id="msgBox">
                	<a style=" display:inline-block;padding:0 29px 0 0px;" href="<?php echo smarty_function_get_url(array('rule'=>"/message/"),$_smarty_tpl);?>
" class="navLnk" id="btnMsg">消息<?php if ($_smarty_tpl->getVariable('companyInfo')->value['messgecount']>0){?> <em class="num"><?php echo $_smarty_tpl->getVariable('companyInfo')->value['messgecount'];?>
</em><?php }?></a>
             	</li> 
            </ul>
        </div>
        
    </div>
</header>
<!--微信气泡提醒2015-12-10 start -->
<style>
    .alt_top{position: absolute;top:20px;font-size:12px;left:500px;display: none;width:209px;height:131px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/weixin/weixin.png) no-repeat;z-index: 99}
    .alt_top i{display: inline-block;width:14px;height:8px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/weixin/weChat09.png) no-repeat;position: absolute;top:-8px;left:70px}
    .alt_top .close{position: absolute;right:7px;bottom:52px;text-decoration: none;cursor: pointer;display: block;width:20px;height: 20px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/weixin/close.png) no-repeat}
    .alt_top .close:hover{background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/weixin/close_hover.png) no-repeat}

</style>
<span class="alt_top" id="wixinAlt" title="微信关注汇博招聘，随时随地招人才！">
    <a class="close" title="关闭"></a>
</span>
<!--微信气泡提醒2015-12-10 end -->
<!--新写的-->
	<div class="step-logo">
		<div class="w1000">
			<a href="<?php echo base_lib_Constant::MAIN_URL_NO_HTTP;?>
"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/www/company/logo.jpg" /></a>
		</div>
	</div>
		
	<!--/-->
<script>
    function auditDialogShow(){
         alert("营业执照尚未通过审核，暂无法预览主页");
    }
</script>


<div class="step-nav">
    <ul class="clearfix"><!--sing = 正在，sye = 已经-->
        <li class="first <?php if ($_smarty_tpl->getVariable('head_step')->value==1){?>sing<?php }elseif($_smarty_tpl->getVariable('head_step')->value>1){?>syes<?php }?>">
            <p>
                <i class="">1</i>
                <u></u>
            </p>
            <span>第一步：企业注册</span>
        </li>
        <li class="<?php if ($_smarty_tpl->getVariable('head_step')->value==2){?>sing<?php }elseif($_smarty_tpl->getVariable('head_step')->value>2){?>syes<?php }?>" >
            <p>
                <i class="">2</i>
                <u></u>
            </p>
            <span>第二步：完善资料</span>
        </li>

        <li class="<?php if ($_smarty_tpl->getVariable('head_step')->value==3){?>sing<?php }elseif($_smarty_tpl->getVariable('head_step')->value>3){?>syes<?php }?>">
            <p>
                <i class="">3</i>
                <u></u>
            </p>
            <span>第三步：完善企业风采</span>
        </li>

        <li class="<?php if ($_smarty_tpl->getVariable('head_step')->value==4){?>sing<?php }elseif($_smarty_tpl->getVariable('head_step')->value>4){?>syes<?php }?>">
            <p>
                <i class="">4</i>
                <u></u>
            </p>
            <span>第四步：发布职位</span>
        </li>
        <li class="last <?php if ($_smarty_tpl->getVariable('head_step')->value==5){?>sing<?php }?>" >
            <p>
                <i class="">5</i>
                <u></u>
            </p>
            <span>第五步：企业认证</span>
        </li>
    </ul>
</div>
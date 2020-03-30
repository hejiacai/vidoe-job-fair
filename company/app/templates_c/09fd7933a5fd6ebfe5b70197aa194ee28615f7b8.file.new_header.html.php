<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:19:13
         compiled from "app\templates\new_header.html" */ ?>
<?php /*%%SmartyHeaderCode:17455e7033a150e933-00670979%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '09fd7933a5fd6ebfe5b70197aa194ee28615f7b8' => 
    array (
      0 => 'app\\templates\\new_header.html',
      1 => 1584332296,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17455e7033a150e933-00670979',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
?><?php include_once ('app/controller/economicnav_header.php');?>

<?php if ($_smarty_tpl->getVariable('par')->value=="全职招聘"){?>
<?php include_once ('app/controller/headpart.php');?>

<?php }elseif($_smarty_tpl->getVariable('par')->value=="兼职招聘"){?>
<?php include_once ('app/controller/headfull.php');?>

<?php }else{ ?>
<?php include_once ('app/controller/head.php');?>

<?php }?>
<style type="text/css">
.banner{width:100%; height:79px; overflow:hidden; position:relative; z-index:1;}
header,.hdCon{ background:#f0f0f0;}
.hdCon{ height:30px;}
.hdL .logo{ height:30px; line-height:30px; color:#8e8e8e; font-family:"宋体"; font-size:12px;}
.hdR{ margin-right: 0;width:750px}
.hdL{width:240px}
.hdR ul{width:750px}
.hdR ul li,.hdR ul li a.lnk,.hdR ul .navLst a{ height:30px; line-height:30px;}
.hdR ul .navLst i{ margin:0 5px 0 0;}
.hdR ul li.wemChatlist{width:100px;}
.hdR ul li.wemChatlist a,.hdR ul li.wemChatlist a:hover{ height:30px; line-height:32px; color:#888;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/weChat06.png) 0 center no-repeat}
.hdR ul li.navLst a:hover,.hdR ul li.wemChatlist a:hover,.hdR ul li.tcomInfo a:hover,.hdR ul li.thelpInfo a:hover{ color: #666;}
.hdR ul li a i{ display: inline-block; width:17px; height: 17px; background: url('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/companyHome_icon.png') no-repeat; margin: 0px 5px 0 0; vertical-align: -5px;}
.hdR ul li.infoHelpx a i{ background-position: -14px -29px; color: #4B84B9;}
.hdR ul li.infoHelpz a i{ background-position: -43px -8px;}
.hdR ul li.infoHelpc a i{width:21px; background-position: -68px -7px;}
.hdR ul li.infoHelpm a i{width:15px; height: 14px; background-position: -99px -7px; vertical-align: middle;}
.hdR ul li.infoHelpn a i{ background-position: -124px -6px;}
.hdR ul li a:hover{ color: #4b84b9!important;}
.hdR ul li.infoHelpx a:hover i{ background-position: -14px -29px;}
.hdR ul li.infoHelpz a:hover i{ background-position: -43px -30px;}
.hdR ul li.infoHelpc a:hover i{width:21px; background-position: -68px -29px;}
.hdR ul li.infoHelpm a:hover i{width:15px; height: 14px; background-position: -99px -29px; vertical-align: middle;}
.hdR ul li.infoHelpn a:hover i{ background-position: -124px -28px;}

.compNavbg{width:100%; overflow:hidden; background:#fff; margin-bottom:20px;}
.compNav{width:1200px; height:50px; overflow:hidden; margin:0 auto; text-align:left; position: relative;}
.compLogo{ display:block; float:left;width:144px; height:30px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/compLogo.png) no-repeat; margin:10px 0;}
.compHot,.compHot2{ display: block; position: absolute;top:3px; left: 515px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/www/hot.png) no-repeat;width:26px; height: 16px;}
.compHot{ left: 606px;}
.compList{ float:left; padding-left:10px;}
.compList li{ float:left;}
.compList li a{ display:block; width:94px; text-align:center;height:50px; line-height:50px; font-size:16px; font-family:"微软雅黑"; color:#2a2623;position: relative;}
.compList li a:hover{text-decoration: none;color:#4c4b49;}
.compList li.cut a{ font-weight:bold; color:#2c7bdc; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany02.png) center bottom no-repeat;}
.compList li.cut2 a{ font-weight:bold; color:#004d92; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/weChat07.png) center bottom no-repeat;}
.comPost{ display:block; float:right;width:110px; height:30px; line-height:30px; font-family:"微软雅黑"; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany03.png) 15px center no-repeat #66bce4; text-indent:40px; margin-top:10px; color:#fff; border-radius:4px;font-size:14px;}
.comPost:hover{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany03.png) 15px center no-repeat #5ca8cc; color:#fff;text-decoration: none;}
.comPost:focus,.comPost:active{background-color: #4aa2f2 }
.subcompNavbg{width:100%; overflow:hidden; background:#2c7bdc; height:36px; text-align:left;}
.subcompMage{width:1000px; margin:0 auto;}
.subcompNav{ float:left; overflow:hidden;}
.subcompNav li{ float:left; display:none;}
.subcompNav li a{ display:block; float:left; color:#fff; color:#d2e5f6; font-family:"微软雅黑"; height:16px; line-height:16px; padding:0 18px; border-right:1px solid #1c62ea;border-left:1px solid #5d94ff; margin-top:11px; font-size: 14px;}
.subcompNav li a.first{ border-left:none;}
.subcompNav li a.last{ border-right:none;}
.subcompNav li a{position: relative}
.subcompNav li a .msg{font-size: 12px;color:#f9f9f9;background: #ff0000;border-radius: 10px;font-weight: normal;padding:0px 4px;height:12px;line-height: 12px;position: absolute;top:-5px;right:0;font-family: '宋体'}
.subcompNav li a.cut{ font-weight:bold; color:#fff;}
.subcompNav li a:hover{color:#fff;text-decoration: none;}
.subcompNav li.tabList3{ margin-left:13px;_margin-left:7px;}
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
.hdR ul li a em.num{left:45px; top:2px;}
.comPostBg,.comPostBgcut{ width:110px; height:50px; overflow:hidden; float:right;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/weChat07.png) center bottom no-repeat;}

.notice_icon {background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/bubble.png);width:23px; height:21px; font-size: 12px; font-family: "宋体"; line-height: 18px; color:#fff; position: absolute;top:5px; right:4px; text-align: center;}


.marktHot,.marktHotx02{width:192px; height:63px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/agency02.png) no-repeat; position:absolute; top:112px; left:50%; z-index:9999; margin-left:182px; overflow:hidden; display: none;}
.marktHot a,.marktHotx02 a{ display:block;width:20px; height:20px; float:right; margin:6px 2px 0 0;}
.marktHotx02{width:349px; height: 60px;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/agencyx02.png) no-repeat; margin-left: -236px;}

.marktHot,.marktHotx03{width:191px; height:45px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/agency02.png) no-repeat; position:absolute; top:112px; left:50%; z-index:9999; margin-left:182px; overflow:hidden;}
.marktHot a,.marktHotx03 a{ display:block;width:20px; height:20px; float:right; margin:6px 2px 0 0;}
.marktHotx03{width:191px; height: 45px;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/agencyx03.png) no-repeat; margin-left: 89px;}
.marktHotx03 .newa {display: block;width: 30px;  font-size: 16px;color: #ff6f04;font-weight: bold;text-decoration: underline;position: absolute;top: 8px;left: 37px;}

.hdR ul li a#btnMsg i {
    display: inline-block;
    width: 17px;
    height: 17px;
    background: url(//assets.huibo.com/img/company/companyHome_icon.png) -99px -7px no-repeat ;
    margin: 9px 5px 0 0;
    vertical-align: -5px;
}
.hdR ul li a#btnMsg:hover i{ background-position: -99px -29px}
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
" class="lnk md_logout">退出登录</a>
                </li>
                <li class="tcomInfo infoHelpn" id="boxCompanyInfo">
                    <?php if ($_smarty_tpl->getVariable('companyInfo')->value["is_audit"]!=1&&$_smarty_tpl->getVariable('companyInfo')->value["is_audit"]!=4){?>
                        <a href="javascript:;" class="lnk showNotAudit md_companyinfo" onclick="return auditDialogShow()" title='<?php echo $_smarty_tpl->getVariable('companyInfo')->value["company_name"];?>
' style="padding:0 19px 0 0;"><i></i><?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->getVariable('companyInfo')->value['company_name'],17,'utf-8','','...');?>
</a>
                    <?php }else{ ?>
                	<a href="<?php echo $_smarty_tpl->getVariable('companyInfo')->value['company_url'];?>
" target="_blank" class="lnk md_companyinfo" title='<?php echo $_smarty_tpl->getVariable('companyInfo')->value["company_name"];?>
' style="padding:0 19px 0 0;"><i></i><?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->getVariable('companyInfo')->value['company_name'],17,'utf-8','','...');?>
</a>
                    <?php }?>
                </li>
                <li class="navLst infoHelpm" style="margin-left:10px;"><a href="<?php echo smarty_function_get_url(array('rule'=>'/account/weixin/'),$_smarty_tpl);?>
" id="msgBox">
                	<a style=" display:inline-block;padding:0 29px 0 0px;" href="<?php echo smarty_function_get_url(array('rule'=>"/message/"),$_smarty_tpl);?>
" class="navLnk md_message" id="btnMsg"><i></i>消息<?php if ($_smarty_tpl->getVariable('companyInfo')->value['messgecount']>0){?> <em class="num"><?php echo $_smarty_tpl->getVariable('companyInfo')->value['messgecount'];?>
</em><?php }?></a>
             	</li>
             	<li class="infoHelpc" style="margin-left:10px;margin-right: 10px"><a class="lnk md_mcruit" href="<?php echo smarty_function_get_url(array('rule'=>'/account/weixin','domain'=>'company'),$_smarty_tpl);?>
" target="_blank"><i></i>微信招聘</a></li>
                <li class="tcomInfo infoHelpz" style="margin-left:10px;"><a class="lnk md_phone" href="<?php echo smarty_function_get_url(array('rule'=>'/topicpage/mobile/app','domain'=>'main'),$_smarty_tpl);?>
/index.html" target="_blank"><i></i>手机招聘</a></li>
                <?php if ($_smarty_tpl->getVariable('service_type')->value==0){?>
                <li class="tcomInfo infoHelpx"><a class="lnk md_help" href="<?php echo smarty_function_get_url(array('rule'=>'/help','domain'=>'company'),$_smarty_tpl);?>
" target="_blank"><i></i>帮助中心</a></li>
                <?php }elseif($_smarty_tpl->getVariable('service_type')->value==1){?>
                <li class="tcomInfo infoHelpx"><a class="lnk md_help" href="<?php echo smarty_function_get_url(array('rule'=>'/help','domain'=>'company'),$_smarty_tpl);?>
" target="_blank"><i></i>帮助中心</a></li>
                <?php }else{ ?>
                <li class="tcomInfo infoHelpx"><a class="lnk md_help" href="<?php echo smarty_function_get_url(array('rule'=>'/help','domain'=>'company'),$_smarty_tpl);?>
" target="_blank"><i></i>帮助中心</a></li>
                <?php }?>
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
    .iphoneCodex{ display: block;width:120px; height: 154px; position: absolute; top: 30px;z-index: 99999; display: none;}
    .auditNotPassx{width:300px; height: 50px; line-height: 50px; background: #e84c3d; color: #fff; position: fixed; border-radius: 4px; position: fixed; top: 50%; left: 50%; margin: -25px 0 0 -150px; display: none; z-index: 999;}
	.videoInterviewcx{width:430px; padding: 10px 0 0 10px; text-align: left; margin: 0 auto;}
	.videoInterviewcx img{ display: block; float: left;width:60px; height: 60px; border-radius: 50%; border: 1px solid #f1f1f1;}
	.videoInterviewcx div{width:360px; float: right; overflow: hidden;}
	.videoInterviewcx div p{ color: #222; font-size: 14px; line-height: 30px;}
	.videoInterviewcx div p em{ display: inline-block; margin-right: 20px;}
	/* .videoInterviewcx a{ display: block;width:100px; height: 35px; background: #0AA0FF; border-radius: 4px; margin: 10px auto; color: #fff;} */
    .newDemandPosRe{position: relative;}
	.newDemandIcon{position:absolute ;top: -10px;right: 0px;z-index: 20;}
</style>
<!--手机/微信招聘-->
<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/mobile_code01.png" class="iphoneCodex iphoneCodez"/>
<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/mobile_code02.png" class="iphoneCodex iphoneCodeweixin"/>
<span class="alt_top" id="wixinAlt" title="微信关注汇博招聘，随时随地招人才！">
    <a class="close" title="关闭"></a>
</span>
<!--微信气泡提醒2015-12-10 end -->
<!---精准推广气泡20170811------->
<?php if ($_smarty_tpl->getVariable('par')->value=="全职招聘"){?>
<div class="marktHot" id="markHotCompanySpread" <?php if ($_smarty_tpl->getVariable('isHotCompanySpread')->value==true){?> style="display: none;"<?php }?>>
    <a onclick="HostCompanySpreadClose()"></a>
</div>
<?php }?>
<!-----面试管理气泡------>
<div class="marktHotx02" id='inviteDiv' <?php if ($_smarty_tpl->getVariable('audition_tip_msg')->value){?>style="display: none;"<?php }?>>
    <a href="javascript:;" onclick='inviteClose()'></a>
</div>

<!-- 职位相符度评价 -->
<?php if ($_smarty_tpl->getVariable('companyInfo')->value['not_read_bad_review_num']>0){?>
<div class="marktHotx03" id="jobCompat">
	<a href="<?php echo smarty_function_get_url(array('rule'=>'/appraise/'),$_smarty_tpl);?>
" class="newa"><?php echo $_smarty_tpl->getVariable('companyInfo')->value['not_read_bad_review_num'];?>
条</a>
    <a href="javascript:;" onclick="jobCompatClose()"></a>
</div>
<?php }?>
<div class="compNavbg">
    <div class="compNav">
        <a href="<?php echo base_lib_Constant::MAIN_URL_NO_HTTP;?>
" class="compLogo"></a>
		<span class="compHot"></span>
		<span class="compHot2"></span>
        <ul class="compList">
            <li class="md_hbjob_mrecruit tabList1 <?php if ($_smarty_tpl->getVariable('par')->value=="全职招聘"){?>cut<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>"/index/"),$_smarty_tpl);?>
">全职招聘<?php if ($_smarty_tpl->getVariable('par')->value!="全职招聘"&&$_smarty_tpl->getVariable('companyInfo')->value['full_count']){?><em class="notice_icon"><?php echo $_smarty_tpl->getVariable('companyInfo')->value['full_count'];?>
</em><?php }?></a></li>
            <?php if (in_array(substr($_smarty_tpl->getVariable('base_location_area')->value,0,2),array('03','15','26'))){?>
                <li id="jianzhi" class="md_partjob_mrecruit tabList2 <?php if ($_smarty_tpl->getVariable('par')->value=="兼职招聘"){?>cut<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>"/part/"),$_smarty_tpl);?>
">兼职招聘<?php if ($_smarty_tpl->getVariable('par')->value!="兼职招聘"&&$_smarty_tpl->getVariable('companyInfo')->value['part_count']){?><em class="notice_icon"><?php echo $_smarty_tpl->getVariable('companyInfo')->value['part_count'];?>
</em><?php }?></a></li>
                <li class="md_fire_mrecruit <?php if ($_smarty_tpl->getVariable('cur')->value=="现场招聘"){?>cut2<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>'/fair'),$_smarty_tpl);?>
">现场招聘</a></li>
            <?php }?>
            <li class="md_movie_school <?php if ($_smarty_tpl->getVariable('cur')->value=="视频校招"){?>cut2<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>'/shuangxuannet'),$_smarty_tpl);?>
">视频招聘会</a></li>
            <li class="worker_shar <?php if ($_smarty_tpl->getVariable('cur')->value=="人才共享"){?>cut2<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>'/workershar/index'),$_smarty_tpl);?>
">人才共享</a></li>
            <li class="md_lwpq <?php if ($_smarty_tpl->getVariable('cur')->value=="劳务派遣"){?>cut2<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>'/paiqian/','domain'=>'main'),$_smarty_tpl);?>
" target="_blank">劳务派遣</a></li>
            <li class="md_zpwb <?php if ($_smarty_tpl->getVariable('cur')->value=="招聘外部"){?>cut2<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>'/rpo/','domain'=>'main'),$_smarty_tpl);?>
" target="_blank">招聘外包</a></li>
            <li class="md_hblt <?php if ($_smarty_tpl->getVariable('cur')->value=="汇博猎头"){?>cut2<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>'/lietou','domain'=>'main'),$_smarty_tpl);?>
" target="_blank">汇博猎头</a></li>
            <li class="md_account_mg <?php if ($_smarty_tpl->getVariable('par')->value=="企业管理"){?>cut<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>'/account/'),$_smarty_tpl);?>
">账户管理</a></li>
            <li class="md_free_createsit <?php if ($_smarty_tpl->getVariable('cur')->value=="免费建站"){?>cut2<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>'/cmscompany/'),$_smarty_tpl);?>
">免费建站</a></li>

        </ul>
        <!--
        <?php if ($_smarty_tpl->getVariable('cur')->value=="发布职位"){?>
        <div class="comPostBg">
            <a href="<?php echo smarty_function_get_url(array('rule'=>"/partjob/addjobselect/"),$_smarty_tpl);?>
" class="comPost">发布职位</a>
        </div>
        <?php }else{ ?>
        <a href="<?php echo smarty_function_get_url(array('rule'=>"/partjob/addjobselect/"),$_smarty_tpl);?>
" class="comPost">发布职位</a>
        <?php }?>
        -->
    </div>
    <?php if (!empty($_smarty_tpl->getVariable('par',null,true,false)->value)){?>
    <div class="subcompNavbg">
        <div class="subcompMage">
            <ul class="subcompNav">
                <li class="tabList3" style="<?php if ($_smarty_tpl->getVariable('par')->value=="全职招聘"){?>display:block;<?php }?>">
                    <a href="<?php echo smarty_function_get_url(array('rule'=>"/index/"),$_smarty_tpl);?>
" class="md_hbjob_index first <?php if ($_smarty_tpl->getVariable('cur')->value=="首页"){?>cut<?php }?>">首页</a>
                    <a id="static_job_manager" href="javascript:;" class="static_job_manager <?php if ($_smarty_tpl->getVariable('cur')->value=="职位管理"){?>cut<?php }?>">职位管理</a>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/apply'),$_smarty_tpl);?>
" class="md_hbjob_resume_mg <?php if ($_smarty_tpl->getVariable('cur')->value=="简历管理"){?>cut<?php }?>">简历管理</a>
                <?php if (!$_smarty_tpl->getVariable('_is_gray_test_company')->value){?>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1'),$_smarty_tpl);?>
" class="md_hbjob_invite_mg <?php if ($_smarty_tpl->getVariable('cur')->value=="面试管理"){?>cut<?php }?>">面试管理</a>
                <?php }?>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/resumesearchnew'),$_smarty_tpl);?>
" class="md_hbjob_resume_search <?php if ($_smarty_tpl->getVariable('cur')->value=="简历搜索"){?>cut<?php }?>">简历搜索</a>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>"/guestbook"),$_smarty_tpl);?>
" class="md_hbjob_person_say <?php if ($_smarty_tpl->getVariable('cur')->value=="留言"){?>cut<?php }?>">求职者留言<?php if ($_smarty_tpl->getVariable('cur')->value!="留言"&&$_smarty_tpl->getVariable('companyInfo')->value["guestbookcount"]>0){?><i class="msg"><?php echo $_smarty_tpl->getVariable('companyInfo')->value["guestbookcount"];?>
</i><?php }?></a>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/appraise/'),$_smarty_tpl);?>
" class="md_hbjob_job_apprise <?php if ($_smarty_tpl->getVariable('cur')->value=="职位相符度评价"){?>cut<?php }?>">职位相符度评价<?php if ($_smarty_tpl->getVariable('cur')->value!="职位相符度评价"&&$_smarty_tpl->getVariable('companyInfo')->value["not_read_appraise_num"]>0){?><i class="msg"><?php echo $_smarty_tpl->getVariable('companyInfo')->value["not_read_appraise_num"];?>
</i><?php }?></a>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/spreadjob/index'),$_smarty_tpl);?>
" class="md_hbjob_jztg <?php if ($_smarty_tpl->getVariable('cur')->value=="精准推广"){?>cut<?php }?>">精准推广</a>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/readjob/CompanyVisit'),$_smarty_tpl);?>
" class="md_data_statics last <?php if ($_smarty_tpl->getVariable('cur')->value=="数据报表"){?>cut<?php }?> newDemandPosRe"> 数据报表 <img class="newDemandIcon" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/newDemand1.png" alt="新功能"></a>
                </li>
                <li class="tabList4" style="<?php if ($_smarty_tpl->getVariable('par')->value=="兼职招聘"){?>display:block;<?php }?>">
                    <a href="<?php echo smarty_function_get_url(array('rule'=>"/part/"),$_smarty_tpl);?>
" class="first <?php if ($_smarty_tpl->getVariable('cur')->value=="首页"){?>cut<?php }?>">首页</a>
                    <!--<a href="<?php echo smarty_function_get_url(array('rule'=>"/partjob/"),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('cur')->value=="职位管理"){?>cut<?php }?>">职位管理</a>-->
                    <a href="<?php echo smarty_function_get_url(array('rule'=>"/partresume/"),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('cur')->value=="简历管理"){?>cut<?php }?>">简历管理</a>
                    <!--<a href="<?php echo smarty_function_get_url(array('rule'=>"/partjobapply/"),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('cur')->value=="报名管理"){?>cut<?php }?>">报名管理</a>-->
                    <a href="<?php echo smarty_function_get_url(array('rule'=>"/partcomment/index/"),$_smarty_tpl);?>
" class="last <?php if ($_smarty_tpl->getVariable('cur')->value=="评价管理"){?>cut<?php }?>">评价管理</a>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>"/parttallage/index/"),$_smarty_tpl);?>
" class="last <?php if ($_smarty_tpl->getVariable('cur')->value=="用工管理"){?>cut<?php }?>" <?php if (!$_smarty_tpl->getVariable('import_task_num')->value){?>style="display:none;"<?php }?>>用工管理</a>
                </li>
                <li class="tabList4" style="<?php if ($_smarty_tpl->getVariable('par')->value=="企业管理"){?>display:block;<?php }?> margin-left:45px;">
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/account/'),$_smarty_tpl);?>
" class="first <?php if ($_smarty_tpl->getVariable('cur')->value=='我的账户'){?>cut<?php }?>">我的账户</a>
                    <a href="<?php if (!$_smarty_tpl->getVariable('is_show_consume_static')->value){?> <?php echo smarty_function_get_url(array('rule'=>'/commoncoupon/'),$_smarty_tpl);?>
 <?php }else{ ?> <?php echo smarty_function_get_url(array('rule'=>'/consumelog/ConsumeStatic'),$_smarty_tpl);?>
 <?php }?>" class=" <?php if ($_smarty_tpl->getVariable('cur')->value=='资金管理'){?>cut<?php }?>">资金管理</a>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/company/modify'),$_smarty_tpl);?>
" <?php if ($_smarty_tpl->getVariable('cur')->value=='企业资料'){?>class="cut"<?php }?>>企业资料</a>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/environment'),$_smarty_tpl);?>
" <?php if ($_smarty_tpl->getVariable('cur')->value=='企业环境'){?>class="cut"<?php }?>>企业风采</a>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/licencevalidate'),$_smarty_tpl);?>
" <?php if ($_smarty_tpl->getVariable('cur')->value=='企业认证'){?>class="cut"<?php }?>>企业认证</a>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/hrlicence'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('cur')->value=='企业资质'){?>cut<?php }?>">企业资质</a>
                    <?php if ($_smarty_tpl->getVariable('companyInfo')->value['is_thr']){?>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/hrmanage'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('cur')->value=='代招客户'){?>cut<?php }?>">代招客户</a>
                    <?php }?>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/account/accountmanage'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('cur')->value=='账号管理'){?>cut<?php }?>">账号管理</a>
                    <?php if ($_smarty_tpl->getVariable('is_main')->value){?>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/account/AccountLogList'),$_smarty_tpl);?>
" class="last <?php if ($_smarty_tpl->getVariable('cur')->value=='操作日志'){?>cut<?php }?>">操作日志</a>
                    <?php }?>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/account/EditionContract'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('cur')->value=='合同管理'){?>cut<?php }?>">合同管理</a>
                </li>
            </ul>
            <?php if ($_smarty_tpl->getVariable('par')->value=="全职招聘"){?>
            <div class="subcompRt">
                 <?php if ($_smarty_tpl->getVariable('companyInfo')->value["is_audit"]!=1&&$_smarty_tpl->getVariable('companyInfo')->value["is_audit"]!=4){?>
                    <a href="javascript:;" class="md_hbjob_see_home compHome showNotAudit" onclick="return auditDialogShow()">预览主页</a>
                 <?php }else{ ?>
                    <a href="<?php echo $_smarty_tpl->getVariable('companyInfo')->value['company_url'];?>
" class="md_hbjob_see_home compHome" target="_blank">预览主页</a>
                 <?php }?>
            </div>
            <?php }?>
        </div>
        </div>
    </div>
    <?php }?>
</div>
<div class="auditNotPassx">企业认证尚未通过审核，暂时无法预览主页</div>

<script type = "text/javascript" >
    window.jQuery || document.write('<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
">'+'<\/script>');

</script>
<?php if ($_smarty_tpl->getVariable('no_video_notice')->value!=1){?>
<script>
    $(document).ready(function () {
       // console.log('视频招聘提醒');
        $.post("<?php echo smarty_function_get_url(array('rule'=>'/shuangxuannet/applyone/'),$_smarty_tpl);?>
",{},function(r){
           // console.log(r);
            if(200==r.code){
                hbjs.use('@hbCommon, @jobDialog, @validator, @confirmBox', function(m) {
                    var $ = m['product.hbCommon'].extend(m['cqjob.jobDialog']);
                    var Dialog = m['widge.overlay.hbDialog'];
                    resumeCompleteDialog = new Dialog({
                        close: 'x',
                        idName: 'resume_complete_dialog',
                        title: '视频面试申请',
                        content: '<div class="videoInterviewcx"><img src="'+r.data.photo+'">' +'<div><p>'+r.data.user_name+'</p>'+
                            '<p>'+'<em>'+r.data.sex+'</em><em>'+r.data.age+'</em><em>'+r.data.degree+'</em>'+'</p>'+
                            '<p>'+'<em>'+r.data.school+'</em><em>'+r.data.major_desc+'</em>'+'</p>'+
                            '<p>面试职位：'+r.data.station+'</p></div>'+
                            /*'<div style="text-align:center;width:100%;padding:20px 0"><a href="<?php echo smarty_function_get_url(array('rule'=>"/chat"),$_smarty_tpl);?>
?sid='+r.data.sid+'&resume_id='+r.data.resume_id+'&job_id='+r.data.job_id+'&net_apply_id='+r.data.shuangxuan_apply_id+'" class="btn1 btnsF14">开始面试</a></div>'+*/
                            '<div style="text-align:center;width:100%;padding:20px 0"><a href="<?php echo smarty_function_get_url(array('rule'=>"/videohall/VideoInterviewHall/"),$_smarty_tpl);?>
?sid='+r.data.sid+'" class="btn1 btnsF14">开始面试</a></div>'+
                            '</div>' ,
                        width: 450,
                        isAjax: false
                        });
                    resumeCompleteDialog.show();
                    });
            }
        },'json');
    })
</script>
<?php }?>
<script>
	
	$('.infoHelpz').hover(function(){
		var thisOfset = $(this).offset().left;
		$('.iphoneCodez').css('left',thisOfset-20);
		$('.iphoneCodez').toggle();
	});
	$('.infoHelpc').hover(function(){
		$('.iphoneCodeweixin').toggle();
		var thisOfset = $(this).offset().left;
		$('.iphoneCodeweixin').css('left',thisOfset-20);
	});
    function auditDialogShow(){
    	$('.auditNotPassx').show();
    	setTimeout(function(){
    		$('.auditNotPassx').hide();
    	},2000);
       
    }
    function HostCompanySpreadClose(){
        document.getElementById('markHotCompanySpread').style.display='none';
        //XmlHttpRequest对象
        var xmlReq = '';
        if(window.ActiveXObject){ //如果是IE浏览器
            xmlReq = new ActiveXObject("Microsoft.XMLHTTP");
        }else if(window.XMLHttpRequest){ //非IE浏览器
            xmlReq = new XMLHttpRequest();
        }
        var url = "/index/SetHotCompanySpreadCookie/";
        xmlReq.open("post", url, true);
        xmlReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlReq.send(null);
    }
    
    function  inviteClose(){
        document.getElementById('inviteDiv').style.display='none';
    }
	
	function jobCompatClose() {
		document.getElementById('jobCompat').style.display='none';
	}
</script>

<script type="text/javascript">
    var action_url = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
';
     $('.static_job_manager').click(function () {
         var log_type = 4;
         var log_data = $(this).attr('log_data');
         if (!log_type) {
             console.log('提交监控失败[未指定 log_type]:', _this);
         }

         do_save_click(log_type,log_data);

         setTimeout(function () {
             window.location.href= "<?php echo smarty_function_get_url(array('rule'=>'/index/joblist'),$_smarty_tpl);?>
";
         },1000)
     })

    $('.md_help').click(function () {
        do_save_click(97,$(this).attr('log_data'));
    })

    $('.md_phone').click(function () {
        do_save_click(98,$(this).attr('log_data'));
    })

    $('.md_mcruit').click(function () {
        do_save_click(99,$(this).attr('log_data'));
    })

    $('.md_message').click(function () {
        do_save_click(100,$(this).attr('log_data'));
    })


    $('.md_companyinfo').click(function () {
        do_save_click(101,$(this).attr('log_data'));
    })

    $('.md_logout').click(function () {
        do_save_click(102,$(this).attr('log_data'));
    })


    $('.md_hbjob_mrecruit').click(function () {
        do_save_click(103,$(this).attr('log_data'));
    })
    $('.md_partjob_mrecruit').click(function () {
        do_save_click(104,$(this).attr('log_data'));
    })
    $('.md_fire_mrecruit').click(function () {
        do_save_click(105,$(this).attr('log_data'));
    })
    $('.md_lwpq').click(function () {
        do_save_click(106,$(this).attr('log_data'));
    })
    $('.md_zpwb').click(function () {
        do_save_click(107,$(this).attr('log_data'));
    })
    $('.md_hblt').click(function () {
        do_save_click(108,$(this).attr('log_data'));
    })
    $('.md_account_mg').click(function () {
        do_save_click(109,$(this).attr('log_data'));
    })
    $('.md_free_createsit').click(function () {
        do_save_click(110,$(this).attr('log_data'));
    })
    $('.md_hbjob_index').click(function () {
        do_save_click(111,$(this).attr('log_data'));
    })

    $('.md_hbjob_resume_mg').click(function () {
        do_save_click(113,$(this).attr('log_data'));
    })

    $('.md_hbjob_invite_mg').click(function () {
        do_save_click(114,$(this).attr('log_data'));
    })
    $('.md_hbjob_resume_search').click(function () {
        do_save_click(115,$(this).attr('log_data'));
    })
    $('.md_hbjob_person_say').click(function () {
        do_save_click(116,$(this).attr('log_data'));
    })
    $('.md_hbjob_job_apprise').click(function () {
        do_save_click(117,$(this).attr('log_data'));
    })
    $('.md_hbjob_jztg').click(function () {
        do_save_click(118,$(this).attr('log_data'));
    })
    $('.md_hbjob_see_home').click(function () {
        do_save_click(119,$(this).attr('log_data'));
    })
    $('.md_movie_school').click(function () {
        do_save_click(417,$(this).attr('log_data'));
    })
    $('.md_data_statics').click(function () {
        do_save_click(419,$(this).attr('log_data'));
    })




    function do_save_click(log_type,log_data) {
        var img = new Image();

        var _static_visit_sys = '';
        if ("ontouchstart" in window) {
            _static_visit_sys = isWeiXin() ? 'weixin':'mobile';//移动端 -  //区分 触屏端  微信

        } else {
            _static_visit_sys = 'pc';
        }
        var area_id = _static_visit_sys=='pc' ? getCookie('ip_area_info'):getCookie('M_area_info');
        area_id = area_id ? area_id : '0300';
        img.src = action_url + "/js/action_log.js?v="+ Math.random() +'&'+ $.param({
            log_type: log_type,
            log_data: log_data,
            visit_sys: _static_visit_sys,
            area_id: area_id
        });

    }
    function isWeiXin(){
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){
            return true;
        }else{
            return false;
        }
    }

    function getCookie(name)
    {
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg))
            return unescape(arr[2]);
        else
            return null;
    }
</script>


<!--<script type="text/javascript">
hbjs.use('@confirmBox', function(m) {
    var $ = m['jquery'];

    $('.compClose').click(function(){
        $('.banner').slideUp('slow');
    });

    $('.tabList2').click(function(){
        $(this).addClass('cut');
        $('.tabList1').removeClass('cut');
        $('.tabList3').hide();
        $('.tabList4').show();
    });

    $('.tabList1').click(function(){
        $(this).addClass('cut');
        $('.tabList2').removeClass('cut');
        $('.tabList4').hide();
        $('.tabList3').show();
    })
});
</script>-->

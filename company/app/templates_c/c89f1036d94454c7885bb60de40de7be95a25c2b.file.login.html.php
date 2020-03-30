<?php /* Smarty version Smarty-3.0.7, created on 2020-03-16 18:22:30
         compiled from "app\templates\login.html" */ ?>
<?php /*%%SmartyHeaderCode:259845e6f536612a799-97701436%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c89f1036d94454c7885bb60de40de7be95a25c2b' => 
    array (
      0 => 'app\\templates\\login.html',
      1 => 1584332292,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '259845e6f536612a799-97701436',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
if (!is_callable('smarty_modifier_replace')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.replace.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="Keywords" content="" />
<title><?php echo $_smarty_tpl->getVariable('title')->value;?>
</title>
<meta name="description" content="汇博网为企业提供网络招聘、现场招聘、校园招聘、招聘外包和HR培训等在内的全方位的人力资源服务,根据企业的不同需求提供不同的招聘服务，帮助企业轻松、高效招聘人才。" />
<!–[if lt IE9]>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>"html5.js"),$_smarty_tpl);?>
"></script>
<![endif]–>

<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
"/>
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'icons.css'),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'comlogin.css'),$_smarty_tpl);?>
" />


<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>"jquery-1.8.3.min.js"),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jplayer.js'),$_smarty_tpl);?>
"></script>

<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'jplayer.blue.monday.css'),$_smarty_tpl);?>
" />
    <style type="text/css">
        /*弹窗样式*/
        .new-sign-pop{ text-align:left; padding:0px 18px 28px 18px;width:508px; overflow:hidden; background:#fff; margin:0 auto; font-family:"微软雅黑";}
        .new-sign-pop-company-ver{ text-align:left; padding:0px 18px 28px 18px;overflow:hidden; background:#fff;font-family:"微软雅黑";}
        .new-sign-tit{height:48px; color:#444; line-height:48px; border-bottom:1px dashed #e9e8e8; font-size:16px;}
        .new-sign-tab tr th{ color:#999; text-indent:15px;font-size: 14px}
        .new-sign-tab tr td{ text-indent:15px; color:#444; overflow:hidden;font-size: 14px}
        .new-sign-tab tr td em{ color:#ff4141;}
        .new-sign-tab tr.gray td{ background:#fafafa;}
        .new-sign-char{ display:block; color:#999; font-size:12px; padding:12px 0 16px 0; text-indent:15px;}
        .new-sign-btnx{overflow:hidden;font-size: 14px}
        .new-sign-btnx a.sub-btn{ display:block;width:140px; height:40px; line-height:40px; text-align:center; color:#fff; background:#66bce4; border-radius:2px; float:left; margin-left:15px;font-size: 16px}
        .new-sign-btnx span{ display:block; float:right; color:#999; line-height:40px;margin-right:15px}
        .new-sign-btnx span a{ color:#4e92c4; padding-left:2px;}

        .new-sign-popx{padding:15px 0 0 0; padding-bottom:0px; overflow:hidden;text-align:left; font-family:"微软雅黑";font-size:14px}
        .new-sign-popx li{ color:#444; padding-bottom:20px;}
        .new-sign-popx .name{display:block;margin-left: 70px}
        .new-sign-popx li .dtit{display: inline-block;width:70px;text-align: right}
        .new-sign-popx li input{ display:inline-block; vertical-align:middle;width:240px; height:34px; border:1px solid #cfcfcf; text-indent:10px; background:none;line-height: 34px;font-size:14px}
        .new-sign-popx li a.present,.new-sign-popx li a.gray{width:120px; height:30px; background:#66bce4; color:#fff; text-align:center; line-height:30px; display:inline-block; margin-right:36px;border-radius:2px;}
        .new-sign-popx li a.gray{ color:#999; background:#f0f0f0;margin-left:30px;margin-right:0px}
        .new-sign-popx li a.code-btn{ display:inline-block;padding:0 15px;height:26px; border:1px solid #58cbe0; background:#58cbe0; vertical-align:middle; margin-left:20px;  #margin-left:51px;text-align:center; line-height:24px; color:#fff;}
        .new-sign-popx li.gray2{ color:#999;}
        .new-sign-popx li a.orange{ color:#ed7f5a; text-decoration:underline;}
        .new-sign-pop-dialog .new-sign-pop{width:auto;}
        .new-sign-tab tr td a{ display:inline-block;width:78px; height:26px; border:1px solid #22a2df; text-align:center; line-height:26px; text-indent:0px; border-radius:2px;font-size: 12px}
        .pact-dialog .ui_dialog_container{padding:20px;line-height:20px;}
        .formText label.txtLabel{top:6px}
        .video_link a{background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/common/login_logo.jpg) no-repeat;cursor: default;}
        .video_link a:hover{background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/common/login_logo.jpg) no-repeat;}
    </style>
    <!--<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'version.js'),$_smarty_tpl);?>
"></script>-->
    <script type="text/javascript">
        window.CONFIG = {
            HOST: '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
',
            COMBOPATH: '/js/v2/'
        }
    </script>

    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'hbjs.js,jquery.min.js,util.js,class.js,shape.js,event.js,aspect.js,attribute.js,cookie.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'global.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript">
	hbjs.loadJS('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/js/v2/cqjob/common.js');
	</script>

</head>
<body id="body">
<header id="header">
    <div class="hdCon">
        <div class="logo"><a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
"></a></div>
    </div>
</header>
<hgroup class="banner">
    <div class="bannerBox">
        <div class="video_link"><a href="javascript:;" id="iplay_id"></a></div>
        <div class="txt" style="width:370px;top:18px">
            <p class="t1" style="font-size: 26px"><em style="font-size: 33px"><?php echo $_smarty_tpl->getVariable('ShowCompanyNum')->value;?>
</em>+企业</p>
            <p class="t2" style="font-size: 20px;margin-top: -10px"><em style="font-size: 30px"><?php echo $_smarty_tpl->getVariable('ShowResumeNum')->value;?>
</em><span>求职者</span></p>
            <p class="t3" style="font-size:14px;line-height: 25px"><?php echo $_smarty_tpl->getVariable('VisitPerDay')->value;?>
日均页面浏览量，<?php echo date("Y")-2002;?>
年专注人才服务。<br />汇博人才网（原重庆人才网）让你高效、快速的招到优秀的人才。<br /><span style="display: block;margin-top: 10px">汇博猎头服务，帮您的企业快速寻猎高端人才。<br />招聘外包(RPO)，专业团队为您招聘，招聘到才付费！</span></p>
             </div>
        <form method="post" action="<?php echo smarty_function_get_url(array('rule'=>"/login/logindo"),$_smarty_tpl);?>
 " id="frmLogin">
            <div class="logBox" style="height:320px">
                <div class="con logBef" style="height:321px" id="divLogin" <?php if ($_smarty_tpl->getVariable('islogin')->value){?>style="display:none"<?php }?>>
                    <input  type="hidden" id="hddseed" name="hddseed" value="<?php echo $_smarty_tpl->getVariable('seed')->value;?>
">
                    <!--new title tab mijing-->
                    <div id="js-login-tab">
                            <style>
                                    .newm-tab-userlogin{height: 43px;border-bottom: 1px solid #dcdcdc}
                                    .newm-tab-userlogin span{padding:0 23px;display: inline-block;height: 43px;line-height: 43px;text-align: center;font-size: 16px;color: #666;font-family: "Microsoft YaHei", "微软雅黑";cursor: pointer}
                                    .newm-tab-userlogin .last{border-left:1px solid #dcdcdc;}
                                    .newm-tab-userlogin .cur{color: #2e70c1}
                                    .logBox .hd{height: 32px;padding-top: 0}
                                    .formMod{margin-bottom: 8px}
                            </style>
                            <div class="newm-tab-userlogin js-tab-title">
                                <span class="cur">快速登录</span>
                                <span class="last">用户名密码登录</span>
                            </div>
                        <?php if ($_smarty_tpl->getVariable('is_down')->value){?><div style="margin: 5px 0 0 5px; font-size: 12px; color: red;">长时间未操作或账号在其他地方登陆，请重新登录</div><?php }?>
                            <!--/new title tab mijing-->
                            <div class="js-logintab-item">
                                <p style="text-align: center;padding-top: 15px;padding-bottom: 10px;position: relative">
                                    <img id="sqrloginimg" src="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/login/Sqrcode/?ssid=<?php echo $_smarty_tpl->getVariable('phpsessid')->value;?>
" style="width: 170px;height: 170px;opacity: 1;filter:alpha(opacity=100)" />
                                    <i id="sqrloginground" style="display: none;width:30px;height: 30px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/login-ewm-succ.png) no-repeat;position: absolute;top:45%;left:45%"></i>
                                </p>
                                <p id="sqrloginmsg" style='font-size: 12px;color: #666;text-align: center;font-family: "Microsoft YaHei", "微软雅黑"'>打开汇博企业APP，扫码登录 | <a href="<?php echo smarty_function_get_url(array('rule'=>'/topicpage/mobile/app','domain'=>'main'),$_smarty_tpl);?>
/index.html?t=4" target="_blank">下载企业APP</a></p>
                            </div>

                            <div class="js-logintab-item" style="display: none"><!--这个div是我新加的-->

                                <div class="hd" style="padding-left:15px"><div class="err" id="loginMsg" ></div></div>
                                <div class="logForm" style="height:225px;padding: 0 15px 0">
                                    <div class="formMod">
                                        <span class="formText zindex"><!--<label for="txtUsername" class="txtLabel">请输入用户名</label>--><input type="text" class="text" maxlength="30" id="txtUsername" name="txtUsername" onkeydown="if(event.keyCode==13)return false;" placeholder="请输入用户名" style="width: 235px"></span>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formMod">
                                        <span class="formText zindex"><!--<label for="txtPassword" class="txtLabel">请输入密码</label>--><input type="password" class="text" maxlength="18" id="txtPassword" name="txtPassword"  onkeydown="if(event.keyCode==13)return false;" placeholder="请输入密码" style="width: 235px"></span>
                                        <div class="clear"></div>
                                    </div>

                                    <div class="formMod" id="divcode" <?php if (!$_smarty_tpl->getVariable('needCode')->value){?>style="display:none;"<?php }?>>
                                        <span class="formText zindex"><input type="text" id="txtAuthCode"  class="text" name="txtAuthCode" style="width:80px;"  /></span>
                                        <span class="yzImg"><img id="imgAuthCode" src="/login/verify?seed=<?php echo $_smarty_tpl->getVariable('seed')->value;?>
"></span>
                                        <span class="tipTxt" style="width:40px;"><a id="refreshAuthCode" href="javascript:void(0);">换一换</a></span>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formBtn" style="bottom: 53px;left: 15px"><a href="javascript:void(0)" class="btn4 btnsF16" id="btnLogin" style="background: #5ab758;box-shadow:none;padding: 0 8px"><span><i style="background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/login_btn.png) no-repeat;display: inline-block;width: 25px;height: 18px;vertical-align: -3px;"></i>登&nbsp;录</span></a><a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/company/register" class="btn3 btnsF16" style="margin-left: 0;padding-right: 0;padding-left: 8px;color: #3D84B8;text-decoration: underline">注册企业会员</a></div>
                                    <p style="position: absolute; bottom: 64px;right: 18px"><a id="forgetPass" href="javascript:;" style='color: #999;font-family: "Microsoft YaHei", "微软雅黑"'>忘记密码</a></p>
                                </div>
                            </div>
                    </div><!--这个结束div是我新加的-->

                <div class="bot"><i class="hbFntWes">&#xf095;</i><em><?php echo $_smarty_tpl->getVariable('HuiboPhone400')->value;?>
</em>
				</div>
                <div class="leftTip" id="loginHelpDiv" style="display:none">
                    <div class="bd">
                        <b class="arr"></b>
                        <p class="tit" >登录小提示</p>
                        <dl>
                        <dt>您是否锁定了键盘的大写功能？</dt>
                        <dd>请检查您键盘上的"Caps Lock"或"A"灯是否亮着，如果是，请先按一下"Caps Lock"键然后重新输入。</dd>
                        </dl>
                        <dl>
                        <dt>您是否忘记或不小心输入了错误的密码？</dt>
                        <dd>您可以通过拨打客服电话（<?php echo $_smarty_tpl->getVariable('HuiboPhone400')->value;?>
）找回密码。</dd>
                        </dl>
                        <div class="errTip orange" id="boxTipErr">5分钟内密码输错4次，您的账号将被禁用30分钟，您还有<span class="strong">3</span>次机会</div>
                    </div>
                </div>
            </div>
            <div class="con logAft" id="divLoginInfo" <?php if (!$_smarty_tpl->getVariable('islogin')->value){?>style="display:none"<?php }?>>
                <div class="hd"><h3>登录信息</h3></div>
                <div class="bd">
                    <table cellpadding="0" cellspacing="0" width="100%">
                    <tbody><tr><td valign="middle" align="left" height="153"><p>您好！<em class="orange" id="emCompanyname"><?php echo $_smarty_tpl->getVariable('company_name')->value;?>
</em></p></td></tr></tbody></table>

                    <div class="btn"><a href="<?php if ($_smarty_tpl->getVariable('is_part')->value){?><?php echo smarty_function_get_url(array('rule'=>"/part/"),$_smarty_tpl);?>
<?php }elseif((!empty($_smarty_tpl->getVariable('redirect',null,true,false)->value))){?><?php echo smarty_function_get_url(array('rule'=>($_smarty_tpl->getVariable('redirect')->value)),$_smarty_tpl);?>
<?php }else{ ?><?php echo smarty_function_get_url(array('rule'=>"/index/"),$_smarty_tpl);?>
<?php }?>" class="btn1 btnsF16">进入招聘中心</a><a href="<?php echo smarty_function_get_url(array('rule'=>"/login/dologout"),$_smarty_tpl);?>
" class="btn3 btnsF16">退出</a></div>
                </div>
                <div class="bot"><i class="hbFntWes">&#xf095;</i><em><?php echo $_smarty_tpl->getVariable('HuiboPhone400')->value;?>
</em></div>
            </div>
        </div>
        </form>
    </div>
</hgroup>
<section>

    <hgroup>
    	<h2>我们能为您提供什么服务</h2>
        <div class="tab" id="tab">
        	<div class="tabT" style="width:940px"><ul><li class="cu"><a href="javascript:void(0);" class="lnk1">网络招聘<b></b></a></li><li><a href="javascript:void(0);" class="lnk2">现场招聘会<b></b></a></li><li><a href="javascript:void(0);" class="lnk6">劳务派遣<b></b></a></li><li><a href="javascript:void(0);" class="lnk7">招聘外包<b></b></a></li><li><a href="javascript:void(0);" class="lnk4">校园招聘<b></b></a></li><li><a href="javascript:void(0);" class="lnk5">HR活动<b></b></a></li></ul><div class="clear"></div></div>
            <div class="tabC">
            	<div class="tabCon lst1">
                	<div class="txt">
                        <p>企业注册用户<?php echo $_smarty_tpl->getVariable('ShowCompanyNum')->value;?>
，有效简历逾<?php echo $_smarty_tpl->getVariable('ShowResumeNum')->value;?>
份</p>
                        <p>每日提供有效职位<?php echo $_smarty_tpl->getVariable('ShowDayJobNum')->value;?>
+条，浏览量<?php echo $_smarty_tpl->getVariable('VisitPerDay')->value;?>
+</p>
                    </div>
                </div>
                <div class="tabCon lst2" style="display:none;">
                	<div class="txt">
                        <p>每周二四六定期举办招聘会，平均每场到场求职者<?php echo $_smarty_tpl->getVariable('HuiBoFairAttendance')->value;?>
人次</p>
                        <p><?php echo $_smarty_tpl->getVariable('HuiBoFairShowRoom')->value;?>
个国际标准展位</p>
                        <p><a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/zhaopinhui/">了解更多</a></p>
                    </div>
                </div>
                <div class="tabCon lst3" style="display:none;">
                    <div class="txt">
                    	<p>人员招聘、员工档案管理、工资支付、福利管理、劳务纠纷处理、政策咨询
</p>
                        <p><a href="<?php echo smarty_function_get_url(array('rule'=>'/paiqian/','domain'=>'main'),$_smarty_tpl);?>
">了解更多</a></p>
                    </div>
                </div>
                 <div class="tabCon lst7" style="display:none;">
                    <div class="txt">
                    	<p>专业团队帮您招聘，招到再付费 ，为您从职位发布、筛选简历、组织面试直至入职，提供“起点到终点”的一站式服务。
</p>
                        <p><a href="<?php echo smarty_function_get_url(array('rule'=>'/rpo/','domain'=>'main'),$_smarty_tpl);?>
">了解更多</a></p>
                    </div>
                </div>
                <div class="tabCon lst4" style="display:none;">
                    <div class="txt">
                    	<p>合作伙伴：重庆大学、重庆师范大学、重庆交通大学、四川外语学院、西南大学、西南政法大学、重庆邮电大学、重庆工商大学</p>
                        <p><a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/xiaoyuan/">了解更多</a></p>
                    </div>
                </div>
                <div class="tabCon lst5" style="display:none;">
                    <div class="txt">
                    	<p class="strong" style="display:none">【<?php echo $_smarty_tpl->getVariable('review_activity_arr')->value[0]['date'];?>
】<?php echo $_smarty_tpl->getVariable('review_activity_arr')->value[0]['subject'];?>
（<?php echo $_smarty_tpl->getVariable('review_activity_arr')->value[0]['modus'];?>
）</p>
                        <p style="display:none">讲师：<?php echo $_smarty_tpl->getVariable('review_activity_arr')->value[0]['resume_name'];?>
</p>
                        <p style="display:none"><?php echo $_smarty_tpl->getVariable('review_activity_arr')->value[0]['course_introduction'];?>
</p>
                        <p>汇博HR学院包含知识讲座、主题沙龙、论坛公开课等，专为汇博人才网会员客户提供专业培训、资源整合和企业人力资源开发与管理系统化等服务，致力于打造人力资源智慧共享平台。</p>
                        <p><a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/hrcollege/">查看详情</a></p>
                    </div>
                </div>
            </div>
        </div>
    </hgroup>
    <hgroup>
    	<h2>我们服务过的部分企业</h2>
        <div class="comLst">
        	<ul>
            <li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/1.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/2.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/3.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/4.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/5.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/6.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/7.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/8.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/9.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/10.jpg" /></li>
            </ul>
            <div style=" overflow:hidden;width:100%;">
            	<a href="<?php echo smarty_function_get_url(array('rule'=>'/wellknown/','domain'=>'main'),$_smarty_tpl);?>
" target="_blank" style="display:inline-block; float:right; color:#333; font-size:14px; padding-right:8px;">点击查看更多<i class="hbFntWes" style="display:inline-block; vertical-align:-2px; padding-left:2px;">&#xf105;</i></a>
            </div>
            <div class="clear"></div>
        </div>
    </hgroup>
    <hgroup style="margin:0;">
    	<h2>我们的资质和荣誉</h2>
        <div class="ryLst">
        	<ul>
            <li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/zs1.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/zs2.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/zs3.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/zs4.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/zs5.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/zs6.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/zs7.jpg" /></li><li><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/login/zs8.jpg" /></li>
            </ul>
            <div class="clear"></div>
        </div>
    </hgroup>
</section>
<a target="_blank" class="feedback feedbackHelp" href="<?php echo smarty_function_get_url(array('rule'=>'/help','domain'=>'company'),$_smarty_tpl);?>
"></a>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<section class="floatRT"><a href="<?php echo smarty_function_get_url(array('rule'=>'/about/message','domain'=>'main'),$_smarty_tpl);?>
" target="_blank" class="serviceLink">我有问题要反馈</a><b></b></section>

<script type="text/javascript">

    if(window.parent.frames.length){
        window.parent.location.href = '/';
    }

hbjs.use('@jobDialog, @hbCommon, @form, @dialog', function(m){

	var $ = m['product.hbCommon'].extend(m['cqjob.jobDialog'], m['cqjob.jobForm']),
			cookie = m['tools.cookie'],
			confirmBox = m['widge.overlay.confirmBox'],
			Dialog = m['widge.overlay.hbDialog'];

		$('#tab').find('.tabT').find('li').click(function() {
			if ($(this).hasClass('cu')) {
				return false;
			} else {
				var thisIndex = $(this).index();
				$(this).addClass('cu').siblings('li').removeClass('cu');
				$('#tab').find('.tabCon').eq(thisIndex).css({'display':'block'}).siblings('.tabCon').css({'display':'none'});
			}
		});

		var companyLogin = {
			intialize: function() {
				this._initControls();
				var val = cookie.get('username');
				if (val) {
				   //$('#txtUsername').prev().hide();
				   $('#txtUsername').focus().val(decodeURIComponent(decodeURIComponent(val)));
				}
			},
			_initControls: function(){

				$('#btnLogin').click(function(event) {
					event.preventDefault();
					companyLogin.login(this);
				});
				//$('#txtUsername').focus();
				$('#frmLogin').find(':input').keydown(function(event) {
					var e = $.event.fix(event);
					if (e.keyCode == 13) {
						$('#btnLogin').click();
					}
				});

				$("#iplay_id").click(function() {
					$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/login/playVideo/"),$_smarty_tpl);?>
', {title: '关于汇博', width: 640});
				});
				$('#divLogin').on('click', '#refreshAuthCode', function(){
					companyLogin.refreshAuthCode();
				});
			},
			login: function(el) {
				$(el).running();

				$('#loginMsg').html('');
				var err = '';
				var con = $('#frmLogin');
				var username = con.find('#txtUsername');
				var password = con.find('#txtPassword');
				var authcode = con.find('#txtAuthCode');
				if (username.val() == '') {
					err = '<i class="hbFntWes">&#xf057;</i><p>请输入用户名</p><div class="clear"></div>';
					username.focus();
				}
				else if ($.trim(username.val()).length > 30 || $.trim(username.val()).length < 2)
				{
					err = '<i class="hbFntWes">&#xf057;</i><p>请输入长度为2-30位的用户名</p><div class="clear"></div>';
					username.focus();
				}
				else if (password.val() == '') {
					err = '<i class="hbFntWes">&#xf057;</i><p>请输入密码</p><div class="clear"></div>';
					password.focus();
				}
				else if ($.trim(password.val()).length > 18 || $.trim(password.val()).length < 6) {
					err = '<i class="hbFntWes">&#xf057;</i><p>请输入长度为6-18位的密码</p><div class="clear"></div>';
					password.focus();
				}
				else if (authcode.val() == '' && authcode.is(':visible')) {
					err = '<i class="hbFntWes">&#xf057;</i><p>请输入验证码</p><div class="clear"></div>';
					authcode.focus();
				}

				if (err.length > 0) {
					$('#loginMsg').html(err);
					$(el).stopRunning();
					return;
				}

				$.ajax({
					url: "<?php echo smarty_function_get_url(array('rule'=>"/login/logindo"),$_smarty_tpl);?>
",
					data: con.serialize(),
					type: 'post',
					dataType: 'json',
					success: function(json) {
						$(el).stopRunning();
						companyLogin.loginSuccess(json,el);
					}
				});
			},
			refreshAuthCode: function() {
				var src = '/login/verify?seed=<?php echo $_smarty_tpl->getVariable('seed')->value;?>
&rand=' + Math.random() + '';
				$('#imgAuthCode').attr('src', src);
				$('#txtAuthCode').val('');
				return false;
			},
			callbacklogin: function() {
				$('#btnLogin').click();
			},
			loginSuccess: function(json, el) {
				$('#loginMsg').html('');


				if(json && json.code &&json.code==1888){
                    window.location.href = "<?php echo smarty_function_get_url(array('rule'=>"/login/forcelogin/"),$_smarty_tpl);?>
?" + "part=<?php echo $_smarty_tpl->getVariable('is_part')->value;?>
&backurl=<?php echo $_smarty_tpl->getVariable('redirect')->value;?>
&ishistoryback=false";
                    return ;
                }


				if(json && json.code && json.code=='boss_forbid'){
                    $.message(json.error, {title: '操作失败！'});
				    return ;
                }

				if (json && json.error) {
					if (json.redirect_html == 1) {
						window.location.href = "<?php echo smarty_function_get_url(array('rule'=>"/login/loginerr/"),$_smarty_tpl);?>
-error_type-" + json.error_type + "-user_id-" + json.user_id;
						return ;
					}

					$('#loginHelpDiv').hide();
					var errorStr = '<i class="hbFntWes">&#xf057;</i><p>' + json.error + '</p><div class="clear"></div>';

					$('#loginMsg').html(errorStr);
					$('#loginMsg').show();

					if (json.invaliErr) {
						$('#boxTipErr').html(json.invaliErr);
					}
					else {
						$('#boxTipErr').html('');
					}

					if (json.error == '验证码错误') {
						$('#txtAuthCode').focus();
					}
					else {
						$('#txtPassword').val('');
						$('#txtPassword').focus();
						$('#loginHelpDiv').show();
					}


					if (json.needCode) {
						$("#divcode").show();
					}

					companyLogin.refreshAuthCode();
					return;
				}

				if (json.need_update_password) {
					$(el).stopRunning();
					$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/login/passwordmod/"),$_smarty_tpl);?>
', {title: '修改密码'});
					return;
				}
                if (json.need_update_password_by_badpassword) {
					$(el).stopRunning();
					$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/login/passwordmodV2/"),$_smarty_tpl);?>
?from=badpassword&code='+json.code+"&token="+json.token, {title: '修改密码'});
					return;
				}
				if (json.success) {
					$('#btnLogin').addClass('btn4Unclick').html('正在跳转');
                    if(json.bindweixin){
                        <?php if ($_smarty_tpl->getVariable('is_part')->value){?>
                        window.location.href = "/part";
                        <?php }elseif((!empty($_smarty_tpl->getVariable('redirect',null,true,false)->value))){?>
                        window.location.href = "<?php echo smarty_function_get_url(array('rule'=>($_smarty_tpl->getVariable('redirect')->value)),$_smarty_tpl);?>
";
                        <?php }else{ ?>
                        window.location.href = "/";
                        <?php }?>
                    }else{
                        //跳转绑定微信页面
                        window.location.href = '<?php echo smarty_function_get_url(array('rule'=>"/account/BindCompanyWx/"),$_smarty_tpl);?>
?part=<?php echo $_smarty_tpl->getVariable('is_part')->value;?>
&backurl=<?php echo $_smarty_tpl->getVariable('redirect')->value;?>
';
                    }

				}
			}
		}

		companyLogin.intialize();

		$("#forgetPass").click(function(){
			showCompanyRepresentationsDialog(getComapnyRepresentationsHtml());
        });
        //忘记密码弹窗
        //申诉弹窗

        var companyRepresentationsDialog;
        function showCompanyRepresentationsDialog(content) {
            if (!companyRepresentationsDialog) {
                companyRepresentationsDialog = new Dialog({
                    idName: 'new-sign-pop-dialog2',
                    width: 438,
                    close: 'x',
                    title: "账户申诉"
                });
            }
            companyRepresentationsDialog.query('#companyRepresCommit').off('click');
            companyRepresentationsDialog.query('#companyRepresCancel').off('click');
            companyRepresentationsDialog.setContent(content);
            companyRepresentationsDialog.query('#companyRepresCommit').on('click', function () {  companyRepresentationsCommit($('#companyRepresName').val(), $('#companyRepresLinkPhone').val());
            });
            companyRepresentationsDialog.query('#companyRepresCancel').on('click', function () {
                companyRepresentationsDialog.hide();
            });

            companyRepresentationsDialog.show();
        }

        //获取企业申诉弹窗html
        function getComapnyRepresentationsHtml() {
            var tmp = [
                '<div class="new-sign-pop-company-ver" style="width:400px;padding-bottom:0px">',
                '<ul class="new-sign-popx" style="width:400px">',
                '<li>',
                '请填写以下信息进行申诉，如需帮助请致电：400-1010-970',
                '</li>',
                '<li>',
                '<span>公司名称：</span>',
                '<input id="companyRepresName" type="text" name="name" />',
                '</li>',
                '<li>',
                '<span>联系电话：</span>',
                '<input id="companyRepresLinkPhone" type="text" name="t" />',
                '</li>',
                '<li>',
                '<a id="companyRepresCommit"  href="javascript:;" class="present">提  交</a>',
                '<a id="companyRepresCancel" href="javascript:;" class="gray">取  消</a>',
                '</li>',
                '</ul>',
                '</div>'
            ].join('');
            return tmp;
        }

        function companyRepresentationsCommit(company_name, phone) {
            if (!company_name) {
                $.message('企业名称不能为空', {icon: 'warning'});
                return false;
            }

            if (!phone) {
                $.message('请输入联系电话', {icon: 'warning'});
                return false;
            }

            if(phone.length > 15 || phone.length < 8){
                $.message('联系电话请输入8-15位',{ icon: 'warning' });
                return false;
            }

            $.post('<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/login/CompanyRespreations', {
                company_name: company_name,
                mobile: phone
            }, function (res) {
                if (res.status) {
                    $.anchorMsg('您的申诉已提交成功,请耐心等待');
                    companyRepresentationsDialog.hide();
                } else {
                    $.message(res.message, {title: '操作失败！'});
                }
            }, 'json');
        }

        //这里是登录JS切换
		var placeHolderInput;
        var tabDivObj = $("#js-login-tab"),
            tabTitleObj = tabDivObj.find(".js-tab-title"),
            tabItemObj = tabDivObj.find(".js-logintab-item");
        tabTitleObj.find("span").click(function(){
            $(this).addClass("cur").siblings().removeClass("cur");
			var index = $(this).index();
            tabItemObj.hide().eq(index).show();

			if(index == 1){
				if(!placeHolderInput){
					placeHolderInput = $('#frmLogin').find(':input').placeHolder();
				}
			}

            return false;
        });

        var ssid = '<?php echo $_smarty_tpl->getVariable('phpsessid')->value;?>
';
        var sstype = "scan";
        var t = "";
        (function longPolling() {
                    clearTimeout(t);
                    $.ajax({
                        url: "<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/login/checksqrlogin",
                        data: {'ssid':ssid,"timed": Date.parse(new Date())/1000,'type':sstype},
                        dataType: "json",
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            t = setTimeout(function(){
                                longPolling();
                            },1500);
                        },
                        success: function (json, textStatus) {
                            if(json.status){
                                if(json["data"].s == 0){
                                    t = setTimeout(function(){
                                        longPolling();
                                    },1500);
                                }else if(json["data"].s == 1){
                                    $("#sqrloginimg").css({opacity:'0.2'});
                                    $("#sqrloginground").css({display:'inline-block'});
                                    $("#sqrloginmsg").html("扫描成功，请在手机端确认登录");
                                    sstype = "login";
                                    t = setTimeout(function(){
                                        longPolling();
                                    },1500);
                                }else if(json["data"].s == 2){
                                    window.location.href = "<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/login/loginbysqrcode/?ssid="+ssid+"&t="+Date.parse(new Date())/1000;
                                }else if(json["data"].s == 3){
                                    window.location.reload();
                                }
                            }else{
                                 $.message(json.msg, {title: '操作失败！',onok:function(){
                                    window.location.reload();
                                 }});
                            }
                        }
                    });

                })();

    });

</script>
</body>
</html>

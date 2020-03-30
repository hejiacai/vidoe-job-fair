<?php /* Smarty version Smarty-3.0.7, created on 2020-03-23 10:17:42
         compiled from "app\templates\public/shuanxuan/hb_header.html" */ ?>
<?php /*%%SmartyHeaderCode:156825e781c46dc1522-37484574%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4dd00b3d9d0595f75de8635889dff5ff89d9447a' => 
    array (
      0 => 'app\\templates\\public/shuanxuan/hb_header.html',
      1 => 1584689965,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '156825e781c46dc1522-37484574',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
?><style type="text/css">
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
.compNav{width:1000px; height:50px; overflow:hidden; margin:0 auto; text-align:left; position: relative;}
.compLogo{ display:block; float:left;width:144px; height:30px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/compLogo.png) no-repeat; margin:10px 0;}
.compHot{ display: block; position: absolute;top:3px; left: 515px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/www/hot.png) no-repeat;width:26px; height: 16px;}
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
<div class="compNavbg">
    <div class="compNav">
        <a href="<?php echo base_lib_Constant::MAIN_URL_NO_HTTP;?>
" class="compLogo"></a>
		<!--<span class="compHot"></span>-->
        <ul class="compList">
            <li class="md_hbjob_mrecruit tabList1"><a href="<?php echo smarty_function_get_url(array('rule'=>"/index/",'domain'=>"company"),$_smarty_tpl);?>
">全职招聘</a></li>
            <li class="md_movie_school cut2"><a href="<?php echo smarty_function_get_url(array('rule'=>'/shuangxuannet','domain'=>'company'),$_smarty_tpl);?>
">视频招聘会</a></li>
            <li class="md_lwpq"><a href="<?php echo smarty_function_get_url(array('rule'=>'/paiqian/','domain'=>'main'),$_smarty_tpl);?>
" target="_blank">劳务派遣</a></li>
            <li class="md_zpwb"><a href="<?php echo smarty_function_get_url(array('rule'=>'/rpo/','domain'=>'main'),$_smarty_tpl);?>
" target="_blank">招聘外包</a></li>
            <li class="md_hblt"><a href="<?php echo smarty_function_get_url(array('rule'=>'/lietou','domain'=>'main'),$_smarty_tpl);?>
" target="_blank">汇博猎头</a></li>
            <li class="md_account_mg"><a href="<?php echo smarty_function_get_url(array('rule'=>'/account/','domain'=>'company'),$_smarty_tpl);?>
">账户管理</a></li>
            <li class="md_free_createsit"><a href="<?php echo smarty_function_get_url(array('rule'=>'/cmscompany/','domain'=>'company'),$_smarty_tpl);?>
">免费建站</a></li>

        </ul>
    </div>
</div>
<div class="auditNotPassx">企业认证尚未通过审核，暂时无法预览主页</div>

<script type = "text/javascript" >
    window.jQuery || document.write('<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
">'+'<\/script>');

</script>
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
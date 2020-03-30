<?php /* Smarty version Smarty-3.0.7, created on 2020-03-30 09:14:54
         compiled from "app\templates\shuangxuannet/center.html" */ ?>
<?php /*%%SmartyHeaderCode:313755e81480e831b20-04240396%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '85afa3fca5d1ff2aadfe191cb3f0813deeed2a1d' => 
    array (
      0 => 'app\\templates\\shuangxuannet/center.html',
      1 => 1585390791,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '313755e81480e831b20-04240396',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_modifier_truncate')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.truncate.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $_smarty_tpl->getVariable('shaungxuan_net_info')->value['title'];?>
</title>
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
"/>
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
"/>
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'videjobfair.css'),$_smarty_tpl);?>
"/>
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'mobile.swiper.css'),$_smarty_tpl);?>
"/>
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'m_font_style.css'),$_smarty_tpl);?>
"/>
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'center.css'),$_smarty_tpl);?>
"/>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'mobile.swiper.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.cookie.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'layer.js'),$_smarty_tpl);?>
"></script>
	<link type="text/css" href="<?php echo smarty_function_version(array('file'=>'layer.css'),$_smarty_tpl);?>
">
	<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'base_script.js'),$_smarty_tpl);?>
"></script>
	<style>
		.newheadMage .login_reg u,.newheadMage .login_reg .reg_new {
			display : none;
		}
	</style>
</head>
<body>

<?php echo $_smarty_tpl->getVariable('head_data')->value;?>

<div class="businessHallTop"
	 style="background: url(<?php echo $_smarty_tpl->getVariable('shaungxuan_net_info')->value['pc_post_path'];?>
/<?php echo $_smarty_tpl->getVariable('shaungxuan_net_info')->value['pc_poster'];?>
) center top no-repeat; background-size: 100% 100%;">
    <?php if ($_smarty_tpl->getVariable('person_action_history')->value){?>
		<div class="businessHallRoll">
			<div class="imgs">
				<div class="swiper-container swiper-container-company">
					<div class="swiper-wrapper">
                        <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('person_action_history')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
?>
							<div class="swiper-slide">
								<span>
									<img src="<?php echo $_smarty_tpl->tpl_vars['val']->value['headphoto'];?>
"
										 onerror="javascript:this.src='<?php echo $_smarty_tpl->tpl_vars['val']->value['default_photo'];?>
'"/>
									<em><?php echo $_smarty_tpl->tpl_vars['val']->value['str_lable'];?>
</em>
								</span>
							</div>
                        <?php }} ?>
					</div>
				</div>
			</div>
		</div>
    <?php }?>
</div>


<div class="businessHallNum">
    <?php if ($_smarty_tpl->getVariable('shaungxuan_net_info')->value['content']){?>
		<div class="businessHallIntroduce">
			<div class="businessHallIntroduce_temp">
				<i class="introduceleft"></i>
				<i class="introduceright"></i>
				<p>
                    <?php echo smarty_modifier_truncate($_smarty_tpl->getVariable('shaungxuan_net_info')->value['content'],145,'...');?>

                    <?php if (mb_strlen($_smarty_tpl->getVariable('shaungxuan_net_info')->value['content'])>145){?>
						<a href="javascript:void(0);"
						   onclick="showDialog('活动介绍','600')">详情</a>
                    <?php }?>
				</p>
				<div class="clear"></div>
			</div>
		</div>
    <?php }?>
	<div class="businessHallNumx businessHallNumxLeft" <?php if (!$_smarty_tpl->getVariable('shaungxuan_net_info')->value['content']){?>style="border-radius: 8px 0 0 8px;"<?php }?>>
		<em>招聘企业</em>
		<span><?php echo $_smarty_tpl->getVariable('school_tip_data')->value['company_num'];?>
<i>家</i></span>
	</div>
	<div class="businessHallNumx businessHallNumxCenter">
		<em>招聘岗位</em>
		<span><?php echo $_smarty_tpl->getVariable('school_tip_data')->value['job_num'];?>
<i>个</i></span>
	</div>
    <?php if ($_smarty_tpl->getVariable('sid')->value!=70){?>
		<div class="businessHallNumx businessHallNumxRight" <?php if (!$_smarty_tpl->getVariable('shaungxuan_net_info')->value['content']){?>style="border-radius: 0 8px 8px 0;"<?php }?>>
			<em>参与求职者</em>
			<span><?php echo $_smarty_tpl->getVariable('school_tip_data')->value['person_num'];?>
<i>人</i></span>
		</div>
    <?php }?>
	<div class="clear"></div>
</div>

<?php if ($_smarty_tpl->getVariable('interviewtimenet_data')->value){?>
	<div class="various_time">
        <?php if ($_smarty_tpl->getVariable('interviewtimenet_beabout_state')->value==0){?>
			<div class="various_time_l" style="top: 31px">
				<span class="vt_title">剩余报名时间：</span>
				<span class="vt_time" id="vt_day"><?php echo $_smarty_tpl->getVariable('interviewtimenet_beabout_time')->value['day'];?>
</span><span class="vt_unit">天</span>
				<span class="vt_time" id="vt_hour"><?php echo $_smarty_tpl->getVariable('interviewtimenet_beabout_time')->value['hour'];?>
</span><span class="vt_unit">小时</span>
				<span class="vt_time" id="vt_minute"><?php echo $_smarty_tpl->getVariable('interviewtimenet_beabout_time')->value['minute'];?>
</span><span class="vt_unit">分</span>
				<span class="vt_time" id="vt_second"><?php echo $_smarty_tpl->getVariable('interviewtimenet_beabout_time')->value['second'];?>
</span><span class="vt_unit">秒</span>
			</div>
        <?php }?>
		<div class="various_time_r <?php if (!$_smarty_tpl->getVariable('interviewtimenet_beabout_state')->value==0){?>no_various_time_r_temp<?php }?>">
			<span class="vt_title vt_title_temp <?php if (!$_smarty_tpl->getVariable('interviewtimenet_beabout_state')->value==0){?>no_various_time_r<?php }?>">视频面试时间：</span>
			<div class="vt_content">
                <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('interviewtimenet_data')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
?>
					<span class="vt_delTime"><?php echo $_smarty_tpl->tpl_vars['val']->value['start_time_desc'];?>
</span>
                <?php }} ?>
			</div>
		</div>
	</div>
<?php }?>

<div class="businessHallTab">
	<a href="javascript:;" class="JobFairList_type_change <?php if ($_smarty_tpl->getVariable('JobFairList_type')->value=='company'){?>cur<?php }?>" data_id="company"><span
				class="icon-pc-enterprise pcicon_one"></span>企业大厅</a>
	<a href="javascript:;" class="JobFairList_type_change <?php if ($_smarty_tpl->getVariable('JobFairList_type')->value=='person'){?>cur<?php }?>" data_id="person"><span
				class="icon-icon_recruit_industry pcicon_two"></span>求职者大厅</a>
	<div class="clear"></div>
</div>

<div id="list_data">
    <?php echo $_smarty_tpl->getVariable('list_html')->value;?>

</div>

<!-- 浮动右边	 -->
<div class="businessHallWindow">
	<a href="javascript:;" class="companySignedUp">
		<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/videi_job_img09.png"/>企业报名
	</a>
	<a href="<?php echo $_smarty_tpl->getVariable('head_info_data')->value['company_login_url'];?>
?redirect=/shuangxuannet" class="companyLogin">
		<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/videi_job_img10.png"/>企业登录
	</a>
</div>

<!-- 企业报名弹窗 -->
<div class="m_master"></div>
<div class="companySignx">
	<div class="companySignTit">
		<span class="companySignPhone">报名联系电话：<?php echo $_smarty_tpl->getVariable('shaungxuan_net_info')->value['enter_phone_desc'];?>
</span>
		<i class="companySignClose"></i>
	</div>
	<span class="companyNameTit">企业报名</span>
	<div class="signPut">
		<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/videi_job_img20.jpg"/>
		<input type="text" name="companyName" id="companyName" placeholder="请输入企业名称" value="">
	</div>
	<div class="signPut">
		<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/videi_job_img21.jpg"/>
		<input type="text" name="companyPhone" id="companyPhone" placeholder="请输入联系电话" value="">
		<input type="hidden" name="sid" id="sid"/>
	</div>
	<a href="javascript:void(0);" class="companySignBtn">提交</a>
</div>

<!-- 企业报名悬浮底部 -->
<div class="bottombtn">
    <?php if ($_smarty_tpl->getVariable('shaungxuan_net_info')->value['activity_school_state']==2||$_smarty_tpl->getVariable('shaungxuan_net_info')->value['is_signup']==1||$_smarty_tpl->getVariable('interviewtimenet_beabout_state')->value>0||!$_smarty_tpl->getVariable('interviewtimenet_data')->value){?>
		<a href="javascript:void(0);" class="bot_companySignedUp"><span class="icon-employer_sign_off botIcon"></span>企业报名</a>
    <?php }else{ ?>
		<a href="javascript:void(0);" class="bot_companySignedUp"><span class="icon-employer_sign_off botIcon"></span>企业报名</a>
        <?php if ($_smarty_tpl->getVariable('person_enter_data')->value){?>
			<a href="javascript:void(0);" class="bot_workerSignedUp"><span class="icon-apply-succeed botIcon"></span>已报名</a>
        <?php }else{ ?>
			<a href="javascript:void(0);" class="bot_workerSignedUp"><span class="icon-apply-succeed botIcon"></span><span
						class="bot_workerSignedUp_html">求职者报名</span></a>
        <?php }?>
    <?php }?>
</div>

<div class="activityDetailsx" style="display: none;"><?php echo $_smarty_tpl->getVariable('shaungxuan_net_info')->value['content'];?>
</div>
<form id="web_subscribe_form" action="//www.huibo.com/jobsearch/" method="post"><input type="hidden" name="subscribe_origin" value="nosearchpage">
</form>
<script type="text/javascript">

    var vt_top_l_top = $('.various_time').height() - $('.various_time_l').height();
    $('.various_time_l').css('top', vt_top_l_top / 2 + 'px');
    $('.vt_title_temp').css('top', vt_top_l_top / 2 + 2 + 'px');
	
	
	var businessHallList_length = $('.businessHallList').children().length
	for(var i = 0;i < businessHallList_length;i++) {
		var businessHallLix02 = $('.businessHallList').children().eq(i).children('span').html().replace(/\s/g, "")
		$('.businessHallList').children().eq(i).children('span').html(businessHallLix02)
	}
	
    var is_IndexAutoAddEnter = Number('<?php echo $_smarty_tpl->getVariable('is_IndexAutoAddEnter')->value;?>
');
    var is_person_enter_data = Number('<?php echo $_smarty_tpl->getVariable('is_person_enter_data')->value;?>
');
    var login_dialog, show_Dialog, Dialog, jobFairCodeHtml;
    var school_source = Number('<?php echo $_smarty_tpl->getVariable('head_info_data')->value['school_source'];?>
');
    var scrollTop = Number('<?php echo $_smarty_tpl->getVariable('scrollTop')->value;?>
');
    var page = 1;
    var is_company_signup = Number('<?php echo $_smarty_tpl->getVariable('shaungxuan_net_info')->value['is_company_signup'];?>
');
    var loadingPage = false;
    var sid = Number('<?php echo $_smarty_tpl->getVariable('sid')->value;?>
');
    var user_id = Number('<?php echo $_smarty_tpl->getVariable('user_id')->value;?>
');
    var cookie_domain = '<?php echo $_smarty_tpl->getVariable('cookie_domain')->value;?>
';
    var is_businessHallListx = Number('<?php echo $_smarty_tpl->getVariable('is_businessHallListx')->value;?>
');
    $('.businessHallList .businessHallListx:nth-child(3n+2)').addClass('jobSeekersHall3n');
    var mySwiperCompany = new Swiper('.swiper-container-company', {
        autoplay: true,
        autoplayDisableOnInteraction: false,
        speed: 5000,
        loop: true,
        freeMode: true,
        slidesPerView: 6,
        slidesPerGroup: 1,
    });

    hbjs.use('@confirmBox', function (m) {

        var ConfirmBox = m['widge.overlay.confirmBox'],
            cookie = m['tools.cookie'],
            fontSize = 18,
            pWidth = 70;
        Dialog = m['widge.overlay.hbDialog'];

        var clientWidth = document.body.clientWidth;
        if (clientWidth < 1400) {
            $('.businessHallWindow').addClass('businessHallWindow1400');
        }

        // 参与视频招聘会
        var jobFairCode = new Dialog({
            close: '×',
            idName: 'jobFairCode_dialog',
            title: '参与视频招聘会',
            width: 400,
        });
        if (school_source == 1) {
            jobFairCodeHtml = '<div class="jobFairCodex"><span>因技术原因视频面试当前仅能在app中参与，请提前<i>下载</i>或<i>打开汇博app</i>。对您造成的不便，敬请谅解。</span>' +
				'<img src="/index/QrCodeScanImage?scan_url=<?php echo urlencode($_smarty_tpl->getVariable('head_info_data')->value['down_app_url']);?>
" /><em>微信扫码下载</em></div>';
        } else {
            jobFairCodeHtml = '<div class="jobFairCodex"><span>因技术原因视频面试当前仅能在app中参与，请提前<i>下载</i>或<i>打开快米app</i>。对您造成的不便，敬请谅解。</span><img src="/index/QrCodeScanImage?scan_url=<?php echo urlencode($_smarty_tpl->getVariable('head_info_data')->value['down_app_url']);?>
" /><em>微信扫码下载</em></div>';
        }
        //单位/职位点击
        $(document).on('click', '.businessHallListx', function () {
            //先登录  取消验证登录
            //          if(!user_id){
            //              $.cookie("businessHallListx:" + sid, 1, {
            //                  path: '/',
            //                  expires: 1,
            //                  domain: cookie_domain,
            //              });
            //              //login_dialog.setContent({content:  '<?php echo smarty_function_get_url(array('rule'=>"/login/Login1",'domain'=>'www'),$_smarty_tpl);?>
'});
            // 	login_dialog.setContent({content: person_login_url, 'title': '求职者登录'});
            //              login_dialog._addLoading();
            //              login_dialog.on('loadComplete', function () {
            //                  login_dialog._removeLoading();
            //              });
            //              login_dialog.show();
            //              return false;
            // }
            jobFairCode.setContent(jobFairCodeHtml).show();
        });
        //自动点击
        if (is_businessHallListx) {
            $('.businessHallListx').click();
        }

        //报名成功
        var signedUpSuccess = new Dialog({
            close: '×',
            idName: 'signedUpSuccess_dialog',
            title: '参与视频招聘会',
            width: 400,
        });
        if (school_source == 1) {
            signedUpSuccessHtml = '<div class="jobFairCodex"><span><?php if (!$_smarty_tpl->getVariable('person_enter_data')->value){?>报名成功，<?php }?>面试将于<?php echo $_smarty_tpl->getVariable('interviewtimenet_beabout')->value;?>
正式开始。<br>因技术原因视频面试当前仅能在app中参与，请提前<i>下载</i>或<i>打开汇博app</i>。对您造成的不便，敬请谅解。</span><img src="/index/QrCodeScanImage?scan_url=<?php echo urlencode($_smarty_tpl->getVariable('head_info_data')->value['down_app_url']);?>
" /><em>微信扫码下载</em></div>';
        } else {
            signedUpSuccessHtml = '<div class="jobFairCodex"><span><?php if (!$_smarty_tpl->getVariable('person_enter_data')->value){?>报名成功，<?php }?>面试将于<?php echo $_smarty_tpl->getVariable('interviewtimenet_beabout')->value;?>
正式开始。<br>因技术原因视频面试当前仅能在app中参与，请提前<i>下载</i>或<i>打开快米app</i>。对您造成的不便，敬请谅解。</span><img src="/index/QrCodeScanImage?scan_url=<?php echo urlencode($_smarty_tpl->getVariable('head_info_data')->value['down_app_url']);?>
" /><em>微信扫码下载</em></div>';
        }
        if(is_IndexAutoAddEnter && !is_person_enter_data){
            signedUpSuccess.setContent(signedUpSuccessHtml).show();
        }else if(is_person_enter_data){
            // 您已报名该场活动
			var msg = "您已报名该场活动";
            ConfirmBox.timeBomb(msg, {
                name: 'success',
                width: fontSize * msg.length + pWidth,
            });
		}

        //求职者报名
        $(document).on('click', '.bot_workerSignedUp', function () {
            if (!user_id) {
                $.cookie("IndexAutoAddEnter:" + sid, 1, {
                    path: '/',
                    expires: 1,
                    domain: cookie_domain,
                });
                //先登录
                login_dialog.setContent({content: person_login_url, 'title': '求职者登录'});
                login_dialog._addLoading();
                login_dialog.on('loadComplete', function () {
                    login_dialog._removeLoading();
                });
                login_dialog.show();
                return false;
            }
            var url = "<?php echo smarty_function_get_url(array('rule'=>"/index/IndexAutoAddEnter"),$_smarty_tpl);?>
",
                data = {sid: sid};
            ajax_request_function(url, data, {
                success: function (data) {
                    $('.bot_workerSignedUp .bot_workerSignedUp_html').html('已报名');
                    signedUpSuccess.setContent(signedUpSuccessHtml).show();
                },
            }, true);
        });

        // 填写企业报名信息
        $(document).on('click', '.companySignedUp', function () {
            $('.m_master,.companySignx').show();
        });
        $(document).on('click', '.companySignClose', function () {
            $('.m_master,.companySignx').hide();
        });
        $('body').on('click', '.companySignBtn', function () {
            var companyName = $('#companyName').val();
            var companyPhone = $('#companyPhone').val();
            var msg = '';
            if (companyName == '') {
                msg = '请输入企业名称';
                return ConfirmBox.timeBomb(msg, {
                    name: 'fail',
                    width: fontSize * msg.length + pWidth,
                });
            }
            if (companyPhone == '') {
                msg = '请输入联系电话';
                return ConfirmBox.timeBomb(msg, {
                    name: 'fail',
                    width: fontSize * msg.length + pWidth,
                });
            }
            if (is_company_signup == 0) {
                msg = '该场活动需线下人工审核，请直接联系工作人员电话报名。';
                return ConfirmBox.timeBomb(msg, {
                    name: 'fail',
                    width: fontSize * msg.length + pWidth,
                });
            }
            // else if (!(/^1\d{10}$/.test(companyPhone))) {
            //     msg = '请输入正确手机号';
            //     ConfirmBox.timeBomb(msg, {
            //         name: 'fail',
            //         width: fontSize * msg.length + pWidth,
            //     });
            // }
            var url = "<?php echo smarty_function_get_url(array('rule'=>'/index/ApplyCompanyPost'),$_smarty_tpl);?>
",
                data = {sid: sid, company_name: companyName, link_tel: companyPhone};
            ajax_request_function(url, data, {
                success: function (data) {
                    ConfirmBox.timeBomb(data.msg, {
                        name: 'success',
                        width: fontSize * data.msg.length + pWidth,
                    });
                    setTimeout(function () {
                        $('.m_master,.companySignx').hide();
                    }, 800);
                },
            });
        });

        // 提示信息
        var jobFairCode03 = new Dialog({
            close: '×',
            idName: 'jobFairCode_dialog03',
            title: '提示',
            width: 400,
            isAjax: true,
        });

        var jobFairCodexHtml1 = '<div class="companySignz" style="padding-bottom:20px;overflow: hidden;">' +
            '<span class="companySignPhone1">企业报名后才可查看求职者简历</span>' +
            '<div class="companySignBtn2-box"><a href="javascript:void(0);" class="companySignBtn2 cancel">取消</a>' +
            '<a href="javascript:void(0);" class="companySignBtn1 yes">企业报名</a><div>	' +
            '</div>';
        $(document).on('click', '.jobSeekersHall', function () {
            jobFairCode03.setContent(jobFairCodexHtml1).show();
        });

        $('body').on('click', '.yes', function () {
            jobFairCode03.setContent(jobFairCodexHtml1).hide();
            $('.m_master,.companySignx').show();
        });
        $('body').on('click', '.cancel', function () {
            jobFairCode03.setContent(jobFairCodexHtml1).hide();
        });

        //底部悬浮按钮
        $('.bot_companySignedUp').click(function () {
            $('.m_master,.companySignx').show();
        });
    });

    function showDialog(title, width) {
        var content = $('.activityDetailsx').text();
        show_Dialog = new Dialog({
            close: '×',
            idName: 'show_Dialog_dialog',
            title: title,
            content: '<div class="introduceContent">' + content + '</div>',
            width: width ? width : 400,
        });
        show_Dialog.show();
    }

    $(function () {
        //企业大厅--求职大厅 切换
        $('.JobFairList_type_change').on('click', function () {
            var type = $(this).attr('data_id');
            $.cookie("JobFairList_type", type, {path: '/', expires: 1});
            $('.JobFairList_type_change').removeClass('cur');
            $(this).addClass('cur');
            page = 1;//重置翻页
            loadingPage = false;
            GetListData(true);
        });

        //滑动翻页
        $(document).on('scroll', function () {
            if ($('.JobFairList_type_change.cur').attr('data_id') == 'person') {
                if (typeof init_loadingPage == 'number' && init_loadingPage == 1) {
                    return false;
                }
                if (loadingPage == 2) {
                    // $('.myLoading').html('到底了');
                    return false;
                }
                if (loadingPage == 2) {
                    // $('.myLoading').html('到底了');
                    return false;
                }
                if (loadingPage == 1) {
                    return false;
                }
                if ($(document).scrollTop() > $('body').height() - 30 - $(window).height()) {
                    page++;
                    loadingPage = 1;
                    GetListData();
                }
            }
        });

        if (scrollTop) {
            $('body,html').animate({
                scrollTop: 0,
            }, 500);
        }


		

        // 倒计时
        var day = parseInt($('#vt_day').html());
        var hour = parseInt($('#vt_hour').html());
        var minute = parseInt($('#vt_minute').html());
        var second = parseInt($('#vt_second').html());
        var total = (second + minute * 60 + hour * 60 * 60 + day * 24 * 60 * 60) * 1000;
        console.log(total);
        // countFunc(total);
        timeId = setInterval(function () {
            total = total - 1000;
            countFunc(total);
        }, 1000);

        function checkTime(i) { //将0-9的数字前面加上0，例1变为01
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }

        function countFunc(remainingTime) {
            if (remainingTime >= 0) {
                var days = parseInt(remainingTime / 1000 / 60 / 60 / 24, 10); //计算剩余的天数
                var hours = parseInt(remainingTime / 1000 / 60 / 60 % 24, 10); //计算剩余的小时
                var minutes = parseInt(remainingTime / 1000 / 60 % 60, 10); //计算剩余的分钟
                var seconds = parseInt(remainingTime / 1000 % 60, 10); //计算剩余的秒数
                days = checkTime(days);
                hours = checkTime(hours);
                minutes = checkTime(minutes);
                seconds = checkTime(seconds);
                $("#vt_day").html(days);
                $("#vt_hour").html(hours);
                $("#vt_minute").html(minutes);
                $("#vt_second").html(seconds);
            } else {
                clearInterval(timeId);
                $("#vt_day").html("00");
                $("#vt_hour").html("00");
                $("#vt_minute").html("00");
                $("#vt_second").html('00');
            }
        }
    });

    //列表翻页
    function GetListData(is_html) {
        $.ajax({
            type: 'post',
            url: "<?php echo smarty_function_get_url(array('rule'=>'/index/JobFairListAjax'),$_smarty_tpl);?>
",
            data: {sid: '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
', page: page},
            dataType: 'json',
            success: function (dataObj) {
                if (dataObj.code == 200) {
                    if (dataObj.data.list_html) {
                        if (is_html) {
                            $('#list_data').html(dataObj.data.list_html);
                        } else {
                            if (typeof init_loadingPage_nodata == 'number' && init_loadingPage_nodata == 1) {
                                $('#list_data').append(dataObj.data.list_html);
                            } else {
                                $('#list_data .businessHallList').append(dataObj.data.list_html);
                            }

                        }
                        setTimeout(function () {
                            if (loadingPage != 2) {
                                loadingPage = false;
                            }
                        }, 300);
                    }
                } else {
                    msg_error(dataObj.msg);
                }
            },
        });
    }
</script>

</body>
</html>

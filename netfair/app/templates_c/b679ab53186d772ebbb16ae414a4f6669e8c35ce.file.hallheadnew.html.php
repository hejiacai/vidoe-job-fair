<?php /* Smarty version Smarty-3.0.7, created on 2020-03-30 09:42:27
         compiled from "app\templates\videohall/hallheadnew.html" */ ?>
<?php /*%%SmartyHeaderCode:147965e814e8308f346-16908437%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b679ab53186d772ebbb16ae414a4f6669e8c35ce' => 
    array (
      0 => 'app\\templates\\videohall/hallheadnew.html',
      1 => 1585532410,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '147965e814e8308f346-16908437',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?>
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'mobile.swiper.css'),$_smarty_tpl);?>
">
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'mobile.swiper.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript"  src='<?php echo smarty_function_version(array('file'=>"aliyun-webrtc-sdk-1.12.0.js"),$_smarty_tpl);?>
'></script>
<div class="videoTopJobFair">
	<div class="imgs">
		<div class="swiper-container swiper-container-company">
			<div class="swiper-wrapper">
			<?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('person_action_history')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
?>
				<div class="swiper-slide">
					<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
					<span>
						<img src="<?php echo $_smarty_tpl->tpl_vars['v']->value['headphoto'];?>
" onerror="nofind('<?php echo $_smarty_tpl->tpl_vars['v']->value['default_photo'];?>
');"/>
						<em><?php echo $_smarty_tpl->tpl_vars['v']->value['str_lable'];?>
</em>
					</span>
					<?php }} ?>
				</div>
			<?php }} ?>
			</div>
		</div>
	</div>
	
    <div class="videoTopJobFairMain">
<!--        <div class="logo_img" <?php if (!$_smarty_tpl->getVariable('s_logo')->value){?>style="display:none;"<?php }?>>
            <img  src="<?php echo $_smarty_tpl->getVariable('s_logo')->value;?>
" class="video_top_img"/>
        </div>-->
		
        <div class="videonewTopRt">
			<h2><?php echo $_smarty_tpl->getVariable('s_title')->value;?>
</h2>
			<p>时间：<?php echo $_smarty_tpl->getVariable('shuang_xuan_time_str')->value;?>
</p>
			<p>规模：已报名<?php echo $_smarty_tpl->getVariable('company_num')->value;?>
家企业    <?php echo $_smarty_tpl->getVariable('job_num')->value;?>
个岗位     <?php echo $_smarty_tpl->getVariable('apply_num')->value;?>
人报名<a href="javascript:;" class="scaleMore" <?php if (empty($_smarty_tpl->getVariable('s_content',null,true,false)->value)){?>style="display:none;"<?php }?>>详情</a></p>
		</div>
    </div>
</div>
<div class="videoNav">
    <div class="videoNavMain">
		<div class="videoNavlf">
			<a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/VideoInterviewHall'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
" <?php if ($_smarty_tpl->getVariable('par')->value=='视频面试大厅'){?>class="cur"<?php }?>>视频面试大厅</a>
			<a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/JobWanters'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
" <?php if ($_smarty_tpl->getVariable('par')->value=='求职者大厅'){?>class="cur"<?php }?>>求职者大厅</a>
			<a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/InterviewList'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
" <?php if ($_smarty_tpl->getVariable('par')->value=='面试结果'){?>class="cur"<?php }?>>面试结果</a>
			<a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/Jobs'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
" <?php if ($_smarty_tpl->getVariable('par')->value=='招聘职位'){?>class="cur"<?php }?>>招聘职位</a>
		</div>
		<div class="videoNavrt">
			<span>今日</span>
			<p>
			</p>
			<span>名求职者已入场</span>
		</div>
    </div>
	<div class="videoNavMain-line" style="<?php if ($_smarty_tpl->getVariable('par')->value=='求职者大厅'){?>background-color: #f6f7f8;<?php }?>"></div>
</div>

<div style="position: relative;width: 100%;">	
	<img class="bg-cloud1" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/bg-cloud_01.png" alt="背景图">
	<img class="bg-cloud2" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/bg-cloud_02.png" alt="背景图">
	<img class="bg-cloud3" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/bg-cloud_03.png" alt="背景图">
</div>
<!-- 详情浮层 -->
<span class="msgIcon"></span>
<div class="scaleMorePop">
	<div><?php echo $_smarty_tpl->getVariable('s_content')->value;?>
</div>
</div>
<div id="public_dialog_kmAPP" style="display: none;">
	<ul class="public_dialog_kmAPP_ul">
		<li>
			<div class="lyldialog_app-box clearfix">
				<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/blue/company/android1.png" />
				<p style="font-size: 14px;color: #333"></p><p class="wxTip">微信扫码下载APP</p>
				<span style="font-size: 12px;color: #666"></span>
			</div>
		</li>
		<li>
			<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/blue/netfair/km_rq_help_right.jpg" >
		</li>
		<li>
			<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/blue/netfair/km_rq_help.jpg" >
		</li>
	</ul>
</div>
<!-- 新手教程 -->
<div class="showPopType"  style="display:none;">
	<div class="showPopType-box">
		<span class="pc_apply">电脑面试</span>
		<span class="phone_apply">手机面试</span>
	</div>
</div>
<div class="phone_pop">
	<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_one.jpg" id="phone_pop_bg_img" data-step="one" alt="">
	<!-- <button class="phone_next">下一步</button> -->
	<div class="phone_next next_pop_ctent"></div>
</div>
<div class="computer_pop">
	<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_one.jpg" id="computer_pop_bg_img" data-step="one" alt="">
	<!-- <button class="computer_next">下一步</button> -->
	<div class="computer_next next_pop_ctent"></div>
</div>
<div class="kuaimi_pop">
	<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_one.jpg" id="kuaimi_pop_bg_img" data-step="one" alt="">
	<!-- <button class="kuaimi_next">下一步</button> -->
	<div class="kuaimi_next next_pop_ctent"></div>
</div>
<!-- 入场检测弹出层 -->

<div id="check_come">
	<div class="check_come-box">
	 <form action="" method="get">
	 <div class="setTime">
		 <div class="settime_top">
		 <div class="setTime_left">
			 <span class="szsj">第一步：设置面试时间</span>
             <span class="spn_right">求职者仅能在该时间内申请视频面试</span>
             
		 </div>
		 <a href="javascript:void(0);" id="setButton" class="setInterviewTime">
		 	设置
		 </a>
		 </div>
		 <div class="time_selet">
		     <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('time_list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
		     <!-- <p><?php echo $_smarty_tpl->tpl_vars['v']->value['date'];?>
  <?php echo $_smarty_tpl->tpl_vars['v']->value['time_type_str'];?>
   <?php echo $_smarty_tpl->tpl_vars['v']->value['time_str'];?>
</p> -->
				 <!-- <table>
					 <tbody>
						 <tr>
							 <td class="show_time"><?php echo $_smarty_tpl->tpl_vars['v']->value['date'];?>
  <?php echo $_smarty_tpl->tpl_vars['v']->value['time_type_str'];?>
   <?php echo $_smarty_tpl->tpl_vars['v']->value['time_str'];?>
</td>
							 <td class="no_start"><?php echo $_smarty_tpl->tpl_vars['v']->value['status_str'];?>
</td>
							 <?php if ($_smarty_tpl->tpl_vars['v']->value["status_str"]!="进行中"){?><td><a href="javascript:editTime(<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
);" class="setInterviewTime">修改</a></td><?php }?>
						 </tr>
					 </tbody>
				 </table> -->
					 <div class="time_li">
						 <div class="time_li_left">
							 <span class="show_time"><?php echo $_smarty_tpl->tpl_vars['v']->value['date'];?>
  <?php echo $_smarty_tpl->tpl_vars['v']->value['time_type_str'];?>
   <?php echo $_smarty_tpl->tpl_vars['v']->value['time_str'];?>
</span>
							 <span class="no_start"><?php echo $_smarty_tpl->tpl_vars['v']->value['status_str'];?>
</span>
						 </div>
						 <div class="time_li_right">
							 <?php if ($_smarty_tpl->tpl_vars['v']->value["status_str"]!="进行中"){?><a href="javascript:editTime(<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
);" class="setInterviewTime">修改</a><?php }?>
						 </div>
					 </div>
		     <?php }} ?>
		 </div>
	 </div>
	
	 <div class="check_content">
		  
		 <!-- <div class="content">选择面试方式</div> -->
		 <div class="radio_vid">
			 <div class="content">第二步：选择面试方式</div>
			 <div class="radio_check" <?php if ($_smarty_tpl->getVariable('source')->value==2){?>style="display:none;"<?php }?>><i class="radio_click" data-type = "1"></i><em>电脑面试</em><span style="color: red; margin-left: 10px;">需准备摄像头、麦克风、耳机设备</span></div>
			 <div class="radio_check"><i class="radio_click cur" data-type = "2"></i><em>手机面试</em><span style="color: red; margin-left: 10px;">需下载手机app</span></div>			 
		 </div>
		 <!-- <div class="radio_vid"></div> -->
	 </div>
		<div class="videoPopBtn">
			<a href="javascript:void(0);" id="saveState" data-type = "2">确定</a>
		</div>
		</form>
		 </div>
</div>

<div id="xieyi" style="display: none;">
	<div>
	<div class="xieyi_title">
		为保障活动顺利开展，面试当天及时处理以下情况：
	</div>
	<div class="xieyi_content">
		<p>1.面试当天，如有求职者申请，请及时处理</p>
		<p>2.初面通过的求职者，请及时邀约复面，并反馈结果</p>
		<div class="showTi">如累计5个求职者超过30分钟未处理，将取消本场及后续场次参会资格</div>
		<div class="lookRule"><i class="is_agree_deal" data-type = "0"></i><span>我已阅读并同意该协议</span></div>
	</div>
	<div class="videoPopBtn">
		<a href="javascript:void(0)" id="comeIn">
			进入招聘会
		</a>
	</div>
	</div>
</div>

<div class="deviceCheckDialog" style="display:none;">
	<div class="deviceCheckDialog-box">
		<p class="video-device">
			<em>请插入:</em>
			<span class="devicefail j_videoDevice_text">摄像头<i class="deviceIcon failIcon j_videoDevice"></i></span>
			<span class="devicefail j_audioDevice_text">麦克风<i class="deviceIcon failIcon j_audioDevice"></i></span>
			<span class="devicefail j_audioDevice_text">耳机<i class="deviceIcon failIcon j_audioDevice"></i></span>
			<!-- <span id="j_browser_text" class="devicefail j_browser_text">浏览器<i id="j_browser" class="deviceIcon devicefail j_browser"></i></span> -->
			<span class="againCheckBtn">重新检测</span>
		</p>
		<div class="RQ-box">
			<div class="saomaTip">汇博企业APP 扫码面试</div>
			<img id='rtcScanCodeImg' src="<?php echo smarty_function_get_url(array('rule'=>'/video/RtcScanCode'),$_smarty_tpl);?>
?job_id=<?php echo $_smarty_tpl->getVariable('base_job_id')->value;?>
&resume_id=<?php echo $_smarty_tpl->getVariable('base_resume_id')->value;?>
&net_apply_id=<?php echo $_smarty_tpl->getVariable('apply_id')->value;?>
" >
			<div class="noHuiboAPP">没有汇博APP？<a href="javascript:void(0);" id="downloadErWeiMaBtn">点击下载</a></div>
		</div>
	</div>
</div>
<div class="downloadErWeiMaDialog" style="display:none;">
	<div class="downloadErWeiMaDialog-box">
		<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_code_new.jpg" />
		<p>微信扫一扫</p>
	</div>
</div>
<?php if ($_smarty_tpl->getVariable('cur')->value){?>
<div class="videoImproveEquipment videoImproveEquipmentCheck" id="videoCheckTopDiv" style="display:block; <?php if (!$_smarty_tpl->getVariable('source')->value==2&&$_smarty_tpl->getVariable('chat_type')->value==1){?>display: none;<?php }?>">
				<!-- <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon04.png" > -->
			    <?php if ($_smarty_tpl->getVariable('source')->value==2){?>
			        <?php if ($_smarty_tpl->getVariable('has_login')->value){?>
			            <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/netfair_check_warn.gif" ><em>面试时</em><span class="color-red fw14Bold">点击“手机面试”</span><em>，打开app扫码功能，</em><span class="color-red fw14Bold">扫描二维码</span><em>即可面试</em>
			        <?php }else{ ?>
			            <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/netfair_check_warn.gif" ><em>您还未下载快米app，</em><span class="color-red fw14Bold">无法进行视频面试</span><a href="javascript:void(0);" id="showkm_RQbtn" class="ahref mar-lef">下载</a>
			        <?php }?>
			    <?php }else{ ?>
			        <?php if ($_smarty_tpl->getVariable('chat_type')->value==1){?>
				<!-- 面试前插上<em class="red">摄像头</em>与<em class="red">耳机</em>，并增强<em class="red">麦克风</em>，以免求职者无法与你沟通<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon23.png" class="queryIcon" > -->
			    <!-- 电脑设备不完善（根据检测的不完善设备动态显示相关设备）：善摄像头、麦克风、耳机设备，无法面试
			    电脑设备已完善：全部做成勾选项 -->
					<p class="video-device video_left" style="<?php if (!$_smarty_tpl->getVariable('source')->value==2&&$_smarty_tpl->getVariable('chat_type')->value==1){?>display: none;<?php }?>">
						<!-- <span>请插入: </span> -->
						<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/netfair_check_warn.gif" >
						<em>无法视频面试,</em><span>请连接:</span>
						<img class="videoStatus" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/netfair_check_01.png" >
						<span class="videoStatus">摄像头</span>
						<img class="maikeStatus" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/netfair_check_02.png" >
						<span class="maikeStatus">麦克风</span>
						<img class="voiceStatus" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/netfair_check_03.png" >
						<span class="voiceStatus">耳机</span>
					</p>
			        <?php }elseif($_smarty_tpl->getVariable('has_login')->value){?>
			        <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/netfair_check_warn.gif" ><em>面试时</em><span class="color-red fw14Bold">点击“手机面试”</span><em>，打开app扫码功能，</em><span class="color-red fw14Bold">扫描二维码</span><em>即可面试</em>
                    <a href="javascript:void(0);" id="showkm_huiboRQbtn" class="ahref mar-lef">下载</a>
			        <?php }else{ ?>
			        <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/netfair_check_warn.gif" ><em>您还未下载汇博app，</em><span class="color-red fw14Bold">无法进行视频面试</span><a href="javascript:void(0);" id="showkm_huiboRQbtn" class="ahref mar-lef">下载</a>
			        <?php }?>
			    <?php }?>
			</div>
<div class="videoIhalMain secondinterview">
	<div class="guide-tabbar">
		<div class="guide-tabbar-li <?php if ($_smarty_tpl->getVariable('cur')->value=='初面'){?>guide-tabbar-liActive<?php }?>" id="viedoOne"><a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/VideoInterviewHall'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
"><?php echo $_smarty_tpl->getVariable('interview_one_num')->value;?>
人待初面</a></div>
		<div class="guide-icon">></div>
		<div class="guide-tabbar-li <?php if ($_smarty_tpl->getVariable('cur')->value=='复面'){?>guide-tabbar-liActive<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/VideoInterviewBySecond'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
"><?php echo $_smarty_tpl->getVariable('interview_second_num')->value;?>
人待复面</a></div>
		<div class="guide-icon">></div>
		<div class="guide-tabbar-li <?php if ($_smarty_tpl->getVariable('cur')->value=='通过'){?>guide-tabbar-liActive<?php }?>"><a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/VideoInterviewBySecond'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
&type=2"><?php echo $_smarty_tpl->getVariable('interview_second_pass_num')->value;?>
人面试通过</a></div>
		<div class="but_list">
            <a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/Questions'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
" target="_blank"><button type="button" class="statSearchBtn" id="comproblem"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/netfair_check_questIcon.png">常见问题</button></a>
            <button type="button" class="statSearchBtn" id="interviewps"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/netfair_check_guide.png" >新手教程</button>
        </div>
	</div>
<?php }?>

<script type="text/javascript">
			var aliWebrtc = new AliRtcEngine();
	var downloadErWeiMaDialog=null,deviceCheckDialog=null;
    var interviewTimeDialog;
    function editTime(id){
        interviewTimeDialog.setContent('<?php echo smarty_function_get_url(array('rule'=>"/videohall/ModCompanyInterviewTime"),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
&id='+id).show();
    }
	hbjs.use('@confirmBox, @jobDialog', function (m) {
	    var ConfirmBox = m['widge.overlay.confirmBox'],
	        Dialog = m['widge.overlay.hbDialog'],
	        cookie = m['tools.cookie'],
	        $ = m['jquery'].extend(m['cqjob.jobDialog']);
			var close = '×';
			var width = 600;
			var zIndex = 9999;
		var public_dialog_kmAPP = new Dialog({
			idName: 'public_dialog_kmAPP',
			title: 'APP下载',
			width: 700,
			close: 'x',
			zIndex: 9999,
			content:$('#public_dialog_kmAPP').html(),
			isAjax: true
		});
		
		var checkCome = new Dialog({
			idName: 'check_come',
			title: '活动设置',
			width: 600,
			zIndex: 9999,
			content:$('#check_come').html(),
			isAjax: true
		});
		var checkXieyi = new Dialog({
			idName: 'xieyi',
			title: '活动举办协议',
			width: 600,
			zIndex: 9999,
			content:$('#xieyi').html(),
			isAjax: true
		});

        interviewTimeDialog = new Dialog({
            close: close,
            idName: 'informTraining_dialog',
            title: '面试时间设置',
            width: width,
            zIndex: zIndex
        });
		downloadErWeiMaDialog = new Dialog({
		    close: close,
		    idName: 'downloadErWeiMaDialog',
		    title: '汇博APP下载',
		    width: 350,
		    zIndex: zIndex,
			isAjax: true,
		    content:$(".downloadErWeiMaDialog").html(),
		});
		deviceCheckDialog = new Dialog({
		    close: close,
		    idName: 'deviceCheckDialog',
		    title: '无法检测到设备',
		    width: 424,
		    zIndex: zIndex,
			isAjax: true,
		    content:$(".deviceCheckDialog").html()
		});
		deviceCheckDialog.query('#downloadErWeiMaBtn').on('click', function(){
			deviceCheckDialog.hide();
		    downloadErWeiMaDialog.show();
		});
		deviceCheckDialog.query('.againCheckBtn').on('click', function(){
			deviceCheckDialog.hide();
			var setT = setTimeout(function(){
				deviceCheckDialog.show();
				checkStyle(true,true);
				clearTimeout(setT);
			},500)
		});
		var checkBlowsDialog = new Dialog({
			idName: 'checkBlowsDialog',
			title: '提示',
			width: 350,
			zIndex: zIndex,
			content:'<div class="checkBlowsDialog-box"><div class="checkBlows-con">您的浏览器型号或版本过低，部分功能可能无法使用，建议您更换谷歌浏览器</div><div class="videoPopBtn"><a href="javascript:<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/down/chrome.exe;">下载谷歌浏览器</a></div></div>'
		});
		var seleType = new Dialog({
		    close: close,
		    idName: 'showType',
		    title: '面试方式',
		    width: 400,
		    zIndex: zIndex,
			content:$(".showPopType").html()
		});
		if(isIE()){
			checkBlowsDialog.show();
			return false;
		}
		<?php if ($_smarty_tpl->getVariable('cur')->value){?>
		$('.pc_apply').click(function(){
			$('.computer_pop').show();
			$('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_one.jpg");
			seleType.hide()
		});
		$('.phone_apply').click(function(){
			$('.phone_pop').show();
			$('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_one.jpg");
			seleType.hide()
		});
		$('#interviewps').click(function(){
			var popType_t = '<?php echo $_smarty_tpl->getVariable('source')->value;?>
'
			if(popType_t == 1){
				seleType.show()
			}else{
				$('.kuaimi_pop').show();
				$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_one.jpg");
			}
		});
		$('#comproblem').click(function(){
			
		});
		$('.computer_next').click(function(){
			var step = $('#computer_pop_bg_img').attr('data-step')
			switch(step) {
			     case 'one':
					$('#computer_pop_bg_img').attr('data-step','tow')
					$('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_tow.jpg");
			        $(this).css({'top':'77.2%','left':'59.4%'})	
					break;
			     case 'tow':
					 $('#computer_pop_bg_img').attr('data-step','tree')
					 $('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_tree.jpg");
					 $(this).css({'top':'29%','left':'41.9%'})
			        break;
				case 'tree':
					$('#computer_pop_bg_img').attr('data-step','four')
					$('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_four.jpg");
					$(this).css({'top':'46.1%','left':'42.5%'})
					// $(this).text('完成')
				   break;
			   case 'four':
					SaveAction();
					$('.computer_pop').hide()
					$('#computer_pop_bg_img').attr('data-step','one')
					$(this).css({'top':'52.2%','left':'42.4%'})
					// $(this).text('下一步')
				  break;
			     default:
			        console.log('hao')
			}
		});
		$('.kuaimi_next').click(function(){
			var step = $('#kuaimi_pop_bg_img').attr('data-step')
			switch(step) {
			     case 'one':
					$('#kuaimi_pop_bg_img').attr('data-step','tow')
					$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_tow.jpg");
			        $(this).css({'top':'46.1%','left':'55%'})
					break;
			     case 'tow':
					 $('#kuaimi_pop_bg_img').attr('data-step','tree')
					 $('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_tree.jpg");
					 $(this).css({'top':'79%','left':'59%'})
			        break;
				case 'tree':
					$('#kuaimi_pop_bg_img').attr('data-step','four')
					$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_four.jpg");
					$(this).css({'top':'56%','left':'64%'})
				   break;
				case 'four':
					$('#kuaimi_pop_bg_img').attr('data-step','five')
					$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_five.jpg");
					$(this).css({'top':'59%','left':'60%'})
					// $(this).text('完成')
				   break;
			   case 'five':
					SaveAction();
					$('.kuaimi_pop').hide()
					$('#kuaimi_pop_bg_img').attr('data-step','one')
					$(this).css({'top':'50%','left':'41%'})
					// $(this).text('下一步')
				  break;
			     default:
			        console.log('hao')
			}
		});
		$('.phone_next').click(function(){
			var step = $('#phone_pop_bg_img').attr('data-step')
			console.log('第几步',step)
			switch(step) {
			     case 'one':
				 // debugger
					$('#phone_pop_bg_img').attr('data-step','tow')
					$('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_tow.jpg");
			        $(this).css({'top':'57%','left':'55%'})
					break;
			     case 'tow':
					 $('#phone_pop_bg_img').attr('data-step','tree')
					 $('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_tree.jpg");
					 $(this).css({'top':'59%','left':'55%'})
			        break;
				case 'tree':
					$('#phone_pop_bg_img').attr('data-step','four')
					$('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_four.jpg");
					$(this).css({'top':'79%','left':'60%'})
				   break;
			   case 'four':
				   $('#phone_pop_bg_img').attr('data-step','five')
				   $('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_five.jpg");
				   $(this).css({'top':'42%','left':'61%'})
					// console.log('完成了')
				  break;
			    case 'five':
				   $('#phone_pop_bg_img').attr('data-step','six')
				   $('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_six.jpg");
				   $(this).css({'top':'58%','left':'62%'})
				   // $(this).text('完成')
					// console.log('完成了')
				  break;
		      case 'six':
		          SaveAction();
				  $('.phone_pop').hide()
				  $('#phone_pop_bg_img').attr('data-step','one')
				  $(this).css({'top':'49%','left':'41%'})
				  // $(this).text('下一步')
		        break;
			     default:
			        console.log('hao')
			}
		});
		<?php }?>
		function SaveAction(){
		    <?php if (!$_smarty_tpl->getVariable('has_read_guide_pic')->value){?>
		    return false;
		    <?php }?>
		    $.post("<?php echo smarty_function_get_url(array('rule'=>'/videohall/SaveAction'),$_smarty_tpl);?>
", {sid: '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
', has_read_guide_pic: 1}, function (e) {
		         window.location.reload();
		     }, 'json');
		}
		var check_in_type = Number('<?php echo $_smarty_tpl->getVariable('check_in_type')->value;?>
');
		var isShow = '<?php echo $_smarty_tpl->getVariable('has_read_guide_pic')->value;?>
'
		var popType_t = '<?php echo $_smarty_tpl->getVariable('source')->value;?>
'
		var chat_typ =  '<?php echo $_smarty_tpl->getVariable('chat_type')->value;?>
'
		
		 console.log('弹出类型',check_in_type)
		$(document).ready(function(){
			checkStyle(false);//设备检测 勿删
			if(check_in_type== 1){
                <?php if (empty($_smarty_tpl->getVariable('time_list',null,true,false)->value)&&$_smarty_tpl->getVariable('chat_type')->value){?>
                    interviewTimeDialog.setContent('<?php echo smarty_function_get_url(array('rule'=>"/videohall/InterviewTimeList"),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
').show();
                <?php }else{ ?>
                    checkCome.show();
                <?php }?>
			}else if(check_in_type== 2){
				checkXieyi.show();
			}
        });
		// else if(check_in_type== 0 && isShow){
		// 	if(popType_t == 1){
		// 		if(chat_typ == 1){
		// 			$('.computer_pop').show();
		// 			$('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_one.jpg");
		// 		}else{
		// 			$('.phone_pop').show();
		// 			$('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_one.jpg");
		// 		}
		// 	}else{
		// 		$('.kuaimi_pop').show();
		// 		$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_one.jpg");
		// 	}
		// }
		// $(document).ready(function(){
		// 	var isShow = '<?php echo $_smarty_tpl->getVariable('has_read_guide_pic')->value;?>
'
		// 	var popType_t = '<?php echo $_smarty_tpl->getVariable('source')->value;?>
'
		// 	var chat_typ =  '<?php echo $_smarty_tpl->getVariable('chat_type')->value;?>
'
		// 	if(isShow){
		// 		if(popType_t == 1){ 
		// 			if(chat_typ == 1){
		// 				$('.computer_pop').show();
		// 				$('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_one.jpg");
		// 			}else{
		// 				$('.phone_pop').show();
		// 				$('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_one.jpg");
		// 			}
		// 		}else{
		// 			$('.kuaimi_pop').show();
		// 			$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_one.jpg");
		// 		}
		// 	}
		$('#showkm_RQbtn').on('click', function(){
			public_dialog_kmAPP.show();
		});
		$('#showkm_huiboRQbtn').on('click', function(){
			downloadErWeiMaDialog.show();
		});
		checkCome.query(".setInterviewTime").on("click", function(){
			checkCome.hide()
		});
		checkCome.query(".setInterviewTime1").on("click", function(){
			checkCome.setContent($(this).attr('data-id'))
		});
		
        checkCome.query(".radio_click").on("click", function(){
			$('.radio_click').removeClass('cur');
			$(this).addClass('cur');
			checkCome.query("#saveState").attr('data-type',$(this).attr('data-type'))
		});
		// checkCome.query("#setButton").on("click", function(){
		// 	checkCome.hide();
		// })
        checkCome.query("#saveState").on("click", function(){
            var chat_type = $(this).attr('data-type');
			console.log(chat_type)
             $.post("<?php echo smarty_function_get_url(array('rule'=>'/videohall/SaveAction'),$_smarty_tpl);?>
", {sid: '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
', chat_type: chat_type ? chat_type : 0}, function (e) {

                 if(e.status){
                     checkCome.hide();
                     checkXieyi.show();
                     return;
                 }

                 ConfirmBox.timeBomb(e.msg,{
                    name: 'fail',
                    width:'auto',
                    timeout : 2000
                });
                return;
             }, 'json');
        });
		checkXieyi.query(".is_agree_deal").on("click", function(){
			if($(this).hasClass('is_agree_dealcur')){
				$(this).removeClass('is_agree_dealcur');
				$(this).attr('data-type','0');
			}else{
				$(this).addClass('is_agree_dealcur');
				$(this).attr('data-type','1');
			}
		})
        checkXieyi.query("#comeIn").on("click", function(){
            var is_agree_deal = checkXieyi.query(".is_agree_deal").attr('data-type');
             $.post("<?php echo smarty_function_get_url(array('rule'=>'/videohall/SaveAction'),$_smarty_tpl);?>
", {sid: '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
', is_agree_deal: is_agree_deal ? is_agree_deal : 0}, function (e) {
                 if(e.status){
                     checkXieyi.hide();
					 setTimeout(function() {
						 window.location.reload();
					 },300);
					 
                     return;
                 }
                 
                 ConfirmBox.timeBomb(e.msg,{
                    name: 'fail',
                    width:'auto',
                    timeout : 2000
                });
                return;
             },  'json');
        });
	});
	
	var mySwiperCompany = new Swiper('.swiper-container-company', {
	    autoplay : true,
	    autoplayDisableOnInteraction : false,
	    speed:5000,
	    loop : true,
	    freeMode:true,
	    slidesPerView : 5,
	    slidesPerGroup : 1
	});
	
	var studentStr = '<?php echo $_smarty_tpl->getVariable('entry_num')->value;?>
';
	var studentHtml ='';
//	 var studentTime = setInterval(student,10000);
    student();
	function student(){
		studentHtml = '';
		for(var i=0;i<studentStr.length;i++){
			studentHtml += '<em>'+studentStr[i]+'</em>';
		}
		$('.videoNavrt p').html(studentHtml);
	}
	
	
	$('.imgs .swiper-slide span:odd').addClass('odd');
	$('.videoSchoolIntro a').click(function(){
		$(this).toggleClass('cur');
		if($(this).hasClass('cur')){
			$('.videoSchoolIntro span').css('height','auto');
			$('.videoSchoolIntro a').text('收起更多');
		}else{
			$('.videoSchoolIntro span').css('height','20px');
			$('.videoSchoolIntro a').text('查看更多');
		}
	});
    $('.scaleMore').on('click', function(){
        var thisTop = $(this).offset().top;
        var thisLeft = $(this).offset().left;
		$('.msgIcon').slideToggle().css({
			top:thisTop+22,
			left:thisLeft
		});
		$('.scaleMorePop').slideToggle().css('top',thisTop+32);
    });
    
    function nofind(img){
        var imgElement = event.srcElement;
        $(imgElement).attr('src',img);
        imgElement.onerror=null;// 控制不要一直跳动
    }
	function isIE() {
		if(!!window.ActiveXObject || "ActiveXObject" in window){
		  return true;
		}else{
		  return false;
	　　 }
	}
		/*
			isShowDialog1 是否是弹窗 .默认false
			isAgainCheck 是否是重新检测,默认fasle
		*/
		function checkStyle(isShowDialog1,isAgainCheck){
			var isShowDialog = isShowDialog1 ? isShowDialog1 : false;
			var againCheck = isAgainCheck ? isAgainCheck : false;
			
			aliWebrtc.isSupport().then(function(re) {
				checkTypeStatus(re.videoDevice,re.audioDevice,isShowDialog);
				console.log('checkDevice_success->',re)
				if(againCheck && re.videoDevice && re.audioDevice){
					var setTi = setTimeout(function(){
						deviceCheckDialog.hide();
						clearTimeout(setTi);
					},500)
				}
			}).catch(function(error){
				console.log('checkDevice_error->',error);
				checkTypeStatus(error.videoDevice,error.audioDevice,isShowDialog);
			});
		}
		function checkTypeStatus(videoStatus,voiceStatus,isShowDialog){
			if(!isShowDialog){
				var source = '<?php echo $_smarty_tpl->getVariable('source')->value;?>
';
				var chat_type =  '<?php echo $_smarty_tpl->getVariable('chat_type')->value;?>
';
				if(source == '2' || chat_type != '1'){
					return false;
				}
				if(videoStatus && voiceStatus){
					$('.videoImproveEquipmentCheck').hide();
					$('.video_left').hide();
				}else{
					
					$('.videoImproveEquipmentCheck').show();
					$('.video_left').show();
					if(videoStatus){
						$(".videoStatus").hide();
					}
					if(voiceStatus){
						$(".maikeStatus").hide();
						$(".voiceStatus").hide();
					}
				}
				return false;
			}
			if(videoStatus && voiceStatus){
				$('.j_videoDevice').removeClass('failIcon').addClass('successIcon');
				$('.j_videoDevice_text').removeClass('devicefail').addClass('devicesuccess');
				$('.j_audioDevice').removeClass('failIcon').addClass('successIcon');
				$('.j_audioDevice_text').removeClass('devicefail').addClass('devicesuccess');
				$('.j_browser').removeClass('failIcon').addClass('successIcon');
				$('.j_browser_text').removeClass('devicefail').addClass('devicesuccess');
				$('.videoImproveEquipmentCheck').hide();
			}else{
				if(videoStatus){
					$('.j_videoDevice').removeClass('failIcon').addClass('successIcon');
					$('.j_videoDevice_text').removeClass('devicefail').addClass('devicesuccess');
				}else{
					$('.j_videoDevice').addClass('failIcon').removeClass('successIcon');
					$('.j_videoDevice_text').addClass('devicefail').removeClass('devicesuccess');
				}
				if(voiceStatus){
					$('.j_audioDevice').removeClass('failIcon').addClass('successIcon');
					$('.j_audioDevice_text').removeClass('devicefail').addClass('devicesuccess');
					$('.j_browser').addClass('failIcon').removeClass('successIcon');
					$('.j_browser_text').addClass('devicefail').removeClass('devicesuccess');
				}else{
					$('.j_audioDevice').addClass('failIcon').removeClass('successIcon');
					$('.j_audioDevice_text').addClass('devicefail').removeClass('devicesuccess');
					$('.j_browser').removeClass('failIcon').addClass('successIcon');
					$('.j_browser_text').removeClass('devicefail').addClass('devicesuccess');
				}
			}
			
		}
		       function checkVideo(msg,id){
				   return
		           if(!msg.isSupported){
		               if(!msg.audioDevice){
		                   $('.audioDevice').show();
		               }else{
		                   $('.audioDevice').hide();
		               }
		
		               if(!msg.videoDevice){
		                   $('.videoDevice').show();
		               }else{
		                   $('.videoDevice').hide();
		               }
		
		               if(msg.browser.toLowerCase() != 'chrome'){
		                   $('.browserDevice').show();
		               }else{
		                   $('.browserDevice').hide();
		               }
		
		               $("#videoCheckTopDiv").show();
		           }else{
		               $("#videoCheckTopDiv").hide();
		           }
		
		           if(id){
		               if(!msg.isSupported){
		                   checkVideoDialog.show();
		                   return;
		               }
		
		               $.ajax({
		                   type: 'post',
		                   url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/StartInterview"),$_smarty_tpl);?>
',
		                   data: {id: id},
		                   dataType: 'json',
		                   success: function (res) {
		                       if(res.isNeedLogin){
		                           window.location.reload();
		                           return;
		                       }
		                       if(!res.status){
		                           ConfirmBox.timeBomb(res.msg,{
		                               name: 'fail',
		                               width:'auto',
		                               timeout : 2000
		                           });
									setTimeout("window.location.reload()",2000);
		                           return;
		                       }
		//                        window.open("https:<?php echo base_lib_Constant::COMPANY_URL_NO_HTTP;?>
/chat/?resume_id=" + res.data.resume_id + "&job_id=" + res.data.job_id + "&net_apply_id=" + res.data.id + "&sid=" + res.data.sid,"_blank");
		//                        setTimeout(function(){ window.location.reload(); }, 3000);
		                       window.location.href = "https:<?php echo base_lib_Constant::COMPANY_URL_NO_HTTP;?>
/chat/?resume_id=" + res.data.resume_id + "&job_id=" + res.data.job_id + "&net_apply_id=" + res.data.id + "&sid=" + res.data.sid;
		                   }
		               });
		           }
		       }
</script>

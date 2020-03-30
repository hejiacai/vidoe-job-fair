<?php /* Smarty version Smarty-3.0.7, created on 2020-03-28 16:34:21
         compiled from "app\templates\./videohall/videointerviewhall.html" */ ?>
<?php /*%%SmartyHeaderCode:322655e7f0c0def24d2-36395793%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd6f3744ec824f61e7829735d5d063d9c5251c292' => 
    array (
      0 => 'app\\templates\\./videohall/videointerviewhall.html',
      1 => 1585384450,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '322655e7f0c0def24d2-36395793',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_modifier_date_format')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.date_format.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="Keywords" content="" />
        <meta name="Description" content="" />
		<meta name="renderer" content="webkit">
        <title>视频面试大厅</title>
        <!–[if lt IE9]>
        <script src="<?php echo smarty_function_version(array('file'=>'html5.js'),$_smarty_tpl);?>
"></script>
        <![endif]–>
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'video_eng.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'comback.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'m_font_style.css'),$_smarty_tpl);?>
">
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
">
		<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
"></script>
        
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
        <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'global.js'),$_smarty_tpl);?>
"></script>
        <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'confirmBox.js'),$_smarty_tpl);?>
"></script>
        <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'dialog.js'),$_smarty_tpl);?>
"></script>
		<style type="text/css">
			.next_pop_ctent{width: 120px;height: 50px;}
		</style>
	</head>
	<body style="background: #f7f7f9 !important;">
		<!-- <div class="phone_pop">
			<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_one.jpg" id="phone_pop_bg_img" data-step="one" alt="">
			<div class="phone_next next_pop_ctent"></div>
		</div>
		<div class="computer_pop">
			<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_one.jpg" id="computer_pop_bg_img" data-step="one" alt="">
			<div class="computer_next next_pop_ctent"></div>
		</div>
		<div class="kuaimi_pop">
			<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_one.jpg" id="kuaimi_pop_bg_img" data-step="one" alt="">
			<div class="kuaimi_next next_pop_ctent"></div>
		</div> -->
		<?php echo $_smarty_tpl->getVariable('head_data')->value;?>

        <?php echo $_smarty_tpl->getVariable('head_nav_data')->value;?>
  
        <?php $_template = new Smarty_Internal_Template('videohall/hallheadnew.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par','视频面试大厅');$_template->assign('cur','初面'); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
		<div class="videoIhalMain">
			<div class="videointerviewTitle">
				<p>
					<i></i>
					<span>即将面试</span>
					<em>按申请顺序面试</em>
				</p>
                <a <?php if (!$_smarty_tpl->getVariable('company_interview_times')->value){?>style="display:none;"<?php }?> href="javascript:void(0);" class='setInterviewTime'>修改面试时间</a>
                <?php if ($_smarty_tpl->getVariable('today_interview_time')->value){?>
                <p style="float: right">
					<em class="color_999">今日面试申请时间：<?php echo $_smarty_tpl->getVariable('today_interview_time')->value;?>
（剩余可申请名额：<?php echo $_smarty_tpl->getVariable('person_num_all')->value;?>
人）</em>
				</p>
                <?php }?>
			</div>
			<div class="interviewData firstInterviewData">
                 <?php if (!empty($_smarty_tpl->getVariable('apply_list_top_one',null,true,false)->value)){?>
				<ul>
					<li>
						<table border="" cellspacing="" cellpadding="">
							<tr>
								<td width="12%" class="firsttd"><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['user_name'];?>
</td>
								<td width="6%"><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['sex'];?>
</td>
								<td width="6%"><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['age'];?>
</td>
								<td width="8%"><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['degree_name'];?>
</td>
								<td width="20%"><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['school'];?>
</td>
								<td width="18%"><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['major_desc'];?>
</td>
								<td width="40%" class="inviteViewJob">面试职位: <?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['station'];?>
</td>
								<!-- <td width="100" style="text-align: center;"><span class="status-daiding">待定</span><span class="statusdaiding-success">通过</span></td> -->
							</tr>
						</table>
						<div class="personInfo">
							<div class="personInfo-left">
								<div class="person-left personIcon">
									<img src="<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['headphoto'];?>
" onerror="javascript:nofind('<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['default_photo'];?>
')"/>
									<!-- <img src="http://assets.hb.com/img/company/video/defaultMan.png" > -->
								</div>
								<div class="person-left personExperience">
									<?php if ($_smarty_tpl->getVariable('apply_list_top_one')->value['work_year_str']){?><h4><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['work_year_str'];?>
</h4><?php }?>
									<?php if ($_smarty_tpl->getVariable('apply_list_top_one')->value['work_list']){?>
										<?php  $_smarty_tpl->tpl_vars['work'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('apply_list_top_one')->value['work_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['work']->key => $_smarty_tpl->tpl_vars['work']->value){
?>
										<p><span class="jobName"><?php echo $_smarty_tpl->tpl_vars['work']->value['station'];?>
</span><span class="jobCompanyName"><?php echo $_smarty_tpl->tpl_vars['work']->value['company_name'];?>
</span><span class="jobCompanyYear"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['work']->value['start_time'],'%Y.%m');?>
-<?php if ($_smarty_tpl->tpl_vars['work']->value['end_time']==null){?>至今<?php }else{ ?><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['work']->value['end_time'],'%Y.%m');?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['work']->value['resume_work_year_desc']){?>(<?php echo $_smarty_tpl->tpl_vars['work']->value['resume_work_year_desc'];?>
)<?php }?></span></p>
										<?php }} ?>
									<?php }?>
									
								</div>
								<div class="person-left hopeJob">
									<?php if ($_smarty_tpl->getVariable('apply_list_top_one')->value['exp_station']){?><p class="hopeJobName">求职意向：<span><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['exp_station'];?>
</span></p><?php }?>
									<p class="hopeMoney">期望薪资：<span><?php if ($_smarty_tpl->getVariable('apply_list_top_one')->value['is_salary_show']==0){?><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['salary'];?>
<?php }else{ ?>面议<?php }?></span></p>
									<?php if ($_smarty_tpl->getVariable('apply_list_top_one')->value['appraise']){?><p class="showMelf">自我评价: <span class="showMelf-info"><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['appraise'];?>
</span></p><?php }?>
								</div>
							</div>
                            <div class="personInfo-left">
								<div class="inviteViewType" data-id="<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['id'];?>
" data-resume-id="<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['resume_id'];?>
" data-job-id="<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['job_id'];?>
" data-person-id="<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['person_id'];?>
"
									data-status="0" data-base-job-id="<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['base_job_id'];?>
" data-base-resume-id="<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['base_resume_id'];?>
" data-base-person-id="<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['base_person_id'];?>
">
									<?php if ($_smarty_tpl->getVariable('apply_list_top_one')->value['status']==0||$_smarty_tpl->getVariable('apply_list_top_one')->value['status']==1){?>
									<a href="javascript:void(0);" class="startVideoBtn" data-status='1' <?php if ($_smarty_tpl->getVariable('apply_list_top_one')->value['person_source']!=1||$_smarty_tpl->getVariable('source')->value!=1){?>style="display:none;"<?php }?>>电脑面试</a>
									<a href="javascript:void(0);" class="mobileVideoBtn">手机面试</a>
									<?php if ($_smarty_tpl->getVariable('apply_list_top_one')->value['status']==1){?>
									<a href="javascript:void(0);" class="waitDealBtn">待定</a>
									<a href="javascript:void(0);" class="notPassBtn">不合适</a>
									<a href="javascript:void(0);" class="passBtn">初面通过</a>
									<?php }?>
									<a href="javascript:void(0);" class="skipBtn" <?php if (!$_smarty_tpl->getVariable('skip_num')->value){?>style="display:none;"<?php }?>>跳过</a>
									<?php }?>
								</div>
							</div>
						</div>
					</li>
				</ul>
            <?php }?>
            <!--面试还未开始-->
            <div class="activityNotStarted  invitViewNotStarted dataNone" <?php if ($_smarty_tpl->getVariable('shuang_xuan_time_class_name')->value!='videoActiveityStartAll'){?>style="display:none;"<?php }?>>
               <h3>面试还未开始</h3>
               <p>请于<span><?php echo $_smarty_tpl->getVariable('min_interview_time')->value;?>
</span> 登录系统与求职者视频面试~</p>
            </div>
			</div>
				<!--------->
			<div class="firstInviteTipCon" <?php if (empty($_smarty_tpl->getVariable('apply_list',null,true,false)->value)){?>style="display:none;"<?php }?>>初面最长10分钟，如需深入了解请复面</div>
			<!-- <div class="videoCoupleBack" <?php if (empty($_smarty_tpl->getVariable('apply_list',null,true,false)->value)){?>style="display:none;"<?php }?>><i></i>反馈面试结果后才能继续下一个面试</div> -->
			<div class="videointerviewTitle" <?php if (empty($_smarty_tpl->getVariable('apply_list',null,true,false)->value)&&$_smarty_tpl->getVariable('shuang_xuan_time_class_name')->value!='videoActiveityStartAll'){?>style="display:none;"<?php }?>>
				<p>
					<i></i>
					<span><em><?php echo $_smarty_tpl->getVariable('wait_apply_person_num')->value;?>
</em>人排队中<span class="title_mark">···</span></span>
				</p>
			</div>
            <?php if (!empty($_smarty_tpl->getVariable('apply_list',null,true,false)->value)){?>
			<table class="interviewTab02">
                <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('apply_list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
				<tr>
					<td width="6%"><img src="<?php echo $_smarty_tpl->tpl_vars['v']->value['headphoto'];?>
" onerror="nofind('<?php echo $_smarty_tpl->tpl_vars['v']->value['default_photo'];?>
');" /></td>
					<td width="10%"><?php echo $_smarty_tpl->tpl_vars['v']->value['user_name'];?>
</td>
                    <td width="6%"><?php echo $_smarty_tpl->tpl_vars['v']->value['sex'];?>
</td>
					<td width="6%"><?php echo $_smarty_tpl->tpl_vars['v']->value['age'];?>
</td>
					<td width="8%"><?php echo $_smarty_tpl->tpl_vars['v']->value['degree_name'];?>
</td>
					<td width="14%"><?php echo $_smarty_tpl->tpl_vars['v']->value['school'];?>
</td>
					<td width="16%"><?php echo $_smarty_tpl->tpl_vars['v']->value['major_desc'];?>
</td>
					<td width="18%"><?php echo $_smarty_tpl->tpl_vars['v']->value['station'];?>
</td>
					<td width="16%"><?php echo $_smarty_tpl->tpl_vars['v']->value['base_wait_time_str'];?>
</td>
				</tr>
                <?php }} ?>
			</table>
			
            <?php }?>
            <!-- 活动未开始 -->
        <?php if (($_smarty_tpl->getVariable('shuang_xuan_time_class_name')->value=='videoActiveityStart'||$_smarty_tpl->getVariable('shuang_xuan_time_class_name')->value=='videoActiveityStartAll')&&empty($_smarty_tpl->getVariable('apply_list',null,true,false)->value)&&empty($_smarty_tpl->getVariable('apply_list_top_one',null,true,false)->value)){?>
            <div class="activityNotStarted invitViewNotStarted dataNone">
                <?php if (empty($_smarty_tpl->getVariable('company_interview_times',null,true,false)->value)){?>
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon11.png" >
                <span>面试未开始，请在活动开始前设置面试时间</span>
                <a href="javascript:void(0);" class='setInterviewTime'>设置面试时间</a>
                <em>求职者只能在您设置的面试时间内申请视频面试</em>
                <?php }else{ ?>
                <h3>面试还未开始</h3>
			   <p>请于<span><?php echo $_smarty_tpl->getVariable('min_interview_time')->value;?>
</span> 登录系统与求职者视频面试~</p>
                <?php }?>
            </div>
        <?php }elseif($_smarty_tpl->getVariable('shuang_xuan_time_class_name')->value=='videoAunderway'&&empty($_smarty_tpl->getVariable('apply_list',null,true,false)->value)&&empty($_smarty_tpl->getVariable('apply_list_top_one',null,true,false)->value)){?>
            <div class="noApplyVideo">
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon10.png" >
                <?php if (!$_smarty_tpl->getVariable('today_interview_time')->value){?>
                <span>请设置面试时间，求职者仅能在设置的面试时间内申请视频面试</span><p/>
                <a href="javascript:void(0);" class='setInterviewTime'>设置面试时间</a>
                <?php }else{ ?>
                当前无人申请，您可前往“求职者大厅”，主动与求职者沟通
                <?php }?>

            </div>
        <?php }elseif($_smarty_tpl->getVariable('shuang_xuan_time_class_name')->value=='videoActivityEnd'){?>
             <div class="noApplyVideo">
				 <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon10.png" >
                <span>初面环节已结束，您可前去“复面”列表处理复面求职者</span><p/>
            </div>
        <?php }?>
			
			<div class="videointerviewTitle">
				<p>
					<i></i>
					<span>未接通申请</span>
				</p>
			</div>
            <?php if ($_smarty_tpl->getVariable('over_apply_list')->value){?>
			
            
			<table class="interviewTab02">
                <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('over_apply_list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
				<tr>
					<td width="6%"><img src="<?php echo $_smarty_tpl->tpl_vars['v']->value['headphoto'];?>
" onerror="nofind('<?php echo $_smarty_tpl->tpl_vars['v']->value['default_photo'];?>
');" /></td>
					<td width="10%"><?php echo $_smarty_tpl->tpl_vars['v']->value['user_name'];?>
</td>
			        <td width="6%"><?php echo $_smarty_tpl->tpl_vars['v']->value['sex'];?>
</td>
					<td width="6%"><?php echo $_smarty_tpl->tpl_vars['v']->value['age'];?>
</td>
					<td width="8%"><?php echo $_smarty_tpl->tpl_vars['v']->value['degree_name'];?>
</td>
					<td width="14%"><?php echo $_smarty_tpl->tpl_vars['v']->value['school'];?>
</td>
					<td width="16%"><?php echo $_smarty_tpl->tpl_vars['v']->value['major_desc'];?>
</td>
					<td width="18%"><?php echo $_smarty_tpl->tpl_vars['v']->value['station'];?>
</td>
					<td width="16%" data-resume-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['resume_id'];?>
" data-person-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['person_id'];?>
" data-job-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['job_id'];?>
" data-apply-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
						<!--<a href="https:<?php echo base_lib_Constant::COMPANY_URL_NO_HTTP;?>
/chat/?resume_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['resume_id'];?>
&job_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['job_id'];?>
&net_apply_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
&sid=<?php echo $_smarty_tpl->tpl_vars['v']->value['sid'];?>
">立即沟通</a><?php echo $_smarty_tpl->tpl_vars['v']->value['mobile_phone'];?>
-->
						<a class="startInterviewBtn" href="javascript:;" <?php if ($_smarty_tpl->tpl_vars['v']->value['person_source']!=1||$_smarty_tpl->getVariable('source')->value!=1){?>style="display:none;"<?php }?>>立即沟通</a>
                        <a href="javascript:;" class="see_mobile_btn">查看电话</a>
					</td>
				</tr>
                <?php }} ?>
			</table>
            <?php }?>
			<!--面试还未开始-->
			<div class="activityNotStarted  invitViewNotStarted dataNone" <?php if (!empty($_smarty_tpl->getVariable('over_apply_list',null,true,false)->value)||$_smarty_tpl->getVariable('shuang_xuan_time_class_name')->value!='videoActiveityStartAll'){?>style="display:none;"<?php }?>>
			   <h3>面试还未开始</h3>
			   <p>请于<span><?php echo $_smarty_tpl->getVariable('min_interview_time')->value;?>
</span>登录系统与求职者视频面试~</p>
			</div>
			<!--------->
        </div>
			<div class="showPopType"  style="display:none;">
				<div class="showPopType-box">
					<span class="pc_apply">电脑面试</span>
					<span class="phone_apply">手机面试</span>
				</div>
			</div>
			<div class="procedurePop" style="display:none;">
				<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon22.jpg" >
			</div>
			<div class="mobileVideoDialog" style="display:none;">
				<div class="mobileVideoDialog-box">
					<img id='rtcScanCodeImg' src="<?php echo smarty_function_get_url(array('rule'=>'/video/RtcScanCode'),$_smarty_tpl);?>
?job_id=&resume_id=&net_apply_id=" >
					<div class="saomaTip">汇博企业APP 扫一扫</div>
					<div class="noHuiboAPP">没有汇博APP？<a href="javascript:void(0);" id="downloadErWeiMaBtn">点击下载</a></div>
				</div>
			</div>
			<div class="rtcScanStatusDialog" style="display:none;">
				<div class="rtcScanStatusDialog-box">
					<div class="user-detail">
						<img src="<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['headphoto'];?>
" onerror="nofind('<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['default_photo'];?>
');"/>
						<div class="userInfo">
							<!-- <h3><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['sex'];?>
</h3> -->
							<h4><?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['user_name'];?>
</h4>
							<p>面试职位：<?php echo $_smarty_tpl->getVariable('apply_list_top_one')->value['station'];?>
</p>
						</div>
					</div>
					<div class="inviteViewStatus-btn">
						<a class="rtcScanStatusDialog_status" data-status="5" href="javascript:void(0);">未接通</a>
						<a class="rtcScanStatusDialog_status" data-status="2" href="javascript:void(0);">初面通过</a>
						<a class="rtcScanStatusDialog_status" data-status="4" href="javascript:void(0);">待定</a>
						<a class="rtcScanStatusDialog_status" data-status="3" href="javascript:void(0);">不适合</a>
					</div>
					<a id='rtcScanStatusDialog_sure_btn' class="rtcScanStatusDialog_sure_btn" href="javascript:void(0);">确定</a>
				</div>
			</div>
			<div class="skipInterviewDialog" style="display:none;">
				<div class="skipInterviewDialog-box">
					<p>是否主动跳过该求职者？您该场招聘会还剩<span class="jumpNum">5次</span>跳过机会。</p>
					<div class="btn-ul">
						<div class="skipInterview_sureBtn">跳过</div>
						<div class="skipInterview_closeBtn">取消</div>
					</div>
				</div>
			</div>
			<div class="mobileCheckDialog-box" style="display: none;">
				<img id='rtcScanCodeImg' src="<?php echo smarty_function_get_url(array('rule'=>'/video/RtcScanCode'),$_smarty_tpl);?>
?job_id=&resume_id=&net_apply_id=" >
				<div class="saomaTip">汇博企业APP 扫一扫</div>
				<div class="noHuiboAPP">没有汇博APP？<a href="javascript:void(0);" id="downloadErWeiMaBtn">点击下载</a></div>
			</div>
			
		<a href="javascript:void(0);" style="display: none;" class="moblielinkTo" target="_blank"></a>
		<a href="javascript:void(0);" style="display: none;" class="PClinkTo" target="_blank"></a>
    <script type="text/javascript">
    var promptInterview;
    hbjs.use('@confirmBox, @jobDialog', function (m) {
        var ConfirmBox = m['widge.overlay.confirmBox'],
            Dialog = m['widge.overlay.hbDialog'],
            $ = m['jquery'].extend(m['cqjob.jobDialog']);

        var close = '×';
        var width = 600;
        var zIndex = 9999;
        var title = '面试时间设置';
        var wait_apply_person_num = '<?php echo $_smarty_tpl->getVariable('wait_apply_person_num')->value;?>
';

		var seleType = new Dialog({
		    close: close,
		    idName: 'showType',
		    title: '面试方式',
		    width: 400,
		    zIndex: zIndex,
			content:$(".showPopType").html()
		});
		
        var checkVideoDialog = new Dialog({
            close: close,
            idName: 'checkVideo_dialog',
            title: '提示',
            width: width,
            zIndex: zIndex,
            content:$("#videoCheckDiv").html()
        });
		
		var checkVideoDialog02 = new Dialog({
		    close: close,
		    idName: 'checkVideo_dialog02',
		    title: '电脑麦克风设置流程',
		    width: width,
		    zIndex: zIndex,
		    content:$(".procedurePop").html()
		});
        
        var mobileVideoDialog = new Dialog({
		    close: close,
		    idName: 'mobileVideoDialog',
		    title: '手机面试',
		    width: width,
		    zIndex: zIndex,
		    content:$(".mobileVideoDialog").html()
		});
        var rtcScanStatusDialog = new Dialog({
		    close: close,
		    idName: 'rtcScanStatusDialog',
		    title: '面试结果设置',
		    width: 520,
		    zIndex: zIndex,
		    content:$(".rtcScanStatusDialog").html()
		});
		var skipInterviewDialog = new Dialog({
		    close: close,
		    idName: 'skipInterviewDialog',
		    title: '跳过求职者',
		    width: 520,
		    zIndex: zIndex,
		    content:$(".skipInterviewDialog").html()
		});
		
        $('.setInterviewTime').on('click', function(){
            interviewTimeDialog.setContent('<?php echo smarty_function_get_url(array('rule'=>"/videohall/InterviewTimeList"),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
').show();
        });
        $(".rtcScanStatusDialog .ui_dialog_close,.mobileVideoDialog .ui_dialog_close,.informTraining_dialog .ui_dialog_close").click(function(){
            window.location.reload();
        });
		$('.queryIcon').click(function(){
			checkVideoDialog02.show();
		});
		// $('.pc_apply').click(function(){
		// 	$('.computer_pop').show();
		// 	$('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_one.jpg");
		// 	seleType.hide()
		// });
		// $('.phone_apply').click(function(){
		// 	$('.phone_pop').show();
		// 	$('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_one.jpg");
		// 	seleType.hide()
		// });
  //       $('#interviewps').click(function(){
		// 	var popType_t = '<?php echo $_smarty_tpl->getVariable('source')->value;?>
'
		// 	if(popType_t == 1){
		// 		seleType.show()
		// 	}else{
		// 		$('.kuaimi_pop').show();
		// 		$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_one.jpg");
		// 	}
		// });
		$('#comproblem').click(function(){
			
		});
		// $('.computer_next').click(function(){
		// 	var step = $('#computer_pop_bg_img').attr('data-step')
		// 	switch(step) {
		// 	     case 'one':
		// 			$('#computer_pop_bg_img').attr('data-step','tow')
		// 			$('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_tow.jpg");
		// 	        $(this).css({'top':'77.2%','left':'59.4%'})	
		// 			break;
		// 	     case 'tow':
		// 			 $('#computer_pop_bg_img').attr('data-step','tree')
		// 			 $('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_tree.jpg");
		// 			 $(this).css({'top':'29%','left':'41.9%'})
		// 	        break;
		// 		case 'tree':
		// 			$('#computer_pop_bg_img').attr('data-step','four')
		// 			$('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_four.jpg");
		// 			$(this).css({'top':'46.1%','left':'42.5%'})
		// 			// $(this).text('完成')
		// 		   break;
		// 	   case 'four':
		// 			SaveAction();
		// 			$('.computer_pop').hide()
		// 			$('#computer_pop_bg_img').attr('data-step','one')
		// 			$(this).css({'top':'52.2%','left':'42.4%'})
		// 			// $(this).text('下一步')
		// 		  break;
		// 	     default:
		// 	        console.log('hao')
		// 	}
		// });
		// $('.kuaimi_next').click(function(){
		// 	var step = $('#kuaimi_pop_bg_img').attr('data-step')
		// 	switch(step) {
		// 	     case 'one':
		// 			$('#kuaimi_pop_bg_img').attr('data-step','tow')
		// 			$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_tow.jpg");
		// 	        $(this).css({'top':'46.1%','left':'55%'})
		// 			break;
		// 	     case 'tow':
		// 			 $('#kuaimi_pop_bg_img').attr('data-step','tree')
		// 			 $('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_tree.jpg");
		// 			 $(this).css({'top':'79%','left':'59%'})
		// 	        break;
		// 		case 'tree':
		// 			$('#kuaimi_pop_bg_img').attr('data-step','four')
		// 			$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_four.jpg");
		// 			$(this).css({'top':'56%','left':'64%'})
		// 		   break;
		// 		case 'four':
		// 			$('#kuaimi_pop_bg_img').attr('data-step','five')
		// 			$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_five.jpg");
		// 			$(this).css({'top':'59%','left':'60%'})
		// 			// $(this).text('完成')
		// 		   break;
		// 	   case 'five':
		// 			SaveAction();
		// 			$('.kuaimi_pop').hide()
		// 			$('#kuaimi_pop_bg_img').attr('data-step','one')
		// 			$(this).css({'top':'50%','left':'41%'})
		// 			// $(this).text('下一步')
		// 		  break;
		// 	     default:
		// 	        console.log('hao')
		// 	}
		// });
		// $('.phone_next').click(function(){
		// 	var step = $('#phone_pop_bg_img').attr('data-step')
		// 	switch(step) {
		// 	     case 'one':
		// 			$('#phone_pop_bg_img').attr('data-step','tow')
		// 			$('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_tow.jpg");
		// 	        $(this).css({'top':'57%','left':'55%'})
		// 			break;
		// 	     case 'tow':
		// 			 $('#phone_pop_bg_img').attr('data-step','tree')
		// 			 $('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_tree.jpg");
		// 			 $(this).css({'top':'59%','left':'55%'})
		// 	        break;
		// 		case 'tree':
		// 			$('#phone_pop_bg_img').attr('data-step','four')
		// 			$('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_four.jpg");
		// 			$(this).css({'top':'79%','left':'60%'})
		// 		   break;
		// 	   case 'four':
		// 		   $('#phone_pop_bg_img').attr('data-step','five')
		// 		   $('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_five.jpg");
		// 		   $(this).css({'top':'42%','left':'61%'})
		// 			// console.log('完成了')
		// 		  break;
		// 	    case 'five':
		// 		   $('#phone_pop_bg_img').attr('data-step','six')
		// 		   $('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_six.jpg");
		// 		   $(this).css({'top':'58%','left':'62%'})
		// 		   // $(this).text('完成')
		// 			// console.log('完成了')
		// 		  break;
  //             case 'six':
  //                 SaveAction();
		// 		  $('.phone_pop').hide()
		// 		  $('#phone_pop_bg_img').attr('data-step','one')
		// 		  $(this).css({'top':'49%','left':'41%'})
		// 		  // $(this).text('下一步')
  //               break;
		// 	     default:
		// 	        console.log('hao')
		// 	}
		// });
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
		// }); 
		$(document).ready(function(){
			var check_in_type = Number('<?php echo $_smarty_tpl->getVariable('check_in_type')->value;?>
');
			var isShow = '<?php echo $_smarty_tpl->getVariable('has_read_guide_pic')->value;?>
'
			var popType_t = '<?php echo $_smarty_tpl->getVariable('source')->value;?>
'
			var chat_typ =  '<?php echo $_smarty_tpl->getVariable('chat_type')->value;?>
'
			 // console.log('是否弹框',isShow)
			// console.log('汇博还是快米',popType_t)
			// console.log('是电脑还是手机',chat_typ)
			// $('.kuaimi_pop').show();
			// $('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_one.jpg");
			// return
			console.log('活动类型',check_in_type)
			console.log('是否显示下一步',isShow)
			if(check_in_type== 0 && isShow){
				if(popType_t == 1){
					if(chat_typ == 1){
						$('.computer_pop').show();
						$('#computer_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/computer_step_one.jpg");
					}else{
						$('.phone_pop').show();
						$('#phone_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/phone_step_one.jpg");
					}
				}else{
					$('.kuaimi_pop').show();
					$('#kuaimi_pop_bg_img').attr("src", "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/activity/kuaimi_step_one.jpg");
				}
			}
		}); 
		$(document).ready(function(){
			var timeId = setInterval(function(){
					var html = $('.title_mark').html() + '·'
					if(html == '····') {
							html = ''
					}
					$('.title_mark').html(html)
			},800)
		});
        mobileVideoDialog.query("#downloadErWeiMaBtn").on("click", function(){
            clearInterval(promptInterview);
            mobileVideoDialog.hide();
            downloadErWeiMaDialog.show();
        });
        
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
        function setStatus(id, status, person_id){
            $.ajax({
                type: 'post',
                url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/SetInterviewStatus"),$_smarty_tpl);?>
',
                data: {id: id, status: status, person_id: person_id, need_send_msg: true},
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
                        return;
                    }
                    ConfirmBox.timeBomb(res.msg,{
                        name: 'success',
                        width:'auto',
                        timeout : 2000
                    });
                    setTimeout("window.location.reload()",2000);
                    return;
                }
            });
        }
        $(".waitDealBtn").on("click", function(){
            var id = $(this).parent().attr('data-id');
            var person_id = $(this).parent().attr('data-person-id');
            $.confirm('确定要设置为待定吗？', '提示', function () {
                setStatus(id, 4, person_id);
            });
        });
        
        $(".notPassBtn").on("click", function(){
            var id = $(this).parent().attr('data-id');
            var person_id = $(this).parent().attr('data-person-id');
            $.confirm('确定要设置为不合适吗？', '提示', function () {
                setStatus(id, 3, person_id);
            });
        });
        
        $(".passBtn").on("click", function(){
            var id = $(this).parent().attr('data-id');
            var person_id = $(this).parent().attr('data-person-id');
            $.confirm('确定要设置为通过吗？', '提示', function () {
                setStatus(id, 2, person_id);
            });
        });
        
       
        $('.startVideoBtn').on('click', function(e){
            var id = $(this).parent().attr('data-id'),
                base_job_id = $(this).parent().attr('data-base-job-id'),
                base_resume_id = $(this).parent().attr('data-base-resume-id'),
                status = $(this).attr('data-status');
            deviceCheckDialog.query("#rtcScanCodeImg").attr("src", "<?php echo smarty_function_get_url(array('rule'=>'/video/RtcScanCode','domain'=>'company'),$_smarty_tpl);?>
?job_id="+base_job_id+"&resume_id="+base_resume_id+"&net_apply_id="+id);
			e.stopPropagation();
			aliWebrtc.isSupport({isDebug: true}).then(function(re) {
				console.log(re);
				if(re.videoDevice && re.audioDevice){
					$.ajax({
						type: 'post',
						url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/StartInterviewV1"),$_smarty_tpl);?>
',
						data: {id: id,need_send_msg : status},
						dataType: 'json',
						success: function (res) {
							if(res.isNeedLogin){
								window.location.reload();
								return;
							}
					
							if(res.code == 1 && wait_apply_person_num > 0){
								$.confirm('求职者正在面试中，您可先面试其他求职者（该求职者会自动移动至最后一名）', '提示', function () {
									$.ajax({
										type: 'post',
										url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/SetMaxNo"),$_smarty_tpl);?>
',
										data: {id: id, sid: '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
'},
										dataType: 'json',
										success: function (res1) {
											if(!res1.status){
												ConfirmBox.timeBomb(res1.msg,{
													name: 'fail',
													width:'auto',
													timeout : 2000
												});
												return;
											}
											ConfirmBox.timeBomb(res1.msg,{
												name: 'success',
												width:'auto',
												timeout : 2000
											});
											setTimeout("window.location.reload()",2000);
											return;
										}
									});
								});
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
							window.location.href = "https:<?php echo base_lib_Constant::COMPANY_URL_NO_HTTP;?>
/chat/?resume_id=" + res.data.resume_id + "&job_id=" + res.data.job_id + "&net_apply_id=" + res.data.id + "&sid=" + res.data.sid;
						}
					});
				}else{
					console.log(re);
					deviceCheckDialog.show();
					checkTypeStatus(re.videoDevice,re.audioDevice,true);
				}
				
			}).catch(function(error){
				console.log(error);
				deviceCheckDialog.show();
			    checkTypeStatus(error.videoDevice,error.audioDevice,true);
			});
        });
        //跳过
        $('.skipBtn').on('click', function(){
            var id = $(this).parent().attr('data-id');
			skipInterviewDialog.show();
			skipInterviewDialog.query('.jumpNum').html("<?php echo $_smarty_tpl->getVariable('skip_num')->value;?>
次");
			skipInterviewDialog.query('.skipInterview_sureBtn').on("click", function(){
				$.ajax({
				    type: 'post',
				    url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/SkipInterview"),$_smarty_tpl);?>
',
				    data: {id: id, sid: '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
', is_add_skip_num: true},
				    dataType: 'json',
				    success: function (res1) {
				        if(!res1.status){
				            ConfirmBox.timeBomb(res1.msg,{
				                name: 'fail',
				                width:'auto',
				                timeout : 2000
				            });
				            return;
				        }
				        ConfirmBox.timeBomb(res1.msg,{
				            name: 'success',
				            width:'auto',
				            timeout : 2000
				        });
				        setTimeout("window.location.reload()",2000);
				        return;
				    }
				});
			})
			skipInterviewDialog.query('.skipInterview_closeBtn').on("click", function(){
				skipInterviewDialog.hide();
			})
            // $.confirm("是否主动跳过该求职者？您该场招聘会还剩<span class="+"'jumpNum'"+"><?php echo $_smarty_tpl->getVariable('skip_num')->value;?>
次</span>跳过机会。", '跳过求职者', function () {
                
            // });
		});
        //立即沟通
        $(".startInterviewBtn").on("click", function(){
            var resume_id = $(this).parent().attr('data-resume-id');
            var job_id = $(this).parent().attr('data-job-id');
            var apply_id = $(this).parent().attr('data-apply-id');
            var has_wait_deal_apply = <?php echo !empty($_smarty_tpl->getVariable('apply_list',null,true,false)->value)||!empty($_smarty_tpl->getVariable('apply_list_top_one',null,true,false)->value);?>

            $.ajax({
                type: 'post',
                url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/GetLinkWay"),$_smarty_tpl);?>
',
                data: {sid: '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
', has_wait_deal_apply: has_wait_deal_apply, need_download: false},
                dataType: 'json',
                success: function (res) {
                    if(!res.status){
                        ConfirmBox.timeBomb(res.msg,{
                            name: 'fail',
                            width:'auto',
                            timeout : 2000
                        });
                        return;
                    }
                    var newwindow = window.open('about:blank');
                    newwindow.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/videohall/startInterviewV2'),$_smarty_tpl);?>
?resume_id="+resume_id+"&job_id="+job_id+"&net_apply_id="+apply_id+"&sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
";
                }
            });
        });
        //查看联系方式
        $(".see_mobile_btn").on("click", function(){
            var resume_id = $(this).parent().attr('data-resume-id');
            var person_id = $(this).parent().attr('data-person-id');
            var apply_id = $(this).parent().attr('data-apply-id');
            var has_wait_deal_apply = <?php echo !empty($_smarty_tpl->getVariable('apply_list',null,true,false)->value)||!empty($_smarty_tpl->getVariable('apply_list_top_one',null,true,false)->value);?>

            $.ajax({
                type: 'post',
                url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/GetLinkWay"),$_smarty_tpl);?>
',
                data: {resume_id: resume_id, person_id: person_id, sid: '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
', has_wait_deal_apply: has_wait_deal_apply},
                dataType: 'json',
                success: function (res) {
                    if(!res.status){
                        ConfirmBox.timeBomb(res.msg,{
                            name: 'fail',
                            width:'auto',
                            timeout : 2000
                        });
                        return;
                    }
                    var newwindow = window.open('about:blank');
                    newwindow.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/resume/index'),$_smarty_tpl);?>
?resume_id=" + resume_id + "&sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
&apply_id=" + apply_id;
                }
            });
        });
		
        //手机面试
        var rtcScanStatusDialog_status_value = 0,
            rtcScanStatusDialog_apply_id = 0,
            rtcScanStatusDialog_person_id = 0;
        $(".mobileVideoBtn").on('click', function(e){
			e.stopPropagation();
            var apply_id = $(this).parent().attr('data-id');
            var resume_id = $(this).parent().attr('data-resume-id');
            var person_id = $(this).parent().attr('data-person-id');
			// $('body').append('<a h>')
            // var newwindow = window.open('about:blank');
			$('.moblielinkTo').attr('href',"<?php echo smarty_function_get_url(array('rule'=>'/resume/index'),$_smarty_tpl);?>
?resume_id=" + resume_id + "&sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
&apply_id=" + apply_id)
			$('.moblielinkTo')[0].click();
            // newwindow.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/resume/index'),$_smarty_tpl);?>
?resume_id=" + resume_id + "&sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
&apply_id=" + apply_id;
            rtcScanStatusDialog_apply_id = apply_id;
            rtcScanStatusDialog_person_id = person_id;
            rtcScanStatusDialog_status_value = 0;
            rtcScanStatusDialog.show();
        });
          
        rtcScanStatusDialog.query('.rtcScanStatusDialog_status').on('click', function(){
            rtcScanStatusDialog_status_value = $(this).attr('data-status');
			$(this).addClass('statusActive').siblings().removeClass('statusActive');
        });
        rtcScanStatusDialog.query('#rtcScanStatusDialog_sure_btn').on('click', function(){
            if(!rtcScanStatusDialog_status_value){
                ConfirmBox.timeBomb('请设置面试结果',{
                    name: 'fail',
                    width:'auto',
                    timeout : 2000
                });
                return;
            }
//            if(rtcScanStatusDialog_status_value == 99){
//                window.location.reload();
//                return;
//            }
                
            setStatus(rtcScanStatusDialog_apply_id, rtcScanStatusDialog_status_value, rtcScanStatusDialog_person_id);
        });

    })
    </script>

	</body>
</html>

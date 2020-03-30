<?php /* Smarty version Smarty-3.0.7, created on 2020-03-28 18:59:14
         compiled from "app\templates\./videohall/secondinterview.html" */ ?>
<?php /*%%SmartyHeaderCode:234575e7f2e021f7176-02121355%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '92079bc63f86022618e9dcb6c812c6cbd2f51b69' => 
    array (
      0 => 'app\\templates\\./videohall/secondinterview.html',
      1 => 1585390791,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '234575e7f2e021f7176-02121355',
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
		<title>视频面试大厅-复面</title>
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
	</head>
	<body style="background: #f7f7f9 !important;">
	<?php echo $_smarty_tpl->getVariable('head_data')->value;?>

    <?php echo $_smarty_tpl->getVariable('head_nav_data')->value;?>

    <?php $_template = new Smarty_Internal_Template('videohall/hallheadnew.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par','视频面试大厅');$_template->assign('cur','复面'); echo $_template->getRenderedTemplate();?><?php unset($_template);?>

			<!-- <div class="videoImproveEquipment" id="videoCheckTopDiv"> -->
				<!-- <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon04.png"> -->
				<!-- 面试前插上<em class="red">摄像头</em>与<em class="red">耳机</em>，并增强<em class="red">麦克风</em>，以免求职者无法与你沟通<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon23.png" class="queryIcon"> -->
				<!-- 请及时<em class="red">反馈复面结果</em>，恶意不反馈<em class="red">将取消本场及后续场次参会资格</em> -->
			<!-- </div> -->
			
			<div class="interviewData">
				<?php if ($_smarty_tpl->getVariable('resume_list')->value){?>
				<ul>
					<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('resume_list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
					<li data-resumeid="<?php echo $_smarty_tpl->tpl_vars['val']->value['resume_id'];?>
" data-person-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['person_id'];?>
" data-job-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['job_id'];?>
" data-apply-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" class="resumeData">
						<table border="" cellspacing="" cellpadding="">
							<tr>
								<td width="12%" class="firsttd"><?php echo $_smarty_tpl->tpl_vars['val']->value['user_name'];?>
</td>
								<td width="6%"><?php echo $_smarty_tpl->tpl_vars['val']->value['sex'];?>
</td>
								<td width="6%"><?php echo $_smarty_tpl->tpl_vars['val']->value['age'];?>
</td>
								<td width="8%"><?php echo $_smarty_tpl->tpl_vars['val']->value['degree_name'];?>
</td>
								<td width="20%"><?php echo $_smarty_tpl->tpl_vars['val']->value['school'];?>
</td>
								<td width="18%"><?php echo $_smarty_tpl->tpl_vars['val']->value['major_desc'];?>
</td>
								<td width="23%" class="inviteViewJob">面试职位: <?php echo $_smarty_tpl->tpl_vars['val']->value['station'];?>
</td>
								<td width="7%" style="text-align: center;">
									<?php if ($_smarty_tpl->tpl_vars['val']->value['status']==2){?>
										<span class="statusdaiding-success">初面通过</span>
									<?php }else{ ?>
										<span class="status-daiding">待定</span>
									<?php }?>
								</td>
							</tr>
						</table>
						<div class="personInfo">
							<div class="personInfo-left">
								<div class="person-left personIcon">
									<img src="<?php echo $_smarty_tpl->tpl_vars['val']->value['headphoto'];?>
" >
								</div>
								<div class="person-left personExperience">
									<?php if ($_smarty_tpl->tpl_vars['val']->value['work_year_str']){?><h4><?php echo $_smarty_tpl->tpl_vars['val']->value['work_year_str'];?>
</h4><?php }?>
									<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['val']->value['work_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
									<p><span class="jobName"><?php echo $_smarty_tpl->tpl_vars['v']->value['station'];?>
</span><span class="jobCompanyName"><?php echo $_smarty_tpl->tpl_vars['v']->value['company_name'];?>
</span><span class="jobCompanyYear"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['start_time'],'%Y.%m');?>
-<?php if ($_smarty_tpl->tpl_vars['v']->value['end_time']==null){?>至今<?php }else{ ?><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['end_time'],'%Y.%m');?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['v']->value['resume_work_year_desc']){?>(<?php echo $_smarty_tpl->tpl_vars['v']->value['resume_work_year_desc'];?>
)<?php }?></span></p>
									<?php }} ?>
								</div>
								<div class="person-left hopeJob">
									<?php if ($_smarty_tpl->tpl_vars['val']->value['exp_station']){?><p class="hopeJob">求职意向：<span><?php echo $_smarty_tpl->tpl_vars['val']->value['exp_station'];?>
</span></p><?php }?>
									<p class="hopeMoney">期望薪资：<span><?php if ($_smarty_tpl->tpl_vars['val']->value['is_salary_show']==0){?><?php echo $_smarty_tpl->tpl_vars['val']->value['salary'];?>
<?php }else{ ?>面议<?php }?></span></p>
									<?php if ($_smarty_tpl->tpl_vars['val']->value['appraise']){?><p class="showMelf">自我评价：<span class="showMelf-info"><?php echo $_smarty_tpl->tpl_vars['val']->value['appraise'];?>
</span></p><?php }?>
								</div>
							</div>
							<div class="personInfo-left">	
								<div class="person-left inviteViewType" data-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" data-resume-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['resume_id'];?>
" data-job-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['job_id'];?>
" data-person-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['person_id'];?>
"
                                     data-base-job-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['base_job_id'];?>
" data-base-resume-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['base_resume_id'];?>
" data-base-person-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['base_person_id'];?>
">
									<a href="javascript:void(0);" class="startVideoBtn" data-status="1" <?php if ($_smarty_tpl->tpl_vars['val']->value['person_source']!=1||$_smarty_tpl->getVariable('source')->value!=1){?>style="display:none;"<?php }?>>电脑面试</a>
									<a href="javascript:void(0);" class="mobileVideoBtn">手机面试</a>
									<a href="javascript:void(0);" class="notPassBtn">不合适</a>
									<a href="javascript:void(0);" class="secondPass">复面通过</a>
								</div>
							</div>
						</div>
					</li>
					<?php }} ?>
				</ul>
				<?php }else{ ?>
					<div class="noDatabox">
						<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon10.png" >
						<p>暂无复面求职者数据</p>
					</div>
				<?php }?>
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
	    <div class="noHuiboAPP">没有汇博APP？<a href="javascript:;" id="downloadErWeiMaBtn">点击下载</a></div>
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
				<a class="rtcScanStatusDialog_status" data-status="99">未接通</a>
				<a class="rtcScanStatusDialog_status" data-status="2">初面通过</a>
				<a class="rtcScanStatusDialog_status" data-status="4">待定</a>
				<a class="rtcScanStatusDialog_status" data-status="3">不适合</a>
			</div>
			<a id='rtcScanStatusDialog_sure_btn' class="rtcScanStatusDialog_sure_btn">确定</a>
		</div>
	</div>
	<a href=":;" style="display: none;" class="moblielinkTo" target="_blank"></a>
	<a href=":;" style="display: none;" class="PClinkTo" target="_blank"></a>
	<a href=":;" style="display: none;" class="resumelinkTo" target="_blank"></a>
<script type="text/javascript">
	var interviewTimeDialog;
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

		interviewTimeDialog = new Dialog({
			close: close,
			idName: 'informTraining_dialog',
			title: title,
			width: width,
			zIndex: zIndex
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

		$(".rtcScanStatusDialog .ui_dialog_close,.mobileVideoDialog .ui_dialog_close,.informTraining_dialog .ui_dialog_close").click(function(){
			window.location.reload();
		});
		$('.queryIcon').click(function(){
			checkVideoDialog02.show();
		});

		mobileVideoDialog.query("#downloadErWeiMaBtn").on("click", function(){
			clearInterval(promptInterview);
			mobileVideoDialog.hide();
			downloadErWeiMaDialog.show();
		});

		function setStatus(id, status,person_id){
			$.ajax({
				type: 'post',
				url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/SetInterviewStatus"),$_smarty_tpl);?>
',
				data: {id: id, status: status,person_id:person_id},
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


		$(".notPassBtn").on("click", function(e){
			e.stopPropagation();
			var id = $(this).parent().attr('data-id');
			var person_id = $(this).parent().attr('data-person-id');
			ConfirmBox.confirm('确定要设置为不合适吗？',"提示",function(){
				setStatus(id, 8,person_id);
			},
			function(){
				this.hide();
			},
			{
				width:350,
				confirmBtn: '<button class="chat_button chat_btnsSure">确定</button>',
				cancelBtn: '<button class="chat_button chat_btnsCancel">取消</button>',
			});
		});

		//复面通过
		$(".secondPass").on("click", function(e){
			e.stopPropagation();
			var id = $(this).parent().attr('data-id');
			var person_id = $(this).parent().attr('data-person-id');
			ConfirmBox.confirm('确定要设置为复面通过？',"提示",function(){
				setStatus(id, 7,person_id);
			},
			function(){
				this.hide();
			},
			{
				width:350,
				confirmBtn: '<button class="chat_button chat_btnsSure">确定</button>',
				cancelBtn: '<button class="chat_button chat_btnsCancel">取消</button>',
			}
			);
		});



		checkVideoDialog.query('#hideVideoCheckDiv').on('click', function(){
			checkVideoDialog.hide();
		});

		$('.startVideoBtn').on('click', function(e){
			var $this = $(this);
            var id = $(this).parent().attr('data-id'),
                base_job_id = $(this).parent().attr('data-base-job-id'),
                base_resume_id = $(this).parent().attr('data-base-resume-id'),
                status = $(this).attr('data-status');
            deviceCheckDialog.query("#rtcScanCodeImg").attr("src", "<?php echo smarty_function_get_url(array('rule'=>'/video/RtcScanCode','domain'=>'company'),$_smarty_tpl);?>
?job_id="+base_job_id+"&resume_id="+base_resume_id+"&net_apply_id="+id);
			e.stopPropagation();
				var id = $(this).parent().attr('data-id'),
							status = $(this).attr('data-status');
			aliWebrtc.isSupport().then(function(re) {
				if(re.videoDevice && re.audioDevice){
						console.log(re);
							$.ajax({
								type: 'post',
								async:false,
								url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/StartInterviewV1"),$_smarty_tpl);?>
',
								data: {id: id,need_send_msg : status},
								dataType: 'json',
								success: function (res) {
									if(res.isNeedLogin){
										window.location.reload();
										return;
									}
				
				//					if(res.code == 1 && wait_apply_person_num > 0){
				//						$.confirm('求职者正在面试中，是否跳过？', '提示', function () {
				//							$.ajax({
				//								type: 'post',
				//								url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/SkipInterview"),$_smarty_tpl);?>
',
				//								data: {id: id},
				//								dataType: 'json',
				//								success: function (res1) {
				//									if(!res1.status){
				//										ConfirmBox.timeBomb(res1.msg,{
				//											name: 'fail',
				//											width:'auto',
				//											timeout : 2000
				//										});
				//										return;
				//									}
				//									ConfirmBox.timeBomb(res1.msg,{
				//										name: 'success',
				//										width:'auto',
				//										timeout : 2000
				//									});
				//									setTimeout("window.location.reload()",2000);
				//									return;
				//								}
				//							});
				//						});
				//						return;
				//					}
									if(!res.status){
										ConfirmBox.timeBomb(res.msg,{
											name: 'fail',
											width:'auto',
											timeout : 2000
										});
										setTimeout("window.location.reload()",2000);
										return;
									}
									 
									var url = "https:<?php echo base_lib_Constant::COMPANY_URL_NO_HTTP;?>
/chat/?resume_id=" + res.data.resume_id + "&job_id=" + res.data.job_id + "&net_apply_id=" + res.data.id + "&sid=" + res.data.sid;
									$('.PClinkTo').attr('href',url);
									$('.PClinkTo')[0].click();
									// var newwindow = window.open('about:blank');
									// newwindow.location.href = url;
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

		$("#viedoOne").click(function(){
			window.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/videohall/VideoInterviewHall'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
";
		});

		$(".resumeData").click(function(e){
			e.stopPropagation();
			var resume_id = $(this).attr('data-resumeid');
            var person_id = $(this).attr('data-person-id');
            var apply_id = $(this).attr('data-apply-id');
			$('.resumelinkTo').attr('href',"<?php echo smarty_function_get_url(array('rule'=>'/resume/index'),$_smarty_tpl);?>
?resume_id=" + resume_id + "&sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
&apply_id=" + apply_id)
			$('.resumelinkTo')[0].click();
			// var newwindow = window.open('about:blank');
            // newwindow.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/resume/index'),$_smarty_tpl);?>
?resume_id=" + resume_id + "&sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
&apply_id=" + apply_id;
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
   //          var newwindow = window.open('about:blank');
   //          newwindow.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/resume/index'),$_smarty_tpl);?>
?resume_id=" + resume_id + "&sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
&apply_id=" + apply_id;
			
			$('.moblielinkTo').attr('href',"<?php echo smarty_function_get_url(array('rule'=>'/resume/index'),$_smarty_tpl);?>
?resume_id=" + resume_id + "&sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
&apply_id=" + apply_id)
			$('.moblielinkTo')[0].click();
//            rtcScanStatusDialog_apply_id = apply_id;
//            rtcScanStatusDialog_person_id = person_id;
//            rtcScanStatusDialog_status_value = 0;
//            rtcScanStatusDialog.show();
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
			setStatus(rtcScanStatusDialog_apply_id, rtcScanStatusDialog_status_value, rtcScanStatusDialog_person_id);
		});
		
	})
</script>
	</body>
</html>

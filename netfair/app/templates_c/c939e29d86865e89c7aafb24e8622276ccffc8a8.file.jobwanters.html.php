<?php /* Smarty version Smarty-3.0.7, created on 2020-03-30 09:32:17
         compiled from "app\templates\./videohall/jobwanters.html" */ ?>
<?php /*%%SmartyHeaderCode:211695e814c21dbd393-43827632%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c939e29d86865e89c7aafb24e8622276ccffc8a8' => 
    array (
      0 => 'app\\templates\\./videohall/jobwanters.html',
      1 => 1585378171,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '211695e814c21dbd393-43827632',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="Keywords" content="" />
		<meta name="Description" content="" />
		<title>求职者大厅</title>
		<!–[if lt IE9]>
		<script src="<?php echo smarty_function_version(array('file'=>'html5.js'),$_smarty_tpl);?>
"></script>
		<![endif]–>
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'comback.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'video_eng.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'m_font_style.css'),$_smarty_tpl);?>
">
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
">
		<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'netfair_video_eng.css'),$_smarty_tpl);?>
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
        <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'WdatePicker.js'),$_smarty_tpl);?>
"></script>
	</head>
    <body style="background-color: #f7f7f9 !important;">
    <?php echo $_smarty_tpl->getVariable('head_data')->value;?>

    <?php echo $_smarty_tpl->getVariable('head_nav_data')->value;?>

    <?php $_template = new Smarty_Internal_Template('videohall/hallheadnew.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par','求职者大厅'); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<!--        <img class="bg-cloud1" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/bg-cloud_01.png" alt="背景图">
        <img class="bg-cloud2" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/bg-cloud_02.png" alt="背景图">
        <img class="bg-cloud3" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/bg-cloud_03.png" alt="背景图"> -->
        <div class="videoIhalMain videoIhalMainJobWanters">
		<!-- <div class="guide"> 
			<h3>视频面试小贴士</h3>
			<p>点击"立即沟通"</p>
			<p>约求职者（在线/电话/短信）</p>
			<p>发起远程视频</p>
			<p>完成面试</p>
			<p>反馈结果</p>
		</div> -->
        <form id="frmInvite" method="get" action="<?php echo smarty_function_get_url(array('rule'=>'/videohall/JobWanters/'),$_smarty_tpl);?>
">
            <input type="hidden" name="sid" value="<?php echo $_smarty_tpl->getVariable('sid')->value;?>
" />
            <div class="dataStatScreen dataJobhunter metting">
                <!-- <span>学历</span> -->
                <select name="degree">
                    <option value="">学历</option>
                    <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('all_degree')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
                    <?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->getVariable('degree')->value){?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['val']->value;?>
</option>
                    <?php }else{ ?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['val']->value;?>
</option>
                    <?php }?>
                    <?php }} ?>
                </select>

                <!-- <span>性别</span> -->
                <select name="sex" class="selectSex">
                    <option value="">性别</option>
                    <option value="1" <?php if ($_smarty_tpl->getVariable('sex')->value==1){?>selected<?php }?>>男</option>
                    <option value="2" <?php if ($_smarty_tpl->getVariable('sex')->value==2){?>selected<?php }?>>女</option>
                </select>

               <!-- <span>专业</span>-->

                <input type="text"  class='searchFs' name="key_word" value="<?php echo $_smarty_tpl->getVariable('params')->value['key_word'];?>
" placeholder="搜索求职意向/专业/学校等关键词" autocomplete="off">
                <button type="button" class="statSearchBtn">搜索</button>
                <div class="clear"></div>
            </div>
        </form>
		<div class="schoolMeeting">
			<ul class="col">
				<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
				<li>
					<?php if ($_smarty_tpl->tpl_vars['v']->value['on_line']){?>
					<img class="online" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/schoolnetOnline.png">
					<?php }?>
					<div class="col-left">
						<a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['resume_url'];?>
" target="_blank">
						<div class="col-left-icon">
                            <img class="userIcon" width="50" height="50" src="<?php echo $_smarty_tpl->tpl_vars['v']->value['photo'];?>
"  onerror="javascript:this.src='<?php echo $_smarty_tpl->tpl_vars['v']->value['default_photo'];?>
';">
                            <img class="sexIcon" width="16" height="16"  src="<?php echo $_smarty_tpl->tpl_vars['v']->value['sexIcon'];?>
" >
						</div>
						<h3><?php echo $_smarty_tpl->tpl_vars['v']->value['user_name'];?>
</h3>
						</a>
					</div>
					<div class="col-right">
                        <div class="col-right-info">
                            <h4><?php echo $_smarty_tpl->tpl_vars['v']->value['exp_jobsort'];?>
</h4>
                            <p><?php echo $_smarty_tpl->tpl_vars['v']->value['age'];?>

							<?php if ($_smarty_tpl->tpl_vars['v']->value['work_year']){?><span class="lie-line">·</span><?php echo $_smarty_tpl->tpl_vars['v']->value['work_year'];?>
<?php }?>
							<?php if (!empty($_smarty_tpl->tpl_vars['v']->value['exp_salary'])){?>
								<span class="lie-line">·</span>
								<?php if ($_smarty_tpl->tpl_vars['v']->value['exp_salary']=='negotiable'){?>面议
								<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['v']->value['exp_salary'];?>

								<?php }?>
							<?php }else{ ?><span class="lie-line">·</span>面议
							<?php }?></p>
                            <p class="schoolStyle"><?php echo $_smarty_tpl->tpl_vars['v']->value['school'];?>
<?php if ($_smarty_tpl->tpl_vars['v']->value['degree_name']){?>(<?php echo $_smarty_tpl->tpl_vars['v']->value['degree_name'];?>
)<?php }?></p>
                            <p class="majorStyle"><?php echo $_smarty_tpl->tpl_vars['v']->value['major_desc'];?>
</p>
                        </div>
						
					</div>
					<div class="clear"></div>
					<div class="btn-box">
					<?php if ($_smarty_tpl->getVariable('company_source')->value==1&&$_smarty_tpl->tpl_vars['v']->value['person_source']==1){?>
                        <!--取消活动结束的控制-->
						<a href="javascript:void (0);" data-notice-status="<?php echo (int)$_smarty_tpl->tpl_vars['v']->value['chat_status'];?>
" data-apply-id="" data-job-id="" data-job-effect="" data-need-download = "<?php if ($_smarty_tpl->tpl_vars['v']->value['huibo_show_linkway']){?>0<?php }else{ ?>1<?php }?>" data-arealimit-id = "<?php if ($_smarty_tpl->tpl_vars['v']->value['not_area_limit']){?>1<?php }else{ ?>0<?php }?>" data-resume-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['base_resume_id'];?>
" data-sid="<?php echo $_smarty_tpl->getVariable('sid')->value;?>
" data-netresumeid="<?php echo $_smarty_tpl->tpl_vars['v']->value['netfair_resume_id'];?>
"  class="startVideoBtn chatOneChat">立即沟通 ></a>
						<?php }?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['resume_url'];?>
" target="_blank" class="startVideoBtn1">查看简历 ></a>
					</div>
				</li>
				<?php }} ?>
                <!--
					<li>
						<img class="online" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/schoolnetOnline.png">
						<div class="col-left">
							<div class="col-left-icon">
								<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/defaultWoman.png" >
								<img class="userIcon" width="80" height="80" src="http://192.168.255.5:85/photo/2019-11-13/1113889505_middle.png" >
								<img class="sexIcon" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/schoolnetWomanIcon.png" >
							</div>
							<h3>张玉凤</h3>
						</div>
						<div class="col-right">
							<h4>行政会计行政会计行政会计行政会计行政会计行政会计</h4>
							<p>重庆大学城市科技学院(本科)行政会计行政会计行政会计行政会计行政会计行政会计行政会计</p>
							<p>会计学</p>
							<a href="###">立即沟通</a>
						</div>
					</li>
				-->
            </ul>
            <!--下拉更多加载-->
			<div class="dropload-load" style="display: none;text-align: center;font-size: 17px;line-height: 30px;margin-bottom: 20px;color: #ccc;">
				<span class="loading"></span>
				加载中...
			</div>
		</div>
	<?php if (empty($_smarty_tpl->getVariable('list',null,true,false)->value)){?>
	<div class="noApplyVideo">
	    <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon10.png" >
        <?php if ($_smarty_tpl->getVariable('params')->value['degree']||$_smarty_tpl->getVariable('params')->value['sex']||$_smarty_tpl->getVariable('params')->value['key_word']){?>
        <p>无结果，请重新搜索</p>
        <?php }else{ ?>
	    <p>暂无求职者</p>
        <?php }?>
	</div>
	<?php }?>

	</div>
    <div class="jobVacancyPop" id='selectJobsDiv' style='display:none;'>
		<div class="jobVacancySelect">
            <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('net_jobs')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
			<span data-id="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
"><i></i><?php echo $_smarty_tpl->tpl_vars['v']->value['station'];?>
</span>
            <?php }} ?>
		</div>
	</div> 
    <div class="failureCheckingPop"  id="videoCheckDiv" style="display:none;">
        <ul class="failureCheckingx">
            <li class="videoDevice">
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon16.png" >
                <span>
                    摄像头
                    <em>未检测到摄像头设备，请接入设备后重新检测</em>
                </span>
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon14.png" class="right" >
            </li>
            <li class="audioDevice">
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon17.png" >
                <span>
                    麦克风
                    <em>未检测到麦克风设备，请接入设备后重新检测</em>
                </span>
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon14.png"  class="right">
            </li>
            <li class="audioDevice">
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon18.png" >
                <span>
                    音频设备
                    <em>未检测到音频设备，请接入设备后重新检测</em>
                </span>
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon14.png"  class="right">
            </li>
            <li class="browserDevice">
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon19.png" >
                <span>
                    浏览器检测
                    <em>建议安装推荐浏览器</em>
                </span>
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon14.png"  class="right">
            </li>
        </ul>
        <div class="chromeDown browserDevice">
            <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon15.png" >
            <span>chrome</span>
            <a href="https://www.google.cn/intl/zh-CN/chrome/" target="_blank">下载</a>
        </div>
        <div class="videoPopBtn">
            <a href="javascript:;" id="hideVideoCheckDiv">关闭</a>
        </div>
    </div>
    <?php if ($_smarty_tpl->getVariable('company_source')->value==1){?>
    <?php $_template = new Smarty_Internal_Template("huibo_chat/chat.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('cur','求职者大厅'); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
    <?php }?>
    <?php $_template = new Smarty_Internal_Template("videohall/footer_v1.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
	<script type="text/javascript" language="javascript"  src='<?php echo smarty_function_version(array('file'=>"aliyun-webrtc-sdk-1.9.0.min.js"),$_smarty_tpl);?>
'></script>
    <script type="text/javascript">
    hbjs.use('@confirmBox, @jobDialog', function (m) {
        var ConfirmBox = m['widge.overlay.confirmBox'],
            Dialog = m['widge.overlay.hbDialog'],
            $ = m['jquery'].extend(m['cqjob.jobDialog']);

       /* $('.studentOnlineStatus').each(function(i,n){
            var person_id = $(n).attr('data-person-id');
            $.ajax({
                type: 'post',
                url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/CheckOnline"),$_smarty_tpl);?>
',
                data: {person_id: person_id},
                dataType: 'json',
                success: function (res) {
                    if(res.isNeedLogin){
                        window.location.reload();
                        return;
                    }
                    if(res.is_online)
                        $(n).show();
                }
            });
        });*/
        var close = '×';
        var width = 455;
        var zIndex = 9999;
        var title = '职位选择';
        
        var selectJobDialog = new Dialog({
            close: close,
            idName: 'selectJob_dialog',
            title: title,
            width: width,
            zIndex: zIndex,
            content:$("#selectJobsDiv").html()
        }),
        checkVideoDialog = new Dialog({
            close: close,
            idName: 'checkVideo_dialog',
            title: '提示',
            width: width,
            zIndex: zIndex,
            content:$("#videoCheckDiv").html()
        });
        
        
//        $('body').on('click','.startVideoBtn', function(){
//            var person_id = $(this).attr('data-person-id');
//            var resume_id = $(this).attr('data-resume_id');
//           /* var sid =  '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
';
//            $.ajax({
//                type: 'post',
//                url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/haveapply"),$_smarty_tpl);?>
',
//                data: {sid:sid,person_id: person_id},
//                dataType: 'json',
//                success: function (res) {
//                     // console.log(res);
//                    if(200==res.code){
//                        checkVideo('',res.data.job_id,person_id);
//                    }else{
//                        selectJobDialog.setContent($("#selectJobsDiv").html()).show();
//                        selectJobDialog.query('.jobVacancySelect span').on('click', function(){
//                            var job_id = $(this).attr('data-id');
//                            $('.jobVacancySelect span.cut').each(function(i,n){
//                                $(n).removeClass('cut');
//                            });
//                            $(this).addClass('cut');
//                            checkVideo('',job_id,person_id);
//                            selectJobDialog.hide();
//                        });
//                    }
//                }
//            });*/
//
//            selectJobDialog.setContent($("#selectJobsDiv").html()).show();
//            selectJobDialog.query('.jobVacancySelect span').on('click', function(){
//                var job_id = $(this).attr('data-id');
//                $('.jobVacancySelect span.cut').each(function(i,n){
//                    $(n).removeClass('cut');
//                });
//                $(this).addClass('cut');
//                // AliRtcEngine.isSupport().then(re => {
//                //     checkVideo(re,job_id,person_id);
//                // }).catch(err => {
//                //     checkVideo(err,job_id,person_id);
//                // });
//                checkVideo('',job_id,person_id,resume_id);
//                selectJobDialog.hide();
//            });
//        });
        
        function checkVideo(msg,job_id,person_id,resume_id){
           /* if(!msg.isSupported){
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
                
                checkVideoDialog.show();
                return;
            }*/

            $.ajax({
                type: 'post',
                url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/StartInterview"),$_smarty_tpl);?>
',
                data: {job_id: job_id, sid: '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
', person_id: person_id,base_resume_id:resume_id},
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
                            timeout : 5000
                        });
                        return;
                    }
                    var newwindow = window.open('about:blank');
                    newwindow.location.href = "https:<?php echo base_lib_Constant::COMPANY_URL_NO_HTTP;?>
/chat/?resume_id=" + res.data.resume_id + "&job_id=" + res.data.job_id + "&net_apply_id=" + res.data.id + "&sid=" + res.data.sid;
                    window.location.reload();
                }
            });
        }
        
        checkVideoDialog.query('#hideVideoCheckDiv').on('click', function(){
            checkVideoDialog.hide();
        });
        
        $('.statSearchBtn').on('click', function(){
            $('#frmInvite').get(0).submit();
        });
    });
    </script>
    <script type="text/javascript">
   /* let str = $('.schoolMeeting .col').html();
    for (let index = 0; index < 4; index++) {
        $('.schoolMeeting .col').append(str);
    }*/
    var next_page = 1;
    var sid = "<?php echo $_smarty_tpl->getVariable('params')->value['sid'];?>
";
    var sex = "<?php echo $_smarty_tpl->getVariable('params')->value['sex'];?>
";
    var key_word = "<?php echo $_smarty_tpl->getVariable('params')->value['key_word'];?>
";
    var degree = "<?php echo $_smarty_tpl->getVariable('params')->value['degree'];?>
";
   var company_source = "<?php echo $_smarty_tpl->getVariable('company_source')->value;?>
";
    var shuang_xuan_time_class_name = "<?php echo $_smarty_tpl->getVariable('shuang_xuan_time_class_name')->value;?>
";

        //分页
   var waiting_page = false;
   var waiting_request = false;
   var cur_page = 2;
		$(window).scroll(function(){
            if($(document).scrollTop() >= $(document).height() - $(window).height() ){
                if(waiting_page){
                    return false;
                }
				if(waiting_request){
				    return false;
				}
				if($('.noApplyVideo').css('display') == 'block'){
					return false;
				}
                $('.dropload-load').show();
                //获取内容
				waiting_request=true;
                $.ajax({
                    type:'POST',
                    url: '/videohall/JobWantersV2Json',
                    data:{sid:sid,sex:sex,key_word:key_word,degree:degree,page:cur_page},
                    dataType:"json",
                    success : function(json){
						// console.log(JSON.stringify(json));
                        $('.dropload-load').hide();
                        //console.log(json);
                        waiting_request = false;
                        if(json.length > 0){
							console.log(json)
                            var html = '';
                            for(var i=0;i<json.length;i++){
                                var huibo_show_linkway = json[i].huibo_show_linkway ? 0 : 1;
                                html +="<li>";
                                if(1==json[i].on_line){
                                    html +='<img class="online" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/schoolnetOnline.png">';
                                }
                                html += '<div class="col-left">';
                                html += '<a href="'+json[i].resume_url+'" target="_blank">';
                                html += '<div class="col-left-icon">';
                                html += '<img class="userIcon" width="50" height="50" src="'+json[i].photo+'"  onerror="javascript:this.src=\''+json[i].default_photo+'\';" >';
                                html += '<img class="sexIcon" width="16" height="16" src="'+json[i].sexIcon+'" >';
                                html += '</div>';
                                html += '<h3>'+json[i].user_name+'</h3>';
                                html += '</a> </div>';
								html += '<div class="col-right"><div class="col-right-info">';
                                html += '<h4>'+json[i].exp_jobsort+'</h4>';
								html += '<p>'+ json[i].age;
								if(json[i].work_year){
									html +='<span class="lie-line">·</span>'+ json[i].work_year;
								}
								if(json[i].exp_salary){
									html += '<span class="lie-line">·</span>';
									 if(json[i].exp_salary == 'negotiable'){
									 	html += '面议';
									 }else{
									 	html += json[i].exp_salary;
									 }
								}else{
									html += '<span class="lie-line">·</span>面议';
								}
                                html += '</p><p>'+json[i].school+'('+json[i].degree_name+')</p>';
                                html += ' <p>'+json[i].major_desc+'</p></div></div>';
                                // if(shuang_xuan_time_class_name!='videoActivityEnd' && company_source == 1 && json[i].person_source == 1){
									//叶延生暂注释
								html += '<div class="clear"></div>';
								html += '<div class="btn-box">';
								if(company_source == 1 && json[i].person_source == 1){
                                    // html +=  '<a href="javascript:void (0);"  data-person-id="'+json[i].person_id+'"   class="startVideoBtn">立即沟通</a>';
									html +=  '<a href="javascript:void (0);" data-notice-status="'+json[i].chat_status+'" data-need-download = "'+huibo_show_linkway+'" data-apply-id="" data-job-id="" data-job-effect="" data-resume-id="'+ json[i].base_resume_id +'" class="startVideoBtn chatOneChat">立即沟通 ></a>'
                                }
//                                if(company_source != 1 && json[i].person_source != 1) {
                                html +=  '<a href="'+json[i].resume_url+'" target="_blank" class="startVideoBtn1">查看简历 ></a>';
//                                }
                                html +=  '</div></li>';
                            }
                             cur_page++;
                            // console.log('滚动添加' + html);
                            $('.schoolMeeting .col').append(html);
                        }else{
							var str = "<div style='text-align: center;color:#ccc;padding:10px 0;font-size: 14px;'>暂无更多求职者</div>";
							$('.schoolMeeting').html($('.schoolMeeting').html()+str);
							waiting_page = true;
                        }

                    }
                });
            }
        });
    </script>
	</body>
</html>

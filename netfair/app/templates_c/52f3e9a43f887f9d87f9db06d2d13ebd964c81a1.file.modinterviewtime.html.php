<?php /* Smarty version Smarty-3.0.7, created on 2020-03-26 17:03:36
         compiled from "app\templates\./videohall/modinterviewtime.html" */ ?>
<?php /*%%SmartyHeaderCode:110225e7c6fe863ee88-09472572%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '52f3e9a43f887f9d87f9db06d2d13ebd964c81a1' => 
    array (
      0 => 'app\\templates\\./videohall/modinterviewtime.html',
      1 => 1585194168,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '110225e7c6fe863ee88-09472572',
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
		<meta charset="utf-8">
		<title>汇博人才网-面试时间设置</title>
		<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'video_eng.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'m_font_style.css'),$_smarty_tpl);?>
">
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
">
		<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
"></script>
        <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'WdatePicker.js'),$_smarty_tpl);?>
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
        <script language="javascript" type="text/javascript">
            var disable_dates = new Array();
            <?php if ($_smarty_tpl->getVariable('disable_dates')->value){?>
            <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('disable_dates')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
            disable_dates.push('<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
');
            <?php }} ?>
            <?php }?>
            //时间插件
            var interview_time = {
                skin: 'whyGreen',
                dateFmt: 'yyyy-MM-dd',
                minDate: '<?php echo $_smarty_tpl->getVariable('min_date')->value;?>
',
                maxDate: '<?php echo $_smarty_tpl->getVariable('max_date')->value;?>
', //最大日期
                readOnly: true,
                onpicking: function (datas) {
//                    datas.cal.newdate.y + "-" + datas.cal.newdate.M + "-" + datas.cal.newdate.d;
                },
                disabledDates : disable_dates
            };
        </script>
	</head>
	<body>
<!-- 新增面试时间 -->
<div class="newInterviewTime" style="padding:10px 0 0 10px">
	<div class="clearFixWrap">
		<div class="viewTimeLeft">面试日期</div>
		<div class="viewTimeRight">
			<input type="text" readonly="readonly" name="interview_date" placeholder="选择日期" onclick="WdatePicker(interview_time)" value="<?php if ($_smarty_tpl->getVariable('interview_data')->value['date']){?><?php echo $_smarty_tpl->getVariable('interview_data')->value['date'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('min_date')->value;?>
<?php }?>"/>
		</div>
	</div>
	<div class="clearFixWrap">
		<div class="viewTimeLeft">面试时间</div>
		<div class="viewTimeRight">
            <?php if (!$_smarty_tpl->getVariable('id')->value||$_smarty_tpl->getVariable('interview_data')->value['time_type']==1){?>
			<span>上午：</span>
			<select name="start_time1">
				<option value ="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('am_times')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
				<option value ="<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
" <?php if ($_smarty_tpl->getVariable('interview_data')->value['start_time_str']==$_smarty_tpl->tpl_vars['v']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</option>
                <?php }} ?>
			</select>
			<b>~</b>
			<select name="end_time1">
				<option value ="">请选择</option>
				<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('am_times')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
				<option value ="<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
" <?php if ($_smarty_tpl->getVariable('interview_data')->value['end_time_str']==$_smarty_tpl->tpl_vars['v']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</option>
                <?php }} ?>
			</select>
            <?php }?>
            
            <?php if (!$_smarty_tpl->getVariable('id')->value||$_smarty_tpl->getVariable('interview_data')->value['time_type']==2){?>
			<div class="clear" <?php if (!$_smarty_tpl->getVariable('id')->value){?>style="padding-top: 10px;"<?php }?>></div>
			<span>下午：</span>
			<select name="start_time2">
				<option value ="">请选择</option>
				<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('pm_times')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
				<option value ="<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
" <?php if ($_smarty_tpl->getVariable('interview_data')->value['start_time_str']==$_smarty_tpl->tpl_vars['v']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</option>
                <?php }} ?>
			</select>
			<b>~</b>
			<select name="end_time2">
				<option value ="">请选择</option>
				<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('pm_times')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
				<option value ="<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
" <?php if ($_smarty_tpl->getVariable('interview_data')->value['end_time_str']==$_smarty_tpl->tpl_vars['v']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</option>
                <?php }} ?>
			</select>
            <?php }?>
		</div>
	</div>
	<div class="videoPopBtn">
		<a href="javascript:;" id="videoPopBtnx">保存</a>
        <a href="javascript:window.history.back(-1);" style="background:#f3f3f3; color: #444;">取消</a>
	</div>
</div>

<script type="text/javascript">
hbjs.use('@confirmBox, @jobDialog', function (m) {
    var ConfirmBox = m['widge.overlay.confirmBox'];
    
	$('#videoPopBtnx').click(function(){
		var data = {id : '<?php echo $_smarty_tpl->getVariable('id')->value;?>
',
                    sid : '<?php echo $_smarty_tpl->getVariable('sid')->value;?>
',
                    interview_date : $('input[name=interview_date]').val(),
                    start_time1 : $('select[name=start_time1]').val(),
                    start_time2 : $('select[name=start_time2]').val(),
                    end_time1 : $('select[name=end_time1]').val(),
                    end_time2 : $('select[name=end_time2]').val()}
        <?php if ($_smarty_tpl->getVariable('id')->value&&$_smarty_tpl->getVariable('interview_data')->value['time_type']==2){?>
            data.start_time1 = $('select[name=start_time2]').val();
            data.end_time1 = $('select[name=end_time2]').val();
        <?php }?>
        $.ajax({
            type: 'post',
            url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/ModCompanyInterviewTimeDo"),$_smarty_tpl);?>
',
            data: data,
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
                ConfirmBox.timeBomb(res.msg,{
                    name: 'success',
                    width:'auto',
                    timeout : 2000
                });
                window.location.href = '<?php echo smarty_function_get_url(array('rule'=>"/videohall/InterviewTimeList"),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
';
            }
        });
	});
});
</script>
	</body>
</html>

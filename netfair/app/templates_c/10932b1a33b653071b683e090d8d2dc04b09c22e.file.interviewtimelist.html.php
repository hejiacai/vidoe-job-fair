<?php /* Smarty version Smarty-3.0.7, created on 2020-03-23 10:17:58
         compiled from "app\templates\./videohall/interviewtimelist.html" */ ?>
<?php /*%%SmartyHeaderCode:14705e781c5630ea42-21195220%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '10932b1a33b653071b683e090d8d2dc04b09c22e' => 
    array (
      0 => 'app\\templates\\./videohall/interviewtimelist.html',
      1 => 1584689965,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14705e781c5630ea42-21195220',
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
	<body>
		<div class="jobVacancyPop" style="width:600px">
			<div class="jobVacancyAdd">
				<span style=" color: #ff1a1a;">求职者仅能在设置的面试时间段内进行申请视频面试</span>
				<a href='<?php echo smarty_function_get_url(array('rule'=>"/videohall/ModCompanyInterviewTime"),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
'>新建</a>
			</div>
			<div style="max-height: 400px; overflow-y: auto;">
			<table class="jobVacancyTab">
                <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
				<tr>
					<td><?php echo $_smarty_tpl->tpl_vars['v']->value['date'];?>
  <?php echo $_smarty_tpl->tpl_vars['v']->value['time_type_str'];?>
   <?php echo $_smarty_tpl->tpl_vars['v']->value['time_str'];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['v']->value['status_str'];?>
</td>
					<td>
						<?php if ($_smarty_tpl->tpl_vars['v']->value["status_str"]!="进行中"){?><a href='<?php echo smarty_function_get_url(array('rule'=>"/videohall/ModCompanyInterviewTime"),$_smarty_tpl);?>
?id=<?php echo $_smarty_tpl->tpl_vars['v']->value["id"];?>
&sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
'>修改</a><?php }?>
						<?php if ($_smarty_tpl->tpl_vars['v']->value["status_str"]!="进行中"){?><a href="javascript:;" class='del' data-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">删除</a><?php }?>
					</td>
				</tr>
                <?php }} ?>
			</table>
			</div>
			<div class="videoPopBtn">
				<a href="javascript:;" id="videoPopBtnz">保存</a>
			</div>
		</div>

<!-- 新增面试时间 -->

<script type="text/javascript">
hbjs.use('@confirmBox, @jobDialog', function (m) {
    var ConfirmBox = m['widge.overlay.confirmBox'],
        $ = m['jquery'].extend(m['cqjob.jobDialog']);
	$('#videoPopBtnz').click(function(){
        parent.location.reload();
//		parent.interviewTimeDialog.hide();
	});
    $('.del').on('click', function(){
        var id = $(this).attr('data-id');
        $.confirm('是否删除该场面试？', '提示', function () {
            $.ajax({
                type: 'post',
                url: '<?php echo smarty_function_get_url(array('rule'=>"/videohall/DelCompanyInterviewTime"),$_smarty_tpl);?>
',
                data: {id: id},
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
&from=del';
                }
            });
        });
    });
});
</script>
	</body>
</html>

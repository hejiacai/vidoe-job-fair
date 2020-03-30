<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:19:10
         compiled from "E:\slightPHP\basecore\service\upload/../../templates/common/upload/up_style4.html" */ ?>
<?php /*%%SmartyHeaderCode:70835e70339e7eeb74-12937906%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd8c016acf0015a1cdc7f8b243a3070c0c66c1b43' => 
    array (
      0 => 'E:\\slightPHP\\basecore\\service\\upload/../../templates/common/upload/up_style4.html',
      1 => 1584411529,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '70835e70339e7eeb74-12937906',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/webuploader/css/uploadstyle2.css"/>
<div class="uploader2 wu-example <?php echo $_smarty_tpl->getVariable('upload_id')->value;?>
">
	<div class="queueList">
	</div>
	<div class="uploaderTips">
		<span class="tipTxt gray">
			已上传图片<span class="green" id="<?php echo $_smarty_tpl->getVariable('has_uplaod_pic_num_id')->value;?>
">0</span>/<span class="red"><?php echo $_smarty_tpl->getVariable('fileNumLimit')->value;?>
</span>，每张最大<?php echo $_smarty_tpl->getVariable('fileSizeLimit_msg_v2')->value;?>
，支持<?php echo $_smarty_tpl->getVariable('ext')->value;?>
格式
		</span>
	</div>
	<div class="error_msg" style="display: none"></div>

</div>
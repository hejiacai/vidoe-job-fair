<?php /* Smarty version Smarty-3.0.7, created on 2020-03-26 10:56:56
         compiled from "app\templates\shuangxuannet/center_company_ajax.html" */ ?>
<?php /*%%SmartyHeaderCode:5785e7c19f80e2479-10683219%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a48463a015e6b8f4b052c3b9b7d629048b0de62f' => 
    array (
      0 => 'app\\templates\\shuangxuannet/center_company_ajax.html',
      1 => 1585107109,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5785e7c19f80e2479-10683219',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('list')->value){?>
	<div class="businessHallList">
	<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
?>
		<div class="businessHallListx">
			<div class="businessHallLix">
				<img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['company_logo_path_name'];?>
" />
				<p>
					<b><?php echo $_smarty_tpl->tpl_vars['item']->value['company_name'];?>
</b>
					<span><?php echo $_smarty_tpl->tpl_vars['item']->value['size_text'];?>

						<i>|</i><?php echo $_smarty_tpl->tpl_vars['item']->value['property_text'];?>

						<i>|</i><?php echo $_smarty_tpl->tpl_vars['item']->value['calling_text'];?>
</span>
				</p>
			</div>
			<span class="businessHallLix02">
			
			<?php if ($_smarty_tpl->tpl_vars['item']->value['company_reward_data']){?>
				<?php  $_smarty_tpl->tpl_vars['reward'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['item']->value['company_reward_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['reward']->key => $_smarty_tpl->tpl_vars['reward']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['reward']->key;
?>
					<?php if ($_smarty_tpl->tpl_vars['k']->value!=count($_smarty_tpl->tpl_vars['item']->value['company_reward_data'])-1){?>
						<?php echo $_smarty_tpl->tpl_vars['reward']->value;?>
、
					<?php }else{ ?>
						<?php echo $_smarty_tpl->tpl_vars['reward']->value;?>

					<?php }?>
				<?php }} ?>
			<?php }?>
			
			</span>
			<div class="businessHallLix03">
				<?php  $_smarty_tpl->tpl_vars['job'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('jobs')->value[$_smarty_tpl->tpl_vars['item']->value['company_id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['job']->key => $_smarty_tpl->tpl_vars['job']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['job']->key;
?>
				<?php if ($_smarty_tpl->tpl_vars['k']->value<3){?>
				<a href="javascript:;">
					<span>
					<?php $_smarty_tpl->tpl_vars['_strlen'] = new Smarty_variable(14, null, null);?>
					<?php if ($_smarty_tpl->tpl_vars['item']->value['interview_status']==2&&$_smarty_tpl->tpl_vars['job']->value['is_end']){?>
					<?php $_smarty_tpl->tpl_vars['_strlen'] = new Smarty_variable(10, null, null);?>
					(已停招)
					<?php }?>
					<?php if (mb_strlen($_smarty_tpl->tpl_vars['job']->value['station'])>$_smarty_tpl->getVariable('_strlen')->value){?>
						<?php echo mb_substr($_smarty_tpl->tpl_vars['job']->value['station'],0,$_smarty_tpl->getVariable('_strlen')->value,'utf-8');?>

					<?php }else{ ?>
						<?php echo $_smarty_tpl->tpl_vars['job']->value['station'];?>

					<?php }?>
					</span>
					<em><?php echo $_smarty_tpl->tpl_vars['job']->value['min_salary'];?>
-<?php echo $_smarty_tpl->tpl_vars['job']->value['max_salary'];?>
</em>
				</a>
				<?php }?>			
				<?php }} ?>
			</div>
			<div class="businessHallLix04">
				<a href="javascript:;" class="businessHallBtn"><i></i>申请视频面试</a>
				<?php if (count($_smarty_tpl->getVariable('jobs')->value[$_smarty_tpl->tpl_vars['item']->value['company_id']])>3){?>
				<a href="javascript:;" class="businessHallMore">查看更多职位>></a>
				<?php }?>
			</div>
		</div>	
	<?php }} ?>
	</div>
<?php }else{ ?>
    <?php if ($_smarty_tpl->getVariable('page')->value==1){?>
		<div class="noApplyVideo">
		    <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon10.png" >
			暂无数据  
		</div>
    <?php }else{ ?>
		<div class="noApplyVideo">
			暂无更多数据
		</div>
    <?php }?>
<?php }?>

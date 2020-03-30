<?php /* Smarty version Smarty-3.0.7, created on 2020-03-24 14:13:20
         compiled from "app\templates\common/page/default.html" */ ?>
<?php /*%%SmartyHeaderCode:272145e79a500c790d2-09304643%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '988146d2c0162277401c8a3bae898357fdf7d1ba' => 
    array (
      0 => 'app\\templates\\common/page/default.html',
      1 => 1584689965,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '272145e79a500c790d2-09304643',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('total_page')->value!=0){?>
<div class="page">
		<?php if ($_smarty_tpl->getVariable('prepg')->value){?>
			<a href="<?php if ($_smarty_tpl->getVariable('prepg')->value){?><?php echo $_smarty_tpl->getVariable('prepg')->value;?>
<?php }else{ ?>javascript:void(0);<?php }?>">&lt;</a>
		<?php }?>
		
		<?php if ($_smarty_tpl->getVariable('pages')->value){?>
	        <?php  $_smarty_tpl->tpl_vars['page'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('pages')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['page']->key => $_smarty_tpl->tpl_vars['page']->value){
?>
				<?php if ($_smarty_tpl->tpl_vars['page']->value['page']==1){?>
					<a <?php if ($_smarty_tpl->getVariable('currpage')->value==1){?> href="javascript:void(0);" class="cu" <?php }else{ ?> href="<?php echo $_smarty_tpl->tpl_vars['page']->value['url'];?>
" <?php }?>>1</a>		
					<?php if ($_smarty_tpl->getVariable('currpage')->value>6){?>
					<a href="javascript:void(0)" class="morePage">...</a>
					<?php }?>
				<?php }?>
	
				<?php if (($_smarty_tpl->tpl_vars['page']->value['page']>=$_smarty_tpl->getVariable('page_show_start')->value)&&($_smarty_tpl->tpl_vars['page']->value['page']<=$_smarty_tpl->getVariable('page_show_end')->value)){?>
					<a <?php if ($_smarty_tpl->getVariable('currpage')->value==$_smarty_tpl->tpl_vars['page']->value['page']){?> href="javascript:void(0);" class="cu" <?php }else{ ?> href="<?php echo $_smarty_tpl->tpl_vars['page']->value['url'];?>
" <?php }?>><?php echo $_smarty_tpl->tpl_vars['page']->value['page'];?>
</a>
				<?php }?>
	
				<?php if ($_smarty_tpl->tpl_vars['page']->value['page']==$_smarty_tpl->getVariable('total_page')->value){?>
					<?php if ($_smarty_tpl->getVariable('currpage')->value+4<$_smarty_tpl->getVariable('total_page')->value){?>
						<a href="javascript:void(0)" class="morePage">...</a>
					<?php }?>
					
					<?php if ($_smarty_tpl->getVariable('total_page')->value>1){?>
						<a <?php if ($_smarty_tpl->getVariable('currpage')->value==$_smarty_tpl->tpl_vars['page']->value['page']){?> href="javascript:void(0);" class="cu" <?php }else{ ?> href="<?php echo $_smarty_tpl->tpl_vars['page']->value['url'];?>
" <?php }?>><?php echo $_smarty_tpl->tpl_vars['page']->value['page'];?>
</a>
					<?php }?>
				<?php }?>
	        <?php }} ?>
	    <?php }?>
        
        <?php if ($_smarty_tpl->getVariable('nextpg')->value){?> 
        	<a href="<?php if ($_smarty_tpl->getVariable('nextpg')->value){?><?php echo $_smarty_tpl->getVariable('nextpg')->value;?>
<?php }else{ ?>javascript:void(0);<?php }?>">&gt;</a>
        <?php }?>
</div>
<?php }?>
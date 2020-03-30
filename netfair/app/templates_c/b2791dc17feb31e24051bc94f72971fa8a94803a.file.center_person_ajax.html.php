<?php /* Smarty version Smarty-3.0.7, created on 2020-03-20 17:12:36
         compiled from "app\templates\shuangxuannet/center_person_ajax.html" */ ?>
<?php /*%%SmartyHeaderCode:325525e748904f41582-09705064%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b2791dc17feb31e24051bc94f72971fa8a94803a' => 
    array (
      0 => 'app\\templates\\shuangxuannet/center_person_ajax.html',
      1 => 1584689965,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '325525e748904f41582-09705064',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('list')->value){?>
<?php if ($_smarty_tpl->getVariable('page')->value==1){?>
	<div class="businessHallList">
<?php }?>
        <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
			<div class="jobSeekersHall">
				<div class="jobSeekersHallLeft">
					<img src="<?php if ($_smarty_tpl->tpl_vars['val']->value['photo']){?><?php echo $_smarty_tpl->tpl_vars['val']->value['photo_name'];?>
<?php }else{ ?>
				<?php if ($_smarty_tpl->tpl_vars['val']->value['sex']==1){?><?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/defaultMan.png<?php }elseif($_smarty_tpl->tpl_vars['val']->value['sex']==2){?><?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/defaultWoman.png<?php }?><?php }?>"
						 onerror="javascript:this.src='<?php if ($_smarty_tpl->tpl_vars['val']->value['sex']==1){?><?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/defaultMan.png<?php }elseif($_smarty_tpl->tpl_vars['val']->value['sex']==2){?><?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/defaultWoman.png<?php }?>'">
					<span><?php echo $_smarty_tpl->tpl_vars['val']->value['user_name_desc'];?>
</span>
					<i class="<?php if ($_smarty_tpl->tpl_vars['val']->value['sex']==1){?>boy<?php }elseif($_smarty_tpl->tpl_vars['val']->value['sex']==2){?>girl<?php }?>"></i>
				</div>
				<div class="jobSeekersHallRight">
					<span class="hallName01" title="<?php echo $_smarty_tpl->tpl_vars['val']->value['station'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['station'];?>
</span>
					<span class="hallName02"><?php echo $_smarty_tpl->tpl_vars['val']->value['age'];?>
岁 <?php if ($_smarty_tpl->tpl_vars['val']->value['work_year']){?> · <?php echo $_smarty_tpl->tpl_vars['val']->value['work_year'];?>
<?php }?> <?php if ($_smarty_tpl->tpl_vars['val']->value['salary_desc']){?>· <?php echo intval($_smarty_tpl->tpl_vars['val']->value['salary_desc'])/1000;?>
k及以上<?php }?></span>
					<span class="hallName03"><?php if ($_smarty_tpl->tpl_vars['val']->value['school']){?><?php echo $_smarty_tpl->tpl_vars['val']->value['school'];?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['val']->value['degree_desc']){?>(<?php echo $_smarty_tpl->tpl_vars['val']->value['degree_desc'];?>
)<?php }else{ ?> <?php }?></span>
					<span class="hallName04"><?php echo $_smarty_tpl->tpl_vars['val']->value['major_desc'];?>
</span>
					<a href="javascritp:void(0);" class="hallName05"><i></i>立即视频面试</a>
				</div>
			</div>
        <?php }} ?>
<?php if ($_smarty_tpl->getVariable('page')->value==1){?>
	</div>
<?php }?>
    <?php echo $_smarty_tpl->getVariable('pager')->value;?>

<?php }else{ ?>
    <?php if ($_smarty_tpl->getVariable('page')->value==1){?>
		<div class="noApplyVideo">
			<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon10.png">
			暂无数据
		</div>
		<script>
            var init_loadingPage = 1;
		</script>
    <?php }else{ ?>
		<div class="noApplyVideo">
			暂无更多数据
		</div>
        <script>
            var init_loadingPage_nodata = 1;
        </script>
    <?php }?>
	<script>
        setTimeout(function () {
            loadingPage = 2;
        }, 200);
	</script>
<?php }?>

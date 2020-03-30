<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:20:05
         compiled from "app\templates\calling.html" */ ?>
<?php /*%%SmartyHeaderCode:111715e7033d5065ac7-98647358%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '38816f5eead285a83a17b0e385c6e58b45648836' => 
    array (
      0 => 'app\\templates\\calling.html',
      1 => 1573447019,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '111715e7033d5065ac7-98647358',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<style>
	.hangye{width:680px;padding:15px}
	.hangye table{border:1px solid #eee}
	.hangye th,.hangye td{border-bottom:1px solid #eee;padding:5px 0;background-color: #f8f8f8}
	.hangye th{width:140px;text-align: center;font-size:14px;font-weight:bold;border-right:1px solid #eee}
	.hangye td{padding-left:10px;padding-right:10px;font-size:12px}
	.hangye td label{display: inline-block;padding-right:15px;margin:5px 0}
	.hangye td label input{vertical-align: middle;margin-right:3px}
	.hangye tr:nth-child(2n) td,.hangye tr:nth-child(2n) th{background-color: #fff}
</style>
<div style="padding:15px" class="hangye">
	<div style="max-height: 400px;#height:400px;overflow-y:auto">
	<table width="100%">
		<?php  $_smarty_tpl->tpl_vars['calling'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('callings')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['calling']->key => $_smarty_tpl->tpl_vars['calling']->value){
?>
                    <tr>
			<th><?php echo $_smarty_tpl->tpl_vars['calling']->value['calling_name'];?>
</th>
			<td>
                            <?php  $_smarty_tpl->tpl_vars['subItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['calling']->value['subItem']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['subItem']->key => $_smarty_tpl->tpl_vars['subItem']->value){
?>
				<label><input <?php if ($_smarty_tpl->tpl_vars['subItem']->value['calling_id']==$_smarty_tpl->getVariable('calling_id')->value){?>disabled="disabled"<?php }?> name="calling" value="<?php echo $_smarty_tpl->tpl_vars['subItem']->value['calling_id'];?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['subItem']->value['calling_name'];?>
" type="radio" /><span <?php if ($_smarty_tpl->tpl_vars['subItem']->value['calling_id']==$_smarty_tpl->getVariable('calling_id')->value){?>style="color:#ccc"<?php }?>><?php echo $_smarty_tpl->tpl_vars['subItem']->value['calling_name'];?>
</span></label>
                             <?php }} ?>
			</td>
		</tr>
                <?php }} ?>
	</table>
	</div>
	<div class="dialogFooter" style="background-color: #fff;margin-top: 15px;border-top: 0px">
    	<!--<a id="btnSortSave" href="javascript:void(0);" class="btn1 btnsF12">确定</a>-->
    	<a id="btnSortClose" href="javascript:void(0);" class="btn3 btnsF12">取消</a>
    </div>
</div>
<script>
if(window.$ != undefined){
	window.fix$ = $;
}
try {
	hbjs.use(factory);
} catch(e) {
	factory($);
}

function factory($){
     var calling = {
		initialize:function(){
			var btnSortClose = $("#btnSortClose");
			$('.hangye').on('change', 'input', function(e){
				var target = $(e.currentTarget);
				var calling_id = target.val();
				var calling_name = target.attr('data-name');
				var type = <?php echo $_smarty_tpl->getVariable('type')->value;?>
;
				updateCalling(calling_id, calling_name, type, $);
				btnSortClose.trigger('click');
			});
			btnSortClose.on('click', function(){
				 if(window.fix$ && window.fix$.fn.closeDialog){
					 $(this).closeDialog();
				 } else {
				 	 $(this).closeDialog();
				 }
			})
		}            
    }
    calling.initialize();
}

</script>
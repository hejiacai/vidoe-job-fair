<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:19:10
         compiled from "app\templates\./modify_notice.html" */ ?>
<?php /*%%SmartyHeaderCode:182925e70339e84a156-08658611%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '51ebea444c7a243777cae09903c7f211491697e2' => 
    array (
      0 => 'app\\templates\\./modify_notice.html',
      1 => 1584332292,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '182925e70339e84a156-08658611',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'m_font_style.css'),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'index_newlayer.css'),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'uploadstyle2.css'),$_smarty_tpl);?>
" />
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'upload.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.cookie.js'),$_smarty_tpl);?>
"></script>
<div class="my_newMask" style="display: none"></div>
<div class="my_newLayer" style="display: none;">
	<form method="post" id="modify_submit" action="/index/SaveModifyNotice">
	<p class="my_title">完善企业信息，提升招聘效果</p>
	<p class="my_smtitle">求职者找工作时，十分关心企业以下信息，完善它们&nbsp;<span class="my_red">增加简历投递量！</span></p>
	<?php if ($_smarty_tpl->getVariable('need_alert_modify')->value['franace']){?>
	<div class="caption clearfix">
		<div class="content_l">
			<span class="my_contenttitle">融资阶段</span>
		</div>
		<div class="content_r">
			<ul class="financing">
				<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('franaces')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
				<li data-id="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
"><span></span><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</li>
				<?php }} ?>
			</ul>
		</div>
	</div>
	<?php }?>

	<?php if ($_smarty_tpl->getVariable('need_alert_modify')->value['photolist']){?>
	<div class="caption clearfix">
		<div class="content_l">
			<span class="my_contenttitle">企业办公环境</span>
		</div>
		<div class="content_r">
			<div class="feedback-img">
				<?php echo $_smarty_tpl->getVariable('up_img_html')->value['html'];?>

			</div>
		</div>
	</div>
	<?php }?>

	<?php if ($_smarty_tpl->getVariable('need_alert_modify')->value['project']){?>
	<div class="caption clearfix">
		<div class="content_l">
			<span class="my_contenttitle">产品/项目介绍</span>
		</div>
		<div class="content_r" id="list1">
			<span class="my_add">添加&nbsp;<span class="icon-resume_drop-down"></span></span>
			<div class="my_temp">
				<div class="my_content">
					<div>
						<span class="my_content_l" placeholder="">名称</span>
						<input type="text" name="project_name" class="nameinput" placeholder="请输入15字以内产品/项目名称" maxlength="15">
					</div>
					<div class="content_two">
						<span class="my_content_l">简介</span>
						<textarea class="nameinput_two" name="project_info" placeholder="请输入100字以内产品/项目简介" maxlength="100"></textarea>
					</div>
					<div class="content_three">
						<span class="my_content_l">图片</span>
						<div class="my_pic">
							<?php echo $_smarty_tpl->getVariable('project_img_html')->value['html'];?>

						</div>
					</div>
				</div>
			</div>
			<div class="addcontent">
				<span class="icon-AI-_-07 jxtj"></span>
				<span style="margin-left: 10px;">继续添加</span>
			</div>
		</div>
	</div>
	<?php }?>

	<?php if ($_smarty_tpl->getVariable('need_alert_modify')->value['manager']){?>
	<div class="caption clearfix">
		<div class="content_l">
			<span class="my_contenttitle">老板/高管介绍</span>
		</div>
		<div class="content_r">
			<span class="my_add_two">添加&nbsp;<span class="icon-resume_drop-down"></span></span>
			<div class="my_temp_two" id="list2">
				<div class="my_content_two">
					<div class="clearfix">
						<div style="float: left;">
							<span class="my_content_l">姓名</span>
							<input type="text" class="nameinput_small" name="manager_name" placeholder="请输入高管姓名">
						</div>
						<div style="float: right;margin-right: 12px;">
							<span class="my_content_l">职位</span>
							<input type="text" class="nameinput_small" name="manager_station" id="manager_station" placeholder="请输入高管职位">
						</div>
					</div>
					<div class="content_two">
						<span class="my_content_l">简介</span>
						<textarea class="nameinput_two" id="nameinput_two" name="manager_info" placeholder="例如：耶鲁大学经济学及东亚研究学士学位、耶鲁法学院法学博士学位。"></textarea>
					</div>
					<div class="content_three">
						<span class="my_content_l">图片</span>
						<div class="my_pic">
							<?php echo $_smarty_tpl->getVariable('manager_img_html')->value['html'];?>

						</div>
					</div>
				</div>
			</div>
			<div class="addcontent_two">
				<span class="icon-AI-_-07 jxtj"></span>
				<span style="margin-left: 10px;">继续添加</span>
			</div>
		</div>
	</div>
	<?php }?>
	<div class="my_line"></div>
	<div class="my_footer">
		<a href="javascript:;" class="my_save">保存</a>
		<a href="#" class="my_remove">关闭</a>
		<div class="remind">
			<span class="gou"></span>
			<span>本月不再提醒</span>
		</div>
	</div>

	</form>
</div>
<script>
	var uploader_img;
	var project_img;
	var manage_img;
	hbjs.use('@hbCommon, @jobDialog, @validator, @areaDrop, @confirmBox, @areaDrop', function(m) {
		//var $ = m['product.hbCommon'].extend(m['cqjob.jobDialog']);
		var Dialog = m['widge.overlay.hbDialog'];
		var AreaDrop = m['product.areaDrop'];
		var validatorForm = m['widge.validator.form'];
		var ConfirmBox = m['widge.overlay.confirmBox'];
		var fontSize = 18,
		pWidth = 70;
<?php if ($_smarty_tpl->getVariable('need_alert_modify')->value['photolist']){?>
		//相册
		$(function () {
			var up_options_<?php echo $_smarty_tpl->getVariable('up_img_html')->value['id'];?>
 = {
				up_id: '.<?php echo $_smarty_tpl->getVariable('up_img_html')->value['id'];?>
',
				auto: <?php echo $_smarty_tpl->getVariable('up_img_html')->value['config']['auto'];?>
,
				BASE_URL: "<?php echo $_smarty_tpl->getVariable('up_img_html')->value['config']['BASE_URL'];?>
",
				fileNumLimit: <?php echo $_smarty_tpl->getVariable('up_img_html')->value['config']['fileNumLimit'];?>
,//文件数量 默认5个 每张1M fileSingleSizeLimit: 1024 * 1024,
				fileSingleSizeLimit: <?php echo $_smarty_tpl->getVariable('up_img_html')->value['config']['fileSingleSizeLimit'];?>
,//每个文件大小
				file_name: '<?php echo $_smarty_tpl->getVariable('up_img_html')->value['config']['file_name'];?>
',//返回接收上传的文件名称隐藏域
				fileVal: '<?php echo $_smarty_tpl->getVariable('up_img_html')->value['config']['fileVal'];?>
',//上传接收name
				defaults_files: <?php echo $_smarty_tpl->getVariable('up_img_html')->value['config']['defaults_files'];?>
,//默认文件
				accept: <?php echo $_smarty_tpl->getVariable('up_img_html')->value['config']['accept'];?>
,//允许文件类型
				formData: <?php echo $_smarty_tpl->getVariable('up_img_html')->value['config']['form_data'];?>
,//上传删除携带参数
				server: '<?php echo smarty_function_get_url(array('rule'=>"/environment/UploadEnvalop/"),$_smarty_tpl);?>
',
				deleteServer: '<?php echo smarty_function_get_url(array('rule'=>"/environment/DelTempFile/"),$_smarty_tpl);?>
',
				error_hint_fun: function (msg) {
					ConfirmBox.timeBomb(msg, {
						name: 'fail',
						timeout: 2000,
						width: fontSize * msg.length + pWidth
					});
				},
				confirm_hint_fun: function (confirm_msg, deleteServerAjax) {
					//询问框
					ConfirmBox.confirm(confirm_msg,'删除图片', function(obj){
						deleteServerAjax();
						this.hide();
					},{
						width :300,
						close : 'x',
						zIndex : 99999
					});
				},
			};
			uploader_img = $('.<?php echo $_smarty_tpl->getVariable('up_img_html')->value['id'];?>
').powerWebUpload(up_options_<?php echo $_smarty_tpl->getVariable('up_img_html')->value['id'];?>
);
		});
<?php }?>

<?php if ($_smarty_tpl->getVariable('need_alert_modify')->value['project']){?>
		//产品
		$(function () {
			var up_options_<?php echo $_smarty_tpl->getVariable('project_img_html')->value['id'];?>
 = {
				up_id: '.<?php echo $_smarty_tpl->getVariable('project_img_html')->value['id'];?>
',
				auto: <?php echo $_smarty_tpl->getVariable('project_img_html')->value['config']['auto'];?>
,
				BASE_URL: "<?php echo $_smarty_tpl->getVariable('project_img_html')->value['config']['BASE_URL'];?>
",
				fileNumLimit: <?php echo $_smarty_tpl->getVariable('project_img_html')->value['config']['fileNumLimit'];?>
,//文件数量 默认5个 每张1M fileSingleSizeLimit: 1024 * 1024,
				fileSingleSizeLimit: <?php echo $_smarty_tpl->getVariable('project_img_html')->value['config']['fileSingleSizeLimit'];?>
,//每个文件大小
				file_name: '<?php echo $_smarty_tpl->getVariable('project_img_html')->value['config']['file_name'];?>
',//返回接收上传的文件名称隐藏域
				fileVal: '<?php echo $_smarty_tpl->getVariable('project_img_html')->value['config']['fileVal'];?>
',//上传接收name
				defaults_files: <?php echo $_smarty_tpl->getVariable('project_img_html')->value['config']['defaults_files'];?>
,//默认文件
				accept: <?php echo $_smarty_tpl->getVariable('project_img_html')->value['config']['accept'];?>
,//允许文件类型
				formData: <?php echo $_smarty_tpl->getVariable('project_img_html')->value['config']['form_data'];?>
,//上传删除携带参数
				server: '<?php echo smarty_function_get_url(array('rule'=>"/introduceProject/Picture/"),$_smarty_tpl);?>
',
				deleteServer: '<?php echo smarty_function_get_url(array('rule'=>"/introduceManage/DelTempFile/"),$_smarty_tpl);?>
',
				error_hint_fun: function (msg) {
					ConfirmBox.timeBomb(msg, {
						name: 'fail',
						timeout: 2000,
						width: fontSize * msg.length + pWidth
					});
				},
				confirm_hint_fun: function (confirm_msg, deleteServerAjax) {
					//询问框
					ConfirmBox.confirm(confirm_msg,'删除图片', function(obj){
						deleteServerAjax();
						this.hide();
					},{
						width :300,
						close : 'x',
						zIndex : 99999
					});
				},
			};
			project_img = $('.<?php echo $_smarty_tpl->getVariable('project_img_html')->value['id'];?>
').powerWebUpload(up_options_<?php echo $_smarty_tpl->getVariable('project_img_html')->value['id'];?>
);
		});
<?php }?>

<?php if ($_smarty_tpl->getVariable('need_alert_modify')->value['manager']){?>
		//高管
		$(function () {
			var up_options_<?php echo $_smarty_tpl->getVariable('manager_img_html')->value['id'];?>
 = {
				up_id: '.<?php echo $_smarty_tpl->getVariable('manager_img_html')->value['id'];?>
',
				auto: <?php echo $_smarty_tpl->getVariable('manager_img_html')->value['config']['auto'];?>
,
				BASE_URL: "<?php echo $_smarty_tpl->getVariable('manager_img_html')->value['config']['BASE_URL'];?>
",
				fileNumLimit: <?php echo $_smarty_tpl->getVariable('manager_img_html')->value['config']['fileNumLimit'];?>
,//文件数量 默认5个 每张1M fileSingleSizeLimit: 1024 * 1024,
				fileSingleSizeLimit: <?php echo $_smarty_tpl->getVariable('manager_img_html')->value['config']['fileSingleSizeLimit'];?>
,//每个文件大小
				file_name: '<?php echo $_smarty_tpl->getVariable('manager_img_html')->value['config']['file_name'];?>
',//返回接收上传的文件名称隐藏域
				fileVal: '<?php echo $_smarty_tpl->getVariable('manager_img_html')->value['config']['fileVal'];?>
',//上传接收name
				defaults_files: <?php echo $_smarty_tpl->getVariable('manager_img_html')->value['config']['defaults_files'];?>
,//默认文件
				accept: <?php echo $_smarty_tpl->getVariable('manager_img_html')->value['config']['accept'];?>
,//允许文件类型
				formData: <?php echo $_smarty_tpl->getVariable('manager_img_html')->value['config']['form_data'];?>
,//上传删除携带参数
				server: '<?php echo smarty_function_get_url(array('rule'=>"/introduceManage/Picture/"),$_smarty_tpl);?>
',
				deleteServer: '<?php echo smarty_function_get_url(array('rule'=>"/introduceManage/DelTempFile/"),$_smarty_tpl);?>
',
				error_hint_fun: function (msg) {
					ConfirmBox.timeBomb(msg, {
						name: 'fail',
						timeout: 2000,
						width: fontSize * msg.length + pWidth
					});
				},
				confirm_hint_fun: function (confirm_msg, deleteServerAjax) {
					//询问框
					ConfirmBox.confirm(confirm_msg,'删除图片', function(obj){
						deleteServerAjax();
						this.hide();
					},{
						width :300,
						close : 'x',
						zIndex : 99999
					});
				},
			};
			manage_img = $('.<?php echo $_smarty_tpl->getVariable('manager_img_html')->value['id'];?>
').powerWebUpload(up_options_<?php echo $_smarty_tpl->getVariable('manager_img_html')->value['id'];?>
);
		});
<?php }?>
	});
</script>
<script type="text/javascript">
	$(function(){
		var layerHeight = $('.my_newLayer').height()
		var dHeight = $(window).height()
		var my_layer_top = dHeight - layerHeight
		
		$('.my_newLayer').css('top',my_layer_top/2)
		// if($('.my_newLayer').height() < 350) {
		// 	$('.my_newLayer').css('top',my_layer_top/2)
		// }
		
		$('.financing li').click(function(){
			$(this).children().addClass('pop')
			$(this).siblings().children().removeClass('pop')
		})
		$('.my_add').click(function(){
			$(this).hide()
			$('.my_content').css('display','inline-block')
			$('.addcontent').show()
			var layerHeight = $('.my_newLayer').height()
			var dHeight = $(window).height()
			var my_layer_top = dHeight - layerHeight
			$('.my_newLayer').css('top',my_layer_top/2)
		})
	
		$('.my_add_two').click(function(){
			$(this).hide()
			$('.my_content_two').css('display','inline-block')
			$('.addcontent_two').show()
			var layerHeight = $('.my_newLayer').height()
			var dHeight = $(window).height()
			var my_layer_top = dHeight - layerHeight
			$('.my_newLayer').css('top',my_layer_top/2)
		})
		var chars = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
		function generateMixed(n) {
		     var res = "";
		     for(var i = 0; i < n ; i ++) {
		         var id = Math.ceil(Math.random()*26);
		         res += chars[id];
		     }
		     return res;
		}
		$('.addcontent').click(function(){
			var pic_name = generateMixed(5)
			$.ajax({
			    type: 'post',
			    url: '/index/NoticeGetImgUpload',
				data: {type:1,pic_name:pic_name},
			    dataType: 'json',
			    success: function (res) {
					console.log(res)
					if(res.status){
						if($('.my_temp').children().length<=3){
							$('.my_temp').append('<div></div>'+
							'<div class="my_content">'+
								'<div>'+
									'<span class="my_content_l">名称</span>'+
									'<input type="text" class="nameinput" name="project_name" placeholder="请输入产品/项目名称" maxlength="15">'+
								'</div>'+
								'<div class="content_two">'+
									'<span class="my_content_l">简介</span>'+
									'<textarea class="nameinput_two" name="project_info" placeholder="请输入产品/项目简介" maxlength="100"></textarea>'+
								'</div>'+
								'<div class="content_three">'+
									'<span class="my_content_l">图片</span>'+
									'<div class="my_pic">'+
										res.up_html+
									'</div>'+
								'</div>'+
							'</div>')
							$('.my_content').css('display','inline-block')
						}
						for(var i=0;i<$('.my_temp').children().length;i++){
							if($('.my_temp').children().length==5){
								$('.addcontent').hide()
							}
						}
					}
			    }
			});
			var layerHeight = $('.my_newLayer').height()
			var dHeight = $(window).height()
			var my_layer_top = dHeight - layerHeight
			$('.my_newLayer').css('top',my_layer_top/2)
		})
		$('.addcontent_two').click(function(){
			var pic_name_two = generateMixed(6)
			$.ajax({
			    type: 'post',
			    url: '/index/NoticeGetImgUpload',
				data: {type:2,pic_name:pic_name_two},
			    dataType: 'json',
			    success: function (res) {
					console.log(res)
					if($('.my_temp_two').children().length<=9){
						$('.my_temp_two').append('<div></div>'+
						'<div class="my_content_two">'+
							'<div class="clearfix">'+
								'<div style="float: left;">'+
									'<span class="my_content_l">姓名</span>'+
									'<input type="text" class="nameinput_small" name="manager_name" placeholder="请输入高管姓名">'+
								'</div>'+
								'<div style="float: right;margin-right: 12px;">'+
									'<span class="my_content_l">职位</span>'+
									'<input type="text" class="nameinput_small" name="manager_station" id="manager_station" placeholder="请输入高管职位">'+
								'</div>'+
							'</div>'+
							'<div class="content_two">'+
								'<span class="my_content_l">简介</span>'+
								'<textarea class="nameinput_two" id="nameinput_two" name="manager_info" placeholder="例如：耶鲁大学经济学及东亚研究学士学位、耶鲁法学院法学博士学位。 限60字"></textarea>'+
							'</div>'+
							'<div class="content_three">'+
								'<span class="my_content_l">图片</span>'+
								'<div class="my_pic">'+
									res.up_html+
								'</div>'+
							'</div>'+
						'</div>')
						$('.my_content_two').css('display','inline-block')
					}
					for(var i=0;i<$('.my_temp_two').children().length;i++){
						if($('.my_temp_two').children().length==9){
							$('.addcontent_two').hide()
						}
					}
			    }
			});
			var layerHeight = $('.my_newLayer').height()
			var dHeight = $(window).height()
			var my_layer_top = dHeight - layerHeight
			$('.my_newLayer').css('top',my_layer_top/2)
		})
		$('.remind').click(function(){
			$('.gou').toggleClass('pop2')
		})
		// $('.my_remove').click(function(){
		// 	$('.my_newLayer').hide()
		// 	$('.my_newMask').hide()
		// })
	})
</script>
<script type="text/javascript">
	function trans() {
		var myData = {
			france: '',
			hddNewPhotoName: [],
			project_arr: [],
			manager_arr: [],
		}
		var dom1 = $('.financing li').children('.pop').parents("li")
		myData.france = dom1.attr("data-id")
		var dom2 = $('.feedback-img').find("[name='hddNewPhotoName[]']")
		$.each(dom2,function(){
			myData.hddNewPhotoName.push($(this).val())
		})
		var dom3 = $('#list1 .my_content')
		$.each(dom3,function(index,item) {
			var obj = {
				name: $(this).find("[name='project_name']").val(),
				info: $(this).find("[name='project_info']").val(),
				// pic: $(this).find('.uploader2').children('input').val()
				pic: []
			}
			var pic_v = $(this).find('.uploader2').children('input')
			$.each(pic_v,function(){
				obj.pic.push($(this).val())
			})
			myData.project_arr.push(obj)
		})
		var dom4 = $('#list2 .my_content_two')
		$.each(dom4,function(index,item) {
			var obj = {
				name: $(this).find("[name='manager_name']").val(),
				info: $(this).find("[name='manager_info']").val(),
				station: $(this).find("[name='manager_station']").val(),
				// pic: $(this).find('.uploader2').children('input').attr('name')
				pic: []
			}
			var pic_v = $(this).find('.uploader2').children('input')
			$.each(pic_v,function(){
				obj.pic.push($(this).val())
			})
			myData.manager_arr.push(obj)
		})
		
		return myData
	}
	
	hbjs.use('@confirmBox', function(m) {
	var	ConfirmBox      = m['widge.overlay.confirmBox'],
		Dialog          = m['widge.overlay.hbDialog'],
		cookie          = m['tools.cookie'],
		fontSize = 18,
		pWidth = 70;
	    var data = {};

		$('.my_remove').click(function(){
			$('.my_newLayer,.my_newMask').hide();
			informationCookie();
		});
		
		$('.my_save').click(function(){
			data = trans()
			console.log(data)

			if(data.france != undefined || data.hddNewPhotoName.length != 0 || $('.my_content').is(":visible") || $('.my_content_two').is(":visible")){
				if($('.my_content').is(":visible")){
					if($('.nameinput').val() == '') {
						var msg = '内容未完善，请填写';
						ConfirmBox.timeBomb(msg,{
							name: 'fail',
							width:fontSize * msg.length + pWidth,
							timeout : 1000
						});
						return false;
					}else if($('.nameinput_two').val() == '') {
						var msg = '内容未完善，请填写';
						ConfirmBox.timeBomb(msg,{
							name: 'fail',
							width:fontSize * msg.length + pWidth,
							timeout : 1000
						});
						return false;
					}else if($('#list1 .my_content').find('.uploader2').children('input').val() == '') {
						var msg = '内容未完善，请填写';
						ConfirmBox.timeBomb(msg,{
							name: 'fail',
							width:fontSize * msg.length + pWidth,
							timeout : 1000
						});
						return false;
					}else {
						$.ajax({
							type: 'post',
							url: '/index/SaveModifyNotice',
							data: data,
							dataType: 'json',
							success: function (res) {
								console.log(res)
								if(!res.status){
									var msg = '未填写内容，请填写';
									ConfirmBox.timeBomb(msg,{
										name: 'fail',
										width:fontSize * msg.length + pWidth,
										timeout : 1000
									});
									return false;
								}else if(res.status){
									var msg = '保存成功';
									ConfirmBox.timeBomb(msg,{
										name: 'success',
										width:fontSize * msg.length + pWidth,
										timeout : 1000
									});
									$('.my_newLayer,.my_newMask').hide();
									informationCookie();
								}
							}
						});
					}
				}else if($('.my_content_two').is(":visible")) {
					if($('.nameinput_small').val() == '') {
						var msg = '内容未完善，请填写';
						ConfirmBox.timeBomb(msg,{
							name: 'fail',
							width:fontSize * msg.length + pWidth,
							timeout : 1000
						});
						return false;
					}else if($('#nameinput_two').val() == '') {
						var msg = '内容未完善，请填写';
						ConfirmBox.timeBomb(msg,{
							name: 'fail',
							width:fontSize * msg.length + pWidth,
							timeout : 1000
						});
						return false;
					}else if($('#manager_station').val() == ''){
						var msg = '内容未完善，请填写';
						ConfirmBox.timeBomb(msg,{
							name: 'fail',
							width:fontSize * msg.length + pWidth,
							timeout : 1000
						});
						return false;
					}else if($('#list2 .my_content_two').find('.uploader2').children('input').val() == '') {
						var msg = '内容未完善，请填写';
						ConfirmBox.timeBomb(msg,{
							name: 'fail',
							width:fontSize * msg.length + pWidth,
							timeout : 1000
						});
						return false;
					}else {
						$.ajax({
							type: 'post',
							url: '/index/SaveModifyNotice',
							data: data,
							dataType: 'json',
							success: function (res) {
								console.log(res)
								if(!res.status){
									var msg = '未填写内容，请填写';
									ConfirmBox.timeBomb(msg,{
										name: 'fail',
										width:fontSize * msg.length + pWidth,
										timeout : 1000
									});
									return false;
								}else if(res.status){
									var msg = '保存成功';
									ConfirmBox.timeBomb(msg,{
										name: 'success',
										width:fontSize * msg.length + pWidth,
										timeout : 1000
									});
									$('.my_newLayer,.my_newMask').hide();
									informationCookie();
								}
							}
						});
					}
				}else if($('.my_content').is(":hidden") && $('.my_content_two').is(":hidden")) {
					data.project_arr = []
					data.manager_arr = []
					$.ajax({
						type: 'post',
						url: '/index/SaveModifyNotice',
						data: data,
						dataType: 'json',
						success: function (res) {
							console.log(res)
							if(!res.status){
								var msg = '未填写内容，请填写';
								ConfirmBox.timeBomb(msg,{
									name: 'fail',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								return false;
							}else if(res.status){
								var msg = '保存成功';
								ConfirmBox.timeBomb(msg,{
									name: 'success',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								$('.my_newLayer,.my_newMask').hide();
								informationCookie();
							}
						}
					});
				}else if($('.my_content').is(":hidden") && $('.my_content_two').is(":visible")) {
					data.project_arr = []
					$.ajax({
						type: 'post',
						url: '/index/SaveModifyNotice',
						data: data,
						dataType: 'json',
						success: function (res) {
							console.log(res)
							if(!res.status){
								var msg = '未填写内容，请填写';
								ConfirmBox.timeBomb(msg,{
									name: 'fail',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								return false;
							}else if(res.status){
								var msg = '保存成功';
								ConfirmBox.timeBomb(msg,{
									name: 'success',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								$('.my_newLayer,.my_newMask').hide();
								informationCookie();
							}
						}
					});
				}else if($('.my_content').is(":visible") && $('.my_content_two').is(":hidden")) {
					data.manager_arr = []
					$.ajax({
						type: 'post',
						url: '/index/SaveModifyNotice',
						data: data,
						dataType: 'json',
						success: function (res) {
							console.log(res)
							if(!res.status){
								var msg = '未填写内容，请填写';
								ConfirmBox.timeBomb(msg,{
									name: 'fail',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								return false;
							}else if(res.status){
								var msg = '保存成功';
								ConfirmBox.timeBomb(msg,{
									name: 'success',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								$('.my_newLayer,.my_newMask').hide();
								informationCookie();
							}
						}
					});
				}else if(data.hddNewPhotoName.length != 0 && $('.my_content_two').is(":hidden")) {
					data.manager_arr = []
					$.ajax({
						type: 'post',
						url: '/index/SaveModifyNotice',
						data: data,
						dataType: 'json',
						success: function (res) {
							console.log(res)
							if(!res.status){
								var msg = '未填写内容，请填写';
								ConfirmBox.timeBomb(msg,{
									name: 'fail',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								return false;
							}else if(res.status){
								var msg = '保存成功';
								ConfirmBox.timeBomb(msg,{
									name: 'success',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								$('.my_newLayer,.my_newMask').hide();
								informationCookie();
							}
						}
					});
				}else if(data.hddNewPhotoName.length != 0 && $('.my_content').is(":hidden")) {
					data.project_arr = []
					$.ajax({
						type: 'post',
						url: '/index/SaveModifyNotice',
						data: data,
						dataType: 'json',
						success: function (res) {
							console.log(res)
							if(!res.status){
								var msg = '未填写内容，请填写';
								ConfirmBox.timeBomb(msg,{
									name: 'fail',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								return false;
							}else if(res.status){
								var msg = '保存成功';
								ConfirmBox.timeBomb(msg,{
									name: 'success',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								$('.my_newLayer,.my_newMask').hide();
								informationCookie();
							}
						}
					});
				}else {
					$.ajax({
						type: 'post',
						url: '/index/SaveModifyNotice',
						data: data,
						dataType: 'json',
						success: function (res) {
							console.log(res)
							if(!res.status){
								var msg = '未填写内容，请填写';
								ConfirmBox.timeBomb(msg,{
									name: 'fail',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								return false;
							}else if(res.status){
								var msg = '保存成功';
								ConfirmBox.timeBomb(msg,{
									name: 'success',
									width:fontSize * msg.length + pWidth,
									timeout : 1000
								});
								$('.my_newLayer,.my_newMask').hide();
								informationCookie();
							}
						}
					});
				}
			}else {
				var msg = '未填写内容，请填写';
				ConfirmBox.timeBomb(msg,{
					name: 'fail',
					width:fontSize * msg.length + pWidth,
					timeout : 1000
				});
				return false;
			}
		})
		
		function informationCookie(){
			console.log('cookie生效');
			var tomorrow;
			if($('.gou').hasClass('pop2')){
				tomorrow = 30;
			}else{
				tomorrow = 1;
			}
			// cookie.set('has_informationCookie',true,{expires:tomorrow,path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
			$.cookie("my_layerCookie","none",{expires: tomorrow, path: '/', domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"})
		}
		
		
	})
</script>
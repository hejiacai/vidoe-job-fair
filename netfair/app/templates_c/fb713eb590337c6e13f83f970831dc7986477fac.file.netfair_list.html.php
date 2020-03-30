<?php /* Smarty version Smarty-3.0.7, created on 2020-03-30 13:45:41
         compiled from "app\templates\./shuangxuannet/netfair_list.html" */ ?>
<?php /*%%SmartyHeaderCode:152875e818785bfbf59-36238691%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb713eb590337c6e13f83f970831dc7986477fac' => 
    array (
      0 => 'app\\templates\\./shuangxuannet/netfair_list.html',
      1 => 1585547135,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '152875e818785bfbf59-36238691',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
if (!is_callable('smarty_modifier_date_format')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.date_format.php';
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<meta name="renderer" content="webkit">
    <title>视频招聘会</title>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'mobile.swiper.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.cookie.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'layer.js'),$_smarty_tpl);?>
"></script>
    <link type="text/css" href="<?php echo smarty_function_version(array('file'=>'layer.css'),$_smarty_tpl);?>
">
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'netfarJobList.css'),$_smarty_tpl);?>
"/>
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'m_font_style.css'),$_smarty_tpl);?>
">
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
">
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'base_script.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'version.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript">
        window.CONFIG = {
            HOST: '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
',
            COMBOPATH: '/js/v2/',
        };
    </script>
    <script type="text/javascript"
            src="<?php echo smarty_function_version(array('file'=>'hbjs.js,jquery.min.js,util.js,class.js,shape.js,event.js,aspect.js,attribute.js,cookie.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'global.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript">
        hbjs.config({
            combos: {
                '@homeSideSortMenu': [
                    'product.homeSideSortMenu', 'product.sideMenu.sideSortMenuGroup', 'product.sideMenu.sideSortMenu',
                    'product.sideMenu.sideSortMenuData', '@popup'
                ]
            }
        });
        hbjs.loadJS('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/js/v2/cqjob/common.js');
    </script>
    <style>
		.formShow .formText input{
			border: 1px solid #cfcfcf!important;
		}
		.box-active{
		  position: fixed;
		  top: 0;
		  z-index: 999;
		}
		.school_mark{
			position: absolute;
			top: 8px;
			right: 8px;
			display: inline-block; width: 40px;text-align: center;
			height: 18px;line-height: 18px; border-radius: 4px;background: #1cd078;color: #ffffff!important;font-size: 14px;margin-right: 5px;
		}
		<?php if (empty($_smarty_tpl->getVariable('list',null,true,false)->value['signup_in'])&&empty($_smarty_tpl->getVariable('list',null,true,false)->value['go_on'])&&empty($_smarty_tpl->getVariable('list',null,true,false)->value['over'])&&empty($_smarty_tpl->getVariable('list',null,true,false)->value['interview_in'])){?>
			body{height: 100vh;}
		<?php }?>
    </style>
</head>
<body>
<?php echo $_smarty_tpl->getVariable('head_data')->value;?>

<div class="videoJobFairTop">
    <div>
        <a>视频招聘会</a>
    </div>
</div>
<div class="nav_content">
	<div class="serach_v">
		<div class="serach">
			<form action="<?php echo smarty_function_get_url(array('rule'=>"/index/index"),$_smarty_tpl);?>
" method="get" id="form">
				<input type="text" placeholder="搜索招聘会、心仪公司或职位" name="content" value="<?php echo $_smarty_tpl->getVariable('content')->value;?>
">
				<input type="hidden">
			</form>
			<div id="submit">
				<span class="img"></span>
				<!-- <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/serach_bot_03.jpg"  style="cursor: pointer"> -->
				<span class="icon-home_navigation_search2x searchIcon"></span>
				<span class="searchBut" style="cursor: pointer">搜索</span>
			</div>
		</div>
	</div>
<?php if ($_smarty_tpl->getVariable('list')->value['signup_in']||$_smarty_tpl->getVariable('list')->value['go_on']||$_smarty_tpl->getVariable('list')->value['over']||$_smarty_tpl->getVariable('list')->value['interview_in']){?>
	<?php if ($_smarty_tpl->getVariable('list')->value['interview_in']){?>
	<div class="search_box">
		<div class="apply">
			<div class="apply_title">
				<!-- <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/vidoe_03.jpg" class="top_img"> -->
				<!-- <span class="icon-video_icon img_icon"></span> -->
				<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/top_viode.gif" class="top_viode">
				<span class="title_mark">视频面试中<em></em></span>
				<span class="apply_td">今日申请,今日面试</span>
			</div>
			<div class="apply_items">
				<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value['interview_in']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
				<a href="<?php echo smarty_function_get_url(array('rule'=>'/fairList/'),$_smarty_tpl);?>
sid-<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" target="_blank">
					<div class="apply_item_c">
							<?php if ($_smarty_tpl->tpl_vars['val']->value['school_type']==1){?><span class="school_mark">校园</span><?php }?>
							<?php if ($_smarty_tpl->tpl_vars['val']->value['list_poster']){?> <img src="<?php echo $_smarty_tpl->tpl_vars['val']->value['list_poster_info'];?>
" class="apply_item_img"><?php }else{ ?><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/mobile/moren_pic.png" class="apply_item_img" /><?php }?>
						<div class="apply_item_c_box">
							<div class="apply_item_c_content">
								<!-- <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/vidoe_03.jpg" > -->
								<span class="icon-video_icon img_icon"></span>
								<span class="top_img_c"><?php echo $_smarty_tpl->tpl_vars['val']->value['title'];?>
</span>
							</div>
							<div class="baom_scri">
								<span>企业 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['company_num'];?>
</em> 家</span>
								<span>岗位 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['job_num'];?>
</em> 个</span>
								<span>参与 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['person_num'];?>
</em> 人</span>
							</div>
                            <?php if ((date('H:i',time())>date('H:i',strtotime($_smarty_tpl->tpl_vars['val']->value['interview_end_time'])))||$_smarty_tpl->tpl_vars['val']->value['number']>0){?>
							<div class="end_time">
								<?php if ((date('H:i',time())>date('H:i',strtotime($_smarty_tpl->tpl_vars['val']->value['interview_end_time'])))){?><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['interview_end_time'],'H:i');?>
结束<?php }?>  <?php if ($_smarty_tpl->tpl_vars['val']->value['number']>0){?>剩余<?php echo $_smarty_tpl->tpl_vars['val']->value['number'];?>
个名额<?php }?>
							</div>
                            <?php }?>
							<div class="tuijian">
								<span class="img_spn">推荐</span>
								 <span class="jobName">商务大厦无多翁多</span>
								 <span>等2010个在招</span>
							</div>
							
							<!-- <div class="tuijian recommend_<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
"></div> -->
						</div>
					</div>
				</a>
				<?php }} ?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<?php }?>
	<?php if ($_smarty_tpl->getVariable('list')->value['signup_in']||$_smarty_tpl->getVariable('list')->value['go_on']||$_smarty_tpl->getVariable('list')->value['over']){?>
	<div class="activi_box">
		<?php if ($_smarty_tpl->getVariable('list')->value['signup_in']||$_smarty_tpl->getVariable('list')->value['go_on']){?>
		<div class="box_ms">
			<div class="list_items">
				<div class="list_item_title list_item_title_c">报名中<em></em></div>
				<?php if ($_smarty_tpl->getVariable('list')->value['signup_in']){?>
				<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value['signup_in']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
				<a href="<?php echo smarty_function_get_url(array('rule'=>'/fairList/'),$_smarty_tpl);?>
sid-<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" target="_blank">
				<div class="list_item_c">
					<div class="list_item_cc">
						<span class="school_mark">校园</span>
						<?php if ($_smarty_tpl->tpl_vars['val']->value['list_poster']){?> <img src="<?php echo $_smarty_tpl->tpl_vars['val']->value['list_poster_info'];?>
" class="nav_img"><?php }else{ ?><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/mobile/moren_pic.png" class="nav_img" /><?php }?>
						<div class="list_item_c_conte">
							<div class="apply_item_c_content_t">
								<!-- <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/vidoe_03.jpg" > -->
								<span class="icon-video_icon img_icon"></span>
								<span class="wza"><?php echo $_smarty_tpl->tpl_vars['val']->value['title'];?>
</span>
							</div>
							<div class="baom_scri_c">
								<span>企业 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['company_num'];?>
</em> 家</span>
								<span>岗位 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['job_num'];?>
</em> 个</span>
								<span>参与 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['person_num'];?>
</em> 人</span>
							</div>
							<div class="baom">
								<span>面试：</span>
								<span class="jttime"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['interview_start_time'],'m.d H:i');?>
  - <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['interview_end_time'],'m.d H:i');?>
</span>
							</div>
							<div class="end_time_c">
								<?php echo $_smarty_tpl->tpl_vars['val']->value['day'];?>

							</div>
							<div class="tuijian recommend_<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
">
							</div>
						</div>
					</div>
				</div>
				</a>
				<?php }} ?>
				<?php }?>
				<?php if ($_smarty_tpl->getVariable('list')->value['go_on']){?>
				<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value['go_on']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
				<a href="<?php echo smarty_function_get_url(array('rule'=>'/fairList/'),$_smarty_tpl);?>
sid-<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" target="_blank">
					<div class="list_item_c">
						<div class="list_item_cc">
							<span class="school_mark">校园</span>
							<?php if ($_smarty_tpl->tpl_vars['val']->value['list_poster']){?> <img src="<?php echo $_smarty_tpl->tpl_vars['val']->value['list_poster_info'];?>
" class="nav_img"><?php }else{ ?><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/mobile/moren_pic.png" class="nav_img" /><?php }?>
							<div class="list_item_c_conte">
								<div class="apply_item_c_content_t">
									<!-- <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/vidoe_03.jpg" > -->
									<span class="icon-video_icon img_icon"></span>
									<span class="wza"><?php echo $_smarty_tpl->tpl_vars['val']->value['title'];?>
</span>
								</div>
								<div class="baom_scri_c">
									<span>企业 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['company_num'];?>
</em> 家</span>
									<span>岗位 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['job_num'];?>
</em> 个</span>
									<span>参与 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['person_num'];?>
</em> 人</span>
								</div>
								<div class="baom">
									<span>活动时间：</span>
									<span class="jttime"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['start_time'],'m.d H:i');?>
  - <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['end_time'],'m.d H:i');?>
</span>
								</div>
								<div class="tuijian recommend_<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
">
								</div>
							</div>
						</div>
					</div>
				</a>
				<?php }} ?>
				<?php }?>
				<div class="clear"></div>
			</div>
		</div>
		<?php }?>
		<?php if ($_smarty_tpl->getVariable('list')->value['over']){?>
		<div class="box_ms box_end">
			<div class="list_items">
				<div class="list_item_title">已结束</div>
				<?php if ($_smarty_tpl->getVariable('list')->value['over']){?>
				<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value['over']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
				<a href="<?php echo smarty_function_get_url(array('rule'=>'/fairList/'),$_smarty_tpl);?>
sid-<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" target="_blank">
					<div class="list_item_c list_item_end">
						<div class="list_item_cc">
							<span class="school_mark">校园</span>
							<?php if ($_smarty_tpl->tpl_vars['val']->value['list_poster']){?> <img src="<?php echo $_smarty_tpl->tpl_vars['val']->value['list_poster_info'];?>
" class="nav_img"><?php }else{ ?><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/mobile/moren_pic.png" class="nav_img" /><?php }?>
							<div class="list_item_c_conte">
								<div class="apply_item_c_content_t">
									<!-- <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/vidoe_03.jpg" > -->
									<span class="icon-video_icon img_icon"></span>
									<span class="wza"><?php echo $_smarty_tpl->tpl_vars['val']->value['title'];?>
</span>
								</div>
								<div class="baom_scri_c">
									<span>企业 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['company_num'];?>
</em> 家</span>
									<span>岗位 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['job_num'];?>
</em> 个</span>
									<span>参与 <em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['person_num'];?>
</em> 人</span>
								</div>
								<div class="baom">
									<span>活动时间：</span>
									<span class="jttime"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['start_time'],'m.d H:i');?>
  - <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['end_time'],'m.d H:i');?>
</span>
								</div>
							</div>
						</div>
					</div>
				</a>
				<?php }} ?>
				<?php }?>
				<div class="clear"></div>
			</div>
		</div>
		<?php }?>
	</div>
	<?php }?>
<?php }else{ ?>
<!-- <p style="">暂无搜索结果，您可浏览其他视频招聘会</p> -->
<div class="noDatabox">
	<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon10.png" >
	<p><?php if ($_smarty_tpl->getVariable('serach_warn')->value){?><?php echo $_smarty_tpl->getVariable('serach_warn')->value;?>
<?php }else{ ?>暂无搜索结果，您可浏览其他视频招聘会<?php }?></p>
</div>
<?php }?>
</div>
<?php $_template = new Smarty_Internal_Template("./public/shuanxuan/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</body>
<script>
    $(function () {
        var sids = "<?php echo $_smarty_tpl->getVariable('sids')->value;?>
";
        var is_person_login = "<?php echo $_smarty_tpl->getVariable('is_person_login')->value;?>
";
        is_layer_index = false;
        if (sids && is_person_login) {
            $.ajax({
                type:'POST',
                url: '<?php echo smarty_function_get_url(array('rule'=>"/index/GetRecommendJob"),$_smarty_tpl);?>
',
                data:{sids:sids},
                dataType:"json",
                success : function(json){
                    if (json.code==200){
                        $.each(json.data, function (key, value) {
                            var html = '';
                            html += '<span class="img_spn">推荐</span>';
                            html += '  <span class="jobName">'+value.station+'</span>';
                            html += '  <span>等'+value.station_count+'个在招</span>';
                            $('.recommend_'+key).html(html);

                        });
                    }
                }
            });
        }

    })

    $('#submit').click(function () {
        $('#form').submit();
    })
	$(document).ready(function(){
		var timeId = setInterval(function(){
				var html = $('.title_mark em').html() + '·'
				if(html == '····') {
						html = ''
				}
				$('.title_mark em').html(html)
		},1000)
		var timeI = setInterval(function(){
				var html = $('.list_item_title_c em').html() + '·'
				if(html == '····') {
						html = ''
				}
				$('.list_item_title_c em').html(html)
		},1000)
	})
	$(document).scroll(function() {
	        var scroH = $(document).scrollTop();  //滚动高度
	        var viewH = $(window).height();  //可见高度
	        var contentH = $(document).height();  //内容高度
			// console.log('页面滚动高度',scroH)
	        if(scroH >126){  //距离顶部大于100px时
				$('.serach_v').addClass('box-active')
	        }else{
				$('.serach_v').removeClass('box-active')
			}
	})
</script>
</html>

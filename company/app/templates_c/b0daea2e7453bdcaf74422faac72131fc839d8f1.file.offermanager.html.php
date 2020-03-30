<?php /* Smarty version Smarty-3.0.7, created on 2020-03-24 09:34:23
         compiled from "app\templates\./offer/offermanager.html" */ ?>
<?php /*%%SmartyHeaderCode:65735e79639fb254e1-52611309%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b0daea2e7453bdcaf74422faac72131fc839d8f1' => 
    array (
      0 => 'app\\templates\\./offer/offermanager.html',
      1 => 1584968332,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '65735e79639fb254e1-52611309',
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
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>offer管理_汇博网</title>
	<!--–[if lt IE9]-->
	<script src="//hm.baidu.com/hm.js?fb51309e47424acd6e31c0bd2a65a5a1"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'html5.js'),$_smarty_tpl);?>
"></script>
	<!--[endif]–-->
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'comback.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'resument2015.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'layer.css'),$_smarty_tpl);?>
" />
	<script type="text/javascript">
		window.CONFIG = {
			HOST: '<?php echo $_smarty_tpl->getVariable('isteurl')->value['style'];?>
',
			COMBOPATH: '/js/v2/'
		}
	</script>
	<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'hbjs.js,jquery.min.js,util.js,class.js,shape.js,event.js,aspect.js,attribute.js,cookie.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'layer.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'common.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'dialog.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_inputFocus.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_tooltip.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_validate.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.form.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_dropdownlist.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_autocomplete.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.email.tip.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.metadata.js'),$_smarty_tpl);?>
"></script>

	<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'WdatePicker.js'),$_smarty_tpl);?>
"></script>
	<!-- <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'WdatePicker.css'),$_smarty_tpl);?>
" /> -->
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'offer.css'),$_smarty_tpl);?>
" />
	<script type="text/javascript">
		hbjs.loadJS('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/js/v2/cqjob/common.js');
	</script>
	<style>
		.rMentRt{ position:relative;width:782px;}
		.rMentDl dd{ background: #fff;}
    .subMetx .com {width: 140px; float: left;}
    .subMetx .job {width: 180px;}
    .rMentLit .rMentLx b.gen-binding {background: #ff9b00;border:0px;color:#feffff;border-radius: 2px;line-height: 18px;font-weight: normal;}
    </style>

	<style>
		.dwonLoadx{width:390px; text-align: left; background: #fff; overflow: hidden; border:1px solid #f1f1f1; position: absolute; top:0;  left:0; z-index: 10;}   
        .dwonLoadx h2{width:100%; height:30px; background:#f1f1f1;overflow: hidden;}
        .dwonLoadx h2 a{ display:block; float: right; height:30px; line-height:30px;width:30px; text-align: center; line-height: 30px; font-size:16px; color: #333;}
        .rMentBtn p a{ margin-right:10px;}
        /*修改切换按钮后的样式*/
        .newMsgList li a.cut{
          color: #333;
          background: #fff;
          border: 1px solid #e4e4e4;
          border-bottom: 1px solid #fff;
          position: relative;
          z-index: 10;
          font-weight: bold;
          margin-top:1px;
        }
        .newMsgList li a{
          display: inline-block;
          height: 38px;
          line-height: 38px;
          padding: 0 15px;
          font-size: 14px;
          position: relative;
          z-index: 1;
          zoom: 1;
          background: #f1f1f1;
          border: 1px solid #e4e4e4;
          color: #4d4d4d;
        }
        .newMsgList li{
          float: left;
          display: inline;
          margin: 1px 10px 0 0;
          height: 38px;
          line-height: 38px;
        }

        .mRtake {
            display:none;
            font-size: 10px;
            z-index: 12;
            width:400px;
            border: 5px solid #666666;
            left: 50%;
            margin-left: -200px;
        }
        .dialogBtn{ padding-top: 5px; background: #fafafa;    border-top: 1px solid #ebebeb;}
        #e1 {
            padding: 10px;
            background: #f1f1f1;
            font-size: 16px;
        }
        #e2 {
            padding-top: 15px;
            color: #8a9499;
            font-family: "微软雅黑";
            font-size: 14px;
            font-weight: normal;
            padding-left: 4%;
        }
        #msg_content {
                        border: 1px solid #cec9c9;width: 345px; padding: 5px;height: 100px;margin:10px 4%;
                        font-size: 14px;
                    }
        .num-count {
            text-align: right;
            padding-right: 6%;
            padding-bottom: 10px;
        }
        body{ font-family: "微软雅黑"; font-size: 14px;}
    </style>
	<!-- 日期选择器样式 -->
	<style>
		.makTime b,.makTime input,.makTime span{ display: block; float: left; height: 28px; line-height: 28px;}
        .makTime b{ margin-left: 10px; font-weight: normal;}
        .makTime input{width:120px; text-indent: 8px; border: 1px solid #e8e8e8;}
        .makTime span{ padding: 0 10px;}
        /*修改切换按钮后的样式*/
        .newMsgList li a.cut{
            color: #333;
            background: #fff;
            border: 1px solid #e4e4e4;
            border-bottom: 1px solid #fff;
            position: relative;
            z-index: 10;
            font-weight: bold;
            margin-top:1px;
        }
        .newMsgList li a{
            display: inline-block;
            height: 38px;
            line-height: 38px;
            padding: 0 15px;
            font-size: 14px;
            position: relative;
            z-index: 1;
            zoom: 1;
            background: #f1f1f1;
            border: 1px solid #e4e4e4;
            color: #4d4d4d;
        }
        .newMsgList li{
            float: left;
            display: inline;
            margin: 1px 10px 0 0;
            height: 38px;
            line-height: 38px;

        }


        .mRtake {
            display:none;
            font-size: 10px;
            z-index: 12;
            width:400px;
            border: 5px solid #666666;
            left: 50%;
            margin-left: -200px;
        }
        .dialogBtn{ padding-top: 5px; background: #fafafa;    border-top: 1px solid #ebebeb;}
        #e1 {
            padding: 10px;
            background: #f1f1f1;
            font-size: 16px;
        }
        #e2 {
            padding-top: 15px;
            color: #8a9499;
            font-family: "微软雅黑";
            font-size: 14px;
            font-weight: normal;
            padding-left: 4%;
        }
        #msg_content {
            border: 1px solid #cec9c9;width: 345px; padding: 5px;height: 100px;margin:10px 4%;
            font-size: 14px;
        }
        .num-count {
            text-align: right;
            padding-right: 6%;
            padding-bottom: 10px;
        }
        body{ font-family: "微软雅黑"; font-size: 14px;}
		.hasHiredPop div{ overflow: hidden; padding-bottom: 10px;}
		.hasHiredPop span,.hasHiredPop input{ display: block; float: left; line-height: 30px; color: #222;}
		.hasHiredPop input{ border: 1px solid #ccc; margin-left: 10px; text-indent: 8px;}
		.rMentLt{ background: none; padding-top: 0;}
		.rMentDl dd span{ margin-left: 0;}
		.rMentDl dd.cut span{ background: none;}
		.resumentNbg .rMentDl dd a {
		    font-size: none;
		    line-height: none;
		    display: block;
		    width: auto;
		    height: auto;
		    line-height: none;
		    border-radius: 0px;
		    text-align: left;
		    margin: none;
		}
		.resumentNbg .rMentDl dd.cut a{ background: none;}
		.offerDatas tbody tr{line-height: 15px;}
		/* .offerDatas tbody tr .offerManager_NamejobName{line-height: 15px;} */
		/* #body .resumentNbgOld{} */
		#body .resumentNbgOld {
			width: 1000px;
			height: auto;
			min-height: 800px;
			overflow: hidden;
			background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/resumentbg.png) repeat-y #fcfcfc;
			box-shadow: 0 0 3px #ddd;
			margin: 15px auto;
			text-align: left;
			position: relative;
		}
		#body .resumentNbgOld .rMentLt{padding-top: 10px;}
		#body .resumentNbgOld  .rMentDl dd em, .rMentDl dd span, .resumentNbgOld_rMentLt .rMentDl dd i{color: #222222;text-align: left;line-height: 47px;font-size: 16px;line-height: 19px;}
		#body .resumentNbgOld .resumentNbgOld_rMentLt .rMentDl dd i{background: none;width: auto;color: #89949e;font-size: 22px;}
		#body .resumentNbgOld .rMentDl dd i:first-child{font-size: 20px;}
		#body .resumentNbgOld .rMentDl dd{width: auto;}
		#body .resumentNbgOld .resumentNbg-new .rMentDl dd.cut{}
		#body .resumentNbgOld .resumentNbg .rMentDl dd a{font-size: 16px;line-height: 40px;display: block;width: 180px;height: 40px;line-height: 40px;border-radius: 3px ;text-align: center;margin: 0 auto;}
		#body .resumentNbgOld .resumentNbg .rMentDl dd.cut a{background: #4da1ff;color: #FFFFFF;}
		#body .resumentNbgOld .resumentNbg .rMentDl dd a:hover{background: #f1f5ff;color: #444;}
		#body .resumentNbgOld .resumentNbg .rMentDl dd.cut a:hover{background: #4da1ff;color: #FFFFFF;}
		/* .resumentNbg .rMentDl dd:hover a{background: #4da1ff;color: #FFFFFF;} */
		/* .resumentNbg .rMentDl dd.cut:hover a{background: #FFFFFF;color: #222222;} */
		#body .resumentNbgOld  .rMentDl dd.cut span,.rMentDl dd.cut a,.rMentDl dd.cut em,.rMentDl dd.cut i{background: #4da1ff;color: #FFFFFF;}
		#body .resumentNbgOld .resumentNbgOld_rMentLt .rMentDl dd.cut span{color: #FFFFFF;}
		#body .resumentNbgOld .rMentDl dd span{margin-left: 0;}
		#body .resumentNbgOld .rMentDl dd.cut a:hover{background: none;}
		#body .resumentNbgOld .rMentDl dd.cut span, .rMentDl dd.cut a, .rMentDl dd.cut em, .rMentDl dd.cut i{
			background: none;
			color: #FFFFFF;
		}
		#body .resumentNbgOld .rMentDl dd{width: 204px;height: 47px;line-height: 47px;background: #5080ad;cursor: pointer;}
		#body .resumentNbgOld .rMentRt{border-left:0 !important;}
		#body .resumentNbgOld .rMentDl dd.cut {
		    background: #00c0c7;}
			#body .resumentNbgOld .rMentDl dd.cut:hover{
				background: #00c0c7;
			}
		#body .resumentNbgOld .rMentLt{
				
		}
		#body .resumentNbgOld .rMentDl dd:hover {
		    background: #578cbc;}
			.resumentNbg .rMentDl dd a:hover {
			    background: none;
			    color: #444;}
				#body .resumentNbgOld  .rMentLt{}
				#body .resumentNbgOld .rMentLt{padding-top: 0;width: 204px;}
				#body .resumentNbgOld .rMentDl dd{width: 204px;}
				#body .resumentNbgOld .resumentNbgOld_rMentLt .rMentDl dd em,#body .resumentNbgOld .resumentNbgOld_rMentLt .rMentDl dd span,#body .resumentNbgOld .resumentNbgOld_rMentLt .rMentDl dd i {
					display: inline-block;
					vertical-align: middle;
					color: #c2e2ff;
					line-height: 36px;
					font-size: 14px;
					cursor: pointer;
				}
				#body .resumentNbgOld .rMentDl dd.cut em {
					background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/resumenticon.png) -1px -141px;
				}
				#body .resumentNbgOld .rMentDl dd em {
				    width: 4px;
				    height: 4px;
				    background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/resumenticon.png) -6px -141px;
				    margin: 0 6px 0 37px;}
					.qq_newsx{margin-left: 510px !important;}
					.feedback{margin-left: 516px !important;}
					..sus{margin-left: 513px !important;}
    </style>

</head>

<body id="body">
<?php if ($_smarty_tpl->getVariable('left_type')->value==1){?>
<?php $_template = new Smarty_Internal_Template('new_header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par',"全职招聘");$_template->assign('cur','面试管理'); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php }else{ ?>
<?php $_template = new Smarty_Internal_Template('new_header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par',"全职招聘");$_template->assign('cur','简历管理'); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php }?>


<div class="resumentNbg resumentNbgOld">
	<?php if ($_smarty_tpl->getVariable('left_type')->value==2){?>
	<?php $_template = new Smarty_Internal_Template('resume/apply/nav.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('cur',"offer管理"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
	<?php }else{ ?>
	<div class="rMentLt resumentNbgOld_rMentLt">
		<dl class="rMentDl">
			<dd>
				<a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/'),$_smarty_tpl);?>
" >
					<em></em><span>面试管理</span>
				</a>
			</dd>
			<dd class="cut">
				<a href="<?php echo smarty_function_get_url(array('rule'=>'/offermanager'),$_smarty_tpl);?>
">
					<em></em><span>offer管理</span>
				</a>
			</dd>
		</dl>
	</div>
	<?php }?>

	<!-- 右侧内容 -->
	<div class="rMentRt">
		<div class="content">
			<hgroup>
				<div class="part part1">
					<form id="frmInvite" method="get" action="<?php echo smarty_function_get_url(array('rule'=>'/offermanager/index/'),$_smarty_tpl);?>
" class="offerSearch">
						<input type="hidden" name="left_type" value="<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
">
						<!-- 面试状态 -->
						<div class="offerStatus">
							<span>状态：</span>
							<ul class="statusList">
								<li class="is_send <?php if ($_smarty_tpl->getVariable('re_con')->value['is_send']==0){?>curStatus<?php }?>" data-value='0'><a href="<?php echo smarty_function_get_url(array('rule'=>'/offermanager/'),$_smarty_tpl);?>
?left_type=<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
&is_send=0"><span></span>未发offer</a></li>
								<li class="is_send <?php if ($_smarty_tpl->getVariable('re_con')->value['is_send']==1){?>curStatus<?php }?>" data-value='1'><a href="<?php echo smarty_function_get_url(array('rule'=>'/offermanager/'),$_smarty_tpl);?>
?left_type=<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
&is_send=1"><span></span>已发offer</a></li>
							</ul>
							<input type="hidden" name="is_send" value="<?php echo $_smarty_tpl->getVariable('re_con')->value['is_send'];?>
"/>
						</div>

						<!-- 筛选条件 -->
						<div class="filterOptions">
							<span>职位：</span>
							<div class="xkTabSelect inB"><span id="tstDropJob" class="drop zindex"></span></div>


							<!-- 面试时间 -->
							<?php if ($_smarty_tpl->getVariable('re_con')->value['is_send']==1){?>
							<div class="makSelect makTime inB">
								<b>发送日期：</b>
								<input type="text" id="startDay" name="s_time" value="<?php echo $_smarty_tpl->getVariable('re_con')->value['s_time'];?>
"  autocomplete='off' onClick="WdatePicker({onpicked:function(){$('.j_time_search_btn').click();submit();},oncleared:function(){$('.j_time_search_btn').click();submit();}})">
								<span>~</span>
								<input type="text" id="endDay" name="e_time" value="<?php echo $_smarty_tpl->getVariable('re_con')->value['e_time'];?>
"  autocomplete='off' onClick="WdatePicker({onpicked:function(){$('.j_time_search_btn').click();submit();},oncleared:function(){$('.j_time_search_btn').click();submit();}})">
							</div>
							<?php }?>

							<!-- 姓名 -->
							<div class="nameSearch inB">
								姓名：
								<input type="text" value="<?php echo $_smarty_tpl->getVariable('re_con')->value['name'];?>
" name="name" placeholder="请输入"  autocomplete='off'>
							</div>

							<!-- 搜索按钮 -->
							<input type="button" value="搜索" class="mianshiSearchBtn">
						</div>
					</form>
					
					<div class="offerTips">
						<a href="javascript:;">（数据仅保存3个月）</a>
					</div>
					
					<div class="offerDatas">
						<?php if (!empty($_smarty_tpl->getVariable('list',null,true,false)->value)){?>
						<table class="offerTable">
							<thead class="offerTbHead">
								<tr>
									<th width="80" class="name">姓名</th>
									<th width="150">职位</th>
									<th width="128">电话</th>
									<th width="162">发送日期</th>
									<th width="93">状态</th>
									<th width="120">操作</th>
								</tr>
							</thead>
							<tbody>
								
								<?php  $_smarty_tpl->tpl_vars['v_list'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v_list']->key => $_smarty_tpl->tpl_vars['v_list']->value){
?>
									<tr>
										<td class="name overtext"><a class="j_resume_detail_btn" target="_blank" href="/resume/resumeshow/type-network-resumeid-<?php echo $_smarty_tpl->tpl_vars['v_list']->value['resume_id'];?>
-src-invite-invitid-<?php echo $_smarty_tpl->tpl_vars['v_list']->value['invite_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['v_list']->value['user_name'];?>
</a></td>
										<td class="overtext pos offerManager_NamejobName" title="<?php echo $_smarty_tpl->tpl_vars['v_list']->value['station'];?>
"><?php echo $_smarty_tpl->tpl_vars['v_list']->value['station'];?>
</td>
										<td class="overtext"><?php echo $_smarty_tpl->tpl_vars['v_list']->value['mobile_phone'];?>
</td>
										<td class="overtext"><?php if ($_smarty_tpl->tpl_vars['v_list']->value['is_offer_send']){?><?php echo $_smarty_tpl->tpl_vars['v_list']->value['send_time_show'];?>
<?php }else{ ?>-<?php }?></td>
										<td class="overtext"><?php if ($_smarty_tpl->tpl_vars['v_list']->value['is_offer_send']){?>已发送<?php }else{ ?>未发<?php }?></td>
										<td class="overtext" invite="<?php echo $_smarty_tpl->tpl_vars['v_list']->value['invite_id'];?>
" station="<?php echo $_smarty_tpl->tpl_vars['v_list']->value['station'];?>
">
											<a class="j_send_offer_btn"  target="_blank" href="/offertemplate/index/invite_id-<?php echo $_smarty_tpl->tpl_vars['v_list']->value['invite_id'];?>
">发送</a>
											<?php if ($_smarty_tpl->tpl_vars['v_list']->value['is_offer_send']==1){?><a class="j_see_btn"  target="_blank" href="/offermanager/VisitOffer/invite_id-<?php echo $_smarty_tpl->tpl_vars['v_list']->value['invite_id'];?>
">查看</a><?php }?>
											<a href="javascript:;" class="hasHired" <?php if ($_smarty_tpl->tpl_vars['v_list']->value['audition_result']==9){?>style="display:none;"<?php }?>>已入职</a>
										</td>
									</tr>
								<?php }} ?>
							
							</tbody>
						</table>
						<?php }else{ ?>
						<div class="noData">
							<p>未找到数据，请更换筛选条件、关键词再试！</p>
	                        <!-- <a class="btn" href="javascript:void(0);" id="btnClearFilterSearch"><i class="hbFntWes">&#xf014;</i>清空筛选/搜索条件</a></p> -->
						</div> 
						<?php }?>
					</div>
				</div>
			</hgroup>
			<?php echo $_smarty_tpl->getVariable('pager')->value;?>

		</div>
	</div>
<div class="j_time_search_btn" data-explain="do not delete!" style="display: none"></div>
<div class="j_username_search_btn" data-explain="do not delete!" style="display: none"></div>
<script  type="text/javascript">
    var action_url = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
';
    action_dom = [
        ['#tstDropJob', 393],
        ['.j_time_search_btn', 394],
        ['.j_username_search_btn', 395],
        ['.j_resume_detail_btn', 396],
        ['.j_send_offer_btn', 397],
        ['.j_see_btn', 398],
    ];
</script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'action.js'),$_smarty_tpl);?>
"></script>

	<script type="text/javascript">
        $('.mianshiSearchBtn').on('click', function(){
            if($.trim($('input[name=name]').val()))
                $('.j_username_search_btn').click();
            $('#frmInvite').submit();
        });
	    $('#tstDropJob').droplist({
	        defaultTitle : '全部职位',
	        style : 'width:105px;',
	        noSelectClass : '#444',
	        inputWidth : 105,
	        width : 128,
	        hddName : 'station',
	        items : <?php echo $_smarty_tpl->getVariable('job_list')->value;?>
,
	    	selectValue : '<?php echo $_smarty_tpl->getVariable('re_con')->value["station"];?>
',
	        maxScroll : 10,
	        onSelect : function(i, name) {
	        	//选中后的事件
	        	$('#frmInvite').submit()
	    	}
	    });
	    /*选择发送状态*/
	   	// $('.is_send').on('click',function() {
	   	// 	var val = $(this).attr("data-value");
	   	// 	console.log(val)
	   	// 	$('input[name="is_send"]').val(val)
	   	// 	$('#frmInvite').submit()
	   	// })
	hbjs.use('tools.cookie, widge.overlay.hbDialog, widge.overlay.confirmBox, product.hbCommon', function(cookie, Dialog, confirmBox, hbCommon, $, util) {
	    var pWidth = 70,
	        fontWidth = 18;
	    function showModel(icon, msg) {
	        confirmBox.timeBomb(msg, {
	            name: icon,
	            width: pWidth + msg.length * fontWidth
	        });
	    }	
		//已入职
		$('.hasHired').click(function(){
            var station = $(this).parent().attr('station');
            var invite_id = $(this).parent().attr('invite');
            var entry_time = "<?php echo date('Y-m-d');?>
";
            var hasHiredStr = '<div class="hasHiredPop"><div><span>入职时间</span><input type="text" onclick="WdatePicker()" id="entryData" value="'+entry_time+'" /></div><div><span>入职职位</span><input type="text" id="entryJobx" value="'+station+'" /></div></div>';
			confirmBox.confirm(hasHiredStr,'入职设置', function(obj){
				var _this = this;
				entry_time = $('#entryData').val();
				station = $('#entryJobx').val();
				if(entry_time == ''){
				   showModel('fail','请选择入职时间');
				   return;
				}
                if(station == ''){
				   showModel('fail','请填写入职职位');
				   return;
				}
					
				$.ajax({
                    url : "<?php echo smarty_function_get_url(array('rule'=>'/invitev1/entryJob'),$_smarty_tpl);?>
",
                    type : "post",
                    dataType : "json",
                    data : {
                        station : station,
                        invite_id : invite_id,
                        entry_time : entry_time
                    },
                    success : function(json) {
                        if(json.isNeedLogin){
                            window.location.reload();
                            return;
                        }
                       if (!json.status) {
                       	showModel('fail',json.msg);
                           return;
                       }
                       showModel('success','操作成功');
                       setTimeout(function() {
                       	window.location.reload();
                       }, 2000);
                    }
                });
			},{
				width:260,
				close : 'x',
				confirmBtn: '<a href="javascript:;" class="btn1 btnsF12" id="submitBtn">确定</a>',
				cancelBtn: '<a href="javascript:;" class="btn3 btnsF12" id="submitBtn">取消</a>'
			});
		});
		
    });
		
		
	</script>
</div>

<?php $_template = new Smarty_Internal_Template("msgpop.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("chat/chat.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</body>
</html>

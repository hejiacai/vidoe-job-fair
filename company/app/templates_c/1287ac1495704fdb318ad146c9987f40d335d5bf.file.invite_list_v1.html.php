<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:40:05
         compiled from "app\templates\resume/invite/invite_list_v1.html" */ ?>
<?php /*%%SmartyHeaderCode:153845e703885cdf9e3-15724730%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1287ac1495704fdb318ad146c9987f40d335d5bf' => 
    array (
      0 => 'app\\templates\\resume/invite/invite_list_v1.html',
      1 => 1584332295,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '153845e703885cdf9e3-15724730',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
if (!is_callable('smarty_modifier_replace')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.replace.php';
?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $_smarty_tpl->getVariable('title')->value;?>
</title>
    <!–[if lt IE9]> 
    <script src="<?php echo smarty_function_version(array('file'=>'html5.js'),$_smarty_tpl);?>
"></script>
    <![endif]–>
    
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'comback.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'resument2015.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'laydate.css'),$_smarty_tpl);?>
" />

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
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'laydate.js'),$_smarty_tpl);?>
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
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'global.js'),$_smarty_tpl);?>
"></script>

    <style>
    .rMentRt{ position:relative;}
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
            position: fixed;
            top: 50%;
            margin-top: -150px;
            background: #fff;
        }

        .m_master{
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: #000;
            opacity: 0.4;
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
        /*分享到微信*/
.linkSharePop{width:500px; overflow: hidden; padding: 30px; margin: 0 auto;}
.linkSharePut{ overflow: hidden; position: relative;}
.linkSharePut span,.linkSharePut input,.linkSharePut a{ line-height: 32px; display: block; float: left; font-size: 14px;}
.linkSharePut input{width:300px; height: 30px; line-height: 30px; color: #444; padding: 0 8px; margin-right: 10px; border: 1px solid #ccc;}
.linkSharePut input#linkCopyx{position: absolute; opacity: 0; top: 0; left: 0; height: 1px;}
.linkSharePut a{ display: block;height: 32px; line-height: 32px;width:100px; text-align: center; background: #2b6fad; color: #fff; border-radius: 4px;}
.linkShareCode{ overflow: hidden; padding-top: 30px;}
.linkShareCode span{ display: block; font-size: 14px; text-align: left;}
.linkShareCode img{ display: block; width:130px; height: 130px; margin:20px auto 10px auto;}
.linkShareCode em{ display: block; text-align: center; font-size: 12px;}
.hasHiredPop div{ overflow: hidden; padding-bottom: 10px;}
.hasHiredPop span,.hasHiredPop input{ display: block; float: left; line-height: 30px; color: #222;}
.hasHiredPop input{ border: 1px solid #ccc; margin-left: 10px; text-indent: 8;}


    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'invite_v1.css'),$_smarty_tpl);?>
" />
	<style type="text/css">
		/*陈杨20191231 面试管理 表格 状态 掉下去了*/
		/* .rMentRt .mianshiSearch{padding-left: 30px;}
		.mianshiSearchRs{padding-left: 0;padding-right: 0;} */
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
<div class="resumentNbg">
    <?php if ($_smarty_tpl->getVariable('left_type')->value==1){?>
    <!-- 左侧内容 -->
    <div class="rMentLt">
        <dl class="rMentDl">
            <dd class="cut">
                <a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/'),$_smarty_tpl);?>
" >
                    <em></em><span>面试管理</span>
                </a>
            </dd>
            <dd class="">
                <a href="<?php echo smarty_function_get_url(array('rule'=>"/offermanager"),$_smarty_tpl);?>
?left_type=1">
                    <em></em><span>offer管理</span>
                </a>
            </dd>
        </dl>
    </div>
    <?php }else{ ?>
    <?php $_template = new Smarty_Internal_Template('resume/apply/nav.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('cur',"面试管理"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
    <?php }?>
    
    <!-- 右侧内容 -->
    <div class="rMentRt">
		<div class="content">
			<section class="section">
				<hgroup>
					<div class="part part1">
						<form id="frmInvite" method="get" action="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index/'),$_smarty_tpl);?>
" class="mianshiSearch">
                            <input type="hidden" name="audition_result" value="<?php echo $_smarty_tpl->getVariable('audition_result')->value;?>
"/>
                            <input type="hidden" name="left_type" value="<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
"/>
							<!-- 面试状态 -->
							<div class="mianshiStatus">
								<span>状态：</span>
								<ul class="statusList">
									<li <?php if ($_smarty_tpl->getVariable('audition_result')->value==99){?>class="curStatus"<?php }?>><a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index/'),$_smarty_tpl);?>
-audition_result-99-left_type-<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
"><span></span>已邀请待面试</a></li>
									<li <?php if (!$_smarty_tpl->getVariable('audition_result')->value){?>class="curStatus"<?php }?>><a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index/'),$_smarty_tpl);?>
-audition_result-0-left_type-<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
"><span></span>已面试待反馈</a></li>
									<li <?php if ($_smarty_tpl->getVariable('audition_result')->value==1){?>class="curStatus"<?php }?>><a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index/'),$_smarty_tpl);?>
-audition_result-1-left_type-<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
"><span></span>已通过</a></li>
									<li <?php if ($_smarty_tpl->getVariable('audition_result')->value==2){?>class="curStatus"<?php }?>><a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index/'),$_smarty_tpl);?>
-audition_result-2-left_type-<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
"><span></span>未通过</a></li>
									<li <?php if ($_smarty_tpl->getVariable('audition_result')->value==8){?>class="curStatus"<?php }?>><a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index/'),$_smarty_tpl);?>
-audition_result-8-left_type-<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
"><span></span>面试爽约</a></li>
									<li <?php if ($_smarty_tpl->getVariable('audition_result')->value==9){?>class="curStatus"<?php }?>><a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index/'),$_smarty_tpl);?>
-audition_result-9-left_type-<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
"><span></span>已入职</a></li>
								</ul>
							</div>
						
							
						
							<!-- 筛选条件 -->
							<div class="filterOptions">
								<!-- 职位 -->
								<span>职位：</span>
                                <div class="xkTabSelect"><span id="tstDropJob" class="drop zindex"></span></div>
							
								<!-- 面试时间 -->
								<div class="makSelect makTime inB" <?php if ($_smarty_tpl->getVariable('audition_result')->value==99){?>style="display:none;"<?php }?>>
										<b>时间：</b>
										<input type="text" id="startDay" name="min_audition_time" value="<?php echo $_smarty_tpl->getVariable('min_audition_time')->value;?>
" autocomplete='off'  onClick="WdatePicker({onpicked:function(){$('.j_time_search_btn').click();submit();},oncleared:function(){$('.j_time_search_btn').click();submit();}})">
										<span>~</span>
										<input type="text" id="endDay" name="max_audition_time" value="<?php echo $_smarty_tpl->getVariable('max_audition_time')->value;?>
"  autocomplete='off' onClick="WdatePicker({onpicked:function(){$('.j_time_search_btn').click();submit();},oncleared:function(){$('.j_time_search_btn').click();submit();}})">
								</div>
								
                                <input type="hidden" name="order_type" id="hidden_order_type" value="<?php echo $_smarty_tpl->getVariable('order_type')->value;?>
"/>
								<!-- 姓名 -->
								<div class="nameSearch inB">
									姓名：
									<input type="text" placeholder="请输入" name="user_name" value='<?php echo $_smarty_tpl->getVariable('user_name')->value;?>
' autocomplete="off">
								</div>
								<!-- 搜索按钮 -->
								<input type="button" value="搜索" class="mianshiSearchBtn">
							</div>
						</form>
							
						<!-- 日期选择 -->
						<div class="mianshiCalendar" <?php if ($_smarty_tpl->getVariable('audition_result')->value!=99){?>style="display:none;"<?php }?>>
							<div class="mianshiCalendarWrapper">
								<div class="dates fl">
									<ul>
                                        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('banner_dates')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
                                        <a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index/'),$_smarty_tpl);?>
?audition_result=99&min_audition_time=<?php echo $_smarty_tpl->tpl_vars['v']->value['audition_date'];?>
&left_type=<?php echo $_smarty_tpl->getVariable('left_type')->value;?>
">
										<li <?php if ($_smarty_tpl->tpl_vars['v']->value['audition_date']==$_smarty_tpl->getVariable('select_audition_time')->value){?>class="select"<?php }?>>
											<div class="specificDate">
												<p class="time"><?php echo $_smarty_tpl->tpl_vars['v']->value['audition_date_str'];?>
</p>
												<p class="nums"><?php echo $_smarty_tpl->tpl_vars['v']->value['num'];?>
</p>
											</div>
										</li>
                                        </a>
                                        <?php }} ?>
									</ul>
								</div>
								<div class="datePicker fl pr">
									<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/caledar.png" alt="">
									<p>招聘<br>日历</p>
                                    <input type="text" class="pa picDateInput" style="width: 100%;height: 100%;top:0;left:0;line-heigh:86px;opacity: 0;" <?php if ($_smarty_tpl->getVariable('min_audition_time')->value&&$_smarty_tpl->getVariable('max_audition_time')->value){?>value="<?php echo $_smarty_tpl->getVariable('min_audition_time')->value;?>
 - <?php echo $_smarty_tpl->getVariable('max_audition_time')->value;?>
"<?php }?>>
								</div>
							</div>
						</div>
						
						
						<div class="mianshiSearchRs" <?php if (empty($_smarty_tpl->getVariable('invitelist',null,true,false)->value)){?>style="display:none;"<?php }?>>
							<div class="mianshiSearchFilter clearfix">
								<label class="fl mianshiSelectAll" <?php if ($_smarty_tpl->getVariable('audition_result')->value!=0&&$_smarty_tpl->getVariable('audition_result')->value!=99){?>style="display:none;"<?php }?>>
									<!-- <input id="reverseallSelect" class="resuemSelectAll" type="checkbox"> -->
									<span class="inB"></span>
									全选
								</label>
								<p class="fl mianshiRsStatus" <?php if ($_smarty_tpl->getVariable('audition_result')->value!=0&&$_smarty_tpl->getVariable('audition_result')->value!=99){?>style="display:none;"<?php }?>>
									<a href="javascript:void(0);" class="btnOpInvite" id="giveUpBatch">面试爽约</a>
									<a href="javascript:void(0);" class="btnOpInvite" id="batchPass">通过</a>
									<a href="javascript:void(0);" class="btnOpInvite" id="batchNotPass">未通过</a>
								</p>

                                <div class="xkTabSelect" <?php if ($_smarty_tpl->getVariable('audition_result')->value!=0&&$_smarty_tpl->getVariable('audition_result')->value!=99){?>style="display:none;"<?php }?>><span id="tstTimeOrder" class="drop zindex"></span></div>
                                
								
								<a href="<?php echo smarty_function_get_url(array('rule'=>'/specification/StarAppraise'),$_smarty_tpl);?>
" class="sanxing fr" target="_blank">
									三招斩获五星职位评价！ 
								</a>
							</div>
							<div class="mianshiSearchData">
								<ul class="tableHeader">
									<li class="checkbox" <?php if ($_smarty_tpl->getVariable('audition_result')->value!=0&&$_smarty_tpl->getVariable('audition_result')->value!=99){?>style="display:none;"<?php }?>></li>
									<li class="time"><?php if ($_smarty_tpl->getVariable('audition_result')->value==9){?>入职时间<?php }else{ ?>面试时间<?php }?></li>
									<li class="person"><?php if ($_smarty_tpl->getVariable('audition_result')->value==9){?>姓名<?php }else{ ?>面试人<?php }?></li>
									<li class="phone">电话</li>
									<li class="pos"><?php if ($_smarty_tpl->getVariable('audition_result')->value==9){?>入职职位<?php }else{ ?>面试职位<?php }?></li>
									<li class="status">状态</li>
								</ul>
								
								<div class="tableBody" id="lstContent">
                                    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('invitelist')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
									<div class="tableTr">
										<div class="mianshiRs">
											<ul>
												<li class="checkbox" <?php if ($_smarty_tpl->getVariable('audition_result')->value!=0&&$_smarty_tpl->getVariable('audition_result')->value!=99){?>style="display:none;"<?php }?> invite='<?php echo $_smarty_tpl->tpl_vars['v']->value["invite_id"];?>
'>
													<span class="inB checkbox"></span>
												</li>
												<li class="time overtext"><?php if ($_smarty_tpl->getVariable('audition_result')->value==9){?><?php echo date('Y-m-d',strtotime($_smarty_tpl->tpl_vars['v']->value['entry_time']));?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['v']->value['audition_time_str'];?>
<?php }?></li>
												<li class="person overtext">
													<a class="j_resume_detail_btn" href="<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-resumeid-<?php echo $_smarty_tpl->tpl_vars['v']->value['resume_id'];?>
-src-invite-invitid-<?php echo $_smarty_tpl->tpl_vars['v']->value['invite_id'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['v']->value['user_name'];?>
</a>
												</li>
												<li class="phone overtext"><?php echo $_smarty_tpl->tpl_vars['v']->value['mobile_phone'];?>
</li>
                                                <?php if ($_smarty_tpl->getVariable('audition_result')->value==9){?>
												<li class="pos overtext" title="<?php echo $_smarty_tpl->tpl_vars['v']->value['entry_station'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['entry_station'];?>
</li>
                                                <?php }else{ ?>
												<li class="pos overtext" title="<?php echo $_smarty_tpl->tpl_vars['v']->value['station'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['station'];?>
</li>
                                                <?php }?>
												<li class="status overtext"><?php echo $_smarty_tpl->tpl_vars['v']->value['audition_result_name'];?>
</li>
											</ul>
										</div>
										<div class="options" invite="<?php echo $_smarty_tpl->tpl_vars['v']->value['invite_id'];?>
" resume="<?php echo $_smarty_tpl->tpl_vars['v']->value['resume_id'];?>
" username="<?php echo $_smarty_tpl->tpl_vars['v']->value['user_name'];?>
" station="<?php echo $_smarty_tpl->tpl_vars['v']->value['station'];?>
">
                                            <?php if ($_smarty_tpl->getVariable('audition_result')->value==0||$_smarty_tpl->getVariable('audition_result')->value==99){?>
											<a href="javascript:;" class="relWeixin">扫码给同事</a>
											<a href="javascript:;" class="giveUp">面试爽约</a>
											<a href="javascript:;" class="pass">通过</a>
											<a href="javascript:;" class="notpass">未通过</a>
                                            <?php }?>
                                            <?php if ($_smarty_tpl->getVariable('audition_result')->value==1){?>
											<a href="javascript:;" class="hasHired">已入职</a>
                                            <?php }?>
											<a href="javascript:;" class="remark">备注</a>
                                            <?php if ($_smarty_tpl->getVariable('audition_result')->value==0||$_smarty_tpl->getVariable('audition_result')->value==99||$_smarty_tpl->getVariable('audition_result')->value==1){?>
											<a href="javascript:;" class="j_send_offer_btn">发送offer</a>
                                            <?php }?>
										</div>
									</div>
									<?php }} ?>
								</div>
								<?php echo $_smarty_tpl->getVariable('pager')->value;?>
 
							</div>
						</div>
						
						<div class="noData" <?php if (!empty($_smarty_tpl->getVariable('invitelist',null,true,false)->value)){?>style="display:none;"<?php }?>>
								<p>未找到相关数据，请更换筛选条件、关键词再试！</p>
                                <?php if ($_smarty_tpl->getVariable('showfilter')->value){?><p><a class="btn" href="javascript:void(0);" id="btnClearFilterSearch"><i class="hbFntWes">&#xf014;</i>清空筛选/搜索条件</a></p><?php }?>
						</div> 
							
					</div>
				</hgroup>
			</section>
		</div>
    </div>
    
    <!------弹窗------>
    <div class="m_master" style='display:none;z-index: 11'></div>
    <!------添加面试结构操作（通过、不通过）------>
    <div class="mRtake"  id="delayjob" style='display:none;font-size: 10px;z-index: 12'>
        <p id="e1"></p>
        <div style="" id="e2"></div>
        <textarea id="msg_content" maxlength="70"></textarea>
        <div class="num-count" style=""><font id="count">可编辑，最多70字</font></div>
        <input type="hidden" id="user_invite_id">
        <input type="hidden" id="invite_result">
        <!--<ul>
            <li><a href="javascript:;" class="mRtakeBtn01">取消</a></li>
            <li><a href="javascript:;"onclick="" class="mRtakeBtn02" id="submitBtn">确定</a></li>
        </ul>-->
        <div class="dialogBtn">
        	<a href="javascript:;"onclick="" class="btn1 btnsF12 mRtakeBtn02" id="submitBtn">确定</a>
        	<a href="javascript:;" class="btn3 btnsF12 mRtakeBtn01">取消</a>
        </div>
    </div>
    <div class="j_notice_before_btn" data-explain="do not delete!" style="display: none"></div>
    <div class="j_no_notice_btn" data-explain="do not delete!" style="display: none"></div>
    <div class="j_other_btn" data-explain="do not delete!" style="display: none"></div>
    <div class="j_giveup_btn" data-explain="do not delete!" style="display: none"></div>
    <div class="j_giveup_notice_btn" data-explain="do not delete!" style="display: none"></div>
    <div class="j_page_load_btn" data-explain="do not delete!" style="display: none"></div>
    <div class="j_job_select_btn" data-explain="do not delete!" style="display: none"></div>
    <div class="j_username_select_btn" data-explain="do not delete!" style="display: none"></div>
    <div class="j_date_select_btn" data-explain="do not delete!" style="display: none"></div>
    <div class="j_time_search_btn" data-explain="do not delete!" style="display: none"></div>
    <div class="j_relWeixin_btn" data-explain="do not delete!" style="display: none"></div>
</div>
<script  type="text/javascript">
    var action_url = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
';
    if(typeof action_dom == 'object'){
        action_dom.push( ['.j_notice_before_btn', 58]);
        action_dom.push( ['.j_no_notice_btn', 59]);
        action_dom.push( ['.j_giveup_btn', 95]);
        action_dom.push( ['.j_giveup_notice_btn', 96]);
        action_dom.push( ['.j_page_load_btn', <?php echo $_smarty_tpl->getVariable('action_log_type')->value;?>
]);
        action_dom.push( ['.j_job_select_btn', 372]);
        action_dom.push( ['.j_username_select_btn', 373]);
        action_dom.push( ['.j_date_select_btn', 374]);
        action_dom.push( ['.picDateInput', 375]);
        action_dom.push( ['#giveUpBatch', 376]);
        action_dom.push( ['#batchPass', 377]);
        action_dom.push( ['#batchNotPass', 378]);
        action_dom.push( ['.sanxing', 379]);
        action_dom.push( ['.j_resume_detail_btn', 380]);
        action_dom.push( ['.j_relWeixin_btn', 381]);
        action_dom.push( ['.remark', 382]);
        action_dom.push( ['.giveUp', 383]);
        action_dom.push( ['.pass', 384]);
        action_dom.push( ['.notpass', 385]);
        action_dom.push( ['.j_send_offer_btn', 386]);
        action_dom.push( ['#tstTimeOrder', 387]);
        action_dom.push( ['.j_time_search_btn', 392]);
    }else{
        action_dom = [
            ['.j_notice_before_btn', 58],//对应码表参数
            ['.j_no_notice_btn', 59],//对应码表参数
            ['.j_giveup_btn', 95],//对应码表参数
            ['.j_giveup_notice_btn', 96],//对应码表参数
            ['.j_page_load_btn', <?php echo $_smarty_tpl->getVariable('action_log_type')->value;?>
],
            ['.j_job_select_btn', 372],
            ['.j_username_select_btn', 373],
            ['.j_date_select_btn', 374],
            ['.picDateInput', 375],
            ['#giveUpBatch', 376],
            ['#batchPass', 377],
            ['#batchNotPass', 378],
            ['.sanxing', 379],
            ['.j_resume_detail_btn', 380],
            ['.j_relWeixin_btn', 381],
            ['.remark', 382],
            ['.giveUp', 383],
            ['.pass', 384],
            ['.notpass', 385],
            ['.j_send_offer_btn', 386],
            ['#tstTimeOrder', 387],
            ['.j_time_search_btn', 392]
        ];
    }
</script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'action.js'),$_smarty_tpl);?>
"></script>
    
<script type="text/javascript" language="javascript">
    $('body').on('click', '.j_notice_before', function(){
        $('.j_notice_before_btn').click();
    });
    $('body').on('click', '.j_no_notice', function(){
        $('.j_no_notice_btn').click();
    });
    $('body').on('click', '.j_other', function(){
        $('.j_other_btn').click();
    });
    $(function () {
        <?php if ($_smarty_tpl->getVariable('selectDateAction')->value){?>
        $('.j_date_select_btn').click();
        <?php }?>
        <?php if ($_smarty_tpl->getVariable('logLoadAction')->value){?>
        $('.j_page_load_btn').click();
        <?php }?>
        var maxCount = 70;  // 最高字数，这个值可以自己配置
        $("#msg_content").on('keyup', function() {
            if (this.value.length >= maxCount){
                $("#count").html('不可编辑，字数已满').css("color","red");

            }else {
                $("#count").css("color","");
            }

        })
    })
    $('.j_send_offer_btn').click(function(){
        var newwindow = window.open('about:blank');
        var invite_id = $(this).parent().attr('invite');
        newwindow.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/index/'),$_smarty_tpl);?>
?invite_id=" + invite_id;
    });
    $("#submitBtn").click(function () {
        var result = $("#invite_result").val()
        var invite_id = $("#user_invite_id").val()
        var msg_content = $("#msg_content").val()
        if (msg_content.length > 70){
            $.message("短信内容最多70个字");
            return false;
        }
        if (msg_content.length <= 0){
            $.message("通知内容不能为空")
            return false
        }
        $.ajax({
            url : "<?php echo smarty_function_get_url(array('rule'=>'/invite/state'),$_smarty_tpl);?>
",
            type : "post",
            dataType : "json",
            data : {
                result : result,
                id : invite_id,
                msg_content:msg_content
            },
            success : function(json) {
                if (!json.success) {
                    $.message(json.error);
                    return false;
                }

                $.message("操作成功",function(){
                    window.location.reload();
                });
            }
        });
    })
    $('.mianshiSearchBtn').on('click', function(){
        submit();
    });
    var submit = function() {
        if($.trim($('input[name=user_name]').val()) != '')
            $('.j_username_select_btn').click();
        //清空水印 
        $('#frmInvite').clearWatermark();
        $('#frmInvite').get(0).submit();	
    },
    updateRemark = function(resumeid) {
        // 更新备注
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/resumeremark/index/"),$_smarty_tpl);?>
resume_id-'+resumeid+'-v-'+Math.random(),{title:'备注',onclose:function(){
            // 更新备注
            $.getJSON("<?php echo smarty_function_get_url(array('rule'=>"/resumeremark/ResumeRemark/"),$_smarty_tpl);?>
"+'-resumeid-'+resumeid+'-v-'+Math.random(),function(result){
                var element = $('#lstContent').find('[resume="'+resumeid+'"]');
                element.find('.remark').unTooltip();
                if(result.remark ==''||result.remark == null) {
                    element.find('.remark').removeAttr('data-toggle').removeAttr('title');				
                }else {
                    element.find('.remark').attr('data-toggle','tooltip').attr('title',result.remark+' '+result.updatetime);
                }			    
            });
        }});
    },
    selectInvite = function(){
       var checkboxs = $('#lstContent').find('ul li span.select'),
           invites = [];
       for(var i=0,len=checkboxs.length;i<len;i+=1) {
           invites.push($(checkboxs[i]).parent().attr('invite'));
       } 	
       return invites;
    },
    giveUp = function(invite_ids, username){
			
			if($('.mianshiSelectAll').hasClass('select')){
				
				$.ajax({
				    url : "<?php echo smarty_function_get_url(array('rule'=>'/invite/GiveUpAudition'),$_smarty_tpl);?>
",
				    type : "post",
				    dataType : "json",
				    data : {
				        invite_ids : invite_ids,
				              give_up_type : 3
				          },
				    success : function(json) {
				        if(!json.success){
				            $.message(json.error, function(){
				                window.location.reload();
				            });
				            return;
				        }
				        if (json.is_send_msg) {
				                  $.message("已警告该求职者，后续爽约会影响求职成功率",function(){
				                      window.location.reload();
				                  });
				              } else {
				                  $.message("操作成功",function(){
				                      window.location.reload();
				                  });
				              }
				    }
				});
				
				
			}else{
				var value="",notice_person='';
				  // $.confirm("确认将"+username+"设置为未参加面试吗？","提示",function(){
				var a=$.confirm(
					"面试未到情况<br><label><input type='radio' class='j_notice_before' name='pigeon' value='1'>求职者已提前通知</label>" + "<br>" + "<div>" + "<label class='secondLabel'>" + "<input type='radio' class='j_no_notice' name='pigeon' value='2'>求职者未提前通知 " + "</label>" + "<span style=\"margin-left: 20px;display: none\" class=\"complaint\">" + "<label class='complaint'>" + "<input type=\"checkbox\" value=\"1\" name=\"notice_person\" >同时投诉" + "</label>" + "</span>" + "</div>" + "<p style=\"padding-left:10px;color:#FF0000;margin-bottom:15px;display:none;\" class=\"tishi\">客服将和求职者核实，如多次反馈情况不属实，将限制使用“面试未到”反馈功能</p></label><label><input type='radio' class='j_other' name='pigeon' value='3'>其他情况</label>", "提示",
					function(){
				   if(value===""){
						 $.message('请选择面试未到情况')
					 }
					 
					 
					 
					 if(value!==""){
						 $.ajax({
						     url : "<?php echo smarty_function_get_url(array('rule'=>'/invite/GiveUpAudition'),$_smarty_tpl);?>
",
						     type : "post",
						     dataType : "json",
						     data : {
                                    invite_ids : invite_ids,
                                    give_up_type : value,
                                    notice_person : notice_person
				               },
						     success : function(json) {
						         if(!json.success){
						             $.message(json.error, function(){
						                 window.location.reload();
						             });
						             return;
						         }
                                 //埋点企业选择和未选择同时投诉
                                if(value == 2){
                                    if(notice_person == 1) 
                                        $('.j_giveup_notice_btn').click();
                                    else
                                        $('.j_giveup_btn').click();
                                }
                                $.message("操作成功",function(){
                                    window.location.reload();
                                });
                                return;
//						         if (json.is_send_msg) {
//				                       $.message("已警告该求职者，后续爽约会影响求职成功率",function(){
//				                           window.location.reload();
//				                       });
//				                   } else {
//				                       $.message("操作成功",function(){
//				                           window.location.reload();
//				                       });
//				                   }
						     }
						 });
					 }
					 
				});
				a.onclose=function(){
					value=$('[name=pigeon]:checked').val()||"";
                    notice_person = $('[name=notice_person]:checked').val()||"";
				};
				
				a.find('label').on('click',function(){
					var index = a.find('label').index(this);
					if(index==0 || index == 3){
						$('[name=notice_person]').removeAttr('checked');
						$('.complaint').hide();
						$('.tishi').hide();
					}else if(index==2){
						if($(this).find('input').attr('checked')) {
							$('.tishi').show()
						}else {
							$('.tishi').hide()
						}
					}else if(index==1) {
						$('.complaint').show()
					}
				});

			}
			
			
    	
        
    },
    serResult = function(invite_id, result, username) {
        var title = "面试通过反馈";
        var send_msg_html = "点击确定后我们会将以下通知内容发送给求职者.<br><div style='width: 100%;height: 70px;border: 1px solid #cec9c9' id='abc'>恭喜您，您已经通过了我们的面试，稍后会有工作人员联系您【<?php echo $_smarty_tpl->getVariable('company_shortname')->value;?>
】</div>"
        var send_msg = "恭喜您，您已经通过了我们的面试，稍后会有工作人员联系您【<?php echo $_smarty_tpl->getVariable('company_shortname')->value;?>
】";
        if(result == 2){
            title = "面试未通过反馈";
            send_msg_html = "点击确定后我们会将以下通知内容发送给求职者.<br>" +
                "<div style='border: 1px solid #cec9c9' name='msg_content'>你好。经过我们慎重评估和考虑，您暂时不符合我们的岗位，希望您早日找到满意的工作，谢谢【<?php echo $_smarty_tpl->getVariable('company_shortname')->value;?>
】</div>";
            send_msg = "你好。经过我们慎重评估和考虑，您暂时不符合我们的岗位，希望您早日找到满意的工作，谢谢【<?php echo $_smarty_tpl->getVariable('company_shortname')->value;?>
】";
        }
        $.confirm(send_msg,title,function(){
            $.ajax({
                url : "<?php echo smarty_function_get_url(array('rule'=>'/invite/state'),$_smarty_tpl);?>
",
                type : "post",
                dataType : "json",
                data : {
                    result : result,
                    id : invite_id,
                    msg_content : send_msg
                },
                success : function(json) {
                    if (!json.success) {
                        $.message(json.error, function(){
                            window.location.reload();
                        });
                        return;
                    }

                    $.message("操作成功",function(){
                        window.location.reload();
                    });
                }
            });
        });
    };
    // 清空筛选条件
    $('#btnClearFilterSearch').click(function(e){
        $('#tstDropJob').initContent();
        $("#startDay").val("");
        $("#endDay").val("");
        submit();
    });
    $('#tstDropJob').droplist({
        defaultTitle : '全部职位',
        style : 'width:105px;',
        noSelectClass : '#444',
        inputWidth : 105,
        width : 128,
        hddName : 'station',
        items : <?php echo $_smarty_tpl->getVariable('jobs')->value;?>
,
        selectValue : '<?php echo $_smarty_tpl->getVariable('station')->value;?>
',
        maxScroll : 10,
        onSelect : function(i, name) {
            $('.j_job_select_btn').click();
            //选中后的事件
            submit();
        }
    });
    $("#tstTimeOrder").droplist({
        defaultTitle : '时间排序',
        style : 'width:105px;',
        noSelectClass : '#444',
        inputWidth : 105,
        width : 128,
        hddName : 'select_order_type',
        items : <?php echo $_smarty_tpl->getVariable('order_types')->value;?>
,
        selectValue : '<?php echo $_smarty_tpl->getVariable('order_type')->value;?>
',
        maxScroll : 10,
        onSelect : function(i, name) {
            $('#hidden_order_type').val(i);
            //选中后的事件
            submit();
        }
    });

    //备注
    $(".remark").on('click', function(){
        var resume_id = $(this).parent().attr("resume");
        updateRemark([resume_id]);
    });
	
    //放弃面试
    $(".giveUp").on("click", function(){
        var invite_id = $(this).parent().attr("invite");
        var username = $(this).parent().attr("username");
        giveUp(invite_id,username);
    });
    //批量放弃
    $("#giveUpBatch").on("click", function(){
        var invites = selectInvite();
        if(invites.length <= 0){
            $.message("请选择数据");
            return;
        }
        giveUp(invites,"");
    });
    //通过面试
    $(".pass").on("click", function(){
        var invite_id = $(this).parent().attr("invite");
        var username = $(this).parent().attr("username");
        // serResult(invite_id,1,username);
        addMobile(invite_id,1,username)
    });

    //弹窗信息
    function addMobile(invite_id, result, username) {
        var title = "面试通过反馈";
        var send_msg = "恭喜您 "+username+"，您已经通过了我们的面试，稍后会有工作人员联系您【<?php echo $_smarty_tpl->getVariable('company_shortname')->value;?>
】"

        if(result == 2){
            title = "面试未通过反馈";
            var send_msg = username+"，你好。经过我们慎重评估和考虑，您暂时不符合我们的岗位，希望您早日找到满意的工作，谢谢【<?php echo $_smarty_tpl->getVariable('company_shortname')->value;?>
】";
        }
        $("#user_invite_id").val(invite_id)
        $("#invite_result").val(result)
        $(".m_master").show();
        $("#delayjob").show();
        $("#e1").html(title)
        $("#e2").html("点击确定后我们会将以下通知内容发送给求职者")
        $("#msg_content").val(send_msg)
    }
    //隐藏弹窗
    $(".mRtakeBtn01").click(function(){
        $(".m_master").hide();
        $(".mRtake").hide();
    });
    //批量通过面试
    $("#batchPass").on("click", function(){
        var invites = selectInvite();
        if(invites.length <= 0){
            $.message("请选择数据");
            return;
        }
        serResult(invites.join(","),1,"");
    });

    //未通过面试
    $(".notpass").on("click", function(){
        var invite_id = $(this).parent().attr("invite");
        var username = $(this).parent().attr("username");
        // serResult(invite_id,2,username);
        addMobile(invite_id,2,username)
    });
    //批量未通过面试
    $("#batchNotPass").on("click", function(){
        var invites = selectInvite();
        if(invites.length <= 0){
            $.message("请选择数据");
            return;
        }
        serResult(invites.join(","),2,"");
    });


    /*全选*/
    $('.mianshiSelectAll').on('click',function(){
        if($(this).is('.select')){
            $(this).removeClass('select')
            $('.tableTr .mianshiRs .checkbox span').removeClass('select')
        }else{
            $(this).addClass('select')
            $('.tableTr .mianshiRs .checkbox span').addClass('select')
        }
    })

    /*单个选择*/
    $('.tableTr .mianshiRs .checkbox span').on('click',function() {
        if($(this).is('.select')) {
            $(this).removeClass('select')
        }else{
            $(this).addClass('select')
        }

        /*判断是否全选*/
        var allCheckBox = $('.tableTr .mianshiRs .checkbox span').length;
        var selectCheckBox = $('.tableTr .mianshiRs .checkbox span.select').length;
        if(allCheckBox != selectCheckBox) {
            $('.mianshiSelectAll').removeClass('select')
        }else{
            $('.mianshiSelectAll').addClass('select')
        }
    })

    hbjs.use('tools.cookie, widge.overlay.hbDialog, widge.overlay.confirmBox, product.hbCommon', function(cookie, Dialog, confirmBox, hbCommon, $, util) {
        var pWidth = 70,
            fontWidth = 18;
        function showModel(icon, msg) {
            confirmBox.timeBomb(msg, {
                name: icon,
                width: pWidth + msg.length * fontWidth
            });
        }
         //转发到QQ、微信
        var lyldialog2 = new Dialog({
            idName: 'save-comp',
            title: '简历转发',
            width: 600,
            close: 'x',
    //        content:'<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/resume/getShareHtml/?resume_id=<?php echo $_smarty_tpl->getVariable('resume_id')->value;?>
',
            isAjax: true
        });
        lyldialog2.on('loadComplete', function(){
             var codeCopyBtn = this.query('.codeCopyBtn');
             var codeLink    = this.query('#linkCopyx');
             codeCopyBtn.on("click",function(){
                  codeLink[0].select(); // 选择对象
                  document.execCommand("Copy");
                 showModel('success', '复制成功');
             })
        });
		
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
        $(".relWeixin").click(function(){
            $('.j_relWeixin_btn').click();
            var _this = this;
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        dialog.resetSize(450).setContent({
                            title: '企业认证',
                            content: getLetterContent(json.msg)
                        }).show();
                    }
                }else{
                    var resume_id = $(_this).parent().attr('resume');
                    lyldialog2.setContent('<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/resume/getShareHtml/?resume_id='+resume_id).show();
                }

            });
            return false;
        });
    });

    /*layDate日历*/
    laydate.render({
        elem: '.picDateInput' ,//指定元素
        type: 'date',
        range: true,
        position: 'fixed',
        done: function(value, date, endDate){ //监听日期被切换
            /*选择的开始时间 结束时间*/
            // console.log(date,endDate)
             if(date.year) {
                var start = date.year + "-" + date.month + '-' + date.date;
                $('#startDay').val(start);
            }else{
                $('#startDay').val('');
            }
            if(endDate.year) {
                var end = endDate.year + "-" + endDate.month + '-' + endDate.date;
                $('#endDay').val(end);
            }else{
                $('#endDay').val('');
            }
            $('.j_time_search_btn').click();
            submit();
            // console.log(value,date,endDate);
            // console.log(new Date(start).getDay())
            // console.log(new Date(end).getDay())
            
            // $(".dates ul").html(initDates(start))
        }
    });
</script>

<?php $_template = new Smarty_Internal_Template("msgpop.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("chat/chat.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</body>
</html>

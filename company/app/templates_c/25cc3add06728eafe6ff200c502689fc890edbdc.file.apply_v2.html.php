<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:30:39
         compiled from "app\templates\resume/apply/apply_v2.html" */ ?>
<?php /*%%SmartyHeaderCode:253805e70364f3429d7-09262954%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '25cc3add06728eafe6ff200c502689fc890edbdc' => 
    array (
      0 => 'app\\templates\\resume/apply/apply_v2.html',
      1 => 1584332294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '253805e70364f3429d7-09262954',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_modifier_replace')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.replace.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="Keywords" content="" />
<meta name="Description" content="" />
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
<script type="text/javascript">
    window.CONFIG = {
        HOST: '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
',
        COMBOPATH: '/js/v2/'
    }
</script>

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
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_validate.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.form.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_dropdownlist.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_tooltip.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_autocomplete.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.email.tip.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.metadata.js'),$_smarty_tpl);?>
"></script><!--指向改变class-->

    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'hbjs.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.min.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'util.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'class.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'shape.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'event.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'aspect.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'attribute.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'cookie.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'global.js'),$_smarty_tpl);?>
"></script>

<script type="text/javascript">
    hbjs.loadJS('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/js/v2/cqjob/common.js');
</script>

</head>
<style>
<?php if (isset($_smarty_tpl->getVariable('reversion_rate',null,true,false)->value)){?>
.rMentRt{ position:relative;}
.subMetx .com {width: 140px; float: left;}
.subMetx .job {width: 180px;}
<?php }?>

.rMentLit .rMentLx b.gen-binding {background: #ff9b00;border:0px;color:#feffff;border-radius: 2px;line-height: 18px;font-weight: normal;}
</style>
<body id="body">
<?php $_template = new Smarty_Internal_Template('new_header.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par',"全职招聘");$_template->assign('cur','简历管理'); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<div class="resumentNbg">

    <!---20151208 微信二维码 start -->
    <style>
        .content{position: relative}
        .ewmBox{display: none;position: absolute;right:-180px;top:0px;width:160px;background: #fff;border:1px solid #dedede;text-align: center;padding:30px 0;font-size:16px;color:#333;font-family:"微软雅黑"}
        .ewmBox img{border:1px solid #e9e9e9;margin-bottom: 5px;width: 118px;height: 118px;}
        .ewmBox a{display: inline-block;width:24px;height:24px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/weixin/close2.jpg) no-repeat;position: absolute;top:0px;right:0px}
        .ewmBox a:hover{background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/weixin/close2_hover.jpg) no-repeat}
        
        .resume_new{border:1px solid #f0f0f0;background: #fafafa;padding:10px;font-size:12px;color:#999;margin-bottom: 25px;font-family: '宋体'}
		.resume_new .gray6{color:#444}
		.resume_new font{color:#444;font-size:18px;margin-right: 10px;vertical-align: -2px;font-family: '微软雅黑'}
		.resume_new .num{margin-bottom:5px}
		.resume_new .num .gray6{margin-left: 20px}
		.resume_new .num u{color:#dbdbdb;text-decoration: none;margin:0 20px}
		.resume_new .item,.subMetz .item{position: relative;padding-left: 0px;z-index: 2}
		.resume_new .ques,.subMetz .que{display: inline-block;width:16px;height:16px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/ques.jpg) no-repeat;position: absolute;left:0;top:6px;z-index: 3}
		.subMetz .que{left:132px;}
		.resume_new .orang{font-size: 14px;border:1px solid #f6e5a6;color:#604e29;background: #fffadd;position:absolute;left:0px;top:28px;padding:3px 10px;z-index: 2;display: none}
        .wh-orang{width: 200px; font-size: 14px;border:1px solid #f6e5a6;color:#604e29!important;background: #fffadd;position:absolute;left:-59px;top:28px;padding:3px 10px;z-index: 2;display: none;}
        .subMetz .item{display: inline-block;  right: -63px;  top: -9px;padding-left: 0px;}
        .subMetz .item.hover .wh-orang{display: block;}
        .subMetz .item.hover .que{height:23px}
        .resume_new .hover .ques{height:23px}
		.resume_new .hover .orang{display: block}
		.filtration p span{color:red}
		.filtration{background: #fffced;padding:15px;width:auto}
		#closefiltration{font-size:18px;color:#d5cfb4;position:absolute;right: 10px;top:5px}
		.subMetx{padding:10px 0}
		.subMetx .drop{border:1px solid #d8d8d8}
		.subMetx .drop .dropSeld{background: #fdfdfd}
		.subMetx .reload{width:80px;height:30px;background: #00c0c7;display: inline-block;float:right;font-size:14px;color:#fcfcfc;text-align: center;line-height: 30px;border-radius: 5px}
    	.subMetx .reload i{display: inline-block;width:17px;height:17px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/reload.jpg);vertical-align:-3px;margin-right: 10px}
    	#timeLog{font-size: 14px;color:#2b6fad;font-family: '微软雅黑'}
    	.yjhj-status{background: #f6fcff;border:1px solid #f0f0f0}
    	.yjhj-status .yjhj-tit{background: #f2f9ff;border-bottom:1px solid #f0f0f0;height:44px;padding:0 10px;color:#444;font-size:14px;line-height: 44px}
    	.yjhj-status .yjhj-tit span{color:red}
    	.yjhj-status .yjhj-tit .right{float:right}
    	.yjhj-status .yjhj-tit a{color:#2b6fad;font-weight: normal}
    	.yjhj-status .yjhj-tit i{display: inline-block;width:20px;height:12px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/arr_top.jpg);margin-left: 20px;cursor: pointer}
    	.yjhj-status .yjhj-tit i.hide{background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/arr_top1.jpg)}
    	.yjhj-status .yjhj-cont{padding:10px;color:#666;line-height: 28px}
    	.yjhj-status .yjhj-cont a{display: inline-block;margin-left: 10px}
    	.subMetz{padding:15px 0;margin:0}
    	.subMetz button{color:#FFF;height:28px;outline: none;  border: none;  border-radius: 4px;line-height:18px;display: inline-block;font-size:14px;background: #ff5400;padding:0 10px;margin-right: 20px;}
        .subMetz button:hover{background: #F54A0E;}
    	.subMetz p{text-align: right;color:#999;font-size:12px;margin-top: 5px;font-family: '宋体'}
    	.rMenTit{margin-bottom: 15px}
    	.rMenTit span{font-size: 14px;color:#444;height:14px;line-height: 14px}
    	.rMenTit span b{color:#00c0c7}
    	.rMentLitBg .hue1{color:#ff003c}/*未读*/
    	.rMentLitBg .hue2{color:#ffa300}/*待定*/
    	.rMentLitBg .hue3{color:#f35a00}/*已读*/
		.rMentLitBg .hue3{padding:4px 5px;margin:12px 0 0 0;}/*已读 未回复*/
        .rMentLv .rMentLink{ margin-bottom:30px;}
		.schoolVideoNet{background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/fire_03.png) no-repeat;background-size:  100% 100%;display: inline-block;color: #FFFFFF;font-size: 12px;line-height: 20px;font-weight: bold;padding: 0 10px 0 20px;text-align: center;vertical-align: middle;margin-left: 9px;}
		.sendTo_img{ display:none;position:absolute; top:10px; left:110px;width:150px; background:#fff; overflow:hidden; border:1px solid #ddd; text-align:center; color:#333;}
        .sendTo_img span, .sendTo_img img, .sendTo_img b{ display:block; margin:0 auto; line-height:20px;font-size:12px; font-weight:normal;}
        .sendTo_img span{padding-top:10px; color:#f35a00;}
		.lookAndDownload{left:120px;bottom:18px;cursor:pointer;height: 20px;font-size: 12px;line-height: 20px;color: #999999;}
		.lookAndDownload img,.lookAndDownload em{margin-right: 5px;vertical-align: middle;}
        #more_search_all{position: relative;z-index: 1;}
        .yqQual{height: 30px;background: #66bce4;font-size: 14px;color: #fff;line-height: 30px;border-radius: 2px;margin-right: 10px;width: 121px;display: inline-block;}
        .yqQual:hover{ background: #31a2d6; color: #fff;}
		.subMetx_bg{ background: #fafafa; border: 1px solid #f1f1f1; padding: 10px 10px 0 10px; margin: 0 0 20px 0;}
    </style>
    <div class="ewmBox" id="ewmBox">
        <a href="" class="close"></a>
        <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/acompany_joblist_ewm.jpg" id="jewm" />
        <p style="font-size: 14px;"> <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/weixin.jpg" style="width: auto; height: auto; border: 0px none; margin: 0px; vertical-align: middle;">关注汇博招聘<br>随时随地筛选简历</p>
    </div>
    <div class="ewmBox" id="ewmBox1" style="top:250px;display: block;padding-bottom: 10px">
        <a href="" class="close"></a>
        <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/acompany_joblist_ewm.jpg" />
        <p style="font-size: 14px;color:#4e74d9">汇博企业版 APP<br><span style="font-size: 16px">随时处理简历<br>提高招聘效率</span></p>
    </div>
    <!---20151208 微信二维码 end -->

    <?php $_template = new Smarty_Internal_Template('resume/apply/nav.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par',"收到的简历");$_template->assign('cur',"待处理简历"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
    <div class="rMentRt">
        <div class="filtration" <?php if ($_smarty_tpl->getVariable('closefiltration_cookie_value')->value){?>style="display:none;"<?php }?>>
            <h2>温馨提示：</h2>
            <?php if ($_smarty_tpl->getVariable('member')->value=='NotMember'){?>
            <p>1. <span>未进行“已通知面试”或“发送面试邀请”或“不合适”</span>操作的简历中，已承诺回复的简历将在5个工作日内自动回<br/>&nbsp;&nbsp;&nbsp;&nbsp;绝，其他简历将在7个工作日内自动回绝。</p>
            <?php }?>
            <?php if ($_smarty_tpl->getVariable('member')->value=='NotMemberTypeCalling'||$_smarty_tpl->getVariable('member')->value=='NotMemberTypeArea'){?>
            <p>1. 收到的简历记得及时查看哦，若发布的职位<span>10个工作日内都未进行查看</span>，则我们会为你关闭掉该职位哦</p>
            <?php }?>
            <p><?php if ($_smarty_tpl->getVariable('member')->value=='NotMember'||$_smarty_tpl->getVariable('member')->value=='NotMemberTypeCalling'||$_smarty_tpl->getVariable('member')->value=='NotMemberTypeArea'){?>2. <?php }?>全部简历我们会为您保存3个月，若想长期查看使用，记得<span>导出保存到本机上</span></p>
            <a href="javascript:;" id="closefiltration" class="md_remain_close">╳</a>
        </div>


        <div style=" position:relative" class="clearfix">
            <div class="resume_new" style="margin-top: 12px;">
                <p class="num" style="margin-bottom: 10px;"><span class="gray6" style="margin-left:0px;margin-right:20px;font-weight: bold;font-size: 14px;">近2周 <?php if ($_smarty_tpl->getVariable('job_station')->value!='所有职位'){?>职位：<?php }?><?php echo $_smarty_tpl->getVariable('job_station')->value;?>
</span>
                <?php if ($_smarty_tpl->getVariable('reversion_rate')->value['reply_rate']<60||$_smarty_tpl->getVariable('reversion_rate')->value['avg_time_hours']>72){?><a id="timeLog" class="md_reamin_repaly" href="javascript:;" style="float: right;">如何提升简历回复率？</a><?php }?>
                </p>
                <div class="item">
                    <span class="gray6">简历回复率：</span>
                    <font><em style="color: #2b6fad;font-size: 16px;font-weight: bold;<?php if ($_smarty_tpl->getVariable('reversion_rate')->value['reply_rate']<60){?><?php }?>"><?php echo $_smarty_tpl->getVariable('reversion_rate')->value['reply_rate'];?>
%</em></font>
					
					<span class="gray6" style="padding-left: 30px;">简历平均回复时长：</span>
					<font style="color: #2b6fad;font-size: 16px; font-weight: bold; margin-right: 0;"><?php echo $_smarty_tpl->getVariable('reversion_rate')->value['avg_time_str'];?>
</font>
					<span class="gray6" style="color: #999;">（<?php if (empty($_smarty_tpl->getVariable('job_id',null,true,false)->value)){?>企业<?php }else{ ?><?php }?>昨日排名：<?php echo $_smarty_tpl->getVariable('reversion_rate')->value['sort_no'];?>
位）</span>

                    <div id="js_tsAppBox" style='font-family: "Microsoft YaHei", "微软雅黑";background: #fff;padding: 10px;border: 1px solid #ccc;position: absolute;height: 100px;width:320px;left:300px;top:29px;#top:31px;display: none'>
                        <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/account/add_appewm.jpg" style="width: 100px;float: left;margin-right: 15px" />
                        <span style="font-size: 14px;color: #333;display: block;margin-bottom: 20px;padding-top: 5px">使用汇博企业APP，随时随地处理简历。快速提升简历回复率。</span>
                        <span style="font-size: 12px;color: #999">扫码安装汇博企业APP 支持安卓 IOS</span>
                    </div>
                    <p class="orang">近两周<?php echo $_smarty_tpl->getVariable('job_station')->value;?>
的简历回复率(最后一天18:00以后收到的简历不纳入回复率计算)</p>
                </div>
                <div class="item" style="z-index: 1;display: none;">
                    <a href="javascript:void(0);" class="ques"></a>
                    <span class="gray6">简历平均回复时长：</span>
                    <font><?php echo $_smarty_tpl->getVariable('reversion_rate')->value['avg_time_str'];?>
</font>
                    <span class="gray6">（<?php if (empty($_smarty_tpl->getVariable('job_id',null,true,false)->value)){?>企业<?php }else{ ?><?php }?>昨日排名：<?php echo $_smarty_tpl->getVariable('reversion_rate')->value['sort_no'];?>
位）</span>
                    <div id="js_hfAppBox" style='font-family: "Microsoft YaHei", "微软雅黑";background: #fff;padding: 10px;border: 1px solid #ccc;position: absolute;height: 100px;width:320px;left:350px;top:29px;#top:31px;display: none'>
                        <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/account/add_appewm.jpg" style="width: 100px;float: left;margin-right: 15px" />
                        <span style="font-size: 14px;color: #333;display: block;margin-bottom: 20px;padding-top: 5px">使用汇博企业APP，及时处理简历。缩短简历回复时长。</span>
                        <span style="font-size: 12px;color: #999">扫码安装汇博企业APP 支持安卓 IOS</span>
                    </div>
                    <p class="orang">近两周<?php echo $_smarty_tpl->getVariable('job_station')->value;?>
收到简历的平均回复时长</p>
                </div>
                <?php if ($_smarty_tpl->getVariable('reversion_rate')->value['reply_rate']<60&&$_smarty_tpl->getVariable('reversion_rate')->value['avg_time_hours']>72){?>
                <span style="color:#ff5400;">回复率过低，回复时长过高，可能影响简历投递量</span>
                <?php }elseif($_smarty_tpl->getVariable('reversion_rate')->value['reply_rate']>=60&&$_smarty_tpl->getVariable('reversion_rate')->value['avg_time_hours']>72){?>
                <span style="color:#ff5400;"> 回复时长过高，可能影响简历投递量</span>
                <?php }elseif($_smarty_tpl->getVariable('reversion_rate')->value['reply_rate']<60&&$_smarty_tpl->getVariable('reversion_rate')->value['avg_time_hours']<=72){?>
                <span style="color:#ff5400;">回复率过低，可能影响简历投递量</span>
                <?php }?>
            </div>
            <div class="mentRPop">
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/reversion/mentRate02.png" width="16" height="7" />
                <div>5月1日后，简历回复率将向求职者完全公开</div>
            </div>
            

            <div class="rMenTit"><span>共<b><?php echo (int)$_smarty_tpl->getVariable('totalSize')->value;?>
</b>份简历</span></div>
			
			<div class="subMetx_bg">
			    <div class="subMetx clearfix">
			        <div class="com" style="<?php if (!$_smarty_tpl->getVariable('is_hr')->value){?>display:none<?php }?>"><span id="tstDropCom" class="drop zindex" style="<?php if (!$_smarty_tpl->getVariable('is_hr')->value){?>display:none<?php }?>"></span></div>
			        <span style="padding-top:5px;">职位发布人：</span>
			        <div class="job" style="width: 103px"><span id="tstDropJobPeople" class="drop zindex md_pub_account"></span></div>
			        <span style="padding-top:5px;">招聘职位：</span>
			        <div class="job" style="width: 145px"><span id="tstDropJob" class="drop zindex md_injob"></span></div>
			        <label  style="padding-top:6px;display: block;float: left;"><input class="md_stopjob" type="checkbox" <?php if ($_smarty_tpl->getVariable('showStopJobApply')->value){?>checked="checked"<?php }?> id="showStopJob" style="display:inline-block; vertical-align:-1px;" />&nbsp;包含停招职位</label>
			        <span onclick="window.location.reload()" style="cursor: pointer" class="reload md_refresh"><i></i>刷新</span>
			    </div>
			
			    <div class="subMetz" style="padding-top: 5px;">
			    	<div class="clearfix">
			            <?php if ($_smarty_tpl->getVariable('child_status')->value!=10){?> <button style="float: right;cursor: pointer" id="refuse_last_apply" class="md_refuse_allresume" >剩余简历不合适</button> <?php }?>
			            <span>简历状态：</span>
			            <a class='child_status md_resume_nolimit <?php if (base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->getVariable('child_status')->value)){?>cut<?php }?>' href='javascript:;' child_status='' >不限</a>
			            <a class='child_status md_resume_noread <?php if ($_smarty_tpl->getVariable('child_status')->value==2){?>cut<?php }?>'  href='javascript:;' child_status='2' >未读</a>
			            <a class='child_status md_resume_hadread <?php if ($_smarty_tpl->getVariable('child_status')->value==1){?>cut<?php }?>' href='javascript:;' child_status='1' >已读（未回复）</a>
			            <a class='child_status md_resume_waitdeal <?php if ($_smarty_tpl->getVariable('child_status')->value==10){?>cut<?php }?>' href='javascript:;' child_status='10' >待定（未回复）</a>
			            <span class="md_resume_more" style="padding-top:5px;color:#3d84b8;cursor: pointer" onclick='$("#more_search_all").toggle();$("#moreshow").toggle();$("#morehide").toggle()'>更多筛选条件
			                <b class="hbFntWes dropIco" style="font-size: 14px;margin-left: 3px">
			                    <i id="moreshow">&#xf0d7;</i>
			                    <i id="morehide" style="display: none">&#xf0d8;</i>
			                </b>
			            </span>
			            <span class="item">
			                <i class="que"></i>
			                <em class="wh-orang">暂缓回复的简历“待定”，再选择“剩余简历不合适”（不会回绝已邀请、已通知和待定的简历）</em>
			            </span>
			        </div>
			        <!--<?php if ($_smarty_tpl->getVariable('child_status')->value!=10){?><p>（不会回绝已面试邀请、已通知、待定的简历）</p> <?php }?>-->
			    </div>
				
				<!--20170601-->
				<div id="more_search_all" style="display: none">
				    <!--<div style="margin-bottom: 5px"><span style="color: #333">简历筛选：</span></div>-->
				    <div class="subMetx clearfix" style="padding-top: 0;">
				        <span style="padding-top:5px;">学历：</span>
				        <div class="job" style="width: 85px"><span id="tstEducation" class="drop zindex" style="z-index: 999;"></span></div>
				        <span style="padding: 5px">~</span>
				        <div class="job" style="width: 105px"><span id="tstEducation2" class="drop zindex" style="z-index: 999;"></span></div>
				        <span style="padding-top:5px;">工作年限：</span>
				        <div class="job" style="width: 55px"><span id="tstYears" class="drop zindex" style="z-index: 999;"></span></div>
				        <span style="padding: 5px">~</span>
				        <div class="job" style="width: 75px"><span id="tstYears2" class="drop zindex" style="z-index: 999;"></span></div>
				        <span style="padding-top:5px;">性别：</span>
				        <div class="job" style="width: 75px"><span id="tstSex" class="drop zindex" style="z-index: 999;"></span></div>
				        <!--<span style="padding-top:5px;color:#3d84b8;cursor: pointer" onclick='$("#more_search").toggle();$("#moreshow").toggle();$("#morehide").toggle()'>高级筛选<b class="hbFntWes dropIco" style="font-size: 14px;margin-left: 3px"><i id="moreshow">&#xf0d7;</i><i id="morehide" style="display: none">&#xf0d8;</i></b></span>-->
				        <span onclick="" style="cursor: pointer" class="reload" id="screening">查询</span>
				    </div>
				    <div id="more_search" style="padding-bottom: 10px;">
				        <!--<p style="border-bottom: 1px dashed #d8d8d8;position: relative;z-index: 1;margin-top: 5px;margin-bottom: 5px">-->
				            <!--<span style="position: absolute;left:50%;top:-10px;background: #fcfcfc;color: #ccc;padding: 0 10px;margin-left: -20px;font-size: 12px">高级筛选</span>-->
				            <!--<i></i>-->
				        <!--</p>-->
				        <div class="subMetx clearfix" style="padding-top: 5px;">
				            <span style="padding-top:5px;">年龄：</span>
				            <input class="rMenText textGray" name="amin" id="amin" value="<?php if (!empty($_smarty_tpl->getVariable('amin',null,true,false)->value)){?><?php echo $_smarty_tpl->getVariable('amin')->value;?>
<?php }?>"  type="text" style="width: 85px;border:1px solid #d8d8d8;color:#333">
				            <span style="padding: 5px">~</span>
				            <input class="rMenText textGray" name="amax" id="amax" value="<?php if (!empty($_smarty_tpl->getVariable('amax',null,true,false)->value)){?><?php echo $_smarty_tpl->getVariable('amax')->value;?>
<?php }?>" type="text" style="width: 85px;border:1px solid #d8d8d8;color:#333">
				            <span style="padding-top:5px;padding-left:20px">到岗时间：</span>
				            <div class="job" style="width: 140px"><span id="tstDutytime" class="drop zindex" style="z-index: 999;"></span></div>
				            
				        </div>
				    </div>
				</div>
				<!--/20170601-->
				<div class="clear"></div>
			</div>
				
            <div class="rMentBtn">
            	<label>
                	<input name="c" class="resuemSelectAll" type="checkbox" value="" /><span>全选</span>
                </label>
                <p>
                	<!--<a href="javascript:;" class="setRead">标记为待定</a>-->
                    <a href="javascript:;" class="savePc md_save_computer">保存到电脑</a>
                    <a href="javascript:;" class="sendEmail md_to_mail">转发到邮箱</a>
                    <?php if (($_smarty_tpl->getVariable('memberinfo')->value=="member")){?><a href="javascript:;"  class="btnOpInvite md_invite">邀请面试</a><?php }?>
                    <a href="javascript:;" class='refuseMore md_not_fit'>不合适</a>
                </p>                
                <div class="rMentSech">
                	<!--<form id="search_applay_s" action="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/" method="post">-->
	                    <input type="text"  id='keyword' name="t" class="rMenText"  value="<?php if (!empty($_smarty_tpl->getVariable('keyword',null,true,false)->value)){?><?php echo $_smarty_tpl->getVariable('keyword')->value;?>
<?php }?>" /> 
	                    <input type="button" id='onSubmit' name="b" value="" class="rMentBtnx" />
                   <!-- </form>-->
                </div>
            </div>
            <div class="rMentLitBg">
                <?php if ($_smarty_tpl->getVariable('totalSize')->value>0){?>
                <?php if ($_smarty_tpl->getVariable('auto_filter_count')->value>0){?>
                <div class="two_alert">
                	<span style="float: right;">
                        <?php if ($_smarty_tpl->getVariable('showPromise')->value!='1'){?>
                        <a href="javascript:;" class="showic" id="showic">显示记录</a>
                        <?php }else{ ?>
                        <a href="javascript:;" class="showic" id="hideic">隐藏记录</a>
                        <?php }?>
                        <a id="clearRecord" data-jid="<?php echo $_smarty_tpl->getVariable('job_id')->value;?>
" href="javascript:;" class="delete">清除记录</a>
                    </span>
                    <p class=""><i class="warning"></i>您有<?php echo $_smarty_tpl->getVariable('auto_filter_count')->value;?>
份简历未按承诺及时回复求职者，已经被自动回绝</p>
                </div>
                <?php }?>
                <?php  $_smarty_tpl->tpl_vars['apply'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('applylist')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['apply']->key => $_smarty_tpl->tpl_vars['apply']->value){
?>
                <?php if ($_smarty_tpl->tpl_vars['apply']->value['re_status']=='5'){?>
                <div class="rMentLit" >
                    <div class="rMentLx">
                        <label>
                            <b title="<?php echo $_smarty_tpl->tpl_vars['apply']->value['station'];?>
"><?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['station'])){?>应聘职位：<?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['apply']->value['station'],18,'utf-8','','…');?>
<?php }?></b>
                            <?php if (($_smarty_tpl->tpl_vars['apply']->value['generation_binding'])){?>
                            <span>--<?php echo $_smarty_tpl->getVariable('accounts')->value[$_smarty_tpl->tpl_vars['apply']->value['company_id']]['company_name_display'];?>
</span>
                            <b class="gen-binding">代招</b>
                            <?php }?>
                            <span data-create-time="<?php echo $_smarty_tpl->tpl_vars['apply']->value['create_time'];?>
"><?php echo date("Y-m-d H:i",strtotime($_smarty_tpl->tpl_vars['apply']->value['create_time']));?>
</span>
                            <?php if (!$_smarty_tpl->tpl_vars['apply']->value['is_new']){?><span>近7天浏览量：<?php echo $_smarty_tpl->tpl_vars['apply']->value['visit_num'];?>
次   下载量：<?php echo $_smarty_tpl->tpl_vars['apply']->value['get_linkway_num'];?>
</span><?php }?>
						</label>
                        <em class="hue2">不合适（到期未回）</em>
                    </div>
                    <div class="rMentLv">
                        <div style="color:#ed6802;font-size: 12px;margin-top: -10px;margin-bottom: 10px">
                            <label>简历过期未回复，无法查看详细信息</label>
                        </div>
                        <a href="javascript:;" class="rMentLink" style="cursor: default;">
                            <div class="mImgBg">
                                <p>
                                    <img class="mImg" src="<?php if ($_smarty_tpl->tpl_vars['apply']->value['small_photo']){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['small_photo'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/headportrait.png<?php }?>"  />
                                </p>
                            </div>
                            <div>
                                <p class="mTit1">
                                    <b>
                                        <?php if ($_smarty_tpl->tpl_vars['apply']->value['sex']=='男'){?>
                                        <?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['apply']->value['user_name'],1,'utf-8','','先生');?>

                                        <?php }else{ ?>
                                        <?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['apply']->value['user_name'],1,'utf-8','','女士');?>

                                        <?php }?>
                                    </b>
                                    <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['sex'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['sex'];?>
/<?php }?>
                                    <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['start_work'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['start_work'];?>
/<?php }?>
                                    <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['age'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['age'];?>
/<?php }?>
                                    <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['cur_area'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['cur_area'];?>
<?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['apply']->value['remark']&&$_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>
                                    <i class='remark_contr'></i><span class='remark_show'><?php echo $_smarty_tpl->tpl_vars['apply']->value['remark'];?>
<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/hbtip2.png" width="5" height="22"></span><?php }?>
                                </p>
                                <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['apply']->value['school'];?>
<?php $_tmp1=ob_get_clean();?><?php if (!empty($_tmp1)){?><p class="mTit3"><?php echo $_smarty_tpl->tpl_vars['apply']->value['school'];?>
<?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['major_desc'])){?><span>|</span><?php echo $_smarty_tpl->tpl_vars['apply']->value['major_desc'];?>
<?php }?><span>|</span><?php echo $_smarty_tpl->tpl_vars['apply']->value['school_degree'];?>
</p><?php }?>
                                <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['worklist'])){?>
                                <p class="mTit2"><b><?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['station'];?>
</b><span>|</span><?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['company_name'];?>
<span>|</span><?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['start_time'];?>
-<?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['end_time'];?>
</p>
                                <?php }?>
                            </div>
                        </a>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php }else{ ?>
                <div class="rMentLit" id="row<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
">
					
					<div class="resumeKuaimiTips">
						<em>快米</em>
						<p>
							<span><b>快米工作</b>汇博旗下专注蓝领招聘平台</span>
						</p>
						<span class="kuaimiTipx">汇博上的<i>普工、服务员</i>等蓝领职位将同步展示到快米平台</span>
					</div>
                    <div class="rMentLx <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==5||$_smarty_tpl->tpl_vars['apply']->value['status']==6){?>rMentLxgray<?php }?>">
                        <label>
                            <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>
                            <input name="chkapply" id="chkapply<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
" type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['apply']->value['user_name'];?>
" data-resumeid="<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
" />
                            <?php }?>
                            <b title="<?php echo $_smarty_tpl->tpl_vars['apply']->value['station'];?>
">
                            <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['station'])){?>应聘职位：<?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['apply']->value['station'],15,'utf-8','','…');?>
<?php }?>
                            </b>
                            <?php if (($_smarty_tpl->tpl_vars['apply']->value['generation_binding'])){?>
                            <span>--<?php echo $_smarty_tpl->getVariable('accounts')->value[$_smarty_tpl->tpl_vars['apply']->value['company_id']]['company_name_display'];?>
</span>
                            <b class="gen-binding">代招</b>
                            <?php }?>
                            <span><?php echo date("Y-m-d H:i",strtotime($_smarty_tpl->tpl_vars['apply']->value['create_time']));?>
</span>
                        </label>
                        <em class="<?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==5||$_smarty_tpl->tpl_vars['apply']->value['status']==6){?>hue4<?php }elseif($_smarty_tpl->tpl_vars['apply']->value['status']==2){?>hue1<?php }elseif($_smarty_tpl->tpl_vars['apply']->value['status']==4){?>hue3<?php }elseif($_smarty_tpl->tpl_vars['apply']->value['status']==10){?>hue2<?php }?>">
                            <?php echo $_smarty_tpl->tpl_vars['apply']->value['statusName'];?>

                        </em>
                       
                        <?php if ($_smarty_tpl->tpl_vars['apply']->value['is_kuaimi']){?>
                        <em class="resumeTipsKm"><i></i>该简历来自快米工作</em>
                        <?php }?>
                        <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['mobile_phone'])){?><p><i></i><?php echo $_smarty_tpl->tpl_vars['apply']->value['mobile_phone'];?>
</p><?php }?>
                    </div>
                    <div class="rMentLv <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==5||$_smarty_tpl->tpl_vars['apply']->value['status']==6){?>rMentLvgray<?php }?>" data-applyid ="<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['apply']->value['user_name'];?>
" data-resumeid ="<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
" data-personid="<?php echo $_smarty_tpl->tpl_vars['apply']->value['person_id'];?>
" data-jobid="<?php echo $_smarty_tpl->tpl_vars['apply']->value['job_id'];?>
">
                        <?php if ($_smarty_tpl->tpl_vars['apply']->value['promiseSoonStop']=='1'){?>
                        <div style="color:#ed6802;font-size: 12px;margin-top: -10px;margin-bottom: 10px">
                            <label>该简历即将超过承诺回复期，请尽快回复。</label>
                        </div>
                        <?php }elseif(strtotime($_smarty_tpl->tpl_vars['apply']->value['remind_time'])>strtotime($_smarty_tpl->tpl_vars['apply']->value['create_time'])){?>
                        <div style="color:#ed6802;font-size: 12px;margin-top: -10px;margin-bottom: 10px;width:100%;">
                            <label>求职者提醒您：请尽快回复他的简历。</label>
                        </div>
                        <?php }?>
                        <a <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>href="javascript:;" <?php }else{ ?>href="javascript:;"<?php }?> class="rMentLink <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==2){?>changeStatus<?php }?>">
                            <div class="mImgBg">
                                <p>
                                    <img <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?> onclick="window.open('<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-resumeid-<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
-src-apply-applyid-<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
')" <?php }?> class="mImg" src="<?php if ($_smarty_tpl->tpl_vars['apply']->value['small_photo']){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['small_photo'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/headportrait.png<?php }?>"  />
                                </p>
                            </div>
                        </a>
						<?php if (!$_smarty_tpl->tpl_vars['apply']->value['is_new']){?>
						<div class="lookAndDownload" style="position: absolute;<?php if ($_smarty_tpl->tpl_vars['apply']->value["is_active"]){?>left: 152px;<?php }?>">
							<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/look_icon.png">
							<em><?php echo $_smarty_tpl->tpl_vars['apply']->value['visit_num'];?>
</em>
							<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/download_icon.png">
							<em><?php echo $_smarty_tpl->tpl_vars['apply']->value['get_linkway_num'];?>
</em>
							<span class="wh-orang " style="left: -5px;top: 22px;display: none;z-index: 100;">近7天简历被浏览量和被下载量</span>
						</div>
						<?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>
                        <div class="sendTo_img md_change_send"><span>把简历转发给职位负责人</span><img src="" data-img-source="<?php echo smarty_function_get_url(array('rule'=>'/apply/SendToWorkMatePng/'),$_smarty_tpl);?>
?src=apply&src_id=<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
&resume_id=<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
"/><b>用汇博企业APP<br />扫码转发简历</b></div>
                        <?php }?>
                       
                        <div>
                                <p class="mTit1">
									 <a <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>href="<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-resumeid-<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
-src-apply-applyid-<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
" target="_blank"<?php }else{ ?>href="javascript:;"<?php }?> class="rMentLink md_see_resume <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==2){?>changeStatus<?php }?>">
									<b><?php echo $_smarty_tpl->tpl_vars['apply']->value['user_name'];?>
</b>
									</a>
                                    <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['sex'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['sex'];?>
/<?php }?>
                                    <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['start_work'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['start_work'];?>
/<?php }?>
                                    <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['age'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['age'];?>
/<?php }?>
                                    <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['cur_area'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['cur_area'];?>
<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['apply']->value['is_shuangxuan_relate']){?><em class="schoolVideoNet">校园视频招聘</em><?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['apply']->value['remark']&&$_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>
                                    <i class='remark_contr'></i><span class='remark_show'><?php echo $_smarty_tpl->tpl_vars['apply']->value['remark'];?>
<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/hbtip2.png" width="5" height="22"></span>
									<?php }?>
									<label class="chatOneChat md_chat <?php if (!$_smarty_tpl->tpl_vars['apply']->value['chat_status']){?>notOffenUse<?php }?>" data-job-effect="<?php if ($_smarty_tpl->tpl_vars['apply']->value['is_job_effect']){?>1<?php }else{ ?>0<?php }?>" data-resume-id="<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
" data-job-id="<?php echo $_smarty_tpl->tpl_vars['apply']->value['job_id'];?>
"  data-need-download="0" data-notice-status="<?php echo $_smarty_tpl->tpl_vars['apply']->value['chat_status'];?>
" data-apply-id="<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
">立即沟通</label>
									<?php if ($_smarty_tpl->tpl_vars['apply']->value["is_active"]){?>
										<em title="该求职者聊天活跃"  class="chatHuoyue">活跃</em>
									<?php }?>
                                </p>
								<a <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>href="<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-resumeid-<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
-src-apply-applyid-<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
" target="_blank"<?php }else{ ?>href="javascript:;"<?php }?> class="rMentLink md_see_resume <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==2){?>changeStatus<?php }?>">
                                <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['apply']->value['school'];?>
<?php $_tmp2=ob_get_clean();?><?php if (!empty($_tmp2)){?>
                                <p class="mTit3">
                                    <?php echo $_smarty_tpl->tpl_vars['apply']->value['school'];?>
<?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['major_desc'])){?>
                                    <span>|</span>
                                    <?php echo $_smarty_tpl->tpl_vars['apply']->value['major_desc'];?>

                                    <?php }?>
                                    <span>|</span>
                                    <?php echo $_smarty_tpl->tpl_vars['apply']->value['school_degree'];?>

                                </p>
                                <?php }?>
                                <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['worklist'])){?>
                                <p class="mTit2" <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['apply']->value['school'];?>
<?php $_tmp3=ob_get_clean();?><?php if (empty($_tmp3)){?>style="margin-top: 8px;"<?php }?> >
                                    <b><?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['station'];?>
</b>
                                    <span>|</span><?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['company_name'];?>

                                    <span>|</span><?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['start_time'];?>
-<?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['end_time'];?>

                                </p>
                                <?php }?>
								</a>
                            </div>
                        <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==5||$_smarty_tpl->tpl_vars['apply']->value['status']==6){?>
                        <div class="rMentLinkv" style="bottom:inherit;top:74px">
                            <a href="javascript:;">&nbsp;</a>
                            <a href="javascript:;" class="mTit4 deleteApply">删除</a>
                            <a href="javascript:;">&nbsp;</a>
                        </div>
                        <?php }else{ ?>
                        <div class="rMentLinkv" style="bottom:inherit;top:74px">

                            <a style="<?php if ($_smarty_tpl->tpl_vars['apply']->value['need_contact']){?>display:none<?php }?>" href="javascript:;" class=" inviteResume md_r_send_invit" >发面试邀请</a>
                            <a style="<?php if (!$_smarty_tpl->tpl_vars['apply']->value['need_contact']){?>display:none<?php }?>" href="javascript:;" class=" getLinkWay">获取联系方式</a>
                            <a style="<?php if ($_smarty_tpl->tpl_vars['apply']->value['need_contact']){?>display:none<?php }?>" href="javascript:;" class="mTit4 hasInvite md_r_send_hadtell">已通知</a>
                            <a href="javascript:;" class="refuseSingle md_r_send_notfit">不合适</a>
                            <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==4||$_smarty_tpl->tpl_vars['apply']->value['status']==2){?>
                            <a href="javascript:;" class="mark_wait_deal md_r_send_waitdeal" data-id="<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
" style="margin-top: 14px">待定</a>
                            <?php }?>
                        </div>
                        <?php }?>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php }?>
                <?php }} ?>
                <?php }else{ ?>
                <div class="noData">
                    <p>未找到相关简历，请更换筛选条件、关键词再试！</p>
                </div>
                <?php }?>
                <div class="clear"></div>
            </div>
        </div>
        <?php if ($_smarty_tpl->getVariable('totalSize')->value>0){?>       
        <div class="rMentBtn">
        	<label>
                <input name="c" class="resuemSelectAll" type="checkbox" value="" /><span>全选</span>
            </label>
            <p>
                <!--<a href="javascript:;" class="setRead">标记为待定</a>-->
                <a href="javascript:;" class="savePc md_save_computer">保存到电脑</a>
                <a href="javascript:;" class="sendEmail md_to_mail">转发到邮箱</a>
                <?php if (($_smarty_tpl->getVariable('memberinfo')->value=="member")){?><a href="javascript:;"  class="btnOpInvite md_invite">邀请面试</a><?php }?>
                <a href="javascript:;" class="refuseMore md_not_fit">不合适</a>
            </p>
        </div>
        <?php }?>
        <?php echo $_smarty_tpl->getVariable('pager')->value;?>

    </div>
    <div class="clear"></div>
    <?php if ($_smarty_tpl->getVariable('set_show_person_url')->value!=''){?>
    <script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('set_show_person_url')->value;?>
"></script>
    <?php }?>
</div>
<!--弹窗职位不匹配提示开始-->
<div class="popReN" style='display:none'>
    <span>职位不匹配的简历现在都在这里了哦</span>
    <a href="javascript:;" id='notComplain'></a>
</div>

<!--弹窗职位不匹配提示结束-->

<!--2019.6.3更新简历完善度浮动图标-->
<?php if ($_smarty_tpl->getVariable('is_question')->value==1){?>
<style>
	.resume_complete{
		position: fixed;
	    margin-left: 516px;
	    left: 50%;
	    bottom: 210px;
	}
</style>
<a href="javascript:void(0);" class="resume_complete"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/resume_complete.png"/></a>
<?php }?>
<?php $_template = new Smarty_Internal_Template("msgpop.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<script>
$(".sendToWorkmate").on("click",function(){
    if($(this).parent().next('.sendTo_img').is(':visible')){
        $('.sendTo_img').hide();
    }else{
        $('.sendTo_img').hide();
        var find_img = $(this).parent().next('.sendTo_img').find("img").attr('data-img-source');
        $(this).parent().next('.sendTo_img').find("img").attr('src',find_img);
        $(this).parent().next('.sendTo_img').show();
    }
    
});
//快米工作
$('.resumeTipsKm').hover(function(){
	$(this).parent().prev('.resumeKuaimiTips').toggle();
});
$.setIndex("zindex");//为需要赋层级设置的元素设置class为zindex
$('.ques').mouseover(function(){
    $(this).parent().siblings('.item').each(function(i, obj){
        if($(obj).hasClass('hover')) $(obj).removeClass('hover');
    });
    if(!$(this).parent().hasClass('hover')) $(this).parent().addClass('hover');
});

$(".ques").mouseout(function(){
    $(this).parent().siblings('.item').each(function(i, obj){
        if($(obj).hasClass('hover')) $(obj).removeClass('hover');
    });
    if($(this).parent().hasClass('hover')) $(this).parent().removeClass('hover');
});
$('.que').mouseover(function(){
  if(!$(this).parent().hasClass('hover')) $(this).parent().addClass('hover');
});

$(".que").mouseout(function(){
  if($(this).parent().hasClass('hover')) $(this).parent().removeClass('hover');
});
$('.lookAndDownload').hover(function(){
	$(this).children('span').css('display','block');
},function(){
	$(this).children('span').css('display','none');
})
$('#tstDropJob').droplist({
    defaultTitle : '全部职位',
    style : 'width:250px;',
    noSelectClass : 'gray',
    inputWidth : 120,
    width : 128,
    hddName : 'job_id',
    items : <?php echo $_smarty_tpl->getVariable('jobs')->value;?>
,
    selectValue : '<?php echo $_smarty_tpl->getVariable('job_id')->value;?>
',
    maxScroll : 10,
    onSelect : function(i, name) {
    //选中后的事件
        var child_status = $(".subMetz .cut").attr("child_status");
        var job_id = $("#job_id").val();
        var account_id = $("#account_id").val();
        var keyword = "";
        var son_account_id = $("#son_account_id").val();

        var marriage_id = $("#marriage_id").val();
        var dutytime_id = $("#dutytime_id").val();
        var sex_id = $("#sex_id").val();
        var years_id = $("#years_id").val();
        var years_id2 = $("#years_id2").val();
        var education_id = $("#education_id").val();
        var education_id2 = $("#education_id2").val();
        var amin   = $("#amin").val();
        var amax   = $("#amax").val();

        apply.submit(job_id, child_status, keyword, account_id, son_account_id, marriage_id, dutytime_id, sex_id, years_id, years_id2, education_id, education_id2,amin,amax);
    }
});

$('#tstDropJobPeople').droplist({
    defaultTitle : '全部',
    style : 'width:80px;',
    noSelectClass : 'gray',
    inputWidth : 80,
    width : 128,
    hddName : 'son_account_id',
    items : <?php echo $_smarty_tpl->getVariable('job_people')->value;?>
,
    selectValue : '<?php echo $_smarty_tpl->getVariable('son_account_id')->value;?>
',
    maxScroll : 10,
    onSelect : function(i, name) {
        //选中后的事件
        var child_status = $(".subMetz .cut").attr("child_status");
        var job_id       = '';
        var son_account_id   = $("#son_account_id").val();
        var account_id   = $("#account_id").val();
        var keyword      = "";


        var marriage_id   = $("#marriage_id").val();
        var dutytime_id   = $("#dutytime_id").val();
        var sex_id   = $("#sex_id").val();
        var years_id   = $("#years_id").val();
        var years_id2   = $("#years_id2").val();
        var education_id   = $("#education_id").val();
        var education_id2   = $("#education_id2").val();
        var amin   = $("#amin").val();
        var amax   = $("#amax").val();

        apply.submit(job_id, child_status, keyword, account_id,son_account_id,marriage_id,dutytime_id,sex_id,years_id,years_id2,education_id,education_id2,amin,amax);

    }
});


$('#tstDropCom').droplist({
    defaultTitle : '所有公司',
    style : 'width:128px;',
    noSelectClass : 'gray',
    inputWidth : 120,
    width : 128,
    hddName : 'account_id',
    items : <?php echo $_smarty_tpl->getVariable('accounts_json')->value;?>
,
    selectValue : '<?php echo $_smarty_tpl->getVariable('account_id')->value;?>
',
    maxScroll : 10,
    onSelect : function(i, name) {
        var child_status = $(".subMetz .cut").attr("child_status");
        var job_id       = "";
        var account_id   = $("#account_id").val();
        var keyword      = "";
        var son_account_id = "";
        var marriage_id   = $("#marriage_id").val();
        var dutytime_id   = $("#dutytime_id").val()?$("#dutytime_id").val():"";
        var sex_id   = $("#sex_id").val();
        var years_id   = $("#years_id").val();
        var years_id2   = $("#years_id2").val();
        var education_id   = $("#education_id").val();
        var education_id2   = $("#education_id2").val();
        var amin   = $("#amin").val();
        var amax   = $("#amax").val();

        apply.submit(job_id, child_status, keyword, account_id,son_account_id,marriage_id,dutytime_id,sex_id,years_id,years_id2,education_id,education_id2,amin,amax);

    }
});
//学历 20170601
$('#tstEducation').droplist({
    defaultTitle : '不限',
    style : 'width:82px;',
    noSelectClass : 'gray',
    inputWidth : 74,
    width : 50,
    hddName : 'education_id',
    items : <?php echo $_smarty_tpl->getVariable('degree_lists')->value;?>
,
    selectValue :<?php echo $_smarty_tpl->getVariable('education_id')->value;?>
,
    maxScroll : 10,
    onSelect : function(i, name) {
        var account_id   = $("#education_id").val();
        //apply.submit(job_id, child_status, keyword, account_id);
    }
});
//学历2 20170601
$('#tstEducation2').droplist({
    defaultTitle : '不限',
    style : 'width:82px;',
    noSelectClass : 'gray',
    inputWidth : 74,
    width : 50,
    hddName : 'education_id2',
    items : <?php echo $_smarty_tpl->getVariable('degree_lists')->value;?>
,
    selectValue :<?php echo $_smarty_tpl->getVariable('education_id2')->value;?>
,
    maxScroll : 10,
    onSelect : function(i, name) {
        var account_id   = $("#education_id2").val();
        //apply.submit(job_id, child_status, keyword, account_id);
    }
});
//年限 20170601
$('#tstYears').droplist({
    defaultTitle : '不限',
    style : 'width:50px;',
    noSelectClass : 'gray',
    inputWidth : 43,
    width : 50,
    hddName : 'years_id',
    items : <?php echo $_smarty_tpl->getVariable('common_workyear_list')->value;?>
,
    selectValue :<?php echo $_smarty_tpl->getVariable('years_id')->value;?>
,
    maxScroll : 15,
    onSelect : function(i, name) {
        var account_id   = $("#years_id").val();
        //apply.submit(job_id, child_status, keyword, account_id);
    }
});
//年限2 20170601
$('#tstYears2').droplist({
    defaultTitle : '不限',
    style : 'width:50px;',
    noSelectClass : 'gray',
    inputWidth : 43,
    width : 50,
    hddName : 'years_id2',
    items : <?php echo $_smarty_tpl->getVariable('common_workyear_list')->value;?>
,
    selectValue :<?php echo $_smarty_tpl->getVariable('years_id2')->value;?>
,
    maxScroll : 15,
    onSelect : function(i, name) {
        var account_id   = $("#years_id2").val();
        //apply.submit(job_id, child_status, keyword, account_id);
    }
});
//性别 20170601
$('#tstSex').droplist({
    defaultTitle : '不限',
    style : 'width:50px;',
    noSelectClass : 'gray',
    inputWidth : 43,
    width : 50,
    hddName : 'sex_id',
    items : <?php echo $_smarty_tpl->getVariable('common_sex_list')->value;?>
,
    selectValue :<?php echo $_smarty_tpl->getVariable('sex_id')->value;?>
,
    maxScroll : 15,
    onSelect : function(i, name) {
        var account_id   = $("#sex_id").val();
        //apply.submit(job_id, child_status, keyword, account_id);
    }
});
//到岗时间 20170601
$('#tstDutytime').droplist({
    defaultTitle : '不限',
    style : 'width:118px;',
    noSelectClass : 'gray',
    inputWidth : 110,
    width : 50,
    hddName : 'dutytime_id',
    items : <?php echo $_smarty_tpl->getVariable('common_accessiontime_list')->value;?>
,
    selectValue :<?php echo $_smarty_tpl->getVariable('dutytime_id')->value;?>
,
    maxScroll : 15,
    onSelect : function(i, name) {
        var account_id   = $("#dutytime_id").val();
        //apply.submit(job_id, child_status, keyword, account_id);
    }
});
//婚姻状态 20170601
$('#tstMarriage').droplist({
    defaultTitle : '全部',
    style : 'width:50px;',
    noSelectClass : 'gray',
    inputWidth : 43,
    width : 50,
    hddName : 'marriage_id',
    items : <?php echo $_smarty_tpl->getVariable('common_marriage_list')->value;?>
,
    selectValue :<?php echo $_smarty_tpl->getVariable('marriage_id')->value;?>
,
    maxScroll : 15,
    onSelect : function(i, name) {
        var marriage_id   = $("#marriage_id").val();

        //apply.submit(job_id, child_status, keyword, account_id);
    }
});
var apply = {
    init : function() {
        $("#notComplain").click(function() {
            apply.setNotComplainCookie();
        });

        //高级筛选
        $("#screening").click(function(){
            var child_status = $(".subMetz .cut").attr("child_status");
            var job_id       = $("#job_id").val();
            var account_id   = $("#account_id").val();
            var keyword      = "";
            var son_account_id   = $("#son_account_id").val();

            var marriage_id   = $("#marriage_id").val();
            var dutytime_id   = $("#dutytime_id").val();
            var sex_id   = $("#sex_id").val();
            var years_id   = $("#years_id").val();
            var years_id2   = $("#years_id2").val();
            var education_id   = $("#education_id").val();
            var education_id2   = $("#education_id2").val();
            var amin   = $("#amin").val();
            var amax   = $("#amax").val();

            if(isNaN(amin) || isNaN(amax)){
                alert("年龄请输入正整数");
                return;
            }

            apply.submit(job_id, child_status, keyword, account_id,son_account_id,marriage_id,dutytime_id,sex_id,years_id,years_id2,education_id,education_id2,amin,amax);
        });


        // 水印 
        var child_status = $(".subMetz .cut").attr("child_status");

        $("#showic").click(function(e){
            cookieutility.set('showPromiseStop', 1, "", "/");
            window.location.href = window.location.href;       
        });

        $("#hideic").click(function (e) {
            window.location.href = window.location.href;       
        });

       $("#clearRecord").click(function (e) {
            cookieutility.set('hidePromiseStop', null, "", "/");
            var job_id = $(this).attr('data-jid');
            if (job_id != "") {
                var data1 = {'job_id' : job_id};
            } else {
                var data1 = null;
            }

            $.getJSON("/apply/clearPromiseResume",data1,function(data){
                if (parseInt(data.status) > 0) {
                    $.anchor(data.msg,{icon:'success'});
                    setTimeout(function(){window.location.href = window.location.href;},1500);
                }
            })
        });

        // 水印 
        $('#keyword').watermark('输入姓名或简历编号');
        
        //回车事件
        $("#keyword").keydown(function (e) {
        	e = e || event;
            if (e.keyCode == 13) {
                $("#onSubmit").click();
            	return false;
            }
        });

        var r = cookieutility.get("notComplainCookie");
        if (!r) {
            $(".popReN").show();
        }


        //单个邀请面试
        $(".rMentLitBg .inviteResume").click(function (e) {
            var apply_id = $(this).parents(".rMentLv").attr("data-applyid");
            apply._invitesingle(apply_id);
            e.preventDefault();
        });

        //批量发送面试邀请 
        $('.btnOpInvite').click(function (e) {
        	e.preventDefault();
        	var applys = apply.selectApply();
        	if (applys.length <= 0) {
        		$.anchor('请选择收到的简历', {icon:'info'});
        		return;
        	}

        	if (applys.length == 1) {
        		apply._invitesingle(applys[0]);
        	} else {
        		apply._invitemulti(applys);
        	}
        });



       //删除投递的简历
       $(".deleteApply").click(function (e) {
            e.preventDefault();
            var apply_id = $(this).parents(".rMentLv").attr("data-applyid");
            val          = cookieutility.get('deleteapply');
            var name     = $(this).parents(".rMentLv").attr("data-name");

            if (val == 'true') {
                apply._deleteapply(apply_id);
            } else {
                $.showModal(encodeURI('<?php echo smarty_function_get_url(array('rule'=>"/apply/DeleteApply/",'data'=>"names='+name+'&ids='+apply_id+'&v='+Math.random()+'"),$_smarty_tpl);?>
'), {title:'删除'});
            }
       });

       //批量保存到电脑
       $('.savePc').click(function (e) {
            e.preventDefault();
            var applys = apply.selectApply();
           var resumeids = apply.selectResume();
            if (applys.length <= 0) {
                $.anchor('请选择收到的简历', {icon:'info'});
                return;
            }
           $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
               if(!json.status){
                   if(json.code == 701) {
                       apply._layeropen(json.msg);
                   }
               }else{
                   apply._downresume(resumeids.join(','),applys.join(','));
               }
           });


        });

        //全选 反选
        $(".resuemSelectAll").click(function(){
            if ($(this).is(':checked')) {
                $('.rMentLit label input[name="chkapply"]').attr('checked','checked');	
            } else {
                $('.rMentLit label input[name="chkapply"]:checked').removeAttr('checked');
            }
        });
        
//      单选
		$('.rMentLit label input[name="chkapply"]').click(function(){
			if(!$(this).prop('checked')){
				$(".resuemSelectAll").removeAttr('checked');
			}
			var chkapplyLengthAll = $('.rMentLit label input[name="chkapply"]').length;
			var chkapplyLength = $('.rMentLit label input[name="chkapply"]:checked').length;
			if(chkapplyLength == chkapplyLengthAll){
				$(".resuemSelectAll").attr('checked','checked');
			}
			
		});

        //批量转发到邮箱
        $('.sendEmail').click(function (e) {
            e.preventDefault();
            var applys = apply.selectApply();
            if (applys.length <= 0) {
                $.anchor('请选择收到的简历',{icon:'info'});
                return;
            }
            var resumeids = apply.selectResume();
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        apply._layeropen(json.msg);
                    }
                }else{
                    apply._sendEmail(resumeids.join(','),applys.join(','));
                }
            });

        });

        //设为待处理
        $(".setRead").click(function (e) {
            e.preventDefault();
            var applys = apply.selectApply();
            if (applys.length <= 0) {
                $.anchor('请选择收到的简历',{icon:'info'});
                return;
            }
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        apply._layeropen(json.msg);
                    }
                }else{
                    apply._setRead(applys.join(','));
                }
            });

        });

        //设为已邀请面试
        $(".hasInvite").click(function (e) {
            var apply_id = $(this).parents(".rMentLv").attr("data-applyid");
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        apply._layeropen(json.msg);
                    }
                }else{
                    e.preventDefault();
                    apply.hasInvite(apply_id);
                }
            });

        });

        //搜索及查询
        $(".child_status").click(function (e) {

            var child_status = $(this).attr("child_status");
            //搜索条件
            var job_id       = $("#job_id").val();
            var account_id   = $("#account_id").val();
            var keyword      = "";
            var son_account_id   = $("#son_account_id").val();

            var marriage_id   = $("#marriage_id").val();
            var dutytime_id   = $("#dutytime_id").val();
            var sex_id   = $("#sex_id").val();
            var years_id   = $("#years_id").val();
            var years_id2   = $("#years_id2").val();
            var education_id   = $("#education_id").val();
            var education_id2   = $("#education_id2").val();
            var amin   = $("#amin").val();
            var amax   = $("#amax").val();

            e.preventDefault();

            apply.submit(job_id, child_status, keyword, account_id,son_account_id,marriage_id,dutytime_id,sex_id,years_id,years_id2,education_id,education_id2,amin,amax);
            //apply.submit(job_id, child_status, keyword, account_id,son_account_id);
        });

        $("#onSubmit").click(function (e) {
            e.preventDefault();
            $('.md_search_name').click();
            //搜索条件
            var keyword      = $("#keyword").val();
            var account_id = "";
            var job_id = "";
            var child_status = "";
            apply.submit(job_id, child_status, keyword,account_id);
            return false;
        });

        $(".remark_contr").mouseover(function (e) {
            e.preventDefault();
            $(this).next(".remark_show").addClass("mTitcut2");
        }).mouseout(function (e) {
            e.preventDefault();
            $(this).next(".remark_show").removeClass("mTitcut2");
        });

        //拒绝单个
        $(".refuseSingle").click(function (e) {
            var apply_id = $(this).parents(".rMentLv").attr("data-applyid");
            var name     = $(this).parents(".rMentLv").attr("data-name");
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        apply._layeropen(json.msg);
                    }
                }else{
                    e.preventDefault();
                    $.showModal(encodeURI('<?php echo smarty_function_get_url(array('rule'=>"/apply/Refuse/",'data'=>"names='+name+'&ids='+apply_id+'&v='+Math.random()+'"),$_smarty_tpl);?>
'), {title:'确定不合适'});
                }
            });

        });


        //停止招聘的职位一键拒绝
        $('.resuse_stop_job').click(function(e){
            e.preventDefault();
            var job_id = $(this).attr('data-jobid');
            var url = '<?php echo smarty_function_get_url(array('rule'=>"/apply/Refuse/src-refuse_stop_job-job_id-'+job_id+'"),$_smarty_tpl);?>
';
            $.showModal(encodeURI(url), {title:'确定不合适'});
        });


        //一键拒绝剩余简历
        $('#refuse_last_apply').click(function(e){
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        apply._layeropen(json.msg);
                    }
                }else{
                    e.preventDefault();
                    var url = "?src=refuse_last_apply&job_id=<?php echo $_smarty_tpl->getVariable('job_id')->value;?>
&account_id=<?php echo $_smarty_tpl->getVariable('account_id')->value;?>
&child_status=<?php echo $_smarty_tpl->getVariable('child_status')->value;?>
&xx=1";
                    $.showModal(encodeURI('<?php echo smarty_function_get_url(array('rule'=>"/apply/Refuse/'+url+'"),$_smarty_tpl);?>
'), {title:'确定不合适'});
                }
            });

        });



        //拒绝多个
        $(".refuseMore").click(function (e) {
            var applys = apply.selectApply();
            if (applys.length <= 0) {
                $.anchor('请选择收到的简历', {icon:'info'});
                return;
            }
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        apply._layeropen(json.msg);
                    }
                }else{
                    $.showModal(encodeURI('<?php echo smarty_function_get_url(array('rule'=>"/apply/Refuse/",'data'=>"ids='+applys+'&v='+Math.random()+'"),$_smarty_tpl);?>
'), {title:'确定不合适'});
                }
            });


        })
           
        $(".changeStatus").click(function (e) {
            //改变状态
            var ban     = $(this).parent().siblings(".rMentLx ").find("em.hue1");
            var applyid = $(this).parents('.rMentLv').attr("data-applyid");
            apply._setReadSing1(applyid, ban);
        })

        //是否显示停招的职位
        $("#showStopJob").click(function () {
            if ($(this).is(":checked")) {
                cookieutility.set('showStopJobApply',true,"","/");
            } else {
                cookieutility.del('showStopJobApply',"/")
            }
            var status = <?php echo $_smarty_tpl->getVariable('status')->value;?>
;
            var son_account_id   = $("#son_account_id").val();
            var url = '<?php echo smarty_function_get_url(array('rule'=>"/apply/index",'data'=>"status='+status+'&son_account_id='+son_account_id+'"),$_smarty_tpl);?>
';
            //var url = '<?php echo smarty_function_get_url(array('rule'=>"/apply/index",'data'=>"status='+status+'"),$_smarty_tpl);?>
';
            window.location.href = url;
        });

        // 获取联系方式
        $(".getLinkWay").click(function () {
            resume_id = $(this).parents(".rMentLv").attr("data-resumeid");
            _this = $(this);
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/download/checkbalance"),$_smarty_tpl);?>
', {
                resume_id : resume_id
            }, function (json) {
                if (json && !json.status && json.msg) {
                    $.anchorMsg(json.msg, {
                        icon : 'success'
                    });
                } else if (!json.status) { 
                    $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/resume/BalanceNotEnough/"),$_smarty_tpl);?>
?account='+json.account_overage+'&consume='+json.price, {title : '获取联系方式'})
                } else {
                    var cookieValue = readCookie('downresumeprompt');
                    if (cookieValue && cookieValue.length > 0) {
                        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/download/getlinkway"),$_smarty_tpl);?>
', {
                            resumeID : resume_id
                        }, function (json) {
                            _this.hide().siblings('.hasInvite,.inviteResume').show();
                            _this.parents('.rMentLit').find(".rMentLx").append("<p><i></i>" + json.mobile_phone + "</p>")
                        });
                    } else {
                        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/apply/deductcountprompt/"),$_smarty_tpl);?>
?resumeid='+resume_id, {title:'获取联系方式'});
                    }
                }
            });
        });
    },
    getLetterContent1 : function(msg){
        var letter_content = "<dl id='yaoqing-alert' class='yaoqing-alert clearfix'><dd style='padding-top:10px'><p class='t2' style='color:#ff7920'>"+msg+"</p><p><a href='/licencevalidate/' class='yqQual'>完善资质</a></p></dd></dl>";
        return letter_content;
    },
    setNotComplainCookie : function() {
        $(".popReN").hide();
        cookieutility.set('notComplainCookie', true);
    },
    _invitesingle : function(applyid) { //单个邀请

        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
            if(!json.status){
                if(json.code == 701) {
                    apply._layeropen(json.msg);
                }
            }else{
                $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/invite/invitesingleshow/",'data'=>"applyID='+applyid+'"),$_smarty_tpl);?>
-v-'+Math.random(), {title:'面试邀请'});
            }
        });

    },
    _invitemulti : function(applyids) {
        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
            if(!json.status){
                if(json.code == 701) {
                   apply._layeropen(json.msg);
                }
            }else{
                var isShowStopJob = 1;//默认包含停招职位
                if (!$("#showStopJob").attr("checked")) {
                    //不包含停招职位
                    isShowStopJob = 2;
                }
                $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/invite/InviteMultiShow2/"),$_smarty_tpl);?>
isShowStopJob-'+isShowStopJob+'-applyids-'+applyids.join('-applyids-'), {title:'批量邀请面试', onclose:function() {
                }
                });
            }
        });

	},
    _invitCallback : function(applyids) { 
        window.location.reload();
    },
     _printresume : function(resumeid) {
		var url = '<?php echo smarty_function_get_url(array('rule'=>"/resume/htmlprint",'data'=>"resumeid='+resumeId+'"),$_smarty_tpl);?>
';
		$('#printIframe').attr("src", url);
    },
    _downresume : function(resumeid, applyid) {
        var url = '<?php echo smarty_function_get_url(array('rule'=>"/apply/DownLoad",'data'=>"resumeid='+resumeid+'&applyid='+applyid+'"),$_smarty_tpl);?>
';
    	$.showModal(url, {title:'请选择保存的文件格式'});
    },
    _downresumeword : function(ids, applyids) {
    	var url = '<?php echo smarty_function_get_url(array('rule'=>"/resume/worddown",'data'=>"resumeid='+ids+'&applyid='+applyids+'&src=apply"),$_smarty_tpl);?>
';
        $(this).attr('href', url).attr('target', '_blank');
        apply.changeStatus();//改变状态
    },
    _layeropen : function(msg){
        var parentlayer = layer.open({
            type: 1,
            area: ["400px", "200px"],
            title: "企业认证",
            content: apply.getLetterContent1(msg)
        });
    },
    _downresumehtml : function(ids, applyids) {
    	var url = '<?php echo smarty_function_get_url(array('rule'=>"/resume/htmldown",'data'=>"resumeid='+ids+'&applyid='+applyids+'&src=apply"),$_smarty_tpl);?>
';
        $(this).attr('href', url).attr('target', '_blank');
        apply.changeStatus();//改变状态
    },
    _downresumeExcel : function(ids) {
        var applys = apply.selectApply();
        var url = apply.length > 0 ? '<?php echo smarty_function_get_url(array('rule'=>"/excel/index/",'data'=>"resumeid='+ids+'&applyid='+applyids+'"),$_smarty_tpl);?>
' : '<?php echo smarty_function_get_url(array('rule'=>"/excel/index/",'data'=>"resumeid='+ids+'"),$_smarty_tpl);?>
';

    	$(this).attr('href', url).attr('target', '_blank');
        apply.changeStatus();//改变状态
    },
    _downresumePdf : function(ids) {
        var applyids = apply.selectApply();
        var url = apply.length > 0 ? '<?php echo smarty_function_get_url(array('rule'=>"/resume/pdfdown/",'data'=>"resumeid='+ids+'&applyid='+applyids+'"),$_smarty_tpl);?>
' : '<?php echo smarty_function_get_url(array('rule'=>"/resume/pdfdown/",'data'=>"resumeid='+ids+'"),$_smarty_tpl);?>
';

    	$(this).attr('href', url).attr('target', '_blank');
        apply.changeStatus();//改变状态
    },
    selectApply : function() {
        var checkboxs = $('.rMentLitBg').find('input[name="chkapply"]:checked'),
        applyids = [];
        for (var i = 0, len = checkboxs.length; i < len; i += 1) {
            applyids.push($(checkboxs[i]).val());
        }
        return applyids;
    },
    //转发到邮箱
    _sendEmail : function(resumeid, applyid) {
    	$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/resume/wordsend",'data'=>"ischangestatus=1&resumeid='+resumeid+'&applyid='+applyid+'&src=apply"),$_smarty_tpl);?>
', {title:'转发到邮箱'});
    },
    selectResume : function() {
        var checkboxs = $('.rMentLitBg').find('input[name="chkapply"]:checked'),
        resumeids = [];
        for(var i = 0, len = checkboxs.length; i < len; i += 1) {
            resumeids.push($(checkboxs[i]).attr('data-resumeid'));
        }
        return resumeids;
    },
    selectUserName : function() {
        var checkboxs = $('.rMentLitBg').find('input[name="chkapply"]:checked'),
        usernames = [];
        for(var i = 0, len = checkboxs.length; i < len; i += 1) {
            usernames.push($(checkboxs[i]).attr('data-name'));
        }
        return usernames;
    },
    //设置为待定，以前是标记为已读逻辑
    _setRead : function(applyids) {
        // 设置已读
        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/apply/MarkWaitDeal/"),$_smarty_tpl);?>
'+'applyid-'+applyids+'-v-'+Math.random(),function(result) {
            if (result.status) {
                $(".rMentLitBg input[name='chkapply']:checked").each(function() {
                    $.anchorMsg("标记待定成功", {icon: 'success'});
                    var ban = $(this).parent().next("em");
                    ban.html("待定").removeClass("hue1").addClass("hue2");
                    window.location.reload();
                });
            } else {
                // 设置失败
                $.anchorMsg(result.msg, {icon: 'fail'});
            }
        });
    },
    //单个设置已读
    _setReadSing : function(applyid, ban) {
        // 设置已读
        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/apply/SetRead/"),$_smarty_tpl);?>
'+'applyid-'+applyid+'-v-'+Math.random(),function(result) {
            if (result.success) {
                $.anchorMsg("标记待定成功", {icon : 'success'});
                ban.removeClass("hue1").addClass("hue3").html("已读（未回复）");
            } else {
                // 设置失败
                $.anchorMsg(result.error, {icon : 'fail'});
            }
        });
    },
    _setReadSing1 : function(applyid, ban) {
        // 设置已读
        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/apply/SetRead/"),$_smarty_tpl);?>
'+'applyid-'+applyid+'-v-'+Math.random(),function(result) {
            if (result.success) {
                //$.anchorMsg("标记待定成功", {icon : 'success'});
                ban.removeClass("hue1").addClass("hue3").html("已读（未回复）");
            } else {
                // 设置失败
                $.anchorMsg(result.error, {icon : 'fail'});
            }
        });
    },
    submit : function(job_id, child_status, keyword, account_id,son_account_id,marriage_id,dutytime_id,sex_id,years_id,years_id2,education_id,education_id2,amin,amax) {


        var show_not_use_job = "<?php echo $_smarty_tpl->getVariable('show_not_use_job')->value;?>
";
        if (keyword == '输入姓名或简历编号') {
            keyword = "";
        }
        var data = [];
        if (job_id != '')
            data.push("job_id=" + job_id);

        if (marriage_id != '')
            data.push("marriage_id=" + marriage_id);
        if (dutytime_id != '')
            data.push("dutytime_id=" + dutytime_id);
        if (sex_id != '')
            data.push("sex_id=" + sex_id);
        if (years_id2 != '')
            data.push("years_id2=" + years_id2);
        if (years_id != '')
            data.push("years_id=" + years_id);
        if (education_id != '')
            data.push("education_id=" + education_id);
        if (education_id2 != '')
            data.push("education_id2=" + education_id2);

        if (child_status != '')
            data.push("child_status=" + child_status);

        if (amin != '')
            data.push("amin=" + amin);
        if (amax != '')
            data.push("amax=" + amax);
    
        if (keyword != '') {
            data.push("keyword=" + keyword);
            data.push("search_model=1");
        }

        if (show_not_use_job == 0)
            data.push("show_not_use=0");

        if (account_id)
            data.push('account_id=' + account_id);
        if (son_account_id)
            data.push('son_account_id=' + son_account_id);

        data.push('search=' + 1);

        if (data.length > 0) {
            window.location.href = '<?php echo smarty_function_get_url(array('rule'=>"/apply/index/"),$_smarty_tpl);?>
' + "?" + data.join("&");
        } else {
            window.location.href = '<?php echo smarty_function_get_url(array('rule'=>"/apply/index/"),$_smarty_tpl);?>
';
        }
    },
    changeStatus : function() {
        $(".rMentLitBg input[name='chkapply']:checked").each(function() {
            var ban = $(this).parent().next("em");
            ban.html("待定").removeClass("hue1").addClass("hue2");
        });  
    },
    refresh : function(){
        window.location.reload();
    },
    //已通知面试 添加面试记录
    hasInvite : function(applyid) {
        var url = '<?php echo smarty_function_get_url(array('rule'=>"/apply/hasInvite/",'data'=>"applyid='+applyid+'"),$_smarty_tpl);?>
';
        $.showModal(url, {title:'标记为“已邀请简历”'});
    },
    _deleteapply : function(applyid) {
         // 删除求职申请	
	   $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/apply/DeleteApply/"),$_smarty_tpl);?>
'+'op-del-ids-'+applyid+'-v-'+Math.random(), function (result) {
			if (result.success) {
				$.anchorMsg('已成功删除');
                window.location.reload();
			} else {
				$.anchorMsg(result.error, {icon : 'fail'}); 
			}
	    });
    },
    _setSelectMultiAppId : function(appids) {
        if (appids.length > 0){
            for (var i = 0; i < appids.length; i++) {
                $("#row" + appids[i]).remove();
            }
        }
    }
    //获得当前选中的状态
}
//回复率提示
$('.mentRImg').hover(function(){
	$('.mentRPop').toggle();							  
})   
apply.init();

$.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/account/twodimensioncode/"),$_smarty_tpl);?>
',function(result){
    if(result&&result.status) {
        var src = result.data.codeurl;
        $('#ewmBox #jewm').attr('src',src);
        $('#ewmBox').show();
    }
});

$("#ewmBox,#ewmBox1").find("a").click(function(){$(this).parents(".ewmBox").hide();return false;});
</script>
<script>
	var resumeCompleteDialog;
    hbjs.use('@hbCommon, @jobDialog, @validator, @confirmBox', function(m) {



        var $ = m['product.hbCommon'].extend(m['cqjob.jobDialog']);
        var Dialog = m['widge.overlay.hbDialog'];
        var confirmBox = m['widge.overlay.confirmBox'];
        var cookie = m['tools.cookie'];
        
        $("#closefiltration").on('click', function(){
            var expires_time = new Date("<?php echo date('Y/m/d 00:00:00',strtotime('+30 days'));?>
");
            cookie.set("<?php echo $_smarty_tpl->getVariable('closefiltration_cookie_key')->value;?>
",1,{expires:expires_time,path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
            $(this).parent().hide(); 
        });
        function showModel(icon, msg) {
            var pWidth = 70, fontWidth = 18;
            confirmBox.timeBomb(msg, {
                name: icon,
                width: pWidth + msg.length * fontWidth
            });
        }


		//2019.6.3更新
		//简历完善度
		resumeCompleteDialog = new Dialog({
			close: 'x',
			idName: 'resume_complete_dialog',
			title: '简历完善度调研',
			content: "<?php echo smarty_function_get_url(array('rule'=>'/answer/index/'),$_smarty_tpl);?>
",
			width:'auto',
			isAjax: true
		})
		$('.resume_complete').on('click',function(){
			resumeCompleteDialog.show()
		})
			
			
        //简历处理率弹窗
        var registerDialog;
        function showRegisterDialog(registerContent){
            if(!registerDialog){
                registerDialog = new Dialog({
                    idName: 'new-sign-pop-dialog',
                    width: 700,
                    close: '╳',
                    title:'正确处理简历的步骤'
                });
                //不需要！
            }
            registerDialog.query('#registerCompanyBtn').off('click');
            registerDialog.setContent(registerContent);
            registerDialog.query('#registerCompanyBtn').on('click', function(){
                //registerCompanyDO();
                registerDialog.hide();

            });

            registerDialog.show();
        }

        $('#timeLog').click(function(){
        	var _this = this;
            if(_this.improveRelateHtml){
               showRegisterDialog(_this.improveRelateHtml);
            }else{
            	$.post("<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/apply/ImproveRelateAlertHtml",{},function(e){
	                _this.improveRelateHtml = e;
	                showRegisterDialog(e);
	            });
            }
        });

		//点击展开收起
		$("#setShowHide").click(function(){
			$(this).toggleClass("hide");
			$(this).parents(".yjhj-status").find(".yjhj-cont").toggle();
		});

        //单个标记为待定
        $('.mark_wait_deal').click(function(){
            var applay_id = $(this).attr('data-id');
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        apply._layeropen(json.msg);
                    }
                }else{

                    $.confirm('将简历标为待定，暂缓回复吗？',function(){
                        $.post('<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/apply/MarkWaitDeal',{applyid:applay_id},function(e){
                            if(e.status){
                                showModel('success', '已成功标记为待定');
                                window.location.reload();
                            }else{
                                showModel('fail', e.msg);
                            }
                        },'json');
                    });
                }
            });

        });
        //App
        $("#js_hfApp").hover(function(e){
            $(this).find("i").show();
            $("#js_hfAppBox").show();
        },function(){
            $(this).find("i").hide();
            $("#js_hfAppBox").hide();
        });
        $("#js_tsApp").hover(function(e){
            $(this).find("i").show();
            $("#js_tsAppBox").show();
        },function(){
            $(this).find("i").hide();
            $("#js_tsAppBox").hide();
        });
    });
</script>

<?php $_template = new Smarty_Internal_Template("chat/chat.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<div class="md_search_name" style="display: none" data-explain="do not delete it!"></div>
<script  type="text/javascript">
    var action_url = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
';


    if(typeof action_dom == 'object'){}else{
        action_dom = [];
    }
    action_dom.push( ['.md_remain_close', 193]);
    action_dom.push( ['.md_pub_account', 194]);
    action_dom.push( ['.md_injob', 195]);
    action_dom.push( ['.md_stopjob', 196]);
    action_dom.push( ['.md_refresh', 197]);
    action_dom.push( ['.md_reamin_repaly', 198]);
    action_dom.push( ['.md_refuse_allresume', 199]);


    action_dom.push( ['.md_resume_nolimit', 200]);
    action_dom.push( ['.md_resume_noread', 201]);
    action_dom.push( ['.md_resume_hadread', 202]);
    action_dom.push( ['.md_resume_waitdeal', 203]);
    action_dom.push( ['.md_resume_more', 204]);
    action_dom.push( ['.md_save_computer', 205]);
    action_dom.push( ['.md_to_mail', 206]);
    action_dom.push( ['.md_invite', 207]);
    action_dom.push( ['.md_not_fit', 208]);
    action_dom.push( ['.md_search_name', 209]);

    action_dom.push( ['.md_r_send_invit', 210]);
    action_dom.push( ['.md_r_send_hadtell', 211]);
    action_dom.push( ['.md_r_send_notfit', 212]);
    action_dom.push( ['.md_r_send_waitdeal', 213]);
    action_dom.push( ['.md_change_send', 214]);
    action_dom.push( ['.md_chat', 215]);
    action_dom.push( ['.md_see_resume', 216]);

    /**
     193=> '温馨提示关闭标签',
     194=> '职位发布人',
     195=> '招聘职位',
     196=> '包含停招职位',
     197=> '刷新',
     198=> '如何提示简历回复率',
     199=> '一键回绝剩余简历',

     200=> '简历状态--不限',
     201=> '简历状态--未读',
     202=> '简历状态--已读（待回复）',
     203=> '简历状态--待定（待回复）',
     204=> '更多筛选条件',
     205=> '保存到电脑',
     206=> '转发到邮箱',
     207=> '面试邀请',
     208=> '不合适',
     209=> '姓名或简历编号搜索',

     210=> '简历列表--发面试邀请',
     211=> '简历列表--已通知',
     212=> '简历列表--不合适',
     213=> '简历列表--待定',
     214=> '转发',
     215=> '聊一聊',
     216=> '查看简历',
     */
</script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'action.js'),$_smarty_tpl);?>
"></script>
</body>
</html>

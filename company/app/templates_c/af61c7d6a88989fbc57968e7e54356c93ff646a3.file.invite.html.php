<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:40:13
         compiled from "app\templates\resume/apply/invite.html" */ ?>
<?php /*%%SmartyHeaderCode:246615e70388d6f3747-31977876%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'af61c7d6a88989fbc57968e7e54356c93ff646a3' => 
    array (
      0 => 'app\\templates\\resume/apply/invite.html',
      1 => 1584332294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '246615e70388d6f3747-31977876',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
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
<style type="text/css">
.rMentLit .rMentLx b.gen-binding {background: #ff9b00;border:0px;color:#feffff;border-radius: 2px;line-height: 18px;font-weight: normal;}
</style>
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
</head>
    
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
        .subMetx .job {width:130px}
        .sendTo_img{ display:none;position:absolute; top:10px; left:110px;width:150px; background:#fff; overflow:hidden; border:1px solid #ddd; text-align:center; color:#333;}
         .sendTo_img span, .sendTo_img img, .sendTo_img b{ display:block; margin:0 auto; line-height:20px;font-size:12px; font-weight:normal;}
         .sendTo_img span{padding-top:10px; color:#f35a00;}
		.sendOffer{ display: block; position: absolute; position: absolute; top: 125px; right: 10px; color: #2b6fad;}
		.sendOffer:hover{ color: #09c;text-decoration: underline;}
    </style>
    <div class="ewmBox" id="ewmBox">
        <a href="" class="close"></a>
        <img src="ewm1.jpg" />
        <p>关注汇博招聘<br />随时随地筛选简历</p>
    </div>
    <div class="ewmBox" id="ewmBox1" style="top:250px;display: block;padding-bottom: 10px">
        <a href="" class="close"></a>
        <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/acompany_joblist_ewm.jpg" />
        <p style="font-size: 14px;color:#4e74d9">汇博企业版 APP<br><span style="font-size: 16px">随时处理简历<br>提高招聘效率</span><br><span style="font-size: 12px;color:#999;display: block;margin-top:5px">暂时只提供安卓版本</span></p>
    </div>
    <!---20151208 微信二维码 end -->


<?php $_template = new Smarty_Internal_Template('resume/apply/nav.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par',"收到的简历");$_template->assign('cur',"已邀请简历"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<div class="rMentRt">
    <div class="rMenTit"><span>共<b><?php echo $_smarty_tpl->getVariable('totalSize')->value;?>
</b>份简历</span></div>
    <div class="rMetSelt">
    	<div class="subMetx">
            <div class="job" style="width:150px;<?php if (!$_smarty_tpl->getVariable('is_hr')->value){?>display:none<?php }?>"><span id="tstDropCom" class="drop zindex" style="<?php if (!$_smarty_tpl->getVariable('is_hr')->value){?>display:none<?php }?>"></span></div>
            <span style="padding-top:5px;">职位发布人：</span>
            <div class="job"><span id="tstDropJobPeople" class="drop zindex"></span></div>
            <span style='padding-top:5px'>招聘职位：</span>
            <div class="job"><span id="tstDropJob" class="drop zindex"></span></div>
            <label  style="padding-top:6px;display: block;float: left;">
                <input type="checkbox" <?php if ($_smarty_tpl->getVariable('showStopJobApply')->value){?>checked="checked"<?php }?> id="showStopJob" style="display:inline-block; vertical-align:-1px;" />&nbsp;包含停招职位
            </label>
            <div class="clear"></div>
        </div>
        <div class="subMetz">
        <span>邀请时间：</span>
            <a href="javascript:;" class="invite_time invite_timeall <?php if (base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->getVariable('invite_time')->value)){?>cut<?php }?>" data-invite='' >不限</a>
            <a href="javascript:;" class="invite_time invite_timeday <?php if ($_smarty_tpl->getVariable('invite_time')->value=='+0'){?>cut<?php }?>" data-invite='+0'>今天</a>
            <a href="javascript:;" class="invite_time invite_time1 <?php if ($_smarty_tpl->getVariable('invite_time')->value==1){?>cut<?php }?>" data-invite='1'>昨天</a>
            <a href="javascript:;" class="invite_time invite_time7 <?php if ($_smarty_tpl->getVariable('invite_time')->value==7){?>cut<?php }?>" data-invite='7'>近7天</a>
            <a href="javascript:;" class="invite_time invite_time30 <?php if ($_smarty_tpl->getVariable('invite_time')->value==30){?>cut<?php }?>" data-invite='30'>近30天</a>
            <a href="javascript:;" class="invite_time invite_time90 <?php if ($_smarty_tpl->getVariable('invite_time')->value==90){?>cut<?php }?>" data-invite='90'>近90天</a>
        </div>
    </div>
    <div class="rMentBtn">
    	<label>
        	<input name="c" class="resuemSelectAll" type="checkbox" value="" /><span>全选</span>
        </label>
        <p>
            <a href="javascript:;" class="savePc">保存到电脑</a>
            <a href="javascript:;" class="sendEmail">转发到邮箱</a>
            <a href="javascript:;" class='goRecycle'>放入回收站</a>
        </p>
        <div class="rMentSech">
            <input type="text" name="t" id="keyword" class="rMenText" value="<?php if (!empty($_smarty_tpl->getVariable('keyword',null,true,false)->value)){?><?php echo $_smarty_tpl->getVariable('keyword')->value;?>
<?php }?>" />
            <input type="button" id="onSubmit" name="b" value="" class="rMentBtnx" />
        </div>
    </div>
    <div class="rMentLitBg">
        <?php if ($_smarty_tpl->getVariable('totalSize')->value>0){?>
        <?php  $_smarty_tpl->tpl_vars['apply'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('applylist')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['apply']->key => $_smarty_tpl->tpl_vars['apply']->value){
?>
        <div class="rMentLit" >
        	<div class="resumeKuaimiTips">
				<em>快米</em>
				<p>
					<span><b>快米工作</b>汇博旗下专注蓝领招聘平台</span>
				</p>
				<span class="kuaimiTipx">汇博上的<i>普工、服务员</i>等蓝领职位将同步展示到快米平台</span>
			</div>
			<!-- 已邀请提示 -->
			<div class="resumeInviteTips">
				<?php echo $_smarty_tpl->tpl_vars['apply']->value['invite_content'];?>

			</div>
            <div class="rMentLx <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==5||$_smarty_tpl->tpl_vars['apply']->value['status']==6){?>rMentLxgray<?php }?>">
                <label>
                    <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>
                    <input name="chkapply" type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
" data-resumeid="<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
"  data-name="<?php echo $_smarty_tpl->tpl_vars['apply']->value['user_name'];?>
"/>
                    <?php }?>
                    <b title="<?php echo $_smarty_tpl->tpl_vars['apply']->value['station'];?>
"><?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['station'])){?>应聘职位：<?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['apply']->value['station'],10,'utf-8','','…');?>
<?php }?></b>
                    <?php if (($_smarty_tpl->tpl_vars['apply']->value['generation_binding'])){?>
                    <span>--<?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->getVariable('accounts')->value[$_smarty_tpl->tpl_vars['apply']->value['company_id']]['company_name_display'],10,'utf-8','','…');?>
</span>
                    <b class="gen-binding">代招</b>
                    <?php }?>
                    <span><?php echo date("Y-m-d H:i",strtotime($_smarty_tpl->tpl_vars['apply']->value['create_time']));?>
</span>
                </label>
                <em class="<?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==5||$_smarty_tpl->tpl_vars['apply']->value['status']==6){?>hue4<?php }elseif($_smarty_tpl->tpl_vars['apply']->value['status']==1){?>hue3 resumeTipsIv<?php }else{ ?>hue2<?php }?>"><?php echo $_smarty_tpl->tpl_vars['apply']->value['statusName'];?>
</em>
                <?php if ($_smarty_tpl->tpl_vars['apply']->value['is_send_offer']){?><em class="hue3" style="display: none;">已发送offer</em><?php }?>
                <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['mobile_phone'])){?>
                <p><i></i><?php echo $_smarty_tpl->tpl_vars['apply']->value['mobile_phone'];?>
</p><?php }?>
                <?php if ($_smarty_tpl->tpl_vars['apply']->value['is_shuangxuan_relate']){?> <a  style="display: inline-block;color: red;border-radius: 25px;padding: 0 10px;text-align: center;border:solid red 1px;width: 130px;height: 25px;vertical-align: middle;line-height: 25px;margin-left: 5px;"   class="">校园视频网络招聘会</a><?php }?>
                <?php if ($_smarty_tpl->tpl_vars['apply']->value['is_kuaimi']){?>
                	<em class="resumeTipsKm" style="margin-right: 10px;"><i></i>该简历来自快米工作</em>
                <?php }?>
                <span class="clear"></span>                                                                                                     
            </div>
            <div class="rMentLv <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==5||$_smarty_tpl->tpl_vars['apply']->value['status']==6){?>rMentLvgray<?php }?>" data-applyid ="<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
" data-resumeid ="<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
" data-personid="<?php echo $_smarty_tpl->tpl_vars['apply']->value['person_id'];?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['apply']->value['user_name'];?>
">
                <a <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>href="<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-resumeid-<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
-src-apply-applyid-<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
" target="_blank"<?php }else{ ?>href="javascript:;"<?php }?> class="rMentLink <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==2){?>changeStatus<?php }?>">
                    <div class="mImgBg">
                        <p>
                            <img src="<?php if ($_smarty_tpl->tpl_vars['apply']->value['small_photo']){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['small_photo'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/headportrait.png<?php }?>"  />
                        </p>
                    </div>
					</a>
                    <div>
                        <p class="mTit1">
							<a <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>href="<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-resumeid-<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
-src-apply-applyid-<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
" target="_blank"<?php }else{ ?>href="javascript:;"<?php }?> class="rMentLink md_see_resume <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==2){?>changeStatus<?php }?>">
							<b><?php echo $_smarty_tpl->tpl_vars['apply']->value['user_name'];?>
</b> </a>
                            <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['sex'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['sex'];?>
/<?php }?>
                            <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['start_work'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['start_work'];?>
/<?php }?>
                            <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['age'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['age'];?>
/<?php }?>
                            <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['cur_area'])){?><?php echo $_smarty_tpl->tpl_vars['apply']->value['cur_area'];?>
<?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['apply']->value['remark']&&$_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?><i class='remark_contr'></i><span class='remark_show'><?php echo $_smarty_tpl->tpl_vars['apply']->value['remark'];?>
<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/hbtip2.png" width="5" height="22"></span><?php }?>
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
<?php $_tmp1=ob_get_clean();?><?php if (!empty($_tmp1)){?>
                        <p class="mTit3"><?php echo $_smarty_tpl->tpl_vars['apply']->value['school'];?>
<?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['major_desc'])){?><span>|</span><?php echo $_smarty_tpl->tpl_vars['apply']->value['major_desc'];?>
<?php }?><span>|</span><?php echo $_smarty_tpl->tpl_vars['apply']->value['school_degree'];?>
</p>
                        <?php }?>
                        <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['worklist'])){?>
                        <p class="mTit2" <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['apply']->value['school'];?>
<?php $_tmp2=ob_get_clean();?><?php if (empty($_tmp2)){?>style="margin-top: 8px;"<?php }?>>
							<b><?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['station'];?>
</b><span>|</span><?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['company_name'];?>
<span>|</span><?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['start_time'];?>
-<?php echo $_smarty_tpl->tpl_vars['apply']->value['worklist'][0]['end_time'];?>
</p>
                        <?php }?>
						</a>
                    </div>
                
                <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']==5||$_smarty_tpl->tpl_vars['apply']->value['status']==6){?>
                <div class="rMentLinkv">
                    <a href="javascript:;">&nbsp;</a>
                    <a href="javascript:;" class="mTit4 goRecycleSingle">删除</a>
                    <a href="javascript:;">&nbsp;</a>
                </div>
                <?php }else{ ?>
                <?php }?>
                <div class="clear"></div>
                <?php if ($_smarty_tpl->tpl_vars['apply']->value['status']!=5&&$_smarty_tpl->tpl_vars['apply']->value['status']!=6){?>
                <div class="sendTo_img"><span>把简历转发给职位负责人</span><img src="" data-img-source="<?php echo smarty_function_get_url(array('rule'=>'/apply/SendToWorkMatePng/'),$_smarty_tpl);?>
?src=apply&src_id=<?php echo $_smarty_tpl->tpl_vars['apply']->value['apply_id'];?>
&resume_id=<?php echo $_smarty_tpl->tpl_vars['apply']->value['resume_id'];?>
"/><b>用汇博企业APP<br />扫码转发简历</b></div>
                <?php }?>
            </div>
            <?php if (!empty($_smarty_tpl->tpl_vars['apply']->value['audition_time'])){?><span class="rMInterviewx">面试时间：<?php echo $_smarty_tpl->tpl_vars['apply']->value['audition_time'];?>
</span><?php }?>
            <a class="sendOffer" target="_blank" href="/offertemplate/index/invite_id-<?php echo $_smarty_tpl->tpl_vars['apply']->value['invite_id'];?>
">发送offer</a>
            <div class="clear"></div>
        </div>
        <?php }} ?>
        <?php }else{ ?>
        <div class="noData">
        	<p>未找到相关简历，请更换筛选条件、关键词再试！</p>
        </div>
        <?php }?>
        <div class="clear"></div>
    </div>
    <?php if ($_smarty_tpl->getVariable('totalSize')->value>0){?>        
    <div class="rMentBtn">
    	<label>
        	<input name="c" class="resuemSelectAll" type="checkbox" value="" /><span>全选</span>
        </label>
        <p>
            <a href="javascript:;" class="savePc">保存到电脑</a>
            <a href="javascript:;" class="sendEmail">转发到邮箱</a>
            <a href="javascript:;" class='goRecycle'>放入回收站</a>
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
<div class="chatonechat22" style="display: none"></div>
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
$.setIndex("zindex");//为需要赋层级设置的元素设置class为zindex

//快米工作
$('.resumeTipsKm').hover(function(){
	$(this).parent().prev('.resumeKuaimiTips').toggle();
});
//已邀请详情
$('.resumeTipsIv').hover(function(){
	$(this).parent().prev('.resumeInviteTips').toggle();
});
$(".md_chat").click(function(){
    $(".chatonechat22").click();
})

$('#tstDropJob').droplist({
    defaultTitle : '全部职位',
    style : 'width:250px;',
    noSelectClass : 'gray',
    inputWidth : 100,
    width : 128,
    hddName : 'job_id',
    items : <?php echo $_smarty_tpl->getVariable('jobs')->value;?>
,
    selectValue : '<?php echo $_smarty_tpl->getVariable('job_id')->value;?>
',
    maxScroll : 10,
    onSelect : function(i,name) {
	   	//选中后的事件
        var invite_time = $(".subMetz .cut").attr("data-invite");
        var job_id      = $("#job_id").val();
        var account_id  = $("#account_id").val();
        var keyword     = "";
        var son_account_id   = $("#son_account_id").val();

        apply.submit(job_id, invite_time, keyword, account_id,son_account_id);
    }
});

$('#tstDropJobPeople').droplist({
    defaultTitle : '全部',
    style : 'width:100px;',
    noSelectClass : 'gray',
    inputWidth : 100,
    width : 128,
    hddName : 'son_account_id',
    items : <?php echo $_smarty_tpl->getVariable('job_people')->value;?>
,
    selectValue : '<?php echo $_smarty_tpl->getVariable('son_account_id')->value;?>
',
    maxScroll : 10,
    onSelect : function(i, name) {
    //选中后的事件
        var invite_time = $(".subMetz .cut").attr("data-invite");
        var job_id       = '';
        var son_account_id   = $("#son_account_id").val();
        var account_id   = $("#account_id").val();
        var keyword      = "";

        apply.submit(job_id, invite_time, keyword, account_id,son_account_id);
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
        //选中后的事件
        var invite_time = $(".subMetz .cut").attr("data-invite");
        var job_id      = "";
        var account_id  = $("#account_id").val();
        var keyword     = "";

        apply.submit(job_id, invite_time, keyword, account_id); 
    }
});

var apply ={
    init:function() {
         //回车事件
       $("#keyword").keydown(function(e){
            if(e.keyCode == 13){
                $("#onSubmit").click();
            }
        });
        // 水印 
        $('#keyword').watermark('输入姓名或简历编号');
       $("#notComplain").click(function(){
           apply.setNotComplainCookie();
       });
       var r = cookieutility.get("notComplainCookie");
       if (!r) {
           $(".popReN").show();
       }

       //单个邀请面试
       $(".rMentLitBg .inviteResume").click(function(e){
            var apply_id = $(this).parents(".rMentLv").attr("data-applyid");
            apply._invitesingle(apply_id);
            e.preventDefault();
       });

       //批量保存到电脑
       $('.savePc').click(function(e){
            e.preventDefault();
            var applys = apply.selectApply();
            if(applys.length<=0) {
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
                   apply._downresume(resumeids.join(','),applys.join(','));
               }
           });


        });

        //全选 反选
        $(".resuemSelectAll").click(function (e) {
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
            if(applys.length<=0) {
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
        $(".setRead").click(function(e){
            e.preventDefault();
            var applys = apply.selectApply();
            if(applys.length<=0) {
                $.anchor('请选择收到的简历',{icon:'info'});
                return;
            }
            apply._setRead(applys.join(','));
        });

        //设为已邀请面试
        $(".hasInvite").click(function(e){
             e.preventDefault();
             var apply_id = $(this).parents(".rMentLv").attr("data-applyid");
             apply.hasInvite(apply_id);
        });

        //搜索及查询
        $(".invite_time").click(function(e){
            e.preventDefault();
            //搜索条件
            var invite_time = $(this).attr("data-invite");
            var job_id      = $("#job_id").val();
            var account_id  = $("#account_id").val();
            var keyword     = "";
            var son_account_id   = $("#son_account_id").val();

            apply.submit(job_id, invite_time, keyword, account_id,son_account_id);
        });

        $("#onSubmit").click(function(e){
            e.preventDefault();
            var keyword = $("#keyword").val();
            var invite_time = "";
             //搜索条件
            var job_id = "";
            var account_id = "";
            apply.submit(job_id, invite_time, keyword,account_id);
        });

        $(".remark_contr").mouseover(function(e){
            e.preventDefault();
            $(this).next(".remark_show").addClass("mTitcut2");
        }).mouseout(function(e){
            e.preventDefault();
            $(this).next(".remark_show").removeClass("mTitcut2");
        });

        //单个放回回收站
        $(".goRecycleSingle").click(function(e){
             var apply_id = $(this).parents(".rMentLv").attr("data-applyid");
             var name = $(this).parents(".rMentLv").attr("data-name");
              val = cookieutility.get('deleteapply');
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        apply._layeropen(json.msg);
                    }
                }else{
                    if(val == 'true'){
                        apply._deleteapply(apply_id);
                    }else {
                        $.showModal(encodeURI('<?php echo smarty_function_get_url(array('rule'=>"/apply/DeleteApply/",'data'=>"names='+name+'&ids='+apply_id+'&v='+Math.random()+'"),$_smarty_tpl);?>
'),{title:'删除'});
                    }
                }
            });

        });

        //放入回收站
        $(".goRecycle").click(function(e){
            e.preventDefault();
            var applys = apply.selectApply();
            if(applys.length<=0) {
                $.anchor('请选择收到的简历',{icon:'info'});
                return;
            }
            var names =apply.selectUserName();
                val = cookieutility.get('deleteapply');
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        apply._layeropen(json.msg);
                    }
                }else{
                    if(val == 'true'){
                        apply._deleteapply(applys.join(','));
                    }else {
                        $.showModal(encodeURI('<?php echo smarty_function_get_url(array('rule'=>"/apply/DeleteApply/",'data'=>"names='+names.join(',')+'&ids='+applys.join(',')+'&v='+Math.random()+'"),$_smarty_tpl);?>
'),{title:'放入回收站'});
                    }
                }
            });

        });

        //是否显示停招的职位
        $("#showStopJob").click(function(){
            if ($(this).is(":checked")) {
                cookieutility.set('showStopJobApply', true, "", "/");
            } else {
                cookieutility.del('showStopJobApply',"/")
            }

            var status = <?php echo $_smarty_tpl->getVariable('status')->value;?>
;
            var son_account_id   = $("#son_account_id").val();
            var url = '<?php echo smarty_function_get_url(array('rule'=>"/apply/index",'data'=>"status='+status+'&son_account_id='+son_account_id+'"),$_smarty_tpl);?>
';
            window.location.href = url;
        });
    },
    setNotComplainCookie:function(){
        $(".popReN").hide();
       cookieutility.set('notComplainCookie',true,1);
       
    },
    _invitesingle:function(applyid) { //单个邀请
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/invite/invitesingleshow/",'data'=>"applyID='+applyid+'"),$_smarty_tpl);?>
-v-'+Math.random(),{title:'同意面试',onclose:function(){
            apply._invitCallback(applyid);
        }});	
    },
    getLetterContent1 : function(msg){
        var letter_content = "<dl id='yaoqing-alert' class='yaoqing-alert clearfix'><dd style='padding-top:10px'><p class='t2' style='color:#ff7920'>"+msg+"</p><p><a href='/licencevalidate/' class='yqQual'>完善资质</a></p></dd></dl>";
        return letter_content;
    },
    _layeropen : function(msg){
        var parentlayer = layer.open({
            type: 1,
            area: ["400px", "200px"],
            title: "企业认证",
            content: apply.getLetterContent1(msg)
        });
    },
    _invitCallback:function(applyids) { 
        window.location.reload();
    },
     _printresume:function(resumeid) {
		var url = '<?php echo smarty_function_get_url(array('rule'=>"/resume/htmlprint",'data'=>"resumeid='+resumeId+'"),$_smarty_tpl);?>
';
		$('#printIframe').attr("src", url);
    },
    _downresume:function(resumeid,applyid) {
        var url = '<?php echo smarty_function_get_url(array('rule'=>"/apply/DownLoad",'data'=>"resumeid='+resumeid+'&applyid='+applyid+'"),$_smarty_tpl);?>
';
    	$.showModal(url,{title:'请选择保存的文件格式'});
    },
    _downresumeword:function(ids,applyids){
    	var url = '<?php echo smarty_function_get_url(array('rule'=>"/resume/worddown",'data'=>"resumeid='+ids+'&applyid='+applyids+'&src=apply"),$_smarty_tpl);?>
';
		$(this).attr('href',url).attr('target','_blank');
    },
    _downresumehtml:function(ids,applyids) {
    	var url = '<?php echo smarty_function_get_url(array('rule'=>"/resume/htmldown",'data'=>"resumeid='+ids+'&applyid='+applyids+'&src=apply"),$_smarty_tpl);?>
';
        $(this).attr('href',url).attr('target','_blank');
    },
    _downresumeExcel:function(ids) {
    	var url = '<?php echo smarty_function_get_url(array('rule'=>"/excel/index/",'data'=>"resumeid='+ids+'"),$_smarty_tpl);?>
';
    	$(this).attr('href',url).attr('target','_blank');
    },
    _downresumePdf:function(ids) {
    	var url = '<?php echo smarty_function_get_url(array('rule'=>"/resume/pdfdown/",'data'=>"resumeid='+ids+'"),$_smarty_tpl);?>
';
    	$(this).attr('href',url).attr('target','_blank');
    },
    selectApply:function(){
        var checkboxs = $('.rMentLitBg').find('input[name="chkapply"]:checked'),
        applyids = [];
        for(var i=0,len=checkboxs.length;i<len;i+=1) {
            applyids.push($(checkboxs[i]).val());
        } 	
        return applyids;
    },
    selectUserName:function(){
        var checkboxs = $('.rMentLitBg').find('input[name="chkapply"]:checked'),
        usernames = [];
        for(var i=0,len=checkboxs.length;i<len;i+=1) {
            usernames.push($(checkboxs[i]).attr('data-name'));
        } 	
        return usernames;
    },
    //转发到邮箱
    _sendEmail:function(resumeid,applyid){
    	$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/resume/wordsend",'data'=>"resumeid='+resumeid+'&applyid='+applyid+'&src=apply"),$_smarty_tpl);?>
',{title:'转发到邮箱'});
    },
    selectResume:function(){
        var checkboxs = $('.rMentLitBg').find('input[name="chkapply"]:checked'),
        resumeids = [];
        for(var i=0,len=checkboxs.length;i<len;i+=1) {
            resumeids.push($(checkboxs[i]).attr('data-resumeid'));
        } 	
        return resumeids;
    },
    //设置为已读
    _setRead : function(applyids) {
        // 设置已读
        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/apply/SetRead/"),$_smarty_tpl);?>
'+'applyid-'+applyids+'-v-'+Math.random(),function(result){
            if (result.success) {
                $(".rMentLitBg input[name='chkapply']:checked").each(function() {
                    var ban = $(this).parent().next("em");
                    ban.html("待处理").removeClass("hue1").addClass("hue2");
                });
            } else {
                // 设置失败
                $.anchorMsg(result.error, {icon: 'fail'});
            }
        });
    },
    submit : function(job_id, invite_time, keyword, account_id,son_account_id) {
        if (keyword =='输入姓名或简历编号') {
            keyword ="";
        }

        var data = [];
        data.push("status=1");
        if (job_id != '') {
            data.push("job_id=" + job_id);
        }

        if (account_id)
            data.push("account_id=" + account_id);

        if (son_account_id)
            data.push("son_account_id=" + son_account_id);

        if (invite_time != '') {
            data.push("invite_time=" + invite_time);
        }

        if (keyword != '') {
            data.push("keyword=" + keyword);
            data.push("search_model=1");
        }

        data.push('search=' + 1);
        if (data.length > 0) {
            window.location.href = '<?php echo smarty_function_get_url(array('rule'=>"/apply/index/"),$_smarty_tpl);?>
' + "?" + data.join("&");
        } else {
            window.location.href = '<?php echo smarty_function_get_url(array('rule'=>"/apply/index/"),$_smarty_tpl);?>
'
        }
    },
    refresh : function() {
        window.location.reload();
    },
    //已通知面试 添加面试记录
    hasInvite:function(applyid){
        var url = '<?php echo smarty_function_get_url(array('rule'=>"/apply/hasInvite/",'data'=>"applyid='+applyid+'"),$_smarty_tpl);?>
';
        $.showModal(url,{title:'标记为“已邀请面试”'});
    },
    _deleteapply:function(ids) {
        // 删除求职申请	
        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/apply/DeleteApply/"),$_smarty_tpl);?>
'+'op-del-ids-'+ids+'-v-'+Math.random(),function(result){
            if (result.success) {
                $.anchorMsg('已放入回收站');
                window.location.reload();
            } else {
                $.anchorMsg(result.error, {icon: 'fail'}); 
            }
         });
    }
}
    
apply.init();
$.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/account/twodimensioncode/"),$_smarty_tpl);?>
',function(result){
    if(result&&result.status) {
        var src = result.data.codeurl;
        $('#ewmBox img').attr('src',src);
        $('#ewmBox').show();
    }
});

$("#ewmBox,#ewmBox1").find("a").click(function(){$(this).parents(".ewmBox").hide();return false;});
</script>
<!--2019.6.3更新-->
<script>
	var resumeCompleteDialog;
    hbjs.use('@hbCommon, @jobDialog, @validator, @confirmBox', function(m) {

        var $ = m['product.hbCommon'].extend(m['cqjob.jobDialog']);
        var Dialog = m['widge.overlay.hbDialog'];
        var confirmBox = m['widge.overlay.confirmBox'];

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
	})
</script>
<script  type="text/javascript">
    var action_url = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
';
    action_dom = [
        ['#tstDropJobPeople', 217],
        ['#tstDropJob', 218],
        ['#showStopJob', 219],
        ['.invite_timeall', 220],
        ['.invite_timeday', 221],
        ['.invite_time1', 222],
        ['.invite_time7', 223],
        ['.invite_time30', 224],
        ['.invite_time90', 225],
        ['.savePc', 226],
        ['.sendEmail', 227],
        ['.goRecycle', 228],
        ['#keyword', 229],
        ['.rMentLink', 230],
//        ['.sendToWorkmate', 231],
//        ['.chatonechat', 232],
        ['.chatonechat22', 232],
        ['.sendOffer', 233]
    ];
</script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'action.js'),$_smarty_tpl);?>
"></script>
<!--2019.6.3更新 完-->
<?php $_template = new Smarty_Internal_Template("chat/chat.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</body>
</html>

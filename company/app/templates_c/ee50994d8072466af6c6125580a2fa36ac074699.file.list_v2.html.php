<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:40:45
         compiled from "app\templates\resume/download/list_v2.html" */ ?>
<?php /*%%SmartyHeaderCode:321655e7038ad68f7b9-92809597%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ee50994d8072466af6c6125580a2fa36ac074699' => 
    array (
      0 => 'app\\templates\\resume/download/list_v2.html',
      1 => 1584332292,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '321655e7038ad68f7b9-92809597',
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
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'layer.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'WdatePicker.js'),$_smarty_tpl);?>
"></script>
<style>
.tips{position:relative; display:block; float:right;}
.tips i.hbFntWes{font-size:16px; margin-left:5px;}
.tipBox{position:absolute;right:-12px;top:21px;color:#444;background:#fff; border:1px solid #dadada; padding:5px 0;zoom:1; line-height:22px;}
.tipBox ul li a{color:#444; width:85px; display:block; padding:0 10px;}
.tipBox ul li a:hover{color:#00aaff; background:#f5f5f5;}
.tipBox ul li.rLnk{position:relative; z-index:1;}
.tipBox ul li.rLnk .downCmt{position:absolute; left:-28px; top:23px; background:#fff; border:1px solid #dadada; width:160px; font-size:12px;padding:10px; zoom:1;}
.tipBox ul li.rLnk .downCmt i.hbIconMoon{font-size:14px; margin-right:5px;}
.tipBox ul li.rLnk a.Un{cursor:text; color:#999;}
.tipBox ul li.rLnk a.Un:hover{color:#999;}
.Wdate{ height: 28px; border: 1px solid #ccc;}
.subMetx{ padding:20px 0 10px 20px!important;}
.subMetx .reload {
    width: 80px;
    height: 28px;
    background: #00c0c7;
    display: inline-block;
    float: left;
    font-size: 14px;
    color: #fcfcfc;
    text-align: center;
    line-height:28px;
    border-radius: 5px;
}
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

<div class="resumentNbg" style="overflow: inherit;">

<!---20151208 微信二维码 start -->
<style>
    .content{position: relative}
    .ewmBox{display: none;position: absolute;right:-180px;top:0px;width:160px;background: #fff;border:1px solid #dedede;text-align: center;padding:30px 0;font-size:16px;color:#333;font-family:"微软雅黑"}
    .ewmBox img{border:1px solid #e9e9e9;margin-bottom: 5px;width: 118px;height: 118px;}
    .ewmBox a{display: inline-block;width:24px;height:24px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/weixin/close2.jpg) no-repeat;position: absolute;top:0px;right:0px}
    .ewmBox a:hover{background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/weixin/close2_hover.jpg) no-repeat}
    .sendTo_img{ display:none;position:absolute; top:10px; left:110px;width:150px; background:#fff; overflow:hidden; border:1px solid #ddd; text-align:center; color:#333;}
     .sendTo_img span, .sendTo_img img, .sendTo_img b{ display:block; margin:0 auto; line-height:20px;font-size:12px; font-weight:normal;}
     .sendTo_img span{padding-top:10px; color:#f35a00;}
     .yqQual{height: 30px;background: #66bce4;font-size: 14px;color: #fff;line-height: 30px;border-radius: 2px;margin-right: 10px;width: 121px;display: inline-block;}
     .yqQual:hover{ background: #31a2d6; color: #fff;}
    .chatHuoyue{ display:inline-block; padding:0 6px; border-radius:2px; background:#00c0c7; color:#fff; margin-left:5px;font-size:12px; vertical-align: middle;}
</style>
<div class="ewmBox" id="ewmBox">
    <a href="javascript:void(0);" class="close"></a>
    <img src="" />
    <p>关注汇博招聘<br />随时随地筛选简历</p>
</div>
<!---20151208 微信二维码 end -->

<?php $_template = new Smarty_Internal_Template('resume/apply/nav.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par',"下载的简历"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<div class="rMentRt">
    <div class="rMenTit"><span>共<b><?php echo $_smarty_tpl->getVariable('totalSize')->value;?>
</b>份简历</span></div>
    <div class="rMetSelt">
    	<div class="subMetx"  style="float: left">
            <span style="padding-top:5px;">合适职位：</span>
            <div class="job"><span id="tstDropJob" class="drop zindex"></span></div>
            <div class="clear"></div>
        </div>

        <div class="subMetx"  style="float: left">
            <span style="padding-top:5px;">获取日期：</span>
            <input style="width: 150px" class="Wdate text" type="text" onClick="WdatePicker({onpicked:function(){$('.j_time_search_btn').click();},oncleared:function(){$('.j_time_search_btn').click();}})" id="start_time" name="start_time" readonly value="<?php echo $_smarty_tpl->getVariable('s_time')->value;?>
" />
            <b> ~ </b>
            <input style="width: 150px" class="Wdate text" type="text" onClick="WdatePicker({onpicked:function(){$('.j_time_search_btn').click();},oncleared:function(){$('.j_time_search_btn').click();}})" id="end_time" name="end_time" readonly value="<?php echo $_smarty_tpl->getVariable('e_time')->value;?>
" />
            <div class="clear"></div>
        </div>

        <div class="subMetx" style="padding-bottom: 0;" >
            <span style="padding-top:5px;">下载人：</span>
            <div class="job"><span id="tstAccount" class="drop zindex"></span></div>
            <span style="cursor: pointer" class="reload">确定</span>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
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
        <?php  $_smarty_tpl->tpl_vars['downs'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('downresumes')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['downs']->key => $_smarty_tpl->tpl_vars['downs']->value){
?>
            <div class="rMentLit <?php if (isset($_smarty_tpl->tpl_vars['downs']->value['notopen'])||isset($_smarty_tpl->tpl_vars['downs']->value['isdelete'])){?>rMentLxgray<?php }?>" >
                    <div class="rMentLx <?php if (isset($_smarty_tpl->tpl_vars['downs']->value['notopen'])||isset($_smarty_tpl->tpl_vars['downs']->value['isdelete'])){?>rMentLxgray<?php }?>">
                    <label>
                        <?php if (!isset($_smarty_tpl->tpl_vars['downs']->value['notopen'])&&!isset($_smarty_tpl->tpl_vars['downs']->value['isdelete'])){?><input name="chkdown" type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['downs']->value['download_id'];?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['downs']->value['user_name'];?>
" data-resumeid="<?php echo $_smarty_tpl->tpl_vars['downs']->value['resume_id'];?>
" /><?php }?><b title="<?php echo $_smarty_tpl->tpl_vars['downs']->value['tagname'];?>
"><?php if (!empty($_smarty_tpl->tpl_vars['downs']->value['tagname'])){?>适合职位：<?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['downs']->value['tagname'],18,'utf-8','','…');?>
<?php }?></b>
                        <span title="下载时间"><?php echo $_smarty_tpl->tpl_vars['downs']->value['down_time'];?>
</span>
                    </label>
                    <em class="<?php if (isset($_smarty_tpl->tpl_vars['downs']->value['notopen'])||isset($_smarty_tpl->tpl_vars['downs']->value['isdelete'])){?>hue4<?php }elseif($_smarty_tpl->tpl_vars['downs']->value['is_invite']==1){?>hue3<?php }else{ ?>hueNone<?php }?>"><?php if (isset($_smarty_tpl->tpl_vars['downs']->value['notopen'])){?>简历未公开<?php }elseif(isset($_smarty_tpl->tpl_vars['downs']->value['isdelete'])){?>简历已删除<?php }elseif($_smarty_tpl->tpl_vars['downs']->value['is_invite']==1){?>已邀请 <?php }else{ ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php }?></em>
                      <?php if (!empty($_smarty_tpl->tpl_vars['downs']->value['phone'])&&!isset($_smarty_tpl->tpl_vars['downs']->value['notopen'])&&!isset($_smarty_tpl->tpl_vars['downs']->value['isdelete'])){?><p><i></i><?php echo $_smarty_tpl->tpl_vars['downs']->value['phone'];?>
</p><?php }?>                                                                                                                      
                    </div>
                <div class="rMentLv <?php if (isset($_smarty_tpl->tpl_vars['downs']->value['notopen'])||isset($_smarty_tpl->tpl_vars['downs']->value['isdelete'])){?>rMentLvgray<?php }?>" data-name="<?php echo $_smarty_tpl->tpl_vars['downs']->value['user_name'];?>
"  data-downid ="<?php echo $_smarty_tpl->tpl_vars['downs']->value['download_id'];?>
" data-resumeid ="<?php echo $_smarty_tpl->tpl_vars['downs']->value['resume_id'];?>
" data-personid="<?php echo $_smarty_tpl->tpl_vars['downs']->value['person_id'];?>
">
                    <a href='<?php if (isset($_smarty_tpl->tpl_vars['downs']->value["notopen"])||isset($_smarty_tpl->tpl_vars['downs']->value["isdelete"])){?>javascript:<?php }else{ ?><?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-src-download-resumeid-<?php echo $_smarty_tpl->tpl_vars['downs']->value['resume_id'];?>
<?php }?>' <?php if (isset($_smarty_tpl->tpl_vars['downs']->value["notopen"])||isset($_smarty_tpl->tpl_vars['downs']->value["isdelete"])){?><?php }else{ ?>target="_blank"<?php }?> class="rMentLink">
                        <div class="mImgBg">
                            <p>
                                <img class="mImg" src="<?php if ($_smarty_tpl->tpl_vars['downs']->value['photo']){?><?php echo $_smarty_tpl->tpl_vars['downs']->value['photo'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/headportrait.png<?php }?>"  />
                            </p>
                        </div>
						</a>
                        <div id="mark<?php echo $_smarty_tpl->tpl_vars['downs']->value['resume_id'];?>
">
                            <p class="mTit1">
								<a href='<?php if (isset($_smarty_tpl->tpl_vars['downs']->value["notopen"])||isset($_smarty_tpl->tpl_vars['downs']->value["isdelete"])){?>javascript:<?php }else{ ?><?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-src-download-resumeid-<?php echo $_smarty_tpl->tpl_vars['downs']->value['resume_id'];?>
<?php }?>' <?php if (isset($_smarty_tpl->tpl_vars['downs']->value["notopen"])||isset($_smarty_tpl->tpl_vars['downs']->value["isdelete"])){?><?php }else{ ?>target="_blank"<?php }?> class="rMentLink">
								<b><?php echo $_smarty_tpl->tpl_vars['downs']->value['user_name'];?>
</b></a>
                                <?php if (!empty($_smarty_tpl->tpl_vars['downs']->value['sex'])){?><?php echo $_smarty_tpl->tpl_vars['downs']->value['sex'];?>
/<?php }?>
                                <?php if (!empty($_smarty_tpl->tpl_vars['downs']->value['start_work'])){?><?php echo $_smarty_tpl->tpl_vars['downs']->value['start_work'];?>
/<?php }?>
                                <?php if (!empty($_smarty_tpl->tpl_vars['downs']->value['age'])){?><?php echo $_smarty_tpl->tpl_vars['downs']->value['age'];?>
/<?php }?>
                                <?php if (!empty($_smarty_tpl->tpl_vars['downs']->value['area'])){?><?php echo $_smarty_tpl->tpl_vars['downs']->value['area'];?>
<?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['downs']->value['remark']&&!isset($_smarty_tpl->tpl_vars['downs']->value['notopen'])&&!isset($_smarty_tpl->tpl_vars['downs']->value['isdelete'])){?>
                                <i class='remark_contr'></i><span class='remark_show' ><?php echo $_smarty_tpl->tpl_vars['downs']->value['remark'];?>
<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/hbtip2.png" width="5" height="22"></span>
                                <?php }?>
								<label class="chatOneChat md_chat <?php if (!$_smarty_tpl->tpl_vars['downs']->value['chat_status']){?>notOffenUse<?php }?>" data-job-effect="<?php if ($_smarty_tpl->tpl_vars['downs']->value['is_job_effect']){?>1<?php }else{ ?>0<?php }?>" data-resume-id="<?php echo $_smarty_tpl->tpl_vars['downs']->value['resume_id'];?>
" data-job-id="<?php echo $_smarty_tpl->tpl_vars['downs']->value['job_id'];?>
"  data-need-download="0" data-notice-status="<?php echo $_smarty_tpl->tpl_vars['downs']->value['chat_status'];?>
" data-apply-id="<?php echo $_smarty_tpl->tpl_vars['downs']->value['apply_id'];?>
">立即沟通</label>
								<?php if ($_smarty_tpl->tpl_vars['downs']->value["is_active"]){?>
									<em title="该求职者聊天活跃"  class="chatHuoyue">活跃</em>
								<?php }?>
                            </p>
							<a href='<?php if (isset($_smarty_tpl->tpl_vars['downs']->value["notopen"])||isset($_smarty_tpl->tpl_vars['downs']->value["isdelete"])){?>javascript:<?php }else{ ?><?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-src-download-resumeid-<?php echo $_smarty_tpl->tpl_vars['downs']->value['resume_id'];?>
<?php }?>' <?php if (isset($_smarty_tpl->tpl_vars['downs']->value["notopen"])||isset($_smarty_tpl->tpl_vars['downs']->value["isdelete"])){?><?php }else{ ?>target="_blank"<?php }?> class="rMentLink">
                             <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['downs']->value['school'];?>
<?php $_tmp1=ob_get_clean();?><?php if (!empty($_tmp1)){?><p class="mTit3">
                                 <?php echo $_smarty_tpl->tpl_vars['downs']->value['school'];?>
<span>|</span>
                                 <?php if (!empty($_smarty_tpl->tpl_vars['downs']->value['major_desc'])){?><?php echo $_smarty_tpl->tpl_vars['downs']->value['major_desc'];?>
<span>|</span><?php }?>
                                 <?php if (!empty($_smarty_tpl->tpl_vars['downs']->value['school_degree'])){?><?php echo $_smarty_tpl->tpl_vars['downs']->value['school_degree'];?>
<?php }?></p>
                             <?php }?>
                             <?php if (!empty($_smarty_tpl->tpl_vars['downs']->value['worklist'])){?>
                             <p class="mTit2"><b><?php echo $_smarty_tpl->tpl_vars['downs']->value['worklist'][0]['station'];?>
</b><span>|</span><?php echo $_smarty_tpl->tpl_vars['downs']->value['worklist'][0]['company_name'];?>
<span>|</span><?php echo $_smarty_tpl->tpl_vars['downs']->value['worklist'][0]['start_time'];?>
-<?php echo $_smarty_tpl->tpl_vars['downs']->value['worklist'][0]['end_time'];?>
</p>
                             <?php }?>
							 </a>
                        </div>
                    
                        <?php if (isset($_smarty_tpl->tpl_vars['downs']->value['notopen'])||isset($_smarty_tpl->tpl_vars['downs']->value['isdelete'])){?>
                        <div class="rMentLinkv">
                            <a href="javascript:;"></a>
                            <a href="javascript:;" class="mTit4 deleteresume">删除</a>
                            <a href="javascript:;"></a>
                        </div>
                        <?php }else{ ?>
                         <div class="rMentLinkv">
                            <a href="javascript:;" class="opinvite">邀请面试</a>
                            <a href="javascript:;" ></a>
                            <a href="javascript:;" class="opremark">备注</a>
                             <?php if ($_smarty_tpl->tpl_vars['downs']->value['invite_id']){?>
                             <a href="javascript:;" ></a>
                             <a target="_blank" href="/offertemplate/index/invite_id-<?php echo $_smarty_tpl->tpl_vars['downs']->value['invite_id'];?>
" class="">发送offer</a>
                             <?php }?>
                         </div>
                         <?php }?>
                        <div class="clear"></div>
                    <?php if (!isset($_smarty_tpl->tpl_vars['downs']->value["notopen"])&&!isset($_smarty_tpl->tpl_vars['downs']->value["isdelete"])){?>
                    <div class="sendTo_img"><span>把简历转发给职位负责人</span><img src="" data-source-img="<?php echo smarty_function_get_url(array('rule'=>'/apply/SendToWorkMatePng/'),$_smarty_tpl);?>
?src=download&resume_id=<?php echo $_smarty_tpl->tpl_vars['downs']->value['resume_id'];?>
"/><b>用汇博企业APP<br />扫码转发简历</b></div>
                    <?php }?>
                </div>
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
</div>
<div class="j_time_search_btn" style="display: none"></div>
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
var s_time = '<?php echo $_smarty_tpl->getVariable('s_time')->value;?>
',e_time='<?php echo $_smarty_tpl->getVariable('e_time')->value;?>
';

var start_times = {
//    skin: 'whyGreen',
    dateFmt: 'yyyy-MM-dd',
    maxDate: '%y-%M-{%d}',
    minDate: "%y-%M-{%d-90}",
    readOnly: true,
    onpicking: function (datas) {

        setTimeout(function(){
            if($('#start_time').val() == s_time){
                return false;
            }
        },500)
    }
};
var end_times = {
//    skin: 'whyGreen',
    dateFmt: 'yyyy-MM-dd',
    maxDate: '%y-%M-{%d}',
    minDate: "%y-%M-{%d-90}",
    readOnly: true,
    onpicking: function (datas) {
        setTimeout(function(){
            if($('#end_time').val() == e_time){
                return false;
            }
        },500)
    }
};


$(".sendToWorkmate").on("click",function(){
    if($(this).parent().next('.sendTo_img').is(':visible')){
        $('.sendTo_img').hide();
    }else{
        $('.sendTo_img').hide();
        var img = $(this).parent().next('.sendTo_img').find("img");
        if(img.attr("src") == '')
            img.attr("src", img.attr("data-source-img"));
        $(this).parent().next('.sendTo_img').show();
    }
    
});
 $.setIndex("zindex");//为需要赋层级设置的元素设置class为zindex
$('#tstDropJob').droplist({
    defaultTitle:'全部适合职位',
    style:'width:178px;',
    noSelectClass:'gray',
    inputWidth:170,
    width:128,
    hddName:'tag_id',
    items:<?php echo $_smarty_tpl->getVariable('companytags')->value;?>
,
    selectValue:'<?php echo $_smarty_tpl->getVariable('tag_id')->value;?>
',
    maxScroll:10
});
$('#tstAccount').droplist({
    defaultTitle:'全部下载人',
    style:'width:178px;',
    noSelectClass:'gray',
    inputWidth:170,
    width:128,
    hddName:'a_id',
    items:<?php echo $_smarty_tpl->getVariable('accounttags')->value;?>
,
    selectValue:'<?php echo $_smarty_tpl->getVariable('accountid')->value;?>
',
    maxScroll:10
});
$('.reload').click(function(){
	download.submit(2);
});

var download ={
    init:function() {
         //回车事件
       $("#keyword").keydown(function(e){
            if(e.keyCode == 13){
                $("#onSubmit").click();
            }
        });
        // 水印 
        $('#keyword').watermark('输入姓名或简历编号');
       //选中的操作
       $(".btnOperate").click(function(e){
            if($(this).next('.tipBox').is(':visible')) {
                $(this).next('.tipBox').hide();	
            }else{
                $(this).next('.tipBox').show();
            }
            e.stopPropagation();
       });
       $('body').click(function(){
            $('.tips .tipBox').hide();
        });	
       //显示备注
//        $(".remark_contr").mouseover(function(e){
//            e.preventDefault();
//            
//        }).mouseout(function(e){
//            e.preventDefault();
//            $(this).next(".remark_show").hide();
//        });
        $(".remark_contr").live("mouseover",function(){
            $(this).next(".remark_show").addClass("mTitcut2");
        }).live("mouseout",function(){
            $(this).next(".remark_show").removeClass("mTitcut2");
        })
       //批量保存到电脑
       $('.savePc').click(function(e){
            e.preventDefault();
            var resumes = download.selectResume();
            if(resumes.length<=0) {
                    $.anchor('请选择下载的简历',{icon:'info'});
                    return;
            }

           $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
               if(!json.status){
                   if(json.code == 701) {
                       var parentlayer = layer.open({
                           type: 1,
                           area: ["400px", "200px"],
                           title: "企业认证",
                           content: getLetterContent(json.msg)
                       });

                   }
               }else{
                   download._downresume(resumes.join(','));
               }
           });
        });
        //全选 反选
        $(".resuemSelectAll").click(function(){
            if($(this).is(':checked')) {
                $('.rMentLit label input[name="chkdown"]').attr('checked','checked');	
            }else {
                $('.rMentLit label input[name="chkdown"]:checked').removeAttr('checked');
            }
        });
        //      单选
		$('.rMentLit label input[name="chkdown"]').click(function(){
			if(!$(this).prop('checked')){
				$(".resuemSelectAll").removeAttr('checked');
			}
			var chkapplyLengthAll = $('.rMentLit label input[name="chkdown"]').length;
			var chkapplyLength = $('.rMentLit label input[name="chkdown"]:checked').length;
			if(chkapplyLength == chkapplyLengthAll){
				$(".resuemSelectAll").attr('checked','checked');
			}
			
		});
        //批量转发到邮箱
        $('.sendEmail').click(function(e){
            e.preventDefault();
            var resumes = download.selectResume();
            if(resumes.length<=0) {
                    $.anchor('请选择下载的简历',{icon:'info'});
                    return;
            }
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        var parentlayer = layer.open({
                            type: 1,
                            area: ["400px", "200px"],
                            title: "企业认证",
                            content: getLetterContent(json.msg)
                        });

                    }
                }else{
                    download._sendEmail(resumes.join(','));
                }
            });


        });
        //批量放入回收站
        $(".goRecycle").click(function(e){
            e.preventDefault();
            var downloads = download.selectDownids();
            if(downloads.length<=0) {
                    $.anchor('请选择下载的简历',{icon:'info'});
                    return;
            }
            var names =download.selectUserName(),
            val = cookieutility.get('deletedownload');
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        var parentlayer = layer.open({
                            type: 1,
                            area: ["400px", "200px"],
                            title: "企业认证",
                            content: getLetterContent(json.msg)
                        });

                    }
                }else{
                    if(val == 'true'){
                        download._deletedownload(downloads.join(','));
                    }else {
                        $.showModal(encodeURI('<?php echo smarty_function_get_url(array('rule'=>"/download/Deletedownload/",'data'=>"names='+names.join(',')+'&ids='+downloads.join(',')+'&v='+Math.random()+'"),$_smarty_tpl);?>
'),{title:'放入回收站'});
                    }
                }
            });


        });
        //删除简历
        $(".oprecycle").click(function(e){
            var download_id = $(this).parents(".rMentLv").attr("data-downid");
            var name = $(this).parents(".rMentLv").attr("data-name");
            val = cookieutility.get('deletedownload');
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        var parentlayer = layer.open({
                            type: 1,
                            area: ["400px", "200px"],
                            title: "企业认证",
                            content: getLetterContent(json.msg)
                        });

                    }
                }else{
                    if(val == 'true'){
                        download._deletedownload(download_id);
                    }else {
                        $.showModal(encodeURI('<?php echo smarty_function_get_url(array('rule'=>"/download/Deletedownload/",'data'=>"names='+name+'&ids='+download_id+'&v='+Math.random()+'"),$_smarty_tpl);?>
'),{title:'删除'});
                    }
                }
            });

        });
        $(".deleteresume").click(function(e){
            var download_id = $(this).parents(".rMentLv").attr("data-downid");
            var name = $(this).parents(".rMentLv").attr("data-name");
            val = cookieutility.get('deletedownload');
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        var parentlayer = layer.open({
                            type: 1,
                            area: ["400px", "200px"],
                            title: "企业认证",
                            content: getLetterContent(json.msg)
                        });

                    }
                }else{
                    if(val == 'true'){
                        download._deletedownload(download_id);
                    }else {
                        $.showModal(encodeURI('<?php echo smarty_function_get_url(array('rule'=>"/download/Deletedownload/",'data'=>"names='+name+'&ids='+download_id+'&v='+Math.random()+'"),$_smarty_tpl);?>
'),{title:'删除'});
                    }
                }
            });

        });

        function getLetterContent(msg){
            var letter_content = "<dl id='yaoqing-alert' class='yaoqing-alert clearfix'><dd style='padding-top:10px'><p class='t2' style='color:#ff7920'>"+msg+"</p><p><a href='/licencevalidate/' class='yqQual'>完善资质</a></p></dd></dl>";
            return letter_content;
        }

        // 单个邀请
        $('.opinvite').click(function(e){
            var resume_id = $(this).parents(".rMentLv").attr("data-resumeid");
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ '-v-' + Math.random(), function(json){
                if(!json.status){
                    if(json.code == 701) {
                        var parentlayer = layer.open({
                            type: 1,
                            area: ["400px", "200px"],
                            title: "企业认证",
                            content: getLetterContent(json.msg)
                        });

                    }
                }else{
                    download._invitesingle(resume_id);
                    e.preventDefault();
                }
            });

        });
        // 单个备注
        $('.opremark').click(function(e){
                 var resume_id = $(this).parents(".rMentLv").attr("data-resumeid");
                 var tree = $(this).parents(".rMentLv").find(".mTit1");
                 download._updateRemark(resume_id);
                 e.preventDefault();
        });
        //单个保存到电脑
        $(".opdown").click(function(e){
            e.preventDefault();
            var resume_id = $(this).parents(".rMentLv").attr("data-resumeid");
            var download_id = $(this).parents(".rMentLv").attr("data-downid");
            download._downresume(resume_id,download_id);
        });
        //单个转发到邮箱
        $(".opsendmail").click(function(e){
             e.preventDefault();
            var resume_id = $(this).parents(".rMentLv").attr("data-resumeid");
            download._sendEmail(resume_id);
        });
        //搜索
        $("#onSubmit").click(function(e){
             e.preventDefault();
             download.submit(1);
        });
    },
    _invitCallback:function(){
        window.location.reload();
    },
     _printresume:function(resumeid) {
		var url = '<?php echo smarty_function_get_url(array('rule'=>"/resume/htmlprint",'data'=>"resumeid='+resumeId+'"),$_smarty_tpl);?>
';
		$('#printIframe').attr("src", url);
    },
    _downresume:function(resumeid,downloadid) {
         var url = '<?php echo smarty_function_get_url(array('rule'=>"/download/DownLoad",'data'=>"resumeid='+resumeid+'&downloadid='+downloadid+'"),$_smarty_tpl);?>
';
    	$.showModal(url,{title:'请选择保存的文件格式'});
    },
    _downresume:function(resumeid,downloadid) {
        var url = '<?php echo smarty_function_get_url(array('rule'=>"/download/DownLoad",'data'=>"resumeid='+resumeid+'&downloadid='+downloadid+'"),$_smarty_tpl);?>
';
    	$.showModal(url,{title:'请选择保存的文件格式'});
    },
    _downresumeword:function(ids,downloadids){
    	var url = '<?php echo smarty_function_get_url(array('rule'=>"/resume/worddown",'data'=>"resumeid='+ids+'"),$_smarty_tpl);?>
';
		$(this).attr('href',url).attr('target','_blank');
    },
    _downresumehtml:function(ids) {
    	var url = '<?php echo smarty_function_get_url(array('rule'=>"/resume/htmldown",'data'=>"resumeid='+ids+'"),$_smarty_tpl);?>
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
    _sendEmail:function(resumeid){
    	$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/resume/wordsend",'data'=>"resumeid='+resumeid+'"),$_smarty_tpl);?>
',{title:'转发到邮箱'});
    },
    _deletedownload:function(ids) {
        // 删除下载的简历	
        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/download/Deletedownload/"),$_smarty_tpl);?>
'+'op-del-ids-'+ids+'-v-'+Math.random(),function(result){
                     if(result.success) {
                             $.anchorMsg('已放入回收站');
                              download.refresh();
                     }else {
                             $.anchorMsg(result.error, { icon: 'fail' }); 
                     }
         });
    },
    _updateRemark:function(resumeid) {
        // 更新备注
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/resumeremark/index/"),$_smarty_tpl);?>
resume_id-'+resumeid+'-v-'+Math.random(),{title:'备注',onclose:function(){
                // 更新备注
                $.getJSON("<?php echo smarty_function_get_url(array('rule'=>"/resumeremark/ResumeRemark/"),$_smarty_tpl);?>
"+'-resumeid-'+resumeid+'-v-'+Math.random(),function(result){
                    if(result.remark !='' && result.updatetime !=''){
                        var c = result.remark+"&nbsp;"+result.updatetime;
                        var html ='<span  class="remark_show">'+c+'<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/hbtip2.png" width="5" height="22"></span>';
                        var m = $("#mark"+resumeid+" .mTit1").find(".remark_contr");
                        var html_top = "<i class='remark_contr'></i>";
                        if(m.length>0){
                            $("#mark"+resumeid+" .mTit1").find(".remark_show").remove();
                            $("#mark"+resumeid+" .mTit1").append(html);
                        }else{
                              $("#mark"+resumeid+" .mTit1").append(html_top+html);
                        }
                    }else{
                        $("#mark"+resumeid+" .mTit1").find(".remark_show").remove();
                        $("#mark"+resumeid+" .mTit1").find(".remark_contr").remove();
                    }
                });
        }});
    },
    //单个邀请
    _invitesingle:function(resumeid) {
            $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/invite/invitesingleshow/",'data'=>"resumeID='+resumeid+'"),$_smarty_tpl);?>
-v-'+Math.random(),{title:'邀请面试 ',onclose:function(){
                download._invitCallback(resumeid);
            }});	
    },
    selectUserName:function(){
        var checkboxs = $('.rMentLitBg').find('input[name="chkdown"]:checked'),
        usernames = [];
        for(var i=0,len=checkboxs.length;i<len;i+=1) {
            usernames.push($(checkboxs[i]).attr('data-name'));
        } 	
        return usernames;
    },
    selectDownids:function(){
        var checkboxs = $('.rMentLitBg').find('input[name="chkdown"]:checked'),
        downids = [];
        for(var i=0,len=checkboxs.length;i<len;i+=1) {
            downids.push($(checkboxs[i]).val());
        } 	
        return downids;
    },
//    //转发到邮箱
//    _sendEmail:function(resumeid,applyid){
//    	$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/resume/wordsend",'data'=>"resumeid='+resumeid+'&applyid='+applyid+'&src=apply"),$_smarty_tpl);?>
',{title:'转发到邮箱'});
//    },
    selectResume:function(){
        var checkboxs = $('.rMentLitBg').find('input[name="chkdown"]:checked'),
        resumeids = [];
        for(var i=0,len=checkboxs.length;i<len;i+=1) {
            resumeids.push($(checkboxs[i]).attr('data-resumeid'));
        } 	
        return resumeids;
    },

    submit:function(searchmod){
         var tag_id = $("#tag_id").val();
         var account_id = $("#a_id").val();
         var keyword = $("#keyword").val();
         var s_time = $("#start_time").val();
         var e_time = $("#end_time").val();

        if(keyword =='输入姓名或简历编号'){
            keyword ="";
        }
        var data =[];

        if(searchmod === 1){
            if(keyword !=''){
                data.push("keyword="+keyword);
                data.push("search_model=1");
            }
        }else{
            if(tag_id !=''){
                data.push("tag_id="+tag_id);
            }

            if(account_id !=''){
                data.push("a_id="+account_id);
            }

            if(s_time !=''){
                data.push("s_time="+s_time);
            }

            if(e_time !=''){
                data.push("e_time="+e_time);
            }


        }

        if(data.length >0){
            window.location.href = '<?php echo smarty_function_get_url(array('rule'=>"/download/index/"),$_smarty_tpl);?>
'+"?"+data.join("&");
        }else{
            window.location.href = '<?php echo smarty_function_get_url(array('rule'=>"/download/index/"),$_smarty_tpl);?>
'
        }   
    },
    refresh:function(){
        window.location.reload();
    }

}
    
download.init();
$.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/account/twodimensioncode/"),$_smarty_tpl);?>
',function(result){
    if(result&&result.status) {
        var src = result.data.codeurl;
        $('#ewmBox img').attr('src',src);
        $('#ewmBox').show();
    }
});

$("#ewmBox").find("a").click(function(){$(this).parents("#ewmBox").hide();return false;});
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
        ['#tstDropJob', 313],
        ['.j_time_search_btn', 314],
//        ['#end_time', 314],
        ['#tstAccount', 315],
        ['.savePc', 317],
        ['.sendEmail', 318],
        ['.goRecycle', 319],
        ['#keyword', 320],
        ['.rMentLink', 321],
//        ['.sendToWorkmate', 322],
        ['.md_chat', 323],
        ['.chatOneChat', 323],
        ['.opinvite', 324],
        ['.opremark', 325]
    ];
</script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'action.js'),$_smarty_tpl);?>
"></script>
<!--2019.6.3更新 完-->
<?php $_template = new Smarty_Internal_Template("chat/chat.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</body>
</html>

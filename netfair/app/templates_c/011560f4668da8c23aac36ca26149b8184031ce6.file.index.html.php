<?php /* Smarty version Smarty-3.0.7, created on 2020-03-26 13:25:15
         compiled from "app\templates\videohall/index.html" */ ?>
<?php /*%%SmartyHeaderCode:220425e7c3cbb914696-14957173%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '011560f4668da8c23aac36ca26149b8184031ce6' => 
    array (
      0 => 'app\\templates\\videohall/index.html',
      1 => 1585200131,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '220425e7c3cbb914696-14957173',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="Keywords" content="" />
    <meta name="Description" content="" />
    <title><?php echo $_smarty_tpl->getVariable('title')->value;?>
</title>
    <!–[if lt IE9]>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'html5.js'),$_smarty_tpl);?>
"></script>
    <![endif]–>
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'comback.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'icons.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'repcalendar.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'video_eng.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'netfair_video_eng.css'),$_smarty_tpl);?>
" />
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'version.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
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
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'global.js'),$_smarty_tpl);?>
"></script>
</head>
<body style="background: #e9e9e9 !important;">
<?php echo $_smarty_tpl->getVariable('head_data')->value;?>

 <?php echo $_smarty_tpl->getVariable('head_nav_data')->value;?>

<div class="videoEngMain">
    <div class="videoEngTab">
        <a href="javascript:void (0);"><em></em>视频招聘会列表</a>
    </div>
    <ul class="videoEngList">
         <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
        <li>
            <?php if (1==$_smarty_tpl->tpl_vars['val']->value['activity_ing_status']){?>
            <span class="engList1 engListCut1"><i></i>共有<em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_num'];?>
</em>家企业正进行视频面试</span>
            <?php }else{ ?>
            <span class="engList1"><i></i>已有<em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_num'];?>
</em>家企业报名本次招聘会</span>
            <?php }?>
            <?php if (!empty($_smarty_tpl->tpl_vars['val']->value['logo'])){?>
            <img src="<?php echo $_smarty_tpl->tpl_vars['val']->value['logo'];?>
" class="engList2" >
             <?php }else{ ?>
             <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/school/shuangxuan/shuangxuan_default_logo.jpg" class="engList2" >
            <?php }?>
            <p class="engList3">
                <span class="subEng1"><?php echo $_smarty_tpl->tpl_vars['val']->value['title'];?>
</span>
                <span class="subEng2">
                    <?php if ($_smarty_tpl->tpl_vars['val']->value['activity_ing_status']==1){?>
                        面试:<em><?php echo $_smarty_tpl->tpl_vars['val']->value['activity_ing_stime'];?>
-<?php echo $_smarty_tpl->tpl_vars['val']->value['activity_ing_etime'];?>
</em>
                    <?php }elseif($_smarty_tpl->tpl_vars['val']->value['activity_ing_status']==2){?>
                        报名：<em><?php echo $_smarty_tpl->tpl_vars['val']->value['enter_start_time'];?>
-<?php echo $_smarty_tpl->tpl_vars['val']->value['enter_end_time'];?>
</em>
                        面试:<em><?php echo $_smarty_tpl->tpl_vars['val']->value['activity_ing_stime'];?>
-<?php echo $_smarty_tpl->tpl_vars['val']->value['activity_ing_etime'];?>
</em>
                    <?php }else{ ?>
                        活动：<em><?php echo $_smarty_tpl->tpl_vars['val']->value['start_time'];?>
-<?php echo $_smarty_tpl->tpl_vars['val']->value['end_time'];?>
</em>
                    <?php }?>
                </span>
                <span class="subEng3"><?php echo $_smarty_tpl->tpl_vars['val']->value['superiority_info'];?>
</span>
            </p>
            <?php if (1==$_smarty_tpl->tpl_vars['val']->value['activity_ing_status']){?>
                <?php if ('-1'==$_smarty_tpl->tpl_vars['val']->value['company_status']){?>
                   <?php if (200==$_smarty_tpl->getVariable('check_company_info')->value['code']){?>
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/index/AddCompanyNet'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" class="enterHall">立即报名</a>
                   <?php }else{ ?>
                    <a href="javascript:void (0);"   class="enterHall no_check" data-sid="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
">立即报名</a>
                   <?php }?>
                <?php }elseif(0==$_smarty_tpl->tpl_vars['val']->value['company_status']){?>
                    <!--<a href="javascript:void (0);"   class="enterHall auditEnterHall">已报名成功，审核中</a>-->
                    <!--<span class="engList4" ><em style="color: red">已报名成功，审核中</em>>，1~2个工作日完成审核，短信通知您结果</span>-->
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/VideoInterviewHall'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" class="enterHall">进入会场</a>
                    <span class="engList4" ><em>已报名成功，审核中</em>1~2个工作日完成审核，短信通知您结果</span>
                 <?php }elseif(1==$_smarty_tpl->tpl_vars['val']->value['company_status']){?>
                     <a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/VideoInterviewHall'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" class="enterHall">进入会场</a>
                 <?php }else{ ?>
                    <a href="javascript:void (0);"   class="enterHall auditEnterHall">审核未通过</a>
                    <?php if ($_smarty_tpl->tpl_vars['val']->value['company_remark']){?><span class="engList4" title="原因：<?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['val']->value['company_remark'],35,'utf-8','','...');?>
；如有问题可咨询<?php echo $_smarty_tpl->tpl_vars['val']->value['company_link_mobile'];?>
">原因：<?php echo $_smarty_tpl->tpl_vars['val']->value['company_remark'];?>
；如有问题可咨询<?php echo $_smarty_tpl->tpl_vars['val']->value['company_link_mobile'];?>
</span><?php }?>
                 <?php }?>
            <?php }elseif(2==$_smarty_tpl->tpl_vars['val']->value['activity_ing_status']){?>
                    <?php if ('-1'==$_smarty_tpl->tpl_vars['val']->value['company_status']){?>
                        <?php if (200==$_smarty_tpl->getVariable('check_company_info')->value['code']){?>
                            <a href="<?php echo smarty_function_get_url(array('rule'=>'/index/AddCompanyNet'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" class="enterHall">立即报名</a>
                        <?php }else{ ?>
                            <a href="javascript:void (0);"   class="enterHall no_check" data-sid="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
">立即报名</a>
                        <?php }?>
                    <?php }?>
                    <?php if (0==$_smarty_tpl->tpl_vars['val']->value['company_status']){?>
                    <a href="javascript:void (0);" class="enterHall auditEnterHall">已报名成功，审核中</a>
                    <span class="engList4" ><em>完成审核后，短信通知您结果</span>
                    <?php }?>
                    <?php if (1==$_smarty_tpl->tpl_vars['val']->value['company_status']){?>
                      <a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/VideoInterviewHall'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" class="enterHall">进入会场</a>
                    <?php }?>
                    <?php if (2==$_smarty_tpl->tpl_vars['val']->value['company_status']){?>
                        <a href="javascript:void (0);"   class="enterHall auditEnterHall">审核未通过</a>
                        <?php if ($_smarty_tpl->tpl_vars['val']->value['company_remark']){?><span class="engList4" title="原因：<?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['val']->value['company_remark'],35,'utf-8','','...');?>
；如有问题可咨询<?php echo $_smarty_tpl->tpl_vars['val']->value['company_link_mobile'];?>
">原因：<?php echo $_smarty_tpl->tpl_vars['val']->value['company_remark'];?>
；如有问题可咨询<?php echo $_smarty_tpl->tpl_vars['val']->value['company_link_mobile'];?>
</span><?php }?>
                    <?php }?>
            <?php }else{ ?>
                    <?php if (0==$_smarty_tpl->tpl_vars['val']->value['company_status']){?>
                        <a href="javascript:void (0);" class="enterHall auditEnterHall">已报名成功，审核中</a>
                        <span class="engList4" ><em>已报名成功，审核中</em>1~2个工作日完成审核，短信通知您结果</span>
                    <?php }?>
                    <?php if (1==$_smarty_tpl->tpl_vars['val']->value['company_status']){?>
                         <a href="<?php echo smarty_function_get_url(array('rule'=>'/videohall/VideoInterviewHall'),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" class="enterHall">进入会场</a>
                    <?php }?>
                    <?php if (2==$_smarty_tpl->tpl_vars['val']->value['company_status']){?>
                        <a href="javascript:void (0);"   class="enterHall auditEnterHall">审核未通过</a>
                        <?php if ($_smarty_tpl->tpl_vars['val']->value['company_remark']){?><span class="engList4" title="原因：<?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['val']->value['company_remark'],35,'utf-8','','...');?>
；如有问题可咨询<?php echo $_smarty_tpl->tpl_vars['val']->value['company_link_mobile'];?>
">原因：<?php echo $_smarty_tpl->tpl_vars['val']->value['company_remark'];?>
；如有问题可咨询<?php echo $_smarty_tpl->tpl_vars['val']->value['company_link_mobile'];?>
</span><?php }?>
                    <?php }?>
            <?php }?>
            <p class=""><?php echo $_smarty_tpl->tpl_vars['val']->value['date_text'];?>
</p>
        </li>
        <?php }} ?>
        <li class="last"></li>
       </ul>
</div>

 <?php $_template = new Smarty_Internal_Template("videohall/footer_v1.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<!-- 浮窗 -->
<div class="engRightPop" style="display: none;">
    <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_code.png" >
    <span>语音<em>无法听清</em><br />或需<em>发送语音</em><br />请用企业APP</span>
</div>
<script>
    hbjs.use(' @form, @jobDialog, @jobDropList', function(m){
        var $ = m['cqjob.jobValidate'].extend(m['cqjob.jobForm'], m['cqjob.jobDialog'], m['cqjob.jobDropList']);
        var error_code = "<?php echo $_smarty_tpl->getVariable('check_company_info')->value['code'];?>
";
        var bind_wx_url = '<?php echo $_smarty_tpl->getVariable('bindWxUrl')->value;?>
';
       $('.no_check').on('click',function () {
           if(error_code == 405){
               var sid = $(this).attr("data-sid");
               var backurl = "/index/AddCompanyNet/";
                window.location.href = bind_wx_url+"/?backurl=" + backurl + "&source=netfair&sid=" + sid;
           }else{
               $.message("<?php echo $_smarty_tpl->getVariable('check_company_info')->value['msg'];?>
",{title:'提示'});
           }

       })
    });
</script>
</body>
</html>

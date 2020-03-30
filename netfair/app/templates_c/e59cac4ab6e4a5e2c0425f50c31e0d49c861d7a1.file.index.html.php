<?php /* Smarty version Smarty-3.0.7, created on 2020-03-20 16:49:58
         compiled from "app\templates\./shuangxuannet/index.html" */ ?>
<?php /*%%SmartyHeaderCode:205515e7483b65f0d31-58824257%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e59cac4ab6e4a5e2c0425f50c31e0d49c861d7a1' => 
    array (
      0 => 'app\\templates\\./shuangxuannet/index.html',
      1 => 1584689965,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '205515e7483b65f0d31-58824257',
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
    <title>视频招聘会</title>
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
"/>
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>"v2-widge.css"),$_smarty_tpl);?>
"/>
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'videjobfair.css'),$_smarty_tpl);?>
"/>
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'mobile.swiper.css'),$_smarty_tpl);?>
"/>
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
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'base_script.js'),$_smarty_tpl);?>
"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-reset.css,v2-widge.css,v2-header.css,newindex2018.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'newHeader2019.css'),$_smarty_tpl);?>
" />
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
    <style type="text/css">
        .huiboJob{ overflow: hidden;}
        .huiboJob a{ display:block;width:100%; height: 60px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/zhaopinhui2020.jpg) center top no-repeat;}

        a.enterprise_qiye i {background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/enterCompany.png) no-repeat;display: inline-block;width: 18px;height: 17px;vertical-align: -4px;vertical-align: -2px\9;margin-right: 5px;}
        .newHeadtop .newheadMage .log_new a.enterprise_qiye:link {color: #999;cursor: pointer;}
        #userEnterBox .reg_new .jobWanted {margin-right: 0;}
        .jobWanted i {background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/login_register.png) no-repeat;display: inline-block;width: 14px;height: 16px;vertical-align: -4px;vertical-align: -2px\9;margin-right: 5px;}
    </style>
</head>
<body>
<?php echo $_smarty_tpl->getVariable('head_data')->value;?>

<div class="videoJobFairTop">
    <div>
        <a>视频招聘会</a>
    </div>
</div>
<div class="videoJobFairMain">
    <div class="videoJobFairTit">
        <i></i>视频招聘会列表
    </div>
    <div class="videoJobFairList">
        <?php if (!empty($_smarty_tpl->getVariable('list',null,true,false)->value)){?>
            <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
                <div class="videoJobFairListx">
                    <a href="<?php echo smarty_function_get_url(array('rule'=>'/fairList/'),$_smarty_tpl);?>
sid-<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" target="_blank"><?php if ($_smarty_tpl->tpl_vars['val']->value['list_poster']){?><img src="<?php echo $_smarty_tpl->tpl_vars['val']->value['list_poster_info'];?>
" class="videoJobImg" /><?php }else{ ?><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/mobile/moren_pic.png" class="videoJobImg" /><?php }?></a>
                    <div class="videoJobCenter">
                        <a href="<?php echo smarty_function_get_url(array('rule'=>'/fairList/'),$_smarty_tpl);?>
sid-<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" target="_blank"><span class="videoJobName"><?php echo $_smarty_tpl->tpl_vars['val']->value['title'];?>
<?php if ($_smarty_tpl->tpl_vars['val']->value['school_type']==1){?><i>校园</i><?php }?></span></a>
                        <span class="videoJobTime"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['start_time'],'Y.m.d H:i');?>
  ～ <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['end_time'],'Y.m.d H:i');?>
  </span>
                        <span class="videoJobPox">企业<em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['company_num'];?>
</em>家<i></i>岗位<em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['job_num'];?>
</em>个<i></i>参与<em><?php echo $_smarty_tpl->tpl_vars['val']->value['count_data']['person_num'];?>
</em>人</span>
                    </div>
                    <div class="videoJobRight">
                        <div class="videoJobVenue">
                            <a href="<?php echo smarty_function_get_url(array('rule'=>'/fairList/'),$_smarty_tpl);?>
sid-<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" class="enterAssemblyHallBtn"  target="_blank">进入会场</a>
                            <?php if ($_smarty_tpl->tpl_vars['val']->value['activity_ing_status']!=2){?><a  class="enterpriseFairBtn" data-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" data-phone="<?php echo $_smarty_tpl->tpl_vars['val']->value['enter_phone_desc'];?>
" data-sign="<?php echo $_smarty_tpl->tpl_vars['val']->value['is_company_signup'];?>
">企业定展</a><?php }?>
                        </div>
                        <span class="videoJobState <?php if ($_smarty_tpl->tpl_vars['val']->value['activity_ing_status']==0){?>videoJobStateGreen<?php }elseif($_smarty_tpl->tpl_vars['val']->value['activity_ing_status']==1){?>videoJobStateOrange<?php }else{ ?>videoJobStateGray<?php }?>"></span>
                    </div>
                </div>
            <?php }} ?>
        <?php }?>
    </div>
    <?php echo $_smarty_tpl->getVariable('pager')->value;?>

</div>

<!-- 企业报名弹窗 -->
<div class="m_master"></div>
<div class="companySignx">
	<div class="companySignTit">
		<span class="companySignPhone">报名联系电话：<?php echo $_smarty_tpl->getVariable('shaungxuan_net_info')->value['enter_phone_desc'];?>
</span>
		<i class="companySignClose"></i>
	</div>
	<span class="companyNameTit">企业报名</span>
	<div class="signPut">
		<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/videi_job_img20.jpg"/>
		<input type="text" name="companyName" id="companyName" placeholder="请输入企业名称" value="">
	</div>
	<div class="signPut">
		<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/videi_job_img21.jpg"/>
		<input type="text" name="companyPhone" id="companyPhone" placeholder="请输入联系电话" value="">
		<input type="hidden" name="sid" id="sid" />
        <input type="hidden" name="is_company_signup" id="is_company_signup" />
	</div>
	<a href="javascript:void(0);" class="companySignBtn">提交</a>
</div>
<form id="web_subscribe_form" action="//www.huibo.com/jobsearch/" method="post"><input type="hidden" name="subscribe_origin" value="nosearchpage"></form>

</body>
<script>
    hbjs.use('@confirmBox', function (m) {

        var ConfirmBox = m['widge.overlay.confirmBox'],
            Dialog = m['widge.overlay.hbDialog'],
            cookie = m['tools.cookie'],
            fontSize = 18,
            pWidth = 70;


        // 填写企业报名信息
        $(document).on('click','.companySignClose',function () {
            $('.m_master,.companySignx').hide();
        });
        $('.enterpriseFairBtn').click(function () {
            $('.m_master,.companySignx').show();
            var sid = $(this).attr('data-id');
            var enter_phone_desc = $(this).attr('data-phone')
            var is_company_signup = $(this).attr('data-sign');
            $('.companySignPhone').html('报名联系电话：'+enter_phone_desc);
            $('#sid').val(sid);
            $('#is_company_signup').val(is_company_signup);
        });
        $('body').on('click', '.companySignBtn', function () {
            var companyName = $('#companyName').val();
            var companyPhone = $('#companyPhone').val();
            var is_company_signup = $('#is_company_signup').val();
            var sid = $('#sid').val();

            var msg = '';
            if (companyName == '') {
                msg = '请输入企业名称';
                return ConfirmBox.timeBomb(msg, {
                    name: 'fail',
                    width: fontSize * msg.length + pWidth,
                });
            }
            if (companyPhone == '') {
                msg = '请输入联系电话';
                return  ConfirmBox.timeBomb(msg, {
                    name: 'fail',
                    width: fontSize * msg.length + pWidth,
                });
            }
            if (is_company_signup == 0) {
                msg = '该场活动需线下人工审核，请直接联系工作人员电话报名。';
                return ConfirmBox.timeBomb(msg, {
                    name: 'fail',
                    width: fontSize * msg.length + pWidth,
                });
            }
            // else if (!(/^1\d{10}$/.test(companyPhone))) {
            //     msg = '请输入正确手机号';
            //     ConfirmBox.timeBomb(msg, {
            //         name: 'fail',
            //         width: fontSize * msg.length + pWidth,
            //     });
            // }
            var url = "<?php echo smarty_function_get_url(array('rule'=>'/index/ApplyCompanyPost'),$_smarty_tpl);?>
",
                data = {sid: sid,company_name:companyName,link_tel:companyPhone};
            ajax_request_function(url, data, {
                success: function (data) {
                    ConfirmBox.timeBomb(data.msg, {
                        name: 'success',
                        width: fontSize * data.msg.length + pWidth,
                    });
                    setTimeout(function () {
                        $('.m_master,.companySignx').hide();
                    }, 800);
                }
            });
        });
    });
</script>

</html>

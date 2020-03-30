<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:28:08
         compiled from "app\templates\common/showdialog.html" */ ?>
<?php /*%%SmartyHeaderCode:1105e7035b8da4a39-64292083%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '176e518b64f63869004d51d92a6a3ebe8d011db9' => 
    array (
      0 => 'app\\templates\\common/showdialog.html',
      1 => 1584412081,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1105e7035b8da4a39-64292083',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_replace')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.replace.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><script>
    hbjs.use('@confirmBox, @jobDialog', function(m) {
       var ConfirmBox      = m['widge.overlay.confirmBox'],
        Dialog          = m['widge.overlay.hbDialog'],
        cookie          = m['tools.cookie'];
        var $ = m['jquery'].extend(m['cqjob.jobDialog']);
        var hasShowDialog = false;
        var cookieset = "";
       
        
        <?php if (count($_smarty_tpl->getVariable('dialog_box')->value)>0){?>
            var close = <?php if ($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='info'){?>''<?php }else{ ?>'×'<?php }?>;
            var width = <?php if ($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='info'){?>330<?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='no_audit'){?>350<?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='is_interview'){?>625<?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='not_type_member'||$_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='not_member'){?>450<?php }elseif(in_array($_smarty_tpl->getVariable('dialog_box')->value[0]['type'],array('no_audit_letter_old','no_audit_letter_new'))){?>450<?php }else{ ?>372<?php }?>;
                <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('dialog_box')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                <?php if ($_smarty_tpl->tpl_vars['v']->value['type']=='is_interview'){?>
                width = 625;
                <?php }elseif($_smarty_tpl->tpl_vars['v']->value['type']=='service_end'){?>
                width = 455;
                <?php }?>
                <?php }} ?>

                var zIndex = 9999;
                var title = '提示';
                <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('dialog_box')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                <?php if ($_smarty_tpl->tpl_vars['v']->value['type']=='is_interview'){?>
                var title = '汇博网 · 永川区人力资源公益沙龙';
                zIndex = 10009;
                <?php }?>
                    <?php }} ?>
            var infoDialog = new Dialog({
                close: close,
                idName:<?php if ($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='is_interview'){?>'informTraining_dialog'<?php }else{ ?>'jian_dialog'<?php }?>,
                title: title,
                width:width,
                zIndex:zIndex
            });
            <?php if ($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='info'){?>
                var thtml =	['<div class="warning_dialog" style="padding:25px 11px 15px;">',
                            '<dl><dt></dt>',
                            '<dd><p style="padding-top: 11px;">您还没有完善<b style="color:red;">企业信息</b>，不能发布职位</p>',
                            '<a href="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/company/ImproveCompanyInfo" class="cpromisetip">马上填写</a>',
                            '</dd></dl>',
                    '</div>'].join('');
            <?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='info_exterior'){?> 
                var thtml = ['<div class="warning_dialog" style="padding:25px 11px 15px;">',
                            '<dl><dt></dt>',
                            '<dd><p style="padding-top: 11px;">您还没有完善<b style="color:red;">企业信息</b>，不能发布职位</p>',
                            '<a href="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/company/ImproveCompanyInfo" class="cpromisetip">马上填写</a>',
                            '<a href="javascript:;" id="info_exterior" class="cpromisetip" style="background: #CCC; border: 1px solid #CCC;">跳过</a>',
                            '</dd></dl>',
                    '</div>'].join('');                   
            <?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='no_job'){?>
                <?php if ($_smarty_tpl->getVariable('dialog_box')->value[0]['is_audit']==0){?>
                    var url = "/register/add/";
                <?php }else{ ?>
                     var url = "/job/add/";
                <?php }?>
                cookieset = "no_job"
                var thtml =	['<div class="warning_dialog" style="padding:25px 11px 15px;">',
                    '<dl><dt></dt>',
                    '<dd><p style="padding-top:11px">您还没<b style="color:red;">发布职位</b>，每天<b style="color:red;">10万人才</b>在汇博等着您</p>',
                    '<span><input style="display:inline-block; vertical-align:-2px; margin-right:5px;" type="checkbox" class="infoDialogCheck"/>不再提示</span>',
                    '<a href="'+url+'" style="margin:30px 0 0 100px;" class="cpromisetip">发布职位</a>',
                    '</dd></dl>',
                '</div>'].join('');
            <?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='audit_not_pass'){?>
                  cookieset = "audit_not_pass" ;
                var thtml =	['<div class="warning_dialog" style="padding:25px 11px 15px;">',
                    '<dl><dt></dt>',
                    '<dd><p style="padding-top:11px">您的企业信息<b style="color:red;">审核失败</b>，请重新上传企业资质</p>',
                    '<span><input style="display:inline-block; vertical-align:-2px; margin-right:5px;"class="infoDialogCheck" type="checkbox"/>不再提示</span>',
                    '<a href="/licencevalidate/" style="margin:30px 0 0 95px;" class="cpromisetip">重新上传</a>',
                    '</dd></dl>',
                '</div>'].join('');
            <?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='no_audit'){?>
                cookieset = "no_audit" ;
                 var thtml =	['<div class="warning_dialog" style="padding:25px 11px 15px;">',
                    '<dl><dt></dt>',
                    '<dd><p style="padding-top:11px">您的企业信息<b style="color:red;">尚未审核</b>，请上传企业资质</p>',
                    '<span><input style="display:inline-block; vertical-align:-2px; margin-right:5px;" class="infoDialogCheck" type="checkbox"/>不再提示</span>',
                    '<a href="/licencevalidate/?step=4" style="margin:30px 0 0 69px;" class="cpromisetip">上传资质</a>',
                    '</dd></dl>',
                '</div>'].join('');
             <?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='no_audit_letter_new'){?>
                cookieset = "no_audit_letter_new" ;
                 var thtml =	['<div class="warning_dialog" style="padding:25px 11px 15px;">',
                    '<dl><dt></dt>',
                    '<dd><p style="padding-top:11px">您还有招聘委托书未上传，发布职位无法对外展示</p>',
                    '<a href="/licencevalidate/" style="margin:30px 0 0 65px;" class="cpromisetip">下载/上传招聘委托书</a>',
                    '</dd></dl>',
                '</div>'].join('');
             <?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='no_audit_letter_old'){?>
                cookieset = "no_audit_letter_old" ;
                 var thtml =	['<div class="warning_dialog" style="padding:25px 11px 15px;">',
                    '<dl><dt></dt>',
                    '<dd><p style="padding-top:11px">根据国务院《人力资源市场暂行条例》相关规定，用人单位在招聘时需提供招聘委托证明材料，<b style="color:red">请贵单位于2019年5月13日前补交招聘委托书</b>，逾期招聘行为将暂时冻结，通过认证后恢复。</p>',
                    '<a href="/licencevalidate/" style="margin:30px 0 0 65px;" class="cpromisetip">下载/上传招聘委托书</a>',
                    '</dd></dl>',
                '</div>'].join('');
             <?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='not_type_member'){?>
                cookieset = "not_type_member" ;
                 var thtml =	['<div class="warning_dialog" style="padding:25px 15px 15px;">',
                    '<dl><dt></dt>',
                    '<dd><p style="padding-top:0px;line-height:16px;">您发布的职位已经展示，以下因素会影响您的招聘效果：</p>',
                    '<p style="padding:10px 0 0 0;">1、职位描述不清楚，不够吸引求职者。</p>',
                    '<p>2、您的职位排列在付费会员职位之后，<b style="color:red;">开通付费会员</b><br />立即优先展示。</p>',
                    '<span><input style="display:inline-block; vertical-align:-2px; margin-right:5px;" class="infoDialogCheck" type="checkbox"/>不再提示</span>',
                    '<a href="/index/memberdetail/" style="margin:30px 0 0 135px;" class="cpromisetip">开通付费会员</a>',
                    '</dd></dl>',
                '</div>'].join('');
                    <?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='not_member'){?>
                cookieset = "not_member" ;
                 var thtml =	['<div class="warning_dialog" style="padding:25px 11px 15px;">',
                    '<dl><dt></dt>',
                    '<dd><p>您发布的职位目前不能展示，求职者无法查看到您的职位。您还不是企业付费会员，<b style="color:red;">开通付费会员</b>立即展示。</p>',
                    '<span><input style="display:inline-block; vertical-align:-2px; margin-right:5px;" class="infoDialogCheck" type="checkbox"/>不再提示</span>',
                    '<a href="/index/memberdetail/" style="margin:30px 0 0 141px;" class="cpromisetip">开通付费会员</a>',
                    '</dd></dl>',
                '</div>'].join('');
                    <?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='recommend_resume_dialog'){?>
                    cookieset = "recommend_resume_dialog" ;
                    var thtml =	['<div class="warning_dialog" style="padding:25px 11px 15px;">',
                        '<dl><dt></dt>',
                        '<dd><p style="padding-top: 11px;">运营人员为您的职位<b style="color:red;"><?php echo $_smarty_tpl->getVariable('dialog_box')->value[0]["station"];?>
</b>精心挑选了新简历</p>',
                        '<a href="#" class="cpromisetip recommend_resume_dialog" style="background: #eee;border: 1px solid #eee;color: #666;">算了</a>',
                        '<a href="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/recommend/Index/?type=2&status=99" class="cpromisetip recommend_resume_dialog_go">去看看</a>',
                        '</dd></dl>',
                        '</div>'].join('');
                    <?php }elseif($_smarty_tpl->getVariable('dialog_box')->value[0]['type']=='service_end'){?>
                    cookieset = "service_end" ;
                    var thtml =	['<div class="membershipDuex" style="width:415px; overflow: hidden;  margin: 20px; text-align: left;">',
                        '<span class="duex01" style="display: block; color: #444; line-height: 22px;">您的会员服务已于<i style="color: #f00;"><?php echo date("Y-m-d",strtotime($_smarty_tpl->getVariable('companyinfo')->value['end_time']));?>
</i>到期，请联系招聘顾问续费<i style="color: #f00;">开通会员</i>服务。</span>',
                        '<div class="duez" style="overflow: hidden; color: #444;padding-left: 108px;">',
                            '<span class="duez01" style="display: block;color: #999; padding: 20px 0 10px 0;"><b style="font-weight: normal; color: #444; font-size: 16px; font-weight: bold; padding-right: 10px;"><?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['user_name'])){?><?php echo $_smarty_tpl->getVariable('hrManager')->value['user_name'];?>
<?php }else{ ?>客户专员<?php }?></b>招聘顾问</span>',
                        <?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['tel'])){?>
                            '<span class="duez02" >电话：</b><?php if ($_smarty_tpl->getVariable('site_type')->value==5){?>028-62520468<?php }else{ ?><?php echo $_smarty_tpl->getVariable('tel_head')->value;?>
转<?php echo $_smarty_tpl->getVariable('hrManager')->value['tel'];?>
<?php }?></span>',
                <?php }else{ ?>
                    '<span class="duez02" >电话：</b>400-1010-970</span>',
                <?php }?>
                    <?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['mobile'])){?>
                           '<span class="duez02" style="line-height: 30px; color: #444;"><b style="font-weight: normal; color: #999;">手机：</b><?php echo $_smarty_tpl->getVariable('hrManager')->value['mobile'];?>
（微信同号）</span>',
                           <?php }?>
                            '<span class="duez02" style="line-height: 30px; color: #444;"><b style="font-weight: normal; color: #999;">QQ：</b><?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['qq'])){?><?php echo trim($_smarty_tpl->getVariable('hrManager')->value['qq']);?>
<?php }else{ ?>2851501279<?php }?></span>',
                        '</div>',
                        '<a style="padding-left:0px;display: block;width:170px; height: 40px; background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/qq_face.jpg) no-repeat; margin: 20px auto 0 auto;" target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['qq'])){?><?php echo trim($_smarty_tpl->getVariable('hrManager')->value['qq']);?>
<?php }else{ ?>2851501279<?php }?>&site=qq&menu=yes">',
                                '</a>',
                    '</div>'].join('');
                    <?php }?>


			setTimeout(function () {
				infoDialog.setContent(thtml).show();
                var expires = "";

                <?php if (in_array($_smarty_tpl->getVariable('dialog_box')->value[0]['type'],array('no_audit_letter_old','no_audit_letter_new','recommend_resume_dialog'))){?>
                var exp = new Date();
                exp.setTime(<?php echo $_smarty_tpl->getVariable('dialog_box')->value[0]['expires'];?>
);
                expires = {expires:exp};
                cookie.set(cookieset,true,expires,"/");
                <?php }?>
				infoDialog.query('.infoDialogCheck').on('click',function(){
                    if($(this).is(":checked")){
                        cookie.set(cookieset,true,"","/");
                    }
                });
                infoDialog.query('.recommend_resume_dialog').on('click',function(){
                    infoDialog.hide();
                    cookie.set(cookieset,true,{expires:exp,path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
                });
				infoDialog.query('.recommend_resume_dialog_go').on('click',function(){
				    infoDialog.hide();
                    cookie.set(cookieset,true,{expires:exp,path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
				});

				//跳过
                infoDialog.query('#info_exterior').on('click',function(){
                    infoDialog.hide();
                    cookie.set('info_exterior', 1, {path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
                });
			},300);
        <?php }else{ ?>
            var showHeadWeixinTip = cookie.get('showHeadWeixinTip');
            var showWeixinDialog = cookie.get('showWeixinDialog');
            var showTipDialog = cookie.get('hidePromiseTip');
            var user_id = cookie.get('userid');
            var promiseDialog = new Dialog({
                    idName: 'promiseDialog',
                    width: 400,
                    title: '重要提醒',
                    isOverflow: false,
                    isAjax: false
                });
            if(showTipDialog != 'true'){
		$.getJSON('/index/promisesoonstop',null,function(data){
			if(parseInt(data.count)>0){
				var promiseTipHTML = [
				'<div class="warning_dialog">',
					'<dl><dt></dt>',
					'<dd><p>有 <b style="color:red;">'+data.count+'</b> 份简历快到回复限期，请尽快回复，</p>',
					'<p>过期未回复将会被禁止使用简历承诺功能。</p>',
					'<a href="/apply" class="cpromisetip">马上去处理</a>',
					'<a href="javascript:void(0);" class="cpromisetip graybutn">我知道了</a>',
					'</dd></dl>',
				'</div>'].join('');
				promiseDialog.setContent(promiseTipHTML).show();
				promiseDialog.query('.graybutn').on('click',function(){
					cookie.set('hidePromiseTip',true,{expires:'',path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
					promiseDialog.hide();
				});
				promiseDialog.query('.cpromisetip').on('click',function(){
					cookie.set('showStopJobApply',true,"","/");
				});
			}else{
				//检测是否绑定微信
				<?php if (!$_smarty_tpl->getVariable('bindweixin')->value){?>
//					if(1 != showWeixinDialog){
//						$.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/account/twodimensioncode/"),$_smarty_tpl);?>
',function(result){
//                                                    if(result&&result.status) {
//                                                            var src = result.data.codeurl;
//                                                            var html = [
//                                                            '<div>',
//                                                            '	<dl class="clearfix ewmBox">',
//                                                            '		<dt style="float:left">',
//                                                            '			<img src="'+src+'" />',
//                                                            '		</dt>',
//                                                            '		<dd style="float:left">',
//                                                            '			<p><a href="javascript:void(0);" style="cursor: default;">发布招聘</a></p>',
//                                                            '			<p><a href="javascript:void(0);" style="cursor: default;">搜索人才</a>',
//                                                            '			</p><p><a href="javascript:void(0);" style="cursor: default;">沙龙培训</a></p>',
//                                                            '			<p><a href="javascript:void(0);" style="cursor: default;">新政解读</a></p>',
//                                                            '			<p><a href="javascript:void(0);" style="cursor: default;">猎头服务</a></p>',
//                                                            '		</dd>',
//                                                            '	</dl>',
//                                                            '</div>'].join('');
//                                                            $.showModal(html,
//                                                                {width:500,title:"微信关注汇博招聘，为您提供一站式人力资源服务",contentType:"html",onclose:closeWeixinDialog
//                                                            });
//                                                    }
//                                                });
//                                        }
				<?php }?>				
			}
		})
            }
            
        <?php }?> 
        /*微信气泡提醒2015-12-10 start */
        function closeWeixinDialog(){
                cookie.set('showWeixinDialog',1,{expires:'',path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
        }
    })
    
</script>
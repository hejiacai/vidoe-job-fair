<?php /* Smarty version Smarty-3.0.7, created on 2020-03-24 09:38:06
         compiled from "app\templates\./offer/editoffer.html" */ ?>
<?php /*%%SmartyHeaderCode:254795e79647ed3e1e3-13006569%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '92938ea9cd153d60bf0237508628cec2bbf79ed3' => 
    array (
      0 => 'app\\templates\\./offer/editoffer.html',
      1 => 1585013882,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '254795e79647ed3e1e3-13006569',
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
		<meta charset="utf-8">
		<title>offer编辑</title>
		<meta content="never" name="referrer">
		<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
		<script type="text/javascript" src="//assets.huibo.com/js/cp/My97DatePicker/WdatePicker.js?v=20190425"></script>
		<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
"></script>
		<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'dialog.js'),$_smarty_tpl);?>
"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
		<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'editoffer.css'),$_smarty_tpl);?>
">
		<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'md_offer.css'),$_smarty_tpl);?>
">
		<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'m_font_style.css'),$_smarty_tpl);?>
">
		<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
">
		<style>
			.icon-icon-04:hover{
			cursor:pointer
			}
			.icon-icon-04{
				color:#514091!important;
			}
			textarea,input{font-family: 'Microsoft YaHei' !important;}
			.xiaoxx {color: #de0000;font-style: normal;margin: 0 0 0 5px;width: 12px;position: absolute;top: 66px;left: 155px;}
			.showRightBox{display: none;}
		</style>
	</head>
	<body>
		<div class="header">
			<div class="headerContent">
				offer编辑
				<!-- <a href="javascript:history.go(-1)">返回</a> -->
			</div>
		</div>
		<div class="bodyTop">
			<span>更多模板：</span>
				<?php if ($_smarty_tpl->getVariable('template_list')->value){?>
					<?php if (!$_smarty_tpl->getVariable('has_ty_template')->value){?>
						<em <?php if ($_smarty_tpl->getVariable('is_ty_cut')->value){?>class="cut"<?php }?>>通用模板</em>
					<?php }?>
					<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('template_list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
						<em data-job_id="<?php echo $_smarty_tpl->tpl_vars['val']->value['job_id'];?>
" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_id']==$_smarty_tpl->tpl_vars['val']->value['template_id']||($_smarty_tpl->tpl_vars['val']->value['template_type']==1&&$_smarty_tpl->getVariable('is_ty_cut1')->value)){?>class="cut"<?php }?>><?php echo $_smarty_tpl->tpl_vars['val']->value['template_name'];?>
<?php if ($_smarty_tpl->tpl_vars['val']->value['template_type']==2){?><div class="tempDele" data-temp_id="<?php echo $_smarty_tpl->tpl_vars['val']->value['template_id'];?>
">×</div><?php }?></em>
					<?php }} ?>
				<?php }else{ ?>
				<em class="cut">通用模板</em>
				<?php }?>
		</div>
		<form class="editBox" id="form1">
		<div class="body">
			<div class="bodyLeft">
				<div class="typeBox">
					<div class="typeItem <?php if ($_smarty_tpl->getVariable('default_template')->value['tempalte_style']==2){?>cut<?php }?>" data-type="2">
						<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/xiaoyuan/model/offerstyle2.jpg" alt="">
					</div>
					<div class="typeItem <?php if ($_smarty_tpl->getVariable('default_template')->value['tempalte_style']==1){?>cut<?php }?>" data-type="1">
						<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/xiaoyuan/model/offerstyle1.jpg" alt="">
					</div>
					
				</div>
				<div class="template_center <?php if ($_smarty_tpl->getVariable('default_template')->value['tempalte_style']==2){?>template_center02<?php }?>">
					<div class="tm_content_bg <?php if ($_smarty_tpl->getVariable('default_template')->value['tempalte_style']==2){?>tm_content_bg02<?php }?>">
					<div class="tm_img_bg <?php if ($_smarty_tpl->getVariable('default_template')->value['tempalte_style']==2){?>tm_img_bg02<?php }?>">
						<img src="<?php echo $_smarty_tpl->getVariable('logo')->value;?>
" >
						<span><?php echo $_smarty_tpl->getVariable('company_info')->value['company_name'];?>
</span>
					</div>
					<div class="tm_content <?php if ($_smarty_tpl->getVariable('default_template')->value['tempalte_style']==2){?>tm_content02<?php }?>">
						<div id="job_intro" <?php if ($_smarty_tpl->getVariable('default_template')->value['tpl_wellcome_show']==0){?>style="display: none;"<?php }?>>
							<h1 class="tm_user_name"><sapn class="job_user"><?php echo $_smarty_tpl->getVariable('person_info')->value['user_name'];?>
<?php if ($_smarty_tpl->getVariable('person_info')->value['sex']==1){?>先生<?php }else{ ?>女士<?php }?></sapn>：</h1>
							<div class="tm_intro">
								<div class="job_intro">
									<?php echo $_smarty_tpl->getVariable('default_template')->value['tpl_wellcome_txt'];?>

								</div>
							</div>
							<span class="icon-icon-04 userIcon"></span>
						</div>	
						<div class="tm_details">
							<span id="job_name" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_station_show']==0){?>style="display: none;"<?php }?>>
								入职职位：<em class="job_name" <?php if (!$_smarty_tpl->getVariable('default_template')->value['template_job_station']){?>style='color:red'<?php }?>><?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_station']){?><?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_station'];?>
<?php }else{ ?>待填写<?php }?></em>
							</span>
							<span id="job_money" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_salary_show']==0){?>style="display: none;"<?php }?>>综合薪资：<em class="job_money" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_salary_show']>0){?>style='color:red'<?php }?>><?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_salary']){?><?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_salary'];?>
<?php }else{ ?>待填写<?php }?></em></span>
							<span id="job_test" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_probation_time_show']==0){?>style="display: none;"<?php }?>>试用期：<em class="job_test"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_probation_time'];?>
</em></span>
							<span id="job_time" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_join_time_show']==0){?>style="display: none;"<?php }?>>入职时间：<em class="job_time"><?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_join_time']&&$_smarty_tpl->getVariable('default_template')->value['template_job_join_time']!='0000-00-00 00:00:00'){?><?php echo date("Y年m月d日 H点",strtotime($_smarty_tpl->getVariable('default_template')->value['template_job_join_time']));?>
<?php }else{ ?><em style="color:red">待填写</em><?php }?> </em></span>
							<span id="job_weal" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_reward_show']==0){?>style="display: none;"<?php }?>>福利待遇：<em class="job_weal"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_reward'];?>
</em></span>
							<!-- <span id="">
								入职时间：<em style="color: #f51919;">待填写</em>
							</span> -->
							<span id="entry_place" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_link_probation_addres_show']==0){?>style="display: none;"<?php }?>>入职地点：<em class="entry_place"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_probation_addres'];?>
</em></span>
							<span id="job_place" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_link_work_addres_show']==0){?>style="display: none;"<?php }?>>工作地点：<em class="job_place"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_work_addres'];?>
</em></span>
							
							<span id="contact_people" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_link_man_show']==0&&$_smarty_tpl->getVariable('default_template')->value['template_link_way_show']==0){?>style="display: none;"<?php }?>>联系人：
								<em class="job_people" id="job_people"  <?php if ($_smarty_tpl->getVariable('default_template')->value['template_link_man_show']==0){?>style="display:none"<?php }?>><?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_man'];?>
</em>
								<em class="job_tele" id="job_tele"  <?php if ($_smarty_tpl->getVariable('default_template')->value['template_link_way_show']==0){?>style="display:none"<?php }?>><?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_way'];?>
</em>
							</span>
							<span class="icon-icon-04 jobicon"></span>
						</div>
						<div class="tm_entry_matter" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_matter_list_show']==0){?>style="display: none;"<?php }?> id="job_matter">
							<h2>入职事项：</h2>
							<div class="job_matter">
								<?php echo $_smarty_tpl->getVariable('default_template')->value['template_matter_list_txt'];?>

							</div>
							<span class="icon-icon-04 sxIcon"></span>
						</div>
						<div class="tm_entry_matter" id="job_thing" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_matter_means_show']==0){?>style="display: none;"<?php }?>>
							<h2>携带材料：</h2>
							<div class="job_thing">
								<?php echo $_smarty_tpl->getVariable('default_template')->value['template_matter_means_txt'];?>

							</div>
							<span class="icon-icon-04 clIcon"></span>
						</div>
						
						<div class="tm_weal job_sup" id="job_sup" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_footnote_show']==0){?>style="display: none;"<?php }?>>
							<h2 style="font-size: 16px;color: #514091;font-weight: bold;padding-bottom: 15px;">补充说明：</h2>
							<?php echo $_smarty_tpl->getVariable('default_template')->value['template_footnote_txt'];?>

							<span class="icon-icon-04 smIcon"></span>
						</div>
						<div class="tm_company" id="company_all" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_company_show']==0){?>style="display: none;"<?php }?>>
							<img src="<?php echo $_smarty_tpl->getVariable('logo')->value;?>
" >
							<p>
								<b><?php echo $_smarty_tpl->getVariable('company_info')->value['company_name'];?>
</b>
								<span>地址：<em class="company_place"><?php echo $_smarty_tpl->getVariable('company_info')->value['address'];?>
</em></span>
								<span>电话：<em class="company_tele"><?php echo $_smarty_tpl->getVariable('company_info')->value['link_mobile'];?>
</em></span>
							</p>
							<span class="icon-icon-04 comIcon"></span>
						</div>
						
					</div>
					</div>
					<div class="tm_bgd_img <?php if ($_smarty_tpl->getVariable('default_template')->value['tempalte_style']==2){?>tm_bgd_img02<?php }?>"></div>
				</div>
				<div class="box_bototm">
				<div class="su_bx">
					<span>求职者手机号<em>*</em></span>
					<input type="text" placeholder="请输入求职者手机号" id="send_phone" name="send_phone" value="<?php echo $_smarty_tpl->getVariable('person_info')->value['mobile_phone'];?>
" data-maxl="50">
				</div>
				<div class="su_bx">
					<span>求职者邮箱</span>
					<input type="text" placeholder="请输入求职者电子邮箱" id="send_email" name="send_email" value="<?php echo $_smarty_tpl->getVariable('person_info')->value['email'];?>
" data-maxl="50">
				</div>
				</div>
			</div>
			<div class="bodyRight">
				<div class="rightBtnBox">
					<div class="rightEditBtn">编辑内容</div>
					<div class="rightSaveBtn">保存为模板</div>
				</div>
				
					<input type="hidden" id="hddModName" name="modelName" value="tongyong">
					<input type="hidden" id="hddModName1" name="modelName1" value="<?php if ($_smarty_tpl->getVariable('default_template')->value['template_type']==1){?>tongyong<?php }else{ ?><?php echo $_smarty_tpl->getVariable('default_template')->value['job_id'];?>
<?php }?>">
					<input type="hidden" id="job_id" name="job_id" value="<?php echo $_smarty_tpl->getVariable('job_id')->value;?>
">
					<input type="hidden" id="invite_id" name="invite_id" value="<?php echo $_smarty_tpl->getVariable('invite_id')->value;?>
">
					<input type="hidden" id="send_type" name="send_type" value="1">
					<input type="hidden" id="tempalte_style" name="tempalte_style" value="<?php echo $_smarty_tpl->getVariable('default_template')->value['tempalte_style'];?>
">
					<div class="showRightBox">
					<div class="editHead">
						<div class="editTab w4 cut">欢迎语</div>
						<div class="editTab">职位相关</div>
						<div class="editTab">地点及联系人</div>
						<div class="editTab">事项材料</div>
						<div class="editTab">补充说明</div>
						<div class="editTab w6">企业名片</div>
						<div class="editCloseBtn">×</div>
					</div>
					<div class="editBody" id="editBody">
						<!-- 欢迎语 -->
						<!-- oninput="snyputval()" -->
						<!-- <input type="" name="" id="" value="" oninput="snyputval()" > -->
						<div class="editView welcomeBox" id="chooseContent0">
							<div class="editRow">
								<span>求职者</span>
								<input  id="qzInput" type="text" placeholder="请输入" data-for="job_user" data-nochange="true" name="template_job_user_ipt" value="<?php echo $_smarty_tpl->getVariable('person_info')->value['user_name'];?>
<?php if ($_smarty_tpl->getVariable('person_info')->value['sex']==1){?>先生<?php }else{ ?>女士<?php }?>" data-maxl="10" />
								<input type="hidden" name="template_job_user" value="<?php echo $_smarty_tpl->getVariable('person_info')->value['user_name'];?>
">
							</div>
							<div class="editRow">
								<h6>欢迎语内容</h6>
								<textarea data-for="job_intro" data-format="br2p" data-count="true" name="tpl_wellcome_ipt" id="wel" data-maxl="200"><?php echo $_smarty_tpl->getVariable('default_template')->value['tpl_wellcome'];?>
</textarea>
								<textarea class="hiddenText" name="tpl_wellcome"><?php echo $_smarty_tpl->getVariable('default_template')->value['tpl_wellcome'];?>
</textarea>
								<div class="textareaNum"><span><?php echo mb_strlen($_smarty_tpl->getVariable('default_template')->value['tpl_wellcome']);?>
</span>字</div>
							</div>
							<div class="huany">
							<label class="editShow" data-show="job_intro"><input type="checkbox" value="0" class="huanyingcheckbox" name="tpl_wellcome_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['tpl_wellcome_show']==0){?>checked="checked"<?php }?>>不展示欢迎语</label>
							</div>
							<!-- <div class="editOk" data-save="true" data-list="wel">确定</div> -->
						</div>
						
						<!-- 职位相关 -->
						<!-- cut -->
						<div class="editView" id="chooseContent1">
							<h6>职位相关</h6>
							<div class="editRow">
								<div>入职职位<em>*</em></div>
								<input id="comeSta" type="text" placeholder="请输入" data-for="job_name" name="template_job_station_ipt" value="<?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_station'];?>
" data-maxl="50">
								<input type="hidden" name="template_job_station" value="<?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_station'];?>
">
								<label class="editShow" data-show="job_name"><input type="checkbox" value="0" class="ruzhicheckbox" name="template_job_station_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_station_show']==0){?>checked="checked"<?php }?>>不展示</label>
							</div>
							<div class="editRow">
								<div>入职时间<em>*</em></div>
								<!-- onclick="WdatePicker({dateFmt:'yyyy年M月d日 H点'})" -->
								<input id="comeTime" class="comeTime" type="text" placeholder="请输入" data-nochange="true"  name="template_job_join_time_ipt" data-for="job_time" value="<?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_join_time']&&$_smarty_tpl->getVariable('default_template')->value['template_job_join_time']!='0000-00-00 00:00:00'){?><?php echo date('Y年m月d日 H点',strtotime($_smarty_tpl->getVariable('default_template')->value['template_job_join_time']));?>
<?php }?>" data-maxl="50">
								<input type="hidden" name="template_job_join_time" value="<?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_join_time']&&$_smarty_tpl->getVariable('default_template')->value['template_job_join_time']!='0000-00-00 00:00:00'){?><?php echo date('Y年m月d日 H点',strtotime($_smarty_tpl->getVariable('default_template')->value['template_job_join_time']));?>
<?php }?>">
								<label class="editShow" data-show="job_time"><input type="checkbox" value="0" class="timecheckbox" name="template_job_join_time_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_join_time_show']==0){?>checked="checked"<?php }?>>不展示</label>
							</div>
							<div class="editRow">
								<span>试用期</span>
								<input id="testTime" type="text" placeholder="请输入" data-for="job_test" name="template_job_probation_time_ipt" value="<?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_probation_time'];?>
" data-maxl="50">
								<input type="hidden" name="template_job_probation_time" value="<?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_probation_time'];?>
">
								<label class="editShow" data-show="job_test"><input type="checkbox" value="0" class="shiyongcheckbox" name="template_job_probation_time_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_probation_time_show']==0){?>checked="checked"<?php }?>>不展示</label>
							</div>
							<div class="editRow">
								<span>综合薪资</span>
								<textarea id="inCome" placeholder="不超过50字" data-nochange="true" data-format="br2br" data-for="job_money" data-maxl="50" name="template_job_salary_ipt"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_salary'];?>
</textarea>
								<textarea class="hiddenText" name="template_job_salary"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_salary'];?>
</textarea>
								<label class="editShow" data-show="job_money"><input type="checkbox" value="0" class="xinzicheckbox" name="template_job_salary_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_salary_show']==0){?>checked="checked"<?php }?>>不展示</label>
							</div>
							<div class="editRow">
								<span>福利待遇</span>
								<textarea id="fldy" placeholder="不超过50字" data-format="br2br" data-for="job_weal" data-maxl="50" name="template_job_reward_ipt"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_reward'];?>
</textarea>
								<textarea class="hiddenText" name="template_job_reward"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_job_reward'];?>
</textarea>
								<label class="editShow" data-show="job_weal"><input type="checkbox" value="0" class="fulicheckbox" name="template_job_reward_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_job_reward_show']==0){?>checked="checked"<?php }?>>不展示</label>
							</div>
							<!-- <div class="editOk">确定</div> -->
						</div>
						
						<!-- 地点及联系人 -->
						<div class="editView" id="chooseContent2">
							<h6>地点及联系人</h6>
							<div class="editRow">
								<span>入职地点</span>
								<textarea id="joinPlace" placeholder="不超过50字" data-for="entry_place" name="template_link_probation_addres_ipt" data-format="br2br" data-maxl="50"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_probation_addres'];?>
</textarea>
								<textarea class="hiddenText" name="template_link_probation_addres"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_probation_addres'];?>
</textarea>
								<label class="editShow" data-show="entry_place"><input type="checkbox" value="0" class="didiancheckbox" name="template_link_probation_addres_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_link_probation_addres_show']==0){?>checked="checked"<?php }?>>不展示</label>
							</div>
							<div class="editRow">
								<span>上班地点</span>
								<textarea id="workPlace" placeholder="不超过50字" data-for="job_place" name="template_link_work_addres_ipt" data-format="br2br" data-maxl="50"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_work_addres'];?>
</textarea>
								<textarea class="hiddenText" name="template_link_work_addres"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_work_addres'];?>
</textarea>
								<label class="editShow" data-show="job_place"><input type="checkbox" class="shangbancheckbox" name="template_link_work_addres_show" value="0" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_link_work_addres_show']==0){?>checked="checked"<?php }?>>不展示</label>
							</div>
							<div class="editRow">
								<span>联系人</span>
								<input id="relaPeo" type="text" placeholder="请输入" data-for="job_people" name="template_link_man_ipt" value="<?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_man'];?>
" data-maxl="50">
								<input type="hidden" name="template_link_man" value="<?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_man'];?>
">
								<label class="editShow" data-show="job_people"><input type="checkbox" class="lianxirencheckbox" name="template_link_man_show" value="0" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_link_man_show']==0){?>checked="checked"<?php }?>>不展示</label>
							</div>
							<div class="editRow">
								<span>联系电话</span>
								<input id="relapone" type="text" placeholder="请输入" data-for="job_tele" name="template_link_way_ipt" value="<?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_way'];?>
" data-maxl="50">
								<input type="hidden" name="template_link_way" value="<?php echo $_smarty_tpl->getVariable('default_template')->value['template_link_way'];?>
">
								<label class="editShow" data-show="job_tele"><input type="checkbox" value="0" class="lianxidianhuacheckbox" name="template_link_way_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_link_way_show']==0){?>checked="checked"<?php }?>>不展示</label>
							</div>
							<!-- <div class="editOk">确定</div> -->
						</div>
						
						
						<!-- 事项及材料 -->
						<div class="editView" id="chooseContent3">
							<div>
								<h6>入职事项</h6>
								<textarea class="hiddenText" name="template_matter_list"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_matter_list'];?>
</textarea>
								<textarea data-for="job_matter" data-format="br2p" name="template_matter_list_ipt" id="matter" data-maxl="500"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_matter_list'];?>
</textarea>
								<!-- <div class="textareaNum"><span>203</span>字</div> -->
								<label class="editShow mp10" data-show="job_matter"><input type="checkbox" value="0" class="ruzhishixiangcheckbox" name="template_matter_list_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_matter_list_show']==0){?>checked="checked"<?php }?>>不展示入职事项</label>
								<!-- <div class="editOk" data-save="true" data-list="matter">确定</div> -->
							</div>
							<div class="editView2" id="xdcl">
								<h6>携带材料</h6>
								<textarea id="batoThin" class="hiddenText" name="template_matter_means"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_matter_means'];?>
</textarea>
								<textarea data-for="job_thing" data-format="br2p" name="template_matter_means_ipt" id="thing" data-maxl="500"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_matter_means'];?>
</textarea>
								<!-- <div class="textareaNum"><span>203</span>字</div> -->
								<label class="editShow mp10" data-show="job_thing"><input type="checkbox" value="0" class="xiedaicailiaocheckbox" name="template_matter_means_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_matter_means_show']==0){?>checked="checked"<?php }?>>不展示携带材料</label>
								<!-- <div class="editOk" data-save="true" data-list="thing">确定</div> -->
							</div>
						</div>
						
						
						<!-- 补充说明 -->
						<div class="editView" id="chooseContent4">
							<h6>补充说明</h6>
							<textarea id="supplyExp" class="hiddenText" name="template_footnote"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_footnote'];?>
</textarea>
							<textarea data-for="job_sup" data-format="br2br" data-count="true" name="template_footnote_ipt" id="sup" data-maxl="500"><?php echo $_smarty_tpl->getVariable('default_template')->value['template_footnote'];?>
</textarea>
							<div class="textareaNum"><span><?php echo mb_strlen($_smarty_tpl->getVariable('default_template')->value['template_footnote']);?>
</span>字</div>
							<label class="editShow" data-show="job_sup"><input type="checkbox" value="0" class="buchongcheckbox" name="template_footnote_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_footnote_show']==0){?>checked="checked"<?php }?>>不展示补充说明</label>
							<!-- <div class="editOk" data-save="true" data-list="sup">确定</div> -->
						</div>
						
						<!-- 企业名片 -->
						<div class="editView companyView" id="chooseContent5">
							<h6>企业名片</h6>
							<div class="editRow editRow2">
								<span>公　　司</span>
								<input id="compayI" type="text" placeholder="请输入" name="company_name" readonly="readonly" value="<?php echo $_smarty_tpl->getVariable('company_info')->value['company_name'];?>
">
							</div>
							<div class="editRow editRow2">
								<span>地　　址</span>
								<input id="compayAdress" type="text" placeholder="请输入" name="address_ipt" data-for="company_place" id="company_place_ipt" value="<?php echo $_smarty_tpl->getVariable('company_info')->value['address'];?>
" data-maxl="50">
								<input type="hidden" name="address" data-for="company_place" value="<?php echo $_smarty_tpl->getVariable('company_info')->value['address'];?>
">
							</div>
							<div class="editRow editRow2">
								<span>电　　话</span>
								<input id="compayPone" type="text" placeholder="请输入" name="link_mobile_ipt" data-for="company_tele" id="company_tele_ipt" value="<?php echo $_smarty_tpl->getVariable('company_info')->value['link_mobile'];?>
" data-maxl="50">
								<input type="hidden" name="link_mobile" value="<?php echo $_smarty_tpl->getVariable('company_info')->value['link_mobile'];?>
">
							</div>
							<label class="editShow"  data-show="company_all"><input type="checkbox" value="0" class="qiyemingpiancheckbox" name="template_company_show" <?php if ($_smarty_tpl->getVariable('default_template')->value['template_company_show']==0){?>checked="checked"<?php }?>>不展示企业名片</label>
							<!-- <div class="editOk">确定</div> -->
						</div>

						<div class="" style="height: 800px"></div>
					</div>

				</div>
			</div>

		</div>
		
		</form>
		<div class="bodyBottom">
			<div class="bottomBtn">发送</div>
		</div>
		<div class="hddSaveTemp">
			<select id="saveName" class="saveName" name="template_list">
				<option value ="tongyong">通用模板</option>
				<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('job_list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
					<option value ="<?php echo $_smarty_tpl->tpl_vars['val']->value['job_id'];?>
"><?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['val']->value['station'],15,'utf-8','','...');?>
</option>
				<?php }} ?>
			</select>
		</div>
		<div class="hddSendTemp">
			<div class="sendContent">
				<h4 class="tongzhi">已短信和邮箱通知求职者，您还可以：</h4>
				<!-- <table>
					<tr>
						<td class="tar">发送到邮箱</td>
						<td><input type="text" id="send_email" value="<?php echo $_smarty_tpl->getVariable('person_info')->value['email'];?>
"></td>
						<td><div class="sendBtn" data-way="1">发送</div></td>
					</tr>
					
					
				</table> -->
				<input type="hidden" id="send_offer_id">
				<input type="hidden" id="send_person_id">
				<div class="qrBox">
					<img src="" alt="" class="qrCode"><br>
					<span>微信扫码一键发送offer</span>
				</div>
			</div>
		</div>
		<!--<div class="hddSendPhone">-->
			<!--<div class="sendContent">-->
				<!--<table>-->
					<!--<tr>-->
						<!--<td class="tar">短信发送<i class="xiaoxx">*</i></td>-->
						<!--<td><input type="text" id="send_phone" value="<?php echo $_smarty_tpl->getVariable('person_info')->value['mobile_phone'];?>
"></td>-->
					<!--</tr>-->
					<!--<tr>-->
						<!--<td class="tar">邮箱发送</td>-->
						<!--<td><input type="text" id="send_email" value="<?php echo $_smarty_tpl->getVariable('person_info')->value['email'];?>
"></td>-->
					<!--</tr>-->
				<!--</table>-->
				<!--<div>-->
					<!--<div class="blueBtn_temp" data-way="2">发送</div>-->
				<!--</div>-->
			<!--</div>-->
		<!--</div>-->
		<script type="text/javascript">
			$(document).ready(function(){
				function snyputval(){
					// var val = $(this).val()
					// console.log('输入值',val)
					console.log('你好吗')
				}
			})
			var sendPhone="<?php echo $_smarty_tpl->getVariable('person_info')->value['mobile_phone'];?>
";
			var hddSaveTemp=$('.hddSaveTemp').remove();
//			var hddSendPhone=$('.hddSendPhone').remove();
			var typeNow=$('#tempalte_style').val();//当前选择的模板
			var isChange=false;//是否修改了offer模板
			var isSaveTemplate = false  //是否保存为模板
			var formats={
				br2p:function(str){
					return '<p>'+str.replace(/\n/g,'</p><p>')+'</p>'
				},
				br2br:function(str){
					return str.replace(/\n/g,'<br>')
				},
			}
			if(localStorage){
				//获取数据
				var localLists={
					wel:localStorage.getItem('wel')?JSON.parse(localStorage.getItem('wel')):[],
					sup:localStorage.getItem('sup')?JSON.parse(localStorage.getItem('sup')):[],
					matter:localStorage.getItem('matter')?JSON.parse(localStorage.getItem('matter')):[],
					thing:localStorage.getItem('thing')?JSON.parse(localStorage.getItem('thing')):[],
				}
				//初始化历史记录
				function initHis(list){
					if(localLists[list].length===0){
						$('.editHistory[data-list='+list+']').remove()
						return false;
					}
					if($('.editHistory[data-list='+list+']').length===0){
						$('.editOk[data-list='+list+']').after('<div class="editHistory" data-list='+list+'><h6>历史使用</h6><ul></ul></div>')
			
					}
					var text='';
					for(var i=0;i < localLists[list].length;i++){
										// $.each(localLists[list],function(i,e){
											text+='<li class="historyItem" data-index='+i+' data-for='+localLists[list][i]+'><div class="historyDele">×</div><div class="historyText" data-text='+localLists[list][i].text+'>'+(localLists[list][i].text.length<25?localLists[list][i].text:(localLists[list][i].text.substring(0,25)+'...'))+'</div></li>'
						}
					$('.editHistory[data-list='+list+'] ul').html(text)
				}
				//存为localStorage
				function saveLocal(list){
					localStorage.setItem(list,JSON.stringify(localLists[list]))
				}
				//调用初始化
				$('.editOk[data-save]').each(function(i,e){
					initHis($(e).attr('data-list'))
				})
				// 点击历史记录
				$('.editView').on('click','.historyText',function(){
					var parent=$(this).parent();
					// var index=parent.attr('data-index');
					var list=parent.attr('data-for');
					$('#'+list).val($(this).attr("data-text"))
					// var li=localLists[list].splice(index,1)
					// saveLocal(list)
				})
				//删除历史记录
				$('.editView').on('click','.historyDele',function(){
					var parent=$(this).parent();
					var list=parent.attr('data-for');
					var index=parent.attr('data-index');
					localLists[list].splice(index,1)
					initHis(list)
				})
			}
			
			
			//点击编辑
			$('.rightEditBtn').on('click',function(){
				// $('.rightBtnBox').hide()
				$(".showRightBox").show()
				$(".rightBtnBox").hide()
				// $('.editBox').show()
				
			})
			
			//关闭编辑
			$('.editCloseBtn').on('click',function(){
				//$('.editOk').click()
				$(".showRightBox").hide()
				$(".rightBtnBox").show()
				// $('.rightBtnBox').show()
				// $('.editBox').hide()
				
			})
			
			//监听右侧滚动区域,高亮标题
			// $('#editBody').on('scroll',function(){
			// 	var num = [0,1,2,3,4,5,6];
			// 	var divScrollTop = $('#editBody').scrollTop();
			// 	// console.log('div滚动高度',$('#editBody').scrollTop())
			// 	var addId =  '';
			// 	var Dheigth = '';
			// 	var secondHeight = '';
			// 	var second = '';
			// 	num.forEach(item=>{
			// 		addId = 'chooseContent' + item;
			// 		second = 'chooseContent' + (item+ 1);
			// 		// console.log(second)
			// 		// console.log(document.getElementById(addId).offsetTop)
			// 		if(document.getElementById(addId)){
			// 			Dheigth = document.getElementById(addId).offsetTop;
			// 		}
			// 		if(document.getElementById(second)){
			// 			secondHeight = document.getElementById(second).offsetTop;
			// 		}
			// 		// if(divScrollTop == Dheigth){
			// 		// if(divScrollTop >= Dheigth && secondHeight >= divScrollTop ){
			// 		// 	// console.log(document.getElementById(addId))
			// 		// 	console.log(item)
			// 		// 	$('.editTab').eq(item).addClass('cut').siblings().removeClass('cut')
			// 		// }
			// 	})
				
			// });
			
			//选择编辑的模块啊
			// $('.editTab').on('click',function(){
			// 	$('.editTab,.editView').removeClass('cut')
			// 	$('.editView').eq($(this).addClass('cut').index()).addClass('cut')
			// })
			$('.userIcon').on('click',function(){
				$(".showRightBox").show()
				$(".rightBtnBox").hide()
				document.getElementById('editBody').scrollTop = 0
				$('.editTab').eq(0).addClass('cut').siblings().removeClass('cut')
			})
			$('.jobicon').on('click', function(){
				$(".showRightBox").show()
				$(".rightBtnBox").hide()
				var Dheigth = document.getElementById('chooseContent1').offsetTop
				document.getElementById('editBody').scrollTop = Dheigth
				$('.editTab').eq(1).addClass('cut').siblings().removeClass('cut')
				$('html,body').animate({scrollTop:0},300)
			})
			$('.sxIcon').on('click',function(){
				$(".showRightBox").show()
				$(".rightBtnBox").hide()
				var Dheigth = document.getElementById('chooseContent3').offsetTop
				document.getElementById('editBody').scrollTop = Dheigth
				$('.editTab').eq(3).addClass('cut').siblings().removeClass('cut')
				$('html,body').animate({scrollTop:0},300)
			})
			$('.clIcon').on('click',function(){
				$(".showRightBox").show()
				$(".rightBtnBox").hide()
				var Dheigth = document.getElementById('xdcl').offsetTop
				document.getElementById('editBody').scrollTop = Dheigth
				$('.editTab').eq(3).addClass('cut').siblings().removeClass('cut')
				$('html,body').animate({scrollTop:0},300)
			})
			$('.smIcon').on('click',function(){
				$(".showRightBox").show()
				$(".rightBtnBox").hide()
				var Dheigth = document.getElementById('chooseContent4').offsetTop
				document.getElementById('editBody').scrollTop = Dheigth
				$('.editTab').eq(4).addClass('cut').siblings().removeClass('cut')
				$('html,body').animate({scrollTop:0},300)
			});
			
			$('.comIcon').on('click',function(){
				$(".showRightBox").show()
				$(".rightBtnBox").hide()
				$('html,body').animate({scrollTop:0},300)
				// var Dheigth = document.getElementById('chooseContent5').offsetTop - 10
				// console.log(Dheigth)
				// document.getElementById('').scrollTop = Dheigth
				$('#editBody').animate({scrollTop:2039},300)
				$('.editTab').eq(5).addClass('cut').siblings().removeClass('cut')
				// $('html,body').animate({scrollTop:0},300)
			})
			$('.editTab').on('click',function(){
				$(this).addClass('cut').siblings().removeClass('cut')
				// var topJu = document.getElementById('editBody').offsetTop
				// var bb = document.getElementById('ttt').offsetTop
				var addId =  'chooseContent' + $(this).index()
				
				var Dheigth = document.getElementById(addId).offsetTop
				$('#editBody').animate({
					scrollTop: Dheigth
					},300)
				// $(window).scroll(function () {
				// //     var top = $(window).scrollTop() + 200;
				// //     var left = $(window).scrollLeft() + 320;
				//     $("#editBody").animate({ "top": Dheigth }, 500); //方式一 效果比较理想
				//     //$("#editInfo").css({ left: left + "px", top: top + "px" }); 方式二 有阴影
				//   });
				// document.getElementById('editBody').scrollTop = Dheigth
				// document.getElementsByClassName('editBody').scrollTop()
				 // $('#ttt').scrollTop('30')
				 // console.log('获取的下标id',addId)
				 // console.log('相对于父亲的高度',Dheigth)
				 // console.log('距离页面顶部距离',$('.editView').eq($(this).index()).offsetTop)
				// console.log('滚动的div距离顶部距离',topJu)
				// console.log('相对于父元素高度',bb)
			})
			
			//自定义滚动位置
			// function upload() {
			//     var btn = document.getElementById('main_btn'); 
			//     var x = btn.offsetTop + 1200;
			//     if (!!u.match(/AppleWebKit.*Mobile.*/)) {   //判断是否在移动端打开的
			//       x = btn.offsetTop + 400;
			//     }
			//     var timer = setInterval(() => {
			//       document.documentElement.scrollTop += 100
			//       if (document.documentElement.scrollTop >= x) {
			//         clearInterval(timer)
			//       }
			//     }, 20);
			//     var timer_1 = setInterval(() => {
			//       window.pageYOffset += 100
			//       if (window.pageYOffset >= x) {
			//         clearInterval(timer_1)
			//       }
			//     }, 20);
			//     var timer_2 = setInterval(() => {
			//       document.body.scrollTop += 100
			//       if (document.body.scrollTop >= x) {
			//         clearInterval(timer_2)
			//       }
			//     }, 20);
			//   }
			
			//输入值同步左侧的预览中
			$('#qzInput').bind('input propertychange',function(){
				var val = $(this).val();
				$('.job_user').html(val)
				$("input[name='template_job_user']").val(val);
				// console.log('输入值',val)
		    });
			
			$('#wel').bind('input propertychange',function(){
				var val = $(this).val();
				var textHtml = "<p class='text_content'>" + val + '</p>'
				console.log($('.job_intro').html())
				$('.job_intro').html(textHtml)
				// console.log('输入值',val)
				$("textarea[name='tpl_wellcome']").val(val);
				// $('.job_intro > p').html(val)
			});
			$('#comeSta').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				$("input[name='template_job_station']").val(val);
				if(val == ''){
					val = "待填写";
					$('.job_name').css("color",'red');
				}else{
					$('.job_name').css("color",'#514091');
				}
				$('.job_name').html(val);

			});
			$('#comeTime').bind('input propertychange',function(){
				var val = $(this).val();
				console.log('选择的时间',val)
				if(val==''){
					var showVal = '待填写'
					$('.job_time').css("color",'red');
					$('.job_time').html(showVal);
				}else{
					$('.job_time').css("color",'#514091');
				}
				$("input[name='template_job_join_time']").val(val);
			});
			$('#comeTime').on('click',function(){
			     // var spacialDates=$(this).attr('data-days').split(',');
			     WdatePicker({
			         //minDate:"#F{formatDate(firstDate)}",
			         minDate:"%y-#{%M-1}-#{%d}",
			        // maxDate:"#F{formatDate(lostDate)}",
			         maxDate:"",
			        // specialDates:spacialDates,
			         onpicked:function(res){
						 
			             curDate=new Date(this.value);
			             var msg_date = $('.comeTime').val();
			             // getChatHistory(msg_date);
						 $('.job_time').css("color",'#514091');
						 $('.job_time').html(msg_date);
						 $("input[name='template_job_join_time']").val(msg_date);
						 console.log('返回的选择时间值',msg_date)
			         },
					 oncleared:function(){
						 var msg_date = $('.comeTime').val();
						 
							 var showVal = '待填写'
							 $('.job_time').css("color",'red');
							 $('.job_time').html(showVal);
						 // $('.job_time').html(msg_date);
						 $("input[name='template_job_join_time']").val(msg_date);
						console.log($('.comeTime').val())
					 }
			     })
			 })
			$('#testTime').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				$("input[name='template_job_probation_time']").val(val);
				$('.job_test').html(val)
			});
			$('#inCome').bind('input propertychange',function(){
				var val = $(this).val();
				console.log('输入的薪资值',val)
				var text = "待填写"
				$("textarea[name='template_job_salary']").val(val);
				$('.job_money').html(val)
				if(val!==''){
					$('.job_money').css("color",'#514091')
				}else{
					$('.job_money').html(text)
					$('.job_money').css("color",'red')
				}
			});
			$('#fldy').bind('input propertychange',function(){
				var val = $(this).val();
				var text = "待填写"
				// console.log('输入值',val)
				$("textarea[name='template_job_reward']").val(val);
				$('.job_weal').html(val)
				if(val!==''){
					$('.job_weal').css("color",'#514091')
				}else{
					$('.job_weal').html(text)
					$('.job_weal').css("color",'red')
				}
			});
			$('#joinPlace').bind('input propertychange',function(){
				var val = $(this).val();
				var text = "待填写"
				// console.log('输入值',val)
				$("textarea[name='template_link_probation_addres']").val(val);
				$('.entry_place').html(val)
				if(val!==''){
					$('.entry_place').css("color",'#514091')
				}else{
					$('.entry_place').html(text)
					$('.entry_place').css("color",'red')
				}
			});
			$('#workPlace').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				var text = "待填写"
				$("textarea[name='template_link_work_addres']").val(val);
				$('.job_place').html(val)
				if(val!==''){
					$('.job_place').css("color",'#514091')
				}else{
					$('.job_place').html(text)
					$('.job_place').css("color",'red')
				}
			});
			$('#relaPeo').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				var text = "待填写"
				$("input[name='template_link_man']").val(val);
				$('.job_people').html(val)
				// if(val!==''){
				// 	$('.job_people').css("color",'#514091')
				// }else{
				// 	$('.job_people').html(text)
				// 	$('.job_people').css("color",'red')
				// }
			});
			$('#relapone').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				var text = "待填写"
				$("input[name='template_link_way']").val(val);
				$('.job_tele').html(val)
				// if(val!==''){
				// 	$('.job_tele').css("color",'#514091')
				// }else{
				// 	$('.job_tele').html(text)
				// 	$('.job_tele').css("color",'red')
				// }
			});
			$('#matter').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				$("textarea[name='template_matter_list']").val(val);
				$('.job_matter > p').html(val)
			});
			$('#thing').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				$("textarea[name='template_matter_means']").val(val);
				$('.job_thing > p').html(val)
			});
			$('#sup').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				$("textarea[name='template_footnote']").val(val);
				$('#job_sup > p').html(val)
			});
			$('#compayI').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				// $("textarea[name='template_footnote']").val(val);
				$('.tm_company > b').html(val)
			});
			$('#compayAdress').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				$("input[name='address']").val(val);
				$('.company_place').html(val)
			});
			$('#compayPone').bind('input propertychange',function(){
				var val = $(this).val();
				// console.log('输入值',val)
				$("input[name='link_mobile']").val(val);
				$('.company_tele').html(val)
			});
			//保存单个编辑的内容
			$('.editOk').on('click',function(){
				// 又不用验证了
				// 验证是否有输入内容
				// var parent=$(this).parents('.editView');
				// var flag=true;
				// if(parent.hasClass('companyView')){
				// 	//对企业名片单独处理
				// 	if(parent.find('.editShow').find(':checked').length===0 && ($('#company_place_ipt').val()==0||$('#company_tele_ipt').val()==0)){
				// 		flag=false;
				// 		//暂时用alert代替
				// 		$.message('请输入内容或不展示该模块')
				// 		return false
				// 	}
				// }else if(parent.hasClass('editView2')){
				// 	//对事项及材料单独处理
				// 	var myParent=$(this).parent();
				// 	if(myParent.find('.editShow').find(':checked').length===0&&myParent.find('textarea').val().length===0){
				// 		flag=false;
				// 		//暂时用alert代替
				// 		$.message('请输入内容或不展示该模块')
				// 		return false
				// 	}
				// }else{
				// 	//其余的editView都是一个checkbox对应一个输入框且一个页面只有一个确定按钮
				// 	parent.find('.editShow').each(function(i,e){
				// 		if($(e).find(':checked').length===0&&$(e).siblings('input,textarea').val().length===0){
				// 			flag=false;
				// 			//暂时用alert代替
				// 			$.message('请输入内容或不展示该模块')
				// 			return false
				// 		}
				// 	})
				// }
				// if(!flag)return false;
				
				//循环带入值
				$(this).parent().find('input[data-for],textarea[data-for]').each(function(i,e){
					//格式化字符串
					var text='';
					var format=$(this).attr('data-format')
					if(format!==undefined && formats[format]!==undefined){
						text=formats[format]($(e).val())
					}else{
						text=$(e).val()
					}
					//把值写入左边的预览中
					$('.'+$(e).attr('data-for')).html(text)
					$('[name='+$(e).prop('name').replace(/_ipt$/,'')+']').val($(e).val())
					
				})
				
				//保存历史记录
				if(localStorage){
					var name=$(this).attr('data-list');
					var text=$('#'+name).val();
					console.log(text)
					if(!text){return false}
					
					if($(this).attr('data-save')!==undefined){
						
						var thisList=localLists[name];
						var isRepeat=false;
						console.log(thisList)
						$.each(thisList,function(i,e){
							if(text==e.text){
								isRepeat=true;
								return false
							}
							
						})
						if(isRepeat){return false};
						thisList.unshift({
							for:name,
							text:text
						})
						if(thisList.length>3)thisList.pop()
						initHis(name)
						saveLocal(name)
					}
				}
			})
			
			
			//勾选不展示隐藏
			$('.editShow').on('click',function(e){
				if ($(e.target).is("input")){
					var type=$(this).attr('data-show')
					if($(this).find(':checked').length===0){
						$('#'+type).show()
					}else{
						$('#'+type).hide()
					}
					if(type==='job_people'||type==='job_tele'){
						console.log($('[data-show=job_people]').find(':checked').length)
						if($('[data-show=job_people]').find(':checked').length===1 && $('[data-show=job_tele]').find(':checked').length===1){
							$('#contact_people').hide()
						}else{
							$('#contact_people').show()
						}
					}
					isChange=true;
					isSaveTemplate=false;
				}
			})
			// 限制最大输入字数
			$('[data-maxl]').on('input propertychange',function(){
				var maxL=$(this).attr('data-maxl');
				if(this.value.length>maxL){
					this.value=this.value.substring(0,maxL)
				}
			})
			
			//字数统计
			$('[data-count]').on('input propertychange',function(){
				$(this).siblings('.textareaNum').find('span').html(this.value.length)
			})
			
			//改变ischange
			$('input[data-for],textarea[data-for]').on('input propertychange',function(){
				if(!$(this).attr('data-nochange')){
					isChange=true;
					isSaveTemplate=false;
				}
			})
			
			// 选择风格
			$('.typeItem').on('click',function(){
				var thisId = $(this).attr('data-type');
				if(thisId==typeNow)return false
				$(this).addClass('cut').siblings().removeClass('cut');
				var typeText=typeNow<10 ? ('0'+typeNow) : typeNow;
				var thisIdText=thisId<10 ? ('0'+thisId) : thisId;
				$('.template_center').removeClass('template_center'+typeText);
				$('.tm_content_bg').removeClass('tm_content_bg'+typeText);
				$('.tm_img_bg').removeClass('tm_img_bg'+typeText);
				$('.tm_content').removeClass('tm_content'+typeText);
				$('.tm_bgd_img').removeClass('tm_bgd_img'+typeText);
				if(thisId != '1'){
					$('.template_center').addClass('template_center'+thisIdText);
					$('.tm_content_bg').addClass('tm_content_bg'+thisIdText);
					$('.tm_img_bg').addClass('tm_img_bg'+thisIdText);
					$('.tm_content').addClass('tm_content'+thisIdText);
					$('.tm_bgd_img').addClass('tm_bgd_img'+thisIdText);
				}
				typeNow=thisId;
				$('#tempalte_style').val(typeNow)
			})
			
			
			// 选择模板
			$('.bodyTop em').on('click',function(){
				var invite_id = $("#invite_id").val();
				var job_id = $(this).attr("data-job_id");
				if(isChange){
					$.confirm('是否更换模板？',function(){
						//跳转页面
						location.href="<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/index/','data'=>'is_change=1&invite_id="+invite_id+"&template_job_id="+job_id+"'),$_smarty_tpl);?>
"
					})
				}else{
					//跳转页面
					location.href="<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/index/','data'=>'is_change=1&invite_id="+invite_id+"&template_job_id="+job_id+"'),$_smarty_tpl);?>
"
				}
			})
			
			//删除模板
			$('.tempDele').on('click',function(){
				var template_id = $(this).attr("data-temp_id");
				$.confirm('确定删除？',function(){
					$.ajax({
						type: 'post',
						url: "<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/delOfferTemplate'),$_smarty_tpl);?>
",
						data: {template_id:template_id},
						dataType: 'json',
						success: function (data) {
							if(!data.status){
								$.message(data.msg);
								return false;
							}

							$.message(data.msg,function(){
								window.location.reload();
							});
							
							return false;
						}
					})
				})
				return false;
			});
			
			$('body').on('change','#saveName', function (e) {
				console.log($(this).val());
			   $('#hddModName').val($(this).val());
			});
			// 提交保存
			function saveOfferTemplateMod(){
				var invite_id = $("#invite_id").val();
				var template_job_id = $("#hddModName").val();
				if(template_job_id == 'tongyong'){
					template_job_id = '';
				}
				$.ajax({
					type: 'post',
					url: "<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/saveOfferTemplate'),$_smarty_tpl);?>
",
					data: $("#form1").serialize(),
					dataType: 'json',
					success: function (data) {
						if(!data.status){
							$.message(data.msg);
							return false;
						}
						isSaveTemplate = true;
						$.message(data.msg,function(){
//							window.location.reload();
							location.href="<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/index/','data'=>'is_change=1&invite_id="+invite_id+"&template_job_id="+template_job_id+"'),$_smarty_tpl);?>
"
						});
						
						return false;
					}
				})
			}

			// 发送offer
			function sendOffer(){
				$.ajax({
					type: 'post',
					url: "<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/SendOffer'),$_smarty_tpl);?>
",
					data: $("#form1").serialize()+"&send_phone="+$('#send_phone').val() + "&send_email=" + $('#send_email').val(),
					dataType: 'json',
					success: function (data) {
						if(!data.status){
							$.message(data.msg);
							return false;
						}
						isSaveTemplate = true;
						$("#send_offer_id").val(data.send_offer_id);
						$("#send_person_id").val(data.person_id);
						var dialog=$.message($('.hddSendTemp').html(),{
							contentType:'html',
							width:450,
							showOk:false
						})
						dialog.onclose(function(){
							location.href=location.href
						})
						dialog.find('.qrCode').prop('src',data.img_url);
						
						return false;
					}
				})
			}
			
			//判断是否邮箱发送
			function isEmile() {
				if($('#send_email').val().length == 0) {
					$('.tongzhi').html('已短信通知求职者，您还可以：')
				}else {
					$('.tongzhi').html('已短信和邮箱通知求职者，您还可以：')
				}
				return
			}
			
			
			//保存模板
			$(".rightSaveBtn").click(function(){
				$("#hddModName").val("tongyong");
				$.confirm(hddSaveTemp.html(),function(){
					checkSaveTemplate(1);
				});
				
			});

			function checkSaveTemplate(type){

				var template_type = $('#hddModName').val();
				if(template_type == '' || template_type == undefined){
					$.message('请选择需要保存的模板');
					return false;
				}
				$.ajax({
					type: 'post',
					url: "<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/CheckSaveTemplate'),$_smarty_tpl);?>
",
					data: {template_type:template_type},
					dataType: 'json',
					success: function (data) {
						if(!data.status){
							if(data.code == 310){
								$.confirm(data.msg,function(){
									if(type == 1){
										saveOfferTemplateMod();
									}else{
										sendOffer();
									}

								});
								return false;
							}else{
								$.message(data.msg);
								return false;
							}
						}
						if(type == 1){
							saveOfferTemplateMod();
						}else{
							sendOffer();
						}
					}
				})
			}

			//点击发送
			$(".bottomBtn").click(function(){
				var send_phone = $("#send_phone").val();

				if(send_phone == '' || send_phone.length == 0 || send_phone == undefined){
					$.message('请输入求职者手机号');
					return false;
				}
				//异步验证是否有数据为空
				$.ajax({
					type: 'post',
					url: "<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/CheckOfferData'),$_smarty_tpl);?>
",
					data: $("#form1").serialize(),
					dataType: 'json',
					success: function (data) {
						if(data.status){
							//已对模板进行修改，但未保存为新模板，二次弹框提示“确定发送offer吗？是否需要保存为模板？
							if(isChange && !isSaveTemplate){
								var saveConfirm=$.confirm('是否需要保存为模板？','提示',
									function(){
										isEmile();
										sendOffer();
									}
								);
								saveConfirm.find('._dialogOk').html('直接发送')
								saveConfirm.find('._dialogCancel').html('保存模板并发送').on('click',function(){
									$("#send_type").val(2);
									$("#hddModName").val("tongyong");
									$.confirm(hddSaveTemp.html(),function(){
										var template_type = $('#hddModName').val();
										if(template_type == '' || template_type == undefined){
											$.message('请选择需要保存的模板');
											return false;
										}
										$.ajax({
											type: 'post',
											url: "<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/CheckSaveTemplate'),$_smarty_tpl);?>
",
											data: {template_type:template_type},
											dataType: 'json',
											success: function (data) {
												if(!data.status){
													
													if(data.code == 310){
														$.confirm(data.msg,function(){
															isEmile();
															sendOffer();
														});
														return false;
													}else{
														$.message(data.msg);
														return false;
													}
												}else{
													isEmile();
													sendOffer();
												}
											}
										});
									});
								});
							}else{
								isEmile();
								sendOffer();
							}
						}else{
							if(data.code == 501){
								$.message(data.msg);
								window.location.reload();
							}
							$.message(data.msg)
						}
					}
				})
			});
			
			
			$(document).on('click','.sendBtn',function(){
				var sendWay=$(this).attr('data-way');
				var linkNum=$(this).parents('tr').eq(0).find('input').val();
				var send_offer_id = $("#send_offer_id").val();
				var send_person_id = $("#send_person_id").val();
				$.ajax({
					type: 'post',
					url: "<?php echo smarty_function_get_url(array('rule'=>'/offertemplate/OtherSendOffer'),$_smarty_tpl);?>
",
					data: {send_type:sendWay,linkNum:linkNum,send_offer_id:send_offer_id,person_id:send_person_id},
					dataType: 'json',
					success: function (data) {
						if(!data.status){
							$.message(data.msg);
							return false;
						}
						$.message(data.msg);
					}
				})
			})
			
			
			$('[name="tpl_wellcome_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.huanyingcheckbox').prop('checked',false)
				$('#job_intro').show()
			});   //欢迎语
			
			$('[name="template_job_station_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.ruzhicheckbox').prop('checked',false)
				$('#job_name').show()
			});   //入职职位
			
			$('[name="template_job_join_time_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.timecheckbox').prop('checked',false)
				$('#job_time').show()
			});   //入职时间
			
			$('[name="template_job_probation_time_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.shiyongcheckbox').prop('checked',false)
				$('#job_test').show()
			});   //试用期
			
			$('[name="template_job_salary_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.xinzicheckbox').prop('checked',false)
				$('#job_money').show()
			});   //综合薪资
			
			$('[name="template_job_reward_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.fulicheckbox').prop('checked',false)
				$('#job_weal').show()
			});   //福利待遇
			
			$('[name="template_link_probation_addres_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.didiancheckbox').prop('checked',false)
				$('#entry_place').show()
			});   //入职地点
			
			$('[name="template_link_work_addres_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.shangbancheckbox').prop('checked',false)
				$('#job_place').show()
			});   //上班地点
			
			$('[name="template_link_man_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.lianxirencheckbox').prop('checked',false)
				$('#job_people').show()
			});   //联系人
			
			$('[name="template_link_way_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.lianxidianhuacheckbox').prop('checked',false)
				$('#job_tele').show()
			});   //联系电话
			
			$('[name="template_matter_list_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.ruzhishixiangcheckbox').prop('checked',false)
				$('#job_matter').show()
			});   //入职事项
			
			$('[name="template_matter_means_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.xiedaicailiaocheckbox').prop('checked',false)
				$('#job_thing').show()
			});   //携带材料
			
			$('[name="template_footnote_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.buchongcheckbox').prop('checked',false)
				$('#job_sup').show()
			});   //补充说明
			
			$('[name="address_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.qiyemingpiancheckbox').prop('checked',false)
				$('#company_all').show()
			});   //企业名片
			
			$('[name="link_mobile_ipt"]').bind('input propertychange', function(){
				if($(this).val().length == 0){
					return
				}
				$('.qiyemingpiancheckbox').prop('checked',false)
				$('#company_all').show()
			});   //企业名片
			
			
			// var backurl=document.referrer;
			// var cookieurl=getCookie('backurl');
			// if(backurl){
			// 	if(cookieurl){
			// 		
			// 	}else{
			// 		setCookie('backurl',backurl)
			// 	}
			// }else{
			// 	if(cookieurl){
			// 		backurl=getCookie('backurl');
			// 	}else{
			// 		backurl=""
			// 	}
			// }
			// $('.header a').on('click',function(){
			// 	if(backurl){
			// 		location.href=backurl;
			// 		delCookie('backurl');
			// 		return false
			// 	}
			// })
			// 
			// function setCookie(name,value)
			// {
			// var Days = 30;
			// var exp = new Date();
			// exp.setTime(exp.getTime() + Days*24*60*60*1000);
			// document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
			// }
			// function getCookie(name)
			// {
			// var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
			// if(arr=document.cookie.match(reg))
			// return unescape(arr[2]);
			// else
			// return null;
			// }
			// function delCookie(name)
			// {
			// var exp = new Date();
			// exp.setTime(exp.getTime() - 1);
			// var cval=getCookie(name);
			// if(cval!=null)
			// document.cookie= name + "="+cval+";expires="+exp.toGMTString();
			// }
		</script>
	</body>
</html>

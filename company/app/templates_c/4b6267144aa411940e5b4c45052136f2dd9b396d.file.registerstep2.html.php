<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:19:36
         compiled from "app\templates\./register/registerstep2.html" */ ?>
<?php /*%%SmartyHeaderCode:72915e7033b8605123-72938654%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4b6267144aa411940e5b4c45052136f2dd9b396d' => 
    array (
      0 => 'app\\templates\\./register/registerstep2.html',
      1 => 1584332291,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '72915e7033b8605123-72938654',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
if (!is_callable('smarty_modifier_replace')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.replace.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="Keywords" content="" />
<meta name="Description" content="" />
<title>企业完善资料</title>
<!–[if lt IE9]>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'html5.js'),$_smarty_tpl);?>
"></script>
<![endif]–>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'version.js'),$_smarty_tpl);?>
"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'comback.css'),$_smarty_tpl);?>
" />
    <!--
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'common.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'dialog.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.form.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'ui_inputFocus.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'ui_dropdownlist.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'ui_validate.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'ui_area.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'ui_uploadify.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'ui_calling.js'),$_smarty_tpl);?>
"></script>
    -->
	<script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=33f9256a1a1ba5a80eb40f8ed45bce3c"></script>

<script type="text/javascript">
window.CONFIG = {
	HOST: '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
',
	COMBOPATH: '/js/v2/'
}
</script>
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
<script type="text/javascript">hbjs.loadJS('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/js/v2/cqjob/common.js');</script>
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'new_step.css'),$_smarty_tpl);?>
" />
</head>
<body id="body">
<!--公共-->
<style type="text/css">
.banner{width:100%; height:79px; overflow:hidden; position:relative; z-index:1;}
header,.hdCon{ background:#f0f0f0;}
.hdCon{ height:30px;}
.hdL .logo{ height:30px; line-height:30px; color:#8e8e8e; font-family:"宋体"; font-size:12px;}
.hdR ul li,.hdR ul li a.lnk,.hdR ul .navLst a{ height:30px; line-height:30px;}
.hdR ul .navLst i{ margin:10px 5px 0 0;}
.hdR ul li.wemChatlist{width:100px;}
.hdR ul li.wemChatlist a,.hdR ul li.wemChatlist a:hover{ height:30px; line-height:32px; color:#888;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/weChat06.png) 0 center no-repeat}
.hdR ul li.navLst a:hover,.hdR ul li.wemChatlist a:hover,.hdR ul li.tcomInfo a:hover,.hdR ul li.thelpInfo a:hover{ color: #666;}
.compNavbg{width:100%; overflow:hidden; background:#fff; margin-bottom:20px;}
.compNav{width:1000px; height:50px; overflow:hidden; margin:0 auto; text-align:left;}
.compLogo{ display:block; float:left;width:144px; height:30px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/compLogo.png) no-repeat; margin:10px 0;}
.compList{ float:left; padding-left:60px;}
.compList li{ float:left;}
.compList li a{ display:block; width:105px; text-align:center;height:50px; line-height:50px; font-size:16px; font-family:"微软雅黑"; color:#2a2623;position: relative;}
.compList li a:hover{text-decoration: none;color:#4c4b49;}
.compList li.cut a{ font-weight:bold; color:#004d92; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany02.png) center bottom no-repeat;}
.compList li.cut2 a{ font-weight:bold; color:#004d92; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/weChat07.png) center bottom no-repeat;}
.comPost{ display:block; float:right;width:110px; height:30px; line-height:30px; font-family:"微软雅黑"; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany03.png) 15px center no-repeat #66bce4; text-indent:40px; margin-top:10px; color:#fff; border-radius:4px;font-size:14px;}
.comPost:hover{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany03.png) 15px center no-repeat #5ca8cc; color:#fff;text-decoration: none;}
.comPost:focus,.comPost:active{background-color: #4aa2f2 }
.subcompNavbg{width:100%; overflow:hidden; background:#2b6fad; height:36px; text-align:left;}
.subcompMage{width:1000px; margin:0 auto;}
.subcompNav{ float:left; overflow:hidden;}
.subcompNav li{ float:left; display:none;}
.subcompNav li a{ display:block; float:left; color:#fff; color:#d2e5f6; font-family:"微软雅黑"; height:16px; line-height:16px; padding:0 20px; border-right:1px solid #23598c;border-left:1px solid #368ad9; margin-top:11px; font-size: 14px;}
.subcompNav li a.first{ border-left:none;}
.subcompNav li a.last{ border-right:none;}
.subcompNav li a.cut{ font-weight:bold; color:#fff;}
.subcompNav li a:hover{color:#fff;text-decoration: none;}
.subcompNav li.tabList3{ margin-left:97px;_margin-left:48px;}
.subcompNav li.tabList4{ margin-left:217px; _margin-left:108px;}
.subcompRt{ float:right;}
.subcompRt a{ display:block; height:36px; padding-left:20px; line-height:36px; color:#d2e5f6; float:left; font-family:"微软雅黑"; font-size: 14px; }
.subcompRt a.compHome{ background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany04.png) left center no-repeat; margin-right:20px;}
.subcompRt a.compHome:hover{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany04cut.png) left center no-repeat;color:#fff;}
.subcompRt a.compSch{ background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany05.png) left center no-repeat;}
.subcompRt a.compSch:hover{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/hbcompany05cut.png) left center no-repeat;color:#fff;}
.hdR ul li a em.num{left:25px; top:2px;}
.comPostBg,.comPostBgcut{ width:110px; height:50px; overflow:hidden; float:right;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/weChat07.png) center bottom no-repeat;}

.notice_icon {background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/bubble.png);width:23px; height:21px; font-size: 12px; font-family: "宋体"; line-height: 18px; color:#fff; position: absolute;top:5px; right:4px; text-align: center;}
.drop .dropLst{z-index: 9999}
.textarea {color: #999}
#hidRightSpot {height: 60px;}
#hidRightSpot::-webkit-input-placeholder {
    /* WebKit browsers */
    color: #9c9c9c;
}
#hidRightSpot:-moz-placeholder {
    /* Mozilla Firefox 4 to 18 */
    color: #9c9c9c;
}
#hidRightSpot::-moz-placeholder {
    /* Mozilla Firefox 19+ */
    color: #9c9c9c;
}
#hidRightSpot::-ms-input-placeholder {
    /* Internet Explorer 10+ */
    color: #9c9c9c;
}
.showTip {margin-left: 225px;margin-right: 196px;position: relative;}
.showTip .tip {display: none;padding: 10px;box-sizing: border-box;color: #666;font-size: 14px;background:#e5f2f8;box-shadow:0px 2px 5px 0px rgba(239,239,239,0.75);position: absolute;z-index: 9999;top: 28px;}
.showTip .example {color: #2B6FAC;font-weight: 400;width: 28px;cursor: default;padding: 0 0 10px 20px;position: relative;}
.showTip .example img {position: absolute;left: 0;top: 50%;margin-top: -13px;}
.showTip .example:hover {color: #0af;}
.showTip .example:hover + .tip {display: block;}
.showTip .tip::before {content: '';position: absolute;border-width: 10px;border-color: transparent transparent #e5f2f8 transparent;border-style: solid;top: -14px;left: 6px;}
</style>
<?php $_template = new Smarty_Internal_Template('register/register_head.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('head_step',2); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<div class="content step-content" id="content">
<div class="step-tit"><i class="icon-face"></i>请完善公司资料，让求职者更了解您的企业，增加简历投递量</div>
    <div class="fstBack" id="fstBack"></div>
    <section class="section">
    	<form id="baseInforForm">
            <div class="mod">
                <h2 id="jbzl" class="yahei">基本资料</h2>
                <div class="form">
                
                    <div class="formMod">
                        <div class="l">公司名称<i>&nbsp;</i></div>
                         <span class="formText" style="margin-top: 10px">
                                <!--<input id="a_company_name" name="a_company_name" value="<?php echo $_smarty_tpl->getVariable('company')->value['company_name'];?>
" class="text" style="width:370px;"  />-->
                             <?php echo $_smarty_tpl->getVariable('company')->value['company_name'];?>

                            </span>
                            
                            <span class="tipPos">
                            	<span class="tipLay" data-for="a_company_name"></span>
                            </span>
                        <div class="clear"></div>
                    </div>
                   
                    <div class="formMod">
                         <a id="shortname" name="shortname"></a>
                        <div class="l">公司简称<i>*</i></div>
                        <div class="r">
                            <span class="formText">
                                <input type="text" id='hidCompanyShortName' value="<?php if (!empty($_smarty_tpl->getVariable('company',null,true,false)->value['company_shortname'])){?><?php echo $_smarty_tpl->getVariable('company')->value['company_shortname'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('company')->value['company_name'];?>
<?php }?>" class="text" style="width:370px;" name='hidCompanyShortName' />
                            </span>
                            
                            <span class="tipPos">
                                <span class="tipLay" data-for="hidCompanyShortName"></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formMod">
                        <a id="property" name="pro"></a>
                        <div class="l">公司性质<i>*</i></div>
                        <div class="r">
                            <span class="drop zIndex" id="company_property"></span>
                            <span class="tipPos">
                                <span class="tipLay" data-for="hddComProperty"></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>
					<div class="formMod">
					    <a id="property" name="pro"></a>
					    <div class="l" class="company_financex_is">融资阶段
                            <?php if (in_array($_smarty_tpl->getVariable('company')->value['calling_ids'],array('01','03','04','05'))){?>
                            <i>*</i>
                            <?php }?>
                        </div>
					    <div class="r">
					        <span class="drop zIndex" id="company_finance"></span>
					        <span class="tipPos">
					            <span class="tipLay">
									<span class="tipLayErr tipw120" id="company_finance_is" style="display: none;">请选择融资阶段<span class="tipArr"></span></span>
					            </span>
					        </span>
					    </div>
					    <div class="clear"></div>
					</div>
					
                    <div class="formMod JobIndDrop checkMod" >
                        <a id="calling" name="cal"></a>
                        <div class="l">所处主行业<i>*</i></div>
                        <div class="r">
                            <span id="newCalling" class="drop formText JobIndDrop zIndex" style="width:250px;border-color:#d0cbcb;height:34px">
                                <span>
                                    <div class="dropSet"><b class="hbFntWes dropIco"></b><span id='addMainCalling'>
                                        <?php if (!empty($_smarty_tpl->getVariable('calling_names',null,true,false)->value[0])){?><span class="seled"><?php echo $_smarty_tpl->getVariable('calling_names')->value[0];?>
</span><?php }?>
                                        </span>
                                        <input class="text JobCay" type="text">
                                    </div>
                                </span>
                                <input type="text" name="main_calling" id="main_calling" value="<?php echo $_smarty_tpl->getVariable('calling_arr')->value[0];?>
" style="position:absolute;left:-9999px;top:0;" />
                            </span>
                            <!--<a href="javascript:void(0)" id="addHtml" style="font-size: 12px;margin-top: 5px;display:inline-block;" onclick="return addCalling()">+添加次行业</a>-->
                            <span class="tipPos">
                                <span class="tipLay" data-for="main_calling"></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    
                    <div id="hidLastCalling" class="formMod JobIndDrop checkMod" style="display:none">
                        <a id="calling" name="cal"></a>
                        <div class="l">次要行业<i>&nbsp;</i></div>
                        <div class="r">
                            <span id="nextCalling" class="drop formText JobIndDrop zIndex" style="width:250px;border-color:#eee">
                                <span>
                                    <div class="dropSet"><b class="hbFntWes dropIco"></b>
                                        <span id='addNextCalling'></span>
                                        <input class="text JobCay" type="text">
                                    </div>
                                </span>
                                <input type="text" name="next_calling" style="position:absolute;left:-9999px" >
                            </span>
                            <a id="nextCallingBtn" href="javascript:void(0)" style="font-size: 12px;margin-top: 5px;margin-left:13px;display: inline-block" >删除</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                         <div class="formMod">
                        <div class="l">公司规模<i>*</i></div>
                        <div class="r">
                            <span class="drop zIndex" id="company_size"></span>
                            <span class="tipPos">
                                <span class="tipLay" data-for="hddComsize"></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formMod" style="margin-bottom: 5px">
                        <a id="spot" name="spot"></a>
                        <div class="l">公司优势<i>*</i></div>
                        <div class="r">
                            <span class="formTextarea">
                                <textarea class="textarea" id='hidRightSpot' name="hidRightSpot" placeholder="向求职者展现公司实力（如行业地位、奖项成就、亮点特色、发展愿景、培训/晋升机制等），吸引更多投递，最多50字"><?php echo $_smarty_tpl->getVariable('company')->value['company_bright_spot'];?>
</textarea>
                            </span>
                            <span class="tipPos">
                                <span class="tipLay" data-for="hidRightSpot"></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formMod showTip">
                        <span class="example"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/shili.png">示例</span>
                        <div class="tip">
                            <span id="spot_detail"><?php echo $_smarty_tpl->getVariable('spots')->value;?>
</span>
                        </div>
                    </div>
                    <div class="formMod">
                        <div class="l">公司简介<i>*</i></div>
                        <div class="r">
                            <span class="formTextarea">
                                <textarea class="textarea" name="info"><?php echo $_smarty_tpl->getVariable('company')->value["info"];?>
</textarea>
                            </span>
                            <span class="tipPos">
                                <span class="tipLay" data-for="info"></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formMod">
                        <a id="linkman" name="linkm"></a>
                        <div class="l">联系人<i>*</i></div>
                        <div class="r">
                            <span class="formText">
                                <input type="text" class="text" style="width:180px;" name="linkman" value="<?php echo $_smarty_tpl->getVariable('company')->value['linkman'];?>
">
                            </span>
                            <input name="open_linkman" type="hidden" value="1"/>
                            <span class="tipPos">
                                <span class="tipLay" data-for="linkman"></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>

                   <div class="formMod">
                        <div class="l">座机<i>&nbsp;</i></div>
                        <div class="r" id="phoneDiv">
                            <span class="formText">
                            	
                                <input type="text" placeholder="区号" style="width:45px;" class="text" name="zone_infor" value="<?php if ($_smarty_tpl->getVariable('phone_infor')->value['zone']=='区号'){?>023<?php }else{ ?><?php echo $_smarty_tpl->getVariable('phone_infor')->value['zone'];?>
<?php }?>" id="zoneNo">
                            </span>
                            <span class="tipTxt">&nbsp;</span>
                            <span class="formText">
                                <input type="text" style="width:143px;" class="text" name="phone_infor" value="<?php echo $_smarty_tpl->getVariable('phone_infor')->value['phone'];?>
" id="phoNo" placeholder="固定电话">
                            </span>
                            <span class="tipTxt">&nbsp;</span>
                            <span class="formText">
                                <input type="text" style="width:45px;" class="text" name="ext_infor" value="<?php if ($_smarty_tpl->getVariable('phone_infor')->value['ext']=='分机号'){?><?php }else{ ?><?php echo $_smarty_tpl->getVariable('phone_infor')->value['ext'];?>
<?php }?>" id="extNo" placeholder="分机号">
                            </span>
                            <span class="tipPos">
                                <span class="tipLay" data-for="zoneNo"></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="formMod">
                        <div class="l">手机<i>*</i></div>
                        <div class="r">
                            <span class="formText">
                                <input type="text" style="width:125px;" class="text" id="mobile" name="link_mobile" value="<?php echo $_smarty_tpl->getVariable('company')->value['link_mobile'];?>
" placeholder="请填写手机号码">
                            </span>
                            <span class="tipPos">
                                <span class="tipLay" data-for="link_mobile"></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    
                    <div class="formMod" id="codeDiv" style="margin-left:10px;display: none">
                        <div class="clearfix">
                            <p class="addTel" style="height: 36px;line-height: 36px;text-align: left; position: relative;width:700px; margin-left: 215px">
                                <input type="text" name="authCode" id="authCode" style="display:block;text-indent:5px;width: 135px; float: left; height: 36px; line-height: 36px; border:1px solid #cfcfcf; color: #333; font-size: 15px;" value="" placeholder="请输入短信验证码">
                                <span class="authCodex" style="display:block;float:left;height:36px;line-height:36px;background:#64bce4;padding: 0px 12px;color: #fff;border-radius: 4px;margin-right: 15px; font-size: 15px; margin-left: 15px; cursor: pointer">获取验证码</span>
                                <span class="authCodetipLay" data-for="authCode">
                            </span>
                            </p>
                        </div>
                    </div>
                </div>

                <h2 id="dlwz" class="yahei"></h2>
                <div class="form">
                   <!-- <form id="addressForm" action="<?php echo smarty_function_get_url(array('rule'=>'/company/saveMap/'),$_smarty_tpl);?>
"> -->
                        <div class="formMod addressMod">
                            <a id="area" name="area"></a>
                            <div class="l">所在地区<i>*</i></div>
                            <div class="r">
                            	 <span class="formText zIndex" id="curarea">
    						     </span>
                                <span class="tipPos">
                                    <span class="tipLay" data-for="hidArea"></span>
                                </span>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="formMod">
                            <a id="addr" name="address"></a>
                            <div class="l">详细地址<i>*</i></div>
                            <div class="r">
                                <span class="formText zIndex" >
                                    <input type="text" style="width:425px;" class="text" id="txtAddress" name="txtAddress" value="<?php echo $_smarty_tpl->getVariable('company')->value['address'];?>
" />
                                    
                                </span>
                                <span class="tipPos">
                                    <span class="tipLay" data-for="txtAddress"></span>
                                </span>
                                <a id="addr"  onclick="return findMap()" style="font-size: 12px;margin-top: 5px;display: inline-block;color:#3D84B8; cursor:pointer;display:none"><i class="hbFntWes" style="font-size: 14px">&#xf041;</i> 在地图中标记</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- 地图标记 -->
                        <div class="formMod">
                            <a id="map"></a>
                            <div class="l"></div>
                            <div class="r">
                                <span id="mapCon" class="formMap" style="width:435px;height:290px;">

                                </span>
                                <input type="hidden" id="hidMapX" name="hidMapX" value="" />
                                <input type="hidden" id="hidMapY" name="hidMapY" value="" />
                                <input type="hidden" id="hidMapZoom" name="hidMapZoom" value="" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="formBtn" style="padding:10px 0"><a href="javascript:void(0);" id="btnSaveAddress" class="btnsF16 btn1">下一步</a></div>
                </div>
            </div>
        </form>
    </section>
</div>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<section class="floatRT"><a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/about/message" target="_blank" class="serviceLink">我有问题要反馈</a><b></b></section>
<script type="text/javascript">

hbjs.use('@actions, @hbCommon, @jobDialog, @jobDropList, @areaSimple, @validator', function(m){

	var $ = m['product.hbCommon'].extend(
		m['cqjob.actions'], m['cqjob.areaSimple'], m['cqjob.jobDialog'], m['cqjob.jobDropList']
	);
	var validatorForm = m['widge.validator.form'];
	
	$('input.text').placeHolder();
	
	
	/*
	$.focusblur('#addInfo');
    $.focusColor('input.text');
    $.focusColor('textarea.textarea');
    $.focusblur('input.conPeople');
    $.focusblur('input.conPhone');
    $.focusblur('input.newMail');
    $.focusblur('#zoneNo');
    $.focusblur('#phoNo');
    $.focusblur('#extNo');
    $.focusblur('#vdNam');
    $.focusblur('#vdSrc');
	*/
	
	var $jbzl = $('#jbzl').offset().top;
	var $dlwz = $('#dlwz').offset().top;
	
	var baseInforRuls = {
			hddComProperty:{required:true},
			main_calling:{required:true},
			hddComsize:{required:true},
			hidCompanyShortName:{required:true,range:[2,15]},
			hidRightSpot:{
				required:true,range:[5,50]
			},
			info:{
				required:true,range:[2,4000]
			},
			linkman :{required:true,range: [1, 10]},
			/*
			link_mobile:{mobile: true},
			phone_infor:{required:true, match:/^[0-9]{6,8}$/},
			zone_infor:{match: /^(([0-9]{3,4})|区号)$/},
			ext_infor:{match: /^(([0-9]+)|分机号)$/},
			*/
            link_mobile:{mobile: true},
			txtAddress:{required:true},
			hidArea:{required:true}
		},
		baseInforMessage = {
			hddComProperty:{required:'<span class="tipLayErr tipw120">请选择公司性质<span class="tipArr"></span></span>'},
			main_calling:{required:'<span class="tipLayErr tipw120">请选择公司行业<span class="tipArr"></span></span>'},
			hddComsize:{required:'<span class="tipLayErr tipw120">请选择公司规模<span class="tipArr"></span></span>'},
			hidCompanyShortName:{
				required:'<span class="tipLayErr tipw120">请输入公司简称<span class="tipArr"></span></span>',
				range:'<span class="tipLayErr tipw150">公司简称请输入2-15个字<span class="tipArr"></span></span>'
			},
			hidRightSpot:{
				required:'<span class="tipLayErr tipw120">请输入公司优势<span class="tipArr"></span></span>',
				range:'<span class="tipLayErr tipw150">请输入公司优势5-50个字<span class="tipArr"></span></span>'
			},
			info:{
				required:'<span class="tipLayErr tipw120">请输入公司简介<span class="tipArr"></span></span>',
				range:'<span class="tipLayErr tipw150">公司简介请输入2-4000个字<span class="tipArr"></span></span>'
			},
			linkman :{
				required  :'<span class="tipLayErr tipw120">请输入联系人姓名<span class="tipArr"></span></span>',
				range :'<span class="tipLayErr tipw150">联系人姓名不能超过10字<span class="tipArr"></span></span>'
			},
            link_mobile:{mobile:'<span class="tipLayErr tipw120">请输入正确的电话号码<span class="tipArr"></span></span>'},
			/*
			link_mobile:{mobile:'<span class="tipLayErr tipw120">请输入正确的电话号码<span class="tipArr"></span></span>'},
			zone_infor:{match:'<span class="tipLayErr tipw120">请输入正确的区号<span class="tipArr"></span></span>'},
			phone_infor:{
				required:'<span class="tipLayErr tipw120">请输入固定电话<span class="tipArr"></span></span>',
				match: '<span class="tipLayErr tipw120">请输入6-8位的数字<span class="tipArr"></span></span>'
			},
			ext_infor:{match:'<span class="tipLayErr tipw120">分机号码为数字<span class="tipArr"></span></span>'},
			*/
			txtAddress:{required:'<span class="tipLayErr tipw120">请输入公司地址<span class="tipArr"></span></span>'},
			hidArea:{required:'<span class="tipLayErr tipw120">请选择公司地区<span class="tipArr"></span></span>'}
		};
		
	var zoneNoRules = {
		zone_infor:{
			match: /^[0-9]{3}[0-9]?$/
		},
		phone_infor:{
			match: /^[0-9]{6,8}$/
		},
		ext_infor: 'number'
	};
	var zoneNoErrorMsg = {
		zone_infor:{match:'<span class="tipLayErr tipw120">请输入正确的区号<span class="tipArr"></span></span>'},
		phone_infor:{match: '<span class="tipLayErr tipw120">请输入6-8位的数字<span class="tipArr"></span></span>'},
		ext_infor:'<span class="tipLayErr tipw120">分机号码为数字<span class="tipArr"></span></span>'
	};
	var zoneNoRules1 = {
		zone_infor:{
			match: /^(\s*|[0-9]{3}[0-9]?)$/
		},
		phone_infor:{
			match: /^(\s*|[0-9]{6,8})$/
		},
		ext_infor: 'number'
	}
	var zoneNoGroup = {zoneNo: 'zone_infor phone_infor ext_infor'};
	var zoneNoName = 'zoneNo';
	
	var mobileRules = {
		link_mobile: 'mobile'
	};
	var mobileRules1 = {
		link_mobile: {match: /^(13[0-9]{9,9}|18[0-9]{9,9}|15[0-9]{9,9}|16[0-9]{9,9}|17[0-9]{9,9}|19[0-9]{9,9}|14[0-9]{9,9})$/}
	};
	var mobileErrorMsg = {
		link_mobile:'<span class="tipLayErr tipw180">请填写正确的手机号码<span class="tipArr"></span></span>'
	};
		
	var baseInforForm = new validatorForm({
		element: $('#baseInforForm'),
		rules: baseInforRuls,
		errorMessages: baseInforMessage,
		/*
		groups: {
			zoneNo: 'zone_infor phone_infor ext_infor'
		},*/
		//keepBlur: 'zone_infor phone_infor ext_infor',
		keepKey: true,
		errorElement: 'span'
	});
	

		orMobile(false);

	
	function orMobile(f){
		baseInforForm.removeGroup(zoneNoName);
		baseInforForm.removeRules('link_mobile');
		if(f){
			baseInforForm.addRules(zoneNoRules);
			baseInforForm.addErrorMessages(zoneNoErrorMsg);
			baseInforForm.addGroup(zoneNoGroup);
			baseInforForm.addRules(mobileRules);
			baseInforForm.addErrorMessages(mobileErrorMsg);
		} else {
//			baseInforForm.addRules(zoneNoRules1);
//			baseInforForm.addErrorMessages(zoneNoErrorMsg);
//			baseInforForm.addGroup(zoneNoGroup);
			baseInforForm.addRules(mobileRules1);
			baseInforForm.addErrorMessages(mobileErrorMsg);
		}
	}
	
	$('#getVd').click(function() {
	  $.showModal('/company/showVideoInfo',{title:'如何获取视频'});
	});
	$('#newCalling').on('click',function(){
		$.showModal('/company/selectCalling/type-1',{
			title:'选择主行业',
			onclose: function(){

			    //get calling spots.
                var calling_id = $("#main_calling").val();
				if(calling_id != '01' || calling_id != '03' || calling_id != '04' || calling_id != '05'){
					$('#company_finance_is').css('display','none');
					$('.company_financex_is').html('<i>*</i>');
				}else{
					$('.company_financex_is').html('<i></i>');
				}
                $.post('/company/GetCompanySpotByCallingid',{calling_id:calling_id},function (e) {
                    if(e.status){
                        $("#spot_detail").text(e.spot);
                    }
                },'json')

			}
		});
	});


    $('#mobile').bind('input propertychange', function() {
        var mobile = $(this).val();
        if(mobile=='<?php echo $_smarty_tpl->getVariable('company')->value['link_mobile'];?>
'){
            $('#codeDiv').hide();
        }else{
            $('#codeDiv').show();
        }
    });

    $('.authCodex').click(function(){
        if($(this).hasClass("authCodeGray"))
            return;
        var txtUserPhone = $('#mobile').val();

        if(txtUserPhone == ''){
            $('.authCodetipLay').html(getAlertMessage("请输入手机号"));
            return;
        }else{
            $('.authCodetipLay').html('');
        }
        $.post('<?php echo smarty_function_get_url(array('rule'=>"/company/SendAuthCode"),$_smarty_tpl);?>
', {mobile_phone : txtUserPhone}, function (data) {
            data = eval("("+data+")");
            if(data && !data.status){
                alert(data.msg);
                return false;
            }else{
                alert("发送成功");
                $('.authCodex').addClass('authCodeGray').html('<b>60</b>秒后重新获取验证码');
                interval = window.setInterval(coundown,1000);
            }
        });
    });
    function coundown(){
        var seconds=$('.authCodeGray').find('b').text();
        seconds = parseInt(seconds);
        if(seconds > 0){
            seconds--;
            $('.authCodeGray').find('b').text(seconds);
        }else{
            window.clearInterval(interval);
            $('.authCodex').removeClass('authCodeGray').text('获取验证码');
        }
    }

    $('#nextCalling').on('click',function(){
	   var calling_id = $("#main_calling").val() //todo
		$.showModal('/company/selectCalling/type-0-calling_id-'+calling_id,{title:'选择次行业'});
	});
	
	/*
	$("#phoneDiv input").focus(function(){
        var thisInput = $(this);
        var inputName = thisInput.attr("name");
        var defaultVal = "分机号";
        if(inputName == "zone_infor"){
            defaultVal = "区号";
        }else if(inputName == "phone_infor"){
            defaultVal = "固定电话";
        }
        var currentVal = $.trim(thisInput.val());
        if(currentVal == defaultVal){
            thisInput.val("");
            thisInput.css("color","#000");
        }
    });

    $("#phoneDiv input").blur(function(){
        var thisInput = $(this);
        var inputName = thisInput.attr("name");
        var defaultVal = "分机号";
        if(inputName == "zone_infor"){
            defaultVal = "区号";
        }else if(inputName == "phone_infor"){
            defaultVal = "固定电话";
        }
        var currentVal = $.trim(thisInput.val());
        if(currentVal == defaultVal || currentVal == ""){
            thisInput.val(defaultVal);
            thisInput.css("color","#A6A6A6");
        }
    });
	*/
	
	//添加次行业
	function addCalling(){
		$("#hidLastCalling").show();
		$("#addHtml").hide();
	}
	function deleteCalling(){
		 $("#hidLastCalling").hide();
		 $("#addHtml").css({'display':'inline-block'});
		 $("input[name='next_calling']").val('');
		 $("#addNextCalling").empty();
	}
	$('#nextCallingBtn').on('click', deleteCalling);
	

	$('.mapClose').click(function(){
		$('#floagMap,#_mask').hide();							  
	})


    $('#jbzlLnk').click(function(){
        $('html,body').animate({scrollTop:$jbzl-50});
    });
    $('#dlwzLnk').click(function(){
        $('html,body').animate({scrollTop:$dlwz-50});
    });

    $('#comNLnk,#callingNLnk').click(function(){
        $(this).parents('.tipTxt').siblings('.comNTxt').find('.tipLay').css({'display':'block'});
    });

    $('.closeThis').click(function(){
        $(this).parents('.tipLay').css({'display':'none'});
    });

    $('#touPeoDrop').hover(function(){
        $(this).siblings('.tipPos').find('.tipLay').css({'display':'block'});
    },function(){
        $(this).siblings('.tipPos').find('.tipLay').css({'display':'none'});
    });
	
	var imgLstTxt = $('#imgLst').find('.li').find('.imgTxt');
	
    imgLstTxt.on('focus', function(e){
        $(this).parents('li').addClass('hov');
    }).on('blur', function(e){
        $(this).parents('li').removeClass('hov');
    });

    imgLstTxt.hover(function(){
        $(this).addClass('imgTxtHov');
    },function(){
        $(this).removeClass('imgTxtHov');
    });
	
	
	$("#company_property").droplist({
		isCanWrite:false,
		inputWidth:125,
		style:'width:133px',
		hddName:'hddComProperty',
		items:[
			<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(1, null, null);?>
		<?php  $_smarty_tpl->tpl_vars['property'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('properties')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['property']->key => $_smarty_tpl->tpl_vars['property']->value){
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['property']->key;
?>
		{id:'<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
',name:'<?php echo $_smarty_tpl->tpl_vars['property']->value;?>
'}
		<?php if ($_smarty_tpl->getVariable('i')->value++<count($_smarty_tpl->getVariable('properties')->value)){?>
		,
		<?php }?>
			<?php }} ?>
			],
				onSelect:function(i,name){
					baseInforForm.checkElement($('#hddComProperty'));
				}
    });
	$("#company_property").setDropListValue(<?php echo $_smarty_tpl->getVariable('company')->value["property_id"];?>
);
	
	$("#company_finance").droplist({
		isCanWrite:false,
		inputWidth:125,
		style:'width:133px',
		hddName:'hddComFinance',
		items:[
			<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(1, null, null);?>
			<?php  $_smarty_tpl->tpl_vars['property'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('franaces')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['property']->key => $_smarty_tpl->tpl_vars['property']->value){
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['property']->key;
?>
			{id:'<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
',name:'<?php echo $_smarty_tpl->tpl_vars['property']->value;?>
'}
			<?php if ($_smarty_tpl->getVariable('i')->value++<count($_smarty_tpl->getVariable('properties')->value)){?>
			,
			<?php }?>
			<?php }} ?>
		],
		onSelect:function(i,name){
			baseInforForm.checkElement($('#hddComFinance'));
			if($('#hddComFinance').val() != '0'){
				$('#company_finance_is').css('display','none')
			}
		}
	});
	$("#company_finance").setDropListValue(<?php echo $_smarty_tpl->getVariable('frnance_type')->value;?>
);
	
	$("#company_size").droplist({
		isCanWrite:false,
		inputWidth:125,
		style:'width:133px',
		hddName:'hddComsize',
		items:[
			<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(1, null, null);?>
	<?php  $_smarty_tpl->tpl_vars['property'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('comsizes')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['property']->key => $_smarty_tpl->tpl_vars['property']->value){
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['property']->key;
?>
	{id:'<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
',name:'<?php echo $_smarty_tpl->tpl_vars['property']->value;?>
'}
	<?php if ($_smarty_tpl->getVariable('i')->value++<count($_smarty_tpl->getVariable('comsizes')->value)){?>
	,
	<?php }?>
		<?php }} ?>
		],
			onSelect:function(i,name){
				baseInforForm.checkElement($('#hddComsize'));
			}
		});
	
	$("#company_size").setDropListValue(<?php echo $_smarty_tpl->getVariable('company')->value["size_id"];?>
);
	
	function getBaseInfor(){
		var baseInfor = {};
		var $_baseInforForm = $("#baseInforForm");
		baseInfor.company_property  = $_baseInforForm.find("input[name=hddComProperty]").val();
		baseInfor.company_finance  = $_baseInforForm.find("input[name=hddComFinance]").val();
	   // baseInfor.calling_ids       = $_baseInforForm.find("input[name=callingids]").val();
		baseInfor.main_calling       = $_baseInforForm.find("input[name=main_calling]").val();
		 baseInfor.next_calling       = $_baseInforForm.find("input[name=next_calling]").val();
		baseInfor.company_size      = $_baseInforForm.find("input[name=hddComsize]").val();
		baseInfor.company_info      = $_baseInforForm.find("textarea[name=info]").val();
		baseInfor.linkman           = $_baseInforForm.find("input[name=linkman]").val();
		baseInfor.link_mobile       = $_baseInforForm.find("input[name=link_mobile]").val();
		baseInfor.homepage          = $_baseInforForm.find("input[name=homepage]").val();
		baseInfor.open_linkman      = $_baseInforForm.find("input[name=open_linkman]").val();
		//baseInfor.open_mobile       = $_baseInforForm.find("input[name=open_mobile]").val();
		//baseInfor.show_email        = $_baseInforForm.find("input[name=show_email]").val();
		//baseInfor.open_tel          = $_baseInforForm.find("input[name=open_phone]").val();
		baseInfor.phone_infor       = $_baseInforForm.find("input[name=phone_infor]").val();
		baseInfor.zone_infor        = $_baseInforForm.find("input[name=zone_infor]").val();
		baseInfor.ext_infor         = $_baseInforForm.find("input[name=ext_infor]").val();
		baseInfor.logo              = $_baseInforForm.find("input[name=hidden_logo]").val();
		baseInfor.del_logo			= $_baseInforForm.find("input[name=hidden_del_logo]").val();
		baseInfor.operate			= $_baseInforForm.find("input[name=operate]").val();
		baseInfor.hidDefaultReward			= $_baseInforForm.find("input[name=hidDefaultReward]").val()
		baseInfor.hidOtherReward			= $_baseInforForm.find("input[name=hidOtherReward]").val()
		baseInfor.hidCompanyShortName			= $_baseInforForm.find("input[name=hidCompanyShortName]").val()
//		baseInfor.company_name			= $_baseInforForm.find("input[name=a_company_name]").val()
		baseInfor.hidRightSpot			= $_baseInforForm.find("#hidRightSpot").val(),
		baseInfor.hidArea = $_baseInforForm.find('input[name="hidArea"]').val(),
		baseInfor.hidMapX = $_baseInforForm.find('input[name="hidMapX"]').val(),
		baseInfor.hidMapY = $_baseInforForm.find('input[name="hidMapY"]').val(),
		baseInfor.hidMapZoom = $_baseInforForm.find('input[name="hidMapZoom"]').val(),
		baseInfor.authCode = $_baseInforForm.find('input[name="authCode"]').val(),
		baseInfor.txtAddress = $_baseInforForm.find('input[name="txtAddress"]').val();
		return baseInfor;
	}
	

	var initPt;
	var initArea;
	var map,marker;
	
	$.setIndex('zIndex');

    /**
     * 街道验证
     * @returns boolean
     */
    function checkAddress(origin){
		
        function getAlertMessage(message){
            return '<span class="tipLayErr tipw120">' + message +'<span class="tipArr"></span></span>';
        }
		var curarea = $('#curarea'),
			promptElement = curarea.next().find('.tipLay'),
			cityElement = curarea.find('.city'),
			countyElement = curarea.find('.county[value="市"]'),
			lastElement = curarea.find('.county[value="区/县"]');
		
        if(cityElement.hasClass('textGray')){
            promptElement.html(getAlertMessage('请选择城市'));
            return false;
        }
        if(countyElement.hasClass('textGray')){
            promptElement.html(getAlertMessage('请选择区县'));
            return false;
        }

        //看区县上一级是否隐藏
        //console.log($('.county[value="区/县"]').parent().css('display'));
        if(origin == 1) {
			if (lastElement.parent().css('display') == 'none') {
				return true;
			} else {
				if(cityElement.val() == '重庆' || countyElement.val() == '成都'){
					if (lastElement.hasClass('textGray')) {
						promptElement.html(getAlertMessage('请选择街道'));
						return false;
					}
				}
			}
        } else {
            //地区选择只验证到第二级
            return true;
        }

        return true;
    }
	
	var isAreaInit = true;
	$('#curarea').singleArea({
		hddName:'hidArea',
		showLevel: 3,
		initLevel: 2,
		selectArea:'<?php echo $_smarty_tpl->getVariable('company')->value["area_id"];?>
',
		onSelect:function(a){
			isAreaInit = false;
			baseInforForm.checkElement($('#hidArea'));
        	var geo = new BMap.Geocoder();
        	geo.getPoint(getAreaDesc(), relocateCallback, '');
            //更新详细地址
            $('#txtAddress').val(getAreaDesc());
			baseInforForm.checkElement($('#txtAddress'));
    	},
		onData: function(e){
			if(e.length && !isAreaInit){
				checkAddress(1);
			}
		}
	});
	initMap();

	$('#txtAddress').focusin(function(){
		var addr = $(this).val();
		if(addr==''){
			var areaDesc='';
			var areas = $('#curarea').getAreaNames();
			for(var i=0;i<areas.length;i++){
				areaDesc += areas[i] ;
			}
			$(this).val(areaDesc);
			addr = areaDesc;
		}
		if(addr!=''){
			var geo = new BMap.Geocoder();
			geo.getPoint(addr, relocateCallback, '');
		}
	});

	$('#txtAddress').focusout(function(){
		var addr = $(this).val();
		if(addr != ''){
			var geo = new BMap.Geocoder();
			geo.getPoint(addr, relocateCallback, '');   //缺一个城市名
		}
	});

	function relocateCallback(point){
		if(point == null) return;
		map.setCenter(point);
		marker.setPosition(point);
		$('#hidMapX').val(marker.getPosition().lng);
		$('#hidMapY').val(marker.getPosition().lat);
		$('#hidMapZoom').val(map.getZoom());
	}

    function getAreaDesc(){
        var areaDesc = '';
        var areas = $('#curarea').getAreaNames();
        for(var i = 0;i<areas.length;i++){
            areaDesc += areas[i] ;
        }
        return areaDesc;
    }

	function initMap(){
		var mapX = $('#hidMapX').val();
		var mapY = $('#hidMapY').val();
		var mapZoom = $('#hidMapZoom').val();
		if(typeof mapZoom =='undefined' || mapZoom == ''){
			mapZoom = 15;
		}
	
		var initPt = null;
		if(typeof mapX != 'undefined' && mapX!='' && typeof(mapY) != 'undefined' && mapY!=''){
			initPt = new BMap.Point(mapX,mapY);
		}
	
		var address = $('#txtAddress').val();
	
		//这个逻辑由于单选地区控件是异步加载的，所以这里可能始终取的是空值，暂时保留吧
		var areaDesc='';
		var areas = $('#curarea').getAreaNames();
		for(var i=0;i<areas.length;i++){
			areaDesc += areas[i] ;
		}
	
		var defaultPt = new BMap.Point(106.553057,29.565468);
	
		map = new BMap.Map("mapCon");
	
	
		//在加载完成，确定中心点后，设置标注点
		map.addEventListener('load',function(){
			var pt = null;
			if(initPt!=null){
				pt = initPt;
			}
			else{
				pt = new BMap.Point(map.getCenter().lng,map.getCenter().lat);
			}
	
			var myIcon = new BMap.Icon("<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/common/maplabel.png", new BMap.Size(33,50));
			marker = new BMap.Marker(pt,{icon:myIcon});  // 创建标注
			map.addOverlay(marker);              // 将标注添加到地图中
			marker.enableDragging();
			marker.setTitle('请拖动至您公司所在位置');
	
			var label = new BMap.Label("请拖动至您公司所在位置",{offset:new BMap.Size(35,5)});
			marker.setLabel(label);
	
			//添加事件，在拖动时去掉文字提示
			marker.addEventListener('dragstart',function(){
				label.setStyle({display:'none'});
			});
			marker.addEventListener('dragend',function(){
				$('#hidMapX').val(marker.getPosition().lng);
				$('#hidMapY').val(marker.getPosition().lat);
				$('#hidMapZoom').val(map.getZoom());
			});
		});
	
		if(initPt != null){
			map.centerAndZoom(new BMap.Point(mapX,mapY), mapZoom);
		}
		else if(address != ''){
			map.centerAndZoom(defaultPt,mapZoom);
			var geo = new BMap.Geocoder();
			geo.getPoint(address, relocateCallback, '');
		}
		else if(areaDesc!=''){
			map.centerAndZoom(defaultPt,mapZoom);
			var geo = new BMap.Geocoder();
			geo.getPoint(areaDesc, relocateCallback, '');
		}
		else{
			map.centerAndZoom(defaultPt, mapZoom);
		}
	
	
		map.addControl(new BMap.NavigationControl());               // 添加平移缩放控件
		map.addControl(new BMap.MapTypeControl());          //添加地图类型控件
		map.addControl(new BMap.OverviewMapControl());              //添加默认缩略地图控件
		map.enableScrollWheelZoom();                            //启用滚轮放大缩小
	
		map.addEventListener('zoomend',function(){
			$('#hidMapZoom').val(map.getZoom());
		});
	}

	function saveAddressCallback(json){
		if(json.error){
			$.message(json.error,{title:'操作失败！'});
			return false;
		}
		//地址保存成功后保存基本信息
		//if(!baseInforForm.form()) return false;
		var baseInfo = getBaseInfor();
		$.ajax({
			url:"/company/modifybasicinfo",
			type:"post",
			dataType:"json",
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			data: baseInfo,
			success:function(json){
				if(!(json.success||false)){
					$.message(json.error,{title:'操作失败！'});
					return false;
				}
                $('#codeDiv').hide();
				$.anchorMsg("完善资料成功！");
				
				//进入第三步
				//setTimeout('window.location.href = "<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/register/add";',1000);
				setTimeout('window.location.href = "<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/environment/index?step=3";',1000);
				return false;
			}
		});
		return false;
	}
	
	baseInforForm.on('invalid', function(e){
		if(e.name === 'link_mobile'){
			orMobile(false);
		}
	});
	baseInforForm.on('pass', function(e){
		e.label.html('');
		if(e.name === 'link_mobile' && $.trim(e.target.val())){
			orMobile(false);
		}
	});
	
	$('#btnSaveAddress').click(function(){   
		var main_calling_name = $('#main_calling').val();
		if(main_calling_name == '01' || main_calling_name == '03' || main_calling_name == '04' || main_calling_name == '05'){
			if($('#hddComFinance').val() == '0'){
				$('#company_finance_is').css('display','block');
				$('body,html').animate({scrollTop:500});
				return false;
			}
		}
		baseInforForm.submit(function(){
			if(!checkAddress(1)){
				return;
			}
			$.ajax({
				url: "<?php echo smarty_function_get_url(array('rule'=>'/company/saveMap/'),$_smarty_tpl);?>
",
				data: {
					hidArea: getBaseInfor().hidArea,
					hidMapX: getBaseInfor().hidMapX,
					hidMapY: getBaseInfor().hidMapY,
					hidMapZoom: getBaseInfor().hidMapZoom,
					txtAddress: getBaseInfor().txtAddress
				},
				success: saveAddressCallback
			});
		}, function(){
			checkAddress(1);
		});
	});
	
});

//修改行业
function updateCalling(calling_id,calling_name,type, $){
	if(type ==1){
		$("#addMainCalling").empty();
		$("#main_calling").val(calling_id);
		  var h = "<span class='seled'>"+calling_name+"</span>"
		$("#addMainCalling").html(h);
	}else{
		 $("#addNextCalling").empty();
		 $("input[name='next_calling']").val(calling_id);
		  var h = "<span class='seled' id='addNextCalling'>"+calling_name+"</span>"
		$("#addNextCalling").html(h);
	}
}

</script>
</body>
</html>

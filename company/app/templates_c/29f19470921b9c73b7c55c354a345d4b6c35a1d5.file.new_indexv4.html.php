<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:30:10
         compiled from "app\templates\./new_indexv4.html" */ ?>
<?php /*%%SmartyHeaderCode:190405e7036325e2cd5-29719562%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '29f19470921b9c73b7c55c354a345d4b6c35a1d5' => 
    array (
      0 => 'app\\templates\\./new_indexv4.html',
      1 => 1584332291,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '190405e7036325e2cd5-29719562',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_function_get_assets')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_assets.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
if (!is_callable('smarty_modifier_replace')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.replace.php';
if (!is_callable('smarty_modifier_date_format')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.date_format.php';
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
	<script src="<?php echo smarty_function_version(array('file'=>'html5.js'),$_smarty_tpl);?>
"></script>
	<![endif]–>
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'comback.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'firmcss.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'company_index_v2.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'m_font_style.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'index_newlayer.css'),$_smarty_tpl);?>
" />
	<!--<link rel="stylesheet" type="text/css" href="http://assets.huibo.com/css/m_font_style.css?v=20181031">-->

	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'common.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'dialog.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'mediaquery.js'),$_smarty_tpl);?>
"></script><!--响应式兼容-->
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_menudisplay.js'),$_smarty_tpl);?>
"></script><!--下拉菜单-->
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_inputFocus.js'),$_smarty_tpl);?>
"></script><!--输入框获取焦点-->
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_hovchange.js'),$_smarty_tpl);?>
"></script><!--指向改变class-->
	<!--<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_dragsort.js'),$_smarty_tpl);?>
"></script>--><!--拖动插件-->
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.form.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_validate.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.cookie.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_dropdownlist.js'),$_smarty_tpl);?>
"></script><!--下拉模拟-->
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
	<script>
	$(document).ready(function(e) {
		$('.firmMagContx p a').hover(function(){
			$(this).toggleClass('cut')
		});
	});
	</script>

	<?php echo $_smarty_tpl->getVariable('up_img_html')->value['hand_html'];?>


<style>
.js-over-height{height:440px;overflow: hidden}
.header-img{display: inline-block;width:60px;height:60px;border-radius: 60px;background: #66a5ea;font-size:20px;color:#fff;line-height: 60px;text-align: center}
ul li a p span .color-red{color:red;display: inline;}
.icon-ji,.icon-ding{width:16px;height: 16px!important;line-height: 16px!important;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/v2/job/ma-icon.jpg) -26px 0 no-repeat;display: inline-block;vertical-align: middle;margin-left: 3px;float: left;margin-top:13px}
.icon-spread{width:30px;height: 16px!important;line-height: 16px!important;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/v2/job/spread.jpg) 0px 0 no-repeat;display: inline-block;vertical-align: middle;margin-left: 3px;float: left;margin-top:13px}
.icon-ding{background-position: 0 0}
#js_click_import a:hover{ color:#ff3948;}

.quseSurvey{width:428px; overflow:hidden; position:fixed; top:50%; left:50%; margin:-260px 0 0 -219px; z-index:10000; }
.quse_img{ display:block;}
.quseBg{width:374px; overflow:hidden; background:#fff; padding:0 20px 20px 20px; text-align:left; }
.quseTita{ font-size:14px; color:#444; line-height:24px;text-indent:40px; overflow:hidden; margin-top:8px;}
.quseTita b{ color:#ffa200;}
.quseTitb{ margin:25px 0px; overflow:hidden; padding:15px 18px; overflow:hidden; background:#fef7e8; color:#604e29; line-height:24px; border-radius:10px;}
.quseBtn{ display:block; width:282px; height:48px; background:#ffa200; color:#fff; font-size:18px; margin:0 auto; border-radius:4px; text-align:center; line-height:48px;}
.quseBtn:hover{ background:#e39002; color:#fff;}
.quseClose{ display:block;}
.quse_bdimg{width:width:428px; height:198px; overflow:hidden; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/mobile/survey_img.png) no-repeat;}
.quse_bdimg a{ display:block;width:26px; height:26px; float:right;}
.hb_ui_dialog .ui_dialog_title {  font-size: 16px;  }
.hb_ui_dialog .ui_dialog_title {  text-align: left;  }

#calling_advert{width:140px}
#calling_advert a{ display:block; margin-bottom: 10px;}

.cmpAnnunciate{width:670px;  background: #fffadd; padding:0px 10px; text-indent: 10px; font-size: 14px; color:#604e29; overflow: hidden;}
.cmpAnnunciateTip{width:100%; height: 40px; line-height: 40px;  overflow: hidden;}
.cmpAnnunciateTip span{color:#ff5400;font-size: 14px; cursor: pointer; text-decoration: underline;}
.cmpAnnunciateTit{border-top: 1px solid #f6e5a6; padding-top: 10px; overflow: hidden; font-size: 12px; color: #604e29; line-height: 20px; text-indent:0px; display: none; padding-bottom: 10px;}

.marketTip{width:100%; overflow:hidden; text-align: right;margin-top: 10px;}
.marketTip i,.marketTip span{ display: inline-block; vertical-align: middle;}
.marketTip i{width:17px; height: 17px; background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/market.jpg) no-repeat; margin-right: 4px;}
.marketTip span{color: #fe7920; text-decoration: underline; cursor: pointer;}

.marketPopx,.morePopx{width:380px; overflow: hidden; position: absolute; z-index: 10; margin-left: 290px; display: none;}
.marketPopx i.markeIcon,.morePopx i{ display: block;width:7px; height: 6px; background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/marketIcon.png) no-repeat; margin-left: 180px; position: relative;}
.marketPop,.morePopx span{ display: block; padding: 5px 0px; background: #fffadd; border: 1px solid #f6e5a6; text-align: center; margin-top: -1px;}
.marketPop span{ display: inline-block; padding-right: 20px; color: #604e29;}
.marketPop span.last{ padding-right: 0px;}
.questSurvey{ display: none;width:690px; height: 60px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/companyBanner.jpg) no-repeat bottom;background-color: #fff;}
.morePopx{ display: block;width:260px; margin-left: -193px;}
.morePopx i{ margin-left: 207px;}
.morePopx span em{ display: inline-block; margin-left: 10px; font-size: 14px; color: #f60; cursor: pointer;}
/*2018-06-13改版*/
.user-infor-box .infor a{  color: #3D84B8;}
.user-infor-box .infor a:hover{color:#09c;text-decoration:none;}
.wh-money-list{display: inline-block;}
.money p .wh-money-list em{color: #2b6fad;font-style: normal;font-family: "Microsoft YaHei";}
.money p a i{display: inline-block; width: 16px;height: 16px;vertical-align: -3px;margin-right: 5px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/natural_icon_tit.png) no-repeat; }
.money p a{color: #666;margin-right: 6px;}
.money p a:hover{color: #2b6fad;text-decoration: underline;}
.money p .wh-money-list:hover em{text-decoration: none;}
.money p .btnxcut1:hover{color: #2b6fad; text-decoration: underline;}
.scedTit span{color: #444;}
.two-weeks{border: none;background: #fff;}
.reversionList li{margin-right: 0px;}
.two-weeks .number{font-size: 14px;color: #444;vertical-align: 0;}
.flowAPP-dialog .ui_dialog_container{overflow-y: scroll;height: 550px;}
.grade_dialog  .ui_dialog_container{height: 400px;}
.reversionList li .orang{width: 220px;font-size: 12px;}
.text-blue{color: #ff5400;margin-left: 5px;font-size: 15px;text-decoration: underline;}
.text-blue:hover{color: #ff5400;text-decoration: underline;}
.comp_infor .money em{margin: 0;}
.user-infor-box .weixinApp a:hover{color: #2b6fad;text-decoration: underline;}
#show_alt i, .refresh-job .alert-warniong .arrdown-icon{left: 96px; top:-6px;transform: rotate(180deg);-ms-transform:rotate(180deg); 	/* IE 9 */
	-moz-transform:rotate(180deg); 	/* Firefox */
	-webkit-transform:rotate(180deg); /* Safari 和 Chrome */
	-o-transform:rotate(180deg); 	/* Opera */}
.refresh-job .alert-warniong{left: 86px;}
.scedTit a{margin-top: 2px;}
	.wh-go-up{display: inline-block;width: 10px;height: 13px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/go_up.png) no-repeat;background-position: -13px 0; }
	.wh-go-up:hover{background-position: 0 0;}
	/*代运营*/
	/*.freshList{overflow:inherit;}*/
	.freshList li{overflow:inherit;position: relative}
	.freshList li .subFresh01 .daiyunying{float: left;}
	.freshList li .subFresh01 .daiyunying img{padding-left: 0px;margin-top: 13px;}
	/*.freshList li .subFresh01 .daiyunying{position: relative;}*/
	.freshList li a, .freshList li span{overflow: inherit;}
	/*.freshList li .subFresh01 .daiyunying p{display: none;position: absolute;padding-left: 4px;height: auto;line-height: 24px; margin-top: 5px; left:0;top: 100%;width: 176px;border: 1px solid #f6e5a6;color: #604e29;background: #fffadd;font-size: 12px;}*/
	/*.freshList li .subFresh01 .daiyunying p img{position: absolute;  top: -6px;  left: 10px;  margin-top: 0;transform: rotate(180deg);  -ms-transform: rotate(180deg);  -moz-transform: rotate(180deg);  -webkit-transform: rotate(180deg);  -o-transform: rotate(180deg);}*/
	/*.freshList li .subFresh01 .daiyunying:hover p{display: inline-block;}*/
	/*企业风采*/
	.money p a.wh-companyShow i{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/companyshow.png) no-repeat;margin-left: 2px;background-position: 2px 0px;}
	/*.money p a.wh-companyShow{color:#999;}*/
	.money p a.wh-companyShow-on i{background-position: 0px 0px;}
	.money p a.wh-companyShow-on{color:#666;}
	.money p a.wh-companyShow-on:hover{color: #2b6fad;}


	/*用户协议*/
.protocolUsage{ text-align: left;width:780px; margin: 20px; color: #444;}
.usagex{ display: block; line-height: 24px;}
.protocolUx{width:718px; border: 1px solid #f0f0f0; padding: 30px; margin: 30px 0; height: 390px; overflow-y: scroll;}
.protocolUx h2{ font-weight: bold; width:100%; text-align: center; font-size: 14px; padding-bottom: 30px;}
.protocolUx p{ color: #666; line-height: 26px;}
.protocolUx p b{ font-weight: bold; color: #333;}
.isProtocolx{ display: block; margin: 20px auto;text-align: center;}
.isProtocolx input{ display: inline-block; vertical-align: -2px; }
.protocolUsageBtn{ display: block; width:150px; height: 40px; margin: 20px auto; background: #66bce4; text-align: center; line-height: 40px; color: #fff; border-radius: 4px;}
.protocolUsageBtn:hover{ color: #fff; background: #4fb4e3;}
/*海報生成*/
.toPic{background: #f8f9fb;border-radius: 8px;padding: 18px 12px;cursor: pointer;margin: 10px 0;}
.toPic img,.toPic div{float: left;}
.toPic img{margin-top: 4px;}
.toPic div{margin-left: 14px;}
.toPic div h4{font-size: 16px;color: #444;font-weight: bold;}
.toPic div p{font-size: 14px;color: #444;}
.toPic .toPic_icon{float: right;margin-top: 14px;}
.sharePoster p{font-size: 14px;color: #444;margin: 20px 0;}




	/*edit 2018/12/25*/
.user-infor-box .weixinApp {
	font-size: 12px;
}
.user-infor-box .weixinApp a {
	cursor: pointer;
}
.user-infor-box .weixinApp .sub-user {

}
.user-infor-box .weixinApp i.sub {
	background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/sub-user.png) no-repeat;
	color: #2b6fad;
	position: relative;
	top: 4px;
	display: inline-block;
	width: 16px;
	height: 16px;
}

.xf-schedule .share-title {
	background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/share.png) no-repeat;
	padding-left: 30px;
	background-position: 0 1px;
	font-size: 16px;
	margin-bottom: 9px;
}
.xf-schedule .share-other {
	overflow: hidden;
	padding-top: 5px;
}
.xf-schedule .share-other div {
	float: left;
	margin-right: 30px;
	color: #2b6fad;
}
.xf-schedule .share-other div i {
	color: #2b6fad;
	margin-right: 5px;
	font-weight: bold;
}
.xf-schedule .share-other div a {
	color: #2b6fad;
	cursor: pointer;
}
.xf-schedule .share-other div a.share {
	padding-left: 18px;
	background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/share-img.png) no-repeat;
	background-size: 14px;
	background-position: 0px 3px;
}
.xf-schedule .share-other div a.link {
	padding-left: 18px;
	background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/link-bg.png) no-repeat;
	background-size: 16px;
	background-position: 0px 3px;
}
.xf-schedule .share-other div a:hover {
	text-decoration: underline;
}

.freshList li {
	position: relative;
	padding-left: 20px;
}
.freshList li .select-all-refresh-job {
	position: absolute;
	top: 13px;
	left: 5px;
	display: block;
	width: 14px;
	height: 14px;
}
.freshList li .select-refresh-job {
	position: absolute;
	top: 13px;
	left: 5px;
	display: block;
	width: 14px;
	height: 14px;
}
.freshList li .un-select-refresh-job {
    position: absolute;
    top: 13px;
    left: 5px;
    display: block;
    width: 14px;
    height: 14px;

}

.freshList li .share-box {
	position: absolute;
	top: 8px;
	left: -100px;
	border: 2px solid #cdcdcd;
	white-space: nowrap;
	display: none;
	line-height: 20px;
	height: 20px;
	padding: 0 3px 0 24px;
	background: #fff url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/share.png) no-repeat;
	background-size: 16px 16px;
	background-position: 4px 2px;
	z-index: 10;
	cursor: pointer;
}
.freshList li:hover .share-box{
	display: inline;
}
.freshList li .share-box .share-link{
	line-height: 20px;
    height: 20px;
    float: none;
    padding-left: 0;
    width: auto;
    display: inline;
}
.freshList li .share-box .share-h5{
	line-height: 20px;
    height: 20px;
    float: none;
    padding-left: 0;
    width: auto;
    display: inline;
}
.new-alert-pop{color:#604e29; overflow: hidden;}
.new-alert-pop span{color:#ff7920;}
.money p .wh-money-list em.fail {
	margin-left: 1px;
	font-size: 12px;
	background: #ed7066;
	color: #fff;
	padding: 0 4px;
	border-radius: 4px;
	line-height: 18px;
	height: 18px;
	display: inline-block;
}
.money p a.wh-companyShow em.fail {
	margin-left: 3px;
	font-size: 12px;
	background: #ed7066;
	color: #fff;
	padding: 0 4px;
	border-radius: 4px;
	line-height: 18px;
	height: 18px;
	display: inline-block;
	position: relative;
	top: -1px;
}


    /*kkp*/

/*充值dialog*/
.need-money {
    width: 470px;
    box-sizing: border-box;
    padding: 50px 20px 20px;
    text-align: center;
}

.need-money .title {margin-bottom: 20px;}
.need-money .title i {font-size: 54px;color: #ccc;}
.need-money .txt1 {font-size: 18px;font-weight: bold;margin-bottom: 40px;}
.need-money .txt2 {font-size: 14px;margin-bottom: 20px;}
.need-money .recharge {display: inline-block;width: 184px;height: 40px;line-height: 40px;background: #ff5400;color: #fff;border-radius: 4px;margin-bottom: 7px;}
.need-money .contact {color: #ff5400;}

/* #refreshAllJob {
	cursor: pointer;
	background: #00bab1;
}
#refreshAllJob:hover {
	background: #00b0b1;
} */
.vip_box_tit .tit {
	color: #2b6fad;
}

.vip_box .center-vip a:hover {
	color: #2b6fad;
}
.hb_ui_dialog .ui_dialog_message {
	padding: 25px 15px;
}
.refresh-job {
	width: 412px;
}
.firmHlt {
	overflow: visible;
}
	/*满意度评分*/
.companyGrade{ display: block;width:113px; height: 108px; background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/grade_img01.png) no-repeat; position: fixed; top: 50%; left: 50%; margin: 60px 0 0 -620px; cursor: pointer;}
/*新增数据过低气泡提示2019-01-23*/
.reversionList li .number i,.reversionList li .number a{ display: inline-block;width: 10px;height: 13px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/go_down.png) no-repeat; vertical-align: -2px; margin-left: 5px; position: absolute; top: 5px;}
.reversionList li.resCur .number i,.reversionList li.resCur .number a{ height: 22px; background-position: -27px 0;}
.reversionList li.resCur{ z-index: 4;}
.reversionList li .resOrang{
	color: #604e29;
    font-size: 14px;
    border: 1px solid #f6e5a6;
    background: #fffadd;
    padding: 5px 10px;
    position: absolute;
    left: 0px;
    top: 26px;
    z-index: 2;
    display: none;
    width: 220px;
    text-align: center;
}
.reversionList li.resCur .resOrang{ display: block;}
.reversionList li .resMore{ text-align: right; color: #3D84B8; text-decoration: underline; }
.freshList li .subFresh02,.freshList li .subFresh04{width:30px;}

.resume_complete{
	position: fixed;
	bottom:210px;
}
.otherComplete {width: 170px !important;}
.completeTip {color: #fff;position: absolute;top: 27px;width: 245px;height: 38px;line-height:38px;left: -3px;font-size: 12px;padding: 0px 10px;border-radius:17px;background-color: rgba(76,76,76,0.85);}
.completeTip .showCompleteList {padding-right: 10px;box-sizing: border-box;display: inline-block;}
.completeTip .closeTip	{position: absolute;right: 18px;top: 16px;display: block;width: 10px;height: 10px;line-height: 16px;text-align: center;cursor: default;}
.completeTip .showCompleteList a {margin-right: 0 !important;text-decoration: underline;color: #fff;}
.completeTip .showCompleteList .lineFirst {color: #FFC103;}
.completeTip .showCompleteList a:hover {color: #0af;}
span.completeTip::before {position: absolute;border: 9px solid;border-color: transparent transparent rgba(76,76,76,0.85) transparent;content: '';top: -18px;left: 40px;}
.calling_advert02 span{ display: block; text-align: center; font-size: 15px;}
.calling_advert02 img{ display: block; padding: 10px 0;}
.calling_advert02 em{ font-style: inherit; color:#444; font-size: 12px;}


</style>
</head>
<body style="background: #e9e9e9;">

<?php if (count($_smarty_tpl->getVariable('advLst')->value)>0){?>
<!--<div class="banner">-->
<!--<a href="javascript:void(0);" class="compClose"></a>-->
<!--<div class="lst flexBanner">-->
<!--<ul class="slides" id="flexBannerList">-->
<!--<?php  $_smarty_tpl->tpl_vars['adv'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('advLst')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['adv']->key => $_smarty_tpl->tpl_vars['adv']->value){
?>-->
<!--<li>-->
<!--<a target="_blank" href="<?php if (stripos($_smarty_tpl->tpl_vars['adv']->value['url'],'http://')===false){?>http://<?php }?><?php echo $_smarty_tpl->tpl_vars['adv']->value['url'];?>
">-->
<!--<img src="<?php echo smarty_function_get_assets(array('img'=>($_smarty_tpl->getVariable('advpath')->value).($_smarty_tpl->tpl_vars['adv']->value["img"])),$_smarty_tpl);?>
"  />-->
<!--</a>-->
<!--</li>-->
<!--<?php }} ?>-->
<!--</ul>-->
<!--</div>-->
<!--</div>-->
<?php }?>

<?php $_template = new Smarty_Internal_Template("new_header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par',"全职招聘");$_template->assign('cur',"首页"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<!-- 企业资料完善弹出层 -->
<?php echo $_smarty_tpl->getVariable('modify_notice_html')->value;?>


<div id="body_content" style="font-family:'微软雅黑'">
	<?php if ($_smarty_tpl->getVariable('open_grade')->value){?><a  id="grade" class="companyGrade"></a><?php }?>
	<div class="firmHomeBg" style="min-height: 530px;">
		<div class="firmHlt">
			<a href="<?php echo smarty_function_get_url(array('rule'=>'/topicpage/hbcollege','domain'=>'main'),$_smarty_tpl);?>
/index.html" class="questSurvey" target="_blank"></a>
			<?php if ($_smarty_tpl->getVariable('newBulletin')->value['is_show_yellow_strip']==true){?>
			<div class="cmpAnnunciate" >
				<div class="cmpAnnunciateTip">升级维护公告：<span onclick="showNewBulletin();"><?php echo $_smarty_tpl->getVariable('newBulletin')->value['title'];?>
</span></div>
				<!--<div class="cmpAnnunciateTit">-->
					<!--1、现有精准推广的职位将暂停使用，预扣的推广金将自动退回，新版精准推广升级上线；<br />-->
<!--2、新版精准推广需重新开启才能生效-->
<!--<br />3、置顶将实行新规则：一个行业的一个职位类别（二级）的职位，如果置顶的职位超过5个，那么系统会随机选择该行业5个置顶职位展示（每次刷新和搜索，这5个职位都可能会随机变动）。  如：“广告/会展”行业的“售后/客服”职位类别下的置顶职位超过5个，则在求职者搜索时，会在已置顶的职位中随机选择5个职位展示。-->
<!--</div>-->
			</div>
			<?php }?>

			<!--企业招聘中心首页，搜索栏顶部，加上图片广告-->
			<div class="">
				<?php if ($_smarty_tpl->getVariable('site_type')->value==0){?>
				<!--<a style="display: block;cursor: pointer;" target="_blank" href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/topicpage/20161129jiangshi/index.html">
					<img style="height: 60px;width: 100%;border: none;" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/hire-teacher.jpg">
				</a>-->
				<?php }elseif($_smarty_tpl->getVariable('site_type')->value==2){?>
				<!--这里是挂区县的广告-->
				<a style="display: none;cursor: pointer;" target="_blank" href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/topicpage/jj20190516/index.html">
					<img style="height: 60px;width: 100%;border: none;" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/cm_jiangjin.jpg">
				</a>
				<?php }?>
			</div>

			<?php if (in_array($_smarty_tpl->getVariable('audit_code')->value,array(501,502,503,504,505,506,507,508,509,510,511))||in_array($_smarty_tpl->getVariable('is_audit')->value,array(4,5))||in_array($_smarty_tpl->getVariable('hrlicence')->value,array(1,2))||$_smarty_tpl->getVariable('not_has_map_job_num')->value>0){?>
			<div class="new-alert-pop">
				<div>
					<?php if ($_smarty_tpl->getVariable('not_has_map_job_num')->value>0){?>
					<p style="width:660px; display:block;">
						<i class="warning-icon"></i>
						<span>有<?php echo $_smarty_tpl->getVariable('not_has_map_job_num')->value;?>
个职位地址坐标未完善，请尽快完善</span>
						<a href="/index/joblist/" target="_blank">去完善&gt;&gt;</a>
					</p>
					
					<?php }?>

					<?php if (in_array($_smarty_tpl->getVariable('audit_code')->value,array(501,502,503))){?>
					<p>
						<i class="warning-icon"></i>
						<?php echo $_smarty_tpl->getVariable('audit_msg')->value;?>

						<a href="/licencevalidate/" target="_blank" style="float:right">上传资料&gt;&gt;</a>
					</p>
					<?php }elseif(in_array($_smarty_tpl->getVariable('audit_code')->value,array(504,505,506))){?>
					<p>
						<i class="warning-icon"></i>
						<?php echo $_smarty_tpl->getVariable('audit_msg')->value;?>

					</p>

					<?php }elseif(in_array($_smarty_tpl->getVariable('audit_code')->value,array(507,508,509))){?>
					<p>
						<i class="warning-icon"></i>
						<?php echo $_smarty_tpl->getVariable('audit_msg')->value;?>

						<a href="/licencevalidate/" target="_blank" style="float:right">重新认证&gt;&gt;</a>
					</p>
					<?php }elseif(in_array($_smarty_tpl->getVariable('audit_code')->value,array(510,511))){?>
					<p style="width:660px; display:block;">
						<i class="warning-icon"></i>
						<?php echo $_smarty_tpl->getVariable('audit_msg')->value;?>

						<a href="/licencevalidate/" target="_blank" style="float:right">下载/上传委托书&gt;&gt;</a>
					</p>
					<?php }elseif($_smarty_tpl->getVariable('is_audit')->value==0||($_smarty_tpl->getVariable('is_audit')->value==5&&!$_smarty_tpl->getVariable('is_local_company')->value)){?>
					<p>
						<i class="warning-icon"></i>
						<span>您的公司还没进行实名认证，赶快去进行验证吧</span>
						<a href="/licencevalidate/" target="_blank" style="float:right">公司认证&gt;&gt;</a>
					</p>

					<?php }elseif($_smarty_tpl->getVariable('is_audit')->value==4){?>
					<p>
						<i class="warning-icon"></i>
						<span>您公司营业执照属于临时认证，到期时间为<?php echo $_smarty_tpl->getVariable('audit_expire')->value;?>
，请尽快重新上传</span>
						<a href="/licencevalidate/index/action-againUpload" target="_blank" style="float:right">重新认证&gt;&gt;</a>
					</p>
					<?php }?>

					<?php if ($_smarty_tpl->getVariable('hrlicence')->value==1){?>
					<p style="width:560px; display:block; float: left;"><i class="warning-icon"></i><span>您的人力资源许可证还没有上传哦。</span>
					<a href="/hrlicence/index" target="_blank">立即上传&gt;&gt;</a>	
					</p>
					
					<?php }elseif($_smarty_tpl->getVariable('hrlicence')->value==2){?>
					<p style="width:560px; display:block; float: left;">
						<i class="warning-icon"></i>
						<span>您的人力资源许可证没有通过审核。<?php if (!empty($_smarty_tpl->getVariable('hrlicence_reason',null,true,false)->value)){?><span title="<?php echo $_smarty_tpl->getVariable('hrlicence_reason')->value;?>
">原因：<?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->getVariable('hrlicence_reason')->value,32,'utf-8','','…');?>
</span><?php }?></span>
					<a href="/hrlicence/index" target="_blank">重新上传&gt;&gt;</a>	
					</p>
					
					<?php }?>
				</div>
			</div>
			<?php }?>

			<!--<?php if ($_smarty_tpl->getVariable('memberinfo')->value=="member"){?>-->
			<!--<a href="https://h5.youzan.com/v2/feature/JbTdCZ7NIU?ps=760" target="_blank" style="display:block;"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/huibo_welfare.jpg" width="690" height="60" /></a>-->
			<!--<?php }?>-->

			<!--需要判断是否显示吗-->
			<?php if (!empty($_smarty_tpl->getVariable('calling_advert_middle',null,true,false)->value)){?>
			<style>
				.slideImg{width:612px;height:60px;overflow: hidden;display: block;position: relative}
				.slideImg .slides img,.slideImg .slides li{width:612px;height:60px;float:left;overflow: hidden}
				.slideImg .flex-control-paging{position:absolute;right:5px;text-align:center;top:45px;top:40px\9;z-index:3;float:right;margin:0px;#width:80px}
				.slideImg .flex-control-paging li{height:20px;overflow: hidden;display: inline-block;height:20px;#float:left}
				.slideImg .flex-control-paging a{display:inline-block;width:8px;height:0;padding-top:8px;background:#000;overflow:hidden;border-radius:50%;filter:alpha(opacity=50);-moz-opacity:0.50;opacity:0.50;margin:0 3px;cursor: pointer}
				.slideImg .flex-control-paging a.flex-active {background:#00f6ff;filter:alpha(opacity=100);-moz-opacity:1;opacity:1}
			</style>
			<div class="slideImg flexBanner">
				<ul class="slides" id="flexBannerList">
					<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('calling_advert_middle')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
					<li><a class="md_index_banner" href="http://<?php echo $_smarty_tpl->tpl_vars['v']->value['url'];?>
" target="_blank" adv_id="<?php echo $_smarty_tpl->tpl_vars['v']->value['adv_id'];?>
" area="calling_advert"><img src="<?php echo $_smarty_tpl->tpl_vars['v']->value['img_url'];?>
" /></a></li>
					<?php }} ?>
				</ul>
			</div>
			<?php }?>

			<a href="<?php echo smarty_function_get_url(array('rule'=>"/resumesearchnew/"),$_smarty_tpl);?>
">
			<div class="new_search">
				<input type="text" name="station" id="searchStation" placeholder="从<?php echo $_smarty_tpl->getVariable('ShowResumeNum')->value;?>
人才简历中找找合适的人吧" class="text md_index_resume_search"/><input type="submit" id="searchResume" value="简历搜索" class="sub_mit"/>
			</div>
			</a>

			<!--新的--简历回复率和职位列表 -->
			<div class="job-content-news">
				<!--近两周处理率-->
				<fieldset class="two-weeks">
					<!--<legend class="title"><strong>近两周</strong>（<?php echo $_smarty_tpl->getVariable('reversion_date_range')->value;?>
）</legend>-->
					<ul class="reversionList  clearfix">
						<li class="item" style="width: 150px;">
							<i class="icon-question ques"></i>
							<span>简历回复率:</span>
							<span class="number"><?php echo $_smarty_tpl->getVariable('reversion_rate')->value['reply_rate'];?>
% <?php if ($_smarty_tpl->getVariable('reversion_rate')->value['reply_rate']<50||$_smarty_tpl->getVariable('reversion_rate')->value['reply_rate']=='低于20'){?><i></i><?php }?></span>
							<p class="orang">
								简历回复率=最近2周企业处理简历数/投递简历数；回复率提升10%收到的简历数将提升20%
							</p>
							<p class="resOrang">
								数据过低，影响招聘效果
							</p>
						</li>
						<li class="item" style="width:170px;">
							<i class="icon-question ques"></i>
							<span>简历回复时长:</span>
							<span class="number"><?php echo $_smarty_tpl->getVariable('reversion_rate')->value['avg_time'];?>
<?php echo $_smarty_tpl->getVariable('reversion_rate')->value['day_hour'];?>

                                <?php if (($_smarty_tpl->getVariable('reversion_rate')->value['avg_time']>2&&$_smarty_tpl->getVariable('reversion_rate')->value['day_hour']=='天')){?>
                                <i></i>
                                <?php }?>
                            </span>
							<p class="orang">简历回复时长是最近2周企业处理投递简历的平均回复时长；处理越及时可能收到的简历就越多哦~</p>
							<p class="resOrang">
								数据过低，影响招聘效果
							</p>
						</li>
						<li class="item" style="width:170px;">
							<i class="icon-question ques"></i>
							<span>职位相符度得分:</span>
							<span class="number" style="<?php if (!$_smarty_tpl->getVariable('appraise_appraise')->value['population_avg_level']){?>color:#999;<?php }?>"><?php if ($_smarty_tpl->getVariable('appraise_appraise')->value['population_avg_level']){?><?php echo $_smarty_tpl->getVariable('appraise_appraise')->value['population_avg_level'];?>
分<?php }else{ ?>暂无<?php }?>
								<?php if (!empty($_smarty_tpl->getVariable('appraise_appraise',null,true,false)->value['population_avg_level'])&&$_smarty_tpl->getVariable('appraise_appraise')->value['population_avg_level']<3.5){?>
                                <a href="<?php echo smarty_function_get_url(array('rule'=>'/specification/StarAppraise'),$_smarty_tpl);?>
" target="_blank"></a>
                                <?php }?>
                            </span>
							<p class="orang">职位相符度得分为最近半年收到求职者面试评价的平均得分；改进近期面试体验，评分会升高哦~</p>
							<p class="resOrang" style="text-align: left;">
								数据过低，影响招聘效果；点击图标查看如何提升！
							</p>
						</li>
                        <?php if (count($_smarty_tpl->getVariable('company_account_info')->value)>1&&$_smarty_tpl->getVariable('is_main')->value){?>
						<li style="width:50px; padding-left: 0; z-index: 5;">
							<a href="javascript:void(0);" id="moreAppraise" class="number resMore">更多></a>
						</li>
                        <?php }?>
					</ul>
				</fieldset>
				<!--/近两周-->
				<!--职位刷新-->
				<div class="pub-job-select clearfix">
					<span class="title">招聘人：</span>
					<div class="simulation-select" id="simulation-select">
						<p class="select-value" style="color:#999"><i class="arrdown-icon"></i><?php if (!empty($_smarty_tpl->getVariable('account_id',null,true,false)->value)){?><?php ob_start();?><?php echo $_smarty_tpl->getVariable('company_account_info')->value[$_smarty_tpl->getVariable('account_id')->value]['user_name'];?>
<?php $_tmp1=ob_get_clean();?><?php if (!empty($_tmp1)){?><?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->getVariable('company_account_info')->value[$_smarty_tpl->getVariable('account_id')->value]['user_name'],6,'utf-8','','...');?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('company_account_info')->value[$_smarty_tpl->getVariable('account_id')->value]['user_id'];?>
<?php }?><?php }else{ ?>请选择招聘人<?php }?></p>
						<ul class="select-list">
							<li><a class="md_index_account_select" href="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/index?all=1" data-id="">不限</a></li>
							<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('company_account_info')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
							<li><a class="md_index_account_select" href="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/index?account_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['account_id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['account_id'];?>
"><?php if (!empty($_smarty_tpl->tpl_vars['v']->value['user_name'])){?><?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['v']->value['user_name'],8,'utf-8','','...');?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['v']->value['user_id'];?>
<?php }?></a></li>
							<?php }} ?>
						</ul>
						<input type="hidden" value="0" />
					</div>

					<div class="refresh-job">

						<a href="/partjob/addjobselect/" target="_blank" class="firmRefresh md_index_pubjob">发布职位</a>
            			<?php if ($_smarty_tpl->getVariable('cq_is_batch_refresh')->value){?>
						<a href="<?php echo smarty_function_get_url(array('rule'=>'/autorefresh/index/'),$_smarty_tpl);?>
" id="dingshi" class="firmRefresh md_index_timerefresh" style="cursor: pointer;">
							<i class="icon-details-time"></i>
							定时刷新
						</a>
						<a id="refreshAllJob" class="firmRefresh md_index_muchrefresh" style="cursor: pointer;">
										<i class="icon-manage-refresh"></i>
						  批量刷新
						</a>
            			<?php }?>
						<!--<a href="javascript:;" class="firmRefresh" onclick1="return refreshAll()" id="refreshAll" data-lastTime="<?php echo $_smarty_tpl->getVariable('lastrefreshtime')->value;?>
">刷新职位(<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_today_overplus'])===null||$tmp==='' ? 0 : $tmp);?>
/<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_perday'])===null||$tmp==='' ? 0 : $tmp);?>
)</a>-->
						<?php if (((($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_today_overplus'])===null||$tmp==='' ? 0 : $tmp))!=0){?>
						<?php if (count($_smarty_tpl->getVariable('job_lists')->value)>0&&$_smarty_tpl->getVariable('is_show_refresh_tip')->value){?>
						<div class="alert-warniong mgt15" style="top: 40px;left: 210px;width: 289px;<?php if ($_smarty_tpl->getVariable('cq_is_batch_refresh')->value){?>left: 86px;<?php }?>">
							<a class="close" href="javascript:void(0)" id="closeRefreshTip">×</a>
							<i class="alert-icon"></i>
							<i class="arrdown-icon"></i>
							您的职位排名已落后了，快刷新提高排名吧！
						</div>
						<?php }?>
						<?php }else{ ?>
						<div class="alert-warniong mgt15" style="top:40px;display: none;">
							<a class="close" href="javascript:void(0)" id="closeRefreshTip">×</a>
							<i class="arrdown-icon"></i>
							<p style="height: 101px;width: 330px">
								<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/account/add_appresume.jpg" style="float: left;margin-right: 20px" />
								<span style="font-size: 14px;color: #333;display: block;padding-top: 5px;padding-bottom: 20px">使用<font style="color: #f00">汇博企业APP</font>，免费刷新职位。快速提升职位浏览量。</span>
								<span style="color: #999;font-size: 12px">扫码安装汇博企业APP，支持安卓 IOS</span>
							</p>
						</div>
						<?php }?>
						<div class="freshTit">
							<p><span id="refreshTimeSpan" <?php if (!isset($_smarty_tpl->getVariable('refresh_time',null,true,false)->value)){?>style="display:none"<?php }?>><i id="divRefreshTime"><?php echo $_smarty_tpl->getVariable('refresh_time')->value;?>
</i>刷新过</span></p>
							<p style="position:relative">
								<input type="hidden" id="refreshStatus" value="<?php echo $_smarty_tpl->getVariable('is_auto')->value;?>
">

								<?php if (isset($_smarty_tpl->getVariable('autorefreshtime',null,true,false)->value)){?>
								<span class="autorefreshtime" style="<?php if (isset($_smarty_tpl->getVariable('autorefreshtime',null,true,false)->value)&&$_smarty_tpl->getVariable('is_auto')->value!=0){?>display:none;<?php }?>margin-right:-10px"><?php echo $_smarty_tpl->getVariable('autorefreshtime')->value;?>
</span>
								<?php }?>
							</p>
							<div class="alt" id="show_alt" style="display:none;">
								<i></i>开启后，系统会在15天内每天10:00-20:00随机挑选一个时间为您刷新1次所有在招职位。建议您在不能登录网站的情况下开启，手动刷新效果更佳。
							</div>
						</div>
					</div>

				</div>
				<style>
					#importP a{padding:0px !important; height:auto !important; line-height: inherit !important; float: none; display: inline-block; overflow:visible; font-size:12px;}
					.freshList li .subFresh06{width: 55px;position: relative;overflow: visible;display: inline-block;}
					.freshList li .subFresh06 div{width:100%;height:100%;overflow: hidden;}
					.freshList li span, .freshList .subFresh05, .freshList .subFresh02{padding-left:5px;}
					.freshList li .subFresh01{width: 225px;}
				</style>
				<!--职位列表管理 -->
				<div style="">
					<ul class="freshList clearfix <?php if (count($_smarty_tpl->getVariable('job_lists')->value)>10){?>js-over-height<?php }?>">
						<li class="freshGray clearfix" >
                            <?php if ($_smarty_tpl->getVariable('cq_is_batch_refresh')->value){?><input class="select-all-refresh-job" type="checkbox"/><?php }?>
							<span class="subFresh01">职位名称</span>
							<span class="subFresh06">招聘人</span>
							<span class="subFresh03" style="padding-left: 10px;">待回复</span>
							<span class="subFresh04">未读</span>
							<!--<span class="subFresh05">待定</span>-->
							<span class="subFresh02">收到</span>
							<span class="subFresh07">刷新时间</span>
							<span class="subFresh08">操作</span>
						</li>
						<?php if ($_smarty_tpl->getVariable('importData')->value['is_import']==1){?>
						<li style="padding-left: 0;">
							<p style="padding: 5px 10px;border:1px solid #fbaba9;background: #fff2ef;color: #444;font-size: 12px;" id="importP">
								<a id="js_click_import" href="javascript:void(0);" style="color: #d5535d;text-decoration: underline;float: right !important; height:auto; line-height:18px;">一键导入汇博</a>
								<?php echo $_smarty_tpl->getVariable('importData')->value['stationstring'];?>

							</p>
						</li>
						<?php }?>
						<?php if (count($_smarty_tpl->getVariable('job_lists')->value)>0){?>
						<?php  $_smarty_tpl->tpl_vars['job'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('job_lists')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['job']->key => $_smarty_tpl->tpl_vars['job']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['job']->key;
?>

							<?php if ($_smarty_tpl->tpl_vars['job']->value['promiseStop']==1){?>
							<li class ="<?php if ($_smarty_tpl->tpl_vars['k']->value%2==1){?>freshGraybg <?php }?>freshRedColor clearfix">
                                <?php if ($_smarty_tpl->getVariable('cq_is_batch_refresh')->value){?> <input class="un-select-refresh-job " value="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
" type="checkbox" disabled/><?php }?>

								<a href='<?php echo $_smarty_tpl->tpl_vars['job']->value["job_link"];?>
' style="color:#ff1919" title="<?php echo $_smarty_tpl->tpl_vars['job']->value['station'];?>
<?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']==4){?>&#10审核中<?php }?>" target="_blank" class="subFresh01">
									<font style="max-width:107px"><?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['job']->value['station'],16,'utf-8','','…');?>
</font>
									<!--急聘-->
									<?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->tpl_vars['job']->value['urgent_mark'])&&$_smarty_tpl->tpl_vars['job']->value['urgent_mark']==true){?>
									<i class="icon-ji" title="<?php echo $_smarty_tpl->tpl_vars['job']->value['urgent_end_date'];?>
截止"></i>
									<?php }?>
									<!--置顶-->
									<?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->tpl_vars['job']->value['top_mark'])&&$_smarty_tpl->tpl_vars['job']->value['top_mark']==true){?>
									<i class="icon-ding"  title="已在<?php echo $_smarty_tpl->tpl_vars['job']->value['top_count'];?>
个关键词列表中置顶"></i>
									<?php }?>

									<!--职位精准推广-->
									<?php if (in_array($_smarty_tpl->tpl_vars['job']->value['job_id'],$_smarty_tpl->getVariable('EffectSpreadJob_ids')->value)){?>
									<i class="icon-spread" ></i>
									<?php }?>
									<?php if (in_array($_smarty_tpl->tpl_vars['job']->value['re_apply_type'],array(2,5))){?>
									<img style="float: left;margin-right:3px;" title="<?php echo $_smarty_tpl->tpl_vars['job']->value['re_apply_type'];?>
天内回复" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/<?php echo $_smarty_tpl->tpl_vars['job']->value['re_apply_type'];?>
.png"/>
									<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['job']->value['agency_state']==1||$_smarty_tpl->tpl_vars['job']->value['agency_state']==2){?>
									<!--<div class="daiyunying"  >-->
										<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/daiyunying.jpg" style="float: left;margin-left:3px" width="42px" tit="此职位由汇博代为发布及运营"/>
										<!--<p><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon22.png" alt="">此职位由汇博代为发布及运营</p>-->
									<!--</div>-->
									<?php }?>


								</a>
								<span class="subFresh06"><div><?php echo $_smarty_tpl->tpl_vars['job']->value['account_user_name'];?>
</div>
								<?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']!=4&&$_smarty_tpl->getVariable('company_level')->value>0&&$_smarty_tpl->getVariable('CompanyAuditStatus')->value=='认证通过'){?>
									<span class="share-box" data-id="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
">
										分享（<a class="share-link md_index_mh5" href="javascript:viod(0);">海报</a>/<a class="share-h5 md_index_mh5" target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>'/index/TemplateStatisticalByH5','data'=>'type=3'),$_smarty_tpl);?>
">H5页面</a>）
									</span>
									<?php }?>
								</span>
								<!--待回复-->
								<a class="subFresh03" target='_blank'<?php if ($_smarty_tpl->tpl_vars['job']->value['no_reply_num']>0){?> href='/apply/Index/?job_id=<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
' <?php }?>><?php echo $_smarty_tpl->tpl_vars['job']->value['no_reply_num'];?>
<?php if ($_smarty_tpl->tpl_vars['job']->value['no_reply_num']>0){?><i></i><?php }?></a>
								<!--未读-->
								<a class="subFresh04" target='_blank' <?php if ($_smarty_tpl->tpl_vars['job']->value['applyNotReadCount']>0){?>href='/apply/Index/?child_status=2&job_id=<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
'<?php }?>><?php echo $_smarty_tpl->tpl_vars['job']->value['applyNotReadCount'];?>
</a>
								<!--待定-->
								<!--<a class="subFresh05" target='_blank'<?php if ($_smarty_tpl->tpl_vars['job']->value['wait_deal_num']>0){?> href='/apply/Index/?job_id=<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
' <?php }?>><?php echo $_smarty_tpl->tpl_vars['job']->value['wait_deal_num'];?>
<?php if ($_smarty_tpl->tpl_vars['job']->value['wait_deal_num']>0){?><i></i><?php }?></a>-->
								<!--收到-->
								<span class="subFresh02"><?php echo $_smarty_tpl->tpl_vars['job']->value['applyCount'];?>

									<?php if ($_smarty_tpl->tpl_vars['job']->value['is_show_recommend_tip']&&empty($_smarty_tpl->getVariable('recommend_tip_cookie',null,true,false)->value)){?>
									<div class="autoRecommenTip">
										收到简历少？试试<a href='<?php echo smarty_function_get_url(array('rule'=>"/recommend/index",'data'=>"type=1&job_id=".($_smarty_tpl->tpl_vars['job']->value['job_id'])),$_smarty_tpl);?>
' target="_blank" data-recommendjob="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
" class="recommend_job">推荐的简历</a><a class="closeTipBtn" href="javascript:void(0);">x</a>
									</div>
									<?php }?>
								</span>
								<span class="subFresh07"><?php echo $_smarty_tpl->tpl_vars['job']->value['refresh_time'];?>
</span>
							<span class="subFresh08">
								<a href="javascript:;" class="job_close md_index_close" data-id="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
" style="margin-right: 10px">关闭</a>
								<!--<a href="javascript:;" class="lazy" data-id="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
">延期</a>-->
							</span>

							</li>
							<?php }else{ ?>
								<li class ="<?php if ($_smarty_tpl->tpl_vars['k']->value%2==1){?>freshGraybg<?php }?> clearfix">
                                    <?php if ($_smarty_tpl->getVariable('cq_is_batch_refresh')->value){?><input class="<?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']==4){?>un-select-refresh-job<?php }else{ ?>select-refresh-job <?php }?> " value="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
" type="checkbox" <?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']==4){?>disabled<?php }?>/><?php }?>
									<a <?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']!=4){?>href='<?php echo $_smarty_tpl->tpl_vars['job']->value["job_link"];?>
'<?php }?> title="<?php echo $_smarty_tpl->tpl_vars['job']->value['station'];?>
<?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']==4){?>&#10审核中<?php }?>" target="_blank" class="subFresh01 clearfix <?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']==4){?> check_status<?php }?>" style="cursor:pointer;">

										<font style="max-width:99px"><?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['job']->value['station'],16,'utf-8','','…');?>
</font>
										<!--急聘-->
										<?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->tpl_vars['job']->value['urgent_mark'])&&$_smarty_tpl->tpl_vars['job']->value['urgent_mark']==true){?>
										<i class="icon-ji" title="<?php echo $_smarty_tpl->tpl_vars['job']->value['urgent_end_date'];?>
截止"></i>
										<?php }?>
										<!--置顶-->
										<?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->tpl_vars['job']->value['top_mark'])&&$_smarty_tpl->tpl_vars['job']->value['top_mark']==true){?>
										<i class="icon-ding"  title="已在<?php echo $_smarty_tpl->tpl_vars['job']->value['top_count'];?>
个关键词列表中置顶"></i>
										<?php }?>
										<!--职位精准推广-->
										<?php if (in_array($_smarty_tpl->tpl_vars['job']->value['job_id'],$_smarty_tpl->getVariable('EffectSpreadJob_ids')->value)){?>
										<i class="icon-spread" ></i>
										<?php }?>
										<?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']==4){?>
										<img title="待审核" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/shen.png" class="shen" style="float: left; margin-left:3px;margin-right:3px;padding-left:0" />
										<?php }else{ ?>
										<?php if (in_array($_smarty_tpl->tpl_vars['job']->value['re_apply_type'],array(2,5))){?>
										<img style="float: left;margin-right:3px" title="<?php echo $_smarty_tpl->tpl_vars['job']->value['re_apply_type'];?>
天内回复" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/<?php echo $_smarty_tpl->tpl_vars['job']->value['re_apply_type'];?>
.png"/>
										<?php }?>
										<?php }?>
										<?php if ($_smarty_tpl->tpl_vars['job']->value['agency_state']==1||$_smarty_tpl->tpl_vars['job']->value['agency_state']==2){?>
										<!--<div class="daiyunying" style="float: left;margin-right:3px" >-->
											<img title="此职位由汇博代为发布及运营" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/daiyunying.jpg" style="float: left;margin-right:3px;margin-top: 13px;" width="42px"/>
											<!--<p><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon22.png" alt="">此职位由汇博代为发布及运营</p>-->
										<!--</div>-->
										<?php }?>

									</a>

									<span class="subFresh06"><div><?php echo $_smarty_tpl->tpl_vars['job']->value['account_user_name'];?>
</div>
									<?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']!=4&&$_smarty_tpl->getVariable('company_level')->value>0&&$_smarty_tpl->getVariable('CompanyAuditStatus')->value=='认证通过'){?>
									<span class="share-box" data-id="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
">
										分享（<a class="share-link md_index_mh5" href="javascript:viod(0);">海报</a>/<a class="share-h5 md_index_mh5" target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>'/index/TemplateStatisticalByH5'),$_smarty_tpl);?>
" data="type=3">H5页面</a>）
									</span>
									<?php }?>
									</span>
									<!--待回复-->
									<a class="subFresh03" target='_blank'<?php if ($_smarty_tpl->tpl_vars['job']->value['no_reply_num']>0){?> href='/apply/Index/?job_id=<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
' <?php }?>><?php echo $_smarty_tpl->tpl_vars['job']->value['no_reply_num'];?>
<?php if ($_smarty_tpl->tpl_vars['job']->value['no_reply_num']>0){?><i></i><?php }?></a>
									<!--未读-->
									<a class="subFresh04" target='_blank' <?php if ($_smarty_tpl->tpl_vars['job']->value['applyNotReadCount']>0){?>href='/apply/Index/?child_status=2&job_id=<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
'<?php }?>><?php echo $_smarty_tpl->tpl_vars['job']->value['applyNotReadCount'];?>
</a>
									<!--待定-->
									<!--<a class="subFresh05" style="color:#2b6fad" target='_blank'<?php if ($_smarty_tpl->tpl_vars['job']->value['wait_deal_num']>0){?> href='/apply/Index/?job_id=<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
' <?php }?>><?php echo $_smarty_tpl->tpl_vars['job']->value['wait_deal_num'];?>
<?php if ($_smarty_tpl->tpl_vars['job']->value['wait_deal_num']>0){?><i></i><?php }?></a>-->
									<!--收到-->
									<span class="subFresh02"><?php echo $_smarty_tpl->tpl_vars['job']->value['applyCount'];?>

										<?php if ($_smarty_tpl->tpl_vars['job']->value['is_show_recommend_tip']&&empty($_smarty_tpl->getVariable('recommend_tip_cookie',null,true,false)->value)){?>
										<div class="autoRecommenTip" style="top: 30px;left: -45px;width: 200px;">
											收到简历少？试试<a href='<?php echo smarty_function_get_url(array('rule'=>"/recommend/index",'data'=>"type=1&job_id=".($_smarty_tpl->tpl_vars['job']->value['job_id'])),$_smarty_tpl);?>
' target="_blank" data-recommendjob="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
" class="recommend_job">推荐的简历</a><a class="closeTipBtn" href="javascript:void(0);">x</a>
										</div>
										<?php }?>
									</span>
									<?php if ($_smarty_tpl->tpl_vars['job']->value['no_reply_num']>0){?><i></i><?php }?>
									<!--到期时间-->
									<span class="subFresh07" <?php if ($_smarty_tpl->tpl_vars['job']->value['refresh_time']=='未刷新'){?>title='未刷新的职位只能展示在刷新职位之后'<?php }?>><?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']==4&&$_smarty_tpl->tpl_vars['job']->value['refresh_time']=='未刷新'){?><?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['job']->value['refresh_time'];?>
<?php }?></span>
								<span class="subFresh08">
									<a href="javascript:;"  class="job_close md_index_close" data-id="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
" style="margin-right: 5px">关闭</a>
									<?php if ($_smarty_tpl->tpl_vars['job']->value['check_state']!=4){?><a href="javascript:;" style="margin-right: 5px" class="job_refresh md_index_refresh" data-id="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
" >刷新</a><?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['job']->value['is_show_lazy']){?><a href="javascript:;"   class="lazy md_index_delay" data-id="<?php echo $_smarty_tpl->tpl_vars['job']->value['job_id'];?>
">延期</a><?php }?>
								</span>

								</li>
							<?php }?>

							<?php if ($_smarty_tpl->tpl_vars['k']->value==($_smarty_tpl->getVariable('promiseStopSize')->value-1)){?>
							<!-- 分隔线 -->
							<li class="freshOne">
								<p><i></i><span style="color:#999">以上停招的承诺职位请将简历回复完</span></p><!--会员-->
							</li>
							<?php }?>
						<?php }} ?>
						<?php }else{ ?>
						<?php }?>
						<?php if ($_smarty_tpl->getVariable('memberinfo')->value!='member'){?>
						<?php }?>
					</ul>
					<?php if (count($_smarty_tpl->getVariable('job_lists')->value)>10){?>
					<p style="margin-top:10px" align="right" id="jobMore">
						<a target="_blank">查看更多<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_move1.jpg" style="vertical-align: -2px;margin-left:6px" /></a>
					</p>
					<?php }?>

					<!--2018/6/19 企业招聘中心及区县职位列表调整 新增内容-->
                    <style>
                        .no-data-con div.none-job-data {
                            text-align: center;

                        }
                        .on-data-con div.none-job-data a {
                            float: none;
                            display: inline;
                            line-height: 30px;
                        }
                    </style>
                    <div class="no-data-con" style="margin-top: 10px;">
                        <div class="none-job-data" style="display: <?php if (count($_smarty_tpl->getVariable('job_lists')->value)>0){?>none<?php }?>" >您还没有发布职位哦~<a href="<?php echo smarty_function_get_url(array('rule'=>'/partjob/addjobselect/'),$_smarty_tpl);?>
" target="_blank">立即免费发布职位</a></div>
                        <div class="none-job-data" style="display: <?php if (count($_smarty_tpl->getVariable('job_lists')->value)>0&&$_smarty_tpl->getVariable('company_level')->value==1&&in_array($_smarty_tpl->getVariable('is_audit')->value,array(0,2,3))){?>block<?php }else{ ?>none<?php }?>" >
							您尚未完成企业认证，发布的职位无法展示~
							<a href="<?php echo smarty_function_get_url(array('rule'=>'/licencevalidate/index/'),$_smarty_tpl);?>
" target="_blank">
								立即上传认证资料
							</a>
						</div>
                        <div class="none-job-data" style="display: <?php if (count($_smarty_tpl->getVariable('job_lists')->value)>0&&$_smarty_tpl->getVariable('company_level')->value==1&&$_smarty_tpl->getVariable('is_audit')->value>0&&$_smarty_tpl->getVariable('is_audit')->value!=3&&$_smarty_tpl->getVariable('is_audit')->value!=2){?>block<?php }else{ ?>none<?php }?>">
							您发布的职位只能展示在付费会员之后，开通会员享受更多特权~
							<a href="//wpa.qq.com/msgrd?v=3&uin=<?php echo trim($_smarty_tpl->getVariable('hrManager')->value['qq']);?>
&site=qq&menu=yes" target="_blank">
								立即开通会员
							</a>
						</div>
                        <div class="none-job-data" style="display: <?php if (count($_smarty_tpl->getVariable('job_lists')->value)>0&&empty($_smarty_tpl->getVariable('company_level',null,true,false)->value)&&!$_smarty_tpl->getVariable('is_free_vip')->value){?>block<?php }else{ ?>none<?php }?>">
							您尚未开通会员，发布的职位无法展示~
							<a href="//wpa.qq.com/msgrd?v=3&uin=<?php echo trim($_smarty_tpl->getVariable('hrManager')->value['qq']);?>
&site=qq&menu=yes" target="_blank">立即开通会员</a></div>
                    </div>
					<!---->

				</div>

				<div class="marketTip" style="">
					<i></i>
					<a class="md_index_upremmon" href="<?php echo smarty_function_get_url(array('rule'=>'/spreadjob/index/'),$_smarty_tpl);?>
"><span>精准推广，增加曝光，提升效果！</span></a>
				</div>

				<div class="marketPopx marketPopx1" style="display: none;">
					<i class="markeIcon"></i>
					<div class="marketPop">

						<span>正在推广职位：<?php echo count($_smarty_tpl->getVariable('EffectSpreadJob_ids')->value);?>
 </span>
						<span>今日展示量：<i id="spreadJobSeeCount">正在统计...</i></span>
						<span class="last">今日点击量：<i id="spreadClickCount">正在统计...</i></span>
					</div>
				</div>
			</div>

			<!--<div class="job-content-news">-->
				<!--<p style="margin-top: 15px; display: inline-block; overflow: hidden; width:100%;color: #999;">昨日（<?php echo date("Y-m-d",strtotime('-1 day'));?>
） <a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/readjob/CompanyVisit"),$_smarty_tpl);?>
" style="float:right;color:#2b6fad">更多数据 ></a> </p>-->
				<!--<ul style="margin-top: 5px; overflow: hidden;">-->
					<!--<li style="width:33.33%; float: left;">职位浏览量：<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/readjob/CompanyVisit"),$_smarty_tpl);?>
" style="color: #2b6fad"><?php echo $_smarty_tpl->getVariable('job_visit_number')->value['job_visit_count'];?>
</a></li>-->
					<!--<li style="width:33.33%; float: left;">简历投递量：<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/readjob/CompanyVisit"),$_smarty_tpl);?>
" style="color: #2b6fad"><?php echo $_smarty_tpl->getVariable('job_visit_number')->value['job_apply_count'];?>
</a></li>-->
					<!--<li style="width:33.33%; float: left;">面试邀请量：<a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/readjob/CompanyVisit"),$_smarty_tpl);?>
" style="color: #2b6fad"><?php echo $_smarty_tpl->getVariable('job_visit_number')->value['job_invite_count'];?>
</a></li>-->
				<!--</ul>-->
			<!--</div>-->

			<style>
                .job-seeker-list {
                }
                .job-seeker-list h3 {
                    overflow: hidden;line-height: 24px;
                    margin-bottom: 10px;
                }
                .job-seeker-list h3 p.title {
                    float: left;
                    color: #444;
                    font-size: 16px;
                }
                .job-seeker-list h3 p.refresh-num {
                    float: right;color: #999;font-size: 12px;
                }
                .job-seeker-list ul {
                    overflow: hidden;
                    padding-bottom: 10px;
                }
                .job-seeker-list li {
                    float: left;
                    margin:0 10px 10px 0;
                    width: 210px;
                    box-sizing: border-box;
                    border: 1px dashed #f1f1f1;
                }
                .job-seeker-list li a {
                    display: block;
                    cursor: pointer;
                    overflow: hidden;
                    color: #666;
                }
                .job-seeker-list li.last-seeker {
                    margin-right: 0;
                }
                .job-seeker-list li .info-con {
                    overflow: hidden;
                    padding: 10px;
                    background: #fafafa;
                }
                .job-seeker-list li .avatar {
                    float: left;
                    width: 40px;
                    height: 40px;
                    background-color: #ccc;
                    margin-top: 2px;
                    margin-right: 10px;
                    border-radius: 50%;
                }
                .job-seeker-list li .info {
                    float: left;
					width: 136px;
                }
                .job-seeker-list li .info .name {
                    font-size: 16px;color: #444;
                }
                .job-seeker-list li .info .other {
                    font-size: 12px;color: #666;
					overflow: hidden;
					 white-space: nowrap;
					 text-overflow: ellipsis;
					 width:136px;
                    height:18px;
                    line-height:18px;
                }

                .job-seeker-list li .order-job {
                    margin:10px 10px 0 10px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    width:188px;
                    color: #666;
                    height: 21px;
                    line-height: 21px;
                }
                .job-seeker-list li .order-place {
                    margin:8px 10px 12px 10px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    color: #666;
                    width:188px;
                    height: 21px;
                    line-height: 21px;
                }

                .company-list {
                }
                .company-list h3 {
                    overflow: hidden;line-height: 24px;
                    margin-bottom: 10px;
                }
                .company-list h3 .title {
                    float: left;
                    font-size: 16px;
                    color: #444;
                }
                .company-list h3 .refresh-num {
                    float: right;color: #999;font-size: 12px;
                }
                .company-list ul {
                    overflow: hidden;
                    padding-bottom: 10px;
                }
                .company-list li {
                    overflow: hidden;
                    width: 320px;
                    box-sizing: border-box;
                    border: 1px dashed #f1f1f1;
                    float: left;
                    height: 60px;
                    margin-bottom: 10px;
                }
                .company-list li a {
                    display: block;
                    padding: 10px;
                    overflow: hidden;
                    cursor: pointer;
                    color: #444;
                }

                .company-list li.odd {
                    margin-right: 10px;
                }

                .company-list li img {
                    display: block;
                    box-sizing: border-box;
                    border:1px solid #f6f6f6;
                    width: 40px;
                    height: 40px;
                    float: left;
                    margin-right: 8px;
                    border-radius: 50%;
                }
                .company-list li .name {
                    font-size: 16px;
                    height: 40px;
                    float: left;
                    width: 115px;
                    padding-right: 10px;
                    border-right: 1px dashed #f0f0f0;
                    margin-right: 10px;
                    line-height: 20px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    display: -webkit-box;
					display: flex;
					align-items:center;
                }
                .company-list li .pub-job {
                    float: left;
                    height: 40px;
                    text-align: center;
                    margin-right: 15px;
                }
                .company-list li .pub-job span {
                    font-size: 16px;
                }
                .company-list li .pub-job p {
                    color: #999;
                    font-size: 12px;
                }
                .company-list li .get-resume {
                    float: left;
                    height: 40px;
                    text-align: center;
                }
                .company-list li .get-resume span {
                    font-size: 16px;
                }
                .company-list li .get-resume  p {
                    color: #999;
                    font-size: 12px;
                }

			</style>
            <style>
                div.not-vip-box {
                    padding-bottom: 20px;
                }
                .not-vip-box .title {
                    color: #444;
                    font-size: 16px;
                    font-weight: bold;
					overflow: hidden;
                }
                .not-vip-box .title a.buy-now {
                    font-size: 14px;
                    color: #fff;
                    background: #66bce4;
                    width: 76px;
                    height: 30px;
                    line-height: 30px;
                    font-weight: normal;
                    display: inline-block;
                    text-align: center;
                    border-radius: 3px;
                    float: right
                }
                .not-vip-box .title a.know-more {
                    color: #4bb6e7;
                    font-weight: 400;
                    font-size: 14px;
                    margin-left: 10px;
                    display: inline;
                    background-color: #fff;
                    cursor: pointer;
					float: right;
					line-height: 30px;
                }
                .not-vip-box .open-vip-teiqan {
                    margin-top: 10px;
                }
                .membershipDuex{width:415px; overflow: hidden;  margin: 20px; text-align: left;}
                .duex01{ display: block; color: #444; line-height: 22px;}
                .duex01 i{ color: #f00;}
                .duez{ overflow: hidden; color: #444;padding-left: 108px;}
                .duez span{ display: block; }
                .duez span.duez01{ color: #999; padding: 20px 0 10px 0;}
                .duez span.duez01 b{ font-weight: normal; color: #444; font-size: 16px; font-weight: bold; padding-right: 10px;}
                .duez span.duez02{ line-height: 30px; color: #444;}
                .duez span.duez02 b{ font-weight: normal; color: #999;}
                .hb_ui_dialog .ui_dialog_message{ text-align: left; line-height: 24px!important; color: #444;}
                .button_a_red,.cancelbtn{ background: #66bce4!important;border:1px solid #66bce4!important; border-radius: 4px;height:30px!important; line-height: 30px!important;width:80px!important;}
                .cancelbtn,.cancelbtn:hover{background: #eee!important; color: #999!important; border: none!important;}
			</style>
           
            <!--左侧非会员-->
            <?php if ($_smarty_tpl->getVariable('memberinfo')->value!='member'){?>



			<div class="not-vip-box">
				<p style="display: none" class="hot"><i></i><?php if ($_smarty_tpl->getVariable('is_audit')->value!=1&&$_smarty_tpl->getVariable('is_audit')->value!=4){?><a target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>'/licencevalidate/'),$_smarty_tpl);?>
">上传营业执照</a>并审核通过后，<?php }?>餐饮行业企业和重庆区县地区企业，可<strong>免费</strong>发布<strong><?php echo $_smarty_tpl->getVariable('job_number')->value;?>
个</strong>职位，排在会员企业之后展示。</p>

				<p class="title">您还不是会员，开通会员可享受以下特权:
					<a href="<?php echo smarty_function_get_url(array('rule'=>'/index/memberdetail/'),$_smarty_tpl);?>
" class="know-more" target="_blank">了解更多</a>
					<a class="buy-now" target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=<?php echo trim($_smarty_tpl->getVariable('hrManager')->value['qq']);?>
&site=qq&menu=yes">立即开通</a>
				</p>
				<div class="open-vip-teiqan">
					<p class="itme">
						<span style="width: 205px">1、可免费同时发布<strong>100个</strong>职位</span>
						<span style="width: 155px">2、会员职位优先展示</span>
						<!--<span>3、每日最高可刷新5次职位</span>-->
						<!--<span style="width: 205px">4、面试邀请短信免费发送</span>-->
						<span>3、下载简历最高优惠<strong>80%</strong></span>
					</p>
				</div>
				<?php if ($_smarty_tpl->getVariable('isEndMember')->value){?><?php }?>
				<!--2018/6/19 企业招聘中心及区县职位列表调整  把下面这个模块关闭 display:none-->
				<div class="open-vip-job" style="display: none">
					<p class="vip-title-h2">他们都成为了我们的高级会员：</p>
					<div class="firmRmendLbg clearfix">
						<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('famous_company')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
							<dl class="firmRmendL">
								<dt><a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['company_url'];?>
" target="_blank"><img width="44" height="44" src="<?php echo $_smarty_tpl->tpl_vars['v']->value['logo_path'];?>
"></a></dt>
								<dd>
									<p class="name"><a target='_blank' href="<?php echo $_smarty_tpl->tpl_vars['v']->value['company_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['company_shortname'];?>
</a><?php if ($_smarty_tpl->tpl_vars['v']->value['is_audit']==1&&($_smarty_tpl->tpl_vars['v']->value['audit_state']==1||$_smarty_tpl->tpl_vars['v']->value['audit_state']==4)){?><i class="renz"></i><?php }?><!--已认证图标-->
										<!--<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon19.png" width="15" height="17" />--><!--会员图标-->
									</p>
									<a target='_blank' title="<?php echo $_smarty_tpl->tpl_vars['v']->value['company_name'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['v']->value['company_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['company_name'];?>
</a>
								</dd>
							</dl>
						<?php }} ?>
					</div>
				</div>
			</div>
			<?php }?>

			<?php if (count($_smarty_tpl->getVariable('recommend')->value)>0||(count($_smarty_tpl->getVariable('newest_recommend_resume')->value)>0&&$_smarty_tpl->getVariable('can_use_newresume_search')->value==1)){?>
			<!-- 推荐的简历 -->
			<div class="firmConTopBg">
				<div class="firmRmendTit">
					<?php if (count($_smarty_tpl->getVariable('recommend')->value)>0){?>
					<span class="talent TalentCur">推荐人才</span>
					<?php if ($_smarty_tpl->getVariable('recommend_resume_num')->value>0){?>
					<a class="md_index_remmonperson_more" href="/recommend/index/?type=2" target="_blank">更多<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_move2.jpg" style="margin-left:3px" /></a>
					<?php }else{ ?>
					<a class="md_index_remmonperson_more" href="/recommend/index/?type=1" target="_blank">更多<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_move2.jpg" style="margin-left:3px" /></a>
					<?php }?>
					<?php }?>
					<?php if (count($_smarty_tpl->getVariable('newest_recommend_resume')->value)>0&&$_smarty_tpl->getVariable('can_use_newresume_search')->value==1){?>
					<span class="talent <?php if (empty($_smarty_tpl->getVariable('recommend',null,true,false)->value)){?> TalentCur <?php }?>" style="position: relative;">
						<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/newDemand.png" class="newDemand"/>最新人才
					</span>
					<a class="md_index_remmonperson_more" id="md_index_newremmonperson_more" style="display: none;<?php if (empty($_smarty_tpl->getVariable('recommend',null,true,false)->value)){?>display: block;<?php }?>" onclick="gotoSearch()" href="javascript:;" >更多<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_move2.jpg" style="margin-left:3px" /></a>
					<script type="text/javascript">
						function gotoSearch(){
							var station = encodeURIComponent('<?php echo $_smarty_tpl->getVariable('newest_job_station')->value;?>
');
						    window.open( "/resumesearchnew/?station=" + station + "&from=index");
						}
					</script>
					<?php }?>
				</div>
				<!--推荐人才-->
				<div class="firmRmendBox">
					<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('recommend')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
					<a class="firmRmendItem md_index_remmonperson_list"  target="_blank" href = '<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/",'data'=>"type=network&src=recommend&resumeid=".($_smarty_tpl->tpl_vars['value']->value['resume_id'])),$_smarty_tpl);?>
'>

						<img src="<?php if ($_smarty_tpl->tpl_vars['value']->value['small_photo']){?><?php echo $_smarty_tpl->tpl_vars['value']->value['small_photo'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/headportrait.png<?php }?>" alt="" class="firmRmendImg" />
						<div class="firmRmendRight">
							<div class="firmRmendName"><?php echo $_smarty_tpl->tpl_vars['value']->value['user_name'];?>
</div>
							<div><?php echo $_smarty_tpl->tpl_vars['value']->value['sex'];?>
/<?php echo $_smarty_tpl->tpl_vars['value']->value['start_work'];?>
/<?php echo $_smarty_tpl->tpl_vars['value']->value['age'];?>
</div>
						</div>
						<div class="firmRmendEdu">
							<?php if ($_smarty_tpl->tpl_vars['value']->value['school']){?><span><?php echo $_smarty_tpl->tpl_vars['value']->value['school'];?>
</span><?php }else{ ?><span>未填写学校</span><?php }?><u>|</u>
							<?php if ($_smarty_tpl->tpl_vars['value']->value['major_desc']){?><span><?php echo $_smarty_tpl->tpl_vars['value']->value['major_desc'];?>
</span><?php }else{ ?><span>未填写专业</span><?php }?><u>|</u>
							<?php if ($_smarty_tpl->tpl_vars['value']->value['degree']){?><span><?php echo $_smarty_tpl->tpl_vars['value']->value['degree'];?>
</span><?php }?>
						</div>
						<div class="firmRmendWork">
							<?php if ($_smarty_tpl->tpl_vars['value']->value['station']){?><span class='bold'><?php echo $_smarty_tpl->tpl_vars['value']->value['station'];?>
</span><?php }?>
							<?php if ($_smarty_tpl->tpl_vars['value']->value['company_name']){?><u>|</u><span><?php echo $_smarty_tpl->tpl_vars['value']->value['company_name'];?>
</span><?php }?>
						</div>
					</a>
					<?php }} ?>
				</div>
				<!--最新人才-->
				<?php if (count($_smarty_tpl->getVariable('newest_recommend_resume')->value)>0){?>
				<div class="firmRmendBox" style="<?php if (!empty($_smarty_tpl->getVariable('recommend',null,true,false)->value)){?>display:none;<?php }?>">
					<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('newest_recommend_resume')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
					<a class="firmRmendItem md_index_remmonperson_list" target="_blank" href = '<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/",'data'=>"type=network&src=recommend&resumeid=".($_smarty_tpl->tpl_vars['value']->value['resume_id'])),$_smarty_tpl);?>
'>
						<img src="<?php if ($_smarty_tpl->tpl_vars['value']->value['small_photo']){?><?php echo $_smarty_tpl->tpl_vars['value']->value['small_photo'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/headportrait.png<?php }?>" alt="" class="firmRmendImg" />
						<div class="firmRmendRight">
							<div class="firmRmendName"><?php echo $_smarty_tpl->tpl_vars['value']->value['user_name'];?>
</div>
							<div><?php echo $_smarty_tpl->tpl_vars['value']->value['sex'];?>
/<?php echo $_smarty_tpl->tpl_vars['value']->value['start_work'];?>
/<?php echo $_smarty_tpl->tpl_vars['value']->value['age'];?>
</div>
						</div>
						<div class="firmRmendEdu">
							<?php if ($_smarty_tpl->tpl_vars['value']->value['school']){?><span><?php echo $_smarty_tpl->tpl_vars['value']->value['school'];?>
</span><?php }else{ ?><span>未填写学校</span><?php }?><u>|</u>
							<?php if ($_smarty_tpl->tpl_vars['value']->value['major_desc']){?><span><?php echo $_smarty_tpl->tpl_vars['value']->value['major_desc'];?>
</span><?php }else{ ?><span>未填写专业</span><?php }?><u>|</u>
							<?php if ($_smarty_tpl->tpl_vars['value']->value['degree']){?><span><?php echo $_smarty_tpl->tpl_vars['value']->value['degree'];?>
</span><?php }?>
						</div>
						<div class="firmRmendWork">
							<?php if ($_smarty_tpl->tpl_vars['value']->value['station']){?><span class='bold'><?php echo $_smarty_tpl->tpl_vars['value']->value['station'];?>
</span><?php }?>
							<?php if ($_smarty_tpl->tpl_vars['value']->value['company_name']){?><u>|</u><span><?php echo $_smarty_tpl->tpl_vars['value']->value['company_name'];?>
</span><?php }?>
						</div>
					</a>
					<?php }} ?>
				</div>
				<?php }?>
			</div>
			<?php }?>
			<!--2019.6.19把“他们正在汇博网寻找工作”模块放在推荐简历下面-->
			<!--2018/6/19 企业招聘中心及区县职位列表调整 新增内容-->
            <?php if ($_smarty_tpl->getVariable('company_level')->value<=1){?>
            <?php if (!empty($_smarty_tpl->getVariable('recommend_resume',null,true,false)->value)){?>
            <div class="not-vip-box">
                <div class="job-seeker-list">
                    <h3 >
                        <p class="title">他们正在汇博网寻找工作</p>
                        <p class="refresh-num">最近7天刷新简历<?php echo $_smarty_tpl->getVariable('refresh_times')->value;?>
次</p>
                    </h3>
                    <ul>
                        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('recommend_resume')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                        <li class="<?php if (($_smarty_tpl->tpl_vars['k']->value+1)%3==0){?>last-seeker<?php }?>">
                            <a href="<?php echo smarty_function_get_url(array('rule'=>'/resume/resumeshow/'),$_smarty_tpl);?>
type-network-resumeid-<?php echo $_smarty_tpl->tpl_vars['v']->value['resume_id'];?>
" target="_blank">
                                <div class="info-con" >
                                    <img class="avatar" src="<?php echo $_smarty_tpl->tpl_vars['v']->value['photo'];?>
"/>
                                    <div class="info">
                                        <p class="name"><?php echo $_smarty_tpl->tpl_vars['v']->value['user_name'];?>
</p>
                                        <p class="other" ><?php echo $_smarty_tpl->tpl_vars['v']->value['start_work'];?>
，<?php echo $_smarty_tpl->tpl_vars['v']->value['sex'];?>
，<?php echo $_smarty_tpl->tpl_vars['v']->value['age'];?>
岁</p>
                                    </div>
                                </div>
                                <div class="order-job">期望工作：<?php echo $_smarty_tpl->tpl_vars['v']->value['str_expect_callings'];?>
</div>
                                <div class="order-place">期望地点：<?php echo $_smarty_tpl->tpl_vars['v']->value['area_name'];?>
</div>
                            </a>
                        </li>
                        <?php }} ?>
                    </ul>
                </div>
            </div>
            <?php }?>
            <?php if (!empty($_smarty_tpl->getVariable('recommend_companys',null,true,false)->value)){?>
            <div class="not-vip-box">
                <div class="company-list">
                    <h3>
                        <p class="title">他们正在汇博网寻找人才</p>
                        <p class="refresh-num">最近7天获取简历<?php echo $_smarty_tpl->getVariable('download_total')->value;?>
份</p>
                    </h3>
                    <ul>
                        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('recommend_companys')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                        <li class="<?php if ($_smarty_tpl->tpl_vars['k']->value==0||$_smarty_tpl->tpl_vars['k']->value==2||$_smarty_tpl->tpl_vars['k']->value==4){?>odd<?php }?>">
                            <a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['company_path'];?>
" target="_blank">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['v']->value['logo_path'];?>
"/>
                                <div class="name"><?php echo $_smarty_tpl->tpl_vars['v']->value['company_name'];?>
</div>
                                <div class="pub-job">
                                    <span><?php echo $_smarty_tpl->tpl_vars['v']->value['job_count'];?>
</span>
                                    <p>发布职位</p>
                                </div>
                                <div class="get-resume">
                                    <span><?php echo $_smarty_tpl->tpl_vars['v']->value['down_total'];?>
</span>
                                    <p>获取简历</p>
                                </div>
                            </a>
                        </li>
                        <?php }} ?>
                    </ul>
                </div>
            </div>
            <?php }?>
            <?php }?>
            <!---->

			
			<!--最近招聘会 -->
			<?php if (count($_smarty_tpl->getVariable('lastThreeFairs')->value)>0&&substr($_smarty_tpl->getVariable('base_location_area')->value,0,2)=="03"){?>
			<div class="firmConTopBg" style="margin-bottom:0;">
				<div class="firmRmendTit">
					<span>最近招聘会</span>
				</div>
				<div class="ltyFairBgc">
					<div class="ltyFairBg">
						<?php  $_smarty_tpl->tpl_vars['fair'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('lastThreeFairs')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['fair']->key => $_smarty_tpl->tpl_vars['fair']->value){
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['fair']->key;
?>
						<dl class="clearfix" <?php if ($_smarty_tpl->tpl_vars['index']->value==0){?>style="padding-top:0px;border:0px"<?php }?>>
                                                    <?php if ($_smarty_tpl->tpl_vars['fair']->value['scene_id']==1078){?>
                                                    <dt class="ltyFlit"><a href="javascript:;" data-scene='745' style="cursor:default"><span>周六<br />5-19</span><i></i></a></dt>
                                                    <?php }else{ ?>
						<dt class="ltyFlit"><a href="javascript:;" data-scene='745' style="cursor:default"><span>周<?php echo $_smarty_tpl->getVariable('weekdays')->value[date('w',strtotime($_smarty_tpl->tpl_vars['fair']->value["date"]))];?>
<br /><?php echo date('m-d',strtotime($_smarty_tpl->tpl_vars['fair']->value["date"]));?>
</span><i></i></a></dt>
                                                    <?php }?>
						<dd class="ltyFlitText" <?php if (mb_strlen($_smarty_tpl->tpl_vars['fair']->value["subject"])<=22){?>style="margin-top: 20px"<?php }?>><a class="md_index_fire_list" href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/zhaopinhui/<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['fair']->value["date"],"%Y%m%d");?>
/" target="_blank"><?php echo $_smarty_tpl->tpl_vars['fair']->value["subject"];?>
</a></dd>
						<dt class="ltyFlitLink"><a  href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
<?php echo smarty_function_get_url(array('rule'=>'/zhaopinhui/enterprise/','data'=>"sceneid=".($_smarty_tpl->tpl_vars['fair']->value['scene_id'])."&fairApply=true"),$_smarty_tpl);?>
" target="_blank" id="changeFair" class="meCustom md_index_fire_dz">我要订展</a></dt>
						</dl>
						<?php }} ?>
					</div>
				</div>
			</div>
			<?php }?>
		</div>

		<!--右侧-->
		<div class="firmHrt">
			<div class="user-infor-box">
			<!--职位联系人-->
			<dl class="clearfix">
				<dt>
					<?php if (!empty($_smarty_tpl->getVariable('account_user_info',null,true,false)->value['head_photo'])){?>
					<img src="<?php echo $_smarty_tpl->getVariable('account_user_info')->value['head_photo'];?>
" />
					<?php }else{ ?>
					<span class="header-img"><?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->getVariable('account_user_info')->value['user_name'],1,'utf-8','','');?>
</span>
					<?php }?>
				</dt>
				<dd class="infor">
					<!--<strong class="name"><?php echo $_smarty_tpl->getVariable('account_user_info')->value['user_id'];?>
</strong>-->
					<!--<span class="zhiwei">-->
							<!--<?php echo $_smarty_tpl->getVariable('account_user_info')->value['user_name'];?>
-->
							<!--<?php if (!empty($_smarty_tpl->getVariable('account_user_info',null,true,false)->value['user_name'])&&!empty($_smarty_tpl->getVariable('account_user_info',null,true,false)->value['station'])){?>/ <?php }?>-->
							<!--<?php echo $_smarty_tpl->getVariable('account_user_info')->value['station'];?>
-->
						<!---->
							<!--<?php if (empty($_smarty_tpl->getVariable('account_user_info',null,true,false)->value['user_name'])||empty($_smarty_tpl->getVariable('account_user_info',null,true,false)->value['station'])){?>-->
								<!--<a target="_blank" href="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/account/accountmanage/">待完善</a>-->
							<!--<?php }?>-->
					<!--</span>-->
					<strong class="name"><?php echo $_smarty_tpl->getVariable('account_user_info')->value['user_id'];?>
 <a href="/account/editAccount/account_id-<?php echo $_smarty_tpl->getVariable('account_user_info')->value['account_id'];?>
" class="text-blue md_index_selfdetail" style="font-size: 14px;" target="_blank">查看资料</a> </strong>
					<span class="zhiwei">
						<?php if ($_smarty_tpl->getVariable('is_login_app')->value){?>
						<a href="javascript:;" class="btnx3" style="cursor: default">企业版APP<span class="green">（在线<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_offline.jpg" />）</span></a>
						<?php }else{ ?>
						<a href="javascript:;" class="btnx3" style="cursor: default;color:#999">企业版APP（离线<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_online.jpg" />）</a>
						<?php }?>
					</span>

				</dd>
			</dl>
			<p class="weixinApp">
						<?php if ($_smarty_tpl->getVariable('bindweixin')->value){?>
						<a class="btnx2 unbindWeixin md_index_unbindwx" href="javascript:;" data-value='<?php echo $_smarty_tpl->getVariable('open_id')->value;?>
'><i></i>解绑微信</a>
						<?php }else{ ?>
						<a class="btnx2 md_index_unbindwx" target="_blank" href="<?php echo smarty_function_get_url(array('rule'=>"/account/weixin/"),$_smarty_tpl);?>
" style="color:#ff5400"><i></i>请绑定微信</a>
						<?php }?>

						<em>|</em>
						<?php if (!empty($_smarty_tpl->getVariable('related_list',null,true,false)->value[$_smarty_tpl->getVariable('account_user_info',null,true,false)->value['account_id']])){?><a href='javascript:;' data-account='<?php echo $_smarty_tpl->getVariable('account_user_info')->value["account_id"];?>
' class='unRelatedPerson btnx3 md_index_unbindapp' data-value='<?php echo $_smarty_tpl->getVariable('related_list')->value[$_smarty_tpl->getVariable('account_user_info')->value["account_id"]]["person_id"];?>
'><i></i>解绑app</a>
						<?php }else{ ?><a href="javascript:;" class="btnx3 md_index_unbindapp" id="appbind" data-id="<?php echo $_smarty_tpl->getVariable('account_user_info')->value['account_id'];?>
"><i></i>绑定APP</a><?php }?>

						<!--<?php if ($_smarty_tpl->getVariable('is_login_app')->value){?>-->
						<!--<a href="javascript:;" class="btnx3" style="cursor: default"><i></i>企业版APP<span class="green">(在线<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_offline.jpg" />)</span></a>-->
						<!--<?php }else{ ?>-->
						<!--<a href="javascript:;" class="btnx3" style="cursor: default;color:#999"><i></i>企业版APP(离线<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_online.jpg" />)</a>-->
						<!--<?php }?>-->
						<?php if ($_smarty_tpl->getVariable('is_main')->value){?>
				        	<em>|</em><i class="sub"></i>
				        	<a class="sub-user" href="<?php echo smarty_function_get_url(array('rule'=>'/account/accountmanage'),$_smarty_tpl);?>
">子账号管理</a>
						<?php }?>
					</p>
		</div>
			<!--企业信息-->
			<div class="comp_infor">
				<dl class="clearfix">
					<dt>
						<input type="hidden" id="delete_logo" value='<?php echo $_smarty_tpl->getVariable('companyinfo')->value["company_logo_path"];?>
'>
						<a href="javascript:void(0)" class="img upLOGO">
							<img id="logo_path" src="<?php echo $_smarty_tpl->getVariable('logo_path')->value;?>
" />
							<span>上传LOGO</span>
						</a>
					</dt>
					<dd class="infor">
						<h2><a class="md_index_selfnameclick" href="/account/" target="_blank"><?php echo $_smarty_tpl->getVariable('companyinfo')->value['company_shortname'];?>
</a></h2>
						<p class="name"><?php if (!$_smarty_tpl->getVariable('companyinfo')->value['company_shortname']){?><a href="/account/" target="_blank"><?php echo $_smarty_tpl->getVariable('companyinfo')->value['company_name'];?>
</a><?php }else{ ?><?php echo $_smarty_tpl->getVariable('companyinfo')->value['company_name'];?>
<?php }?></p>
						<!--<p><?php if (!empty($_smarty_tpl->getVariable('companyinfo',null,true,false)->value['link_tel'])){?><?php echo $_smarty_tpl->getVariable('companyinfo')->value['link_tel'];?>
 <?php echo $_smarty_tpl->getVariable('companyinfo')->value['linkman'];?>
<?php }else{ ?> 还未填写<?php }?></p>-->
						<!--<p class="butn2"><a href="/company/modify/"><i></i>编辑资料</a>（<span><?php echo $_smarty_tpl->getVariable('companyInfoPercent')->value;?>
%</span>）</p>-->
						<p class="num" style="margin-top: 5px;">余额：<strong><?php echo sprintf("%.2f",$_smarty_tpl->getVariable('companyresources')->value['account_overage']);?>
元</strong><a href="<?php echo smarty_function_get_url(array('rule'=>'/pay/'),$_smarty_tpl);?>
" class="text-blue md_index_czmoney" target="_blank">充值</a></p>
					</dd>
				</dl>
				<div class="money">
					<!--<p class="butn"><a href="<?php echo smarty_function_get_url(array('rule'=>'/pay/'),$_smarty_tpl);?>
" target="_blank">充值</a></p>-->
					<!--<div>-->
						<!--<span>-->
							<!--<?php if ($_smarty_tpl->getVariable('is_audit')->value=='0'||$_smarty_tpl->getVariable('is_audit')->value==5){?><a target='_blank' href="/licencevalidate/index/action-againUpload" class="btnx1"><i></i>未认证</a><?php }?>-->
                                                        <!---->
							<!--<?php if ($_smarty_tpl->getVariable('companyinfo')->value['is_audit']=='1'&&$_smarty_tpl->getVariable('is_audit')->value!=5){?>-->
							<!--<a <?php if ($_smarty_tpl->getVariable('is_audit')->value=='4'){?>target='_blank' href="/licencevalidate/index/action-againUpload"<?php }else{ ?>href="javascript:void(0)" style="cursor:default;"<?php }?> class="btnx1 btnxcut1"><i></i>已认证<?php if ($_smarty_tpl->getVariable('companyinfo')->value['audit_state']=='2'){?>(临时)<?php }elseif($_smarty_tpl->getVariable('companyinfo')->value['audit_state']=='3'){?>(待补)<?php }?></a>-->
							<!--<?php }?>-->
							<!--<?php if ($_smarty_tpl->getVariable('is_audit')->value=='2'){?><a href="javascript:void(0)" class="btnx1" style="cursor:default;"><i></i>认证中</a><?php }?>-->
							<!--<?php if ($_smarty_tpl->getVariable('is_audit')->value=='3'){?><a target='_blank' href="/licencevalidate/index/action-againUpload" class="btnx1"><i></i>认证失败</a><?php }?>-->
						<!--</span>-->
						<!--<em>|</em>-->
						<!--<a href="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/index/TemplateStatisticalByH5" class="btnx3 btnxcut3" id="weiBtn" target="_blank"><i></i>微招聘</a>-->
						<!--<p class="num">账户余额：<strong><?php echo sprintf("%.2f",$_smarty_tpl->getVariable('companyresources')->value['account_overage']);?>
元</strong></p>-->
					<!--</div>-->
					<p style="position: relative">
						<a href="/company/modify/" class="wh-money-list md_index_editdeail"><i style="background-position: 0 0;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/ziliao.png)"></i>企业资料</a>
						<a href="/commoncoupon/" target="_blank" class="md_index_moneymg"><i  style="background-position: -21px 0;"></i>资金管理</a>
<!--						<?php if ($_smarty_tpl->getVariable('CompanyAuditStatus')->value){?>-->
<!--						<?php if ($_smarty_tpl->getVariable('CompanyAuditStatus')->value=='认证通过'){?>-->
<!--						<a href="/licencevalidate" class="wh-money-list btnxcut1 md_index_com_verify"><i style="background-position: 0 -23px;"></i>企业认证</a>-->
<!--						<?php }else{ ?>-->
<!--						<a href="/licencevalidate" class="wh-money-list md_index_com_verify" style="color: #999;"><i style="background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/natural_icon_tit_02.png) no-repeat;background-position: 1px 0px;"></i>企业认证</a>-->
<!--						<?php }?>-->
<!--						<?php }?>-->
						<?php if ($_smarty_tpl->getVariable('show_light')->value==1){?>
						<a href="/environment/" target="_blank" class="wh-companyShow wh-companyShow-on md_index_com_intrest">
							<!--<font <?php if ('show_light'==0){?>color="gray" title='上传企业环境,产品项目介绍,高管团队,提升企业吸引力'<?php }?>>-->
							<i></i>企业风采
						</a>
						<?php }else{ ?>
						<a href="/environment/" target="_blank" class="wh-companyShow md_index_com_intrest">
							<!--<font <?php if ('show_light'==0){?>color="gray" title='上传企业环境,产品项目介绍,高管团队,提升企业吸引力'<?php }?>>-->
							<i></i>企业风采
						</a>
						<?php }?>
						<span class="completeTip" style="<?php if ($_smarty_tpl->getVariable('companyInfoPercent')->value<70||$_smarty_tpl->getVariable('show_light2')->value!=1){?>display: black;<?php }else{ ?>display: none;<?php }?>">
							<span class="showCompleteList">
								<span class="lineFirst">提升招聘效果：</span>
								<?php if ($_smarty_tpl->getVariable('companyInfoPercent')->value<70){?>
								<a href="<?php echo smarty_function_get_url(array('rule'=>'/company/modify/'),$_smarty_tpl);?>
">企业信息</a>
								<?php }?>
								<?php if ($_smarty_tpl->getVariable('companyInfoPercent')->value<70&&$_smarty_tpl->getVariable('show_light2')->value!=1){?>
								、
								<?php }?>
								<?php if ($_smarty_tpl->getVariable('show_light2')->value!=1){?>
								<a href="<?php echo smarty_function_get_url(array('rule'=>'/environment/index/'),$_smarty_tpl);?>
">企业风采</a>
								<?php }?>
							</span>
							<span class="closeTip"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/chacha.png" width="10px" height="10px"></span>
						</span>
					</p>
					<p style="display: none">
						<?php if ($_smarty_tpl->getVariable('CompanyAuditStatus')->value){?>
							<?php if ($_smarty_tpl->getVariable('CompanyAuditStatus')->value=='认证通过'){?>
							<a href="/licencevalidate" class="wh-money-list btnxcut1 md_index_com_verify"><i style="background-position: 0 -23px;"></i>企业认证</a>
							<?php }else{ ?>
							<a href="/licencevalidate" class="wh-money-list md_index_com_verify" style="color: #999;"><i style="background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/natural_icon_tit_02.png) no-repeat;background-position: 1px 0px;"></i>企业认证 <em class="fail">未认证</em></a>
							<?php }?>
						<?php }?>
						<?php if ($_smarty_tpl->getVariable('show_light')->value==1){?>
						<a href="/environment/" target="_blank" class="wh-companyShow wh-companyShow-on md_index_com_intrest">
							<!--<font <?php if ('show_light'==0){?>color="gray" title='上传企业环境,产品项目介绍,高管团队,提升企业吸引力'<?php }?>>-->
							<i></i>企业风采
						</a>
						<?php }else{ ?>
						<a href="/environment/" target="_blank" class="wh-companyShow md_index_com_intrest">
							<!--<font <?php if ('show_light'==0){?>color="gray" title='上传企业环境,产品项目介绍,高管团队,提升企业吸引力'<?php }?>>-->
							<i></i>企业风采<em class="fail">未完善</em>
						</a>
						<?php }?>

					</p>
					<?php if ($_smarty_tpl->getVariable('show_light')->value==0){?>
					<div class="marketPopx companyShow" style="font-size: 14px;color: #604e29;margin-left: 22px;">
						<i class="markeIcon"></i><div class="marketPop"> 上传企业环境、产品项目介绍、高管团队，提升企业吸引力</div>
					</div>
					<?php }?>
				</div>
			</div>
			<div class="xf-schedule">
				<div class="share-title">
					<a href="javascript:;" id="share_effect_list_btn" class="md_index_zpenjoy">
						<em id="share_effect_num"><?php if ($_smarty_tpl->getVariable('share_effect_num')->value){?><?php echo $_smarty_tpl->getVariable('share_effect_num')->value;?>
<?php }?></em><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_move2.jpg" style="margin-left:10px" />
					</a>
                    招聘分享
				</div>
				<!--<h2 class="scedTit">-->
					<!--<span>子账号<b style="font-size: 14px;">-可开通多账号，同时招聘</b></span>-->
					<!--<a href="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/account/accountmanage">管理<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_move2.jpg" style="margin-left:3px"></a>-->
				<!--</h2>-->
                <?php if ($_smarty_tpl->getVariable('recruit_type')->value==1){?>
				<!--<div class="toPic clearfix " id="<?php if (count($_smarty_tpl->getVariable('job_lists')->value)>0){?>sharePoster<?php }else{ ?>sharePosterNone<?php }?>">-->
					<!--<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_pic_01.png" alt="">-->
					<!--<div>-->
						<!--<h4>分享招聘海报到朋友圈</h4>-->
						<!--<p>让招聘信息被更多人看到</p>-->
					<!--</div>-->
					<!--<img class="toPic_icon" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_go_pic.png" alt="">-->
				<!--</div>-->
                <?php }?>
				<!--<h2 class="scedTit" style="margin-top: 10px;">-->
					<!--<span>微招聘<b style="font-size: 14px;">-社交分享招聘新模式</b></span>-->
					<!--<a href="<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/index/TemplateStatisticalByH5" class="btnx3 btnxcut3" id="weiBtn" target="_blank">分享<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_move2.jpg" style="margin-left:3px"></a>-->
				<!--</h2>-->
				<div class="share-other">
					<div id="share_img_poster"><a class="share md_index_mh5" >图片海报</a></div>
					<div id="share_h5"><a class="link md_index_mh5 md_index_mh5" href="javascript:void(0);">H5页面</a></div>
				</div>

			</div>
            <!--今日面试日程-->
			<div class="xf-schedule">
				<h2 class="scedTit"><span>今日面试日程<b>（共<?php echo count($_smarty_tpl->getVariable('no_audition_items')->value);?>
人）</b></span><a class="md_index_invit_detail" href="<?php echo smarty_function_get_url(array('rule'=>'/invite/'),$_smarty_tpl);?>
">详情 <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_move2.jpg" style="margin-left:3px"></a></h2>
				<!--暂无面试-->
                <?php if (empty($_smarty_tpl->getVariable('no_audition_items',null,true,false)->value)){?>
				<div class="schedule-line">
					<i></i>
					<span>今日暂无面试</span>
				</div>
                <?php }else{ ?>
				<ul class="scedList">
                    <?php  $_smarty_tpl->tpl_vars['invite'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('no_audition_items')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['invite']->key => $_smarty_tpl->tpl_vars['invite']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['invite']->key;
?>
                    <?php if ($_smarty_tpl->tpl_vars['k']->value<=2){?>
					<li><a class="md_index_invit_data" href="<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-resumeid-<?php echo $_smarty_tpl->tpl_vars['invite']->value['resume_id'];?>
-src-invite-invitid-<?php echo $_smarty_tpl->tpl_vars['invite']->value['invite_id'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['invite']->value['audition_time_day'];?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['invite']->value['audition_time_hour'];?>
&nbsp;&nbsp;<?php echo $_smarty_tpl->tpl_vars['invite']->value['user_name'];?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['invite']->value['station'];?>
</a></li>
                    <?php }?>
                    <?php }} ?>
				</ul>
                <?php }?>
				<h2 class="scedTit scedTit02"><span>已面试待答复<b>（共<?php echo count($_smarty_tpl->getVariable('has_audition_items')->value);?>
人）</b></span><a class="md_index_hadinvit_detail" href="<?php echo smarty_function_get_url(array('rule'=>'/invite/'),$_smarty_tpl);?>
?search_type=2">详情 <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_move2.jpg" style="margin-left:3px"></a></h2>
				<!--暂无面试-->
                <?php if (empty($_smarty_tpl->getVariable('has_audition_items',null,true,false)->value)){?>
				<div class="schedule-line">
					<i></i>
					<span>暂无待答复人员</span>
				</div>
                <?php }else{ ?>
				<ul class="scedList">
					<?php  $_smarty_tpl->tpl_vars['invite'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('has_audition_items')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['invite']->key => $_smarty_tpl->tpl_vars['invite']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['invite']->key;
?>
                    <?php if ($_smarty_tpl->tpl_vars['k']->value<=2){?>
					<li><a class="md_index_hadinvit_data" href="<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/"),$_smarty_tpl);?>
type-network-resumeid-<?php echo $_smarty_tpl->tpl_vars['invite']->value['resume_id'];?>
-src-invite-invitid-<?php echo $_smarty_tpl->tpl_vars['invite']->value['invite_id'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['invite']->value['audition_time_day'];?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['invite']->value['audition_time_hour'];?>
&nbsp;&nbsp;<?php echo $_smarty_tpl->tpl_vars['invite']->value['user_name'];?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['invite']->value['station'];?>
</a></li>
                    <?php }?>
                    <?php }} ?>
				</ul>
                <?php }?>
			</div>
			<!--推广<?php echo $_smarty_tpl->getVariable('company_level')->value;?>
金-->
			<div class="vipcon clearfix">
				<?php if ($_smarty_tpl->getVariable('memberinfo')->value=='member'&&$_smarty_tpl->getVariable('company_level')->value>1){?>
					<p class="vip_box_tit">
						<strong class="tit"><?php echo $_smarty_tpl->getVariable('hr_member')->value;?>
 <?php echo $_smarty_tpl->getVariable('companyresources')->value['companyLevel'];?>
</strong>
                        <?php if (!empty($_smarty_tpl->getVariable('companyinfo',null,true,false)->value["end_time"])){?>
						<span class="time"><?php echo date("Y.m.d",strtotime($_smarty_tpl->getVariable('companyinfo')->value['end_time']));?>

							<?php if (isset($_smarty_tpl->getVariable('overdue_day',null,true,false)->value)){?>
							<?php if ($_smarty_tpl->getVariable('overdue_day')->value<0){?>(<i class='red'>已过期</i>)<?php }else{ ?>(还有<i class="red"><?php echo $_smarty_tpl->getVariable('overdue_day')->value;?>
</i>天)<?php }?><?php }else{ ?>(剩<?php echo $_smarty_tpl->getVariable('last_lev_day')->value;?>
天)
							<?php }?>
						</span>
                                                <?php }?>
						<span id="ttp" class="dec">联系招聘顾问购买推广金，可享受更多优惠</span>
					</p>
				<?php }else{ ?>
					<p class="novip_box_tit">
					<strong>尚未开通会员</strong><a href="<?php echo smarty_function_get_url(array('rule'=>'/index/memberdetail/'),$_smarty_tpl);?>
" target="_blank">立即开通</a>
				</p>
				<?php }?>



                <div style="height: 60px;line-height: 60px;padding-left: 20px;background: #fafafa;margin-top: 10px;position: relative;bottom: -5px;">
					<?php if ($_smarty_tpl->getVariable('companyinfo')->value['com_level']==1&&$_smarty_tpl->getVariable('companyinfo')->value['site_type']==4){?>
                    	<span style="color: #2b6fad;margin-right: 10px;"><?php if ($_smarty_tpl->getVariable('job_count')->value>=5){?>0<?php }else{ ?><?php echo 5-$_smarty_tpl->getVariable('job_count')->value;?>
<?php }?>/5</span>本月可发布职位次数
					<?php }else{ ?>
						<span style="color: #2b6fad;margin-right: 10px;"><?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_relese_job_num'];?>
/<?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_job_num'];?>
</span>可发布职位
					<?php }?>

                </div>
				<ul class="vip_box clearfix">
					<!-- 推广金 -->
                    <?php if ($_smarty_tpl->getVariable('cur_is_main')->value){?>
                    <li class="on1">
                        <p class="center-vip">
                            <strong><a style="display:inline" class="money"><?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_release_point_sub_account'];?>
/<?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_point_sub_account'];?>
</a></strong>
                            <span>可添加直聘账号</span>

                        </p>
                    </li>
                    <?php }?>
                    <li class="not-margin">
                        <p><strong title=""><?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_resume_num_release'];?>
</strong><span>剩余简历点</span></p>
                    </li>

                    <li>
                        <p><strong><?php echo $_smarty_tpl->getVariable('companyresources')->value['spread_overage'];?>
</strong><span>剩余推广金</span></p>
                    </li>

                    <li class="not-margin">
                        <p><strong title=""><?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_release_point_job_refresh'];?>
</strong><span>剩余刷新点</span></p>
                    </li>

                    <li>
                        <p><strong><?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_release_point_message'];?>
</strong><span>剩余短信数</span></p>
                    </li>

                    <li class="not-margin">
                        <p><strong><?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_release_point_chat'];?>
</strong><span>剩余聊一聊次数</span></p>
                    </li>
					<li class="not-margin">
						<p><strong><?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_release_video_num_use_desc'];?>
</strong><span>剩余视频时长</span></p>
					</li>

					<!--&lt;!&ndash; 简历点数 &ndash;&gt;-->
					<!--<li class="not-margin">-->
						<!--<p><strong title="可用点数<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['over_resume_down_num'])===null||$tmp==='' ? 0 : $tmp);?>
，总共<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['ser_resume_num'])===null||$tmp==='' ? 0 : $tmp);?>
"><?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['over_resume_down_num'])===null||$tmp==='' ? 0 : $tmp);?>
/<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['ser_resume_num'])===null||$tmp==='' ? 0 : $tmp);?>
</strong><span>可用简历点数</span></p>-->
					<!--</li>-->
					<!--&lt;!&ndash; 刷新次数 &ndash;&gt;-->
					<!--<li>-->
						<!--<p><strong title="当天剩余<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_today_overplus'])===null||$tmp==='' ? 0 : $tmp);?>
，当天总共<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_perday'])===null||$tmp==='' ? 0 : $tmp);?>
"><?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_today_overplus'])===null||$tmp==='' ? 0 : $tmp);?>
/<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_perday'])===null||$tmp==='' ? 0 : $tmp);?>
</strong><span>可刷新次数</span></p>-->
					<!--</li>-->
					<!--&lt;!&ndash; 可用职位数 &ndash;&gt;-->
					<!--<li class="not-margin">-->
						<!--<p><strong  title="可用职位数<?php if (intval($_smarty_tpl->getVariable('companyresources')->value['default_job_num']-$_smarty_tpl->getVariable('companyresources')->value['has_pub_job_num'])<0){?>0<?php }else{ ?><?php echo intval($_smarty_tpl->getVariable('companyresources')->value['default_job_num']-$_smarty_tpl->getVariable('companyresources')->value['has_pub_job_num']);?>
<?php }?>，总共<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['default_job_num'])===null||$tmp==='' ? 0 : $tmp);?>
"><?php if (intval($_smarty_tpl->getVariable('companyresources')->value['default_job_num']-$_smarty_tpl->getVariable('companyresources')->value['has_pub_job_num'])<0){?>0<?php }else{ ?><?php echo intval($_smarty_tpl->getVariable('companyresources')->value['default_job_num']-$_smarty_tpl->getVariable('companyresources')->value['has_pub_job_num']);?>
<?php }?>/<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['default_job_num'])===null||$tmp==='' ? 0 : $tmp);?>
</strong><span>可用职位数</span></p>-->
					<!--</li>-->
					<!--&lt;!&ndash; 完整面试短信 &ndash;&gt;-->
					<!--<li>-->
						<!--<p><strong><?php if ($_smarty_tpl->getVariable('companyresources')->value['is_enabled_messagenotice']){?>已开启<?php }else{ ?><font style="color:#999">未开启</font><?php }?></strong><span>完整面试短信</span></p>-->
					<!--</li>-->
					<!--&lt;!&ndash; 智能推广 &ndash;&gt;-->
					<!--<li class="not-margin">-->
						<!--<p><strong><?php if ($_smarty_tpl->getVariable('companyresources')->value['is_enabled_intelligentrecommend']){?>已开启<?php }else{ ?><font style="color:#999">未开启</font><?php }?></strong><span>智能推广</span></p>-->
					<!--</li>-->

					




				</ul>
				<?php if ($_smarty_tpl->getVariable('account_type')->value=="NotMember"){?>
				<?php }else{ ?>
				<!--此种情况，智能推广、完整短信功能关闭-->
				<!--<ul class="vip_box clearfix">-->
					<!--&lt;!&ndash; 推广金 &ndash;&gt;-->
					<!--<li class="on1">-->
						<!--<p class="center-vip">-->
							<!--<strong><a style="display:inline" class="money" href="<?php echo smarty_function_get_url(array('rule'=>'/spread/'),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->getVariable('companyresources')->value['spread_overage'];?>
</a>元</strong>-->
							<!--<span>推广金</span>-->
							<!--<a class="wenti" href="javascript:void(0)">-->
								<!--<span class="pop"><u></u>推广金用于汇博的推广服务职位置顶、设置急聘职位、购买简历、超额刷新职位。</span>-->
							<!--</a>-->
						<!--</p>-->
					<!--</li>-->
					<!--&lt;!&ndash; 简历点数 &ndash;&gt;-->
					<!--<li class="not-margin">-->
						<!--<p><strong title="可用点数<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['over_resume_down_num'])===null||$tmp==='' ? 0 : $tmp);?>
，总共<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['ser_resume_num'])===null||$tmp==='' ? 0 : $tmp);?>
"><?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['over_resume_down_num'])===null||$tmp==='' ? 0 : $tmp);?>
/<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['ser_resume_num'])===null||$tmp==='' ? 0 : $tmp);?>
</strong><span>可用简历点数</span></p>-->
					<!--</li>-->
					<!--&lt;!&ndash; 刷新次数 &ndash;&gt;-->
					<!--<li>-->
						<!--<p><strong title="当天剩余<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_today_overplus'])===null||$tmp==='' ? 0 : $tmp);?>
，当天总共<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_perday'])===null||$tmp==='' ? 0 : $tmp);?>
"><?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_today_overplus'])===null||$tmp==='' ? 0 : $tmp);?>
/<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['refresh_perday'])===null||$tmp==='' ? 0 : $tmp);?>
</strong><span>可刷新次数</span></p>-->
					<!--</li>-->
					<!--&lt;!&ndash; 可用职位数 &ndash;&gt;-->
					<!--<li class="not-margin">-->
						<!--<p><strong  title="可用职位数<?php if (intval($_smarty_tpl->getVariable('companyresources')->value['default_job_num']-$_smarty_tpl->getVariable('companyresources')->value['has_pub_job_num'])<0){?>0<?php }else{ ?><?php echo intval($_smarty_tpl->getVariable('companyresources')->value['default_job_num']-$_smarty_tpl->getVariable('companyresources')->value['has_pub_job_num']);?>
<?php }?>，总共<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['default_job_num'])===null||$tmp==='' ? 0 : $tmp);?>
"><?php if (intval($_smarty_tpl->getVariable('companyresources')->value['default_job_num']-$_smarty_tpl->getVariable('companyresources')->value['has_pub_job_num'])<0){?>0<?php }else{ ?><?php echo intval($_smarty_tpl->getVariable('companyresources')->value['default_job_num']-$_smarty_tpl->getVariable('companyresources')->value['has_pub_job_num']);?>
<?php }?>/<?php echo (($tmp = @$_smarty_tpl->getVariable('companyresources')->value['default_job_num'])===null||$tmp==='' ? 0 : $tmp);?>
</strong><span>可用职位数</span></p>-->
					<!--</li>-->
					<!--&lt;!&ndash; 完整面试短信 &ndash;&gt;-->
					<!--<li>-->
						<!--<p><strong><font style="color:#999">未开启</font></strong><span>完整面试短信</span></p>-->
					<!--</li>-->
					<!--&lt;!&ndash; 智能推广 &ndash;&gt;-->
					<!--<li class="not-margin">-->
						<!--<p><strong><font style="color:#999">未开启</font></strong><span>智能推广</span></p>-->
					<!--</li>-->
					<!--<li class="on1">-->
						<!--<p class="center-vip">-->
							<!--<strong><a style="display:inline" class="money">3/5</a></strong>-->
							<!--<span>可添加直聘账号</span>-->

						<!--</p>-->
					<!--</li>-->

					<!--<li class="not-margin">-->
						<!--<p><strong title="">1000</strong><span>剩余简历点</span></p>-->
					<!--</li>-->

					<!--<li>-->
						<!--<p><strong>10000.00</strong><span>剩余推广金</span></p>-->
					<!--</li>-->

					<!--<li class="not-margin">-->
						<!--<p><strong title="">500</strong><span>剩余刷新点</span></p>-->
					<!--</li>-->

					<!--<li>-->
						<!--<p><strong>1000</strong><span>剩余短信数</span></p>-->
					<!--</li>-->

					<!--<li class="not-margin">-->
						<!--<p><strong>343</strong><span>剩余聊一聊次数</span></p>-->
					<!--</li>-->
				<!--</ul>-->
				<?php }?>

			</div>


			<!--招聘顾问-->
			<?php if ($_smarty_tpl->getVariable('memberinfo')->value!='member'){?>
				<?php if ($_smarty_tpl->getVariable('isEndMember')->value){?>
					<!--疑问联系顾问 -->
					<div class="firmConTopBg" style="padding-top: 0">
						<div class="firmRmendTit" style="width:auto">
							<span>您的会员服务已于<?php echo date("Y-m-d",strtotime($_smarty_tpl->getVariable('companyinfo')->value['end_time']));?>
到期，请联系招聘顾问续费开通会员服务</span>
						</div>
					</div>
					<?php if ($_smarty_tpl->getVariable('hasHRManager')->value){?>
						<ul class="fRmendConat" style="position: relative; min-height: 90px;">
						<?php if ($_smarty_tpl->getVariable('hasHRManager')->value){?>
						<li>
							<p class="name"><b><?php echo $_smarty_tpl->getVariable('hrManager')->value['user_name'];?>
</b><span>招聘顾问</span></p>
							<p class="mid">
								<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['tel'])){?>
								<span><?php if ($_smarty_tpl->getVariable('site_type')->value==5){?>028-62520468<?php }else{ ?><?php echo $_smarty_tpl->getVariable('tel_head')->value;?>
转<?php echo $_smarty_tpl->getVariable('hrManager')->value['tel'];?>
<?php }?></span>
								<?php }?>
								<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['mobile'])){?>
								<span><?php echo $_smarty_tpl->getVariable('hrManager')->value['mobile'];?>
(微信同号)</span>
								<?php }?>
								<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['qq'])){?>
								<span>QQ:<?php echo $_smarty_tpl->getVariable('hrManager')->value['qq'];?>
</span>
								<span><a style="padding-left:0px;" target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=<?php echo trim($_smarty_tpl->getVariable('hrManager')->value['qq']);?>
&site=qq&menu=yes">
									<img border="0"  width="79" height="25" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/qq_face.jpg" alt="点击这里给我发消息" title="点击这里给我发消息"/>
								</a></span>
								<?php }else{ ?>
								<script charset="utf-8" type="text/javascript" src="//wpa.b.qq.com/cgi/wpa.php?key=XzkzODA0MDQyNl8xMjgzMTBfNDAwODg3Mjg4N18"></script>
								<?php }?>
							</p>
						</li>
						<?php }?>
						<!--<img src="<?php echo $_smarty_tpl->getVariable('QRCodeImagePath')->value;?>
" style="width:90px; height: 90px; top: 10px; right: 17px; position: absolute;"/>-->
						<!--<i style="position: absolute;top: 100px;right: 27px;">微信服务号</i>-->
					</ul>
					<?php }else{ ?>
						<ul class="fRmendConat" style="position: relative; min-height: 90px;">
							<li>
								<p class="titWeight" style="padding-top:5px;">
									<span style="vertical-align:middle;">客服热线：<?php echo $_smarty_tpl->getVariable('huibo400')->value;?>
</span>
									<span style="padding-left:35px;">QQ : 2851501279</span>
								</p>
							</li>
							<li>
								<p class="titWeight">
									<a class="md_index_qq"  target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=2851501279&site=qq&menu=yes" style="padding-left:50px;">
										<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon06.png" width="94" height="24" />
									</a>
								</p>
							</li>
							<!--<img src="<?php echo $_smarty_tpl->getVariable('QRCodeImagePath')->value;?>
" style="width:90px; height: 90px; top: 10px; right: 17px; position: absolute;"/>-->
							<!--<i style="position: absolute;top: 100px;right: 27px;">微信服务号</i>-->
						</ul>
					<?php }?>
				<?php }else{ ?>
					<!--疑问联系顾问 -->
						<?php if ($_smarty_tpl->getVariable('hasHRManager')->value||$_smarty_tpl->getVariable('hasCustomer')->value){?>
						<ul class="fRmendConat" style="position: relative; min-height: 90px;">
							<?php if ($_smarty_tpl->getVariable('hasHRManager')->value){?>
							<li>
								<p class="name"><b><?php echo $_smarty_tpl->getVariable('hrManager')->value['user_name'];?>
</b><span>招聘顾问</span></p>
								<p class="mid">
									<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['tel'])){?>
									<span><?php if ($_smarty_tpl->getVariable('site_type')->value==5){?>028-62520468<?php }else{ ?><?php echo $_smarty_tpl->getVariable('tel_head')->value;?>
转<?php echo $_smarty_tpl->getVariable('hrManager')->value['tel'];?>
<?php }?></span>
									<?php }?>
									<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['mobile'])){?>
									<span><?php echo $_smarty_tpl->getVariable('hrManager')->value['mobile'];?>
(微信同号)</span>
									<?php }?>
									<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['qq'])){?>
									<span>QQ:<?php echo $_smarty_tpl->getVariable('hrManager')->value['qq'];?>
</span>
									<span><a class="md_index_qq" style="padding-left:0px;" target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=<?php echo trim($_smarty_tpl->getVariable('hrManager')->value["qq"]);?>
&site=qq&menu=yes">
										<img border="0"  width="79" height="25" src="//wpa.qq.com/pa?p=2:<?php echo trim($_smarty_tpl->getVariable('hrManager')->value["qq"]);?>
:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
										</a></span>
									<?php }else{ ?>
									<script charset="utf-8" type="text/javascript" src="//wpa.b.qq.com/cgi/wpa.php?key=XzkzODA0MDQyNl8xMjgzMTBfNDAwODg3Mjg4N18"></script>
									<?php }?>
								</p>
							</li>
							<?php }?>

							<?php if ($_smarty_tpl->getVariable('hasCustomer')->value){?>
							<li>
								<p><b><?php echo $_smarty_tpl->getVariable('customeruser')->value['user_name'];?>
</b><span style="color:#ccc">客服顾问 &nbsp;&nbsp;</span></p>
								<p class="mid">
									<?php if (!empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value['tel'])){?>
									<span <?php if (empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value['qq'])){?>style="vertical-align: 5px;"<?php }?>><?php echo $_smarty_tpl->getVariable('tel_head')->value;?>
转<?php echo $_smarty_tpl->getVariable('customeruser')->value['tel'];?>
</span>
									<?php }?>
									<?php if (!empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value['mobile'])){?>
									<span style="padding:0 40px;<?php if (empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value['qq'])){?>vertical-align: 5px;<?php }?>"><?php echo $_smarty_tpl->getVariable('customeruser')->value['mobile'];?>
(微信同号)</span>
									<?php }?>
									<?php if (!empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value['qq'])){?>
									<span>QQ:<?php echo $_smarty_tpl->getVariable('customeruser')->value['qq'];?>
</span>
									<span><a class="md_index_qq" style="padding-left:0px;" target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=<?php echo trim($_smarty_tpl->getVariable('customeruser')->value["qq"]);?>
&site=qq&menu=yes">
									<img border="0"  width="79" height="25" src="//wpa.qq.com/pa?p=2:<?php echo trim($_smarty_tpl->getVariable('customeruser')->value["qq"]);?>
:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
										</a></span>
									<?php }else{ ?>
									<script charset="utf-8" type="text/javascript" src="//wpa.b.qq.com/cgi/wpa.php?key=XzkzODA0MDQyNl8xMjgzMTBfNDAwODg3Mjg4N18"></script>
									<?php }?>
								</p>
							</li>
							<?php }?>
							<!--<img src="<?php echo $_smarty_tpl->getVariable('QRCodeImagePath')->value;?>
" style="width:90px; height: 90px; top: 10px; right: 17px; position: absolute;"/>-->
							<!--<i style="position: absolute;top: 100px;right: 27px;">微信服务号</i>-->
						</ul>
						<?php }else{ ?>
						<ul class="fRmendConat" style="position: relative; min-height: 90px;">
							<li>
								<p class="titWeight" style="padding-top:5px;">
									<span style="vertical-align:middle;">客服热线：<?php echo $_smarty_tpl->getVariable('huibo400')->value;?>
</span>
									<span style="padding-left:35px;">QQ : 2851501279</span>
								</p>
							</li>
							<li>
								<p class="titWeight">
									<a class="md_index_qq"  target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=2851501279&site=qq&menu=yes" style="padding-left:50px;">
										<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon06.png" width="94" height="24" />
									</a>
								</p>
							</li>
							<!--<img src="<?php echo $_smarty_tpl->getVariable('QRCodeImagePath')->value;?>
" style="width:90px; height: 90px; top: 10px; right: 17px; position: absolute;"/>-->
							<!--<i style="position: absolute;top: 100px;right: 27px;">微信服务号</i>-->
						</ul>
						<?php }?>
				<?php }?>
			<?php }else{ ?>
			<!--疑问联系顾问 -->
				<?php if ($_smarty_tpl->getVariable('hasHRManager')->value||$_smarty_tpl->getVariable('hasCustomer')->value){?>
				<ul class="fRmendConat" style="position: relative; min-height: 90px;">
					<?php if ($_smarty_tpl->getVariable('hasHRManager')->value){?>
					<li>
						<p class="name"><b><?php echo $_smarty_tpl->getVariable('hrManager')->value['user_name'];?>
</b><span>招聘顾问</span></p>
						<p class="mid">
							<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['tel'])){?>
							<span><?php if ($_smarty_tpl->getVariable('site_type')->value==5){?>028-62520468<?php }else{ ?><?php echo $_smarty_tpl->getVariable('tel_head')->value;?>
转<?php echo $_smarty_tpl->getVariable('hrManager')->value['tel'];?>
<?php }?></span>
							<?php }?>

							<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['mobile'])){?>
							<span><?php echo $_smarty_tpl->getVariable('hrManager')->value['mobile'];?>
(微信同号)</span>
							<?php }?>

							<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value['qq'])){?>
							<span>QQ:<?php echo $_smarty_tpl->getVariable('hrManager')->value['qq'];?>
</span>
							<span><a class="md_index_qq" style="padding-left:0px;" target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=<?php echo trim($_smarty_tpl->getVariable('hrManager')->value["qq"]);?>
&site=qq&menu=yes">
								<img border="0"  width="79" height="25" src="//wpa.qq.com/pa?p=2:<?php echo trim($_smarty_tpl->getVariable('hrManager')->value["qq"]);?>
:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
								</a></span>
							<?php }else{ ?>
							<script charset="utf-8" type="text/javascript" src="//wpa.b.qq.com/cgi/wpa.php?key=XzkzODA0MDQyNl8xMjgzMTBfNDAwODg3Mjg4N18"></script>
							<?php }?>
						</p>
					</li>
					<?php }?>

					<?php if ($_smarty_tpl->getVariable('hasCustomer')->value){?>
					<li>
						<p><b><?php echo $_smarty_tpl->getVariable('customeruser')->value['user_name'];?>
</b><span style="color:#ccc">客服顾问 &nbsp;&nbsp;</span></p>
						<p class="mid">
							<?php if (!empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value['tel'])){?>
							<span <?php if (empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value['qq'])){?>style="vertical-align: 5px;"<?php }?>><?php echo $_smarty_tpl->getVariable('tel_head')->value;?>
转<?php echo $_smarty_tpl->getVariable('customeruser')->value['tel'];?>
</span>
							<?php }?>

							<?php if (!empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value['mobile'])){?>
							<span style="padding:0 40px;<?php if (empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value['qq'])){?>vertical-align: 5px;<?php }?>"><?php echo $_smarty_tpl->getVariable('customeruser')->value['mobile'];?>
(微信同号)</span>
							<?php }?>

							<?php if (!empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value['qq'])){?>
							<span>QQ:<?php echo $_smarty_tpl->getVariable('customeruser')->value['qq'];?>
</span>
							<span><a class="md_index_qq" style="padding-left:0px;" target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=<?php echo trim($_smarty_tpl->getVariable('customeruser')->value["qq"]);?>
&site=qq&menu=yes">
							<img border="0"  width="79" height="25" src="//wpa.qq.com/pa?p=2:<?php echo trim($_smarty_tpl->getVariable('customeruser')->value["qq"]);?>
:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
								</a></span>
							<?php }else{ ?>
							<script charset="utf-8" type="text/javascript" src="//wpa.b.qq.com/cgi/wpa.php?key=XzkzODA0MDQyNl8xMjgzMTBfNDAwODg3Mjg4N18"></script>
							<?php }?>
						</p>
					</li>
					<?php }?>
					<!--<img src="<?php echo $_smarty_tpl->getVariable('QRCodeImagePath')->value;?>
" style="width:90px; height: 90px; top: 10px; right: 17px; position: absolute;"/>-->
					<!--<i style="position: absolute;top: 100px;right: 27px;">微信服务号</i>-->
				</ul>
				<?php }else{ ?>
				<ul class="fRmendConat" style="position: relative; min-height: 90px;">
					<li>
						<p class="titWeight" style="padding-top:5px;">
							<span style="vertical-align:middle;">客服热线：<?php echo $_smarty_tpl->getVariable('huibo400')->value;?>
</span>
							<span style="padding-left:30px;">QQ:2851501279</span>
						</p>
					</li>
					<li>
						<p class="titWeight">
							<a class="md_index_qq" target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=2851501279&site=qq&menu=yes" style="padding-left:30px;">
								<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon06.png" width="94" height="24" />
							</a>
						</p>
					</li>
					<!--<img src="<?php echo $_smarty_tpl->getVariable('QRCodeImagePath')->value;?>
" style="width:90px; height: 90px; top: 10px; right: 17px; position: absolute;"/>-->
					<!--<i style="position: absolute;top: 100px;right: 27px;">微信服务号</i>-->
				</ul>
				<?php }?>

			<?php }?>


			<!--面试评价模块 由求职者留言模板改过来的-->
			<!--<div class="firmEce">-->
                                <!--<?php if (!$_smarty_tpl->getVariable('is_allow_appraise')->value){?>-->
                                    <!--<p class="appealSwitchNotice" style="background-color: #fffadd;border: 1px solid #f6e5a6;margin-bottom:20px;padding:10px;line-height:24px">您的面试评价未公开，可能会影响职位的展示量和简历投递量（<a href="javascript:;" id="appealSwitch" style="color:#00bab1">公开</a>）</p>-->
                                <!--<?php }?>-->
				<!--<p class="firmEcetit"><span>面试评价</span><a target="_blank" href="/appraise/"><?php echo $_smarty_tpl->getVariable('appraise_wait_deal')->value;?>
条（近一月）</a></p>-->
				<!--<ul>-->
					<!--<?php if (count($_smarty_tpl->getVariable('appraise_list')->value)>0){?>-->
					<!--<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('appraise_list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
?>-->
					<!--<li>-->
						<!--<a href="/appraise/" target="_blank">-->
							<!--<?php if (!empty($_smarty_tpl->tpl_vars['value']->value['person_small_photo'])){?>-->
							<!--<img src="<?php echo $_smarty_tpl->tpl_vars['value']->value['person_small_photo'];?>
" class="newLeaveImg" width="50" height="50" />-->
							<!--<?php }else{ ?>-->
							<!--<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/appeal/mobile/default_head.jpg" class="newLeaveImg" width="50" height="50" />-->
							<!--<?php }?>-->
							<!--<p>-->
								<!--<span style="color:#333">-->
									<!--<?php echo $_smarty_tpl->tpl_vars['value']->value['person_user_name'];?>
-->
								<!--</span>-->
								<!--<span style="padding-top:3px;">-->
									<!--<?php if ($_smarty_tpl->tpl_vars['value']->value["complain_status"]==1||$_smarty_tpl->tpl_vars['value']->value['check_state']==2){?>-->
							 			<!--<em style="color: #999">（该评论审核未通过，已删除）</em>-->
									<!--<?php }else{ ?>-->
									<!--<?php echo base_lib_BaseUtils::cutstr($_smarty_tpl->tpl_vars['value']->value['content'],40,'utf-8','','…');?>
-->
									<!--<?php }?>-->
								<!--</span>-->
							<!--</p>-->
                            <!--<span class="rt_date"><?php echo $_smarty_tpl->tpl_vars['value']->value['appraise_time'];?>
</span>-->
						<!--</a>-->
					<!--</li>-->
					<!--<?php }} ?>-->
					<!--<?php }else{ ?>-->
					<!--<li><span style="color: #999; font-size: 14px;">暂无面试评价哦~</span></li>-->
					<!--<?php }?>-->
				<!--</ul>-->
				<!--<p align="right"><a target="_blank" href="/appraise/" class="more-msg">查看更多面试评价<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_icon_v2_move2.jpg" style="margin-left:3px" /></a></p>-->
			<!--</div>-->
			<div class="clear"></div>

			<!--<style>-->
				<!--.diamond-2019-con {border-top: 1px solid #f1f1f1;padding: 30px 20px;}-->
				<!--.diamond-2019-con .head {position: relative;padding-bottom: 20px;}-->
				<!--.diamond-2019-con .head .name {-->
					<!--font-size: 16px;-->
					<!--line-height: 1;-->
					<!--display: block;-->
					<!--margin-bottom: 5px;-->
				<!--}-->
				<!--.diamond-2019-con .head .txt {color: #666;}-->
				<!--.diamond-2019-con .head .date {position: absolute;top: 0;right: 0;font-size: 12px;color: #999;}-->
				<!--.diamond-2019-con .body {-->

				<!--}-->
				<!--.diamond-2019-con .body .job-num {box-sizing: border-box;padding: 20px;background: #fafafa;margin-bottom: 4px;}-->
				<!--.diamond-2019-con .body .job-num .num {-->
					<!--line-height: 1;-->
					<!--margin-bottom: 8px;-->
				<!--}-->
				<!--.diamond-2019-con .body .job-num .num a {font-size: 16px;margin-right: 5px;}-->
				<!--.diamond-2019-con .body .job-num .txt {color: #999;font-size: 12px;line-height: 1;}-->
				<!--.diamond-2019-con .body .item-con {overflow: hidden;}-->
				<!--.diamond-2019-con .body .item-con .item {float: left;width: 50%;box-sizing: border-box;margin-bottom: 4px;}-->
				<!--.diamond-2019-con .body .item-con  .l {-->
					<!--padding-right: 4px;-->
				<!--}-->
				<!--.diamond-2019-con .body .item-con .item .inner {background: #fafafa;padding: 12px 20px;}-->
				<!--.diamond-2019-con .body .item-con .item .inner .num {font-size: 16px;line-height: 1;display: inline-block;margin-bottom: 8px;}-->
				<!--.diamond-2019-con .body .item-con .item .inner .item-name {line-height: 1;}-->
				<!--.diamond-2019-con .contact-con {-->

				<!--}-->
				<!--.diamond-2019-con .contact-con .name {font-size: 16px;}-->
				<!--.diamond-2019-con .contact-con .name span {font-size: 14px;color: #999;margin-left: 8px;}-->
				<!--.diamond-2019-con .contact-con .contact {-->
					<!--margin-bottom: 2px;-->
				<!--}-->
				<!--.diamond-2019-con .contact-con .tel {margin-bottom: 2px;}-->
				<!--.diamond-2019-con .contact-con .qq {margin-bottom: 10px;}-->
				<!--.diamond-2019-con .contact-con .chat {display: inline-block;}-->
				<!--.diamond-2019-con .contact-con .chat a {-->

				<!--}-->
				<!--.diamond-2019-con .contact-con .chat a img {-->
					<!--height: 22px;-->
					<!--width: 78px;-->
				<!--}-->
			<!--</style>-->
			<!--<div class="diamond-2019-con">-->
				<!--<div class="head">-->
					<!--<a class="name">钻石套餐2019-简历版</a>-->
					<!--<p class="txt">联系招聘顾问购买，获得更多折扣</p>-->
					<!--<div class="date">2019.11.12到期</div>-->
				<!--</div>-->
				<!--<div class="body">-->
					<!--<div class="job-num">-->
						<!--<div class="num"><a>195</a>可发布职位</div>-->
						<!--<div class="txt">(免费职位：5/10&nbsp;&nbsp;精品职位：190/200)</div>-->
					<!--</div>-->
					<!--<div class="item-con">-->
						<!--<div class="item l">-->
							<!--<div class="inner">-->
								<!--<a class="num">800</a>-->
								<!--<div class="item-name">剩余精品点</div>-->
							<!--</div>-->
						<!--</div>-->
						<!--<div class="item">-->
							<!--<div class="inner">-->
								<!--<a class="num">2000</a>-->
								<!--<div class="item-name">剩余刷新点</div>-->
							<!--</div>-->
						<!--</div>-->
						<!--<div class="item l">-->
							<!--<div class="inner">-->
								<!--<a class="num">1000</a>-->
								<!--<div class="item-name">剩余简历点</div>-->
							<!--</div>-->
						<!--</div>-->
						<!--<div class="item">-->
							<!--<div class="inner">-->
								<!--<a class="num">3888.00元</a>-->
								<!--<div class="item-name">剩余推广金</div>-->
							<!--</div>-->

						<!--</div>-->
					<!--</div>-->

					<!--<div class="contact-con">-->
						<!--<div class="name">-->
							<!--隆丽梅<span>招聘顾问</span>-->
						<!--</div>-->
						<!--<div class="contact">400-1010-970转7025</div>-->
						<!--<div class="tel">17823487535(微信同号)</div>-->
						<!--<div class="qq">QQ:3004696292</div>-->
						<!--<a class="chat"><img></a>-->
					<!--</div>-->
				<!--</div>-->
			<!--</div>-->
		</div>
		<div class="clear"></div>
		<style>
			.joinqqGroup{top:315px}
		</style>
			<div style="position:absolute; margin-right: 510px; right: 50%; top: 136px;font-size: 20px;text-align: center;">
				<a target="_blank" adv_id="<?php echo $_smarty_tpl->getVariable('v')->value['adv_id'];?>
" area="calling_advert" class="calling_advert calling_advert02">
					<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/yiqingwenjuan.jpg" style="width: 160px;height: auto;padding: 0;">
					
				</a>
			</div>
        <div id="calling_advert" style="position:absolute; margin-left: 510px; left: 50%; top: 136px;">
			<a target="_blank" adv_id="<?php echo $_smarty_tpl->getVariable('v')->value['adv_id'];?>
" area="calling_advert" class="calling_advert"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_code.jpg" width="100" /></a>
			<?php if (!empty($_smarty_tpl->getVariable('calling_advert',null,true,false)->value)){?>
			<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('calling_advert')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
			<a href="http://<?php echo $_smarty_tpl->tpl_vars['v']->value['url'];?>
" target="_blank" adv_id="<?php echo $_smarty_tpl->tpl_vars['v']->value['adv_id'];?>
" area="calling_advert" class="calling_advert"><img src="<?php echo $_smarty_tpl->tpl_vars['v']->value['img_url'];?>
" width="140" height="90" /></a>
			<?php }} ?>
			<?php }?>

			<?php if ($_smarty_tpl->getVariable('promotion')->value['is_effect']==1){?>
				<?php if (in_array($_smarty_tpl->getVariable('area_id')->value,$_smarty_tpl->getVariable('promotion')->value['area_ids'])||in_array($_smarty_tpl->getVariable('calling_id')->value,$_smarty_tpl->getVariable('promotion')->value['calling_ids'])){?>
				<a href="#" class="promotion"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/panyMoneyNew.png" width="100" height="66" /></a>
				<?php }?>
			<?php }?>
			
		</div>

		<!--<a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_r0311.jpg" /></a>-->
		<!--<a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_r0330.jpg" /></a>-->
	</div>

	<style>
		.showPromotionMain{ width:100%; height:100%; background:#000; position:fixed; z-index:4; display: none; left:0px; top:0px; opacity:0.2; filter:alpha(opacity=20); -moz-opacity:0.2;}
		.showPromotionMain1{ width:100%; height:100%; background:#000; position:fixed; z-index:4; display: block; left:0px; top:0px; opacity:0.2;}
		.pop-prom-bg{width:427px; overflow:hidden; position:fixed; top:26%; left:50%; margin:0 0 0 -213px; z-index:5; font-family:"微软雅黑";}
		.promImg{ display:block;width:427px; height:192px; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/promImg2.png) no-repeat;}
		.pop-prom-top{width:100%; position:relative; overflow:hidden;}
		.radiusProm{ display:block;width:40px; height:40px; position:absolute; top:0px; right:0px;}
		.moneyProm{ display:block;width:100%; text-align:center; color:#fff; font-size:54px;position:absolute; text-indent:-20px; top:20%; left:0px;}
		.moneyProm em{ font-size:24px;}
		.pop-prom-bm{width:384px; text-align:left; padding:15px; background:#fff; overflow:hidden;}
		.prom-bm01,.prom-bm03{ display:block; color:#666; font-size:14px; line-height:24px;}
		.prom-bm02,.prom-bm04{ display:block; color:#666; font-size:14px; line-height:24px; text-indent:2em;}
		.prom-bm02 em,.prom-bm04 em{ color:#ffa200; font-size:14px;}
		.prom-btn{ display:block;width:384px; height:45px; background:#2ccc9b; margin-top:20px; border-radius:4px; line-height:45px; text-align:center; color:#fff; font-size:15px;}
		.prom-bm03,.prom-bm04,.prom-bm05,.prom-bm06{ display:block;}
		.prom-bm03{ font-size:13px;}
		.prom-bm04 em{ font-weight:bold; color:#666;}
		.prom-bm05{ text-indent:0px; padding:6px 0 3px 0; font-size:12px;}
		.prom-bm06{ overflow:hidden; padding-bottom:5px; font-size: 12px;}
		.prom-bm06 em{  font-size:14px;}
		.prom-bm06 em b{ color:#ffa200; font-size:14px;}
		.prom-bm05 a{ display:inline-block; font-size:12px; padding-left: 10px; color:#25c99e; text-decoration:underline; cursor: pointer;}
		.prom-bm07{ padding:5px 8px; background:#eafff8; border:1px dashed #2ccc9b; margin:6px 0;}
		.prom-bm07 b{ display:block; color:#2ccc9b; line-height:20px; padding-bottom:3px; font-size:14px;}
		.prom-bm07 span{ display:block; font-size:14px; color:#666; line-height:20px;}
		.prom-bm08 a{ display:inline-block; float:right;}
	</style>
	<div class="showPromotionMain">

	</div>


	<?php if ($_smarty_tpl->getVariable('promotion')->value['is_effect']==1&&empty($_smarty_tpl->getVariable('ShowEveryDay',null,true,false)->value)&&$_smarty_tpl->getVariable('companyinfo')->value['is_audit']=='1'){?>
	<?php if ((in_array($_smarty_tpl->getVariable('area_id')->value,$_smarty_tpl->getVariable('promotion')->value['area_ids'])||in_array($_smarty_tpl->getVariable('calling_id')->value,$_smarty_tpl->getVariable('promotion')->value['calling_ids']))){?>
	<div class="showPromotionMain" style="display: block;">

	</div>
	<div class="pop-prom-bg" style=" display:block; z-index: 10002" id="showPromotionContent3">
		<div class="pop-prom-top">
			<a href="#" class="radiusProm"></a>
			<span class="moneyProm" style="font-size:62px; top:18%;">免费领</span>
			<div class="promImg"></div>
		</div>
		<div class="pop-prom-bm">
			<?php if (in_array($_smarty_tpl->getVariable('calling_id')->value,$_smarty_tpl->getVariable('promotion')->value['calling_ids'])){?>
			<span class="prom-bm04 prom-bm05" style="padding-top:0px;">邀请您推荐更多<em><?php echo $_smarty_tpl->getVariable('company_Calling')->value;?>
</em>企业注册汇博人才网，每推荐一个企业注册成功（通过营业执照审核），奖励您<em style=" color:#ffa200;"><?php echo $_smarty_tpl->getVariable('promotion')->value['share_price'];?>
元</em>推广金！</span>
			<?php }elseif(in_array($_smarty_tpl->getVariable('area_id')->value,$_smarty_tpl->getVariable('promotion')->value['area_ids'])){?>
			<span class="prom-bm04 prom-bm05" style="padding-top:0px;">邀请您推荐更多<em><?php echo $_smarty_tpl->getVariable('company_Areaname')->value;?>
</em>企业注册汇博人才网，每推荐一个企业注册成功（通过营业执照审核），奖励您<em style=" color:#ffa200;"><?php echo $_smarty_tpl->getVariable('promotion')->value['share_price'];?>
元</em>推广金！</span>
			<?php }?>

			<div class="prom-bm07">
				<b>领奖条件：</b>
        <span>1. 推荐的企业通过您的分享链接或邀请码注册
<br />2. 推荐的企业注册并上传营业执照，通过审核</span>
			</div>
			<span class="prom-bm06" style="padding-top:8px;">您的邀请码是：<em><b style="color:#666;"><?php echo $_smarty_tpl->getVariable('promotion_code_link')->value['code'];?>
</b></em>
				<!--<a onclick="copyToClipboard('<?php echo $_smarty_tpl->getVariable('promotion_code')->value;?>
');" id="code123">复制邀请码</a>-->
			</span>
    		<span class="prom-bm06 prom-bm08">您的推广链接：<?php echo $_smarty_tpl->getVariable('promotion_code_link')->value['shortLink'];?>

				<!--<a onclick="copyToClipboard('<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/company/register?flagkey=<?php echo $_smarty_tpl->getVariable('company_flag')->value;?>
');" class="copy">复制链接</a>-->
			</span>
			<script type="text/javascript">
				function thisSetCookie(name, value) { //设置cookie
					var expire = new Date();
					expire.setTime(<?php echo $_smarty_tpl->getVariable('tomorrow')->value;?>
);
					var path ="";
					expire = '; expires=' + expire.toGMTString();
					document.cookie = name + '=' + escape(value) + expire+path;
				}
				window.onload(thisSetCookie('isShowEveryDay','true'));
			</script>
		</div>
	</div>
	<?php }?>
	<?php }?>

	<div class="pop-prom-bg" style=" display:none; z-index: 10002" id="showPromotionContent">
		<div class="pop-prom-top">
			<a href="#" class="radiusProm"></a>
			<span class="moneyProm" style="font-size:62px; top:18%;">免费领</span>
			<div class="promImg"></div>
		</div>
		<div class="pop-prom-bm">
			<?php if (in_array($_smarty_tpl->getVariable('calling_id')->value,$_smarty_tpl->getVariable('promotion')->value['calling_ids'])){?>
			<span class="prom-bm04 prom-bm05" style="padding-top:0px;">邀请您推荐更多<em><?php echo $_smarty_tpl->getVariable('company_Calling')->value;?>
</em>企业注册汇博人才网，每推荐一个企业注册成功（通过营业执照审核），奖励您<em style=" color:#ffa200;"><?php echo $_smarty_tpl->getVariable('promotion')->value['share_price'];?>
元</em>推广金！</span>
			<?php }elseif(in_array($_smarty_tpl->getVariable('area_id')->value,$_smarty_tpl->getVariable('promotion')->value['area_ids'])){?>
			<span class="prom-bm04 prom-bm05" style="padding-top:0px;">邀请您推荐更多<em><?php echo $_smarty_tpl->getVariable('company_Areaname')->value;?>
</em>企业注册汇博人才网，每推荐一个企业注册成功（通过营业执照审核），奖励您<em style=" color:#ffa200;"><?php echo $_smarty_tpl->getVariable('promotion')->value['share_price'];?>
元</em>推广金！</span>
			<?php }?>

			<div class="prom-bm07">
				<b>领奖条件：</b>
        <span>1. 推荐的企业通过您的分享链接或邀请码注册
<br />2. 推荐的企业注册并上传营业执照，通过审核</span>
			</div>
			<span class="prom-bm06" style="padding-top:8px;">您的邀请码是：<em><b style="color:#666;"><?php echo $_smarty_tpl->getVariable('promotion_code_link')->value['code'];?>
</b></em>
				<!--<a onclick="copyToClipboard('<?php echo $_smarty_tpl->getVariable('promotion_code')->value;?>
');" id="code123">复制邀请码</a>-->
			</span>
    		<span class="prom-bm06 prom-bm08">您的推广链接：<?php echo $_smarty_tpl->getVariable('promotion_code_link')->value['shortLink'];?>

				<!--<a onclick="copyToClipboard('<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/company/register?flagkey=<?php echo $_smarty_tpl->getVariable('company_flag')->value;?>
');" class="copy">复制链接</a>-->
			</span>
			<!--<a href="#" class="prom-btn">推荐给朋友</a>-->
		</div>
	</div>
	<?php if (!empty($_smarty_tpl->getVariable('prolist',null,true,false)->value['promotionlist'])){?>
	<div class="showPromotionMain" style="display: block;">

	</div>
	<div class="pop-prom-bg" style="display:block; z-index: 10001;" id="showPromotionContent1">
		<div class="pop-prom-top">
			<a href="#" class="radiusProm"></a>
			<span class="moneyProm"><?php echo $_smarty_tpl->getVariable('prolist')->value['promotion_price'];?>
<em>元</em></span>
			<div class="promImg"></div>
		</div>
		<div class="pop-prom-bm">
			<span class="prom-bm03">感谢您的推荐：</span>
			<span class="prom-bm04"><em><?php echo $_smarty_tpl->getVariable('prolist')->value['promotionlist'];?>
</em>，营业执照审核通过。推广金已存入您的账户！</span>
			<span class="prom-bm04 prom-bm05">推荐企业注册，审核成功，可再获<?php echo $_smarty_tpl->getVariable('prolist')->value['share_price'];?>
元推广金。<a class="promotion">查看详情>></a></span>

		</div>
	</div>
	<?php }?>
	<!--<?php if (!empty($_smarty_tpl->getVariable('prolist',null,true,false)->value['reglist'])){?>-->
	<!--<div class="showPromotionMain" style="display: block;">-->

	<!--</div>-->
	<!--<div class="pop-prom-bg" style="display:block;" id="showPromotionContent2">-->
		<!--<div class="pop-prom-top">-->
			<!--<a href="#" class="radiusProm"></a>-->
			<!--<span class="moneyProm"><?php echo $_smarty_tpl->getVariable('prolist')->value['price'];?>
<em>元</em></span>-->
			<!--<div class="promImg"></div>-->
		<!--</div>-->
		<!--<div class="pop-prom-bm">-->
			<!--<span class="prom-bm01">感谢您注册汇博网：</span>-->
			<!--<span class="prom-bm02">您获得了<em><?php echo $_smarty_tpl->getVariable('prolist')->value['registered_price'];?>
元</em>推广金，营业执照审核通过以后，将存入您的账户！</span>-->
			<!--<span class="prom-bm04" style="text-indent: 0px;"><b style="color:#ffa200">推广金</b>可用于下载简历</span>-->
			<!--<span class="prom-bm04 prom-bm05">推荐企业注册，审核成功，可再获<?php echo $_smarty_tpl->getVariable('prolist')->value['share_price'];?>
元推广金。</span>-->
			<!--<span class="prom-bm06"><a href="<?php echo smarty_function_get_url(array('rule'=>'/spread/index'),$_smarty_tpl);?>
">查看详情>></a></span>-->
		<!--</div>-->
	<!--</div>-->
	<!--<?php }?>-->

	<?php if ($_smarty_tpl->getVariable('promotion')->value['is_effect']==1&&empty($_smarty_tpl->getVariable('ShowPromotion',null,true,false)->value)){?>
	<?php if ((in_array($_smarty_tpl->getVariable('area_id')->value,$_smarty_tpl->getVariable('promotion')->value['area_ids'])||in_array($_smarty_tpl->getVariable('calling_id')->value,$_smarty_tpl->getVariable('promotion')->value['calling_ids']))&&in_array($_smarty_tpl->getVariable('is_audit')->value,array(0,2))){?>
	<div class="showPromotionMain" style="display: block;">

	</div>
	<div class="pop-prom-bg" style="display:block; z-index: 10000" id="showPromotionContent2">
		<div class="pop-prom-top">
			<a href="#" class="radiusProm"></a>
			<span class="moneyProm"><?php echo $_smarty_tpl->getVariable('prolist')->value['registered_price'];?>
<em>元</em></span>
			<div class="promImg"></div>
		</div>
		<div class="pop-prom-bm">
			<span class="prom-bm01">感谢您注册汇博网：</span>
			<span class="prom-bm02">您获得了<em><?php echo $_smarty_tpl->getVariable('prolist')->value['registered_price'];?>
元</em>推广金，营业执照审核通过以后，将存入您的账户！</span>

			<?php if ($_smarty_tpl->getVariable('is_audit')->value==0){?>
			<a href="javascript:isPromotionShowLQ();" class="prom-btn">立即领取</a>
			<?php }else{ ?>
			<span class="prom-bm04 prom-bm05">推荐企业注册，审核成功，可再获<?php echo $_smarty_tpl->getVariable('prolist')->value['share_price'];?>
元推广金。<a class="promotion">查看详情>></a></span>
			<span class="prom-bm06 prom-bm08" style="font-size:12px; padding-top:5px;">请复制推荐链接，分享给朋友：<?php echo $_smarty_tpl->getVariable('promotion_code_link')->value['shortLink'];?>
</span>
			<script type="text/javascript">
				window.onload(cookieutility.set('isShowPromotionShowFX','true'));
			</script>
			<?php }?>
		</div>
	</div>
	<?php }?>
	<?php }?>
	
	<?php $_template = new Smarty_Internal_Template("msgpop.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
	<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
	<section class="floatRT">
		<a href="<?php echo smarty_function_get_url(array('rule'=>'/about/message','domain'=>'main'),$_smarty_tpl);?>
" target="_blank" class="serviceLink">我有问题要反馈</a><b></b>
	</section>
	<!-- 企业招聘首页 -->
	<?php if (count($_smarty_tpl->getVariable('qq_group_arr')->value)>0){?>
	<div class="joinqqGroup" style="display: none">
		<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/joinqq.jpg" width="140" height="141" />
		<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('qq_group_arr')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
		<p>
			<b><?php echo $_smarty_tpl->tpl_vars['v']->value['qq_group'];?>
</b>
			<span><?php echo $_smarty_tpl->tpl_vars['v']->value['group_name'];?>
</span>
		</p>
		<?php }} ?>
	</div>
	<?php }?>


	<div class="md_index_resume_search" data-expain="md use,never delete it!" style="display:none"></div>
	<div class="md_index_muchrefresh" data-expain="md use,never delete it!" style="display:none"></div>
	<script  type="text/javascript">
        var action_url = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
';
        if(typeof action_dom == 'object'){}else{
            action_dom = [];
        }
        action_dom.push( ['.share-link', 40]);
        action_dom.push( ['#share_img_poster', 39]);
        action_dom.push( ['.share-h5', 48]);
        action_dom.push( ['#share_h5', 47]);

        action_dom.push( ['.md_index_banner', 120]);
        action_dom.push( ['.md_index_resume_search', 121]);
        action_dom.push( ['.md_index_account_select', 122]);
        action_dom.push( ['.md_index_pubjob', 123]);
        action_dom.push( ['.md_index_timerefresh', 124]);
        action_dom.push( ['.md_index_muchrefresh', 125]);
        action_dom.push( ['.md_index_close', 126]);
        action_dom.push( ['.md_index_refresh', 127]);
        action_dom.push( ['.md_index_delay', 128]);
        action_dom.push( ['.md_index_upremmon', 129]);

        action_dom.push( ['.md_index_remmonperson_more', 130]);
        action_dom.push( ['.md_index_remmonperson_list', 131]);
        action_dom.push( ['.md_index_fire_list', 132]);
        action_dom.push( ['.md_index_fire_dz', 133]);
        action_dom.push( ['.md_index_selfdetail', 134]);
        action_dom.push( ['.md_index_unbindwx', 135]);
        action_dom.push( ['.md_index_unbindapp', 136]);
        action_dom.push( ['.md_index_selfnameclick', 137]);
        action_dom.push( ['.md_index_czmoney', 138]);
        action_dom.push( ['.md_index_editdeail', 139]);
        action_dom.push( ['.md_index_moneymg', 140]);
        action_dom.push( ['.md_index_com_verify', 141]);
        action_dom.push( ['.md_index_com_intrest', 142]);
        action_dom.push( ['.md_index_zpenjoy', 143]);
        action_dom.push( ['.md_index_mh5', 144]);

        action_dom.push( ['.md_index_invit_detail', 145]);
        action_dom.push( ['.md_index_invit_data', 146]);
        action_dom.push( ['.md_index_hadinvit_detail', 147]);
        action_dom.push( ['.md_index_hadinvit_data', 148]);
        action_dom.push( ['.md_index_qq', 149]);
        action_dom.push( ['.md_chat', 150]);



        /**
         *   120=> 'banner图',
         121=> '简历搜索',
         122=> '招聘人选择',
         123=> '发布职位',
         124=> '定时刷新',
         125=> '批量刷新',
         126=> '关闭',
         127=> '刷新',
         128=> '延期',
         129=> '精准推广，增加曝光，提升效果！',
         130=> '推荐人才--更多',
         131=> '推荐人才--人才列表',
         132=> '最近招聘会--招聘会列表',
         133=> '最近招聘会--我要定展',
         134=> '企业面板--查看资料',
         135=> '绑定/解绑微信',
         136=> '绑定/解绑app',
         137=> '企业测试简称',
         138=> '充值',
         139=> '编辑资料',
         140=> '资金管理',
         141=> '企业认证',
         142=> '企业风采',
         143=> '招聘分享--浏览记录',
         144=> '图片海报--H5页面',
         145=> '面试日程--详情',
         146=> '面试日程--内容数据',
         147=> '已面试待答复--详情',
         148=> '已面试待答复--数据内容',
         149=> 'qq交谈',
         150=> '聊一聊',
         */


	</script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'action.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript">
        var MakeGrade_redirect_url = '<?php echo $_smarty_tpl->getVariable('MakeGrade_redirect_url')->value;?>
';
        hbjs.use('@dialog,@confirmBox', function (m) {

            var $ = m['jquery'];
            confirmBox = m['widge.overlay.confirmBox'];
            Dialog = m['widge.overlay.hbDialog'];
            if (MakeGrade_redirect_url) {
                var loadingDialog1 = loadingDialog('登录过期,请重新登录...');
                setTimeout(function () {
                    window.parent.location.href = MakeGrade_redirect_url;
                }, 600);
            }
        });
	</script>
	<script type="text/javascript">
		var Grade;
        hbjs.use('@validator, @fileUploader, @confirmBox, @hbCommon', function(m){

            var validator = m['widge.validator.form'];
            var fileUploader = m['widge.fileUploader'];
            var ConfirmBox = m['widge.overlay.confirmBox'];
            var Dialog = m['widge.overlay.hbDialog'];
            var $ = m['product.hbCommon'];

            var companyname = '<?php echo $_smarty_tpl->getVariable('companyname')->value;?>
';
            var agreementHtml = '<div class="protocolUsage">\n' +
                '\t\t\t<span class="usagex">尊敬的企业客户，感谢您使用汇博网！为了响应国务院《人力资源市场暂行条例》，提高求职者对企业的信任度，我们对企业《使用协议》进行了调整。请您务必阅读以下协议内容。再次对使用汇博网表示感谢！\n' +
                '</span>\n' +
                '\t\t\t<div class="protocolUx">\n' +
                '\t\t\t\t<h2>使用协议</h2>\n' +
                '\t\t\t\t<p>\n' +
                '\t\t\t\t\t甲方：'+companyname+'<br />\n' +
                '乙方：重庆汇博信息科技有限公司<br />\n' +
                '鉴于：甲乙双方本着互惠互利的原则，经友好协商，依据实际情况，在原合同基础上变更合同条款内容，特订立以下补充协议，以资共同遵守：<br />\n' +
                '一、汇博网只能用于合法的特定目的，即雇主寻找雇员和个人寻找职业。汇博网明确禁止任何其他用途，企业必须保证不用于以下任何一种途径：<br />\n' +
                '1、为汇博网的竞争同行发布职位，并用此方法寻求与雇主联络业务。<br />\n' +
                '2、未经合同明确书面许可，发布第三方招聘信息和代第三方招聘的行为或冒用其他公司名义招聘。<br />\n' +
                '3、将营业执照借给其他企业开账号，以及将账号借给其他企业使用。<br />\n' +
                '4、在招聘过程中，向求职者收取费用，包含但不限于收费模特，KTV收费服务员，收费中介等收费的职位或行业。<br />\n' +
                '5、为了尽可能的规避求职者的风险，全面禁止以下行业及部分风险职位通过汇博网发布招聘信息：<br />\n' +
                '（1）直销业、保险行业、其他诈骗行业、传销行业。  <br /> \n' +
                '（2）汇博网的同行业公司、非人力资源中介公司、收费中介公司、要求求职者自己出钱的投资理财公司。<br />\n' +
                '（3）娱乐会所：包含但不限于夜总会、浴场洗浴、按摩场所等可能会涉及色情、准色情的行业。<br />\n' +
                '（4）手工兼职：包含但不限于兼职打字员、工艺品制作、制作打火机或圆珠笔等职位或行业、淘宝信誉代刷、代购等虚拟兼职的职位或行业。<br />\n' +
                '（5）岗位职责描述虚假不实，故意以高薪、高收入等为诱饵吸引求职者或其他可能导致求职者误解的职位。<br />\n' +
                '（6）包含但不限于发布陪聊、视频陪聊、包房公主、包房少爷、私人游伴等职位。<br />\n' +
                '6、对于以下4种行为，如果被3人举报属实，该职位自动变为审核不通过，该公司自动暂停招聘服务：\n' +
                '<br />（1）以服装、培训、介绍工作等为由变相欺诈收费；<br />（2）娱乐会所、博彩等涉嫌赌博、色情；<br />\n' +
                '（3）冒用该公司名义招聘；<br />（4）保险代理人\n' +
                '<br /><b>在汇博网上，企业被求职者举报为以上行为，经核实属实，将对该企业进行关闭服务，且不退款处理。</b><br />\n' +
                '\n' +
                '二、在汇博网上进行招聘，甲方须及时向乙方提供营业执照等各类相关手续所需的证件，中介和培训机构等特殊行业须提供相关业务所需的资质证件或证明。<br />\n' +
                '1、培训学校/机构等代招航空地勤类收费职位的代招机构须提供以下资料：<br />\n' +
                '①　提供培训机构（学校）的办学许可证<br />\n' +
                '②　培训机构提供盖公章的授权委托书 <br />\n' +
                '③　被授权单位及个人的营业执照、身份证复印件<br />\n' +
                '2、给代招国家机构类、事业单位类等职位的企业须提供以下资料：<br />\n' +
                '①　人力资源代招机构提供人力资源许可证和代招证明<br />\n' +
                '②　非人力资源类企业提供代招证明<br />\n' +
                '3、进行海外招聘的企业须提供以下资料：<br />\n' +
                '①　提供人力资源许可证和代招证明<br />\n' +
                '②　提供资质证书（对外劳务合作经营资格）或者是跟有资质证书公司之间的代招协议<br />\n' +
                '三、在汇博网上，对以下行业或企业进行打标：<br />\n' +
                '①　人力资源公司为其他企业代招，将该企业打标为“代招机构”；<br />\n' +
                '②　企业存在假借招聘名义进行培训招生的行为，经核实后，将该企业打标为“假招聘转招生”；<br />\n' +
                '③　企业实际为KTV，经核实后，将该企业打标为“KTV”；<br />\n' +
                '④　企业进行海外招聘，经核实后，将该企业打标为“海外招聘”。<br />\n' +
                '<br /><b>任何经汇博网确认已违反了相关法律法规、本公司法律声明、网站使用规则及使用协议的某一项或多项的用户，汇博网有权决定是否给予其暂停使用或终止使用的处理，且已付费用不予退还。</b>\n' +
                '\t\t\t\t</p>\n' +
                '\t\t\t</div>\n' +
                '\t\t\t<label class="isProtocolx">\n' +
                '\t\t\t\t<input type="checkbox" name="isProtocol" id="" value="" />\n' +
                '\t\t\t\t我已仔细阅读以上内容并同意遵守《使用协议》\n' +
                '\t\t\t</label>\n' +
                '\t\t\t<a href="javascript:void(0);" class="protocolUsageBtn">确定</a>\n' +
                '\t\t</div>';
            var shareEffectDialog = new Dialog({
                idName: 'shareeffect-dialog',
                title: '浏览记录',
                content: "<?php echo smarty_function_get_url(array('rule'=>'/index/shareEffectList/'),$_smarty_tpl);?>
",
                close: 'X',
                width:500,
                height:400
            }); 
            shareEffectDialog.on('closeX', function(){
                $.ajax({
							url : "<?php echo smarty_function_get_url(array('rule'=>'/index/GetShareEffectNum/'),$_smarty_tpl);?>
",
							type : "GET",
							dataType : "JSON",
							success : function(result) {
                                var num = result.share_effect_num || '';
                                $('#share_effect_num').text(num);
                            }
                        });
            });
            $("#share_effect_list_btn").on('click', function(){
                shareEffectDialog.show();
            });
            var agreementDialog = new Dialog({
                idName: 'agreement-dialog',
                title: '',
                content: agreementHtml,
                close: '',
                width:850,
                height:700,
                zIndex:100000
            });
            var have_agreement = '<?php echo $_smarty_tpl->getVariable('have_agreement')->value;?>
';
            if (have_agreement==1){
                agreementDialog.hide();
            }else{
                agreementDialog.show();
            }
			$('.protocolUsageBtn').click(function(){

               var isProtocol =  $("input[name='isProtocol']").prop('checked');
               if (!isProtocol){
				   var msg = '请仔细阅读以上内容并同意遵守《使用协议》';
                   ConfirmBox.timeBomb(msg, {
                       name : "warning",
                       timeout : 1000,
                       width: 400,
                       zIndex:100001
                   });
               }else{
                   $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/SaveAgreement/"),$_smarty_tpl);?>
', function (result) {
                       if (result.status) {
                           ConfirmBox.timeBomb(result.msg, {
                               name : "success",
                               timeout : 1000,
                               width: 200
                           });
                           window.location.reload();
                       }else{
                           ConfirmBox.timeBomb(result.msg, {
                               name : "fail",
                               timeout : 1000,
                               width: 200
                           });
                       }
                   });
               }
			});

            var blindAppHtml =
                '<style>\n' +
                '        .app-flow{padding: 34px 0 70px 60px;text-align: left;}\n' +
                '        .app-flow-tit{color: #444;font-size: 16px;padding-bottom: 20px;}\n' +
                '        .app-flow-tit span{color: #ff5400;}\n' +
                '    </style>\n' +
                '<div class="app-flow">\n' +
                '    <p class="app-flow-tit">1、添加子账号，如已添加略过此步骤<span>（可直接绑定管理员账号）</span></p>\n' +
                '    <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/account/flow_01.jpg" alt="" width="178" height="40" style="margin-bottom: 30px;">\n' +
                '    <p class="app-flow-tit">2、打开app，用<span>汇博求职者账号</span>登陆,没有则右上角注册即可</p>\n' +
                '    <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/account/flow_02.jpg" alt="" width="810" height="358">\n' +
                '    <p class="app-flow-tit">3、进入“我的”，点击“请绑定”，输入添加的直聘子账号或企业主账号，即绑定成功！</p>\n' +
                '    <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/account/flow_03.jpg" alt="" width="810" height="350">\n' +
                '</div>';
            var blindAppDialog = new Dialog({
                idName: 'flowAPP-dialog',
                title: '绑定app操作流程说明',
                content: blindAppHtml,
                close: '╳',
                width: 900,
                height:600
            });

			var blindAppDialog_new = new Dialog({
				idName: 'flowAPP-dialog',
				title: '手机号绑定',
				content: "",
				close: '╳',
				width: 600,
				initHeight:350
			});
			$('#appbind').on('click', function(){
				/* blindAppDialog.show();*/
				var account_id = $(this).attr('data-id');
				blindAppDialog_new.setContent({content:"<?php echo smarty_function_get_url(array('rule'=>'/account/AccountBind/'),$_smarty_tpl);?>
?account_id="+account_id}).show();
			});

                $(".unbindWeixin").on("click", function () {
                    var openid = $(this).attr('data-value');
                    ConfirmBox.confirm('确定解除绑定吗？', '解绑微信', function () {
                        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/account/unbindweixin/"),$_smarty_tpl);?>
', {openid: openid}, function (result) {
                            if (!result.status) {
                                alert(result.msg);
                            }
                            window.location.reload();
                        });
                    }, {
                        width: 300,
                        close: 'x'
                    });
                });
            $(".unRelatedPerson").on("click", function () {
                var person_id = $(this).attr('data-value');
                var account_id = $(this).attr("data-account");
                ConfirmBox.confirm('确定解除绑定吗？', '解绑app', function () {
                    $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/account/delRelatedPerson/"),$_smarty_tpl);?>
', {
                        person_id: person_id,
                        account_id: account_id
                    }, function (result) {
                        if (result.error) {
                            alert(result.error);
                        }
                        window.location.reload();
                    });
                }, {
                    width: 300,
                    close: 'x'
                });
            });

            Grade = new Dialog({
                close: 'x',
                idName: 'grade_dialog',
                title: '满意度评价',
                width: 600,
                initHeight: 180,
                autoHeight: false,
                content: '<?php echo smarty_function_get_url(array('rule'=>"/index/MakeGrade/"),$_smarty_tpl);?>
',
                isAjax: false,
                zIndex:10010
            });
            var moreAppraise = new Dialog({
                close: 'x',
                idName: 'moreAppraise',
                title: '招聘状况',
                width: 650,
                initHeight: 180,
                autoHeight: true,
                content: '<?php echo smarty_function_get_url(array('rule'=>"/index/RecruitStatus/"),$_smarty_tpl);?>
',
                isAjax: false,
                zIndex:10010
            });

            $('#moreAppraise').click(function(){
                moreAppraise.show();
            });
           $('#grade').click(function(){
               Grade.show();
		   });

           $(function(){
               var cookie_grade_id = '<?php echo $_smarty_tpl->getVariable('cookie_grade_id')->value;?>
';
               var open_grade = '<?php echo $_smarty_tpl->getVariable('open_grade')->value;?>
';
               if (cookie_grade_id=='' && open_grade>0) {
                   Grade.show();
			   }
		   })
           
        });
	</script>

	<script type="text/javascript">

		try {
			hbjs.use(factory);
		} catch(e) {
			factory($);
		}

		function factory($){
			$(window).scroll(function(){
				if ($(document).scrollTop() > 120){
					$('#sus').find('a.backTop').css({'display':'inline-block'});
				}else{
					$('#sus').find('a.backTop').css({'display':'none'});
				}
			});
			$('#sus').find('a.backTop').click(function(){
				$('html,body').animate({ scrollTop: 0 });
			});
		}
	</script>

	<script type="text/javascript">
        var  Feedback,_thisClose,resumeCompleteDialog;
        var job = '';
        var singleJobId = '';
        var refreshSomeDialog = '';
		var poster_msg = '<?php echo $_smarty_tpl->getVariable('poster_msg')->value;?>
';
		var job_num_poster = '<?php echo $_smarty_tpl->getVariable('has_pub_job')->value;?>
';
		var poster_job_id = '<?php echo $_smarty_tpl->getVariable('poster_job_id')->value;?>
';
		var is_share_h5 = '<?php echo $_smarty_tpl->getVariable('is_share_h5')->value;?>
';
		hbjs.use('@css3, @jobFlexSlider,@imageEditor, @confirmBox, @jobsort, @orderActions', function(m) {
			var imageEditor     = m['widge.imageEditor'],
				ConfirmBox      = m['widge.overlay.confirmBox'],
				Dialog          = m['widge.overlay.hbDialog'],
				util            = m['base.util'],
				cookie          = m['tools.cookie'],
				orderAction     = m['product.orderActions'],
				$               = m['jquery'].extend(m['cqjob.jobsort'], m['cqjob.jobFlexSlider']),
				uploadDialog = new Dialog({
					close: 'x',
					idName: 'ui_company_dialog',
					title: '请上传有识别性的logo图片',
					width: 700,
					content: '<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/company/uploadCompanyLogo/',
					isAjax: true
				}),
				fontSize = 18,
				pWidth = 70,
				stopDialog   = new Dialog({
					close : 'X',
					idName : 'stop_job_dialog',
					title : '职位停招',
					width : 400,
					isOverflow : false,
					isAjax : true
				}),
				imgEditor;
			//一键导入
			var  importDialog = new Dialog({
				close: 'x',
				idName: 'click_import_dialog',
				title: '请选择要导入的职位',
				width: 700,
				content: '<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/index/getOuterJobs/',
				isAjax: true
			});

			//2019.6.17自动推荐气泡
			$('.autoRecommenTip').on('click','a',function(){
				var tomorrow = new Date("<?php echo date('Y/m/d 00:00:00',strtotime('+14 day'));?>
");
				var recommend_cookie_pre = "recommend_tip_cookie_"+'<?php echo $_smarty_tpl->getVariable('account_id11')->value;?>
';
				
				cookie.set(recommend_cookie_pre,'is_click_recommend',{expires:tomorrow,path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
				if($(this).hasClass('closeTipBtn')){
					$(this).parent('.autoRecommenTip').hide()
				}
			})

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

			//通知：置顶规则修改预告
			$('.cmpAnnunciateTip span').click(function(){
				$('.cmpAnnunciateTit').slideToggle();
			});
			$("#js_click_import").click(function(){
				importDialog.show();
			});
			$("body").delegate('#importClose',"click",function(){
				importDialog.hide();
				return false;
			});
			var  JobCheck = new Dialog({
				close: 'x',
				idName: 'click_import_dialog',
				title: '系统提示',
				width: 300,
				content: '<div style="padding:35px 15px; ">该职位正在审核中，暂不能预览。如需修改，请到职位管理编辑</div>',
				isAjax: true
			});


			$(".check_status").click(function(){
				JobCheck.show();
			});

			//海报
          var sharePoster=  '<div class="sharePoster">' +
            '<p>使用微信扫码生成招聘海报</p>' +
            '<img src="<?php echo smarty_function_get_url(array('rule'=>'/poster/PostCode'),$_smarty_tpl);?>
" alt="">' +
          '<p>分享招聘海报让更多朋友帮你转发、为你推荐人才吧</p>' +
          '</div>'
          var sharePosterDialog = new Dialog({
            idName: 'sharePoster-dialog',
            title: '分享招聘海报',
            content: sharePoster,
            close: '╳',
            width: 400,
            height:300
          });

          $('#sharePoster').on('click', function(){
            sharePosterDialog.show();
          });

          $('#sharePosterNone').on('click', function(){
            var msg = "您当前没有在招职位，无法生成招聘海报";
            ConfirmBox.timeBomb(msg, {
              name : "warning",
              timeout : 1000,
              width: fontSize * msg.length + pWidth
            });
          })


			stopDialog.after('hide', function(){
				if(stopDialog.query('.replyAndStop').length){
					stopDialog.query('.replyAndStop').off('click');
				}
				this.off('loadComplete');
			});
			//系统后台提示
			var informTraining2 = new Dialog({
				close : 'X',
				idName : 'informTraining_dialog',
				title : '温馨提示',
				width : 625,
				isOverflow : false,
				isAjax : true,
				zIndex:9999
			});

//			$("#js_inforCompany2").click(function(){
//				informTraining2.setContent('<style>.informTraining_dialog .ui_dialog_title{font-family: "Microsoft YaHei"}</style><div style="padding:15px 20px;text-align: left;font-family: \'Microsoft YaHei\"\', \'微软雅黑\'"><p style="font-size: 14px;color: #444;line-height: 24px;text-indent: 28px;margin-bottom: 15px">尊敬的客户：为了更好的维护汇博网企业会员权益，高效满足企业人才招聘需求，汇博网系统将于2018年1月1日起全面升级，届时将停止非会员的职位发布功能。为避免对您的工作带来不便，请及时与您的招聘顾问联系，再次感谢您一直以来对汇博网的信任与支持</p><p style="text-align: right;font-size: 14px;color: #666;padding-top: 20px">汇博网（huibo.com）<br />2017年12月12日</p></div>').show();
//				return false;
//			});


			//金牌面试官  20170714
			var gold = new Dialog({
				close : 'X',
				idName : 'informTraining_dialog',
				title : '汇博网 · 永川区人力资源公益沙龙',
				width : 535,
				isOverflow : false,
				isAjax : true,
				zIndex:9999
			});
			$("#js_gold").click(function(){
				gold.setContent('<div style="text-align: center;padding:25px 55px 45px 55px;font-family: \'Microsoft YaHei\';line-height: 23px"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/infro_company_gold2017.jpg" /><p style="font-size: 18px;color: #ff7200;border-top:1px solid #ff7200;border-bottom: 1px solid #ff7200;height: 38px;line-height: 38px;width: 320px;margin: 10px auto 25px">7月29日（周六）下午 / 永川名豪酒店</p><strong style="font-size: 18px;color: #ff7200">分享嘉宾：肖老师</strong><p style="font-size: 14px;color: #333;margin-top: 15px;font-weight: bold">高级人力资源管理师、现任某世界500强企业人力资源部部长<br />曾任重庆某大型上市公司招聘经理和人力负责人</p><p style="font-size: 14px;color: #666;margin-top: 15px;margin-bottom: 20px;text-align: left">17年人力管理经验，十年制造业人力资源管理工作经验和上市公司、世界500强企业工作背景。担任过企业内部高级讲师，多次为重庆本地企业、HR协会进行授课。精通现代企业人力资源管理实务和理论，在集团化管控、员工招聘、培训与开发、组织绩效、员工关系等板块有丰富的实操经验，是一位实战派的人力资源专家。</p><p style="color: #ff7200;font-size: 18px;font-weight:blod;background: #fff3ec;height: 38px;line-height: 38px;border-radius: 5px;margin: 0 10px">咨询：15696154943 / 15023087231</p></div>').show();
				return false;
			});

			//企业年报弹窗
			/* var company_annual_report_dialog = new Dialog({
				close : 'X',
				idName : 'company_annual_report_dialog',
				title : '企业年报',
				width : 450,
				height : 300,
				isOverflow : false,
				isAjax : true,
				zIndex:9999
			}); */
			
			$('.morePopx span em').on('click', function(){
				$('.morePopx').hide();
				var tomorrow = new Date("<?php echo date('Y/m/d 00:00:00',strtotime('+10 day'));?>
");
                cookie.set('isShowMorePopx','show',{expires:tomorrow,path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
			});
			
			var isShowMorePopx = cookie.get('isShowMorePopx');
			if(isShowMorePopx == 'show'){
				$('.morePopx').hide();
			}else{
				$('.morePopx').show();
			}
			
			var joblist = {
				initialize : function() {
					//是否显示提示框
					var isShowAutoMaticTip = cookie.get('isShowAutoMaticTip');
					/*if (isShowAutoMaticTip == null || isShowAutoMaticTip == "") {
						$("#show_alt").show();
					}*/
					$('.showClose').click(function() {
						$('#show_alt').hide();
						cookie.set('isShowAutoMaticTip', 'true');
					});
				},
				delayJob : function(job_id) {
					/*
					$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/DelaySingle/",'data'=>"job_id=' + job_id + '&obj=joblist&callback=delayJobCallback&v=' + Math.random() + '"),$_smarty_tpl);?>
', {title:'职位延期'});
					*/

					stopDialog.setContent({
						title: '职位延期',
						content: '<?php echo smarty_function_get_url(array('rule'=>"/job/DelaySingle/",'data'=>"job_id=' + job_id + '&obj=joblist&callback=delayJobCallback&v=' + Math.random() + '"),$_smarty_tpl);?>
'
					}).show();
					stopDialog.on('loadComplete', function(){
						this.addCloseEvent(this.query('#btnDelayCancel'));
					});

				},
				delayJobCallback : function(job_id, end_time) {
					$("#li" + job_id).find('td:eq(5)').html('<em class="gray" title="' + end_time + '结束招聘">' + end_time + '</em>');
				},
				delayJobsCallback : function(job_id,end_time) {
					window.location.reload();
				}
			};
			joblist.initialize();
			<?php if ($_smarty_tpl->getVariable('is_show_company_annual_dialog')->value){?>
			/* company_annual_report_dialog.setContent('<span style="display:block;text-align:center;font-size:18px;padding-bottom:10px;font-weight:bold;color: red;font-weight:bold">2019年企业招聘报告请查收！</span><img style="display:block;width:150px; height:150px;margin:0 auto;" src="<?php echo $_smarty_tpl->getVariable('companyAnnualReport')->value;?>
"><span style="color:#444; text-align:center; padding-top:10px;display:block;">微信扫一扫</span>').show(); */
			<?php }?>
			//职位相关操作
			var job_manager = {
				init:function(){
					$('.job_close').click(function(){
						job_manager.toStopJobs($(this).attr('data-id'),false);
					});
					$('.lazy').click(function(){
						joblist.delayJob($(this).attr('data-id'));
					});
					$('.share-link').click(function(){
						job_manager.showJobSharePosters($(this).parent('.share-box').attr("data-id"));
					});
					$('#share_img_poster').click(function(){

						job_manager.showPostersMsg();
					});
					$('#share_h5').click(function(){
						if(is_share_h5 == 1){
							var msg = "您当前没有在招职位，无法生成H5分享";
					            ConfirmBox.timeBomb(msg, {
					              name : "fail",
					              timeout : 3000,
					              width: fontSize * msg.length + pWidth
					            });
							return false
						}else if(is_share_h5 == 2){
							var msg = "您当前职位不能公开展示，无法生成H5分享";
					            ConfirmBox.timeBomb(msg, {
					              name : "fail",
					              timeout : 3000,
					              width: fontSize * msg.length + pWidth
					            });
							return false
						}else{
							window.open('<?php echo smarty_function_get_url(array('rule'=>'/index/TemplateStatisticalByH5','data'=>'type=4'),$_smarty_tpl);?>
')
						}
					});
				},

				showJobSharePosters:function(job_id){

					$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/poster/GetPic/",'data'=>"job_id='+job_id+'"),$_smarty_tpl);?>
',{title:"分享海报"});
				},
				showPostersMsg:function(){
					if(poster_msg != '') {
						$.anchorMsg(poster_msg, {icon: 'fail'});
						return;
					}
					if(job_num_poster == 1){
						job_manager.showJobSharePosters(poster_job_id);
					}else{
						window.open("<?php echo smarty_function_get_url(array('rule'=>'/createposter/index'),$_smarty_tpl);?>
","_blank");
					}
					//window.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/createposter/index'),$_smarty_tpl);?>
";

				},

				toStopJobs:function(jobidArr,is_only) {
					var jobid    = jobidArr;
					var applyUrl = "/apply/index-job_id-"+jobid;
					var title_message = '关闭职位';
					/**判断先**/

					var usertype = cookie.get('usertype'),
						userid = cookie.get('userid'),
						accountid = cookie.get('accountid'),
						isLogin = (!!userid && (usertype == 'c') && !!accountid);

					if(!isLogin){
						window.location.href = window.location.href;
						return;
					}

					$.getJSON("<?php echo smarty_function_get_url(array('rule'=>'/index/GetNoreplycount'),$_smarty_tpl);?>
-jids-"+jobid, null, function (data) {
						if (parseInt(data.count) > 0) {
							var tipHTML = [
								'<div class="warning_dialog">',
								'<dl><dt></dt>',
								'<dd>',
								'<p><font color="red">' + data.names + '</font>' + data.promise_jids.length + '个职位中还有' + data.count + '份简历未进行回复，请先回复完简历后再关闭职位。</p>',
								'</dd></dl>',
								'<div style="text-align:text;width:340px;border:1px solid #fff;">',
								'<a href="javascript:void(0);" class="cpromisetip replyAndStop" style="margin-right:15px;">回绝简历并关闭职位</a>',
								'<a href="' + applyUrl + '" class="cpromisetip graybutn" target="_blank" style="margin-right:0px;">手动回复简历</a>',
								'</div>',
								'</div>'
							].join('');
                            if(is_only == 'not_refresh')
                                jobRefreshDialog.hide();

							stopDialog.setContent({
								title: '职位停招',
								content: tipHTML
							}).show();
							stopDialog.query('.replyAndStop').on('click', function (e) {
								$.getJSON('/index/stopandreplyall-jids-' + jobid, null, function (data) {
									if (parseInt(data.status) > 0) {
										$.anchorMsg(data.msg);
                                        if(is_only != 'not_refresh'){
                                            setTimeout(function() {
                                                window.location.href = window.location.href;
                                            }, 1500);
                                        }
									} else {
										if (typeof data.msg != 'undefined') {
											$.anchorMsg(data.msg, {
												icon : 'fail'
											});
										} else {
										}
									}
								});

								stopDialog.hide();
							});

							/*stopDialog.query('a.graybutn').on('click', function (e) {
								stopDialog.hide();
							});*/
						} else if(is_only == 'not_refresh'){
                            ConfirmBox.confirm("关闭（停止招聘）后，不再被求职者看到，确定关闭吗？","关闭职位",function(obj){
                                this.hide();
                                $.getJSON('/job/BatchStopJobDo-', {hddjobID:jobid}, function (result) {
                                    if(result.success) {
                                        $.anchorMsg("关闭成功 ");
                                        _thisClose.parent().parent().remove();
                                        if(!$('#jobRefreshHtml').find('a').hasClass('job_close')){
                                            jobRefreshDialog.hide();
                                            window.location.reload();
                                        }
//                                        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/GetNotFreshJobs/",'data'=>"v='+Math.random()+'"),$_smarty_tpl);?>
', null, function (data) {
//                                            jobRefreshDialog.hide();
//                                            if(data.not_refresh_num > 0)
//                                                showJobRefreshDialog(data);
//                                            else
//                                                window.location.reload();
//
//                                        });
                                    }else{
                                         $.anchorMsg("关闭失败",{icon:'fain'});
                                    }
                                });
                            },null,{width:300});

                        }else {
							/*
							if(!is_only)
								title_message = '关闭职位';
							*/
							//stopDialog.setCp
							stopDialog.setContent({
								'title': '关闭职位',
								'content': '/job/BatchStopJob?jobids='+jobid
							}).show();
							stopDialog.on('loadComplete', function(){
								this.addCloseEvent(this.query('#btnStopJobCancel'));
							});

							//$.showModal('/job/BatchStopJob?jobids='+jobid, {title:title_message});
						}
					});
				}
			};
			job_manager.init();

			$(".ques").mouseover(function(){
				$(".reversionList li").each(function(i,obj){
					if($(obj).hasClass('hover')) $(obj).removeClass('hover');
				});
				$(this).parent().addClass('hover');
			});

			$(".ques").mouseout(function(){
				$(this).parent().removeClass('hover');
			});
			
			$(".number i").mouseover(function(){
				$(".reversionList li").each(function(i,obj){
					if($(obj).hasClass('resCur')) $(obj).removeClass('resCur');
				});
				$(this).parents('li').addClass('resCur');
			});

			$(".number i").mouseout(function(){
				$(this).parents('li').removeClass('resCur');
			});

			$('.upLOGO').on('click', function(e){
				uploadDialog.show();
			});
			uploadDialog.after('hide', function(){
				this.query('#newPopQuit').off('click');
				imgEditor.destory();
			});
			uploadDialog.on('loadComplete', function() {
				var uploadPanel = this.query('#uplogo1'),
						editPanel = this.query('#dropImageContainer'),
						dropImage = this.query('#dropImage'),
						uploadingDialog;

				imgEditor = new imageEditor({
					imageCroperConfig: {
						element: dropImage,//目标
						handle: '#dragDiv',
						color: '#f1f1f1',
						width: dropImage.width(),
						height: dropImage.height()
					},
					fileUploaderConfig: {
						imageURL: '<?php echo $_smarty_tpl->getVariable('logo_virt_path')->value;?>
'
					},
					trigger: this.query('#newPopSave')
				});
				imgEditor.on('startUpload', function(e){
					var msg = "正在上传...";
					uploadingDialog = ConfirmBox.timeBomb(msg, {
						name : "warning",
						timeout : 100000,
						width: fontSize * msg.length + pWidth
					});
				}).on('progresed', function(e){
					this._imgFileName = e.data.name;
					this._imgType = e.data.thumb_type;
					this._imgRatio = e.data.thumb_ratio;
					this.loadImage(e.url);

					uploadingDialog.hide();
					uploadPanel.hide();
					editPanel.show();

					var preview1 = editPanel.find('#preview120'),
							preview2 = editPanel.find('#preview160');

					this.addPreview(preview1, 120, 120);
					this.addPreview(preview2, 160, 160);

				}).on('progressError', function(e){

//					uploadingDialog.hide();
					if(e.errorMsg != undefined){
						ConfirmBox.timeBomb(e.errorMsg, {
							name : "fail",
							timeout : 3000,
							width: fontSize * e.errorMsg.length + pWidth
						});
						return;
					}

					if(e.data.status == 0){
						ConfirmBox.timeBomb(e.data.errorMsg, {
							name : "fail",
							timeout : 3000,
							width: fontSize * e.data.errorMsg.length + pWidth
						});
					}

				}).on('save', function(e){
					var self = this;
					$.ajax({
						url: '<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/company/saveLogo',
						data: {
							cropX: e.cropX,
							cropY: e.cropY,
							cropH: e.cropH,
							cropW: e.cropW,
							cropPH: e.cropPH,
							cropPW: e.cropPW,
							url: e.url,
							name: this._imgFileName,
							thumb_type: this._imgType,
							thumb_ratio: this._imgRatio
						},
						success: function(e){
							e = util.json(e);
							if(e.state){
								$('#hidLogo').val(e.image_path);
								//上传头像
								var del_logo = $('#delete_logo').val();
								$.post("/index/editLogoPath", {'logo_path':e.image_path,'del_logo':del_logo},
										function(data){
											if (data.error) {
												$.anchorMsg(data.error);return;
											}
											$('#logo_path').attr('src',"<?php echo $_smarty_tpl->getVariable('logo_base_path')->value;?>
"+e.image_path);
											$('#delete_logo').val(e.image_path);
											uploadDialog.hide();
											delete self._imgFileName;
											delete self._imgType;
											delete self._imgRatio;
										}, "json");

							}
						}
					});
				});

				imgEditor.imageCroper.on('loadSuccess', function(){
					uploadPanel.hide();
					editPanel.show();
				}).on('loadError', function(){
					delete this._imgFileName;
					delete this._imgType;
					delete this._imgRatio;
					uploadPanel.show();
					editPanel.hide();
					var msg = '加载图片失败';
					ConfirmBox.timeBomb(msg, {
						name : "fail",
						timeout : 1000,
						width: fontSize * msg.length + pWidth
					});
				});

				this.query('#newPopQuit').on('click', function(){
					uploadDialog.hide();
				});
			});

			$('#ttp').click(function(){
				var jdialog = new Dialog({
					close: 'x',
					idName: 'jian_dialog',
					title: '详情咨询',
					width:380,
					zIndex:9999
				});


				var thtml =	['<style>.jian_dialog{text-align:left}</style>',
					'<div class="warning_dialog" style="padding:15px;font-family:\'微软雅黑\';line-height:30px">',
					'<p>联系招聘顾问购买推广金，最高多送<b style="color:red;">50%</b></p>',
					<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value["user_name"])){?>
				'<p>招聘顾问：<?php echo $_smarty_tpl->getVariable('hrManager')->value["user_name"];?>
</p>',
				<?php }?>
				<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value["mobile"])||!empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value["mobile"])){?>
				'<p>联系电话：<?php echo $_smarty_tpl->getVariable('tel_head')->value;?>
转<?php echo $_smarty_tpl->getVariable('hrManager')->value["tel"];?>
 [9：00-18：00]</p>',
						'<p>手机号码：<?php echo (($tmp = @$_smarty_tpl->getVariable('hrManager')->value["mobile"])===null||$tmp==='' ? $_smarty_tpl->getVariable('customeruser')->value["mobile"] : $tmp);?>
</p>',
				<?php }else{ ?>
				'<p>联系电话：<?php echo $_smarty_tpl->getVariable('huibo400')->value;?>
</p>',
				<?php }?>
					'<p style="margin-top:15px">',
				<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value["qq"])||!empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value["qq"])){?>
				'<a target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=<?php echo trim((($tmp = @$_smarty_tpl->getVariable('hrManager')->value["qq"])===null||$tmp==='' ? $_smarty_tpl->getVariable('customeruser')->value["qq"] : $tmp));?>
&site=qq&menu=yes">',
						'<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/qq.jpg" />',
						'</a>',
				<?php }else{ ?>
				'<a target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=2851501279&site=qq&menu=yes">',
						'<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/qq.jpg" />',
						'</a>',
				<?php }?>
					'</p>',
					'</div>'].join('');
				jdialog.setContent(thtml).show();
			});
			$('.promotion').click(function(){
				$.post('<?php echo smarty_function_get_url(array('rule'=>"/promotion/AjaxSetPrmotionNumber/"),$_smarty_tpl);?>
');
				$('.showPromotionContent2').hide();
				$('.showPromotionMain').toggle();
				$('#showPromotionContent').toggle();
			});
			$('.radiusProm').click(function(){
				$('.showPromotionMain').hide();
				$('#showPromotionContent').hide();
				$('#showPromotionContent1').hide();
				$('#showPromotionContent2').hide();
				$('#showPromotionContent3').hide();
			});

            //省外同行认证的企业，每天提示一次可刷新职位
            <?php if ($_smarty_tpl->getVariable('need_prompt_refresh_tip')->value){?>
            function setHasPromptRefreshTip(){
                var tomorrow = new Date("<?php echo date('Y/m/d 00:00:00',strtotime('+1 day'));?>
");
                cookie.set('has_prompt_refresh_tip',1,{expires:tomorrow,path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
            }
            var promptRefreshTipNum = 0;
            var promptRefreshTip = setInterval(function(){
                //推广金出来了，这个就不出来了
                if($(".showPromotionMain").is(":visible")){
                    clearInterval(promptRefreshTip);
                    return;
                }
                promptRefreshTipNum++;
                if(promptRefreshTipNum == 10){
                    clearInterval(promptRefreshTip);
                     ConfirmBox.confirm("一键刷新所有职位，让您的职位优先展示","刷新职位",function(){
                        this.hide();
                        setHasPromptRefreshTip();
                    },function(){
                        this.hide();
                        setHasPromptRefreshTip();
                        $("#refreshAll").click();
                    },{width:300,cancelBtn: '<button class="button_a button_a_red">一键刷新</button>',confirmBtn: '<button class="button_a cancelbtn">取消</button>'});
                }
            },1000);
            <?php }?>
			// 刷新简历
			$('#refreshAll').on('click',function(){
				var _this = $(this);
				if(_this.hasClass('locked')){
					return false;
				}
				_this.addClass('locked');

				var url = '<?php echo smarty_function_get_url(array('rule'=>"/index/refreshAll/",'data'=>"v='+Math.random()+'"),$_smarty_tpl);?>
';

				var tipDialog = new Dialog({
					close: 'x',
					idName: 'refresh_dialog',
					title: '系统提示',
					width: 440
				});
				var tipDialogTS = new Dialog({
					close: 'x',
					idName: 'refresh_dialog',
					title: '系统提示',
					width: 300
				});

				<?php if ($_smarty_tpl->getVariable('isEnd')->value){?>
				var tiphtml = "<div style='padding:30px 15px;text-align: center;'><img style='display:inline-block; vertical-align: middle; padding-right: 10px;' src='<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/query.png'>非会员不能刷新职位</div>";
				tipDialogTS.setContent({ content:tiphtml}).show();
				_this.removeClass('locked');
				return false;
				<?php }?>
				$.getJSON(url, null, function (json) {
					if (!json.status) {
						_this.removeClass('locked');
						if (json.not_member) {
							var tiphtml = [
								'<div class="not-member">',
								'<p style="margin-bottom: 10px;">今天的免费刷新机会已用完，可以联系招聘顾问开通会员，获得更多的刷新机会，并且刷新的职位可优先展示</p>',
								<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value["user_name"])){?>
							'<p class="lt"><b>招聘顾问：</b><?php echo $_smarty_tpl->getVariable('hrManager')->value["user_name"];?>
</p>',
							<?php }?>
							<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value["mobile"])||!empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value["mobile"])){?>
							'<p class="lt"><b>联系电话：</b><?php echo $_smarty_tpl->getVariable('tel_head')->value;?>
转<?php echo $_smarty_tpl->getVariable('hrManager')->value["tel"];?>
 [9：00-18：00]</p>',
									'<p class="lt"><b>手机号码：</b><?php echo (($tmp = @$_smarty_tpl->getVariable('hrManager')->value["mobile"])===null||$tmp==='' ? $_smarty_tpl->getVariable('customeruser')->value["mobile"] : $tmp);?>
</p>',
							<?php }else{ ?>
							'<p class="lt"><b>联系电话：</b><?php echo $_smarty_tpl->getVariable('huibo400')->value;?>
</p>',
							<?php }?>
								'<p style="margin-top:20px">',
							<?php if (!empty($_smarty_tpl->getVariable('hrManager',null,true,false)->value["qq"])||!empty($_smarty_tpl->getVariable('customeruser',null,true,false)->value["qq"])){?>
							'<a target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=<?php echo trim((($tmp = @$_smarty_tpl->getVariable('hrManager')->value["qq"])===null||$tmp==='' ? $_smarty_tpl->getVariable('customeruser')->value["qq"] : $tmp));?>
&site=qq&menu=yes">',
									'<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/qq.jpg" />',
									'</a>',
							<?php }else{ ?>
							'<a target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=2851501279&site=qq&menu=yes">',
									'<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/qq.jpg" />',
									'</a>',
							<?php }?>
								'</p>',
								'</div>'
						].join('');
							tipDialog.setContent({title:'友情提示', content:tiphtml}).show();
						} else {
                                $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/GetNotFreshJobs/",'data'=>"v='+Math.random()+'"),$_smarty_tpl);?>
', null, function (data) {
                                    if(data.not_refresh_num > 0){
                                        var tiphtml = ['<style>.refresh_dialog{text-align:left}.hb_ui_dialog .ui_dialog_container{padding:0px}.jian_dialog{text-align:left}.warning_dialog{padding:30px 20px 20px 20px}</style>',
                                            '<div class="warning_dialog">',
                                            '<dl><dt></dt>',
                                            '<dd style="line-height:40px">',
                                            ' <p>请刷新剩余职位</p>',
                                            '</dd></dl>',
                                            '</div>',
                                            '<div class="actuBtn dialogFooter" style="border-top:0px;background:#f3f3f3;padding:7px;text-align:right;">',
                                            '<a href="javascript:void(0);" id="close" class="btn1 btnsF12" style="background:#66bce4;border:0px;box-shadow:none">免费刷新剩余职位</a>',
                                            '</div>'
                                        ].join('');

                                        tipDialog.setContent(tiphtml).show();

                                        tipDialog.query('#close').on('click', function() {
                                            showJobRefreshDialog(data);
                                            tipDialog.hide();
                                        });
                                    }else{
                                        $.message(json.msg, {title:'系统提示', icon:'fail'});
                                    }
                                });
                                return;
							}
						} else if (json.items.params.packageCount >= json.items.params.newCount) {
								torefreshDo();
								_this.removeClass('locked');
                        } else {
                            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/GetNotFreshJobs/",'data'=>"v='+Math.random()+'"),$_smarty_tpl);?>
', null, function (data) {
                                var cancel_str = "取&nbsp;消";
                                if(data.not_refresh_num > 0)
                                    cancel_str = "免费刷新剩余职位";
                                var tiphtml = ['<style>.refresh_dialog{text-align:left}.hb_ui_dialog .ui_dialog_container{padding:0px}.jian_dialog{text-align:left}.warning_dialog{padding:30px 20px 20px 20px}</style>',
                                    '<div class="warning_dialog">',
                                    '<dl><dt style="margin-top:5px"></dt>',
                                    '<dd style="line-height:25px">',
                                    ' <p>今日的刷新次数（'+json.items.params.packageCount+'次）已用完，付费刷新将花费'+json.items.params.consume+'元。<br /><span style="color:#999">（优先使用推广金代扣）</span></p>',
                                    '</dd></dl>',
                                    '</div>',
                                    '<div class="actuBtn dialogFooter" style="border-top:0px;background:#f3f3f3;padding:7px;text-align:right;">',
                                    '<a href="javascript:void(0);" class="btn1 btnsF12" id="btnSet" style="background:#66bce4;border:0px;box-shadow:none">付费刷新</a>',
                                    '<a href="javascript:void(0);" id="close" class="btn3 btnsF12" style="background:#fff;border:0px;box-shadow:none">'+cancel_str+'</a>',
                                    '</div>'
                                ].join('');

                                tipDialog.setContent(tiphtml).show();

                                tipDialog.query('#close').on('click', function() {
                                    if(data.not_refresh_num > 0)
                                        showJobRefreshDialog(data);
                                    tipDialog.hide();
                                });

                                tipDialog.query('#btnSet').on('click', function() {
                                    tipDialog.hide();
                                    orderAction.show({
                                        data : json,
                                        submit : function() {
                                            torefreshDo();
                                            this.hide();

                                        }
                                    });
                                    return;
                                });
                                _this.removeClass('locked');
                            });
                        }
                    });

			});

			function torefreshDo() {
				$('#refreshAll').removeClass('locked');
				$.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/refreshAllDo/",'data'=>"v=' + Math.random() + '"),$_smarty_tpl);?>
', null, function (json) {
					if (json.fail) {
						$.message(json.failitem, {title: '系统提示', icon: 'fail'});
						return;
					}
					if (json.success) {
						$("#divRefreshTime").html(json.refreshtime);
						$("#refreshTimeSpan").show();
                        if(json.not_refresh_num == 0){      //没有未刷新的职位
                            $.anchorMsg('刷新全部职位成功');
                            setTimeout(function() {
                                window.location.href = window.location.href;
                            }, 500);
                            return;
                        }else{          //单独刷新的职位
                            showJobRefreshDialog(json);
                        }

					}
					return;
				});
			}
            var jobRefreshDialog;
            function showJobRefreshDialog(json){
                var has_refreshed_job_num = parseInt(json.hasNoRefresh);
                jobRefreshDialog = new Dialog({
                    idName: 'jobRefreshDialog',
                    width: 540,
                    height:500,
                    title: '职位刷新提示',
                    close:"×",
                    isOverflow: false,
                    isAjax: false
                });
                var jobRefreshHtml = '<div style="padding:10px" id="jobRefreshHtml">'+
                    '<style>.jobRefreshDialog{text-align: left;font-family: "Microsoft YaHei";line-height: 25px}.jobRefreshDialog table{width:100%}.jobRefreshDialog th{font-size: 14px;color: #999;padding: 15px 10px 10px 10px;text-align: center}.jobRefreshDialog td{height:24px;line-height: 48px;font-size: 14px;color: #666;text-align: center;padding-left:10px;padding-right: 10px}.jobRefreshDialog .one td{background-color: #fafafa}.jobRefreshDialog a{color: #2b6fad;margin: 0 10px}.jobRefreshDialog a:hover{color}</style>'+
                    '<p style="font-size: 14px;color: #666;border-bottom: 1px dashed #e9e8e8;padding-bottom: 10px">'+json.msg+'<br />如需刷新请在列表中手动刷新，如已停招请关闭职位</p>'+
                    '<div style="height:190px;overflow-y: auto"><table style="width: 100%">'+
                    '<tr><th style="text-align: left" width="50%">职位名称</th><th>招聘人</th><th>待回复</th><th>操作</th></tr>';
                    $.each(json.notRefreshJobs, function(i, n){
                        if(i%2 == 0)
                            jobRefreshHtml += '<tr class="one"><td style="text-align: left">'+n.station+'</td><td>'+n.account_username+'</td><td>'+n.no_reply_num+'</td><td><a data-id="'+n.job_id+'" href="javascript:;" class="singleRefrsh">刷新</a><a data-id="'+n.job_id+'" href="javascript:;" style="margin-right: 0" class="job_close md_index_close">关闭</a></td></tr>';
                        else
                            jobRefreshHtml += '<tr><td style="text-align: left">'+n.station+'</td><td>'+n.account_username+'</td><td>'+n.no_reply_num+'</td><td><a data-id="'+n.job_id+'" href="javascript:;" class="singleRefrsh">刷新</a><a data-id="'+n.job_id+'" href="javascript:;" style="margin-right: 0" class="job_close md_index_close">关闭</a></td></tr>';
                    });
                    jobRefreshHtml += '</table></div></div>';

                jobRefreshDialog.setContent(jobRefreshHtml).show();
                //弹窗中关闭职位
                jobRefreshDialog.query('.job_close').on('click', function() {
                    //jobRefreshDialog.hide();
                    _thisClose = $(this);
                    job_manager.toStopJobs($(this).attr('data-id'),'not_refresh');
//                    stopDialog.on('closeX', function(){
//                        $(this).hide();
//                    });
                    return;
                });
                //弹窗中刷新职位
                jobRefreshDialog.query('.singleRefrsh').on('click', function() {
                    var _this = $(this);
                    $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/RefreshCqDo/",'data'=>"v=' + Math.random() + '"),$_smarty_tpl);?>
', {job_id:_this.attr('data-id')}, function (data) {
                        if(data.status){
                            _this.hide();
                            $.message("<div style='text-align:center'><b>职位刷新成功</b><br/>请及时处理该职位收到的简历</div>", {title: '提示'});
                        }else{
                            $.message(data.msg, {title: '系统提示', icon: 'fail'});
                        }
                    });
                    return;
                });
                //关闭弹窗时刷新页面
                jobRefreshDialog.on('closeX', function(){
                    window.location.reload();
                });
            }

			/* 分享提示 */
			var shareHTML = [
				"<div class='shareList'>",
				"<h2 style='color:#1abc9c; font-size:18px; margin-bottom:10px;'>分享招聘宣传到朋友圈，让同事和你一起招聘</h2>",
				"<p style='color:#666; font-size:14px; line-height:24px;margin-bottom:20px;'>用微信扫描下方二维码，分享到朋友圈即可<br />",
				"邀请同事去朋友圈分享，TA的朋友很可能就会是你的新同事哦~</p>",
				"</div>",
				"<div class='img'><img src='<?php echo base_lib_Constant::MOBILE_URL;?>
/companylikes/qrcode/flag-' /></div>"
			].join('');
			var shareDialog = new Dialog({
				close: 'x',
				idName: 'ui_company_share_dialog',
				width: 500,
				title: '',
				content: shareHTML,
				isAjax: true
			});

			$("#share").on("click", function() {
				shareDialog.show();
			});
			<?php if (!empty($_smarty_tpl->getVariable('calling_advert_middle',null,true,false)->value)&&count($_smarty_tpl->getVariable('calling_advert_middle')->value)>1){?>
			$('.flexBanner').flexslider({
				animation:"slide",
				direction:"vertical",
				itemWidth:0,
				itemMargin:0,
				prevText:'&#xf053;',
				nextText:'&#xf054;',
				pauseOnAction:false,
				pauseOnHover:true,
				slideshowSpeed:5000,
				move:3
			});
			<?php }?>
				$('.compClose').click(function(){
					$('.banner').slideUp('slow');
				});
				//判断有没有cookie
				var hidShowWeixin = cookie.get('hidShowWeixin');
				if(hidShowWeixin !="true"){
					$('.in-tit').show();
				}
				$('.btnx3').click(function(){
					$('.in-tit').hide();
					if(hidShowWeixin !="true"){
						cookie.set('hidShowWeixin',true,"","/");
					}
				});
				$('.tabList2').click(function(){
					$(this).addClass('cut');
					$('.tabList1').removeClass('cut');
					$('.tabList3').hide();
					$('.tabList4').show();
				});
				$('.tabList1').click(function(){
					$(this).addClass('cut');
					$('.tabList2').removeClass('cut');
					$('.tabList4').hide();
					$('.tabList3').show();
				})

                                //面试评价关闭
                                <?php if (!$_smarty_tpl->getVariable('is_allow_appraise')->value){?>
                                $("#appealSwitch").click(function(){
                                    ConfirmBox.confirm("确定要公开面试评价吗？","公开面试评价",function(obj){
                                        var self = this;
                                        self.hide();
                                        $.post('<?php echo smarty_function_get_url(array('rule'=>"/appraise/changeAppeal/"),$_smarty_tpl);?>
',{"value":1},function(result){
                                            if(result.error){
                                                $.message(result.error, { title: '系统提示', icon: 'fail' });
                                            }else{
                                                $.anchorMsg(result.success);
                                                $("#appealSwitch").parent(".appealSwitchNotice").remove();
                                            }
                                        },"json");
                                    });
                                });
                                <?php }?>
				$("#wixinAlt .close").click(function(){
					$(this).parent().hide();
					cookie.set('showHeadWeixinTip',1,{expires:'',path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
					return false;
				});

				/*微信气泡提醒2015-12-10 end */
				<?php if (isset($_smarty_tpl->getVariable('notMarkJobidsArr',null,true,false)->value)&&count($_smarty_tpl->getVariable('notMarkJobidsArr')->value)>0){?>
				var jobSortDialog = new Dialog({
					close: 'x',
					idName: 'jobSortUpdate',
					width: 800,
					title: '',
					content: '<?php echo smarty_function_get_url(array('rule'=>"/index/updateJobSort/",'data'=>"jobids=".($_smarty_tpl->getVariable('notMarkJobids')->value)),$_smarty_tpl);?>
',
					isAjax: true
				});

				jobSortDialog.on('loadStart', function(){
					this._isOverflow = true;
				}).on('loadComplete', function(){
					this.query(".btn-gray").on("click",function(e){
						jobSortDialog.hide();
					});
					var _s = this
					this.query(".btn-yellow").on("click",function(e){
						//提交
						$.ajax({
							url : "<?php echo smarty_function_get_url(array('rule'=>'/index/UpdateJobSortDo/'),$_smarty_tpl);?>
",
							type : "GET",
							dataType : "JSON",
							data : _s.query("#formjobsort").serialize(),
							success : function(result) {
								if(result.error){
									ConfirmBox.timeBomb(result.error, {
										name : "warning",
										timeout : 1000,
										width: fontSize * result.error.length + pWidth
									});
								}else{
									ConfirmBox.timeBomb(result.success, {
										name : "success",
										timeout : 1000,
										width: fontSize * result.success.length + pWidth
									});
									jobSortDialog.hide();
								}
							}

						});
						//_s.query("#formjobsort").submitForm({success:jobSortSuccessCallBack,clearForm: false});
					});
					<?php  $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('notMarkJobidsArr')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['id']->key => $_smarty_tpl->tpl_vars['id']->value){
?>
					this.query('#dropJobsort<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
').jobsort({
						max:2,hddName:'hidJobsort[<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
]',
						isLimit:true,isAll:false
					});
					<?php }} ?>
					}).show();

				<?php }?>

					<?php if ($_smarty_tpl->getVariable('calling_mark')->value){?>
					var callingDialog = new Dialog({
						close: 'x',
						idName: 'selectCalling',
						width: 800,
						title: '',
						content: '<?php echo smarty_function_get_url(array('rule'=>"/index/selectCalling/"),$_smarty_tpl);?>
',
						isAjax: true
					});
					callingDialog.on('loadStart', function(){
						this._isOverflow = true;
					}).on('loadComplete', function(){
						this.query(".btn-gray").on("click",function(e){
							callingDialog.hide();
						});
						var _s = this
						this.query(".btn-yellow").on("click",function(e){
							//提交
							$.ajax({
								url : "<?php echo smarty_function_get_url(array('rule'=>'/index/UpdateCallingDo/'),$_smarty_tpl);?>
",
								type : "GET",
								dataType : "JSON",
								data : _s.query("#formcalling").serialize(),
								success : function(result) {
									if(result.error){
										ConfirmBox.timeBomb(result.error, {
											name : "warning",
											timeout : 1000,
											width: fontSize * result.error.length + pWidth
										});
									}else{
										ConfirmBox.timeBomb(result.success, {
											name : "success",
											timeout : 1000,
											width: fontSize * result.success.length + pWidth
										});
										callingDialog.hide();
									}
								}

							});
							//_s.query("#formjobsort").submitForm({success:jobSortSuccessCallBack,clearForm: false});
						});
					}).show();
					<?php }?>




			$('#outer_Jobs').on('click',function(data){

			});




		//	kkp




						//选择刷新的职位
						$('.freshList li .select-refresh-job').on('click',function () {
							if($('.freshList li .select-refresh-job').length === $('.freshList li .select-refresh-job:checked').length) {
								$('.freshList li .select-all-refresh-job').attr("checked",true);
							}else {
                                $('.freshList li .select-all-refresh-job').attr("checked",false);
							}
                        })
						//全选操作
                        $('.freshList li .select-all-refresh-job').on('click',function () {
							if($(this).is(':checked')) {
                                $('.freshList li .select-refresh-job').attr("checked",true);
								if($('ul.freshList').hasClass('js-over-height')) {
                                    $('ul.freshList').removeClass('js-over-height');
								}
							}else {
							    $('.freshList li .select-refresh-job').attr("checked",false);
							}
                        })

                        $('#refreshAllJob').on('click',function(){
                            var count = $('.freshList li .select-refresh-job:checked').length;
                                 if(count < 1){
                             	ConfirmBox.alert('请选择需要刷新的职位','',{width:350});
                             	return;
                             }

                           // var refreshPoint = '<?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_release_point_job_refresh'];?>
';
                            var refreshPoint = 10000;
                            var needCash  = <?php echo $_smarty_tpl->getVariable('selling')->value;?>
 * count;
                            var jobsIds = [];
                            $('.freshList li .select-refresh-job:checked').each(function () {
                                jobsIds.push($(this).val())
                            })
                            var loading;
                            job = jobsIds.join(',');
                            var content = "<?php echo smarty_function_get_url(array('rule'=>'/index/RefreshAllCq'),$_smarty_tpl);?>
/count-"+count+"-is_batch-1";
                            refreshSomeDialog.setContent(content).show();

                        })
                        $(document).on('click','.refreshSomeDialog .many-con .btns .btnRefresh',function () {
                            refreshSomeDialog._addLoading();
                            $('.refresh-many').hide();
//                                loading = ConfirmBox.timeBomb('提交中...', {
//                                    name : "success",
//                                    timeout : 100000,
//                                    width: 200
//                                });
                            $.post("<?php echo smarty_function_get_url(array('rule'=>'/index/RefreshAllCqDo'),$_smarty_tpl);?>
",{jobids:job},function(r){
                                if(r.status){
                                    refreshSomeDialog._removeLoading();
                                    refreshSomeDialog.hide();
                                    if(r.hasNoRefresh >0){
                                        showJobRefreshDialog(r);
                                    }else{
                                        ConfirmBox.alert(r.msg,function(){
                                            window.location.reload();
                                        },{width:450});
                                    }
                                    return;
                                }
                                ConfirmBox.alert(r.msg,function(){
                                    refreshSomeDialog.hide();
                                },{width:450});
                            },'json');
                            //refreshSomeDialog.hide();
                        })

                        $('.job_refresh').on('click',function(){
                            singleJobId = $(this).attr('data-id');
                            var content = "<?php echo smarty_function_get_url(array('rule'=>'/index/RefreshAllCq'),$_smarty_tpl);?>
/count-"+1+"-is_batch-0-job_id-"+singleJobId;
                            var spread = "<?php echo $_smarty_tpl->getVariable('companyresources')->value['spread_overage'];?>
";
                            var yue    = "<?php echo $_smarty_tpl->getVariable('companyresources')->value['account_overage'];?>
";
                            var isShow = cookieutility.get('isShowCq') ? true:false;
                            var refreshPoint = "<?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_release_point_job_refresh'];?>
";
                            if( Number(refreshPoint) < 1 && !isShow){
                                refreshSomeDialog.setContent(content).show();
                                return;
                            }
                            if( Number(refreshPoint) < 1 && Number(spread) <2 && Number(yue) <2){
                                refreshSomeDialog.setContent(content).show();
                                return;
                            }
                            if(!isShow){
                                refreshSomeDialog.setContent(content).show();
                                return;
                            }

                            $.post("<?php echo smarty_function_get_url(array('rule'=>'/index/RefreshCqDo'),$_smarty_tpl);?>
",{job_id:singleJobId},function(r){
                                if(r.status){
                                    $.anchorMsg(r.msg, {icon: 'success'});
//                                    ConfirmBox.alert(r.msg,function(){
                                        window.location.reload();
//                                    });
                                    return;
                                }
                                refreshSomeDialog.hide();
                                $.anchorMsg(r.msg, {icon: 'fail'});
                                //ConfirmBox.alert(r.msg);
                            },'json');
                        });

                        $(document).on('click','.refreshSomeDialog .only-one-con .btns .btnRefreshSingle',function () {
                            refreshSomeDialog._addLoading();
                            $('.refresh-many').hide();
                            $.post("<?php echo smarty_function_get_url(array('rule'=>'/index/RefreshCqDo'),$_smarty_tpl);?>
",{job_id:singleJobId},function(r){
                                if(r.status){
                                    if($('.refreshSomeDialog .only-one-con .check-box input').is(':checked')){
                                        cookieutility.set('isShowCq',1);
                                    }
                                    refreshSomeDialog.hide();
                                    $.anchorMsg(r.msg, {icon: 'success'});
                                  //  ConfirmBox.alert(r.msg,function(){
                                        window.location.reload();
                               //     });
                                    return;
                                }
                                refreshSomeDialog.hide();
                                $.anchorMsg(r.msg, {icon: 'fail'});
                                //ConfirmBox.alert(r.msg);
                            },'json');

                        })

                        var rechargeDialog =new Dialog({
                            close: 'x',
                            idName: 'rechargeDialog',
                            width: 470,
                            title: '提示',
                            isAjax: false
                        });
                        refreshSomeDialog = new Dialog({
                            close:'x',
                            idName:'refreshSomeDialog',
                            width:400,
                            title:'提示',
                            isAjax:true
                        });

                        $(document).on('click','.refreshSomeDialog  .refresh-many .btns .cancel,.btncancel',function () {
                            refreshSomeDialog.hide();
                        });
		});


		var BATCH_JOB_PARAMS= {
			selectJobs:[],
			isRefreshValTip:true,
			isSpreadMoneyTip:true,
			refreshVal:3,        //刷新点
			singleRefreshVal:1,  //刷新一个职位需要的刷新点
            spreadMoney:5,       //推广金
			singleSpreadMoney:2  //刷新一个职位需要的推广金
        }

        function jobSortSuccessCallBack(json){
		}

		var is_ajaxspread_query = false;
		$(function() {
			$('.marketTip span').hover(function(){
				var spreadJobSeeCount = $('#spreadJobSeeCount').html();
				var spreadClickCount = $('#spreadClickCount').html();
				if((spreadJobSeeCount == "正在统计..." || spreadClickCount == "正在统计...") && is_ajaxspread_query == false){
					is_ajaxspread_query = true;
					$.ajax({
						url: '<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/index/AjaxSpreadJobStatistics/',
						dataType : 'json',
						success: function(e){
							$('#spreadJobSeeCount').html(e.spreadJobSeeCount == -1 ? 0 : e.spreadJobSeeCount);
							$('#spreadClickCount').html(e.spreadClickCount == -1 ? 0 : e.spreadClickCount);
							is_ajaxspread_query = false;
						}
					});
				}
				$('.marketPopx1').toggle();
			});
//			var star01=$('#weiBtn'),starList=$('.in-tit');
//			starList.css("left",star01.position().left-10);
			  $('.wh-companyShow').hover(function(){
				$('.companyShow').toggle();
			  })


			$('#auto-refresh').click(function() {
				var fn     = $(this),
						autostatus = $('#refreshStatus').val();

				$.getJSON("<?php echo smarty_function_get_url(array('rule'=>'/index/autorefesh'),$_smarty_tpl);?>
", {
					auto_status : autostatus
				}, function (data) {
					if (data.status == "error") {
						$.message(data.info, {
							title: '系统提示', icon: 'fail'
						});
						return;
					}
					if (data.status == "success") {
						if (autostatus == 1) {
							fn.addClass('cut');
							$('#refreshStatus').val(0);
							$('.autorefreshtime').show();
						} else {
							fn.removeClass('cut');
							$('#refreshStatus').val(1);
							$('.autorefreshtime').hide();
						}
						$.anchorMsg(data.info);
					}
				});
			});

			$("#auto-refresh").mouseover(function(){
				$("#show_alt").show();
			}).mouseout(function(){
				$("#show_alt").hide();
			});

			/*搜索*/
			$("#searchResume").click(function(){
                $('.md_index_resume_search').click();
			    var station = $("#searchStation").val();
				window.location.href = "<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/resumesearchnew/index/station-"+station;
			});

			/*刷新提示条*/
			$("#closeRefreshTip").click(function(){
				$(this).parent().hide();
				return false;
			});

			$('#calling_advert,#flexBannerList').on('click', 'a', function(e){
				var self = $(e.currentTarget);
				var advert_id = self.attr('adv_id');
				var area = self.attr('area');
				var data = {advert_id:advert_id,area:area};
				$.post('<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/ad/adverVisit/',data);
			});
		})

		function refreshAll(){
			$.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/refreshAll/",'data'=>"v='+Math.random()+'"),$_smarty_tpl);?>
', function(json) {
				if(json.error){
					$.message(json.error, { title: '系统提示', icon: 'fail' });
					return;
				}
				if(json.fail){
					$.message(json.failitem, { title: '系统提示', icon: 'fail' });
					return;
				}
				if(json.success){
					$("#divRefreshTime").html(json.refreshtime);
					$("#refreshTimeSpan").show();
					$.anchorMsg('刷新全部职位成功');
				}
				return;
			});
		}

		function startVip(){
			var title = '开通会员';
			$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/account/showApplyVipTipsV2/"),$_smarty_tpl);?>
',{title:title});
		}
		//升级维护公告start
		<?php if ($_smarty_tpl->getVariable('newBulletin')->value['is_show']==true){?>
		showNewBulletin();
		<?php }?>
		function showNewBulletin(){
			var thisid = "<?php echo $_smarty_tpl->getVariable('newBulletin')->value['id'];?>
";
			$.showModal('<?php echo smarty_function_get_url(array('rule'=>"/index/showNewBulletin/"),$_smarty_tpl);?>
?id='+thisid,{title:'升级维护公告',onclose:function(){closeShowNewBulletin()}});
		}
		function closeShowNewBulletin(){
			var thisid = "<?php echo $_smarty_tpl->getVariable('newBulletin')->value['id'];?>
";
			$.post('<?php echo smarty_function_get_url(array('rule'=>"/index/CloseNewBulletin"),$_smarty_tpl);?>
',{id:thisid});
		}
		//升级维护公告end

		<?php if (!empty($_smarty_tpl->getVariable('calling_advert_middle',null,true,false)->value)&&count($_smarty_tpl->getVariable('calling_advert_middle')->value)>1){?>
		var srolObj = $("#scrollTop").find("ul"),timeSrol = null;
		function getSrol(){
			timeSrol = setInterval(function(){
				srolObj.stop().animate({"margin-top":-52},200,function(){
					$(srolObj.find("li").eq(0)).appendTo(srolObj.css("margin-top","0px"));
				});
			},7000);
		}
		srolObj.hover(function(){
			timeSrol && clearInterval(timeSrol);
		},function(){
			getSrol();
		});
		getSrol();
		<?php }?>


		//招聘人
		var simuSselect = $("#simulation-select");
		simuSselect.hover(function(){
			$(this).find(".select-list").show();
		},function(){
			$(this).find(".select-list").hide();
		});
		//更多
		$("#jobMore").click(function(){
			$(this).prev("ul").toggleClass("js-over-height");
			return false;
		});
		function isPromotionShowLQ(){
			cookieutility.set('isShowPromotionShowLQ','true');
			window.location.href = '<?php echo smarty_function_get_url(array('rule'=>"/licencevalidate/index"),$_smarty_tpl);?>
';
		}
		function copyToClipboard(txt) {
			if (window.clipboardData) {
				window.clipboardData.clearData();
				clipboardData.setData("Text", txt);
				alert("复制成功！");
			} else if (navigator.userAgent.indexOf("Opera") != -1) {
				window.location = txt;
			} else if (window.netscape) {
				try {
					netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
				} catch (e) {
					alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将 'signed.applets.codebase_principal_support'设置为'true'");
				}
				var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
				if (!clip)
					return;
				var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
				if (!trans)
					return;
				trans.addDataFlavor("text/unicode");
				var str = new Object();
				var len = new Object();
				var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
				var copytext = txt;
				str.data = copytext;
				trans.setTransferData("text/unicode", str, copytext.length * 2);
				var clipid = Components.interfaces.nsIClipboard;
				if (!clip)
					 return false;
				clip.setData(trans, null, clipid.kGlobalClipboard);
				alert("复制成功！");
			}
		}
		
	</script>
	<script>

$('.closeTip').click(function () {
	$('.completeTip').hide();
	setCookie('isShowCompleteInfo',true);
});
function getCookie(name){
	var reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)"),
			arr = document.cookie.match(reg);
	if (arr != null) {
		return unescape(arr[2]);
	} else {
		return null;
	}
}
function setCookie(name, value){
	var str = name + "=" + escape(value);
	var ctuskytime=new Date();
	ctuskytime.setDate( ctuskytime.getDate()+1);
	ctuskytime.setHours(0);
	ctuskytime.setMinutes(0);
	ctuskytime.setSeconds(0);
	str += "; expires=" + ctuskytime.toGMTString();    // toGMTstring将时间转换成字符串
	//写入Cookie
	document.cookie = str;
}
var companyInfoPercent = "<?php echo $_smarty_tpl->getVariable('companyInfoPercent')->value;?>
";
var  show_light = "<?php echo $_smarty_tpl->getVariable('show_light2')->value;?>
";
var isShowCompleteInfo = getCookie('isShowCompleteInfo');
if(!isShowCompleteInfo) {
	if(companyInfoPercent < 70 || show_light != 1){
		$('.completeTip').css({
			'display': 'block'
		});
		if($('.completeTip').find('a').length == 1) {
			$('.completeTip').addClass('otherComplete');
		}
	}else{
		$('.completeTip').css({
			'display': 'none'
		});
	}

}else {
	$('.completeTip').css({
		'display': 'none'
	});
}
	$('.talent').click(function () {
		$(this).addClass('TalentCur').siblings('.talent').removeClass('TalentCur');
		var index = $(this).index('.talent');
		$('.md_index_remmonperson_more').eq(index).show().siblings('.md_index_remmonperson_more').hide();
		$('.firmRmendBox').eq(index).show().siblings('.firmRmendBox').hide();
	})
	</script>
	
	<script type="text/javascript">
		$(function(){
			checkCookie()
			
			function checkCookie() {
				if($.cookie('my_layerCookie')) {
					$(".my_newLayer").css("display",$.cookie("my_layerCookie"));
					$(".my_newMask").css("display",$.cookie("my_layerCookie"));
				}
			}
			
		})
	</script>
	
	<script type="text/javascript">
		<?php if ($_smarty_tpl->getVariable('need_alert_modify_bool')->value){?>
			$('.my_newMask,.my_newLayer').show()
		<?php }?>
	</script>

	<?php $_template = new Smarty_Internal_Template("common/showdialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('dialog_box',($_smarty_tpl->getVariable('dialog_box')->value)); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
	<?php $_template = new Smarty_Internal_Template("chat/chat.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</body>

</html>

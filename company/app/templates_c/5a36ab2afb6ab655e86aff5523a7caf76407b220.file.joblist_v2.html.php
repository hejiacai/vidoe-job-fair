<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:41:22
         compiled from "app\templates\joblist_v2.html" */ ?>
<?php /*%%SmartyHeaderCode:298345e7038d29efa65-88961059%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5a36ab2afb6ab655e86aff5523a7caf76407b220' => 
    array (
      0 => 'app\\templates\\joblist_v2.html',
      1 => 1573435394,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '298345e7038d29efa65-88961059',
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
<!--<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>"v2-reset.css"),$_smarty_tpl);?>
" />-->
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'job-manage.css'),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
" />
    <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'m_font_style.css'),$_smarty_tpl);?>
" />

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
<!--<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>"ui_dragsort.js"),$_smarty_tpl);?>
"></script>--><!--拖动插件-->
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.form.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_validate.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_dropdownlist.js'),$_smarty_tpl);?>
"></script><!--下拉模拟-->

<script type="text/javascript">
window.CONFIG = {
  HOST: '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
',
  COMBOPATH: '/js/v2/'
}
</script>
<!--<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'hbjs.js,jquery.min.js,util.js,class.js,shape.js,event.js,aspect.js,attribute.js,cookie.js'),$_smarty_tpl);?>
"></script>-->

    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'hbjs.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.min.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'util.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'class.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'shape.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'event.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'aspect.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'attribute.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'cookie.js'),$_smarty_tpl);?>
"></script>

<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'global.js'),$_smarty_tpl);?>
"></script>
</head>
<body id="body">
<style type="text/css">
.left{float:left}
div.content{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/index_secr1.jpg) repeat-y right 0 #fff}
section.secR{float:right;display:inline;width:250px}
.hd .tabT{height:40px;}
.hd .tabT .l{float:left;display:inline;}
.hd .tabT .l ul{float:left; display:inline;}
/*mj 修改*/
#auto-refresh{position: relative}
#auto-refresh em{font-size:12px;color:#666}
.alt{float:left;border:1px solid #fcde81;background-color: #fdf7d5;color:#666;font-size:12px;padding:3px 5px;margin: 5px 15px;position: relative}
.hd .tabT .alt i{display: inline-block;width:5px;height: 7px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/v2/job/ma-icon.jpg) 0 -55px no-repeat;position: absolute;left:-5px;top:8px}
.hd .tabT .alt{color:#ec8601}
.hg1 .alt{line-height: 22px;padding:10px;position: absolute;bottom:27px;left:0px;margin:0px;display: none;height: 110px;_bottom:auto;_top:-140px}
.hg1 .alt i{display:block;width:9px;height: 6px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/v2/job/ma-icon.jpg) -15px -56px no-repeat;position: absolute;left:85px;bottom:-6px;_bottom:-11px}
.hd .tabT .l ul li{float:left;display:inline;margin:0 10px 0 0; height:38px; line-height:38px;}
.hd .tabT .l ul li a{display:inline-block;height:38px;line-height:38px;padding:0 15px;font-size:14px; position:relative; z-index:1;zoom:1;background:#f1f1f1;border:1px solid #e4e4e4;color:#4d4d4d;}
.hd .tabT .l ul li a:hover{background:#ececec;color:#333;}
.hd .tabT .l ul li.cu a,.hd .tabT .l ul li.cu a:hover{color:#333;background:#fff;border:1px solid #e4e4e4;border-bottom:1px solid #fff;position:relative; z-index:10;font-weight:bold}
.oper-box{font-size:12px;padding-top:15px;position: relative;zoom:1;z-index:4}
.oper-box .all{float:left;margin-top: 7px}
.oper-box .all input{margin-right: 5px;vertical-align: middle}
.sort-select,.select{float:left;margin-left: 10px!important}
.sort-select .tit,.select .tit{background-color: #f2f2f2;padding:0px 12px;margin:0px;cursor: pointer;height: 30px;line-height: 30px;float:left; position: relative;}
.select .tit{padding:0px}
.select a{display:inline-block;padding:0 12px;height: 28px}
.sort-select .tit i,.select .tit i{font-size:12px;color:#b3b3b3; position: absolute;top: 8px;right: 5px;}
.sort-select a,.select a{color:#666}
.sort-select ul,.select ul{display: none}
.show-sort{position: relative;zoom:1;z-index: 2}
.show-sort .tit{background: #fff;border:1px solid #bfbfbf;border-bottom:0px;padding:0 11px;height:26px;line-height: 26px}
.show-sort ul{display:inline-block;position: absolute;top:26px;left:0px;border:1px solid #bfbfbf;border-top:0px;background-color: #fff;padding: 5px 0;width:100%}
.show-sort ul li{cursor: pointer}
.show-sort ul li a{display: block;padding:2px 10px;background-color: #fff;#position: relative;#zoom:1}
.show-sort ul li a:hover{background-color:#f2f2f2}
.search{border:1px solid #ddd;float:right;width:165px;height: 28px;position: relative;background-color: #fff; margin-top: 0px;}
.search span{position: absolute;top:6px;left:5px;color:#666}
.search input{float:left;width:129px;height: 20px;border:0px;padding:3px}
.search button{width:27px;height:26px;float:right;background: none;border:0px;color:#e1e1e1;font-size:14px}
.icon-ji,.icon-ding{width:16px;height: 16px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/v2/job/ma-icon.jpg) -26px 0 no-repeat;display: inline-block;vertical-align: middle;margin-left: 3px}
.icon-ding{background-position: 0 0}
.icon-spread{width:30px;height: 16px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/v2/job/spread.jpg) 0px 0 no-repeat;display: inline-block;vertical-align: middle;margin-left: 3px}
.hg1 p.refresh,.hg1 p.release{margin: 10px 10px 5px 0}
.hg1 p.refresh a,.hg1 p.release a{display: block;height: 46px;color:#fff;font-size:16px;line-height: 46px;font-weight:bold;text-align: center;margin-left: 0px}
.hg1 p.refresh a i{font-weight:normal;vertical-align: middle;margin-right: 5px}
.hg1 p.refresh a{background-color: #7cb1d6}
.hg1 p.release{padding-bottom: 20px}
.hg1 p.release a{background-color: #3d85b8}
.hg1 p.refresh a:hover{background-color: #6d9cbd}
.hg1 p.release a:hover{background-color: #34729e}
.ico-status{display: inline-block;width:42px;height: 19px;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/v2/job/ma-icon.jpg) -52px -26px no-repeat;vertical-align: middle;cursor: pointer}
.open{background-position: 0 -26px}
.bd .lst .red{color:#ed2801}
.hg1 .red{color:#ff766f}
/*/*/
.hd .tabT .l .tipLay{top:6px;left:0;}
.hd .tabT .l .tipLayTxt{line-height:23px;background:#fffce3;border:1px solid #fad5a8;color:#d78727}
.hd .tabT .l .tipLay .tipArr{top:9px;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/common/contrl.gif) no-repeat 0 -5px;}
.hd .tabT .r{float:right;display:inline;margin:5px 0 0;width:275px;}
.hd .tabT .r a.btn3{float:left; display:inline;}
.hd .tabT .r .liSchBox{background:none repeat scroll 0 0 #FFFFFF; border:1px solid #e3e3e3; display:inline; float:right; position:relative; z-index:1;}
.hd .tabT .r .liSchBox .txtLabel{left:5px;position:absolute;top:0; color:#999999;cursor:text;}
.hd .tabT .r .liSchBox input{background:none repeat scroll 0 0 transparent; border:0 none;float:left; font-size:12px; height:25px;line-height: 25px;margin:0 5px; width:120px;}
.hd .tabT .r .liSchBox a.hbFntWes{float:left; font-size:14px; background:#f6f6f6; color:#999; height:25px; line-height:25px; padding:0 7px; zoom:1;}
.hd .tabT .r .liSchBox a.hbFntWes:hover{background:#f1f1f1;}
/*mj 修改*/
.bd{border-top:1px solid #e4e4e4;zoom:1;position:relative; z-index:1;top:-1px;}
/*/*/
.bd .lst{padding:10px 0;zoom:1; position:relative; z-index:3;}
.bd .lst .table{font-size:12px;width:100%}
.bd .lst .table .formChb input.chb{margin:0;}
.lst .table tr th{font-weight:normal; color:#999; border-bottom:1px solid #dadada;padding:8px 0px; zoom:1; text-align:left; height:20px; line-height:20px;}
.lst .table tr td{border-bottom:1px solid #f1f1f1; padding:8px 0px; zoom:1; color:#424242; text-align:left; height:20px; line-height:20px;}
.lst .table .wid220{width:220px; overflow:hidden;white-space:nowrap;text-overflow:ellipsis;height:20px; }
.lst .table .wid300{width:300px;}
.lst .table .wid180{width:180px;}
.lst .table .wid240{width:240px;}
.lst .table .wid110{width:110px;}
.lst .table .wid100{width:100px;}
.lst .table .wid120{width:120px;}
.lst .table .wid90{width:90px;}
.lst .table .wid80{width:65px;}
.lst .table .wid160{width:170px;}
.lst .table .wid140{width:140px;}
.lst .table .wid30{width:30px;}
.lst .table .lstBox span.tipTxt a{color:#999; margin:0 2px; #margin:0 1px;}
.lst .table .lstBox .tipLoadTxt{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/common/loading.gif) no-repeat 4px 4px;}
.lst .table .lstBox tr.hov{background:#fffdf3;}
.lst .table .lstBox b{font-weight:normal; background:#FFFCE3; border:1px solid #FAD5A8; color:#ff3b3b; padding:2px 4px; margin-left:5px;white-space:nowrap}
.lst .table .lstBox tr.hov span.tipTxt a{color:#3d84b8;}
.lst .table .lstBox tr.hov span.tipTxt a:hover{color:#0af;}
.lst .table .lstBox tr.hov a.jobName{color:#3d84b8;}
.lst .table .lstBox tr.hov a.jobName:hover{color:#0af;}
.lst .table .lstBox tr.hov span.tipTxt .tipTxtBox a{color:#424242;}
.lst .table .lstBox tr.hov span.tipTxt .tipTxtBox a:hover{background:#f2f2f2; color:#424242;}

.lst .table .lstBox span.tipTxt a:hover,.lst .table .lstBox tr.hov span.tipTxt a:hover{color: #3d84b8!important}
.lst1 .table .lstBox a.jobName{line-height:20px; font-size:12px; font-weight:bold;}/*mj 12*/
.lst1 .table .lstBox a.aud{cursor:text; color:#424242; font-weight:bold; font-size:14px;}
.lst1 .table .lstBox span.tipTxt{position:relative;z-index: 3}
.lst1 .table .lstBox span.tipTxt i.hbFntWes{font-size:14px; margin-left:3px;}
.lst1 .table .lstBox span.tipTxt .tipTxtBox{position:absolute; right:-10px;top:15px;width:100px; background:#fff; padding:5px 0; zoom:1; border:1px solid #dadada;z-index: 2}
.lst1 .table .lstBox span.tipTxt .tipTxtBox a{display:block; padding:0 10px; zoom:1; line-height:22px; color:#424242; margin:0;}
.lst1 .table .lstBox span.rec a.unRead{font-size:12px;color:#fff;background:#aaa; margin-left:2px;padding:0 4px;border-radius:5px;height:18px;line-height:18px;}
.lst1 .table .lstBox span.rec a.unRead:hover{background:#999;}
.lst2 .table .lstBox a.jobName{font-size:14px; font-weight:bold; color:#666;}
.lst1 .table .lstBox em.num{font-size:12px;color:#fff;margin:0 5px;background:#ed7066;padding:0 3px;margin-left:5px;border-radius:5px;height:16px;line-height:16px; cursor:pointer;}
.allBtn{margin:15px 0 0 5px;}

.remTxt{height:30px; line-height:30px; background:#fffce3; border:1px solid #fad5a8; font-size:12px; margin-top:10px; padding-left:10px;}
.remTxt a{margin-left:5px;}

.section .noData{font-size:18px;margin:85px 0;}
.section .noData p{margin-bottom:20px;}
div.not-member {text-align: left; padding: 20px;}
.hb_ui_dialog .ui_dialog_title {text-align: left;}

/*右侧*/
.hg1{padding:20px 10px 0 20px;zoom:1;font-family:"宋体";border-bottom:1px solid #DEDEDE}/*mj 修改*/

.hg1 p.comName{font-size:12px; font-weight:bold; margin-bottom:5px; line-height:18px;}
.hg1 span{font-size:12px; display:block; line-height:22px;}
.hg1 span em{color:#666;}
.hg1 span .expTxt i.hbFntWes{font-size:14px; margin-left:5px; cursor:pointer; color:#3d84b8;}
.hg1 span .expTxt{position:relative; z-index:1; height:28px; line-height:28px; display:inline;}
.hg1 span .expTxt .expBox{position:absolute; bottom:24px;left:-90px; background:#fffbe8; border:1px solid #ffe6bb; width:165px; padding:0 10px; zoom:1; line-height:26px; z-index:999;}
.hg1 span .expTxt .expBox em{ background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/common/common.gif) no-repeat -174px -12px; height:10px; width:19px; font-size:0; position:absolute; left:90px; bottom:-10px;}
.hg1 span .expTxt .expBox b{margin:0 3px;}
.hg1 span .expTxt .expBox p{line-height:20px; padding:5px 0; zoom:1;}
.hg1 p{font-size:12px; line-height:24px;_line-height:22px}/*mj 修改*/
.hg1 p em{color:#666;}
.hg1 p a{margin-left:10px;}
.hg1 p i.time{color:#666; font-family:'Times New Roman',Helvetica,sans-serif;}
.hg1 i.ico{vertical-align:middle;}
.hg1 p a.btnsF14{padding:0 75px; zoom:1; margin:8px 0 0 0; width:208px; padding:0; text-align:center;}

.secR h3{font-weight:bold;}
.hg2{border-bottom:1px solid #dedede;border-top:1px solid #fff}
.hg2 h3{margin-bottom:10px; font-size:14px; color:#999; font-weight:normal;}
.hg2 dl{height:85px;}
.hg2 dl dt{width:61px;height:76px;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/common/user100_125.jpg) no-repeat;float:left;margin:5px 0 0;}
.hg2 dl dt img{width:61px; height:76px;}
.hg2 dl dd{float:left;display:inline;margin:5px 0 0 10px}
.hg2 dl dd p{font-size:12px; color:#999;}
.hg2 dl dd p.name{color:#424242;}
.hg2 dl dd p b{font-weight:normal;}

/*招聘会*/
.hg3{border-top:1px solid #fff;}
.hg3 h3{margin-bottom:15px;}
.hg3 h3 a{float:right; font-size:12px; font-weight:normal;}
.hg3Tab{}
.hg3TabT{margin-bottom:15px;}
.hg3TabT ul li{float:left;display:inline;width:55px;height:50px;border:1px solid #dadada; text-align:center;margin:0 10px 0 0; cursor:pointer; position:relative; z-index:1;}
.hg3TabT ul li p.week{background:#e6eefb;color:#55738b;height:30px;line-height:30px;}
.hg3TabT ul li p.date{background:#fff;color:#666;font-size:12px;height:20px;line-height:20px;}
.hg3TabT ul li b{ position:absolute;top:50px;left:50%;margin-left:-6px;background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/c.gif) no-repeat -430px 0;width:12px;height:6px;display:none;}
.hg3TabT ul li.cu b{display:block;}
.hg3TabT ul li.cu p.week{background:#54728c;color:#fff;}
.hg3TabCon{font-size:12px;}
.hg3TabCon dl dt{margin-bottom:5px;}
.hg3TabCon dl dd p{color:#999;margin-bottom:10px;}
.hg3TabCon dl dd p a.btn3{margin:0;}

.RefreshTip{width:530px;}
.RefreshTip p{test-align:left;line-height:26px;}
.RefreshTip p.tit{padding-bottom:5px;line-height:28px; font-weight:bold;}
.RefreshTip div{margin:20px 0 0 190px;}

/*新版职位列表*/
section.secL{display:inline;width:1000px;}
.newCtantBg{ overflow:hidden; padding:0px; border-top:1px solid #fff;}
.newConsultant{ overflow:hidden; border-bottom:1px solid #DEDEDE; padding:10px 8px;}
.newConsultant li{ float:left;}
.newConsultant li a{ display:block; float:right; margin:25px 0 0 5px;}
.newConsultant li p{ color:#424242; line-height:24px;}
.newConsultant li p em{ color:#999; padding-left:10px;}
div.content{background:#fff}
.lst .table tr td span img{ display:inline-block;width:4px;height:4px; vertical-align:top; margin-left:2px;}
.lst .table tr td span em.widDian{ display:inline-block;width:4px;height:4px; background: #fff;border:none; vertical-align:top; margin-left:2px;}
/*==开关==*/
.openBtn, .openBtnDisable{ display:block; float:left;width:60px; height:30px; background:url("<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/open.png") -60px 0px no-repeat;}
.openBtncut{ background-position:0px 0px}

#show_alt {
  top: 19px;
  width: 290px;
  right: 82px;
  line-height: 22px;
  margin: 0;
  padding:15px 20px 10px 10px;
  position: absolute;
  z-index:5;
  background-color: #fdf7d5;
  border: 1px solid #fcde81;
}
#show_alt i {
  background: url("<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon22.png") no-repeat;
  bottom: -6px;
  display: block;
  height: 6px;
  left: 74px;
  position: absolute;
  width: 10px;
}
.showClose{ display:block;width:14px; height:14px; background:url("<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/datePopimg04.png") 0 0 no-repeat; position:absolute; right:6px; top:6px;}
.hd .tabT .l{width:100%;}
.jobListCont{ float:right; height:40px;}
.jobListCont .jobRefresh{ display:block; float:left;width:200px; height:30px; background:#00bab1; color:#fff; line-height:30px; margin-right:10px; text-align:center; border-radius:2px; margin-top: 2px;}
.jobListCont .jobRefresh:hover{ background:#2297a5;}
.jobListCont .firmRfGray{ display:block; float:left;width:200px; height:35px;background:#dbdbdb; color:#666; line-height:35px; margin-right:10px; text-align:center; border-radius:2px;}
.jobListCont div{ float:left;}
.jobListCont div p{ color:#333; line-height:18px;width: 168px;}
.jobListCont div p a,.jobListCont div p span{ display:inline-block; vertical-align:middle;}
.jobListCont div p a{width: 26px;
  height: 14px;
  background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon08.png) no-repeat;
  cursor: pointer;
  margin: 0 4px;}
  .jobListCont div p a.cut{ background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon08hover.png) no-repeat}
  #show_altx {
  top: 50px;
  width: 290px;
  left: 50%;
  margin-left:216px;
  line-height: 22px;
  padding: 10px;
  position: absolute;
  background-color: #fdf7d5;
  border: 1px solid #fcde81;
  text-align:left;
}
#show_altx i {
  background: url("<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon22.png") no-repeat;
  bottom: -6px;
  display: block;
  height: 6px;
  left: 155px;
  position: absolute;
  width: 10px;
}
.promiseTent{ overflow:hidden;width:220px; position:absolute; margin: -23px 0 0 78px;#margin:-4px 0 0 -5px; display: none;}
.promiseTent img{ display:block; position:absolute; margin:10px 0 0 1px;#margin:10px 0 0 -2px;}
.pTentx{width:196px; padding:8px; overflow:hidden; border:1px solid #ffedcc; background:#fdf7d5; border-radius:4px; float:right;}
.pTentx h2{ color:#f18635; font-size:14px; padding-bottom:6px;}
.pTentx p{ line-height:22px; color:#545454;}
.pTentx a{ display:block; float:right; color:#0862a5;}
.ptentBtn{ color: #ed6800;border: 1px solid #fff;border-radius:2px;}
.ptentBtnDft{ cursor: default;}
.ptentBtnDft:hover{color:#3D84B8;}
.SperateLine td{ font-size: 14px;background:url("<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/job/newjob/tipsIcon.png") 8px center no-repeat #fdf7d5;border:1px solid #ffedcc; text-indent: 29px; color:#ed6600 !important;}
.ptentBtnD{padding: 2px 3px;}
.ptentBtnD:hover{color:#ed6600;border: solid 1px #ed6600;}

.tipText a{cursor: default;}
.lst .table tr.hov td span a{color: #999 !important;}

.sort-select ul li a {white-space: nowrap;width: 110px;text-overflow: ellipsis;overflow: hidden;}
.lst .table .lstBox b.gen-binding {background: #ff9b00;border:0px;color:#feffff;border-radius: 2px}

.jp-spread span.clearfix {margin-left: 5px;}
.refresh_dialog .not-member {padding: 5px;font-size: 14px;}
.openBtn, .openBtncut{width:53px;height:31px;overflow: hidden}
.openBtn{background:url("<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/kai.jpg")}
.openBtncut{background:url("<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/guan.jpg")}
#js_click_import a:hover{ color:#ff3948;}
.hb_ui_dialog .ui_dialog_title{ font-size: 16px;}
/*代运营*/
/*.daiyunying{display: inline-block;}*/
/*.daiyunying img{padding-left: 0px;vertical-align: -5px;}*/
/*.daiyunying{position: relative;}*/
/*.daiyunying p{display: none;position: absolute;padding-left: 4px;height: auto;line-height: 24px; margin-top: 5px; left:0;top: 100%;width: 176px;border: 1px solid #f6e5a6;color: #604e29;background: #fffadd;font-size: 12px;}*/
/*.daiyunying p img{position: absolute;  top: -6px;  left: 10px;  margin-top: 0;transform: rotate(180deg);  -ms-transform: rotate(180deg);  -moz-transform: rotate(180deg);  -webkit-transform: rotate(180deg);  -o-transform: rotate(180deg);}*/
/*.daiyunying:hover p{display: inline-block;}*/

/*确认关键字弹窗样式*/
.keyword_dialog .keyword_confirm{
	font-size: 14px;
	height:50px;
	padding:15px 0;
	line-height: 2em;
	text-align: left;
	text-indent: 2em;
}
.postRefreshBtn,.jobRefresh{
	display: block;
	float: left;
    width: 100px;
    background: #66bce4;
    height: 30px;
    color: #fff;
    line-height: 30px;
    text-align: center;
    font-size: 14px;
    border-radius: 2px;
    margin-left: 10px;
    font-family: "微软雅黑";
}
.jobRefresh{ background: #00bab1;}
.postRefreshBtn i{ display: inline-block; vertical-align: -1px; margin-right: 5px;}
.postRefreshBtn:hover,.jobRefresh:hover{ background: #22a6e3; color: #fff;}
.jobRefresh:hover{background: #00d4c9; color: #fff;}
#refreshTimeSpan{ display: block; float: left; margin-left: 10px; line-height: 30px;}

</style>
<?php $_template = new Smarty_Internal_Template("new_header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par',"全职招聘");$_template->assign('cur',"职位管理"); echo $_template->getRenderedTemplate();?><?php unset($_template);?> 
<div class="alt" id="show_altx" style="display: none;">
    <i></i>开启后，系统会在15天内每天10:00-20:00随机挑选一个时间为您刷新1次所有在招职位。建议您在不能登录网站的情况下开启，手动刷新效果更佳。
</div>
<div class="content" id="content">
    <!---20151208 微信二维码 start -->
    <style>
      .content{position: relative}
      .ewmBox{display: none;position: absolute;right:-110px;top:0px;width:100px;}
      /*.ewmBox img{border:1px solid #e9e9e9;margin-bottom: 5px;width: 118px;height: 118px;}*/
      .ewmBox a{display: inline-block;width:24px;height:24px;background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/weixin/close2.jpg) no-repeat;position: absolute;top:0px;right:0px}
      .ewmBox a:hover{background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/weixin/close2_hover.jpg) no-repeat}
      .myjob-span{padding: 8px 15px 0 20px;margin:0px;display:block;float:left;position: relative;line-height: 25px}
      .myjob-span input{position: absolute;left:0px;top:15px;#top:10px;margin:0;padding:0}


    /*edit 2018/12/26*/
.lst .table .wid30 {
    position: relative;
}
     .table tr .share-box {
         position: absolute;
         top: 10px;
         border: 2px solid #cdcdcd;
         display: none;
         line-height: 20px;
         height: 20px;
         padding: 0 3px 0 24px;
         background: #fff url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/share.png) no-repeat;
         background-size: 16px 16px;
         background-position: 4px 2px;
         z-index: 10;
         right: -230px;
         cursor: pointer;
     }
.getPicDialog {
    height: 650px !important;
}
.hb_ui_dialog .ui_dialog_message {
    padding: 25px 15px;
    font-size: 14px;
}
    </style>
    <!--<div class="ewmBox" id="ewmBox">
        <a href="" class="close"></a>
        <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/acompany_joblist_ewm.jpg" id="jewm"/>
        <p style="font-size: 14px;"> <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/weixin.jpg" style="width: auto; height: auto; border: 0px none; margin: 0px; vertical-align: middle;">关注汇博招聘<br />随时随地刷新职位</p>
    </div>-->
    <div class="ewmBox" id="ewmBox1" style="top:0px;display: block;padding-bottom: 10px">
        <!--<a href="" class="close"></a>-->
        <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/company_code.jpg"/>
        <!--<p style="font-size: 14px;color:#4e74d9">汇博企业版 APP<br /><span style="font-size: 16px">随时处理简历<br />提高招聘效率</span></p>-->
    </div>
    <!---20151208 微信二维码 end -->

  <section class="secL">
      <hgroup style="min-height:600px;overflow:visible">
            <div class="hd" id="tab">
                <div class="tabT" id="TabT">
                    <div class="l">
                        <ul>
                            <li class="cu"><a class="md_inmrecruit" href="<?php echo smarty_function_get_url(array('rule'=>"/index/JobList/"),$_smarty_tpl);?>
">招聘中（<em id="jobListUseCount"><?php echo $_smarty_tpl->getVariable('use_job_count')->value;?>
</em>）<b></b></a></li>
                            <li><a class="md_endmrecruit" href="<?php echo smarty_function_get_url(array('rule'=>"/index/JobList/",'data'=>"status=".($_smarty_tpl->getVariable('stop_job_status')->value)),$_smarty_tpl);?>
">已结束招聘（<em id="jobListStopCount"><?php echo $_smarty_tpl->getVariable('stop_job_count')->value;?>
</em>）<b></b></a></li>
                        </ul>
                        <?php if ($_smarty_tpl->getVariable('job_over_time_count')->value>0){?>
                        <p class="alt"><i></i><?php echo $_smarty_tpl->getVariable('job_over_time_count')->value;?>
个岗位刚刚停招</p>
                        <?php }?>
                        <div class="jobListCont">
                            <label class="myjob-span clearfix"><input class="md_myjob" type="checkbox" name="myjob" <?php if ($_smarty_tpl->getVariable('is_myjob')->value){?>checked<?php }?> />我的职位</label>
                            <a href="/partjob/addjobselect/" class="jobRefresh md_pubjob" style="width:100px;background:#66bce4">发布职位</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bd" id="TabC">
                <?php if (!empty($_smarty_tpl->getVariable('job_list',null,true,false)->value)&&count($_smarty_tpl->getVariable('job_list')->value)!=0){?>
                <div class="alt" id="show_alt" style="display:none">
                    <i></i>自动过滤简历开启后，将自动过滤掉不匹配职位要求的简历哟，大大节省您的工作时间，还不快去试试！<a href="javascript:;"  class="showClose"></a>
                </div>
                <?php }?>
                <p class="tabCon">
                    <div class="oper-box">
                        <label class="all"><input id="upAllSelect" type="checkbox" class="chb" />全选</label>

                        <?php if ($_smarty_tpl->getVariable('companyresources')->value['cq_is_batch_refresh']){?>
                        <a href="<?php echo smarty_function_get_url(array('rule'=>'/autorefresh/index/'),$_smarty_tpl);?>
" target="_blank" class="postRefreshBtn md_dsrefresh"><i class="icon-details-time"></i>定时刷新</a>
                            <a href="javascript:;" class="jobRefresh md_muchrefresh" id="refreshAll" style="width:100px">
                                <i class="icon-manage-refresh"></i>
                                批量刷新
                            </a>
                            <?php if (!empty($_smarty_tpl->getVariable('job_list',null,true,false)->value)&&count($_smarty_tpl->getVariable('job_list')->value)!=0){?>
                            <span id="refreshTimeSpan" <?php if (!isset($_smarty_tpl->getVariable('refresh_time',null,true,false)->value)){?>style="display:none"<?php }?>>最近刷新时间:<i id="divRefreshTime"><?php echo $_smarty_tpl->getVariable('refresh_time')->value;?>
</i></span>
                            <?php }?>
                            <?php }?>
                        <form class="search" method="get" id="formSearchMyJob" action='<?php echo smarty_function_get_url(array('rule'=>"/index/JobList/",'data'=>"status=".($_smarty_tpl->getVariable('use_job_status')->value)),$_smarty_tpl);?>
'>
                          <span <?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->getVariable('search_keyword')->value)){?>style="display: none"<?php }?>>搜索职位</span>
                          <input type="text" value="<?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->getVariable('search_keyword')->value)){?><?php echo $_smarty_tpl->getVariable('search_keyword')->value;?>
<?php }?>" name="txtStation"  id="liSchText" />
                          <input type="hidden" value="<?php echo $_smarty_tpl->getVariable('now_account')->value['company_id'];?>
" name="account_id" />
                            <button class="hbFntWes md_search" type="submit">&#xf002;</button>
                        </form>
                        <div class="sort-select" style="#width:140px; float:right; margin:0px 10px 0 0;">
                          <p class="tit"><span>批量操作</span><i class="hbFntWes">&#xf0d7;</i></p>
                          <ul style="width:135px">
                            <li><a class="md_much_alterphone" href="javascript:void(0)" onclick="joblist.updatelinkway();">修改联系电话</a></li>
                            <li><a class="md_much_altermail" href="javascript:void(0)" onclick="joblist.batchupdatemail();">修改邮箱</a></li>
                            <li><a class="md_much_dealy" href="javascript:void(0)" onclick="joblist.delayChooseJobs();">延长有效期</a></li>
                            <li><a  href="javascript:void(0)" data-onclick="joblist.batchstopJob();" class="stopAllJob md_much_close" >关闭</a></li>
                            <li><a class="md_much_order" href="javascript:void(0)" onclick="joblist.updateSort();">批量排序</a></li>
                            <li><a class="md_much_alterlevel" href="javascript:void(0)" onclick="joblist.updateLevel();">批量更改岗位级别</a></li>
                            <li><a class="md_much_jobsalary" href="javascript:void(0)" onclick="banner_on()">批量设置职位薪资</a></li>
                            <li><a class="md_much_onlychat" href="javascript:void(0)" onclick="onlinetalk_on()">批量允许在线沟通</a></li>
                          </ul>
                        </div>
                        <?php if ($_smarty_tpl->getVariable('hr_type')->value=="hr_main"){?>
                        <div class="sort-select com-select" style="#width:140px; float:right; margin-right:10px;width:140px;">
                            <p class="tit" style="overflow: hidden;width:116px;"><span><?php if (empty($_smarty_tpl->getVariable('now_account',null,true,false)->value)){?>所有公司<?php }elseif($_smarty_tpl->getVariable('now_account')->value['company_shortname']){?><?php echo $_smarty_tpl->getVariable('now_account')->value['company_shortname'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('now_account')->value['company_name'];?>
<?php }?></span><i class="hbFntWes">&#xf0d7;</i></p>
                            <ul style="width:138px">
                                <li><a href="javascript:void(0)" class="com-selected" data-company="">所有公司</a></li>
                                <?php  $_smarty_tpl->tpl_vars['account'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('accounts')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['account']->key => $_smarty_tpl->tpl_vars['account']->value){
?>
                                <li><a href="javascript:void(0)" class="com-selected" data-company="<?php echo $_smarty_tpl->tpl_vars['account']->value['company_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['account']->value['company_name_display'];?>
</a></li>
                                <?php }} ?>
                            </ul>
                        </div>
                        <?php }?>
                    <div class="clear"></div>
                  </div>
                <style>
                    #importP a{padding:0px !important; height:auto !important; line-height: inherit !important; float: none !important; display: inline-block; overflow:visible;}
                </style>
                  <p style="font-size: 14px;color:#ff9b00;font-weight: bold;font-family: '微软雅黑';margin-top:20px">没在招聘的职位请及时关闭，以免拉低企业整体简历回复率</p>

                    <?php if ($_smarty_tpl->getVariable('importData')->value['is_import']==1&&(empty($_smarty_tpl->getVariable('job_list',null,true,false)->value)||count($_smarty_tpl->getVariable('job_list')->value)==0)){?>
                    <p style="padding: 5px 10px;border:1px solid #fbaba9;background: #fff2ef;color: #444;font-size: 12px;width:940px; margin-top:10px;" id="importP">
                        <a id="js_click_import" class="md_onekeyimprot" href="javascript:void(0);" style="color: #d5535d;text-decoration: underline;float: right !important;">一键导入汇博</a>
                        <?php echo $_smarty_tpl->getVariable('importData')->value['stationstring'];?>

                    </p>
                    <?php }?>


                    <div class="lst lst1" id="lst1" <?php if (empty($_smarty_tpl->getVariable('job_list',null,true,false)->value)||count($_smarty_tpl->getVariable('job_list')->value)==0){?>style="display:none;"<?php }?>>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="wid30"></th>
                                    <th class="wid180">职位名称</th>
                                    <th class="wid90">招聘人</th>
                                    <th class="wid100" style="text-align:center">收到简历</th>
                                    <th class="wid80" style="text-align:center">未读</th>
                                    <th class="wid80" style="text-align:center">待定</th>
                                    <th class="wid100" style="text-align:center">待回复</th>
                                    <th class="wid80" style="text-align:center">人才匹配</th> 
                                    <th class="wid90" style="text-align:center">承诺回复</th>
                                    <th class="wid80">简历过滤</th>
                                    <th class="wid80">过期时间</th>
                                    <th class="wid80">刷新时间</th>
                                    <th class="wid240">操作</th>
                                </tr>
                            </thead>
                            <tbody class="lstBox" id="lstBox">
                            <?php if ($_smarty_tpl->getVariable('importData')->value['is_import']==1){?>
                                <tr>
                                   <td colspan="13">
                                       <p style="padding: 5px 10px;border:1px solid #d66564;background: #fff2ef;color: #444;font-size: 12px;width:940px;">
                                       <a id="js_click_import" class="md_onekeyimprot" href="javascript:void(0);" style="color: #d5535d;text-decoration: underline;float: right">一键导入汇博</a>
                                           <?php echo $_smarty_tpl->getVariable('importData')->value['stationstring'];?>

                                   </p></td>
                                </tr>
                            <?php }?>
                              <?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['job']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['name'] = 'job';
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['loop'] = is_array($_loop=$_smarty_tpl->getVariable('job_list')->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['job']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['job']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['job']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['job']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['job']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['job']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['job']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['job']['total']);
?>
                                <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['promiseStop']=='1'&&$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['re_apply_type']!='0'){?>
                                <tr id="li<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" data-id="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" data-username="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['account_user_name'];?>
" data-cando="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['can_do'];?>
" data-jobresource="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_account_resource_type'];?>
">
                                    <td class="wid30">
                                        <span class="formChb">
                                            <input value="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" type="checkbox" name="chkjob" class="chb" <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']==4||$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['promiseStop']==1){?>disabled="true"<?php }?>>
                                        </span>
                                        <?php if ($_smarty_tpl->getVariable('com_level')->value>0&&$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']!=4&&$_smarty_tpl->getVariable('audit_msg')->value=='认证通过'){?>
                                            <div class="share-box" data-id="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
">
												分享（<a class="share-link" href="javascript:viod(0);">海报</a>/<a class="share-h5" href='<?php echo smarty_function_get_url(array('rule'=>"/index/TemplateStatisticalByH5",'data'=>"type=2"),$_smarty_tpl);?>
' target="_blank">H5页面</a>）
											</div>
                                        <?php }?>
                                    </td>

                                    <td class="wid180">
                                        <p>
                                            <a title="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['station'];?>
" class="<?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']==4){?>aud<?php }else{ ?>jobName<?php }?>" <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']==4){?>href="javascript:void(0);"<?php }else{ ?>href="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_link'];?>
" target="_blank"<?php }?> style="color:red;"><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['sub_station'];?>
</a>
                                            <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']==4){?>
                                            <b title="我们会在1个工作日内完成审核，在此期间求职者将无法看到该职位">审核中</b>
                                            <?php }else{ ?>
                                            <?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['urgent_mark'])&&$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['urgent_mark']==true){?>
                                            <a onclick="joblist.checkCanUrgentJob(<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
)" class="icon-ji" href="javascript:void(0);" title="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['urgent_end_date'];?>
截止"></a>
                                            <?php }?>
                                            <?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['top_mark'])&&$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['top_mark']==true){?>
                                            <a href="javascript:void(0);" onclick="joblist.checkCanTopJob(<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
)" class="icon-ding"  title="已在<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['top_count'];?>
个关键词列表中置顶"></a><?php }?>
                                            <?php }?>
                                            <?php if (in_array($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'],$_smarty_tpl->getVariable('EffectSpreadJob_ids')->value)){?>
                                            <a href="javascript:void(0);" class="icon-spread" ></a>
                                            <?php }?>
                                        </p>
                                        <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['generation_bidding']){?>
                                        <p><?php echo $_smarty_tpl->getVariable('accounts')->value[$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['company_id']]['company_name_display'];?>
</p>
                                        <?php }?>
                                    </td>
                                    <!--发布人-->
                                    <td><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['account_user_name'];?>
</td>
                                    <!--收到的简历-->
                                    <td class="wid80" style="text-align:center">
                                        <span class="rec">
                                            <em style="color:#666"><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['applyCount'];?>
</em>
                                        </span>
                                    </td>
                                    <!--未读的简历-->
                                    <td class="wid80" style="text-align:center">
                                        <span class="rec">
                                            <a <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['applyNotReadCount']>0){?>target='_blank' href='<?php echo smarty_function_get_url(array('rule'=>"/apply/Index/",'data'=>"job_id=".($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'])."&child_status=2"),$_smarty_tpl);?>
'<?php }else{ ?>href='javascript:void(0);'<?php }?>><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['applyNotReadCount'];?>
</a>
                                            <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['applyNotReadCount']>0){?>
                                            <img style="display:none" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/reddian.png">
                                            <?php }?>
                                        </span>
                                    </td>
                                    <!--待定的简历-->
                                    <td class="wid80" style="text-align:center">
                                      <span class="rec">
                                        <a <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['wait_deal_num']>0){?> target='_blank' href='<?php echo smarty_function_get_url(array('rule'=>"/apply/Index/",'data'=>"job_id=".($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'])."&child_status=10"),$_smarty_tpl);?>
' <?php }else{ ?> href='javascript:void(0);'<?php }?> >
                                          <?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['wait_deal_num'];?>

                                          </a>
                                      </span>
                                    </td>
                                    <!--待回复-->
                                    <td class="wid100" style="text-align:center">
                                        <span class="rec">
                                            <a <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['no_reply_num']>0){?>target='_blank' href='<?php echo smarty_function_get_url(array('rule'=>"/apply/Index/",'data'=>"job_id=".($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'])),$_smarty_tpl);?>
'<?php }else{ ?>href='javascript:void(0);'<?php }?>><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['no_reply_num'];?>
</a>
                                            <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['no_reply_num']>0){?>
                                            <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/reddian.png"><?php }else{ ?><em class="widDian"></em>
                                            <?php }?>
                                        </span>
                                    </td>
                                    <!--人才匹配-->
                                    <td class="wid80" style="text-align:center">
                                        <a class="resume_match md_peoplematch" log_data="job_id=<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" href='<?php echo smarty_function_get_url(array('rule'=>"/recommend/index",'data'=>"type=1&job_id=".($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'])),$_smarty_tpl);?>
' target="_blank"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/icon-w.jpg" /></a>
                                    </td>
                                    <!--回复承若-->
                                    <td class="wid90" style="text-align:center">
                                        <a href="javascript:;" class="ptentBtnDft md_ljcl"><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['re_apply_type'];?>
天必回</a>
                                    </td>
                                    <!--简历过滤-->
                                    <td class="wid80">
                                        <a href="javascript:;" id="automatic<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" data-jobid="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" class="md_resumefilter openBtn <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['auto_filter']){?>openBtncut<?php }?>"></a>
                                    </td>
                                    <!--过期时间-->
                                    <td class="wid80"><em class="gray" style="color:red;">已过期</em></td>
                                     <!--刷新时间-->
                                    <td class="wid80"><em class="gray" <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['is_not_refresh']){?>title="未刷新的职位只能展示在刷新职位之后"<?php }?>><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['refresh_time'];?>
</em></td>
                                    <!--操作-->
                                    <td class="wid240">
                                        <span class="tipTxt tipText">
                                            <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']!=4&&$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['promiseStop']!=1){?>
                                            <a class="md_refresh" href="javascript:void(0);">刷新</a>
                                            <a href="javascript:void(0);">推广</a>
                                            <?php }?>
                                            <a href='javascript:void(0);' class="stopJob md_close" data-jid="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" >关闭</a>
                                             <a href="javascript:void(0);" class="editJob md_edit"  target="_blank">编辑</a>
                                            <?php if (empty($_smarty_tpl->getVariable('job_list',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['job']['index']]['map_y'])&&empty($_smarty_tpl->getVariable('job_list',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['job']['index']]['map_x'])){?>
                                            <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/map_img.png" style="position:absolute; top:-25px;right:65px;width:70px;height:24px;"/>
                                            <?php }?>
                                            <a href="javascript:void(0);" class="more" id="moreSortLnk">更多<i id="moreSortLnkPic" class="hbFntWes">&#xf0dd;</i></a>
                                            <span class="tipTxtBox zindex moretipTxtBox" id="staLst" style="display:none;">
                                                <a class="md_more_see" href="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_link'];?>
" target="_blank">预览</a>
                                                <!--<a href="javascript:void(0);" class="stopJob" data-jid="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
">停止招聘</a>-->
                                                <a class="md_more_delay" href="javascript:void(0);" onclick="joblist.delayJob(<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
)">延期</a>
                                            </span>
                                        </span>
                                    </td>

                                </tr>
                                <?php }else{ ?>
                                <tr id="li<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" data-id="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" data-username="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['account_user_name'];?>
" data-cando="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['can_do'];?>
" data-jobresource="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_account_resource_type'];?>
">
                                    <td class="wid30">
                                        <span class="formChb">
                                            <input value="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" type="checkbox" name="chkjob" class="chb" <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']==4){?>disabled="true"<?php }?>>
                                        </span>
                                        <?php if ($_smarty_tpl->getVariable('isEnd')->value==false&&$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']!=4&&$_smarty_tpl->getVariable('audit_msg')->value=='认证通过'){?>
                                            <div class="share-box" data-id="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
">
												分享（<a class="share-link" href="javascript:viod(0);">海报</a>/<a class="share-h5" href='<?php echo smarty_function_get_url(array('rule'=>"/index/TemplateStatisticalByH5",'data'=>"type=2"),$_smarty_tpl);?>
' target="_blank">H5页面</a>）
											</div>
                                        <?php }?>
                                    </td>
                                    <td class="wid180">
                                        <p>
                                            <a title="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['station'];?>
"
                                               class="<?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']==4){?>aud<?php }else{ ?>jobName<?php }?>" <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']==4){?>href="javascript:void(0);" <?php }else{ ?>href="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_link'];?>
" target="_blank"<?php }?>>
                                                <?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['sub_station'];?>

                                            </a>
                                            <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']==4){?>
                                                <b title="我们会在1个工作日内完成审核，在此期间求职者将无法看到该职位" style="display:none">审核中</b>
                                            <?php }else{ ?>
                                                <?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['urgent_mark'])&&$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['urgent_mark']==true){?>
                                                    <a href="javascript:void(0);" class="icon-ji set-urgent-act" href="javascript:void(0);" title="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['urgent_end_date'];?>
截止"></a>
                                                <?php }?>
                                                <?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['top_mark'])&&$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['top_mark']==true){?>
                                                    <a href="javascript:void(0);" class="icon-ding set-top-act" title="已在<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['top_count'];?>
个关键词列表中置顶"></a>
                                                <?php }?>
                                            <?php }?>
                                            <?php if (in_array($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'],$_smarty_tpl->getVariable('EffectSpreadJob_ids')->value)){?>
                                                <a href="javascript:void(0);" class="icon-spread"></a>
                                            <?php }?>
                                            <!--代运营-->
                                            <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['agency_state']==1||$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['agency_state']==2){?>
                                            <img title="此职位由汇博代为发布及运营" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/daiyunying.jpg" width="42px" style="vertical-align: -5px;"/>
                                            <?php }?>
                                        </p>
                                        <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['generation_bidding']){?>
                                        <div style="color:#999;margin-top: 5px"><?php echo base_lib_BaseUtils::cutStr($_smarty_tpl->getVariable('accounts')->value[$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['company_id']]['company_name_display'],10,'utf-8','','...');?>

                                            <b class="gen-binding">代招</b>
                                        </div>
                                        <?php }?>
                                        <!--<?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['agency_state']==1||$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['agency_state']==2){?>-->
                                        <!--<div class="daiyunying" style="display: inline-block;" >-->
                                            <!--<img title="" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/daiyunying.jpg" width="42px"/>-->
                                            <!--<p><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/firmicon22.png" alt="">此职位由汇博代为发布及运营</p>-->
                                        <!--</div>-->
                                        <!--<?php }?>-->
                                    </td>
                                    <!--发布人-->
                                    <td><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['account_user_name'];?>
</td>
                                    <td class="wid80" style="text-align:center">
                                      <span class="rec">
                                        <em style="color:#666"><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['applyCount'];?>
</em>
                                      </span>
                                    </td>
                                    <!--未读的简历-->
                                    <td class="wid80" style="text-align:center">
                                      <span class="rec">
                                        <a <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['applyNotReadCount']>0){?>target='_blank' href='<?php echo smarty_function_get_url(array('rule'=>"/apply/Index/",'data'=>"job_id=".($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'])."&child_status=2"),$_smarty_tpl);?>
'<?php }else{ ?>href='javascript:void(0);'<?php }?>><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['applyNotReadCount'];?>
</a>
                                          <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['applyNotReadCount']>0){?><img style="display:none" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/reddian.png"><?php }?>
                                      </span>
                                    </td>
                                    <!--待定的简历-->
                                    <td class="wid80" style="text-align:center">
                                      <span class="rec">
                                        <a <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['wait_deal_num']>0){?> target='_blank'  href='<?php echo smarty_function_get_url(array('rule'=>"/apply/Index/",'data'=>"job_id=".($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'])."&child_status=10"),$_smarty_tpl);?>
' <?php }else{ ?> href='javascript:void(0);'<?php }?> >
                                          <?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['wait_deal_num'];?>

                                        </a>
                                      </span>
                                    </td>
                                    <!--待回复-->
                                    <td class="wid100" style="text-align:center">
                                        <span class="rec">
                                            <a <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['no_reply_num']>0){?>target='_blank' href='<?php echo smarty_function_get_url(array('rule'=>"/apply/Index/",'data'=>"job_id=".($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'])),$_smarty_tpl);?>
'<?php }else{ ?>href='javascript:void(0);'<?php }?>><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['no_reply_num'];?>
</a>
                                            <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['no_reply_num']>0){?><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_index/reddian.png"><?php }else{ ?><em class="widDian"></em><?php }?>
                                        </span>
                                    </td>
                                    <!--人才匹配-->
                                    <td class="wid80" style="text-align:center">
                                        <a class="resume_match md_peoplematch" log_data="job_id=<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" href='<?php echo smarty_function_get_url(array('rule'=>"/recommend/index",'data'=>"type=1&job_id=".($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'])),$_smarty_tpl);?>
' target="_blank"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/icon-w.jpg" /></a>
                                    </td>
                                    <!--简历回复承若-->
                                    <td class="wid90" style="text-align:center">
                                        <?php if (!empty($_smarty_tpl->getVariable('job_list',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['job']['index']]['re_apply_type'])){?>
                                        <a href="javascript:;" class="ptentBtnDft"><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['re_apply_type'];?>
天必回</a>
                                        <?php }else{ ?>
                                        <div class="chengnuoBtn" style="position: relative;z-index: 99">
                                            <a href="<?php echo smarty_function_get_url(array('rule'=>"/job/mod/",'data'=>"job_id=".($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'])."&mod_type=edit"),$_smarty_tpl);?>
" class="md_ljcl  ptentBtn ptentBtnD">立即承诺</a>
                                            <div class="promiseTent">
                                              <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/templateicon02.png" width="6" height="8" />
                                                <div class="pTentx">
                                                    <h2>按时回复简历，好处免费送!</h2>
                                                    <p>首页专题页面长期进行职位推荐 专属职位图标，吸引更多求职者 单独职位展示列表，让职位脱颖而出</p>
                                                    <a href="/job/mustreplydetail" target="_blank">了解详情</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }?>
                                    </td>
                                    <!--简历过滤-->
                                    <td class="wid80">
                                        <a href="javascript:;" id="automatic<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" data-jobid="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" class="md_resumefilter openBtn <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['auto_filter']){?>openBtncut<?php }?>"></a>
                                    </td>
                                    <!--过期时间-->
                                    <td class="wid80">
                                      <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['day']>=0&&$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['day']<=5){?>
                                        <em class="red" title="<?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['day']==0){?>今天<?php }else{ ?><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['day'];?>
天后<?php }?>结束招聘"><?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['day']==0){?>今天<?php }else{ ?><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['day'];?>
天后<?php }?></em>
                                      <?php }else{ ?>
                                        <em class="gray" title="<?php echo date('Y-m-d',strtotime($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['end_time']));?>
结束招聘"><?php echo date('m-d',strtotime($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['end_time']));?>
</em>
                                      <?php }?>
                                    </td>
                                    <!--刷新时间-->
                                    <td class="wid80">
                                        <em class="gray" <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['is_not_refresh']){?>title="未刷新的职位只能展示在刷新职位之后"<?php }?>><?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['refresh_time'];?>
</em>
                                    </td>
                                    <!--操作-->
                                    <td class="wid240">
                                        <span class="tipTxt">
                                            <?php if ($_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['check_state']!=4&&$_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['promiseStop']!=1){?>
                                            <a href="javascript:void(0);" class="job_refresh md_refresh" >刷新</a>
                                            <?php }?>
                                            <?php if ($_smarty_tpl->getVariable('isEnd')->value==false){?>
                                            <a href="javascript:void(0);" class="spreadMore" id="spreadMore">推广</a>
                                            <span class="tipTxtBox zindex spreadTipTxtBox" id="spreadLst"  style="right:42px;display:none;">
                                                <a href="javascript:void(0);" class="set-urgent-act md_remmon_up">急聘推广</a>
                                                <a href="javascript:void(0);" class="set-top-act md_remmon_top">置顶推广</a>
                                                <a href="/spreadjob/" class="set-spread-act md_remmon_jz">精准推广</a>
                                            </span>
                                            <?php }?>
                                            <a href='javascript:void(0);' class="stopJob md_close" data-jid="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
" >关闭</a>
                                            <a href="javascript:void(0);" class="editJob md_edit">编辑</a>
                                            <?php if (empty($_smarty_tpl->getVariable('job_list',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['job']['index']]['map_y'])&&empty($_smarty_tpl->getVariable('job_list',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['job']['index']]['map_x'])){?>
                                            <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/map_img.png" style="position:absolute; top:-25px;right:65px;width:70px;height:24px;"/>
                                            <?php }?>
                                            <a href="javascript:void(0);" class="more" id="moreSortLnk">更多<i id="moreSortLnkPic" class="hbFntWes">&#xf0dd;</i></a>
                                            <span class="tipTxtBox zindex moretipTxtBox" id="staLst" style="display:none;">
                                                <a class="md_more_see" href="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_link'];?>
" target="_blank">预览</a>
                                                <!--<a href="javascript:void(0);" class="stopJob" data-jid="<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
">停止招聘</a>-->
                                                <a class="md_more_delay" href="javascript:void(0);" onclick="joblist.delayJob(<?php echo $_smarty_tpl->getVariable('job_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['job']['index']]['job_id'];?>
)">延期</a>
                                            </span>
                                        </span>                                     
                                    </td>

                                </tr>
                                <?php }?>
                                <?php if ($_smarty_tpl->getVariable('smarty')->value['section']['job']['iteration']==$_smarty_tpl->getVariable('promiseStopSize')->value){?>
                                <tr class="SperateLine">
                                  <td colspan="13"><i></i>以上停招的承诺职位简历要回复完！</td>
                                </tr>
                                <?php }?>
                                <?php endfor; endif; ?>
                            </tbody>
                        </table>
                    </div>
              <div class="oper-box" style="z-index: 1">
                <?php if (!empty($_smarty_tpl->getVariable('job_list',null,true,false)->value)&&count($_smarty_tpl->getVariable('job_list')->value)>0){?>
                    <label class="all"><input id="upAllSelect" type="checkbox" class="chb" />全选</label>
                    <div class="sort-select" style="#width:140px">
                      <p class="tit"><span>批量操作</span><i class="hbFntWes">&#xf0d7;</i></p>
                      <ul style="width:135px">
                        <li><a class="md_much_alterphone" href="javascript:void(0)" onclick="joblist.updatelinkway();">修改联系电话</a></li>
                        <li><a class="md_much_altermail" href="javascript:void(0)" onclick="joblist.batchupdatemail();">修改邮箱</a></li>
                        <li><a class="md_much_dealy" href="javascript:void(0)" onclick="joblist.delayChooseJobs();">延长有效期</a></li>
                        <li><a href="javascript:void(0)" class="stopAllJob md_much_close">关闭</a></li>
                        <li><a  class="md_much_order" href="javascript:void(0)" onclick="joblist.updateSort();">批量排序</a></li>
                        <li><a class="md_much_alterlevel" href="javascript:void(0)" onclick="joblist.updateLevel();">批量更改岗位级别</a></li>
                        <li><a class="md_much_jobsalary" href="javascript:void(0)" onclick="banner_on()">批量设置职位薪资</a></li>
                        <li><a class="md_much_onlychat" href="javascript:void(0)" onclick="onlinetalk_on()">批量允许在线沟通</a></li>
                        </ul>
                    </div>
                    <?php }?>              
                        <div class="noData" <?php if (!empty($_smarty_tpl->getVariable('job_list',null,true,false)->value)&&count($_smarty_tpl->getVariable('job_list')->value)>0){?>style="display:none;"<?php }?>>
                            <p><?php if (!base_lib_BaseUtils::nullOrEmpty($_smarty_tpl->getVariable('search_keyword')->value)){?>未找到相关职位，请更换关键词重试或<a href='<?php echo smarty_function_get_url(array('rule'=>"/index/joblist"),$_smarty_tpl);?>
'>查看全部职位</a><?php }else{ ?>没有招聘中的职位<?php }?></p> 
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </hgroup>     
    </section>
    <div class="clear"></div>
</div>
<?php $_template = new Smarty_Internal_Template("msgpop.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<section class="floatRT"><a href="<?php echo smarty_function_get_url(array('rule'=>'/about/message','domain'=>'main'),$_smarty_tpl);?>
" target="_blank" class="serviceLink">我有问题要反馈</a><b></b></section>

<!--几日回复 停招 start-->
<script type="text/javascript">
var getPic;
var singleJobId = '';
var job = '';
var job_boutique_release = "<?php echo $_smarty_tpl->getVariable('companyresources')->value['job_boutique_release'];?>
";
var pricing_job_boutique = "<?php echo $_smarty_tpl->getVariable('companyresources')->value['pricing_job_boutique'];?>
";
var resource_type = "<?php echo $_smarty_tpl->getVariable('companyresources')->value['resource_type'];?>
";
hbjs.use('@imageEditor, @confirmBox, @jobsort, @orderActions', function(m) {

    var $ = m['cqjob.jobsort']
    var Dialog       = m['widge.overlay.hbDialog'],
        ConfirmBox      = m['widge.overlay.confirmBox'],
        orderActions = m['product.orderActions'],
        confirmBox   = m['widge.overlay.confirmBox'],
        stopDialog   = new Dialog({
            close : 'X',
            idName : 'stop_job_dialog',
            title : '职位停招',
            width : 400,
            isOverflow : false,
            isAjax : true
        });
        getPic = new Dialog({
            close : 'X',
            idName : 'getPicDialog',
            title : '海报分享',
            width : 700,
            isOverflow : false,
            isAjax : true
        });
    var refreshSomeDialog = new Dialog({
        close:'x',
        idName:'refreshSomeDialog',
        width:400,
        title:'提示',
        isAjax:true
    });
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
    $(document).on('click','.refreshSomeDialog  .refresh-many .btns .cancel',function () {
        refreshSomeDialog.hide();
    });
    $(document).on('click','.refreshSomeDialog  .refresh-many-all .btns .cancel,.btncancel',function () {
        refreshSomeDialog.hide();
    })
    $("#js_click_import").click(function(){
        importDialog.show();
    });
    $("body").delegate('#importClose',"click",function(){
        importDialog.hide();
        return false;
    });


    $('.editJob').on('click', function() {
        var job_id = $(this).closest('tr').attr("data-id");
        var cando = $(this).closest('tr').attr("data-cando");
        var jobresource = $(this).closest('tr').attr("data-jobresource");
        var job_user_name = $(this).closest('tr').attr("data-username");
        var msg = '';
        if(cando == 2){
            if(resource_type == 2){
                msg = '该职位由账号（'+job_user_name+'）发布，请登录该账号操作';
            }else{
                msg ="该职位由分配模式账号（"+job_user_name+"）发布，请登录该子账号操作";
            }

            if(msg){
                ConfirmBox.timeBomb(msg, {
                    name: 'fail',
                    timeout: 2000,
                    width:  msg*18 + 20,
                    zIndex: 999999
                });
                return false;
            }

        }
        window.location.href='<?php echo smarty_function_get_url(array('rule'=>"/job/mod/",'data'=>"job_id='+job_id+'&mod_type=edit"),$_smarty_tpl);?>
';
    });

    $('#refreshAll').on('click', function() {
        var jobids = [];
        $('input.chb[name=chkjob]:checked').each(function() {
            jobids.push($(this).val());
        });

        if (jobids.length < 1) {
            ConfirmBox.alert('请选择需要刷新的职位','',{width:350});
            return;
        }
        var count = jobids.length;
        job = jobids.join(',');
        var content = "<?php echo smarty_function_get_url(array('rule'=>'/index/RefreshAllCq'),$_smarty_tpl);?>
/count-"+count+"-is_batch-1";
        refreshSomeDialog.setContent(content).show();
    });

    $(document).on('click','.refreshSomeDialog .many-con .btns .btnRefresh',function () {
        refreshSomeDialog._addLoading();
        $('.refresh-many').hide();
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
            ConfirmBox.alert(r.msg);
        },'json');
        //refreshSomeDialog.hide();
    })



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
                '<style>.jobRefreshDialog{text-align: left;font-family: "Microsoft YaHei";line-height: 25px}.jobRefreshDialog table{width:100%}.jobRefreshDialog th{font-size: 14px;color: #999;padding: 15px 10px 10px 10px;text-align: center}.jobRefreshDialog td{height:48px;line-height: 48px;font-size: 14px;color: #666;text-align: center;padding-left:10px;padding-right: 10px}.jobRefreshDialog .one td{background-color: #fafafa}.jobRefreshDialog a{color: #2b6fad;margin: 0 10px}.jobRefreshDialog a:hover{color}</style>'+
                '<p style="font-size: 14px;color: #666;border-bottom: 1px dashed #e9e8e8;padding-bottom: 10px">'+json.msg+'<br />如需刷新请在列表中手动刷新，如已停招请关闭职位</p>'+
                '<div style="height:190px;overflow-y: auto"><table style="width: 100%">'+
                '<tr><th style="text-align: left">职位名称</th><th>招聘人</th><th>待回复</th><th>操作</th></tr>';
        $.each(json.notRefreshJobs, function(i, n){
            if(i%2 == 0)
                jobRefreshHtml += '<tr class="one"><td style="text-align: left">'+n.station+'</td><td>'+n.account_username+'</td><td>'+n.no_reply_num+'</td><td><a data-id="'+n.job_id+'" href="javascript:;" class="singleRefrsh">刷新</a><a data-id="'+n.job_id+'" href="javascript:;" style="margin-right: 0" class="job_close">关闭</a></td></tr>';
            else
                jobRefreshHtml += '<tr><td style="text-align: left">'+n.station+'</td><td>'+n.account_username+'</td><td>'+n.no_reply_num+'</td><td><a data-id="'+n.job_id+'" href="javascript:;" class="singleRefrsh">刷新</a><a data-id="'+n.job_id+'" href="javascript:;" style="margin-right: 0" class="job_close">关闭</a></td></tr>';
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



    $('.job_refresh').on('click',function(){
        singleJobId = $(this).closest('tr').attr("data-id");
        console.log(singleJobId);
        var content = "<?php echo smarty_function_get_url(array('rule'=>'/index/RefreshAllCq'),$_smarty_tpl);?>
/count-"+1+"-is_batch-0-job_id-"+singleJobId;
        var isShow = cookieutility.get('isShowCq') ? true:false;
        if("<?php echo $_smarty_tpl->getVariable('company_resource_info')->value['cq_release_point_job_refresh'];?>
" < 1  && !isShow){
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
                window.location.reload();
                return;
            }
            ConfirmBox.alert(r.msg);
        },'json');
    });
 	$(document).on('click','.refreshSomeDialog .ok',function () {
 		refreshSomeDialog.hide();
 	})
    $(document).on('click','.refreshSomeDialog .only-one-con .btns .btnRefreshSingle',function () {
        console.log(singleJobId);
        $.post("<?php echo smarty_function_get_url(array('rule'=>'/index/RefreshCqDo'),$_smarty_tpl);?>
",{job_id:singleJobId},function(r){
            if(r.status){
                if($('.refreshSomeDialog .only-one-con .check-box input').is(':checked')){
                    cookieutility.set('isShowCq',1);
                }
                refreshSomeDialog.hide();
                $.anchorMsg(r.msg, {icon: 'success'});
                window.location.reload();
                return;
            }
            ConfirmBox.alert(r.msg);
        },'json');

    })

    //立即承诺移上去效果
    $('.chengnuoBtn').hover(function() {
        var tt = $(this).find('.promiseTent');
        if (tt.is(":hidden")) {
            tt.show();
        }
    }, function() {
        var tt = $(this).find('.promiseTent');
        if (!tt.is(":hidden")) {
            tt.hide();
        }
    });

    $('.stopJob').on('click', function (e) {
        var _this = $(this),
            jobid = _this.attr('data-jid');
        
        var jobidArr = [];
        jobidArr.push(jobid);
        toStopJobs(jobidArr,true);
    });

    $('.stopAllJob').on('click', function (e) {
        var jobids = [];
        $('input.chb[name=chkjob]:checked').each(function() {
            jobids.push($(this).val());
        });

        if (jobids.length > 0) {
            toStopJobs(jobids,false);
        } else {
            $.anchor('请选择职位', {
                icon:'info'
            });
            return;
        }        
    });

    $(".com-selected").on('click', function (e) {
        $("input[name=account_id]").val($(this).attr("data-company"));
        $("#formSearchMyJob").submit();
    });

    var dialog = new Dialog({
        close : 'X',
        idName : 'job_dialog',
        width : 520,
        isOverflow : false,
        isAjax : true
    });

    dialog.after("hide", function (e) {
        dialog.off("click");
    });

    dialog.on("loadComplete", function (e) {
    	//取消在input敲回车自动提交表单
		dialog.query('#tops').on('keydown','input',function(ev){
			if(ev.keyCode==13){
	    			return false;
	    	}
		})
		//输入框获得焦点时隐藏错误提示
		dialog.query('#tops').on('focus','.list input',function(){
				$(this).parent('.left').siblings('.errword').hide()
		})
		
        // 新增关键词
        dialog.query("#set-top").on("click", ".add", function (e) {
            var i = $("#set-top").find(".list").length + 1;
            var $li = $(".modal").find('li').clone();
            if (i >= 3) {
                $(this).hide();
            }

            $("#set-top").find("ul#tops").append($li);
            $('.text').change(function(){
                var text_val = $(this).val();
                if (/^([\u4e00-\u9fa5]+|[0-9a-zA-Z\/.\-\\#\+]+)$/.test(text_val)) {} else {
                    var new_val = '';
                    for(var i=0;i<text_val.length;i++)
                    {
                        if (/^([\u4e00-\u9fa5]+|[0-9a-zA-Z\/.\-\\#\+]+)$/.test(text_val.charAt(i))) {
                            new_val += text_val.charAt(i);
                        }
                    }
                    $(this).val(new_val);
                }
            });
        });

        // 删除关键词
        dialog.query("#set-top").on("click", ".del", function (e) {
            $(this).closest('li').remove();
            if ($("#set-top").find(".list").length < 3)
                $(".add").show();
        });

        // 延长时间
        dialog.query("#set-top").on("click", ".delay", function (e) {
            $(this).removeClass("delay").addClass("delay-cancel").siblings('.extend-box').find("select[name=dllday]").removeClass('disabled');
            $(this).removeClass("delay").addClass("delay-cancel").siblings('.extend-box').show();
            $(this).text("取消延长");
        });

        // 取消延长时间
        dialog.query("#set-top").on("click", ".delay-cancel", function (e) {
            $(this).siblings('.extend-box').find("select[name=dllday]").addClass('disabled');
            $(this).removeClass("delay-cancel").addClass("delay").siblings('.extend-box').hide();
            $(this).text("延长时间");
        });

        // 取消弹窗按钮
        dialog.query(".cancel").on("click", function (e) {
            dialog.hide();
        });

        // 提交弹窗页面
        dialog.query(".top-submit").on("click", function (e) {

            //兼容IE8
            if (!document.getElementsByClassName) {
                document.getElementsByClassName = function (className, element) {
                    var children = (element || document).getElementsByTagName('*');
                    var elements = new Array();
                    for (var i = 0; i < children.length; i++) {
                        var child = children[i];
                        var classNames = child.className.split(' ');
                        for (var j = 0; j < classNames.length; j++) {
                            if (classNames[j] == className) {
                                elements.push(child);
                                break;
                            }
                        }
                    }
                    return elements;
                };
            }

            //过滤下字符
            var str_1 = '';
            var x = document.getElementsByClassName('text');
            for(var i1 =0;i1< x.length;i1++){

                if (/^([\u4e00-\u9fa5]+|[0-9a-zA-Z\/.\-\\#\+]+)$/.test($(x[i1]).val())) {} else {
                    var new_val = '';
                    for(var i=0;i<$(x[i1]).val().length;i++)
                    {
                        if (/^([\u4e00-\u9fa5]+|[0-9a-zA-Z\/.\-\\#\+]+)$/.test($(x[i1]).val().charAt(i))) {
                            new_val += $(x[i1]).val().charAt(i);
                        }
                    }
                    $(x[i1]).val(new_val);

                }

                if($(x[i1]).val()){
                    str_1 +=  '“' + $(x[i1]).val() + '”';
                }

             }
            
			var inputflag=true;
            var typeflag=false;
            orders = [];
            job_id = dialog.query("input[name=hiddenjob]").val();
            
            //将过滤后的关键字存进数组准备提交
            dialog.query("#formjob").find(".list").each(function (i, e) {
                if ($(this).find("select[name=dllday]").hasClass('disabled'))
                    return true;
				
                var txtword = $(this).find("input[name=txtword]").val();
                var dllday  = $(this).find("select[name=dllday]").val();
                
				orders.push({type : 'top', keyword : txtword, dllday : dllday});
                
				//如果输入框为空，显示"请输入关键字"
				if(txtword==''){
					$(this).find(".errword").show();
					inputflag=false;
				}
				if($(this).find("input[name=txtword]").attr("type")=="text"){
					typeflag=true;
				}
                
            });
            
            
            function show_orderActions(){
            	orderActions.show({
	                url: "<?php echo smarty_function_get_url(array('rule'=>"/spread/order/"),$_smarty_tpl);?>
",
	                orders : orders,
	                jobid : job_id,
	                submit : function(e) {
	                    var target = $(e.currentTarget),
	                            self = this;
	
	                    orderActions.setSubmit(false, target);
	                    $.post("<?php echo smarty_function_get_url(array('rule'=>"/spread/consume/"),$_smarty_tpl);?>
", {job_id : job_id, orders : orders}, function (e1) {
	                    	
	                        if (e1.status) {
	                            confirmBox.timeBomb("操作成功！", {
	                                name: 'success',
	                                timeout : 1000,
	                                width : 200,
	                                zIndex : 999999,
	                                callback : function (e) {
	                                    window.location.reload();
	                                }
	                            });
	                        } else {
	                            //$.anchorMsg(e1.msg, {icon : 'fail'});
	                            confirmBox.timeBomb(e1.msg, {
	                                name: 'fail',
	                                timeout : 3000,
	                                width : 300,
	                                zIndex : 999999,
	                                callback : function (e) {
	                                    self.hide();
	                                    dialog._showMask();
	                                }
	                            });
	                        }
	                    },"json");
	                },
	                hide : function () {
	                    dialog._showMask();
	                }
	            });
            }
			
			
			//输入框为空的时候返回
			if(!inputflag){
				return false;
			}
			
			if(typeflag){
				$('.keyword_dialog').remove()
				var keyword_dialog=new Dialog({
					close : 'X',
		            idName : 'keyword_dialog',
		            title : '确认关键字',
		            width : 350,
		            isAjax : true,
		            content:'<div class="keyword_confirm">您设置的关键词为：' + str_1 + '，请确认</div>' +'<div class="dialogFooter set-top-foot" style="border-top:0px;background:#f3f3f3;padding:7px"><a class="btn1 btnsF12 submit" href="javascript:void(0);" style="background:#66bce4;border:0px;box-shadow:none;">确定</a><a class="btn3 btnsF12 cancel" href="javascript:void(0);" style="background:#fff;border:0px;box-shadow:none">取消</a></div>'
				})
				$('.keyword_dialog .submit').on('click',function(){
					keyword_dialog.hide();
			        show_orderActions()
				})
				$('.keyword_dialog .cancel').on('click',function(){
					keyword_dialog.hide();
				})
				keyword_dialog.show()

//				ConfirmBox.confirm("<span>"+'您设置的关键词为：' + str_1 + '，请确认'+"</span>","设置关键字", function(obj){
//					this.hide();
//					dialog._showMask();
//					show_orderActions()
//					
//				},function(obj){
//					console.log('关闭')
//					dialog._showMask();
//				},{
//					width :500,
//					close : 'x'
//				});
			}else{
				show_orderActions()
			}

        });

        dialog.query(".urg-submit").on("click", function (e) {
            orders = [];
            job_id = dialog.query("input[name=hiddenjob]").val();

            var dllday = dialog.query(":input[name=radUrgentDay][checked]").val();
            orders.push({type : 'urgent', dllday : dllday});

            orderActions.show({
                url: "<?php echo smarty_function_get_url(array('rule'=>"/spread/order/"),$_smarty_tpl);?>
",
                orders : orders,
                jobid : job_id,
                submit : function() {
                    var target = $(e.currentTarget),
                        self = this;
                        
                    orderActions.setSubmit(false, target);
                    $.post("<?php echo smarty_function_get_url(array('rule'=>"/spread/consume/"),$_smarty_tpl);?>
", {job_id : job_id, orders : orders}, function (e) {
                        if (e.status) {
                            confirmBox.timeBomb("操作成功！", {
                                name: 'success',
                                timeout : 1000,
                                width : 200,
                                zIndex : 999999,
                                callback : function (e) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.anchorMsg(e.msg, {icon : 'fail'});
                            self.hide();
                        }
                    },"json");
                },
                hide : function () {
                    dialog._showMask();
                }
            })
        });
    });

    // 置顶推广
    $(".set-top-act").on("click", function (e) {

        job_id = $(this).closest('tr').attr("data-id");
        dialog.setContent({
            'title' : '置顶推广',
            'content' : '<?php echo smarty_function_get_url(array('rule'=>"/spread/top/",'data'=>"jobid='+job_id+'&obj=joblist&callback=topJob&v='+Math.random()+'"),$_smarty_tpl);?>
'
        }).show();
    });

    // 急聘推广 
    $(".set-urgent-act").on('click', function (e) {
        job_id = $(this).closest('tr').attr("data-id");
        dialog.setContent({
            'title' : '急聘推广',
            'content' : '<?php echo smarty_function_get_url(array('rule'=>"/spread/urgent/",'data'=>"jobid='+job_id+'&obj=joblist&callback=topJob&v='+Math.random()+'"),$_smarty_tpl);?>
'
        }).show();
    });
	

    function toStopJobs(jobidArr,is_only) {

        if (typeof jobidArr == 'object' && jobidArr.length > 1) {
            var jobid    = jobidArr.join(',');
            var applyUrl = "/apply";
        } else {
            var jobid    = jobidArr;
            var applyUrl = "/apply/index-job_id-"+jobid;
        }
        var title_message = '关闭职位';
        /**判断先**/
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
                    '<a href="' + applyUrl + '" class="cpromisetip graybutn" style="margin-right:0px;">手动回复简历</a>',
                    '</div>',
                    '</div>'
                ].join('');
                if(is_only == 'not_refresh')
                    jobRefreshDialog.hide();
                
                stopDialog.setContent(tipHTML).show();
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
                })
            
                stopDialog.query('a.graybutn').on('click', function (e) {
                    cookieutility.set('showStopJobApply', true, "", "/");
                    stopDialog.hide();
                });
            } else if(is_only == 'not_refresh'){
                ConfirmBox.confirm("关闭（停止招聘）后，不再被求职者看到，确定关闭吗？","关闭职位",function(obj){
                    this.hide();
                    $.getJSON('/job/BatchStopJobDo-', {hddjobID:jobid}, function (result) {
                        if(result.success) {
                            jobRefreshDialog.hide();
                            $.anchorMsg("关闭成功 ");
                            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/GetNotFreshJobs/",'data'=>"v='+Math.random()+'"),$_smarty_tpl);?>
', null, function (data) {
                                if(data.not_refresh_num > 0)
                                    showJobRefreshDialog(data);
                                else
                                    window.location.reload();
                            });
                        }else{
                             $.anchorMsg("关闭失败",{icon:'fain'});
                        }
                    });
                },null,{width:300});
            }else {
                if(!is_only)
                    title_message = '批量关闭职位';
                $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/BatchStopJob/",'data'=>"jobids=' + jobidArr.join('-jobids-') + '&v='+Math.random()+'"),$_smarty_tpl);?>
', {title:title_message,width:600});
            }
        });
    }
});
</script>
<!--几日回复 停招 end-->

<script  type="text/javascript">
    var action_url = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
';


    if(typeof action_dom == 'object'){}else{
        action_dom = [];
    }
    action_dom.push( ['.resume_match', 3]);
    action_dom.push( ['.share-link', 42]);
    action_dom.push( ['.share-h5', 50]);


    action_dom.push( ['.md_inmrecruit', 154]);
    action_dom.push( ['.md_endmrecruit', 155]);
    action_dom.push( ['.md_myjob', 156]);
    action_dom.push( ['.md_pubjob', 157]);
    action_dom.push( ['.md_dsrefresh', 158]);
    action_dom.push( ['.md_muchrefresh', 159]);

    action_dom.push( ['.md_much_alterphone', 160]);
    action_dom.push( ['.md_much_altermail', 161]);
    action_dom.push( ['.md_much_dealy', 162]);
    action_dom.push( ['.md_much_close', 163]);
    action_dom.push( ['.md_much_order', 164]);
    action_dom.push( ['.md_much_alterlevel', 165]);
    action_dom.push( ['.md_much_jobsalary', 166]);
    action_dom.push( ['.md_much_onlychat', 167]);

    action_dom.push( ['.md_onekeyimprot', 168]);
    action_dom.push( ['.md_peoplematch', 169]);
    action_dom.push( ['.md_ljcl', 170]);
    action_dom.push( ['.md_resumefilter', 171]);
    action_dom.push( ['.md_refresh', 172]);
    action_dom.push( ['.md_remmon_up', 173]);
    action_dom.push( ['.md_remmon_top', 174]);
    action_dom.push( ['.md_remmon_jz', 175]);
    action_dom.push( ['.md_close', 176]);
    action_dom.push( ['.md_edit', 177]);
    action_dom.push( ['.md_more_see', 178]);
    action_dom.push( ['.md_more_delay', 179]);

    action_dom.push( ['.md_search', 418]);
</script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'action.js'),$_smarty_tpl);?>
"></script>

<script>
    var selece_myjob = $('input[name=myjob]');
    selece_myjob.click(function(){
        var url = '<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],"http:",'');?>
/index/joblist/?position=joblist';
        if(selece_myjob.is(':checked')){
            url += '&ismyjob=true';
        }
        window.location.href = url;
    });

var joblist = {
    initialize : function() {
        //全选
//        allChk  = $(".all input");
//        allChk.bindCheckBox('chkjob', '#lstBox');

        $('#lst1').find('tr').hover(function() {
            $(this).addClass('hov');
        }, function() {
            $(this).removeClass('hov');
        });

        $('#auto-refresh').click(function() {       
            var fn = $(this),
            autostatus = fn.find('#refreshStatus').val();
            $.getJSON("<?php echo smarty_function_get_url(array('rule'=>'/index/autorefesh'),$_smarty_tpl);?>
", {auto_status:autostatus}, function (data) {
                if (data.status == "error") {
                    $.message(data.info, {title: '系统提示', icon: 'fail'});
                
                return;
            }

            if (data.status == "sucssus") {
                if (autostatus == 1) {                
                    fn.find('i').addClass('cut');
                    fn.find('#refreshStatus').val(0);
                    fn.find('.autorefreshtime').show();
                } else {
                    fn.find('i').removeClass('cut');
                    fn.find('#refreshStatus').val(1);
                    fn.find('.autorefreshtime').hide();
                }

                $.anchorMsg(data.info);
            }
        });
    });
    
    $('.watermark').watermark2();
        //刷新职位
        $(".jobRefresh1").click(function() {
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/refreshAll/",'data'=>"v=' + Math.random() + '"),$_smarty_tpl);?>
', function (json) {
                if (json.error) {
                    $.message(json.error, {title: '系统提示', icon: 'fail'});
                    return;
                }

                if (json.fail) {
                    $.message(json.failitem, {title: '系统提示', icon: 'fail'});
                    return;
                }

                if (json.success) {
                    $("#divRefreshTime").html(json.refreshtime);
                    $("#refreshTimeSpan").show();
                    $.anchorMsg('刷新全部职位成功');
                }
                return;
            });
        });

        //是否显示提示框
        var isShowAutoMaticTip = cookieutility.get('isShowAutoMaticTip');
        if (isShowAutoMaticTip == null || isShowAutoMaticTip == "") {
            $("#show_alt").show();
        }
        $('.showClose').click(function() {
            $('#show_alt').hide();
            cookieutility.set('isShowAutoMaticTip','true');
        });
    },
    stopJob : function(job_id) {
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/BatchStopJob/",'data'=>"jobids=' + job_id + '&v=' + Math.random() + '"),$_smarty_tpl);?>
', {title:'停用职位'});
    },
    delayJob : function(job_id) {
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/DelaySingle/",'data'=>"job_id=' + job_id + '&obj=joblist&callback=delayJobCallback&v=' + Math.random() + '"),$_smarty_tpl);?>
', {title:'职位延期'});
    },
    delayJobCallback : function(job_id, end_time) {
        $("#li" + job_id).find('td:eq(5)').html('<em class="gray" title="' + end_time + '结束招聘">' + end_time + '</em>');
    },
    updateLevel : function() {
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/levelUpdate/"),$_smarty_tpl);?>
', {title:'批量更改岗位级别'});
    },
    tanchuang : function() {
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/mordModSalary/"),$_smarty_tpl);?>
', {title:'批量更改岗位薪水'});
    },
    onlinetalk:function(){
        var jobids = this.getCheckedJob();
        if (jobids.length <= 0) {
            $.anchor('请选择职位', {icon:'info'});
            return;
        }
        $.post('<?php echo smarty_function_get_url(array('rule'=>"/job/setOnlineTalk/"),$_smarty_tpl);?>
' , {hddjobID:jobids} , function(data){
            var icom_ = 'fail';
            if(data.status == true){
                var icom_ = 'success';
            }
            $.message(data.msg, {title: '系统提示', icon: icom_});
        } , 'json');
    },
    updateSort : function() {
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/JobSort/"),$_smarty_tpl);?>
', {title:'批量排序'});
    },
    updatelinkway : function() {
        var jobids = this.getCheckedJob();
        if (jobids.length <= 0) {
            $.anchor('请选择职位', {icon:'info'});
            return;
        }
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/UpdateLinkway/",'data'=>"jobids=' + jobids.join('-jobids-') + '&v='+Math.random()+'"),$_smarty_tpl);?>
', {title:'批量修改联系电话'});
    },
    batchupdatemail : function() {
        var jobids = this.getCheckedJob();
        if (jobids.length <= 0) {
            $.anchor('请选择职位', {icon:'info'});
            return;
        }
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/UpdateMail/",'data'=>"jobids=' + jobids.join('-jobids-') + '&v='+Math.random()+'"),$_smarty_tpl);?>
', {title:'批量修改邮箱'});
    },
    batchstopJob : function() {
        var jobids = this.getCheckedJob();
        if (jobids.length <= 0) {
            $.anchor('请选择职位', {icon:'info'});
            return;
        }

        /**判断先**/
        $.getJSON("<?php echo smarty_function_get_url(array('rule'=>'/index/GetNoreplycount'),$_smarty_tpl);?>
",{'jids':jobids.join(',')},function(data){
            if (parseInt(data.count) > 0) {
                var promiseTipHTML = [
                    '<div class="warning_dialog">',
                    '<dl><dt></dt>',
                    '<dd>',
                    ' <p><font color="red">'+data.names+'</font>'+data.jids.length+'个职位中还有简历未进行回复，</p>',
                    ' <p>请先回复完简历后再关闭职位。</p>',
                    ' <a href="javascript:void(0);" class="replyAndStop">回绝所有未回复简历并关闭职位</a>',
                    ' <a href="/apply" class="cpromisetip graybutn">手动回复简历</a>',
                    '</dd></dl>',
                    '</div>'
                ].join('');

                alert('xianshi Dialog! ' + data.count);
            } else {
                $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/BatchStopJob/",'data'=>"jobids=' + jobids.join('-jobids-') + '&v='+Math.random()+'"),$_smarty_tpl);?>
', {title:'批量停用职位'});
            }
        });
    },
    getCheckedJob : function() {
    var checkboxs = $('#lstBox').find('input[name="chkjob"]:checked'),
    jobids = [];
        for (var i = 0, len = checkboxs.length; i < len; i += 1) {
          jobids.push($(checkboxs[i]).val());
        }
        
        return jobids;
    },
    delayChooseJobs : function() {
        var checkboxs = $('#lstBox').find('input[name="chkjob"]:checked'),
        jobids = [];
        for (var i = 0, len = checkboxs.length; i < len; i += 1) {
            jobids.push($(checkboxs[i]).val());
        }

        if (jobids.length <= 0) {
            $.anchor('请选择职位', {icon:'info'});
            return;
        }

        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/job/DelayMulti/",'data'=>"obj=joblist&callback=delayJobsCallback"),$_smarty_tpl);?>
-job_ids-' + jobids.join('-job_ids-') + '-v-'+Math.random()+'"', {title:'批量延长有效期'});
    },
    delayJobsCallback : function(job_id,end_time) {
        window.location.reload();
    },
    stopChooseJobs : function() {
        var checkboxs = $('#lstBox').find('input[name="chkjob"]:checked'),
        jobids = [];
        for (var i = 0, len = checkboxs.length; i < len; i += 1) {
            jobids.push($(checkboxs[i]).val());
        }

        if (jobids.length <= 0) {
            ConfirmBox.alert('请选择需要刷新的职位','',{width:350});
            return;
        }

        $.confirm('关闭（停止招聘）后，职位将不再被求职者看到，确定关闭吗？', '操作提示', function() {
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/stopchoosejob/",'data'=>"jobids=' + jobids.join('-jobids-') + '&v='+Math.random()+'"),$_smarty_tpl);?>
', function (json) {
                if (json.error) {
                    $.message(json.error, {title: '系统提示', icon: 'fail'});
                    return;
                }

                if (json.fail) {
                    $.message(json.failitem, {title: '系统提示', icon: 'fail'});
                } else if (json.success) {
                    $.anchorMsg('关闭成功');
                }

                if (json.job_ids) {
                    var count = json.job_ids.length;
                    if (count > 0) {
                        var jobListUseCount  = parseInt($("#jobListUseCount").html());
                        var jobListStopCount = parseInt($("#jobListStopCount").html());
                        $("#jobListUseCount").html(jobListUseCount - count);
                        $("#jobListStopCount").html(jobListStopCount + count);
                        
                        $.each(json.job_ids, function (i, item) {
                            $("#li" + item).remove();
                        });

                        if ($('#lstBox').find('tr').length <= 0) {
                            $('#lst1').remove();
                            $('div.noData').show();
                        }
                    }
                }
            });
        });
    },
    //开启自动过滤
    openAutomatic : function(job_id, result) {
        if (result) {
            $("#automatic" + job_id).addClass("openBtncut");
            return;
        }
    }
};

joblist.initialize();
var TabC = $("#TabC"),
sortS   = TabC.find(".sort-select");


$("body").mousemove(function (e) {
  sortS.each(function(){
    if ($(this)[0] === e.target || $.contains($(this)[0], e.target)){
      $(this).addClass("show-sort");
    } else {
      $(this).removeClass("show-sort");
    }
  });
});

var Ttime = null,
    Mtime = null;

$("#lstBox .spreadMore").hover(function(){
  var par = $(this).parent(".tipTxt");
  par.find(".spreadTipTxtBox").show();
}, function() { 
  var par = $(this).parent(".tipTxt");  
    Ttime = setTimeout(function() {
      par.find(".spreadTipTxtBox").hide();
    }, 150);
});

$("#lstBox .spreadTipTxtBox").hover(function() {
  if(Ttime)clearTimeout(Ttime);
  $(this).show();
}, function() {
  $(this).hide();
});

$('.openBtn').click(function(){
    var job_id = $(this).attr("data-jobid");
    if ($(this).hasClass("openBtncut")) {
        //关
        setAutomaticClose(job_id);
    } else {
        //开 判断简历是否可以自动开启，是否设置了匹配的关键字
        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/checkJobAutomatic/"),$_smarty_tpl);?>
'+'job_id-'+job_id+'-v-'+Math.random(), function (result) {
            if (result.success) {
                //已经设置 设置自动过滤 开启
                setAutomaticOpen(job_id);
            } else {
                //未设置
                showSetJobAutomatic(job_id);
            }
        });
    }     
});

$(".openBtnDisable").on("click", function (e) {
    $.message("该职位已经停招", {title: '系统提示', icon: 'fail'});
});

$('.jobfreshBtn').hover(function() {
  $('#show_altx').toggle();                
});

$("#lstBox .more").hover(function() {
  var par = $(this).parent(".tipTxt");
  par.find(".moretipTxtBox").show();
}, function() { 
  var par = $(this).parent(".tipTxt");
  Mtime = setTimeout(function(){        
    par.find(".moretipTxtBox").hide();
  }, 150);
});

$("#lstBox .moretipTxtBox").hover(function(){
  if(Mtime)clearTimeout(Mtime);
  $(this).show();
}, function() {
  $(this).hide();
});

//开启关闭自动刷新
$('.jobListCont div p a').click(function(){
    var fn     = $(this),
    autostatus = $('#refreshStatus').val();
    $.getJSON("<?php echo smarty_function_get_url(array('rule'=>'/index/autorefesh'),$_smarty_tpl);?>
", {auto_status:autostatus}, function (data) {
        if (data.status=="error") {
            $.message(data.info, {title: '系统提示', icon: 'fail'});
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
/*全选*/
listChk = TabC.find("table input:not(:disabled)[type='checkbox']"),
allChk  = TabC.find(".all input");//全选按钮
$("body").click(function(e){
  if (e.target.type == "checkbox") {
    var flg = true;
    listChk.each(function(){
      if(!$(this).attr("checked")) flg = false;
    });
    if (flg) {
      allChk.attr("checked","checked");
    } else {
      allChk.removeAttr("checked");
    }
  }
});

allChk.click(function(){
  if ($(this).attr("checked")) {
    listChk.attr("checked","checked");
  } else {
    listChk.removeAttr("checked");
  }
});

var refresh = $("#auto-refresh");
refresh.hover(function() {
  $(this).find(".alt").show();
}, function() {
  $(this).find(".alt").hide();
});

//搜索
var liSchText = $("#liSchText");
liSchText.prev("span").click(function() {
    liSchText.focus()
});

liSchText.focus(function() {
  $(this).prev("span").hide();
}).blur(function() {
  if(/^[　\s]*$/.test($(this).val())){
    $(this).prev("span").show();
  }
    
});

/*层叠*/
  var tipTxt = $(".tipTxt");
  for (var i = 0; i < tipTxt.length; i++) {
    tipTxt.eq(i).css({"z-index" : tipTxt.length - i});
  }

function banner_on() {
    joblist.tanchuang();
}

function onlinetalk_on(){
    joblist.onlinetalk();
}

function clocse_show_banner() {
    $("#show_banner").hide();
}

//弹窗显示 该企业是否有职位
function showSetJobAutomatic(job_id) {
    $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/index/setJobAutomatic"),$_smarty_tpl);?>
'+"job_id-"+job_id, {title:"自动过滤简历"});
}

//设置自动过滤简历开启
function setAutomaticOpen(job_id) {
    val = cookieutility.get('automatic');
    var is_show_success = 0;
    if (val == 'true') {
        is_show_success = 1;
    }

    if (!is_show_success) {
        $.showModal('<?php echo smarty_function_get_url(array('rule'=>"/index/automaticJob"),$_smarty_tpl);?>
'+"is_automatic-1-job_id-"+job_id+'-is_show_success-1', {title:"自动过滤简历"});
    } else {
        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/automaticJob/"),$_smarty_tpl);?>
'+'is_automatic-1-job_id-'+job_id+'-v-'+Math.random(), function (result) {
            if (result.error) {
                $.anchorMsg(result.error, {icon:'fail'});
                return;
            }

            $.anchorMsg(result.success, {icon:'success'}); 
            $("#automatic" + job_id).addClass("openBtncut");

            return;
        });
    }
}

//设置自动过滤简历关闭
function setAutomaticClose(job_id) {
    $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/index/automaticJob/"),$_smarty_tpl);?>
'+'is_automatic-0-job_id-'+job_id+'-v-'+Math.random(),function(result){
        if (result.error) {
            $.anchorMsg(result.error, {icon: 'fail'});
            return;
        }

        $.anchorMsg(result.success, {icon: 'success'}); 
        $("#automatic"+job_id).removeClass("openBtncut");
        return;
    });
}


$.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/account/twodimensioncode/"),$_smarty_tpl);?>
',function(result){
    if(result&&result.status) {
        var src = result.data.codeurl;
        $('#ewmBox #jewm').attr('src',src);
        $('#ewmBox').show();
    }
});

$("#ewmBox,#ewmBox1").find("a").click(function(){$(this).parents(".ewmBox").hide();return false;});


    $('.table tr').hover(function (e) {
        console.log('hhhddd');
        // $(this).find('.share-link').show().css('top',e.clientY).css('left',e.clientX)
        $(this).find('.share-box').show();
    },function (e) {
        $(this).find('.share-box').hide();
    });

    $('.table tr .share-link').click(function (e) {
       console.log($(this).attr('data-id'),'e.clientY:'+e.clientY,'e.screenY:'+e.screenY,'pageY:'+e.pageY);


        getPic.setContent("<?php echo smarty_function_get_url(array('rule'=>'/poster/GetPic/'),$_smarty_tpl);?>
"+"job_id-"+$(this).parent('.share-box').attr('data-id')).show();
        // $('.getPicDialog').css('top',e.pageY-200)
    });

</script>

 <?php $_template = new Smarty_Internal_Template("chat/chat.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</body>
</html>

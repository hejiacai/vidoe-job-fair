<?php /* Smarty version Smarty-3.0.7, created on 2020-03-23 16:23:33
         compiled from "app\templates\./resume/searchnew.html" */ ?>
<?php /*%%SmartyHeaderCode:119795e7872054a2ae1-91177225%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ee615f165c8489412da226e41d808d32af5d1fa2' => 
    array (
      0 => 'app\\templates\\./resume/searchnew.html',
      1 => 1584935757,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '119795e7872054a2ae1-91177225',
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>汇博人才网_招聘中心_简历搜索</title>
<!–[if lt IE9]> 
<script src='<?php echo smarty_function_version(array('file'=>"html5.js"),$_smarty_tpl);?>
'></script>  
<![endif]–>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>"jquery-1.8.3.min.js"),$_smarty_tpl);?>
"></script>
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>"v2-reset.css"),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>"v2-widge.css"),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href='<?php echo smarty_function_version(array('file'=>"base.css"),$_smarty_tpl);?>
' />
<link rel="stylesheet" type="text/css" href='<?php echo smarty_function_version(array('file'=>"comback.css"),$_smarty_tpl);?>
' />
<link rel="stylesheet" type="text/css" href='<?php echo smarty_function_version(array('file'=>"prSearch.css"),$_smarty_tpl);?>
' />
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
<script type="text/javascript" src="<?php echo smarty_function_version(array('file'=>'global.js'),$_smarty_tpl);?>
"></script>
<script type="text/javascript">
	hbjs.config({
		combos: {
			'@jobSorter':[
				'@confirmBox', '@autoComplete', 'product.jobSort.jobSorter', 
				'product.jobSort.jobSortDialog', 'product.jobSort.jobSortSearch', 
				'product.jobSort.jobSortMenu', 'product.sideMenu.sideSortMenuData'
			]
		}
	});
    hbjs.loadJS('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/js/v2/cqjob/common.js');
</script>
<style>
#splitLabel,.radTpe{margin-top:3px; float: left;}
#splitLabel input,#radWorkType1,#radWorkType2,#radComType1,#radComType2{ display: inline-block; vertical-align: middle; margin:0px 6px 0 6px;}
.newDemand{position: absolute;right: -34px;top: -20px;width: 34px;height: 22px;}
.dropx01{background-position: 136px center;}
.dropx02{background-position: 52px center;}

.sexpLine{width:500px; position: relative; height:40px; overflow: hidden; margin-left: 200px;}
.semLine{width:500px;height:20px;border-bottom: 1px dashed #ccc; position: absolute; top:0px; left: 0;}
.sexpLine a{ display: block; height:40px; line-height: 40px; background: #fff; width:150px; text-align: center; color: #00bec1; position: relative; z-index: 5; margin: 0 auto;}

.sexpLine i {background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/02.png) no-repeat; width: 18px; height: 10px; display: inline-block; vertical-align: middle;}
.sexpLine i.show {background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/01.png) no-repeat;}

.prSchPost{border: none; padding: 0 0 0 0;}
.prSchExpect{padding: 10px 0 0 0;}

.sexpLine a i{ font-family:'Arial Normal', 'Arial'; font-size: 13px }
.formRadx01{ border:1px solid #ccc; border-radius: 4px; background:#7d9ab3; float: left;margin:11px 0 0 10px; display: block;color: rgb(113, 113, 113);}
.formRadtitile{ color: rgb(113, 113, 113); float: left;margin:13px 0 0 30px;}
.prSchList div p span, .prSchList div p a {margin-top: 1px;}

.prSchList div div {margin-right: 20px;}

.formRadx01 .drop{border:none; line-height: 24px;position: relative;}
.dropAfter:after{
    content: '';
    display: block;
    position: absolute;
    width: 0;
    height: 0;
    top: 10px;
    right: 5px;
    border: 5px solid;
    border-color: #ccc transparent transparent;
}
.prSchList div div a{width:65px; height:25px; line-height: 25px;color: #0d4e80; margin-top: 3px;}
.prSchList div div a em{ vertical-align: middle;}

.prSchList div div a.prSchIcon1cut,.prSchList div div a.prSchIcon2cut{ color: #00eef1;}

.prSchList div div a:hover{color: #00eef1}
.prSchList div p {padding:16px 15px 16px 10px;}
.prSchList div p a {padding: 2px 5px;}

.formMod {margin-bottom: 10px}
.formMod .l{width:237px}
.formMod label.radio {margin-left: 10px;}
.formMod .sex-radio label {margin:0 15px 0 0;}

.drop b.dropIco {margin-right: 6px;}

.prSchExpR{padding-left:275px}
.prSchTabx dl {width: 552px}
.prSchTabx .pBoxT {width: 347px;}
.prSchTabx {color: #999; min-height: 110px;}
.prSchTabx span.station{color: #333}
.prSchTabx p{width: 450px; line-height: 28px;}
.prSchTabx .pBoxT p {width: 347px;}
.prSchTabx p b, .prSchTabx .pBoxT p b {padding: 0; font-weight: bold; color: #333}
.prSchTabx .pBoxT .prTabx3{color: #999}
.prSchListBm.detail .pslBm4, #resumeDetailList .prSchTx .pslBm4 {width: 140px}
.prSchListBm.detail .pslBm3, #resumeDetailList .prSchTx .pslBm3 {width: 250px}
.prSchListBm.detail .pslBm6, #resumeDetailList .prSchTx .pslBm6 {width: 120px}

#btnSeekerSave, #btnSeekerCancel {margin: 10px 0 0 40px;width: 50px;text-align: center;}
#btnSeekerSave{margin-left: 0;}
.prSchList div div a.prSchIcon1 em {background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/detail.png) 0px center no-repeat; display: inline-block; width:19px; height: 20px; margin-top: -2px;}
.prSchList div div a.prSchIcon1cut em, .prSchList div div a.prSchIcon1:hover em {background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/detail-clicked.png) 0px center no-repeat; display: inline-block; width:19px; height: 20px; margin-top: -2px;}
.prSchList div div a.prSchIcon2 em {background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/simple.png) 0px center no-repeat; display: inline-block; width:19px; height: 20px; margin-top: -2px;}
.prSchList div div a.prSchIcon2cut em, .prSchList div div a.prSchIcon2:hover em {background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/simple-clicked.png) 0px center no-repeat; display: inline-block; width:19px; height: 20px; margin-top: -2px;}

.expTxt {position:relative; z-index:1; _margin:6px 0 0 5px;}
.expTxt i.hbFntWes{color:#3d84b8; margin-left: 10px; font-size: 17px; position:absolute; right: -16px;}
.expTxt .expBox,.expBoz {position:absolute; top:34px; left:-181px; background:#fffbe8;width: 386px; padding: 10px;} 
.expTxt .expBox em,.expBoz em{ background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/common/common.gif) no-repeat -174px 0;width: 20px;height: 10px;display: inline-block;top: -10px;right: 206px;position: absolute;}
.expTxt .expBox p,.expBoz p{line-height:20px; zoom:1;}
.inputTextBox {width: auto}
.expBoz{ left: 173px; display: none; z-index: 1010;}
span.divided {color: #d9d9d9; font-family: "宋体"; font-weight: normal; margin:0 6px;}

.mutilpleSelectBox{padding:0;width:380px;}
.mutilpleSelectBox .icon{
	color:#ccc;margin-right:11px;
}
<?php if ($_smarty_tpl->getVariable('this_own_man')->value){?>
.buyPoints .ui_dialog_container{height: 300px !important;}
<?php }else{ ?>
.buyPoints .ui_dialog_container{height: 200px !important;}
<?php }?>
<!---20151208 微信二维码 start -->
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
 .prSchTab{ padding-bottom:10px;position: relative;}
.chatHuoyue{ display:inline-block; padding:0 6px; border-radius:2px; background:#13dbac; color:#fff; margin-left:5px;font-size:12px;vertical-align: middle; line-height: 18px;}
.fuzzyRadio{ 
	position: absolute;
    top: 5px;
    left: 265px;
}

.tip-iconx{
	position: absolute;
    top: 2px;
    left: 355px;
}
.fuzzyRadio input{    display: inline-block;
    vertical-align: -2px;
    margin: 0px 6px 0 6px;}
</style>

<style>
.formMod.lang select{
	height: 30px;
	border: 1px solid #ccc;
	outline: none;
	background: #fff;
}
.resume-icon{
    position:absolute;top:0;left:0;height:50px;
}
.formMod.lang .yuyan{
	width: 100px;
}

.formMod.lang .shuliandu{
	width: 80px;
}

.formMod.lang .zhengshu{
	width: 194px;
}
.prSchTx{
    position: relative;
}
.prSchTx a:visited span{
	color:#800080
}
.newResume{
    position: relative;
    top: 2px;
    margin-right: 3px;
}
.resume-box{
    clear: both;display: inline-block;vertical-align: middle;margin: 10px 0 0 20px;
    color: rgb(113, 113, 113);
}
.resume-box>div{
    display: inline-block;
    position: relative;
}
.reTime{
    display: inline-block;vertical-align: middle;
}
.newResumeBox{
    clear: both;margin-bottom: 5px;
}
/* .degreeSelect ul li{
	padding:0;
} */
</style>

</head> 
<body>
<?php $_template = new Smarty_Internal_Template("new_header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par',"全职招聘");$_template->assign('cur',"简历搜索"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<!--<div style="width:1000px;margin:auto;">
    <a href="<?php echo base_lib_Constant::MAIN_URL_NO_HTTP;?>
/about/message/contentsearch-1" style="text-align:right;float:right;display:block;" target="_blank">新版的搜索简历好用吗？请将您的建议尽快告诉我们</a>
    <div style='clear:both'></div>
</div>-->
<div class="prSchBg">
    <div class="prSchToptit">
    	<h2>搜索简历</h2>
        <div class="prSchRt">
            <!-- <a href="javascript:void(0)" id="btnSeeker">常用搜索</a> -->
            <span>简历编号搜索</span>
            <input type="text" value="" name="t" class="prSchTxt" id="txtResumeID"/>
            <input type="button" value="搜索" name="btn" class="prSchBtn" id="btnSearchResumeID"/>
        </div>
        <div class="clear"></div>
    </div>
	<?php if (!empty($_smarty_tpl->getVariable('job_list',null,true,false)->value)){?>
	<div class="searchJobKeyWords clearfix">
		<span class="searchJobKeyWords-title">按职位搜索: </span>
		<div class="searchJobKeyWords-box clearfix">
			<div class="searchJobKeyWords-ul clearfix">
				<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('job_list')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
?>
				<span><a href="<?php echo smarty_function_get_url(array('rule'=>'/resumesearchnew'),$_smarty_tpl);?>
?job_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['job_id'];?>
&from=self"><?php echo $_smarty_tpl->tpl_vars['v']->value['station'];?>
</a></span>
				<?php }} ?>
				
				<span class="moreJobs-name moreJobs_name_up">更多职位<i class="moreup-icon"></i></span>
			</div>
		</div>
		<span class="moreJobs-name moreJobs_name_down">更多职位<i class="moredown-icon"></i></span>
		<script type="text/javascript">
			
			var joblistKeyWords_span=null,realWidth=0;
			joblistKeyWords_span= $('.searchJobKeyWords-ul span');
			realWidth=joblistKeyWords_span.width() * joblistKeyWords_span.length+joblistKeyWords_span.length*16;/*向右边的间距*/
			if(  realWidth > $('.searchJobKeyWords-box').width() ){
				$('.moreJobs_name_down').show();
				$('.moreJobs_name_up').show();
			}
			$(".moreJobs_name_down").click(function(){
				$(this).hide();
				$(".searchJobKeyWords-box").width(824);
				$(".searchJobKeyWords-ul").width(834);
				$(".searchJobKeyWords-ul").css("overflow","visible");
				$(".searchJobKeyWords-ul").css("white-space","normal");
				$(".searchJobKeyWords-ul").css("height","auto");
			})
			$(".moreJobs_name_up").click(function(){
				$(".moreJobs_name_down").show();
				$(".searchJobKeyWords-box").width(730);
				$(".searchJobKeyWords-ul").width(740);
				$(".searchJobKeyWords-ul").css("overflow","hidden");
				$(".searchJobKeyWords-ul").css("white-space","nowrap");
				$(".searchJobKeyWords-ul").css("height","26px");
			})
			// $('.moreJobs-name').click(function(){
			// 	if($('.moreJobs-name i').hasClass('moredown-icon')){
			// 		$('.moreJobs-name i').removeClass('moredown-icon');
			// 		$('.searchJobKeyWords-ul').css('overflow','visible');
			// 	}else{
			// 		$('.moreJobs-name i').addClass('moredown-icon');
			// 		$('.searchJobKeyWords-ul').css('overflow','hidden');
			// 	}
			// })
		</script>
	</div>	
    <?php }?>
	
	<div class="recentSearchInfo">
			<div class="prSchExpK">
				<p id="spanSearchCondition"></p>
			</div>
			
			<a href="javascript:void(0)" id="btnSeeker">常用搜索</a>
	</div>
		
    <form id="frmResumeSearch" method="get" action="<?php echo smarty_function_get_url(array('rule'=>'/resumesearch'),$_smarty_tpl);?>
" >
        <div class="prSchPost">
            <div class="formMod jobAddMod checkMod">
                <div class="l"><b>全文关键词</b></div>
                <div class="r inputTextBox">
                    <input type="text" value="<?php echo $_smarty_tpl->getVariable('k')->value;?>
" name="keyword" class="prSchTxt2" <?php if (!$_smarty_tpl->getVariable('k')->value){?>placeholder="在简历全文中搜索，多个词用空格隔开"<?php }?> style="width:370px;"/>
										<!-- <label for="recentWork" class="fuzzyRadio">
										  <input type="checkbox" name="recentWork" id="recentWork" value="1" class="chb" />最近工作经历
										</label> -->
                </div>
                <label id="splitLabel" for="split" class="radio">
                    <input type="checkbox" name="split" id="split" value="1" class="chb" />包含任一关键词
                </label>
                <span class="tipTxt expTxt zindex">
                     <i class="hbFntWes tip-icon"><a id="btnTip" href="javascript:void(0);" data-toggle="tooltip" data-placement="bottom" style="margin-top: 4px;">&#xf059;</a></i>
                    <div class="expBox" id="expBox" style="display:none">
                        <em></em>
                        <div class="expBoxC">
                            <p>全文关键词可允许搜索多个关键词，每个词之间用空格间隔；</p>
                            <p>采用多个关键词搜索时，不再单独进行分词，按照空格间隔进行分词；</p>
                            <p>例如<b>“销售  营业员  促销”</b>，分词结果是<b>“销售”,“营业员”,“促销”</b>。</p>
                        </div>
                    </div>
                </span>
                <div class="clear"></div>
            </div>
            <div class="formMod jobAddMod checkMod">
                <div class="l"><b>曾任职位</b></div>
                <div class="r inputTextBox" style="overflow: inherit;">
                    <input type="text" value="<?php echo $_smarty_tpl->getVariable('k')->value;?>
" name="station" class="prSchTxt2" placeholder="搜索做过的工作岗位，例如销售" style="width:370px"/>
                    <label for="fuzzy" class="fuzzyRadio">
	                    <input type="checkbox" name="fuzzy" id="fuzzy" value="1" class="chb" />模糊匹配
	                  </label>
	                <i class="hbFntWes tip-iconx" style="font-size: 17px;"><a id="btnTipx2" href="javascript:void(0);" style="margin-top: 4px;">&#xf059;</a></i>
	                <div class="expBoz" id="expBoz" style="display:none">
	                    <em></em>
	                    <div class="expBoxC">
	                        <p>例如搜索：“ 电话销售”<br />默认结果仅包含“ 电话销售”整词<br />模糊匹配结果分为“ 电话”，“ 销售”两个词</p>
	                    </div>
	                </div>
                </div>
                <label class="radTpe radio" for="radWorkType1">
                    <input type="radio" id="radWorkType1" name="workType" value="0" class="chb" />最近职位
                </label>
                <label class="radTpe radio" for="radWorkType2">
                    <input type="radio" id="radWorkType2" name="workType" value="1" class="chb" checked/>全部经历
                </label>
                <div class="clear"></div>
            </div>
            <div class="formMod checkMod" style="display: <?php if ($_smarty_tpl->getVariable('is_show_jobsort')->value){?>block;<?php }else{ ?>none;<?php }?>">
                <div class="l">期望职位类别</div>
                <div class="r">
                
                	<div id="txtJobsort" class="formText mutilpleSelectBox" style="width: 380px;">
                        <b class="hbFntWes icon">&#xf03a;</b>
                        <ul class="label">
                            <?php  $_smarty_tpl->tpl_vars['jobsort'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['jobsortid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('jobsorts')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['jobsort']->key => $_smarty_tpl->tpl_vars['jobsort']->value){
 $_smarty_tpl->tpl_vars['jobsortid']->value = $_smarty_tpl->tpl_vars['jobsort']->key;
?>
                            <li data-id="<?php echo $_smarty_tpl->tpl_vars['jobsortid']->value;?>
"><a href="javascript:"><?php echo $_smarty_tpl->tpl_vars['jobsort']->value['jobsort_name'];?>
<span class="close">×</span></a></li>
                            <?php }} ?>
                        </ul>
                        <input type ="hidden" name="jobsort" id="jobsort" value="<?php echo $_smarty_tpl->getVariable('jobsort_arr')->value[0];?>
">
                    </div>
                
                	<!--
                    <span class="drop formText JobCayDrop zindex" id="txtJobsort" style="width:380px"></span>
                    -->
                </div>
                <div class="clear"></div>
            </div>
            <div class="formMod jobAddMod checkMod">
                <div class="l">现居地</div>
                <div class="r">
                    <span class="drop formText jobAddDrop zindex" id="txtNativeArea" style="width:380px"></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formMod jobAddMod checkMod">
                <div class="l">期望地区</div>
                <div class="r">
                    <span class="drop formText jobAddDrop zindex" id="txtExpArea" style="width:380px"></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formMod">
                <div class="l">工作年限</div>
                <div class="r">
                    <span class="drop dropx02 zindex" id="workYMinSel" style="width:67px;"></span>
                    <span class="tipTxt font12" style="margin:0 8px;">~</span>
                    <span class="drop dropx02 zindex" id="workYMaxSel" style="width:67px;"></span>
                </div>
                <div class="l" style="width:48px">学历</div>
                <div class="r">                             
                    <span class="drop dropx02 zindex" id="degreeMinSel" style="width:67px"></span>
                    <span class="tipTxt font12" style="margin:0 8px;">~</span>
                    <span class="drop dropx02 zindex" id="degreeMaxSel" style="width:67px"></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formMod">
                <div class="l">年龄</div>
                <div class="r">
                    <span class="formText">
                        <input type="text" id="txtAgeLower" name="txtAgeLower" value="<?php echo $_smarty_tpl->getVariable('amin')->value;?>
" style="width:57px;" class="text">
                    </span>
                    <span class="tipTxt font12" style="margin:0 8px;">~</span>
                    <span class="formText">
                        <input type="text" id="txtAgeUpper" name="txtAgeUpper" value="<?php echo $_smarty_tpl->getVariable('amax')->value;?>
" style="width:57px;" class="text">
                    </span>
                </div>
                <div class="l" style="width:48px">性别</div>
                <div class="r sex-radio">                             
                    <label id="splitLabel" for="radSex1">
                        <input type="radio" id="radSex1" name="sexRad" value="0" class="chb" checked/>不限
                    </label>
                    <label id="splitLabel" for="radSex2">
                        <input type="radio" id="radSex2" name="sexRad" value="1" class="chb" />男
                    </label>
                    <label id="splitLabel" for="radSex3">
                        <input type="radio" id="radSex3" name="sexRad" value="2" class="chb" />女
                    </label>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formMod">
                <div class="l">到岗时间</div>
                <div class="r">
                    <span class="drop formText jobAddDrop zindex" id="txtAccession" style="width:380px; background-position:355px center;"></span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="sexpLine">
            <div class="semLine"></div>
            <a href="javascript:void(0)" id="getmore"><i class="show"></i>展开更多搜索条件</a>
        </div>
        <div class="prSchExpect" style="display:none">
            <div class="formMod jobAddMod checkMod">
                <div class="l">期望职位</div>
                <div class="r">
                    <div class="inputTextBox">
                        <input type="text" value="<?php echo $_smarty_tpl->getVariable('es')->value;?>
" style="width:370px;" name="eStation" class="prSchTxt2" placeholder="请输入期望职位，例如项目主管" />
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formMod JobIndDrop checkMod">
                <div class="l">期望行业</div>
                <div class="r">
                    <span class="drop formText JobIndDrop zindex" id="txtCalling" style="width:380px"></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formMod jobAddMod checkMod">
                <div class="l">公司名称</div>
                <div class="r">
                    <div class="inputTextBox">
                        <input type="text" value="<?php echo $_smarty_tpl->getVariable('cn')->value;?>
" style="width:370px;" name="company" class="prSchTxt2" placeholder="请输入公司名称" />
                    </div>
                </div>
                <label class="radTpe radio" for="radComType1">
                    <input type="radio" id="radComType1" name="comType" value="0" class="chb" />最近公司
                </label>
                <label class="radTpe radio" for="radComType2">
                    <input type="radio" id="radComType2" name="comType" value="1" class="chb" checked/>所有公司
                </label>
                <div class="clear"></div>
            </div>
            <div class="formMod jobAddMod checkMod">
                <div class="l">专业名称</div>
                <div class="r">
                    <div class="inputTextBox">
                        <input type="text" value="<?php echo $_smarty_tpl->getVariable('md')->value;?>
" style="width:370px;" name="majorDesc" class="prSchTxt2" placeholder="请输入专业名称，例如工商管理" />
                    </div>
                </div>
                <div class="clear"></div>
            </div>
						<!-- <div class="formMod jobAddMod checkMod lang">
						    <div class="l">语言要求</div>
						    <div class="r">
						        <select name="yuyan" id="" class="yuyan">
											<option value="">不限</option>
											<option value="1">英语</option>
										</select>
										<select name="shuliandu" id="" class="shuliandu">
											<option value="">不限</option>
											<option value="1">熟练级以上</option>
										</select>
										<select name="zhengshu" id="" class="zhengshu">
											<option value="">不限</option>
											<option value="1">大学英语六级</option>
										</select>
						    </div>
						    <div class="clear"></div>
						</div> -->
            <div class="formMod">
                <div class="l" style="display: none">婚姻状况</div>
                <div class="r" style="display: none">
                    <span class="formRad">
                        <span class="drop" id="marriageSel" style="width:100px;height:30px;"></span>
                    </span>
                </div>
                <div class="l" style="/** width:82px; */">期望月薪</div>
                <div class="r">
                    <span class="formText">
                        <input type="text" id="salaryMinSel" name="salaryMinSel" value="<?php echo $_smarty_tpl->getVariable('amin')->value;?>
" style="width:68px;" class="text">
                    </span>
                    <span class="tipTxt font12" style="margin:0 10px;">~</span>
                    <span class="formText">
                        <input type="text" id="salaryMaxSel" name="salaryMaxSel" value="<?php echo $_smarty_tpl->getVariable('amin')->value;?>
" style="width:68px;" class="text">
                    </span>
                    <span class="tipTxt font12" style="margin:0 10px;">元</span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formMod JobIndDrop checkMod">
                <div class="l">身高</div>
                <div class="r">
                    <span class="formText">
                        <input type="text" id="txtStatureLower" name="txtStatureLower" value="<?php echo $_smarty_tpl->getVariable('sml')->value;?>
" style="width:71px;" class="text"/>
                    </span>
                    <span class="tipTxt font12" style="margin:0 10px;">~</span>
                    <span class="formText">
                        <input type="text" id="txtStatureUpper" name="txtStatureUpper" value="<?php echo $_smarty_tpl->getVariable('smb')->value;?>
" style="width:71px;" class="text"/>
                    </span>
                    <span class="tipTxt font12">厘米</span>
                </div>
                <div class="clear"></div>
            </div>

            <div class="formMod">
                <div class="l">照片</div>
                <div class="r sex-radio" style="margin-top: 2px;">
                    <label for="photoType1">
                        <input type="radio" id="photoType1" name="photoType" value="0" class="chb" checked style="display: inline-block;vertical-align: middle;margin: 0px 6px 0 6px;" />不限
                    </label>
                    <label for="photoType2">
                        <input type="radio" id="photoType2" name="photoType" value="1" class="chb" style="display: inline-block;vertical-align: middle;margin: 0px 6px 0 6px;" />有头像/生活照

                    </label>
                </div>
                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </form>
    <div class="prSchExpR">
        <input type="button" value="搜 索" name="t" class="btn1 btnsF16 prSchBtn3" id="btnSearch"/>
        <input type="button" value="保存为常用搜索" name="t" class="btn3 btnsF16 prSchBtn4" id="btnSaveSeeker"/>
        <a href="javascript:void(0)" id="clearConditions" style="margin-left:16px">清空搜索条件</a>
    </div>
    <div class="prSchExpK" id="spanSearchCondition">
    </div>
</div>
<div class="prSchBg" id="resumePart" style="display:none">
	<div class="prSchList">
    	<ul id="firmSchTab">
        	<li data-value="1" class="cut"><a href="javascript:void(0)"><b>刷新时间</b><span class="resumecount"></span></a></li>
            <li data-value="2"><a href="javascript:void(0)"><b>登录时间</b><span class="resumecount"></span></a></li>
			<li data-value="3"><a href="javascript:void(0)"><b>默认排序</b><span class="resumecount"></span></a><i></i></li>
        </ul>
        <!-- <span class="formRadtitile">刷新时间：</span>
        <span class="formRad formRadx01">
            <span class="drop" id="timeSel" style="width:100px;height:24px;"></span>
        </span> -->
        <div>
        	<p id="btnPageSize">
            	<span>每页显示：</span>
                <a href="javascript:void(0)" class="cut" data-value="20">20</a>
                <a href="javascript:void(0)" data-value="40">40</a>
                <a href="javascript:void(0)" data-value="60">60</a>
            </p>
            <div id="btnDisplayType">
            	<a href="javascript:;" class="prSchIcon1 prSchIcon1cut" data-type="1"><em></em>详情</a>
                <a href="javascript:;" class="prSchIcon2" data-type="2"><em></em>列表</a>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="newResumeBox">
        <div class="resume-box">
            <input type="checkbox" class="newResume" name="newResume" value="true">
            <div>
                <span>新简历</span>
                <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/newDemand.png" class="newDemand">
            </div>
        </div>
        <div class="reTime">
            <span class="formRadtitile">刷新时间</span>
            <span class="formRad formRadx01">
                <span class="drop dropAfter" id="timeSel" style="width:100px;height:24px;"></span>
            </span>
        </div>
    </div>
    <div class="prSchListBm simple" style="display:none">
    	<span class="pslBm1">姓名</span>
        <span class="pslBm2">性别</span>
        <span class="pslBm2">年龄</span>
        <span class="pslBm2">学历</span>
        <span class="pslBm4">现居地</span>
        <span class="pslBm6">工作年限</span>
        <span class="pslBm7">最近工作</span>
        <span class="pslBm3">最近工作公司</span>
        <span class="pslBm5 sortType">刷新简历</span>
    </div>
    <div class="prSchListBm detail">
        <span class="pslBm1">姓名</span>
        <span class="pslBm2">性别</span>
        <span class="pslBm2">年龄</span>
        <span class="pslBm4">现居地</span>
        <span class="pslBm8">学历</span>
        <span class="pslBm3">学校</span>
        <span class="pslBm6">专业</span>
        <span class="pslBm7">时间</span>
    </div>
    <div class="prSchTabBg" data-type="1" id="resumeDetailList">
    </div>
    <div class="prSchTabBg" style="display:none" data-type="2" id="resumeSimpleList">
    </div>
    <div id="pager"></div>
</div>
<div class="prSchBg" id="noData" style="display:none">
    <div class="noData">没有数据</div>
</div>
<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/PC-loading.gif" id="loading" style="display:none"/>

<?php $_template = new Smarty_Internal_Template("msgpop.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<section class="floatRT"><a href="<?php echo smarty_function_get_url(array('rule'=>'/about/message','domain'=>'main'),$_smarty_tpl);?>
" target="_blank" class="serviceLink">我有问题要反馈</a><b></b></section>
<script> 
var user_select_so=''; //是否用户主动选择排序
var isShowNewr = false            //是否添加新简历搜索条件
function getQueryString(param) { //param为要获取的参数名 获取不到为null
    var currentUrl = window.location.href; 
    var arr = currentUrl.split("?");
    if (arr.length > 1) {
        arr = arr[1].split("&");
        for (var i = 0; i < arr.length; i++) {
            var tem = arr[i].split("="); 
            if (tem[0] == param) {
                return tem[1];
            }
        }
        return null;
    }
    else {
        return null;
    }
}
 from = "<?php echo $_smarty_tpl->getVariable('from')->value;?>
";//index：来自招聘首页
if(from=='index'){
    $('.newResume').prop('checked',true)
    isShowNewr = true
}
can_use_newresume_search =  parseInt('<?php echo $_smarty_tpl->getVariable('can_use_newresume_search')->value;?>
');//1 可以使用 新简历搜索条件  0：静止使用
if(can_use_newresume_search==0){
    $('.resume-box').hide()
}
var salaryItems = [{value:'0',label:'不限'}];
<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('salaries')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
    salaryItems.push({value:'<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
',label:'<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
'});
<?php }} ?>

var workYearItems = [{value:'0',label:'不限'}];
<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('workyear')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
    workYearItems.push({value:'<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
',label:'<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
'});
<?php }} ?>

var degreeItems = [{value:'0',label:'不限'}];
<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('degrees')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
    var value = '<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
';
	if(value.length>4){
		value=value.slice(0,4)
	}
    degreeItems.push({value:'<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
',label:value});
<?php }} ?>

var sexItems = [{value:'0', label:'不限'}, {value:'1', label:'男'}, {value:'2', label:'女'}];
var marriageItems = [{value:'0', label:'不限'}, {value:'1', label:'未婚'}, {value:'2', label:'已婚'}];

var jobStateItems = [{value:'0',label:'不限'}];
<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('job_states')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
    jobStateItems.push({value:'<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
',label:'<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
'});
<?php }} ?>

var accessionItems = [{value:'0',label:'不限'}];
<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('accessions')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
    accessionItems.push({value:'<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
',label:'<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
'});
<?php }} ?>

var timeFreshItems = [{value:'0', label: '&nbsp;不限'}];
var timeLoginItems = [{value:'0', label: '&nbsp;不限'}];
<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('timelimit')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
    timeFreshItems.push({value:'<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
',label:'<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
刷新'});
    timeLoginItems.push({value:'<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
',label:'<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
登陆'});
<?php }} ?>

var time = 0;
hbjs.use('@select, @jobSorter, @areaMulitiple, @calling, @jobTooltip, @actions, @hbCommon, @jobDialog', function(m) {
    var $ = m['jquery'].extend(
			m['cqjob.areaMulitiple'], m['cqjob.calling'], m['cqjob.jobsort'], 
			m['cqjob.jobTooltip'], m['cqjob.actions'], m['cqjob.hbCommon'], m['cqjob.jobDialog']),
        select = m['widge.select'],
        confirmBox = m['widge.overlay.confirmBox'],
        cookie = m['tools.cookie'],
        Dialog = m['widge.overlay.hbDialog'],
		jobSorter = m['product.jobSort.jobSorter'];
//  模糊匹配说明
	$('#btnTipx2').hover(function(){
		$('.expBoz').toggle();			
	});
    
    $("#resumeDetailList").on("click", ".sendToWorkmate", function(){
        if($(this).parent().next('.sendTo_img').is(':visible')){
            $('.sendTo_img').hide();
        }else{
            $('.sendTo_img').hide();
            var find_img = $(this).parent().next('.sendTo_img').find("img").attr('data-img-source');
            $(this).parent().next('.sendTo_img').find("img").attr('src',find_img);
            $(this).parent().next('.sendTo_img').show();
        }

    });
    
    var workYMinSelect = new select({
        trigger: $('#workYMinSel'),
        className: 'dropv2_select',
        align: {baseXY: [0, '100%-1']},
        selectName: 'workYMinSel',
        dataSource: workYearItems,
        selectedValue: "0",
        isHidDefault: false,
        zIndex: 999,
        selectCallback: {
            isDefault: true
        }
    }),
    workYMaxSelect = new select({
        trigger: $('#workYMaxSel'),
        className: 'dropv2_select',
        align: {baseXY: [0, '100%-1']},
        selectName: 'workYMaxSel',
        dataSource: workYearItems,
        selectedValue: "0",
        isHidDefault: false,
        zIndex: 999,
        selectCallback: {
            isDefault: true
        }
    }),
    degreeMinSelect = new select({
        trigger: $('#degreeMinSel'),
        className: 'dropv2_select degreeSelect',
        align: {baseXY: [0, '100%-1']},
        selectName: 'degreeMinSel',
        dataSource: degreeItems,
        selectedValue: "0",
        isHidDefault: false,
        zIndex: 999,
        selectCallback: {
            isDefault: true
        }
    }),
    degreeMaxSelect = new select({
        trigger: $('#degreeMaxSel'),
        className: 'dropv2_select degreeSelect',
        align: {baseXY: [0, '100%-1']},
        selectName: 'degreeMaxSel',
        dataSource: degreeItems,
        selectedValue: "0",
        isHidDefault: false,
        zIndex: 999,
        selectCallback: {
            isDefault: true
        },
    }),
    marriageSel = new select({
        trigger: $('#marriageSel'),
        className: 'dropv2_select',
        align: {baseXY: [0, '100%-1']},
        selectName: 'marriageSel',
        dataSource: marriageItems,
        selectedValue: "0",
        isHidDefault: false,
        selectCallback: {
            isDefault: true
        }
    }),
    accessionSel = new select({
        trigger: $('#txtAccession'),
        className: 'dropv2_select',
        align: {baseXY: [0, '100%-1']},
        selectName: 'txtAccession',
        dataSource: accessionItems,
        selectedValue: "0",
        isHidDefault: false,
        zIndex:999,
        selectCallback: {
            isDefault: true
        }
    }),
    areaSelect = $('#txtNativeArea').multiplearea({
        max : 5,
        hddName : 'nativeArea',
        isLimit : true
        <?php if (!empty($_smarty_tpl->getVariable('default_area',null,true,false)->value)&&$_smarty_tpl->getVariable('default_area')->value=='1501'){?>
            ,
            selectItems:['<?php echo $_smarty_tpl->getVariable('default_area')->value;?>
']
        <?php }?>
    }),
    expAreaSelect = $('#txtExpArea').multiplearea({
        max : 5,
        hddName : 'expArea',
        isLimit : true
        <?php if (!empty($_smarty_tpl->getVariable('default_area',null,true,false)->value)){?>
            ,
            selectItems:['<?php echo $_smarty_tpl->getVariable('default_area')->value;?>
']
        <?php }?>
    }),
    callingSelect = $('#txtCalling').calling({
        max : 5,
        type : 'multiple',
        isLimit : true,
        hddName : 'calling'
    });

    var timeSel = new select({
        trigger: $('#timeSel'),
        className: 'dropv2_select',
        align: {baseXY: [0, '100%-1']},
        selectName: 'timeSel',
        dataSource: timeFreshItems,
        selectedIndex: 0,
        isHidDefault: false,
        selectCallback: {
            isDefault: true
        }
    }),
	jobSorterBox = new jobSorter({
		trigger: $('#txtJobsort'),
		selectedId:  $('#txtJobsort').val(),
		search: {
			dataSource: '//www.huibo.com/jobsort/searchJobsort?jobSortName={{query}}&pageSize={{pageSize}}&callback=jobsortcallback',
			size: 10
		}
	});
	jobSorterBox.addSelect=function(arr_value,arr_label){
		$.each(arr_value,function(i,e){
			jobSorterBox.addItem({value:e,label:arr_label[i]})
		})
	}

	$('input.prSchTxt2').placeHolder();
    
    var resumesearch = {
        
        init : function() {
            $.setIndex("zindex");
            timeSel.on('change', function(e) {
                var queryString = resumesearch.getQueryString();
                var url = "/resumesearchnew/search?" + queryString + "&p=1";
                var timetype = $("input[name=timeSel]").val();
                url += '&tt=' + timetype;
                resumesearch.searchResumeData(url);
            });

            /* 保存搜索器 */
            var saveSeekerDialog = new Dialog({
                idName : 'saveSeeker',
                title : '保存常用搜索',
                close : 'x',
                isAjax : true,
                width : 315
            });
            $('#btnSaveSeeker').click(function() {
                isShowNewr = false
				var findStr = resumesearch.glueKeyWords()
				if(findStr==""){
					confirmBox.timeBomb('请输入搜索条件后保存', {width:'auto',name : 'warning'});
					return false
				}
                $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resumesearchnew/canaddseeker/"),$_smarty_tpl);?>
', function(data) {
                    if (data.status != 'succeed') {
                        confirmBox.timeBomb(data.msg, {width:'auto',name : 'warning'});
                    } else {
						
                        saveSeekerDialog.setContent('<div class="formMod">'
                            + '<p>'
                            + '<input type="text" class="text" id="txtSeekerName" value="' + findStr + '" style="width:292px;border:none;outline:none" readonly="readonly" />'
                            + '</p><div><a href="javascript:void(0);" id="btnSeekerSave" class="btn1 btnsF12">保存</a>'
                            + '<a href="javascript:void(0);" id="btnSeekerCancel" class="btn3 btnsF12">取消</a>'
                            + '</div></div>').show();
                    }
                });
            });

            $(".saveSeeker").on("click", "#btnSeekerSave", function (e) {
                var seeker = resumesearch.getQueryString(false);
                var params = {seekerName:$("#txtSeekerName").val().replace(/\s+/," "), seekerconter:seeker};
                $.post('<?php echo smarty_function_get_url(array('rule'=>"/resumesearchnew/seekeradddo/"),$_smarty_tpl);?>
', params, function(data) {
                    data = eval("(" + data + ")");
                    if (data && data.error) {
                        confirmBox.timeBomb(data.error, {name : 'fail', width: 400});
                        return;
                    }
                    confirmBox.timeBomb('保存常用搜索成功', {name : 'success', width: 300});
                    saveSeekerDialog.hide();
                });
            });

            $(".saveSeeker").on("click", "#btnSeekerCancel", function (e) {
                saveSeekerDialog.hide();
            });

            $("input[name=station],input[name=company],input[name=keyword],input[name=eStation],input[name=majorDesc]").keydown(function(e) {
                if (e.keyCode == 13) {
                    isShowNewr = true
                    $("#btnSearch").click();
                }
            });

            $("#getmore").on("click", function (e) {
                if ($(this).parent().next('div.prSchExpect').is(":hidden")) {
                    $(this).html("<i></i>收起更多搜索条件")
                    $("div.prSchExpect").show();
                } else {
                    $(this).html("<i class='show'></i>展开更多搜索条件")
                    $("div.prSchExpect").hide();
                }
            });

            $('#btnTip').hover(function() {
                $('#expBox').show();
            }, function() {
                $('#expBox').hide();
            });

            /* 清空搜索条件 */
            $("#clearConditions").on('click', function() {
                /* 所有下拉选择框 */
                workYMinSelect.setSelectedIndex(0);
                workYMaxSelect.setSelectedIndex(0);
                degreeMinSelect.setSelectedIndex(0);
                degreeMaxSelect.setSelectedIndex(0);
                marriageSel.setSelectedIndex(0);
                accessionSel.setSelectedIndex(0);                

                /* 所有输入框 */
                $("input[name=station]").val("");
                $("input[name=company]").val("");
                $("input[name=keyword]").val("");
                $("input[name=eStation]").val("");
                $("input[name=majorDesc]").val("");
                $("input[name=txtAgeLower]").val("");
                $("input[name=txtAgeUpper]").val("");
                $("input[name=txtStatureLower]").val("");
                $("input[name=txtStatureUpper]").val("");
                $("input[name=salaryMinSel]").val("");
                $("input[name=salaryMaxSel]").val("");

                // 单选框
                $("#radSex1").attr("checked", "checked").siblings("input[type=radio]").removeAttr('checked');
                $("#radWorkType1").attr("checked", "checked").siblings("input[type=radio]").removeAttr('checked');
                $("#radComType1").attr("checked", "checked").siblings("input[type=radio]").removeAttr('checked');
				$('#photoType1').click()

                areaSelect.resetMultipleareaValue();
                expAreaSelect.resetMultipleareaValue();
                callingSelect.resetCallingValue();
				jobSorterBox.clearAllItem()
                if(!typeof(jobsortSelect)){jobsortSelect.resetJobsortValue();}
            });
            
            dialog = new Dialog({
                idName : 'seeker',
                title : '常用搜索',
                close : 'x',
                isAjax : true,
                width : 470
            });

            /* 删除搜索器 */
            $(".seeker").on("click", ".remove", function(e) {
                var _this = $(this);
                confirmBox.confirm('您确定删除该常用搜索?', '确认框', function() {
                    var _confirm = this;
                    $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resumesearchnew/seekerdel/"),$_smarty_tpl);?>
',{seekerid : _this.attr('data-id')},
                        function(result) {
                            _confirm.hide();
                            if (result && result.error) {
                                confirmBox.timeBomb('系统提示', {
                                    width : 200,
                                    name : 'fail'
                                });
                                return;
                            }

                            confirmBox.timeBomb('删除常用搜索成功', {
                                width : 240,
                                name : 'success'
                            });

                            _this.closest("li").remove();
                            if ($('#dataContent').find('li').length <= 0) {
                                $('#dataContent').parent().empty().append("<li class=\"noData\">未创建常用搜索</li>");
                            }
                        });
                });
            });

            $(".seeker").on("click", ".operate", function(e) {
                var _this = $(this);
                var url = _this.attr("data-url") + "&p=1";

                resumesearch.searchResumeData(url);
            });

            $("#spanSearchCondition").on("click", "a", function(e) {
                var _this = $(this);
                var url = _this.attr("data-url") + "&p=1";
                resumesearch.searchResumeData(url);
				if(url.indexOf('&sn=') > -1  || url.indexOf('&k=') > -1){
					$("#firmSchTab li").eq(2).css('display','block');
					$("#firmSchTab li").removeClass('cut').eq(2).addClass('cut');
					$('.reTime').hide();
				}
            })

            $('#btnSeeker').click(function() {
                dialog.setContent("<?php echo smarty_function_get_url(array('rule'=>'/resumesearchnew/seeker/'),$_smarty_tpl);?>
").show();
            });

            /* search for resume_id */
            $('#btnSearchResumeID').click(function() {
                
                $('#btnSearchResumeID').attr('href', 'javascript:void(0)');
                $('#btnSearchResumeID').removeAttr('target');
                
                var resumeID = $('#txtResumeID').val();
                if (/^[0-9]{1,9}$/.test(resumeID) == false) {
                    $.anchorMsg('请输入正确的简历编号', {icon: 'warning'});
                    return false;
                }
                var parms = {'resume_id': resumeID };
                $.ajax({
                    url : '<?php echo smarty_function_get_url(array('rule'=>"/resumesearchnew/searchid/"),$_smarty_tpl);?>
',
                    async : false,
                    type : 'GET',
                    dataType : 'json', 
                    contentType:"application/json; charset=utf-8",
                    data : parms, 
                    success : function(data) {
                        if (data && data.isNeedLogin) {
                            window.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/login/'),$_smarty_tpl);?>
";
                        }
                        if (data.status != 'succeed' || data.resumeid == undefined){
                            $.anchorMsg('对不起，该简历不存在或未向您公开', {icon: 'warning'});
                        } else {
                            window.open('/resume/resumeshow/type-network-src-search-resumeid-' + data.resumeid +'.html');
                        }
                    }
                });
            });

            $("#txtResumeID").keydown(function(e) {
                if(e.keyCode == 13) {
                    $("#btnSearchResumeID").click();
                }
            });

            // 列表详情页面切换
            $("#btnDisplayType").on("click", "a", function(e) {
                if ($(this).attr("data-type") == 1 && !$(this).hasClass('prSchIcon1cut')) {
                    $(this).addClass('prSchIcon1cut').siblings().removeClass('prSchIcon2cut');
                    $(".prSchTabBg[data-type=1]").show().siblings('.prSchTabBg').hide();
                    $(".prSchListBm.detail").show().siblings('.simple').hide();
                }

                if ($(this).attr("data-type") == 2 && !$(this).hasClass('prSchIcon2cut')) {
                    $(this).addClass('prSchIcon2cut').siblings().removeClass('prSchIcon1cut');
                    $(".prSchTabBg[data-type=2]").show().siblings('.prSchTabBg').hide();
                    $(".prSchListBm.detail").hide().siblings('.simple').show();
                }
            });
            $('.newResume').click(function(e){
                isShowNewr = true

                var queryString = resumesearch.getQueryString();
                var timetype = $("input[name=timeSel]").val();
                var newr = $("input[name='newResume']").is(':checked')?"true":'false';
                var url = "/resumesearchnew/search?" + queryString
                    + "&p=1"
                    + "&rt=" + time
                    + "&newr=" + newr
                    + "&tt=" + timetype;
                resumesearch.searchResumeData(url);
            })
            
            $('#btnSearch').click(function() {
				resumesearch.defaultSort();//是否显示默认排序
                isShowNewr = true
                var queryCondition = resumesearch.glueKeyWords();
                var queryString = resumesearch.getQueryString();
								
                if (queryCondition != '') {
                    resumesearch.writeRecentSeearchCriteria(queryCondition, queryString);
                }
                var url = "/resumesearchnew/search?" + queryString + "&p=1";
                timeSel.setSelectedIndex(0);
                $("#btnSaveSeeker").show();
            });

            $("#pager").on("click", ".item", function() {
                var queryString = resumesearch.getQueryString();
                var timetype = $("input[name=timeSel]").val();

                var url = "/resumesearchnew/search?" + queryString 
                        + "&p=" + $(this).attr("data-id")
                        + "&rt=" + time
                        + "&tt=" + timetype;

                resumesearch.searchResumeData(url);
            });

            /* 点击切换 标签页 */
            $('#btnPageSize a,#firmSchTab li').on('click', function(e) {
                /* Act on the event */
                if ($(this).hasClass("cut"))
                    return false;

                $(this).addClass("cut").siblings().removeClass("cut")
                var queryString = resumesearch.getQueryString();
                var url = "/resumesearchnew/search?" + queryString + "&p=1";

                var sorttype = $("#firmSchTab").find("li.cut").attr("data-value");
				user_select_so = sorttype;
                if (sorttype == 1) {
                    timeSel = new select({
                        trigger: $('#timeSel'),
                        className: 'dropv2_select',
                        align: {baseXY: [0, '100%-1']},
                        selectName: 'timeSel',
                        dataSource: timeFreshItems,
                        selectedIndex: 0,
                        isHidDefault: false,
                        selectCallback: {
                            isDefault: true
                        }
                    });
                    $(".formRadtitile").text("刷新时间");
					$('.reTime').show();
                }else if(sorttype == 2) {
                    timeSel = new select({
                        trigger: $('#timeSel'),
                        className: 'dropv2_select',
                        align: {baseXY: [0, '100%-1']},
                        selectName: 'timeSel',
                        dataSource: timeLoginItems,
                        selectedIndex: 0,
                        isHidDefault: false,
                        selectCallback: {
                            isDefault: true
                        }
                    });
                    $(".formRadtitile").text("登陆时间");
					$('.reTime').show();
                }else{
					timeSel = new select({
					    trigger: $('#timeSel'),
					    className: 'dropv2_select',
					    align: {baseXY: [0, '100%-1']},
					    selectName: 'timeSel',
					    dataSource: timeFreshItems,
					    selectedIndex: 0,
					    isHidDefault: false,
					    selectCallback: {
					        isDefault: true
					    }
					});
					$(".formRadtitile").text("默认排序");
					$('.reTime').hide();
				}

                resumesearch.searchResumeData(url);

                timeSel.on('change', function(e) {
                    var queryString = resumesearch.getQueryString();
                    var url = "/resumesearchnew/search?" + queryString + "&p=1";

                    var timetype = $("input[name=timeSel]").val();
                    url += '&tt=' + timetype;

                    resumesearch.searchResumeData(url);
                });
            });

            /* 匹配人才逻辑 */
            <?php if ($_smarty_tpl->getVariable('job_id')->value){?>
            resumesearch.searchResumeData("/resumesearchnew/search?job_id=<?php echo $_smarty_tpl->getVariable('job_id')->value;?>
&ct=2&st=2&from=<?php echo $_smarty_tpl->getVariable('from')->value;?>
");
            <?php }elseif(!empty($_smarty_tpl->getVariable('station',null,true,false)->value)){?>
            var station = encodeURIComponent('<?php echo $_smarty_tpl->getVariable('station')->value;?>
');
            resumesearch.searchResumeData("/resumesearchnew/search?k="+ station +"&ct=2&st=2&searchfrom=5&newr=true");
            <?php }?>
        },
		defaultSort : function(){
			//默认排序是否显示
			var keyword = $("input[name=keyword]").val();
			var stationName = $("input[name=station]").val();
			// console.log('默认排序是否显示',keyword,stationName);
			if(keyword == '' && stationName == ''){
				$("#firmSchTab li").eq(2).css('display','none');
				if($("#firmSchTab li").eq(2).is(':hidden')){
					$("#firmSchTab li").removeClass('cut').eq(0).addClass('cut');
				}
			}else{
				$("#firmSchTab li").eq(2).css('display','block');
				$("#firmSchTab li").removeClass('cut').eq(2).addClass('cut');
				$('.reTime').hide();
			}
		},
				
				glueKeyWords : function() {
					var searchArr = []
																
					 var keyword = $('input[name=keyword]').val();       //关键词  
					 // var split = $('input[name=split]:checked').val()?'包含任意关键词':''; 	        //包含任一关键词
					 var station = $('input[name=station]').val();       //职位名称        
					 
					var txtJobsorts = $('#txtJobsort a');					      //期望职位类别		 
					var txtJobsortstr = []
					for(var i=0; i<txtJobsorts.length;i++){
						if($(txtJobsorts[i]).text()!=''){
							txtJobsortstr.push($(txtJobsorts[i]).text().substring(0,$(txtJobsorts[i]).text().length-1)) 
						}
					}
					txtJobsorts = txtJobsortstr.join(',')
					// console.log(txtJobsorts,'qqqq')
					
					
					var txtNativeAreas = $('#txtNativeArea .seled');		 //现居地
					var txtNativeAreastr = []
					for(var i=0; i<txtNativeAreas.length;i++){
						if($(txtNativeAreas[i]).text()!=''){
						 txtNativeAreastr.push($(txtNativeAreas[i]).text().substring(0,$(txtNativeAreas[i]).text().length-1))
						}
					}
					txtNativeAreas = txtNativeAreastr.join(',')
					
					
					var txtExpAreas = $('#txtExpArea .seled');		    //期望地区	
					var txtExpAreastr = []		
					for(var i=0; i<txtExpAreas.length;i++){
										 if($(txtExpAreas[i]).text()!=''){
											txtExpAreastr.push($(txtExpAreas[i]).text().substring(0,$(txtExpAreas[i]).text().length-1))
										 }
					}
					txtExpAreas = txtExpAreastr.join(',')
					
					var workYMinSel = $('#workYMinSel').text();			 //工作年限 小				 
					var workYMaxSel = $('#workYMaxSel').text();	     //工作年限 大
											 
					var degreeMinSel = $('#degreeMinSel').text();		 //学历  小			 
					var degreeMaxSel = $('#degreeMaxSel').text();		 //学历  大				 
											 
					var txtAgeLower = $('#txtAgeLower').val();			//年龄 小			 
					var txtAgeUpper = $('#txtAgeUpper').val();			//年龄 大				 
											 
					var sexRad =''						 
					if($('input[name=sexRad]:checked').val() == 1){sexRad = '男'};			//性别		
					if($('input[name=sexRad]:checked').val() == 2){sexRad = '女'};			//性别		
							 
					var txtAccession = $('#txtAccession').text();			 //到岗时间		 
					var eStation = $('input[name=eStation]').val();	   //期望职位
							 
					var txtCalling = $('#txtCalling .seled')	        //期望行业
					var txtCallingstr = []
					for(var i=0; i<txtCalling.length;i++){
						if($(txtCalling[i]).text()!=''){
							txtCallingstr.push($(txtCalling[i]).text().substring(0,$(txtCalling[i]).text().length-1)) 
						}
					}
					txtCalling = txtCallingstr.join(',')
								 
					var company = $('input[name=company]').val();     //公司名称
					var comType = $('input[name=comType]').val() ? '所有公司':'最近公司';	  //公司类型
						
					var majorDesc = $('input[name=majorDesc]').val();			//专业名称 
					
					var salaryMinSel = $('input[name=salaryMinSel]').val();    //期望月薪 小
					var salaryMaxSel = $('input[name=salaryMaxSel]').val();		 //期望月薪 大			 
										 
					var txtStatureLower = $('input[name=txtStatureLower]').val(); //身高 小
					var txtStatureUpper = $('input[name=txtStatureUpper]').val(); //身高 大				 
										 
					var photoType = $('input[name=photoType]:checked').val()!=0 ? "有头像/生活照":"";	
								 
					searchArr.push(keyword)
					// searchArr.push(split)
					searchArr.push(station)
					searchArr.push(txtJobsorts)
					searchArr.push(txtNativeAreas)
					searchArr.push(txtExpAreas)
					
					/* 工作年限 */
					if(workYMinSel != '不限' && workYMaxSel!='不限'){
						searchArr.push(workYMinSel+'~' +workYMaxSel)
					}
					
					if(workYMinSel != '不限' && workYMaxSel=='不限'){
						searchArr.push(workYMinSel + '以上')
					}
					
					if(workYMinSel == '不限' && workYMaxSel!='不限'){
						searchArr.push(workYMaxSel + '以下')
					}
					
					
					/* 学历 */
					if(degreeMinSel != '不限' && degreeMaxSel!='不限'){
						searchArr.push(degreeMinSel+'~' +degreeMaxSel)
					}
					
					if(degreeMinSel != '不限' && degreeMaxSel=='不限'){
						searchArr.push(degreeMinSel + '以上')
					}
					
					if(degreeMinSel == '不限' && degreeMaxSel!='不限'){
						searchArr.push(degreeMaxSel + '以下')
					}
					
					/* 年龄 */
					if(txtAgeLower  && txtAgeUpper){
						searchArr.push(txtAgeLower+'~' +txtAgeUpper)
						searchArr.push(txtAgeLower+'~' +txtAgeUpper)
					}
					
					if(txtAgeLower && !txtAgeUpper){
						searchArr.push(txtAgeLower + '岁以上')
					}
					
					if(!txtAgeLower && txtAgeUpper){
						searchArr.push(txtAgeUpper + '岁以下')
					}


					
					searchArr.push(sexRad )

					searchArr.push(txtAccession =="不限" ? '' : txtAccession)
					searchArr.push(eStation)
					searchArr.push(txtCalling)
					searchArr.push(company)
					// searchArr.push(comType)
					searchArr.push(majorDesc)
					
					
					
					//薪资
					if(salaryMinSel  && salaryMaxSel ){
						searchArr.push(salaryMinSel+'~' +salaryMaxSel)
					}
					
					if(salaryMinSel && !salaryMaxSel){
						searchArr.push(salaryMinSel + '以上')
					}
					
					if(!salaryMinSel && salaryMaxSel){
						searchArr.push(salaryMaxSel + '以下')
					}
					
					//身高
					if(txtStatureLower  && txtStatureUpper ){
						searchArr.push(txtStatureLower+'~' +txtStatureUpper)
					}
					
					if(txtStatureLower && !txtStatureUpper){
						searchArr.push(txtStatureLower + '以上')
					}
					
					if(!txtStatureLower && txtStatureUpper){
						searchArr.push(txtStatureUpper + '以下')
					}
					
					// console.log(keyword)
					// console.log(split)
					// console.log(station)
					// console.log(txtJobsorts)
					// console.log(txtNativeAreas)
					// console.log(txtExpAreas)
					// console.log(workYMinSel+',' +workYMaxSel)
					// console.log(degreeMinSel + ',' +degreeMaxSel)
					// console.log(txtAgeLower +','+ txtAgeUpper)
					// // console.log(sexRad)
					// console.log(txtAccession)
					// console.log(eStation)
					// console.log(txtCalling)
					// console.log(company)
					// console.log(comType)
					// console.log(majorDesc)
					// console.log(salaryMinSel +','+ salaryMaxSel)
					// console.log(txtStatureLower+','+txtStatureUpper)
					// console.log(photoType)
					
					//照片
					searchArr.push(photoType)

					var findStr = ''
					for(var i=0; i<searchArr.length; i++){
						if(searchArr[i]){
							findStr  +=  (findStr == '' ? searchArr[i] : '+' + searchArr[i])
						}
					}
					
					// console.log(findStr,123);
					
					findStr = (findStr.length >= 15 ? findStr.substring(0,15) + '...':findStr)
					
					
					return findStr
				},
				
				
        getQueryString : function(decode) {
            var newr = $('.newResume').is(':checked')
            var keyword     = $("input[name=keyword]").val();
            var split       = $(':input[name=split][checked]').val();
            var indisdint   = $(':input[name=fuzzy][checked]').val();
            var comtype     = $(':input[name=comType][checked]').val();
            var worktype    = $(':input[name=workType][checked]').val();
            var stationName = $('input[name=station]').val();
            
            var currArea    = $('input[name=nativeArea]').val();
            var expArea     = $('input[name=expArea]').val();
            var eStation    = $('input[name=eStation]').val();
            var majorDesc    = $('input[name=majorDesc]').val();
            var jobsort     = $('input[name=jobsort]').val();
            var accession   = $("input[name=txtAccession]").val();

            var sex         = $(':input[name=sexRad][checked]').val();
            var workYearMin = $('input[name=workYMinSel]').val();
            var workYearMax = $('input[name=workYMaxSel]').val();
            var ageLower    = $('input[name=txtAgeLower]').val();
            var ageUpper    = $('input[name=txtAgeUpper]').val();
            var degreeMin   = $('input[name=degreeMinSel]').val();
            var degreeMax   = $('input[name=degreeMaxSel]').val();
            
            var calling     = $('input[name=calling]').val();
            var companyName = $('input[name=company]').val();

            var salaryMin   = $('input[name=salaryMinSel]').val();
            var salaryMax   = $('input[name=salaryMaxSel]').val();
            var marrage     = $('input[name=marriageSel]').val();
            
            //新增身高和婚姻状况
            var stature_min = $("input[name='txtStatureLower']").val();
            var stature_max = $("input[name='txtStatureUpper']").val();
            var pagesize    = $("#btnPageSize").find("a.cut").attr("data-value");
			var sorttype = $("#firmSchTab").find("li.cut").attr("data-value");
            //是否有照片
            var phtot_type = $('input[name=photoType]:checked').val();
            
            var str = (comtype ? "&ct=" + comtype : '')
                    + (companyName ? "&cn=" + (decode === false ? companyName : encodeURIComponent(companyName)) : '')
                    + (worktype ? "&st=" + worktype : '')
                    + (stationName ? "&sn=" + (decode === false ? stationName : encodeURIComponent(stationName)) : '')
                    + (keyword ? "&k=" + (decode === false ? keyword : encodeURIComponent(keyword)) : '')
                    + ($.trim(currArea) ? "&a=" + currArea : '')
                    + ($.trim(expArea) ? "&ea=" + expArea : '')
                    + (eStation ? "&es=" + (decode === false ? eStation : encodeURIComponent(eStation)) : '')
                    + (majorDesc ? "&md=" + (decode === false ? majorDesc : encodeURIComponent(majorDesc)) : '')
                    + ($.trim(calling) ? "&c=" + calling : '')
                    + ($.trim(jobsort) ? "&j=" + jobsort : '')
                    + (accession && accession != '0' ? "&ac=" + accession : '')
                    + (workYearMin && workYearMin != '0' ? "&ymin=" + workYearMin : '')
                    + (workYearMax && workYearMax != '0' ? "&ymax=" + workYearMax : '')
                    + (ageLower && ageLower != '0' ? "&amin=" + ageLower : '')
                    + (ageUpper && ageUpper != '0' ? "&amax=" + ageUpper : '')
                    + (degreeMin && degreeMin != '0' ? "&dmin=" + degreeMin : '')
                    + (degreeMax && degreeMax != '0' ? "&dmax=" + degreeMax : '')
                    + (salaryMin && salaryMin != '0' ? "&smin=" + salaryMin : '')
                    + (salaryMax && salaryMax != '0' ? "&smax=" + salaryMax : '')
                    + (split && split != '0' ? "&sp=" + split : '')
                    + (indisdint && indisdint != '0' ? "&idst="+indisdint : '')
                    + (sex ? "&s=" + sex : '')
                    + (marrage ? "&ma=" + marrage : '')
                    + (stature_min && stature_min !="" && stature_min !="0" ? "&sml=" + stature_min : '')
                    + (stature_max && stature_max !="" && stature_max !="0" ? "&smb=" + stature_max : '')
                    + "&so=" + sorttype
                    + "&ps=" + pagesize 
                    + "&pht=" + phtot_type
            
            if(isShowNewr){
                str += "&newr=" + (decode === false ? newr : encodeURIComponent(newr))
            }
            return str;
        },
        getQueryCondition : function() {
            var keyword = $("input[name=keyword]").val();
            return keyword;
        },
        writeRecentSeearchCriteria : function(queryCondition, queryString) {
            var cookieValue      = cookie.get('resumeQueryCondition');
            var queryCookie      = cookie.get('resumeQueryString');
            var conditionArray = new Array();
            var queryStringArray = new Array();
            if (cookieValue != undefined && cookieValue.length > 0) {
                conditionArray = unescape(cookieValue).split(';');
                queryStringArray = unescape(queryCookie).split(';');
            }

            if (conditionArray != undefined && conditionArray != null) {
                var same = false;
                for (var i = 0; i < queryStringArray.length; i++) {
                    if (queryString == queryStringArray[i]) {
                        conditionArray.splice(i, 1);
                        queryStringArray.splice(i, 1);
                        same = true;
                    }
                }
                if (same == false && conditionArray.length >= 3) {
                    conditionArray.splice(0, 1);
                    queryStringArray.splice(0, 1);
                }
                conditionArray.push(queryCondition);
                queryStringArray.push(queryString);
            }
            
            cookie.set('resumeQueryCondition', escape(conditionArray.join(';')), {isEncode:false});
            cookie.set('resumeQueryString', escape(queryStringArray.join(';')), {isEncode:false});

            if (conditionArray != null) {
                var len  = conditionArray.length;
                var span = $('#spanSearchCondition');
                span.html('');
                if (len > 0) {
                    span.html('<span>最近搜索：</span>');
                }
                for (var i = len - 1; i >= 0; i--) {
                    span.append("<a href='javascript:void(0)' data-url='" + "<?php echo smarty_function_get_url(array('rule'=>'/resumesearchnew/search/'),$_smarty_tpl);?>
?p=1"
                        + queryStringArray[i] + "'>" + conditionArray[i] + '</a>');
                }
            }
        },
        readRecentSearchCriteria : function() {
            var cookieValue      = cookie.get('resumeQueryCondition');
            var queryCookie      = cookie.get('resumeQueryString');
            var conditionArray   = new Array();
            var queryStringArray = new Array();

            if (cookieValue != undefined && cookieValue.length > 0) {
                conditionArray   = unescape(cookieValue).split(';');
                queryStringArray = unescape(queryCookie).split(';');
            }

            if (conditionArray != null) {
                var len  = conditionArray.length;
                var span = $('#spanSearchCondition');
                span.html('');
                if (len > 0) {
                    span.html('<span>最近搜索：</span>');
                }
                for (var i = len - 1; i >= 0; i--) {
                    span.append("<a href='javascript:void(0)' data-url='" + "<?php echo smarty_function_get_url(array('rule'=>'/resumesearchnew/search/'),$_smarty_tpl);?>
?p=1" 
                        + queryStringArray[i] + "'>" + conditionArray[i] + '</a>');
                }
            }
        },
        searchResumeData : function(url) {
            $("#loading").show();
            $("#resumePart").hide();
            $("#resumeDetailList").empty();
            $("#resumeSimpleList").empty();

            $.ajax({
                url : url,
                type : "GET",
                dataType : "JSON",
                contentType:"application/json; charset=utf-8",
                success : function(obj) {
                    if (obj && obj.isNeedLogin) {
                        window.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/login/'),$_smarty_tpl);?>
";
                    }
                    time = obj.time;
                    $("#loading").hide();
                    resumesearch.buildResumeData(obj);
                    resumesearch.buildPager(obj);
                    resumesearch.bindParams(obj);
                }
            })
        },
        buildResumeData: function (obj) {
            var h = obj.params.highlight ? obj.params.highlight : false;
            if (obj.total > 0) {
                var detail = "",
                    simple = ""

                var works_all = [], works_rec = [], com_all = [], com_rec = [], exp_works = [], all = [], station = [], detail_highligt = [];
                    
                works_rec = works_rec.concat(h.keyword || []).concat(h.last_work_station || []).concat(h.station_name || []);
                com_rec   = com_rec.concat(h.keyword || []).concat(h.last_company_name || []).concat(h.company_name || []);
                station   = station.concat(h.keyword || []).concat(h.exp_station || []);
                all       = all.concat(h.keyword || []);

                var _sortType = $("#firmSchTab").find("li.cut").attr("data-value");

                detail_highligt = detail_highligt.concat(h.keyword || []).concat(h.station_name || []);
                $.unique(detail_highligt);
                detail_highligt = detail_highligt.join(',');
                for (var i = 0; i < obj.resumes.length; i++) 
                {
                    var resume = obj.resumes[i];
                    var need_download = resume.has_download ? "0" : "1";
                    var not_area_limit = resume.not_area_limit ? "1" : "0";
					var _chat_html  = resume.is_active ? '<em title="该求职者聊天活跃" class="chatHuoyue">活跃</em>' : "";
                    var workdetail = "<p><span>工作年限：</span><b>" + resume.start_work + "</b>"
					+'<a href="javascript:;" class="chatOneChat '+ ((!resume.chat_status) ?  "notOffenUse" : "") +'" data-notice-status="'+ resume.chat_status +'" data-from = "search" data-resume-id="'+resume.resume_id+'" data-need-download="'+need_download+'" data-arealimit-id="'+not_area_limit+'">立即沟通</a>'
					+ _chat_html
					+"</p>";
                    
                    if (resume.worklist) 
                    {
                        for (var j = 0; j < resume.worklist.length; j++) 
                        {
                            var work = resume.worklist[j];
                                work.station = resumesearch.highlight(work.station, works_rec);
                                work.company_name = resumesearch.highlight(work.company_name, com_rec);

                            workdetail += "<p><span class='station'>" + work.station + "</span>"
                                        + "<span class='divided'>|</span><em>" + work.company_name + "</em><span class='divided'>|</span><em>"
                                        + work.start_time + "-" + work.end_time + "(" + work.last + ")</em></p>"
                        }
                    }
                     var resume_href = '<?php echo smarty_function_get_url(array('rule'=>"/resume/resumeshow/",'data'=>"type=network&src=search"),$_smarty_tpl);?>
' + "-resumeid-"  + resume.resume_id + "-dh-" + detail_highligt + "'";
                    detail += "<div class='prSchTab' data-url='" + resume_href + "'>"
                                + "<div class='prSchTx clearfix'>"
                                    + "<a href='" + resume_href + "' target='_blank' class='pslBm1'>" + resume.user_name + "</a>"
                                    + "<a href='" + resume_href + "' target='_blank'>" 
																				+ "<span class='pslBm2'>" + resume.sex_text + "</span>"
																		+ "</a>" 
																		+ "<a href='" + resume_href + "' target='_blank'>" 
																			+ "<span class='pslBm2'>" + resume.age + "</span>"
																		+ "</a>"
																		+ "<a href='" + resume_href + "' target='_blank'>" 
																			+ "<span class='pslBm4'>" + resume.cur_area_full + "</span>"
																		+ "</a>"
																		+ "<a href='" + resume_href + "' target='_blank'>" 
																			+ "<span class='pslBm8' title='" + resume.degree_text + "'>" + resume.degree_text + "</span>"
																		+ "</a>"
																		+ "<a href='" + resume_href + "' target='_blank'>" 
																			+ "<span class='pslBm3' title='" + resume.school + "'>" + resumesearch.highlight(resume.school, all) + "</span>"
																		+ "</a>"
																		+ "<a href='" + resume_href + "' target='_blank'>" 
																			+ "<span class='pslBm6' title='" + resume.major_desc + "'>" +resumesearch.highlight(resume.major_desc, all) + "</span>"
																		+ "</a>"
																		+ "<a href='" + resume_href + "' target='_blank'>"
																			+ (_sortType == 1 || _sortType == 3 ? "<span class='pslBm5'>" + (_sortType==3 ? resume.refresh_txt:resume.refresh_time) + "</span>" : "<span class='pslBm5'>" + resume.login_time + "</span>")
																		+ "</a>"
                                + "</div>"
                                + "<div class='prSchTabx'>"
                                    + "<dl>"
                                        + "<dt><a target='_blank' href='" + resume_href + "'><img src='" + (resume.photo ? resume.photo : "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/headportrait.png")
                                            + "' width='88' height='88'></a></dt>"
                                        + "<dd>"  + workdetail + "</dd>"
                                    + "</dl>"
                                    +'<div class="sendTo_img"><span>把简历转发给职位负责人</span><img src="" data-img-source="<?php echo smarty_function_get_url(array('rule'=>'/apply/SendToWorkMatePng/'),$_smarty_tpl);?>
?src=recommend&resume_id='+resume.resume_id+'"/><b>用汇博企业APP<br />扫码转发简历</b></div>'
                                    + "<div class='pBoxT'>"
                                        + (resume.station ? "<p class='prTabx1'>求职意向：<span>" + resumesearch.highlight(resume.station, all) + "</span>" + "</p>" : "")
                                        + (resume.job_state ? "<p class='prTabx1'>求职状态：<b>" + resume.job_state + "</b>" + (resume.accession ? "<b>，" + resume.accession + "</b>" : "") + "</p>" : "")
                                        + (resume.one_marks ? "<p class='prTabx1'>"+resume.one_marks_key+"："+resume.one_marks+"</p>" : "")
                                    + "</div>"
                                    + "<div class='clear'></div>"
                                + "</div>";
                    
                    if (resume.remark_msg) {
                        detail = detail + "<div class='has_remark'><em>注</em><img src ='<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/has_remark.png'/><b>"+resume.remark_msg+"</b></div>";
                    } else if (resume.has_download) {
                        detail = detail + "<em class='has_download' title='已下载'>载</em>";
                    } else if (resume.has_read) {
                        detail = detail + "<em class='has_read' title='已查看'>阅</em>";
                    }else if(resume.is_new_resume){
                        detail += "<img src='<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/newResume.png' class='resume-icon'>"     
                    }
                    detail = detail + "</div>";

                    simple += "<div class='prSchTab prSchTabD" + (i + 1 == obj.resumes.length ? " Last" : "") + "'>"
                                + "<div class='prSchTx prSchTxD clearfix'>"
                                    + "<a href='" + resume_href + "' target='_blank' class='pslBm1'>"
                                            + "<img src='" + (resume.small_photo ? resume.small_photo : "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/headportrait.png") + "' width='44' height='44'>"
                                            + "<span>" + resume.user_name + "</span>" 
                                        + "</a>"
                                    + "<span class='pslBm2'>" + resume.sex_text + "</span>"
                                    + "<span class='pslBm2'>" + resume.age + "</span>"
                                    + "<span class='pslBm2' title='" + resume.degree_text + "'>" + resume.degree_text + "</span>"
                                    + "<span class='pslBm4'>" + resume.cur_area_text + "</span>"
                                    + "<span class='pslBm6'>" + resume.start_work + "</span>"
                                    + "<span class='pslBm7' title='" + resume.work + "'>" + resumesearch.highlight(resume.work, works_rec) + "</span>"
                                    + "<span class='pslBm3' title='" + resume.company_name + "'>" + resumesearch.highlight(resume.company_name, com_rec) + "</span>"
                                    + (_sortType == 1 || _sortType == 3 ? "<span class='pslBm5'>" + (_sortType==3 ? resume.refresh_txt:resume.refresh_time) + "</span>" : "<span class='pslBm5'>" + resume.login_time + "</span>")
                                + "</div>";

                    if (resume.remark_msg) {
                        simple = simple + "<div class='has_remark2'><em>注</em><img src='<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/new_resume/has_remark.png' /><b>"+resume.remark_msg+"</b></div>";
                    } else if (resume.has_download) {
                        simple = simple + "<em class='has_download2' title='已下载'>载</em>";
                    } else if (resume.has_read) {
                        simple = simple + "<em class='has_read2' title='已查看'>阅</em>";
                    }

                    simple = simple  + "</div>";
                }

                $("#resumeDetailList").append(detail);
                $("#resumeSimpleList").append(simple);
                $(".resumecount").text(obj.total > 9999 ? "9999+结果" : obj.total + "结果");

                $("#resumePart").show();
                $("#noData").hide();

                $('html,body').animate({scrollTop: 567});
            } else {
                $("#resumePart").show();
                $("#noData").show();
				$(".resumecount").text('');
            }
        },
        buildPager : function(obj) {
            $("#pager").empty();
            if (obj.total > 0) {
                var page = obj.pager;
                var $pager = $("<div class='page'></div>");
                if (page.preg > 0)
                    $pager.append("<a href='javascript:void(0)' class='item' data-id='" + page.preg + "'>&lt;</a>");

                if (page.pages) {
                    var _curpage = page.currpage;
                    var start = page.page_show_start;
                    var end = page.page_show_end;
                    var total = page.total_page;

                    for (var i = 0; i < page.pages.length; i++) {
                        var _page = page.pages[i];
                        if (_page == 1) {
                            $pager.append("<a href='javascript:void(0)' class='item " + (_curpage == 1 ? "cu" : "") + "' data-id='" + _page + "'>" + _page + "</a>");
                            if (_curpage > 6)
                                $pager.append("<a href='javascript:void(0)' class='morePage'>...</a>");
                        }

                        if (_page >= start && _page <= end)
                            $pager.append("<a href='javascript:void(0)' class='item " + (_curpage == _page ? "cu" : "") + "' data-id='" + _page + "'>" + _page + "</a>");

                        if (_page == total) {
                            if (_curpage + 4 < total)
                                $pager.append("<a href='javascript:void(0)' class='morePage'>...</a>");

                            if (total > 1)
                                $pager.append("<a href='javascript:void(0)' class='item " + (_curpage == _page ? "cu" : "") 
                                    + "' data-id='" + _page + "'>" + _page + "</a>");
                        }
                    }
                }

                if (page.next > 0)
                    $pager.append("<a href='javascript:void(0)' class='item' data-id='" + page.next + "'>&gt;</a>");
            }
            $("#pager").append($pager);
        },
        highlight : function (text, obj) {
            
            if(text == '' || text == null){
                return text;
            }
            for (var i = 0; i < obj.length; i++) {
            	if(obj[i]){
	                var reg = new RegExp(obj[i].replace("+", "\\+"), "gi");
	                text = text.replace(reg, "<strong class='prTabx4'>" + obj[i] + "</strong>");
	            }
            }

            return text;
        },
        bindParams : function (result) {
            /* 设置控件条件 */
            result.params.ymin ? workYMinSelect.setSelectedValue(result.params.ymin) : workYMinSelect.setSelectedIndex(0);
            result.params.ymax ? workYMaxSelect.setSelectedValue(result.params.ymax) : workYMaxSelect.setSelectedIndex(0);
            result.params.dmin ? degreeMinSelect.setSelectedValue(result.params.dmin) : degreeMinSelect.setSelectedIndex(0);
            result.params.dmax ? degreeMaxSelect.setSelectedValue(result.params.dmax) : degreeMaxSelect.setSelectedIndex(0);
            result.params.ma ? marriageSel.setSelectedValue(result.params.ma) : marriageSel.setSelectedIndex(0);
            result.params.ac ? accessionSel.setSelectedValue(result.params.ac) : accessionSel.setSelectedIndex(0);
            
            /* 所有输入框 */
            result.params.sn ? $("input[name=station]").val(decodeURIComponent(result.params.sn)) : $("input[name=station]").val("");
            result.params.cn ? $("input[name=company]").val(decodeURIComponent(result.params.cn)) : $("input[name=company]").val("");
            result.params.k ? $("input[name=keyword]").val(decodeURIComponent(result.params.k)) : $("input[name=keyword]").val("");
            result.params.es ? $("input[name=eStation]").val(decodeURIComponent(result.params.es)) : $("input[name=eStation]").val("");
            result.params.md ? $("input[name=majorDesc]").val(decodeURIComponent(result.params.md)) : $("input[name=majorDesc]").val("");
            result.params.amin ? $("input[name=txtAgeLower]").val(result.params.amin) : $("input[name=txtAgeLower]").val("");
            result.params.amax ? $("input[name=txtAgeUpper]").val(result.params.amax) : $("input[name=txtAgeUpper]").val("");

            result.params.sml ? $("input[name=txtStatureLower]").val(result.params.sml) : $("input[name=txtStatureLower]").val("");
            result.params.smb ? $("input[name=txtStatureUpper]").val(result.params.smb) : $("input[name=txtStatureUpper]").val("");

            result.params.smin ? $("input[name=salaryMinSel]").val(result.params.smin) : $("input[name=salaryMinSel]").val("");
            result.params.smax ? $("input[name=salaryMaxSel]").val(result.params.smax) : $("input[name=salaryMaxSel]").val("");

            /* 单选框 */
            result.params.sp
                ? $("input[name=split]").attr("checked", "checked")
                : $("input[name=split]").removeAttr("checked");
            result.params.s 
                ? $("#radSex" + (1 + parseInt(result.params.s))).attr("checked", "checked").siblings("input[type=radio]").removeAttr('checked')
                : $("#radSex1").attr("checked", "checked").siblings("input[type=radio]").removeAttr('checked');
            result.params.st
                ? $("#radWorkType" + (1 + parseInt(result.params.st))).attr("checked", "checked").siblings("input[type=radio]").removeAttr('checked')
                : $("#radWorkType1").attr("checked", "checked").siblings("input[type=radio]").removeAttr('checked');
            result.params.ct
                ? $("#radComType" + (1 + parseInt(result.params.ct))).attr("checked", "checked").siblings("input[type=radio]").removeAttr('checked')
                : $("#radComType1").attr("checked", "checked").siblings("input[type=radio]").removeAttr('checked');
			// result.params.pht ? console.log(result.params.pht) : console.log(1)
			if(result.params.pht==0||result.params.pht==1){
				$('input[name=photoType][value='+result.params.pht+']').click()
			}
			

			areaSelect.resetMultipleareaValue()
			expAreaSelect.resetMultipleareaValue()
			callingSelect.resetCallingValue();
			jobSorterBox.clearAllItem();
            result.params.a ? areaSelect.setMultipleAreaValue(result.params.a) : areaSelect.resetMultipleareaValue();
            result.params.ea ? expAreaSelect.setMultipleAreaValue(result.params.ea) : expAreaSelect.resetMultipleareaValue();
            result.params.c ? callingSelect.setCallingValue(result.params.c) : callingSelect.resetCallingValue();
			result.params.j&&result.params.jname ? jobSorterBox.addSelect(result.params.j.split(','),result.params.jname.split(',')) : jobSorterBox.clearAllItem()
            
			if(!typeof(jobsortSelect)){result.params.j ? jobsortSelect.setJobSortValue(result.params.j) : jobsortSelect.resetJobsortValue();}           	

            dialog.hide();
            $('input.prSchTxt2').resetPlaceHolder && $('input.prSchTxt2').resetPlaceHolder();
        }
    }

    resumesearch.init();
    resumesearch.readRecentSearchCriteria();





});


/* 语言切换时修改熟练度和证书 */
$('.yuyan').on('change',function(){
	
	if(!$(this).val()){
		$('.shuliandu').html("<option>不限</option>");
		$('.zhengshu').html("<option>不限</option>");
		$('.shuliandu').trigger('change')
		$('.zhengshu').trigger('change')
	}else{
		$('.shuliandu').html(
			"<option>不限</option>"+
			"<option value='2'>入门</option>"+
			"<option value='2'>熟练</option>"+
			"<option value='2'>精通</option>"
		);
		$('.zhengshu').html(
			"<option>不限</option>"+
			"<option value='2'>一级</option>"+
			"<option value='2'>二级</option>"+
			"<option value='2'>三级</option>"
		);
	}
});
</script>

<script  type="text/javascript">
    var action_url = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
';
    action_dom = [
        ['.chatOneChat', 400],
        ['.md_chat', 400],
    ];
</script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'action.js'),$_smarty_tpl);?>
"></script>

<?php $_template = new Smarty_Internal_Template("chat/chat.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</body>
</html>
<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:30:40
         compiled from "app\templates\resume/apply/nav.html" */ ?>
<?php /*%%SmartyHeaderCode:7485e7036502c6013-19756572%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd8d5854163473742383f93f83142d2d43e355964' => 
    array (
      0 => 'app\\templates\\resume/apply/nav.html',
      1 => 1584332294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7485e7036502c6013-19756572',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
?><?php include_once ('app/controller/applynav.php');?>

<?php if ($_smarty_tpl->getVariable('_is_gray_test_company')->value){?>
<div class="gray_test">
<link rel="stylesheet" type="text/css" href="//assets.huibo.com/css/m_font_style.css?v=20191220" />
<div class="rMentLt">
	<a href="<?php echo smarty_function_get_url(array('rule'=>'/apply/'),$_smarty_tpl);?>
" class="mentNav <?php if ($_smarty_tpl->getVariable('cur')->value=='待处理简历'){?>cut<?php }?> md_wait_replay"><i class="icon-resume_reply"></i>待回复简历</a>
	<a href="<?php echo smarty_function_get_url(array('rule'=>'/download/'),$_smarty_tpl);?>
" class="mentNav <?php if ($_smarty_tpl->getVariable('cur')->value=='下载的简历'){?>cut<?php }?> md_get_resume"><i class="icon-navigation_download"></i>获取的简历</a>
	<a href="javascript:void(0);" class="mentNavShowBtn cut"><i class="icon-resume_interview-resume"></i>面试简历管理<em class="icon-svg122"></em></a>
	<div class="mentNavShow" style="display: block;">
		<a href="<?php echo smarty_function_get_url(array('rule'=>'/apply/index','data'=>'status=1'),$_smarty_tpl);?>
" class="mentNavList md_had_invit <?php if ($_smarty_tpl->getVariable('cur')->value=='已邀请简历'){?>cut<?php }?>"><i></i>已邀请简历</a>
		<a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index','data'=>'audition_result=1'),$_smarty_tpl);?>
" class="mentNavList <?php if ($_smarty_tpl->getVariable('cur')->value=='面试通过'){?>cut<?php }?>"><i></i>面试通过</a>
		<a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index','data'=>'audition_result=2'),$_smarty_tpl);?>
" class="mentNavList <?php if ($_smarty_tpl->getVariable('cur')->value=='面试未通过'){?>cut<?php }?>"><i></i>面试未通过</a>
		<a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index','data'=>'audition_result=8'),$_smarty_tpl);?>
" class="mentNavList <?php if ($_smarty_tpl->getVariable('cur')->value=='面试爽约'){?>cut<?php }?>"><i></i>面试爽约</a>
		<a href="<?php echo smarty_function_get_url(array('rule'=>'/invitev1/index','data'=>'audition_result=9'),$_smarty_tpl);?>
" class="mentNavList <?php if ($_smarty_tpl->getVariable('cur')->value=='已入职'){?>cut<?php }?>"><i></i>已入职</a>
		<a href="<?php echo smarty_function_get_url(array('rule'=>'/offermanager/index','data'=>'left_type=1'),$_smarty_tpl);?>
" class="mentNavList <?php if ($_smarty_tpl->getVariable('cur')->value=='offer管理'){?>cut<?php }?>"><i></i>offer管理</a>
	</div>
	<a href="<?php echo smarty_function_get_url(array('rule'=>'/readjob'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('cur')->value=='谁看过我的职位'){?>cut<?php }?> mentNav md_who_seeme" style="margin-top: 15px;">
        <i class="icon-navigation_view "></i>谁看过我的职位<?php if ($_smarty_tpl->getVariable('who_see_me_has_new')->value){?><b></b><?php }?>
    </a>
    <?php if ($_smarty_tpl->getVariable('isShowArtificialRecommendTip')->value){?>
    <a href="<?php echo smarty_function_get_url(array('rule'=>"/recommend/index/",'data'=>"type=2&status=99"),$_smarty_tpl);?>
" class="mentNav md_remmon_resume hideRecommendCtip  <?php if ($_smarty_tpl->getVariable('cur')->value=='推荐的简历'){?>cut<?php }?>"><i class="icon-navigation_recommend"></i>推荐的简历<?php if ($_smarty_tpl->getVariable('recommend_red_point')->value){?><b></b><?php }?></a>
    <?php }else{ ?>
    <a href="<?php echo smarty_function_get_url(array('rule'=>"/recommend/index/",'data'=>"type=1"),$_smarty_tpl);?>
" class="mentNav <?php if ($_smarty_tpl->getVariable('cur')->value=='推荐的简历'){?>cut<?php }?> md_remmon_resume hideRecommendCtip"><i class="icon-navigation_recommend"></i>推荐的简历<?php if ($_smarty_tpl->getVariable('recommend_red_point')->value){?><b></b><?php }?></a>
    <?php }?>

	<a href="<?php echo smarty_function_get_url(array('rule'=>'/fav'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('par')->value=='收藏的简历'){?>cut<?php }?> mentNav"><i class="icon-navigation_favorite md_fav_resume"></i>收藏的简历</a>
	<a href="javascript:void(0);" class="mentNavShowBtn"><i class="icon-navigation_recycle-bin"></i>简历垃圾箱<em class="<?php if ($_smarty_tpl->getVariable('par')->value=='回收站'||in_array($_smarty_tpl->getVariable('cur')->value,array('不合适简历','自动过滤简历'))){?> icon-svg132<?php }else{ ?>icon-svg122<?php }?>"></em></a>
	<div class="mentNavShow" style="<?php if ($_smarty_tpl->getVariable('par')->value=='回收站'||in_array($_smarty_tpl->getVariable('cur')->value,array('不合适简历','自动过滤简历'))){?>display: block<?php }?>">
		<a href="<?php echo smarty_function_get_url(array('rule'=>'/apply/index','data'=>'status=3'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('par')->value=='不合适简历'){?>cut<?php }?> mentNavList md_del_site"><i></i>不合适简历</a>
		<a href="<?php echo smarty_function_get_url(array('rule'=>'/apply/index','data'=>'status=9'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('par')->value=='自动过滤简历'){?>cut<?php }?> mentNavList md_del_site"><i></i>自动过滤简历</a>
		<a href="<?php echo smarty_function_get_url(array('rule'=>'/recycle'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('par')->value=='回收站'){?>cut<?php }?> mentNavList md_del_site"><i></i>回收站</a>
	</div>
   
</div>
    <!-- 推荐的简历气泡 -->
    <?php if ($_smarty_tpl->getVariable('isShowArtificialRecommendTip')->value){?>
    <div class="recommendCtip" data-url="<?php echo smarty_function_get_url(array('rule'=>"/recommend/index/",'data'=>"type=2&status=99"),$_smarty_tpl);?>
">
        <a href="javascript:void(0);">运营人员为您精心挑选<br />了符合您职位的简历</a>
        <em></em>
    </div>
    <?php }?>
</div>
<?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'resument2015.css'),$_smarty_tpl);?>
" />
<div class="rMentLt">
    <dl class="rMentDl">
        <dt><em></em>收到的简历</dt>
        <dd class="<?php if ($_smarty_tpl->getVariable('cur')->value=="待处理简历"){?>cut<?php }?>">
        <a class="md_wait_replay" href="<?php echo smarty_function_get_url(array('rule'=>"/apply"),$_smarty_tpl);?>
" ><em></em><span>待回复简历</span>
        <?php if ($_smarty_tpl->getVariable('apply_status_count')->value[0]['not_do']>0){?>
        <i><?php if ($_smarty_tpl->getVariable('apply_status_count')->value[0]['not_do']>99){?>99+<?php }else{ ?><?php echo $_smarty_tpl->getVariable('apply_status_count')->value[0]['not_do'];?>
<?php }?></i>
        <?php }?>
        </a>
        </dd>
        <dd class="<?php if ($_smarty_tpl->getVariable('cur')->value=="已邀请简历"){?>cut<?php }?>">
        <a class="md_had_invit" href="<?php echo smarty_function_get_url(array('rule'=>'/apply/index','data'=>'status=1'),$_smarty_tpl);?>
"><em></em><span>已邀请简历</span></a>
        </dd>
        <dd class="<?php if ($_smarty_tpl->getVariable('cur')->value=="不合适的简历"){?>cut<?php }?>">
        <a class="md_not_fit" href="<?php echo smarty_function_get_url(array('rule'=>'/apply/index','data'=>'status=3'),$_smarty_tpl);?>
"><em></em><span>不合适的简历</span></a>
        </dd>
        <dd class="<?php if ($_smarty_tpl->getVariable('cur')->value=="自动过滤简历"){?>cut<?php }?>">
        <a class="md_auto_faiter" href="<?php echo smarty_function_get_url(array('rule'=>'/apply/index','data'=>'status=9'),$_smarty_tpl);?>
"><em></em><span>自动过滤简历</span>
            <?php if ($_smarty_tpl->getVariable('apply_status_count')->value[0]['automatic']>0){?>
            <i><?php if ($_smarty_tpl->getVariable('apply_status_count')->value[0]['automatic']>99){?>99+<?php }else{ ?><?php echo $_smarty_tpl->getVariable('apply_status_count')->value[0]['automatic'];?>
<?php }?></i>
            <?php }?>
        </a>
        </dd>
    </dl>
    <dl class="rMentDl">
        <dt><em></em>面试简历管理</dt>
        <dd class="<?php if ($_smarty_tpl->getVariable('cur')->value=="面试管理"){?>cut<?php }?>">
        <a href="<?php echo smarty_function_get_url(array('rule'=>"/invitev1"),$_smarty_tpl);?>
?left_type=2" ><em></em><span>面试管理</span>
        </a>
        </dd>
        <dd class="<?php if ($_smarty_tpl->getVariable('cur')->value=="offer管理"){?>cut<?php }?>">
        <a href="<?php echo smarty_function_get_url(array('rule'=>"/offermanager"),$_smarty_tpl);?>
?left_type=2"><em></em><span>offer管理</span></a>
        </dd>
    </dl>
    <ul class="rMentUl">
        <li>
            <a href="<?php echo smarty_function_get_url(array('rule'=>'/download'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('par')->value=='下载的简历'){?>cut<?php }?>  md_get_resume">
                <em class="mentIcon01"></em>获取的简历
            </a>
        </li>
        <li>
            <a href="<?php echo smarty_function_get_url(array('rule'=>'/readjob'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('cur')->value=='谁看过我的职位'){?>cut<?php }?> md_who_seeme">
                <em class="mentIcon05"></em>谁看过我的职位<?php if ($_smarty_tpl->getVariable('who_see_me_has_new')->value){?><i style="left: 170px;"></i><?php }?>
            </a>
        </li>
        <li>
            <a href="<?php echo smarty_function_get_url(array('rule'=>'/recommend'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('par')->value=='推荐的简历'){?>cut<?php }?>  md_remmon_resume">
                <em class="mentIcon02"></em>推荐的简历<?php if ($_smarty_tpl->getVariable('recommend_red_point')->value){?><i></i><?php }?>

            </a>
        </li>
        <li>
            <a href="<?php echo smarty_function_get_url(array('rule'=>'/fav'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('par')->value=='收藏的简历'){?>cut<?php }?>  md_fav_resume">
                <em class="mentIcon03"></em>收藏的简历
            </a>
        </li>
        <li>
            <a href="<?php echo smarty_function_get_url(array('rule'=>'/recycle'),$_smarty_tpl);?>
" class="<?php if ($_smarty_tpl->getVariable('par')->value=='回收站'){?>cut<?php }?>  md_del_site">
                <em class="mentIcon04"></em>回收站
            </a>
        </li>
    </ul>
</div>
<?php }?>

<script>
	
	
    var action_url = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
';
	
	$('.mentNavShowBtn').click(function () {
	    $(this).toggleClass('cut');
		if($(this).hasClass('cut')){
			$(this).find('em').attr('class','icon-svg132');
			$(this).next('div.mentNavShow').css('display','block');
			if($(this).index('.mentNavShowBtn')==0 && $('.recommendCtip')[0]){
				
				$('.recommendCtip').css('top',$('.recommendCtip')[0].offsetTop + $(this).next('div.mentNavShow').innerHeight())
			}
		}else{
			$(this).find('em').attr('class','icon-svg122');
			$(this).next('div.mentNavShow').css('display','none');
			if($(this).index('.mentNavShowBtn')==0 && $('.recommendCtip')[0]){
			$('.recommendCtip').css('top',$('.recommendCtip')[0].offsetTop - $(this).next('div.mentNavShow').innerHeight())
			}
		}
	});


    $('.md_wait_replay').click(function () {
        do_save_click(184,$(this).attr('log_data'));
    })

    $('.md_had_invit').click(function () {
        do_save_click(185,$(this).attr('log_data'));
    })
    $('.md_not_fit').click(function () {
        do_save_click(186,$(this).attr('log_data'));
    })
    $('.md_auto_faiter').click(function () {
        do_save_click(187,$(this).attr('log_data'));
    })
    $('.md_get_resume').click(function () {
        do_save_click(188,$(this).attr('log_data'));
    })
    $('.md_who_seeme').click(function () {
        do_save_click(189,$(this).attr('log_data'));
    })
    $('.md_remmon_resume').click(function () {
        do_save_click(190,$(this).attr('log_data'));
    })
    $('.md_fav_resume').click(function () {
        do_save_click(191,$(this).attr('log_data'));
    })
    $('.md_del_site').click(function () {
        do_save_click(192,$(this).attr('log_data'));
    })

    /**
     184=> '待回复简历',
     185=> '已邀请简历',
     186=> '不合适简历',
     187=> '自动过滤简历',
     188=> '获取的简历',
     189=> '谁看过我的职位',
     190=> '推荐的简历',
     191=> '收藏的简历',
     192=> '回收站',
     */

    function do_save_click(log_type,log_data) {
        var img = new Image();

        var _static_visit_sys = '';
        if ("ontouchstart" in window) {
            _static_visit_sys = isWeiXin() ? 'weixin':'mobile';//移动端 -  //区分 触屏端  微信

        } else {
            _static_visit_sys = 'pc';
        }
        var area_id = _static_visit_sys=='pc' ? getCookie('ip_area_info'):getCookie('M_area_info');
        area_id = area_id ? area_id : '0300';
        img.src = action_url + "/js/action_log.js?v="+ Math.random() +'&'+ $.param({
            log_type: log_type,
            log_data: log_data,
            visit_sys: _static_visit_sys,
            area_id: area_id
        });
		
    }
	
	$('.hideRecommendCtip').click(function(){
	    hideRecommendCtip();
	});
    $('.recommendCtip').click(function(){
        hideRecommendCtip();
		window.location.href = $('.recommendCtip').attr('data-url');
    });
	function hideRecommendCtip(){
		$('.recommendCtip').hide();
		var cookie_time = new Date("<?php echo date('Y/m/d 00:00:00',strtotime('+14 day'));?>
");
		var account_id = getCookie('accountid');
		var recommend_cookie_pre = "showArtificialRecommendTip_" + account_id;

		thisSetCookie()
	}
    function isWeiXin(){
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){
            return true;
        }else{
            return false;
        }
    }

    function thisSetCookie() { //设置cookie
        $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/apply/setArtificialRecommendCookie/"),$_smarty_tpl);?>
',function(result){
			 	
        });
    }

    function getCookie(name)
    {
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg))
            return unescape(arr[2]);
        else
            return null;
    }
</script>
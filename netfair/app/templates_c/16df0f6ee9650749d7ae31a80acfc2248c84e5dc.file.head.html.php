<?php /* Smarty version Smarty-3.0.7, created on 2020-03-20 16:49:57
         compiled from "app\templates\public/shuanxuan/head.html" */ ?>
<?php /*%%SmartyHeaderCode:170575e7483b5dcabf7-36951763%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '16df0f6ee9650749d7ae31a80acfc2248c84e5dc' => 
    array (
      0 => 'app\\templates\\public/shuanxuan/head.html',
      1 => 1584689965,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '170575e7483b5dcabf7-36951763',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
?>
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'newHeader2019.css'),$_smarty_tpl);?>
" />
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-reset.css,v2-widge.css,v2-header.css,newindex2018.css'),$_smarty_tpl);?>
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
                'product.sideMenu.sideSortMenuData', '@popup',
            ],
        },
    });
    hbjs.loadJS('<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/js/v2/cqjob/common.js');

    //<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
www.huibo.com
    var homeLink = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
';
    //<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
样式目录
    var homeStyle = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
';
    //<?php echo $_smarty_tpl->getVariable('siteurl')->value['person'];?>
跳转到个人
    var personLink = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['person'];?>
';
    //<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
跳转到企业
    var companyLink = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
';

    var feedBackLink = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/personplan/GetFeedBackData/';
    var feedBackSetLink = '<?php echo $_smarty_tpl->getVariable('siteurl')->value['main'];?>
/personplan/setFeedBackData/';
</script>
<style type="text/css">
	.huiboJob { overflow : hidden;}
	.huiboJob a { display : block;width : 100%; height : 60px; background : url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/zhaopinhui2020.jpg) center top no-repeat;}

	a.enterprise_qiye i {background : url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/enterCompany.png) no-repeat;display : inline-block;width : 18px;height : 17px;vertical-align : -4px;vertical-align : -2px \9;margin-right : 5px;}
	.newHeadtop .newheadMage .log_new a.enterprise_qiye:link {color : #999;cursor : pointer;}
	#userEnterBox .reg_new .jobWanted {margin-right : 0;}
	.jobWanted i {background : url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/newindex/login_register.png) no-repeat;display : inline-block;width : 14px;height : 16px;vertical-align : -4px;vertical-align : -2px \9;margin-right : 5px;}
</style>
<div class="newHeadtop clearfix">
	<div class="newheadMage">
		<div id="userEnterBox" class="login_reg">
            <?php if ($_smarty_tpl->getVariable('isLogin')->value){?>
				<div class="log_new">
					<a class="<?php if ($_smarty_tpl->getVariable('type')->value=='p'){?>jobWanted<?php }else{ ?>enterprise_qiye<?php }?>" href="javascript:;">
						<i></i><?php echo $_smarty_tpl->getVariable('username')->value;?>

					</a>
					<a href="/login/Logout" class="login_out" title="">退出</a>
				</div>
            <?php }else{ ?>
				<div class="log_new">
					<a class="enterprise_qiye" href="<?php echo $_smarty_tpl->getVariable('company_login_url')->value;?>
" target="_blank">
						<i></i>企业入口
					</a>
				</div>
				<u>|</u>
				<div class="reg_new">
					<a href="javascript:" class="jobWanted netfair_person_login"><i></i>求职登录</a>
				</div>
            <?php }?>
		</div>
	</div>
</div>

<script>
    var person_login_url = '<?php echo $_smarty_tpl->getVariable('person_login_url')->value;?>
';
    var company_login_url = '<?php echo $_smarty_tpl->getVariable('company_login_url')->value;?>
';
    var login_dialog, Dialog;

    hbjs.use('@confirmBox', function (m) {

        var ConfirmBox = m['widge.overlay.confirmBox'],
            cookie = m['tools.cookie'],
            fontSize = 18,
            pWidth = 70;
        Dialog = m['widge.overlay.hbDialog'];

        var clientWidth = document.body.clientWidth;
        if (clientWidth < 1400) {
            $('.businessHallWindow').addClass('businessHallWindow1400');
        }
        login_dialog = new Dialog({
            close: '×',
            idName: 'ui_login_dialog',
            title: '求职者登录',
            width: 330,
            content: person_login_url,
            isAjax: true,
        });

        //求职者登录
        $('.netfair_person_login').on('click', function () {
            login_dialog.setContent({content: person_login_url, 'title': '求职者登录'});
            login_dialog._addLoading();
            login_dialog.on('loadComplete', function () {
                login_dialog._removeLoading();
            });
            login_dialog.show();
            return false;
        });

        //企业登录
        $('.netfair_company_login').on('click', function () {
            window.location.href = company_login_url;
        });
    });
</script>

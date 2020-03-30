<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 09:44:29
         compiled from "app\templates\./companyajaxlogin.html" */ ?>
<?php /*%%SmartyHeaderCode:134205e702b7d20ca61-68972760%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ad4280d8163b84f9cd3cca05fa623502fe2946e2' => 
    array (
      0 => 'app\\templates\\./companyajaxlogin.html',
      1 => 1584332292,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '134205e702b7d20ca61-68972760',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<style>
.ajaxlogin{width:290px;}
.ajaxlogin .formMod .l{width:25px;}
.ajaxlogin .formMod .r{width:250px;}
.ajaxlogin .formMod .r .formChb{margin:0;}
.ajaxlogin .formMod .r .formText input.text{width:197px;}
.ajaxlogin .formBtn{margin:0 0 20px 30px;}
.ajaxlogin .formBtn a.btn1{width:168px; text-align:center;}
.ajaxlogin .txt{font-size:12px;margin:0 0 0 35px;color:#666;}
.ajaxlogin .txt p{line-height:24px;}
.ajaxlogin .txt p i{margin:0 10px;}
</style>
<form  id="frmUserLogin"  action="<?php echo smarty_function_get_url(array('rule'=>"/login/AjaxLoginDo/"),$_smarty_tpl);?>
" method="post">
<div class="dgBox ajaxlogin">
	<div class="form">
        <div class="formMod">
            <div class="l">&nbsp;</div>
            <div class="r"><span class="formText"><label for="id" class="txtLabel">用户名/邮箱/手机号</label><input type="text" class="text " id="id" name="txtUsername" /></span></div>
            <div class="clear"></div>
        </div>
        <div class="formMod">
            <div class="l">&nbsp;</div>
            <div class="r"><span class="formText"><label for="pass" class="txtLabel">密码</label><input type="password" class="text " id="pass"  name="txtPassword"/></span></div>
            <div class="clear"></div>
        </div>
        <div class="formBtn"><a href="javascript:void(0);" class="btn1 btnsF16" id="btnLogin">登录</a></div>
    </div>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $.focusblur('input.text'); 
});

var frmUserLogin = $("#frmUserLogin").validate({
	rules:{
		txtUserName:{
			required:true
		},
		txtPassword:{
			required:true
		}
	},
	messages:{
		txtUserName:{
			required:'请输入用户名 <span class="tipArr"></span>'
		},
			txtPassword:{
			required:'请输入密码<span class="tipArr"></span>'
		}
	},
	errorClasses:{
		txtUserName:{
			required:'tipLayErr tipw120'
		},
		txtPassword:{
			required:'tipLayErr tipw120'
		}
	},
	tipClasses:{
		txtUserName:'tipLayTxt tipw120',
		txtPassword:'tipLayTxt tipw120'
	},
	errorElement:'span',
	errorPlacement:function(error,element){
		element.closest('div.formMod').find('.tipPos .tipLay').empty().append(error);
	},
	success:function(label){
		label.text(" ");
	}
});
/**
 * 登录
 */
var login ={
	init:function() {
		$("#id,#pass").keydown(function(e){
	    	if(e.keyCode == 13){
	            $("#btnLogin").click();
	        }
	    });
     	var val = cookieutility.get('username');
        if(val) {
           $('#id').prev().hide();
           $('#id').focus().val(decodeURI(val)); 
        }
		$('#btnLogin').click(function(){
			$(this).submitForm({ beforeSubmit: $.proxy(frmUserLogin.form, frmUserLogin),success:function(result){
		 		if(result && result.code &&result.code==1888){
		 		    window.location.href = "<?php echo smarty_function_get_url(array('rule'=>"/login/forcelogin/"),$_smarty_tpl);?>
?" + "part=<?php echo $_smarty_tpl->getVariable('is_part')->value;?>
&backurl=<?php echo $_smarty_tpl->getVariable('redirect')->value;?>
&ishistoryback=true";
		 		    return ;
		 		}
				if(result.error){
					 $.message(result.error,{title:'登录失败 '});
				}
		 		else if(result.success){
		 			$('#btnLogin').closeDialog();
					$.anchorMsg(result.success,{title: "登录成功", icon: "success"});
					var callback = <?php echo $_smarty_tpl->getVariable('callback')->value;?>
;
				 	if(typeof callback !=''&& typeof callback !='undefined') {
				 		callback();
				    }
				}
			},clearForm:false});
			
		});
	}
};
login.init();
</script>
</body>
</html>

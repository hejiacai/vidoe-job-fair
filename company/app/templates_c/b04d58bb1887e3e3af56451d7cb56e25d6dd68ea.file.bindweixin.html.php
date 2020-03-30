<?php /* Smarty version Smarty-3.0.7, created on 2020-03-24 16:32:27
         compiled from "app\templates\bindweixin.html" */ ?>
<?php /*%%SmartyHeaderCode:129365e79c59be42148-38990162%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b04d58bb1887e3e3af56451d7cb56e25d6dd68ea' => 
    array (
      0 => 'app\\templates\\bindweixin.html',
      1 => 1585036833,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '129365e79c59be42148-38990162',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
if (!is_callable('smarty_modifier_replace')) include 'E:\slightPHP\plugins\smarty3\/plugins\modifier.replace.php';
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
" />
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'common.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery.form.js'),$_smarty_tpl);?>
"></script>
    <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'dialog.js'),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'ui_validate.js'),$_smarty_tpl);?>
"></script>
    <style type="text/css">
        * {padding: 0px;  margin: 0px;  font-family: "微软雅黑";  font-size: 14px;  list-style: none; color: #444;}
        body{background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/code_img01.jpg);font-family: "Microsoft YaHei";}
        a {  text-decoration: none;}
        img {  border: none;  }
        .codeMain{width:430px; margin:90px auto 0 auto; overflow: hidden;}
        .huiboLogo{ display: block;width:206px; height: 49px; background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/huiboLogo.png) no-repeat; margin: 0 auto 50px auto;}
        .codeBgk{width:430px; background: #fff; overflow: hidden; border-radius: 4px; text-align: center;}
        .codeBgk img,.codeBgk span{ display: block; margin: 0 auto;}
		.codeBgk span.codeTitx{ padding: 60px 0 25px 0; font-size: 18px;}
		.codeBgk span.codeTitz{ padding: 40px 0 20px 0; color: #666;}
		.codeBgk span.codeTitm{ padding: 0px 0 45px 0; font-size: 16px;}
		.codeBgk span.codeNext{ padding: 0px 25px 30px 0; font-size: 12px; color: #ccc; text-align: right;}
		.codeBgk span.codeNext:hover{ color: #999; text-decoration: underline;}
    </style>
</head>
<body>
<div class="codeMain">
    <a href="//www.huibo.com/" class="huiboLogo"></a>
    <div class="codeBgk">
        <span class="codeTitx">登录验证</span>
        <img id="codeimg" src=""/>
        <span class="codeTitz">需验证你的登陆信息</span>
        <span class="codeTitm">用手机微信扫码关注公众号 • 绑定微信身份</span>
        <?php if ($_smarty_tpl->getVariable('source')->value!='netfair'){?>
        <span class="codeNext" style="cursor:pointer">下次验证</span>
        <?php }?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var loadcodeurl = function() {
            $('#codeimg').removeAttr('width').removeAttr('height').attr('src',"<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/c/indicator_medium.gif");
            $.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/account/twodimensioncode/"),$_smarty_tpl);?>
',function(result){
                if(result&&result.status) {
                    $('#codeimg').removeAttr('src').attr({width:234,height:234,src:result.data.codeurl});
                }else {
                    $('#codeimg').attr({title:'加载失败，请刷新页面',src:''});
                }
            });
        };

        var t = "";
        function checkBindWX() {
            clearTimeout(t);
            $.ajax({
                url: "<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
/login/CheckBindWX",
                data: {},
                dataType: "json",
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    t = setTimeout(function(){
                        checkBindWX();
                    },1500);
                },
                success: function (json, textStatus) {
                    if(json.status){
                        if(json["data"].isbind == false){
                            t = setTimeout(function(){
                                checkBindWX();
                            },1500);
                        }else{
                            <?php if ($_smarty_tpl->getVariable('part')->value){?>
                            window.location.href = "/part";
                            <?php }elseif((!empty($_smarty_tpl->getVariable('redirect',null,true,false)->value))){?>
                                <?php if ($_smarty_tpl->getVariable('source')->value=='netfair'){?>
                                window.location.href = "<?php echo smarty_function_get_url(array('rule'=>'/index/AddCompanyNet','domain'=>'netfair'),$_smarty_tpl);?>
/?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
";
                                <?php }else{ ?>
                                window.location.href = "<?php echo smarty_function_get_url(array('rule'=>($_smarty_tpl->getVariable('redirect')->value)),$_smarty_tpl);?>
";
                                <?php }?>
                            <?php }else{ ?>
                            window.location.href = "/";
                            <?php }?>
                        }
                    }else{
                        $.message(json.msg, {title: '操作失败！',onok:function(){
                            window.location.reload();
                        }});
                    }
                }
            });
    }


        $('#codeimg').click(function(){
            loadcodeurl();
        });

        $(".codeNext").click(function(){
            <?php if ($_smarty_tpl->getVariable('part')->value){?>
            window.location.href = "/part";
            <?php }elseif((!empty($_smarty_tpl->getVariable('redirect',null,true,false)->value))){?>
            window.location.href = "<?php echo smarty_function_get_url(array('rule'=>($_smarty_tpl->getVariable('redirect')->value)),$_smarty_tpl);?>
";
            <?php }else{ ?>
            window.location.href = "<?php echo smarty_modifier_replace($_smarty_tpl->getVariable('siteurl')->value['company'],'http:','');?>
";
            <?php }?>

        });
        loadcodeurl();
        checkBindWX();
    });
</script>
</body>
</html>

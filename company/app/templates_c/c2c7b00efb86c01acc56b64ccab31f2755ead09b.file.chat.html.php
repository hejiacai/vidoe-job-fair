<?php /* Smarty version Smarty-3.0.7, created on 2020-03-17 10:19:14
         compiled from "app\templates\chat/chat.html" */ ?>
<?php /*%%SmartyHeaderCode:183785e7033a21442c0-29112598%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c2c7b00efb86c01acc56b64ccab31f2755ead09b' => 
    array (
      0 => 'app\\templates\\chat/chat.html',
      1 => 1584332295,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '183785e7033a21442c0-29112598',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_get_url')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.get_url.php';
?><?php include_once ('app/controller/chat.php');?>

<style type="text/css">
    .qq_newsz{width:338px; height: 58px; overflow:hidden; background:url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/chat/chat_icon15.png) no-repeat; position: fixed; bottom:188px; left: 50%; margin-left: 162px; z-index: 999;}
    .qq_newsz span,.qq_newsz a{ display: block; float: left;}
    .qq_newsz span{width:285px; font-size: 16px; color: #fff; line-height: 20px; text-align: left; margin: 10px 0 0 18px;cursor:pointer}
    .qq_newsz span b{color: #ff3532; font-weight: normal;}
    .qq_newsz a{ float: right; width:24px; height: 24px;  margin: 7px 8px 0 0;}
    .qq_newsx{width:80px; height: 65px; background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/chat/chat_icon_new12.png) left 2px no-repeat; position: fixed; bottom:170px; left: 50%; margin-left: 510px;}
    .qq_newsx i{ display: block; height: 16px; padding: 0 6px; background: #f00; color: #fff; font-size: 10px; margin-left: 30px; float: left; border-radius: 20px; line-height: 18px;}
	.qq_newsx em{ display: block;width:48px; font-size: 10px; color: #444; text-align:center; margin-top: 50px;}
    /*绑定手机号*/
    .boundPhonePop{width:285px; overflow: hidden; padding: 20px; margin: 0 auto; text-align: left;}
    .boundx01,.boundx02{ display: block; padding-bottom: 3px;}
    .boundx01{ line-height: 18px; color: #444;}
    .boundx02{ color: #ff671c;}
    .boundFrom{width:100%; overflow: hidden; padding-top: 15px;}
    .putBound{ display:block; float: left; height: 29px; border: 1px solid #ccc; line-height: 29px; text-indent: 8px;width:282px}
    .noteCodex{width:100%; overflow: hidden; padding-top: 15px;}
    .boundGetCode{ cursor:pointer; display:block; float: right; width:104px; height: 30px; background: #66bce4; color: #fff; text-align: center; line-height: 30px; border-radius: 2px;}
    .noteCodex img{ display: block; float: left; margin-left:15px ; height: 30px;}
    .boundBtn{ display: block;width:282px; height: 32px; text-align: center; line-height: 32px; background: #66bce4; margin-top: 15px; border-radius: 2px; color: #fff;}
    .boundBtn:hover,.boundGetCode:hover{ background: #39ade3; color: #fff;}
    /*职位沟通*/
    .postSearchPop{width:335px; padding: 15px; overflow: hidden; margin: 0 auto; text-align: left;}
    .postSearchx{width:333px; height: 28px; border: 1px solid #ccc; overflow: hidden;}
    .postSearchx input{ display: block; float: left; border: none;width:300px; height: 28px; line-height: 28px; text-indent: 8px;}
    .postSearchx a{ display: block; float: right;width:30px; height: 28px; background: url(<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/chat/chat_icon16.png) no-repeat; border: none;}
    .postSchList{width:100%; overflow: hidden; padding-top: 15px; height: 210px; overflow-y: auto;}
    .postSchList li{ cursor: pointer; overflow: hidden; line-height: 38px; height: 38px; border-bottom: 1px dashed #e7e7e7;}
    .postSchList li span{ display: block;width:230px; height: 38px; float: left; overflow:hidden;white-space:nowrap;text-overflow:ellipsis; text-indent: 15px;}
    .postSchList li span em{ display:inline-block; height:16px; line-height:16px; padding:0 6px; border-radius:2px; background:#15cd7d; color:#fff; text-indent:0px; font-size:10px; margin-right:5px;}
    .postSchList li a{ display: block; float: right; padding-left: 20px; margin-right: 15px; color: #3d84b8; font-size: 12px;}
    .postSchList li:hover{ background: #f6f8fe;}
    .noPostx{width:100%;}
    .noPostx img{ margin: 0px auto 20px auto;}
    .noPostx img,.noPostx span{ display: block; text-align: center; font-size: 12px; color: #999;}
    .noPostx span a:hover {
        color: #3d84b8;
    }
    
    /*加载层*/
    .chatloading{width:104px; height:30px;text-align:left; font-size:12px;}
    .boundGetCode img{ display:inline-block;width:16px; height:16px;margin-top:7px; color:#666; margin-right: 10px}
    
    /*转发*/
    .chatBottomAndSend{ height:30px;position:absolute; bottom:6px; left:17px}
    .chatBottomAndSend a{ display:block; float:left; text-align: center; width: 88px;border-radius: 10px;color: #fff;background-color: #2b6fad;line-height: 20px;}
	/* .chatBottomAndSend a{ display:block; float:left; text-align: center; width: 88px;color: #fff;line-height: 20px;} */
		
    .chatBottomAndSend a:hover{ background-color:#3D84B8;color:#fff;}
		
		.chatBottomAndSend a.notOffenUse{ background-color:#999;}
		.chatBottomAndSend a.notOffenUse:hover{  background-color:#666;}

    /*.chatBottomAndSend a{ display:block; float:left; text-align: center; width: 88px;border-radius: 10px;color: #2b6fad;line-height: 20px;}


    .chatBottomAndSend a:hover{color:#3D84B8;}

    .chatBottomAndSend a.notOffenUse{ color:#999;}
    .chatBottomAndSend a.notOffenUse:hover{  color:#666;}*/
		
    .chatBottomAndSend span{ display:block; float:left; padding:0 8px; color:#ccc; line-height:30px}
    
    .yaoqing-alert{margin:30px;color:#999;font-size:14px;line-height: 22px; text-align: center}
    .yaoqing-alert dt{float: left;width:121px;height: 121px;margin-right: 20px}
    .yaoqing-alert .t1{color:#0ea5a0;font-size:24px;font-family: '微软雅黑';margin-bottom: 15px}
    .yaoqing-alert .t2{margin-bottom: 15px}
    .yaoqing-alert .t2 span{color:#ff6563}
    .yaoqing-alert .link2{color:#3f74c2;font-size:14px;margin-left: 10px}
    .yqQual{height: 30px;background: #66bce4;font-size: 14px;color: #fff;line-height: 30px;border-radius: 2px;margin-right: 10px;width: 121px;display: inline-block;}
    .yqQual:hover{ background: #31a2d6; color: #fff;}

    /*聊一聊*/
/*    .liaoyiliao_content{padding:10px; text-align:left;}
    .liaoyiliao_content .liaoyiliao_text{font-size:16px;margin-bottom:5px;}
    .liaoyiliao_content .liaoyiliao_tishi{display: inline-block; margin-bottom: 2px; color:#666; text-align: left;}
    .liaoyiliao_content .liaoyiliao_NotTS{color:#666; text-align: left;}
    .liaoyiliao_content .liaoyiliao_NotTS input[type=checkbox]{vertical-align: middle;}
    .liaoyiliao_content .liaoyiliao_BTN{text-align: center;}
    .liaoyiliao_content .liaoyiliao_BTN input[type=button]{margin:0px 15px; padding: 3px 8px; font-size:14px; font-size:12px; border:none; border-radius:3px;cursor: pointer;}
    .liaoyiliao_content .liaoyiliao_BTN input[type=button].liaoyiliao_ok{color:#fff; background:#0a63a3;}
    .liaoyiliao_content .liaoyiliao_BTN input[type=button].liaoyiliao_ok:hover{ background:#0a81ff;}
    .liaoyiliao_content .liaoyiliao_BTN input[type=button].liaoyiliao_close:hover{ background:#ccc;} */

	/* 2019.10.9扣聊一聊点新按钮 */
	.remindWord{
		text-align: left;
		line-height: 20px;
	}
	.remindWord p{
		padding-bottom:5px;
	}
</style>
    <div id="MyHbChat" style="display:none">
	<div class="qq_newsz" style="display:none">
            <span class="chatMsgPart">又收到<b class="chatMsgCount">0条</b>来自求职者的消息了，快去看看吧！</span>
            <a href="javascript:void(0);" class="closeChatTip"></a>
        </div>
        <a href="javascript:void(0);" class="qq_newsx chatButtom md_chat" data-from-resume-id='<?php echo $_smarty_tpl->getVariable('from_detail_resume_id')->value;?>
'>
            <i class="chatNewMsg" style="display:none"></i>
			<em>聊天列表</em>
        </a>
    </div>
<?php if ($_smarty_tpl->getVariable('can_chat')->value){?>
<script type="text/javascript" language="javascript" src='<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/js/chat/json2.js'></script>
<script type="text/javascript" language="javascript" src='<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/js/chat/nim/NIM_Web_SDK_v6.10.0.js'></script>
<script type="text/javascript" language="javascript" src='<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/js/chat/nim/NIM_Web_NIM_v6.10.0.js'></script>
<?php }?>
<script>
    //解决ie8js不执行
if (typeof window.console === "undefined") {
        window.console = {};
        window.console.log = function(str) {};
}

var dialog_msg;
hbjs.use('@validator, @fileUploader, @confirmBox, @hbCommon, @jobDialog', function(m){
    var ConfirmBox = m['widge.overlay.confirmBox'];
    var Dialog = m['widge.overlay.hbDialog'];
    var $ = m['product.hbCommon'].extend(m['cqjob.jobDialog']);
    var cookie = m['tools.cookie'];
    var seed   = "<?php echo $_smarty_tpl->getVariable('chat_seed')->value;?>
";
    //var need_bind = <?php if ($_smarty_tpl->getVariable('need_bind_person')->value){?>true<?php }else{ ?>false<?php }?>; //是否需要绑定企业
   // var can_auto_bind = <?php if ($_smarty_tpl->getVariable('can_auto_bind')->value){?>true<?php }else{ ?>false<?php }?>; //企业是否可以自动绑定
    var chat_job_list    = <?php echo $_smarty_tpl->getVariable('chat_job_list')->value;?>
; //职位列表
    var pageFrom     = "<?php echo $_smarty_tpl->getVariable('chatFrom')->value;?>
";
    var chatTotalMsg = 0; //最新消息数量
    var chatLoginStatus = chatGetCookie("chatLoginStatus");
	// 2019.10.9
    var htmlTemp=["该求职者近期未使用聊一聊，可能收不到消息，是否继续？",
	'<p>需消耗1次聊一聊次数，是否继续？</p>',
	'<label for=""><input type="checkbox" value="1" id="notshow">下次不再提示</label>',
	'<p style="color:#666;">（优先使用推广金，推广金不足则扣取余额）</p>'];

    var chatinterval;

    if(pageFrom == "notBindPage"){
        $("#MyHbChat").hide();
    }else{
        $("#MyHbChat").show();
    }
    console.log(chatLoginStatus);

    //点击聊天按钮
    $("#MyHbChat .chatButtom").on("click",function(){
        var from_resume_id = $(this).attr("data-from-resume-id");
       if(from_resume_id !=""){ //简历详情页进入 需要选择职位和判断下载
           $("body").find(".chatOneChat").click();
           return;
       }
        hideUnRead();
        //window.location.href = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat";
        window.open("<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat");
        return;
       /* if(!need_bind){
            //隐藏未读消息
            hideUnRead();
            //window.location.href = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat";
            window.open("<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat");
            return;
        }
        if(can_auto_bind){
            var auto_bind_url = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/AutoBind";
            $.getJSON(auto_bind_url,{},function(result){
                if(!result.status){
                    chatBindPhond();//绑定手机
                }else{
                    hideUnRead();
                    //window.location.href = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat";
                    window.open("<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat");
                    return;
                }
            });
            return;
        }
        chatBindPhond();//绑定手机*/
    });
    //聊天页面绑定手机
    $("#noBindCart .bindButton").on("click",function(){
        chatBindPhond();
    });
    
    $(".chatMsgPart").on("click",function(){
        $(this).parent(".qq_newsz").hide();
        hideUnRead();
        window.open("<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat");
        
    });
    var chatBindPhond = function(){  //绑定手机
         //绑定APP
        
        var bindHtml = getBindHtml();
        var bindDialog = new Dialog({
                idName: 'bind_dialog',
                title: '绑定手机',
                content: bindHtml,
                close: '╳',
                width: 370
        });
        var bindObj = bindDialog.query("#chatBindPhoen");//弹窗 jquey 对象
        //图片验证码添加
        bindObj.on("click","#chatImgCode",function(){
            $(this).attr('src',"<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/verify/?seed="+seed+"&rand="+Math.random());
        });
        //获取短信验证码
        var countdown = function(){
            var seconds = bindObj.find('.cutDown i').html();
            seconds = parseInt(seconds);
            if(seconds>0){
                seconds--;
                bindObj.find('.cutDown i').html(seconds);
            }else{
                window.clearInterval(chatinterval);
                bindObj.find("#chatImgCode").click();
                bindObj.find(".getPhoneCode").show();
		bindObj.find(".cutDown").html("<i>60</i>秒后重新获").hide();
            }
        };
        bindObj.on("click",".getPhoneCode",function(){
            window.clearInterval(chatinterval);//清除倒计时
            bindObj.find(".getPhoneCode").show();
            bindObj.find(".cutDown").html("<i>60</i>秒后重新获").hide();
            var vild_code = bindObj.find("#boundImgCode").val();
            //发送短信
           var phone = bindObj.find('#boundPhone').val();
            if(phone==''){
                $.anchorMsg("请填写手机号码",{ icon: 'warning' });
                return;
            }
            if(vild_code == ""){
                $.anchorMsg("请输入图片验证码",{ icon: 'warning' });
                return;
            }
            var pattern = /^[1]\d{10}$/;
            if(!pattern.exec(phone)){
                    $.anchorMsg("手机号码格式不正确",{ icon: 'warning' });
                    return;
            }
            var data = {phone:phone,seed:seed,vild_code:vild_code};
            var sendMsgUrl = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/sendmobilecode/";
            bindObj.find(".getPhoneCode").hide();
            bindObj.find(".chatloading").show();
            $.getJSON(sendMsgUrl,data,function(result){
                bindObj.find(".chatloading").hide();
		if(result && result.error){
                    bindObj.find("#chatImgCode").click();
                    bindObj.find(".getPhoneCode").show();
                    $.anchorMsg(result.error,{ icon: 'warning' });
                    return;
		}
                //显示倒计时
		bindObj.find(".cutDown").html("<i>60</i>秒后重新获").show();
                $.anchorMsg("已发送验证码短信到您的手机");
                
		bindObj.find("#boundNoteCode").focus();
		chatinterval = window.setInterval(countdown,1000);
            });
        });
        
        //绑定
         bindObj.on("click",".bindSubmit",function(){
            var _this = $(this);
            if(_this.hasClass("isLock")){
                return;
            }
            //发送短信
           var phone = bindObj.find('#boundPhone').val();
            if(phone == ''){
                $.anchorMsg("请填写手机号码",{ icon: 'warning' });
                return;
            }
            var pattern = /^[1]\d{10}$/;
            if(!pattern.exec(phone)){
                $.anchorMsg("手机号码格式不正确",{ icon: 'warning' });
                return;
            }
            var msg_code = bindObj.find("#boundNoteCode").val();
            if(msg_code == ""){
                $.anchorMsg("请输入短信验证码",{ icon: 'warning' });
                return;
            }
            
            var data = {phone:phone,msg_code:msg_code};
            var bindUrl = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/bindPerson/";
            
            _this.addClass("isLock");
            _this.html("绑定中，请稍后...");
           
            $.getJSON(bindUrl,data,function(result){
		_this.removeClass("isLock");
                _this.html("立即绑定");
                if(result && result.error){
                    if(result.code == 2){
                        bindDialog.hide();
                        ConfirmBox.confirm(result.error,'换绑手机提示',function(obj){
                            var self = this;
                            self.hide();
                            var rebind_data = {"phone":phone,"unbind_person_id":result.unbind_person_id,"unbind_account_id":result.unbind_account_id};
                            var rebindUrl = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/Rebind/";
                            $.getJSON(rebindUrl,rebind_data,function(rebindresult){
                                if(rebindresult.error){
                                    $.anchorMsg(rebindresult.error,{ icon: 'warning' });
                                    return;
                                }
                                $.anchorMsg("重新绑定成功，现在可以随时随地与求职者在线沟通了！",{onclose:function(){
                                    //刷新页面
                                    window.location.reload();
                                }});
                                  return;
                            });
                             
                         },{'width':350});
                        return;
                    }else{
                        $.anchorMsg(result.error,{ icon: 'warning' });
                        return;
                    }
                    return;
		}
              //  $.anchorMsg("绑定成功，现在可以随时随地与求职者在线沟通了！");
                $.anchorMsg("绑定成功，现在可以随时随地与求职者在线沟通了！",{onclose:function(){
                    //刷新页面
                    bindDialog.hide();
                    window.location.reload();
                }});
                
              
            });
            
         });
        bindDialog.show();
    };

            dialog_msg = new Dialog({
                close: '╳',
                idName: 'buyPoints',
                width: 400,
                title: '提示',
                isAjax: true,
                isOverflow : false,
                content: '<?php echo smarty_function_get_url(array('rule'=>"/index/ShowHRManagerMsg/"),$_smarty_tpl);?>
'
            });

    var dialog_msg_liao = new Dialog({
        close: '╳',
        idName: 'liaoyiliao',
        width: 'auto',
        title: '提示',
        isAjax: true,
        isOverflow : false
    });

    var key_liaoyiliao  = "liaoyiliao_notTS";
    var person_id = '';
    var liaoyiliao_resume_id = '';
    /**
     *@desc 聊一聊事件 
     */
    $("body").on("click",".chatOneChat",function(e){
        var _this = $(this);
		liaoyiliao_resume_id = _this.attr("data-resume-id");
		var run0=_this.running();
		$.getJSON('<?php echo smarty_function_get_url(array('rule'=>"/resume/CheckCompanyLetter/"),$_smarty_tpl);?>
'+ 'check_type-chat-resume_id-'+liaoyiliao_resume_id+'-v-' + Math.random(), function(json){
			$(_this).attr('data-notice-status',0)
			
			run0.close()
			if(json.isNeedLogin){
				window.location.reload();
				return;
			}
			if(!json.status){
				if(json.code == 701) {
					var letter_dialog = new Dialog({
							idName: 'letter_dialog',
							title: "企业认证",
							content: getLetterContent(json.msg),
							close: '╳',
							width: 450
					});
					letter_dialog.show();
					return;
				}
				if(json.code == 702) {
					 ConfirmBox.timeBomb(json.msg, {
							 name: 'fail',
							 timeout: 2000,
							 width:  json.msg*18 + 20,
							 zIndex: 999999
					 });
					return;
				}
			}else{
                //2019-11-05注释，从腾讯切换成网易运IM，网易云IM不需要绑定
				//是否能自动绑定
				/*if(can_auto_bind){
					var auto_bind_url = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/AutoBind";
					$.getJSON(auto_bind_url,{},function(result){});
					need_bind = false;
				}
				if(need_bind){ //如果需要绑定 则 先绑定
					chatBindPhond();//绑定手机
					return;
				}*/
                need_bind = false;//为了注释掉之后不影响之前的逻辑，这里强行定义为不需要绑定的状态
				//2019-11-05注释结束
				var not_area_limit = _this.attr('data-arealimit-id');
				if(not_area_limit==0){
					chatAreaLimit();return false;//已超出简历下载地区限制，请联系工作人员开通相应招聘服务。
				}
				//绑定成功后判断是否需要下载
				var resume_id       = _this.attr("data-resume-id");
				var need_downlaod   = _this.attr("data-need-download");
				var job_id          = _this.attr("data-job-id");
				var from            = _this.attr("data-from");
				var job_effect      = _this.attr("data-job-effect");//职位是否有效
				if(typeof(job_id) !='undefined' && job_id !='' && typeof(job_effect) !='undefined' && job_effect == 0){
					job_id = "";
				}
				var checkDownloadUrl = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/download/CheckBalanceV2/";
				var data = {resume_id:resume_id,get_alert:false};

				if(need_downlaod == 1 || true){
					var run1=_this.running();
						$.getJSON(checkDownloadUrl,data,function(result){
							run1.close()
							person_id = result.data.person_id;
							if(result.remain_status==0){
								// selectJob(resume_id,job_id,_this);
								checkHaveChat(resume_id,job_id,_this);
								return;
							}
							
							//该企业跟该求职者3个月内未聊过
							if(result.status == '200'){
								
								if(result.remain_status=='1'){
									var notUseDialog = ConfirmBox.confirm(
										htmlTemp[0], 
										"提示",
										function(){
											notUseDialog.hide()
											// selectJob(resume_id,job_id,_this);
											checkHaveChat(resume_id,job_id,_this);
										},
										function(){     //取消回调
											console.log('点击取消按钮')
											notUseDialog.hide()
										},{
											width: 400,
											close : 'x',
											cancelBtn: '<button class="btn3 btnsF14">取消</button>',
											confirmBtn: '<button class="btn1 btnsF14">继续</button>'	
										}
									);
								}else{
									// selectJob(resume_id,job_id,_this);
									checkHaveChat(resume_id,job_id,_this);
								}
							}else{
								function thisDialogContent(text, discript) {
									var div = "<div class='liaoyiliao_content'>" +
											"<p class='liaoyiliao_text'>"+text+"</p>" +
											"<span class='liaoyiliao_tishi'>"+discript+"</span>" +
											"<p class='liaoyiliao_NotTS'><label><input type='checkbox' class='NotTs' value='1'>下次不再提示</label></p>" +
											"<p class='liaoyiliao_BTN'><input type='button' value='确&nbsp;&nbsp;定' class='liaoyiliao_ok'><input type='button' value='取&nbsp;&nbsp;消' class='liaoyiliao_close'></p>" +
											"</div>";
									dialog_msg_liao.setContent(div).show();
								}
								var is_NotTs = cookie.get(key_liaoyiliao);
								//未聊过
								//聊天次数充足提示 获取余额充足提示
								if(result.status == '401' || result.status == '402'){
									
										if(is_NotTs==1){
												chatDownLoadResume(resume_id,from,_this,job_id,person_id,_this);
										}else{
											// var descript = result.status == '402' ? '（优先使用推广金，推广金不足则扣取余额）' : '';
											// thisDialogContent(result.msg , descript);
											var remindWord='<div class="remindWord">'
											+(result.remain_status==2?('<p style="color:red">'+htmlTemp[0]+'</p>'):'')
											+"<p>"+result.msg+"</p>"
											+(result.status == '402' ? htmlTemp[3] : '')
											+htmlTemp[2]
											+'</div>';
											if (result.remain_status==1){
												remindWord=htmlTemp[0]
											}
											var notUseDialog = ConfirmBox.confirm(
												remindWord, 
												"提示",
												function(){
                                                    liaoyiliao_ok_fn();
                                                    notUseDialog.hide();
												},
												function(){     //取消回调
													console.log('点击取消按钮')
													notUseDialog.hide()
												},{
													width: 400,
													close : 'x',
													cancelBtn: '<button class="btn3 btnsF14">取消</button>',
													confirmBtn: '<button class="btn1 btnsF14">继续</button>'	
												});
										}
										
								}else{
										dialog_msg_liao.setContent("<?php echo smarty_function_get_url(array('rule'=>'/resume/resumeinfomsghtml'),$_smarty_tpl);?>
").show();
								}
							}
							
						});
					return;
				};
				//选择需要聊天的职位 todo
				//todo:异步获取14天内是否被所属企业的其他账号沟通过
				checkHaveChat(resume_id,job_id,_this);
			}
			
		});
	})	

	var liaoyiliao_ok_fn=function () {
        var is_NotTs = $('#notshow').prop('checked');
        if(is_NotTs){
            cookie.set(key_liaoyiliao,1,{expires:'',path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
        }

        var _this = $('body .chatOneChat[data-resume-id='+liaoyiliao_resume_id+']');
        var resume_id       = _this.attr("data-resume-id");
        var need_downlaod   = _this.attr("data-need-download");
        var job_id          = _this.attr("data-job-id");
        var from            = _this.attr("data-from");
        chatDownLoadResume(resume_id,from,_this,job_id,person_id,_this);
    };
    $('body').on('click','.liaoyiliao_ok',function () {
        var is_NotTs = $('#notShow:checked').val();
        if(is_NotTs){
            cookie.set(key_liaoyiliao,1,{expires:'',path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
        }

        var _this = $('body .chatOneChat[data-resume-id='+liaoyiliao_resume_id+']');
        var resume_id       = _this.attr("data-resume-id");
        var need_downlaod   = _this.attr("data-need-download");
        var job_id          = _this.attr("data-job-id");
        var from            = _this.attr("data-from");
        chatDownLoadResume(resume_id,from,_this,job_id,person_id,_this);
        dialog_msg_liao.hide();
    });
    $('body').on('click','.liaoyiliao_close',function () {
        dialog_msg_liao.hide();
    });

    function getLetterContent(msg){
        var letter_content = "<dl id='yaoqing-alert' class='yaoqing-alert clearfix'><dd style='padding-top:10px'><p class='t2' style='color:#999;text-align: left'>"+msg+"</p><p><a href='/licencevalidate/' class='yqQual'>完善资质</a></p></dd></dl>";
        return letter_content;
    }

    //验证是否14天内已经聊过，没有就跳转，有就弹框提示
    function checkHaveChat(resume_id,job_id,_this) {
        if (job_id && job_id != 'undefined') {
            var chat_history_url = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/checkChatHistoryV2?resume_id=" + resume_id;
            var run2=_this.running()
			$.post(chat_history_url, {}, function (re) {
				run2.close()
                re = JSON.parse(re);
                if (!re.status) {
                    var have_chat_html = "<div class='have_chat_content'>" +
                        "<p class='have_chat_text' style='height: 120px;padding: 35px 35px 0 35px;box-sizing: border-box;text-align: left;'>" + re.msg + "，是否要继续聊天？</p>" +
                        "<p class='have_chat_btn' style='height: 60px;width: 230px;margin: auto;'><input type='button' value='取&nbsp;&nbsp;消' class='cancelChat' style='margin-right: 50px;height:35px;padding: 0 25px;box-sizing: border-box;border-radius: 4px;border: 1px solid #D9D9D9;background-color: #fff;'><input type='button' value='继续聊天' class='goChat' style='height:35px;padding: 0 15px;box-sizing: border-box;border-radius: 4px;border: 1px solid #D9D9D9;background-color: #fff;'></p>" +
                        "</div>";
                    var haveChatDialog = new Dialog({
                        idName: 'have_chat_dialog',
                        title: '',
                        content: have_chat_html,
                        close: '',
                        width: 380,
                        height: 180
                    });
                    haveChatDialog.show();
                    var haveChatObj = haveChatDialog.query(".have_chat_content");
                    haveChatObj.on("click", ".goChat", function () {
                        haveChatDialog.hide();
                        var hr_person_url = "<?php echo smarty_function_get_url(array('rule'=>'/download/GetHrPersonChatHistory'),$_smarty_tpl);?>
";
                        $.post(hr_person_url, {resume_id:resume_id}, function(re){
                            if (re.status && re.data.job_id) {
                                selectJob(resume_id,re.data.job_id,_this);
                            } else {
                                selectJob(resume_id,job_id,_this);
                            }
                        }, 'json');
                        //selectJob(resume_id, job_id,_this);
                    });
                    haveChatObj.on("click", ".cancelChat", function () {
                        haveChatDialog.hide();
                    });
                } else {
                    //selectJob(resume_id, job_id,_this);
                    var hr_person_url = "<?php echo smarty_function_get_url(array('rule'=>'/download/GetHrPersonChatHistory'),$_smarty_tpl);?>
";
                    $.post(hr_person_url, {resume_id:resume_id}, function(re){
                        if (re.status && re.data.job_id) {
                            selectJob(resume_id,re.data.job_id,_this);
                        } else {
                            selectJob(resume_id,job_id,_this);
                        }
                    }, 'json');
                }
            });
        } else {
            //selectJob(resume_id, job_id,_this);
            var hr_person_url = "<?php echo smarty_function_get_url(array('rule'=>'/download/GetHrPersonChatHistory'),$_smarty_tpl);?>
";
            $.post(hr_person_url, {resume_id:resume_id}, function(re){
                if (re.status && re.data.job_id) {
                    selectJob(resume_id,re.data.job_id,_this);
                } else {
                    selectJob(resume_id,job_id,_this);
                }
            }, 'json');
        }
    }

    function selectJobDo(resume_id,from_job_id, msg,$this) {
        var joblisthtml = getJobListHtml(from_job_id, msg);
        var jobListDialog = new Dialog({
            idName: 'selectJob_dialog',
            title: '请选择想要沟通的职位',
            content: joblisthtml,
            close: '╳',
            width: 380
        });
        var joblistObj = jobListDialog.query(".chatPostSearchJob");
        joblistObj.on("click",".gotoChat",function(){
            var _job_id = $(this).attr("data-job-id");
            var chat_url = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/?resume_id="+resume_id+"&job_id="+_job_id;
            window.open(chat_url);
            jobListDialog.hide();
        });
        //搜索职位
        joblistObj.on("click",".searchSubmit",function(){
            var _this = $(this);
            var station = joblistObj.find("#postSearchText").val();
            var searchUrl = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/searchJob";

            _this.hide();
			var $run=$this.running();
            joblistObj.find(".searchjobloading").show();
            $.getJSON(searchUrl,{station:station},function(result){ //获取职位
				$run.close();
                _this.show();
                joblistObj.find(".searchjobloading").hide();
                var job_list = result.job_list;
                var new_job_list = [];
                if(typeof(from_job_id) !='undefined' && from_job_id !=''){
                    for(var i=0;i<job_list.length;i++){
                        if(job_list[i].job_id == from_job_id){
                            new_job_list.unshift({'job_id':job_list[i].job_id,'station':job_list[i].station});
                        }else{
                            new_job_list.push({'job_id':job_list[i].job_id,'station':job_list[i].station});
                        }
                    }
                }else{
                    new_job_list = job_list;
                }
                if(new_job_list.length >0){
                    joblistObj.find(".noChatSearchJob").hide();
                    var li_html = "";
                    for(var i=0;i< new_job_list.length;i++){
                        var _pre_html = "";
                        if(new_job_list[i].job_id==from_job_id){
                            _pre_html = '<em class="chatHasApply">已投</em>';
                        }
                        li_html += '<li><span>'+_pre_html+new_job_list[i].station+'</span><a href="javascript:void(0);" data-job-id="'+new_job_list[i].job_id+'" class="gotoChat">聊一聊</a></li>';;
                    }
                    joblistObj.find("ul").html(li_html);
                    joblistObj.find("ul").show();
                }else{
                    joblistObj.find("ul").hide();
                    joblistObj.find(".chatSearchKeyWord").html(station);
                    joblistObj.find(".noChatSearchJob").show();
                }
            });
        });
        //回车事件
        joblistObj.find("#postSearchText").keydown(function (event) {
            if (event.keyCode == 13) {
                joblistObj.find(".searchSubmit").click();
            }
        });
        jobListDialog.show();
    }
    //选择职位
    var selectJob = function(resume_id,from_job_id,$this){
        if(typeof(from_job_id) !='undefined' && from_job_id !=''){
           var chat_url = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/?resume_id="+resume_id+"&job_id="+from_job_id;
           window.open(chat_url);
           return;
        }
        var chat_history_url = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/checkChatHistoryV2?resume_id="+resume_id;
		var $run=$this.running();
        $.post(chat_history_url, {}, function(re) {
			$run.close();
            re = JSON.parse(re);
            var msg = '';
            if (!re.status && re.msg) {
                msg = re.msg;
            }
            selectJobDo(resume_id,from_job_id, msg,$this);
        });

        /*var joblisthtml = getJobListHtml(from_job_id);
        var jobListDialog = new Dialog({
                idName: 'selectJob_dialog',
                title: '请选择想要沟通的职位',
                content: joblisthtml,
                close: '╳',
                width: 380
        });
        var joblistObj = jobListDialog.query(".chatPostSearchJob");
        joblistObj.on("click",".gotoChat",function(){
            var _job_id = $(this).attr("data-job-id");
            var chat_url = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/?resume_id="+resume_id+"&job_id="+_job_id;
            window.open(chat_url);
            jobListDialog.hide();
        });
        //搜索职位
        joblistObj.on("click",".searchSubmit",function(){
            var _this = $(this);
            var station = joblistObj.find("#postSearchText").val();
            var searchUrl = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/searchJob";
            
            _this.hide();
            joblistObj.find(".searchjobloading").show();
            $.getJSON(searchUrl,{station:station},function(result){ //获取职位
                _this.show();
                joblistObj.find(".searchjobloading").hide();
                var job_list = result.job_list;
                var new_job_list = [];
                if(typeof(from_job_id) !='undefined' && from_job_id !=''){
                    for(var i=0;i<job_list.length;i++){
                        if(job_list[i].job_id == from_job_id){
                            new_job_list.unshift({'job_id':job_list[i].job_id,'station':job_list[i].station});
                        }else{
                            new_job_list.push({'job_id':job_list[i].job_id,'station':job_list[i].station});
                        }
                    }
                }else{
                    new_job_list = job_list;
                }
                if(new_job_list.length >0){
                    joblistObj.find(".noChatSearchJob").hide();
                    var li_html = "";
                    for(var i=0;i< new_job_list.length;i++){
                        var _pre_html = "";
                        if(new_job_list[i].job_id==from_job_id){
                            _pre_html = '<em class="chatHasApply">已投</em>';
                        }
                        li_html += '<li><span>'+_pre_html+new_job_list[i].station+'</span><a href="javascript:void(0);" data-job-id="'+new_job_list[i].job_id+'" class="gotoChat">聊一聊</a></li>';;
                    }
                    joblistObj.find("ul").html(li_html);
                    joblistObj.find("ul").show();
                }else{
                    joblistObj.find("ul").hide();
                    joblistObj.find(".chatSearchKeyWord").html(station);
                    joblistObj.find(".noChatSearchJob").show();
                }
            });
        });
        //回车事件
        joblistObj.find("#postSearchText").keydown(function (event) {
            if (event.keyCode == 13) {
                joblistObj.find(".searchSubmit").click();
            }
        });
        jobListDialog.show();*/
    };
    
    /**下载简历**/
    var chatDownLoadResume = function(resume_id,from,obj,job_id,person_id,$this){
        var checkDownloadUrl = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/download/setmealChat/";
        var data = {resume_id:resume_id,get_alert:true,person_id:person_id};
		// var $run=$this.running();
        $.getJSON(checkDownloadUrl,data,function(result){
			// $run.close();
            if (result.code){
                if (result.code == 200){
                    //selectJob(resume_id,job_id,$this);
                    checkHaveChat(resume_id,job_id,$this);
                }else{
                    ConfirmBox.timeBomb(result.msg, {
                        name : "fail",
                        timeout : 2000,
                        width: 350
                    });
                }
            }else {
                ConfirmBox.timeBomb('网络异常，请稍后！', {
                    name : "warning",
                    timeout : 2000,
                    width: 350
                });
            }
        });
        // window.location.reload();//页面重新加载
    };
    
    /**余额不足弹出**/
    function showChatNoPrice(noPriceHtml){ //余额不足弹出
        var chatNoPriceDialog = new Dialog({
                idName: 'chat_noprice_dialog',
                title: '余额不足',
                content: noPriceHtml,
                close: '╳',
                width: 510
        });
        chatNoPriceDialog.show();
    };
    
    /**
     *@desc 获取职位选择弹窗 
     */
    function getJobListHtml(from_job_id, msg){
        var job_list = [];
        var start_html = '<div class="postSearchPop chatPostSearchJob">';
            if(typeof(from_job_id) !='undefined' && from_job_id !=''){
                for(var i=0;i<chat_job_list.length;i++){
                    if(chat_job_list[i].job_id == from_job_id){
                        job_list.unshift({'job_id':chat_job_list[i].job_id,'station':chat_job_list[i].station});
                    }else{
                        job_list.push({'job_id':chat_job_list[i].job_id,'station':chat_job_list[i].station});
                    }
                }
            }else{
                job_list = chat_job_list;
            }
            
            if(job_list.length > 0){
		//重新排序
                if (msg) {
                    start_html = start_html +'<div style="height: 65px;overflow: auto;color: red;">'+ msg +'，沟通前请确认是否重复联系</div>';
                }
                start_html = start_html	+'<div class="postSearchx">'
                +'<input type="text" name="postSearchText" id="postSearchText" placeholder="搜索职位" value="" />'
                +'<a href="javascript:;" class="postSearchx searchSubmit"></a>'
                +'<a href="javascript:;" class="postSearchx searchjobloading" style="background:none;display:none"><img style="margin:7px" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/common/loading.gif"/></a>'
                +'</div>';
            }
                if(job_list.length > 0){
                    start_html = start_html+ '<div class="noPostx noChatSearchJob" style=display:none>'
				 + '<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/chat/chat_icon18.png" />'
				 + '<span>没有找到“<b class="chatSearchKeyWord"></b>”相关职位<br />请更换关键词重新搜索</span>'
                                + '</div>';
                }else{
                    start_html = start_html+ '<div class="noPostx">'
                                + '<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/chat/chat_icon18.png" />'
                                + '<span>当前没有在招职位，不能与求职者进行聊天</br>请先去<a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/partjob/addjobselect/">发布职位</a>吧</span>'
                               + '</div>';
                }
                
		if(job_list.length >0){
                        start_html += '<ul class="postSchList chatJobSearchList">';
                        for(var j=0;j<job_list.length;j++){
                            var _pre_html = "";
                            if(job_list[j].job_id==from_job_id){
                                _pre_html = '<em class="chatHasApply">已投</em>';
                            }
                            start_html += '<li><span>'+_pre_html+job_list[j].station+'</span><a href="javascript:void(0);" data-job-id="'+job_list[j].job_id+'" class="gotoChat">聊一聊</a></li>';
                        }
                    start_html += '</ul>';
                }
		start_html += '</div>';
            return start_html;
    }
    
    /**获取没钱后的弹窗**/
    function getNoPriceHtml(price,account){
        var html ='<div style="width:510px;">'
                    +'<dl id="yaoqing-alert" class="yaoqing-alert clearfix">'
                            +'<dt><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/cjl.jpg" /></dt>'
                            +'<dd>'
                                    +'<p class="t1">哎呀，余额不足了！</p>'
                                    +'<p class="t2">当前帐号余额：<span>￥'+account+'</span><br />简历需花费：'
                                   +'<span>￥'+price+'</span></p>'
                                    +'<p><a href="<?php echo smarty_function_get_url(array('rule'=>"/pay/"),$_smarty_tpl);?>
" target="_blank" class="btn1 btnsF14 close">立即充值</a><a href="<?php echo smarty_function_get_url(array('rule'=>"/index/memberdetail/"),$_smarty_tpl);?>
" class="link2" target="_blank">成为会员，下载简历低至2元</a></p>'
                            +'</dd>'
                   +'</dl>'
                +'</div>';
        return html;
    };
    /**获取绑定手机html**/
    function getBindHtml(){
         var html = '<div class="boundPhonePop" id="chatBindPhoen">'
                       + '<span class="boundx01">绑定手机号后，才能使用聊一聊功能与求职者进行在线沟通。</span>'
                        +'<span class="boundx02">注：手机号不会展示给求职者。</span>'
                        +'<div class="boundFrom">'
                                +'<input type="text" name="boundPhone" id="boundPhone" class="putBound" value="" placeholder="请输入手机号" />'
                                +'<div class="noteCodex">'
                                    +'<input type="text" name="boundImgCode" id="boundImgCode" class="putBound" style="width:158px" value="" placeholder="图片验证码" />'
                                    +'<img id="chatImgCode" src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat/Verify/seed-'+seed+'">'
                                +'</div>'
                               +'<div class="noteCodex">'
                                    +'<input type="text" name="boundNoteCode" id="boundNoteCode" class="putBound" style="width:158px" value="" placeholder="短信验证码" />'
                                    +'<span class="boundGetCode getPhoneCode" >获取验证码</span>'
                                    +'<span class="boundGetCode chatloading" style="display:none"><img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/common/loading.gif"/>发送中...</span>'
                                    +'<span class="boundGetCode cutDown" style="display:none"><i>60</i>秒后重新获</span>'
                                +'</div>'
                                +'<a href="javascript:void(0);" class="boundBtn bindSubmit">立即绑定</a>'
                        +'</div>'
		+'</div>';
        return html;
        
    }
    var chat_qq_news_setInterval;
   /* $('.qq_newsx').on('click', function(){
        if(chat_qq_news_setInterval){
            clearInterval(chat_qq_news_setInterval);
            var old_background_image = $('.qq_newsx').css('backgroundImage');
            $('.qq_newsx').css({backgroundImage:old_background_image});
        }

    });*/
    //显示未读数量
    function showUnRead(){
        var _show = chatTotalMsg > 99 ? "99+" : chatTotalMsg;
        if(chatTotalMsg > 0){
            $("#MyHbChat .chatNewMsg").html(_show).show();
            var old_background_image = $('.qq_newsx').css('backgroundImage');
            var new_background_image = "url(<?php echo base_lib_Constant::STYLE_URL;?>
/img/company/chat/chat_icon_new2.png)";
            var flag = true;
            chat_qq_news_setInterval = setInterval(function(){
                if (flag) {
                    $('.qq_newsx').css({backgroundImage:new_background_image});
                    flag = false;
                } else {
                    $('.qq_newsx').css({backgroundImage:old_background_image});
                    flag = true;
                }
            }, 500);

        }
        var hasCloseChatMsgCount = cookie.get("hasCloseChatMsgCount");
        if(chatTotalMsg > 0 &&  !hasCloseChatMsgCount){
            $("#MyHbChat .chatMsgCount").html(chatTotalMsg+"条");
            $("#MyHbChat .qq_newsz").show();
            
        }
    }
    
    //隐藏未读消息
    function hideUnRead(){
         $("#MyHbChat .qq_newsz").hide();
         $("#MyHbChat .chatNewMsg").html("").hide();
        if(chat_qq_news_setInterval){
            clearInterval(chat_qq_news_setInterval);
            var old_background_image ="url(<?php echo base_lib_Constant::STYLE_URL;?>
/img/company/chat/chat_icon_new12.png)";
            $('.qq_newsx').css({backgroundImage:old_background_image});
        }
    }
    
    //关闭弹窗
    $("#MyHbChat .closeChatTip").on("click",function(){
        $("#MyHbChat .qq_newsz").hide();
        //添加cookie
        var tomorrow = new Date("<?php echo date('Y/m/d 00:00:00',strtotime('+1 day'));?>
");
        cookie.set('hasCloseChatMsgCount', 'true',{'expires':tomorrow});
    });


    function chatGetCookie(name) {
        var cookieValue = '';
        var search = name + '=';
        if (document.cookie.length > 0) {
            var offset = document.cookie.indexOf(search)
            if (offset != -1) {
                offset += search.length;
                var end = document.cookie.indexOf(';', offset);
                if (end == -1) end = document.cookie.length;
                cookieValue = unescape(document.cookie.substring(offset, end));
            }
        }
        return cookieValue;
    }

    if (chatLoginStatus != "true") {
        //如果不需要绑定 登录 IM
        <?php if ($_smarty_tpl->getVariable('can_chat')->value){?>
        //登录IM
        var popNotice = function(del_img,user_name,text_str) {
            if (Notification.permission == "granted") {
                var notification = new Notification(user_name, {
                    body: text_str,
                    icon: del_img,
                    vibrate:[200, 100, 200],
                    tag:'1',
                    renotify:true
                });
                //setInterval(notification.close(),3000);
                notification.onclick = function() {
                    window.open("<?php echo $_smarty_tpl->getVariable('siteurl')->value['company'];?>
/chat","_blank");
                    notification.close();
                };
            }
        };

        var nim = SDK.NIM.getInstance({
            //debug: true,
            appKey: "<?php echo $_smarty_tpl->getVariable('swy_info')->value['appkey'];?>
",
            account: "<?php echo $_smarty_tpl->getVariable('swy_info')->value['accid'];?>
",
            token: "<?php echo $_smarty_tpl->getVariable('swy_info')->value['token'];?>
",
            db: true,
            // privateConf: {}, // 私有化部署方案所需的配置
            // 收到消息事件触发
            onmsg: function (msg) {
                 //console.log('onmsg1',msg);
                if(msg.scene=="p2p"){
                    if('in'==msg.flow){
                        //去除快米用户
                        if(msg.from.indexOf("pb") != -1){
                            return;
                        }
                        chatTotalMsg++;
                        showUnRead();
                        if (window.Notification) {
                            var user_name = '';
                            var text_str = '';
                            if('text'==msg.type){
                                text_str = msg.text;
                            }
                            if('image'==msg.type){
                                text_str ="[图片]";
                            }
                            if('audio'==msg.type){
                                text_str = "[语音]";
                            }
                            var del_img = "<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/user_img.png";
                            if(msg.hasOwnProperty('custom')&&msg.custom!=''){
                                var tempContentJson = JSON.parse(msg.custom);
                                if(tempContentJson.hasOwnProperty('userInfoFirst')){
                                    del_img = tempContentJson.userInfoFirst.url;
                                    if(tempContentJson.userInfoFirst.name){
                                        user_name = tempContentJson.userInfoFirst.name;
                                    }
                                }
                            }
                            if (Notification.permission == "granted") {
                                popNotice(del_img,user_name,text_str);
                            } else if (Notification.permission != "denied") {
                                Notification.requestPermission(function (permission) {
                                    if(permission === "granted"){
                                        popNotice(del_img,user_name,text_str);
                                    }else{
                                        alert('浏览器Notification已关闭,你有一条新消息');
                                    }
                                });
                            }else{
                                alert('浏览器Notification已关闭,你有一条新消息');
                            }
                        } else {
                            alert('浏览器不支持Notification,你有一条新消息');
                        }
                    }
                }
            },
            onconnect: onConnect,
            onsyncdone: onSyncDone,
            syncSessionUnread :true,
            syncMsgReceipts:true,
            //忽略某条通知类消息
            shouldIgnoreNotification:function () {
                return true;
            }
        });


        function onConnect() {
            //console.log('nim 连接成功');
        }

        function onSyncDone() {
            nim.getLocalSessions({
                limit: 100,
                done: getLocalSessionsDone
            });
        }
        //连接成功后的获取会话数
        function getLocalSessionsDone(error, obj) {
            if (!error) {
                var temp_session = obj.sessions;
                var objSessions = new Array();
                //去除快米用户
                //console.log('初始化会话数据', temp_session);
                for (var i = 0; i < temp_session.length; i++) {
                    var temp_session_id = temp_session[i].id;
                    if (temp_session_id.indexOf("pb") == -1) {
                        objSessions.push(temp_session[i]);
                    }
                }
                //console.log('初始化会话数据', objSessions);
                for (var i = 0; i < objSessions.length; i++) {
                    if(objSessions[i].scene=="p2p"){
                        chatTotalMsg += objSessions[i].unread
                    }
                }
                showUnRead();
            }
        }
        <?php }?>
     }

});
/**
 *@desc 弹出简历下载地区限制
 */
function chatAreaLimit(){
    dialog_msg.show();
}
</script>


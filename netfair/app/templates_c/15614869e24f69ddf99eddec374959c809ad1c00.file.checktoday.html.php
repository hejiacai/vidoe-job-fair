<?php /* Smarty version Smarty-3.0.7, created on 2020-03-23 14:32:40
         compiled from "app\templates\./videohall/checktoday.html" */ ?>
<?php /*%%SmartyHeaderCode:319515e785808798c22-21080736%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '15614869e24f69ddf99eddec374959c809ad1c00' => 
    array (
      0 => 'app\\templates\\./videohall/checktoday.html',
      1 => 1584945152,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '319515e785808798c22-21080736',
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
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="Keywords" content="" />
        <meta name="Description" content="" />
        <title>求职者大厅</title>
        <!–[if lt IE9]>
        <script src="<?php echo smarty_function_version(array('file'=>'html5.js'),$_smarty_tpl);?>
"></script>
        <![endif]–>
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'base.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'comback.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'video_eng.css'),$_smarty_tpl);?>
" />
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'m_font_style.css'),$_smarty_tpl);?>
">
        <link rel="stylesheet" type="text/css" href="<?php echo smarty_function_version(array('file'=>'v2-widge.css'),$_smarty_tpl);?>
">

		<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'jquery-1.8.3.min.js'),$_smarty_tpl);?>
"></script>
        
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
        <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'confirmBox.js'),$_smarty_tpl);?>
"></script>
        <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'dialog.js'),$_smarty_tpl);?>
"></script>
        <script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'WdatePicker.js'),$_smarty_tpl);?>
"></script>
        
	</head>
	<body>
		
        <?php $_template = new Smarty_Internal_Template('videohall/hallheadnew.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('par',$_smarty_tpl->getVariable('par')->value); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
		
        <?php if ($_smarty_tpl->getVariable('souece')->value==1){?>
		<div class="interDetection">
			
			<div class="interDetection500">
			<h2>为保障您的面试效果，请先按步骤完成电脑设置</h2>
			<ul>
				<li>
					<em class="interNumber">1</em>
					<span class="interTit01">
						安装谷歌浏览器<a href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/down/chrome.exe">下载</a>
					</span>
					<div class="interFacility">
						<span>
							<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon19.png" >
							<em>浏览器检测</em>
							<b class="browserDevice <?php if (!$_smarty_tpl->getVariable('browser_not_ok')->value){?>sure<?php }?>"></b>
						</span>
					</div>
				</li>
				<li>
					<em class="interNumber">2</em>
					<span class="interTit01">
						面试时准备好设备
					</span>
					<div class="interFacility">
						<span>
							<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon18.png" >
							<em>耳机</em>
						</span>
						<span>
							<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon16.png" >
							<em>摄像头</em>
						</span>
					</div>
				</li>
				<li>
					<em class="interNumber">3</em>
					<span class="interTit01">
						放大麦克风
					</span>
					<span class="procedure">操作流程</span>
				</li>
				<li>
					<em class="interNumber">4</em>
					<span class="interTit01">
						设置面试时间<a href="javascript:;" class="setInterviewTime">设置面试时间</a><?php if (!empty($_smarty_tpl->getVariable('list',null,true,false)->value)){?><a href="javascript:void(0);" class="red">已设置</a><?php }?>
					</span>
					<span class="interTit02">学生在设置的面试时间内申请视频面试</span>
				</li>
			</ul>
			
			<div class="interVideoPopBtn">
				<a href="javascript:;" id="complete_btn">完成</a>
			</div>
			</div>
		</div>
        <?php }else{ ?>
        <div class="interDetection interDetection_km">
			<div class="interDetection500 interDetection_km500">
				<h2 class="red">为保障您的面试效果，请先按步骤完成电脑设置</h2>
				<ul>
					<li>
						<em class="stepNum fwBold">第一步 : </em>
						<span class="fwBold">
							安装谷歌浏览器<a class="ahref mar-lef" href="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/down/chrome.exe">下载</a>
						</span>
						<div class="interFacility">
							<span>
								<img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon19.png" >
								<em>浏览器检测</em>
								<b class="browserDevice <?php if (!$_smarty_tpl->getVariable('browser_not_ok')->value){?>sure<?php }?>"></b>
							</span>
						</div>
					</li>
					<li>
						<em class="stepNum fwBold">第二步 : </em>
						<span class="fwBold">
							下载快米app才能<span class="red">视频面试</span><a href="javascript:void(0);" class="ahref mar-lef" id="showkm_RQbtn">下载</a>
						</span>
					</li>
					<li>
						<em class="stepNum fwBold">第三步 : </em>
						<span class="fwBold">
							设置面试时间<a href="javascript:;" class="setInterviewTime ahref">设置面试时间</a><?php if (!empty($_smarty_tpl->getVariable('list',null,true,false)->value)){?><a href="javascript:void(0);" class="grayBtn">已设置</a><?php }?>
						</span>
						<span class="interTit02">学生在设置的面试时间内申请视频面试</span>
					</li>
				</ul>
				<div class="interVideoPopBtn">
					<a href="javascript:;" id="complete_btn">完成</a>
				</div>
			</div>
		</div>
		<?php }?>
        <div class="procedurePop" style="display:none;">
            <img src="<?php echo $_smarty_tpl->getVariable('siteurl')->value['style'];?>
/img/company/video/eng_icon22.jpg" >
        </div>
        
        <script type="text/javascript" language="javascript"  src='<?php echo smarty_function_version(array('file'=>"aliyun-webrtc-sdk-1.9.0.min.js"),$_smarty_tpl);?>
'></script>
        <script type="text/javascript" language="javascript">
(function() {
	var packageName = 'brw';
	if(!window[packageName]) {
		window[packageName] = {};
		window[packageName]["browserInfo"] = {};
	}
	var MAX_360_CHROME_VERSION = 69; //以360极速浏览器的最大内核版本为准
	function getIOSVersion(ua) {
		if(/cpu (?:iphone )?os (\d+_\d+)/.test(ua)) {
			return parseFloat(RegExp.$1.replace("_", "."));
		} else {
			return 2;
		}
	}
 
	function _mime(where, value, name, nameReg) {
		var mimeTypes = window.navigator.mimeTypes,
			i;
 
		for(i in mimeTypes) {
			if(mimeTypes[i][where] == value) {
				if(name !== undefined && nameReg.test(mimeTypes[i][name])) return !0;
				else if(name === undefined) return !0;
			}
		}
		return !1;
	}
	var browser360 = {
		result: "Chrome",
		details: {
			Chrome: 5,
			Chromium: 0,
			_360SE: 0,
			_360EE: 0
		},
		sorted: ["Chrome", "360SE", "360EE", "Chromium"],
		check: function() {
			var init = {
				Chrome: 5,
				Chromium: 0,
				_360SE: 0,
				_360EE: 0
			};
 
			var plugins = window.navigator.plugins;
 
			var webstore = window.chrome.webstore;
			var webstoreLen = Object.keys(webstore).length;
			var pluginsLen = plugins.length;
 
			if(window.clientInformation.languages ||
				(init._360SE += 8), /zh/i.test(navigator.language) &&
				(init._360SE += 3, init._360EE += 3), window.clientInformation.languages) {
				var lanLen = window.clientInformation.languages.length;
				if(lanLen >= 3) {
					(init.Chrome += 10, init.Chromium += 6);
				} else if(2 == lanLen) {
					init.Chrome += 3, init.Chromium += 6, init._360EE += 6;
				} else if(1 == lanLen) {
					init.Chrome += 4, init.Chromium += 4;
				}
 
			}
			var pluginFrom,
				maybe360 = 0;
			for(var r in plugins) {
				if(pluginFrom = /^(.+) PDF Viewer$/.exec(plugins[r].name)) {
					if("Chrome" == pluginFrom[1]) {
						init.Chrome += 6,
							init._360SE += 6,
							maybe360 = 1;
 
					} else if("Chromium" == pluginFrom[1]) {
						init.Chromium += 10,
							init._360EE += 6,
							maybe360 = 1;
 
					}
				} else if("np-mswmp.dll" == plugins[r].filename) {
					init._360SE += 20, init._360EE += 20;
				}
			}
 
			maybe360 || (init.Chromium += 9);
			if(webstoreLen <= 1) {
				init._360SE += 7;
			} else {
				init._360SE += 4;
				init.Chromium += 3;
				if(pluginsLen >= 30) {
					init._360EE += 7, init._360SE += 7, init.Chrome += 7;
				} else if(pluginsLen < 30 && pluginsLen > 10) {
					init._360EE += 3, init._360SE += 3, init.Chrome += 3;
				} else {
					init.Chromium += 6;
				}
			}
 
			var m = new Object();
			m.Chrome = init.Chrome,
				m.Chromium = init.Chromium,
				m["360SE"] = init._360SE,
				m["360EE"] = init._360EE;
			var s = [];
			for(var u in m) {
				s.push([u, m[u]]);
			}
			s.sort(function(e, i) {
				return i[1] - e[1]
			});
			this.sorted = s;
			this.details = init;
			this.result = s[0][0] || '';
 
			return this.result.toLowerCase();
		}
 
	};
	/**
	 * 获取国内加壳浏览器类型
	 */
	function _getShellerType() {
		var brwType = "",
			appVersion = window.navigator.appVersion,
			external = window.external;
 
		if(external && 'SEVersion' in external) { // 搜狗浏览器
			brwType = 'sougou';
		} else if(external && 'LiebaoGetVersion' in external) { // 猎豹浏览器
			brwType = 'liebao';
		} else if(/QQBrowser/.test(appVersion)) { //qq浏览器
			brwType = 'qq';
		} else if(/Maxthon/.test(appVersion)) { //遨游浏览器
			brwType = 'maxthon';
		} else if(/TaoBrowser/.test(appVersion)) { //淘宝浏览器
			brwType = 'taobao';
		} else if(/BIDUBrowser/.test(appVersion)) { //百度浏览器
			brwType = 'baidu';
		} else if(/UBrowser/.test(appVersion)) { //UC浏览器
			brwType = 'uc';
		}
		return brwType;
	}
	/**
	 * 获取 Chromium 内核浏览器类型
	 * @link http://www.adtchrome.com/js/help.js
	 * @link https://ext.chrome.360.cn/webstore
	 * @link https://ext.se.360.cn
	 * @return {String}
	 *         360ee 360极速浏览器
	 *         360se 360安全浏览器
	 *         sougou 搜狗浏览器
	 *         liebao 猎豹浏览器
	 *         chrome 谷歌浏览器
	 *         ''    无法判断
	 */
 
	function _getChromiumType(version) {
		if(window.scrollMaxX !== undefined) return '';
 
		var doc = document;
		var _track = 'track' in doc.createElement('track');
		var chromeBrowserType = _getShellerType();
		if(chromeBrowserType != "") {
			return chromeBrowserType;
		}
		if(window.navigator.vendor && window.navigator.vendor.indexOf('Opera') == 0) { //opera
			return 'opera';
		}
		var p = navigator.platform.toLowerCase();
		if(p.indexOf('mac') == 0 || p.indexOf('linux') == 0) {
			return 'chrome';
		}
		if(parseInt(version) > MAX_360_CHROME_VERSION) {
			return 'chrome';
		}
		return browser360.check();
	}
	var client = function() {
		var browser = {};
		var ua = navigator.userAgent.toLowerCase();
		console.log(ua)
		var s;
		if(s = ua.match(/rv:([\d.]+)/)) {
			browser.name = 'ie';
			browser['ie'] = s[1];
			if(ua.indexOf("wow") > -1) {
				browser['type'] = _getShellerType() ? _getShellerType() : "360";
			} else {
				browser['type'] = "IE";
			}
		} else if(s = ua.match(/msie ([\d.]+)/)) {
			browser.name = 'ie';
			browser['ie'] = s[1];
			if(ua.indexOf("wow") > -1) {
				browser['type'] = _getShellerType() ? _getShellerType() : "360";
			} else {
				browser['type'] = "IE";
			}
		} else if(s = ua.match(/edge\/([\d.]+)/)) {
			browser.name = 'edge';
			browser['edge'] = s[1];
			browser['type'] = "edge";
		} else if(s = ua.match(/firefox\/([\d.]+)/)) {
			browser.name = 'firefox';
			browser['firefox'] = s[1];
			browser['type'] = "firefox";
		} else if(s = ua.match(/chrome\/([\d.]+)/)) {
			browser.name = 'chrome';
			browser['chrome'] = s[1];
			var type = _getChromiumType(browser['chrome']);
			if(type) {
				browser['chrome'] += '(' + type + ')';
				browser['type'] = type;
			}
		} else if(s = ua.match(/opera.([\d.]+)/)) {
			browser.name = 'opera';
			browser['opera'] = s[1];
			browser['type'] = "opera";
		} else if(s = ua.match(/version\/([\d.]+).*safari/)) {
			browser.name = 'safari';
			browser['safari'] = s[1];
			browser['type'] = "safari";
		} else {
			browser.name = 'unknown';
			browser['unknow'] = 0;
		}
 
		var system = {};
 
		//detect platform
		//        var p = navigator.platform.toLowerCase();
		if(ua.indexOf('iphone') > -1) {
			system.name = 'iphone';
			system.iphone = getIOSVersion(ua);
		} else if(ua.indexOf('ipod') > -1) {
			system.name = 'ipod';
			system.ipod = getIOSVersion(ua);
		} else if(ua.indexOf('ipad') > -1) {
			system.name = 'ipad';
			system.ipad = getIOSVersion(ua);
		} else if(ua.indexOf('nokia') > -1) {
			system.name = 'nokia';
			system.nokia = true;
		} else if(/android (\d+\.\d+)/.test(ua)) {
			system.name = 'android';
			system.android = parseFloat(RegExp.$1);
		} else if(ua.indexOf("win") > -1) {
			system.name = 'win';
 
			if(/win(?:dows )?([^do]{2})\s?(\d+\.\d+)?/.test(ua)) {
				if(RegExp["$1"] == "nt") {
					switch(RegExp["$2"]) {
						case "5.0":
							system.win = "2000";
							break;
						case "5.1":
							system.win = "XP";
							break;
						case "6.0":
							system.win = "Vista";
							break;
						case "6.1":
							system.win = "7";
							break;
						case "6.2":
							system.win = "8";
							break;
						case "6.3":
							system.win = "8.1";
							break;
						case '10.0':
							system.win = '10';
							break;
						default:
							system.win = "NT";
							break;
					}
				} else if(RegExp["$1"] == "9x") {
					system.win = "ME";
				} else {
					system.win = RegExp["$1"];
				}
			}
 
		} else if(ua.indexOf("mac") > -1) {
			system.name = 'mac';
		} else if(ua.indexOf('linux') > -1) {
			system.name = 'linux';
		}
		var str = system.name + (system[system.name] || '') + '|' + browser.name + browser[browser.name];
		var isMobile = system.android || system.iphone || system.ios || system.ipad || system.ipod || system.nokia;
 
		return {
			browser: browser,
			system: system,
			isMobile: isMobile,
			string: str
		};
	}();
	window[packageName]['browserInfo'] = client;
})();
var browser = brw.browserInfo || {};

        var interviewTimeDialog, is_chrome = '<?php echo !$_smarty_tpl->getVariable('browser_not_ok')->value;?>
', has_times = '<?php echo !empty($_smarty_tpl->getVariable('list',null,true,false)->value);?>
';
        hbjs.use('@confirmBox, @jobDialog', function (m) {
            var ConfirmBox = m['widge.overlay.confirmBox'],
                Dialog = m['widge.overlay.hbDialog'],
                cookie = m['tools.cookie'],
                $ = m['jquery'].extend(m['cqjob.jobDialog']);


            var close = '×';
            var width = 600;
            var zIndex = 9999;
            var title = '面试时间设置';
//            if(browser.browser.type.toLowerCase() != 'chrome'){
//                is_chrome = false;
//            }
            interviewTimeDialog = new Dialog({
                close: close,
                idName: 'informTraining_dialog',
                title: title,
                width: width,
                zIndex: zIndex
            });
            var checkVideoDialog = new Dialog({
                close: close,
                idName: 'checkVideo_dialog',
                title: '电脑麦克风设置流程',
                width: width,
                zIndex: zIndex,
                content:$(".procedurePop").html()
            });
			
			
//            function _mime() {
//                var mimeTypes = navigator.mimeTypes;
//                for (var mt in mimeTypes) {
//                    if (mimeTypes[mt]["type"] == "application/vnd.chromium.remoting-viewer") {
//                        return true;
//                    }
//                }
//                return false;
//            }

            $('#complete_btn').on('click', function(){
                var msg = '';
                if(!has_times && !is_chrome)
                    msg = '请使用新版本谷歌浏览器并设置面试时间.';
                else if(!has_times)
                    msg = '请设置面试时间.';
                else if(!is_chrome)
                    msg = '请使用新版本谷歌浏览器.';
                    
                if(msg != ''){
                   ConfirmBox.timeBomb(msg,{
                                name: 'fail',
                                width:'auto',
                                timeout : 4000
                    });
                    return; 
                }
                
                var expires_time = new Date("<?php echo date('Y/m/d 00:00:00',strtotime('+1 day'));?>
");
                cookie.set('school_videohall_equipment_check_today_<?php echo $_smarty_tpl->getVariable('sid')->value;?>
',1,{expires:expires_time,path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
                window.location.reload();
//                AliRtcEngine.isSupport().then(re => {
//                    checkVideo(re, true, msg);
//                }).catch(err => {
//                    checkVideo(err, true, msg);
//                });    
            });
            
            $('.procedure').on('click', function(){
                checkVideoDialog.show();
            });
            $('.setInterviewTime').on('click', function(){
                interviewTimeDialog.setContent('<?php echo smarty_function_get_url(array('rule'=>"/videohall/InterviewTimeList"),$_smarty_tpl);?>
?sid=<?php echo $_smarty_tpl->getVariable('sid')->value;?>
').show(); 
            });
            $(".informTraining_dialog .ui_dialog_close").click(function(){
                window.location.reload();
            });

//            AliRtcEngine.isSupport().then(re => {
//                checkVideo(re);
//            }).catch(err => {
//                checkVideo(err);
//            });
            checkVideo();
            function checkVideo(){
                if(is_chrome){
                    if(!$('.browserDevice').hasClass('sure'))
                        $('.browserDevice').addClass('sure');
                }else{
                    is_chrome = false;
                    $('.browserDevice').removeClass('sure');
                }
                
//                if(!res.audioDevice){
//                    $('.audioDevice').removeClass('sure');
//                }else{
//                    $('.audioDevice').addClass('sure');
//                }
//
//                if(!res.videoDevice){
//                    $('.videoDevice').removeClass('sure');
//                }else{
//                    $('.videoDevice').addClass('sure');
//                }

//                if(complete_click){
//                    if(!res.audioDevice && !res.videoDevice){
//                        msg += '请插入音频/视频设备.';
//                    }else if(!res.audioDevice){
//                        msg += '请插入音频设备.';
//                    }else if(!res.videoDevice){
//                        msg += '请插入视频设备.';
//                    }else if(!res.isSupported){
//                        msg += '设备初始化失败.';
//                    }
//                    if(msg != ''){
//                        ConfirmBox.timeBomb(msg,{
//                                    name: 'fail',
//                                    width:'auto',
//                                    timeout : 4000
//                        });
//                        return;
//                    }
//                    var expires_time = new Date("<?php echo date('Y/m/d 00:00:00',strtotime('+1 day'));?>
");
//                    cookie.set('school_videohall_equipment_check_today',1,{expires:expires_time,path:'/',domain:"<?php echo base_lib_Constant::COOKIE_DOMAIN;?>
"});
//                    window.location.reload();
//                }
                
            }

        });
		</script>
		
	</body>
</html>

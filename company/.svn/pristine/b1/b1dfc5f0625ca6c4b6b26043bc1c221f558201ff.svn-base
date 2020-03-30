<?php
define('ERROR', 404);//请求失败代码
define('SUCCESS', 200);//请求成功代码
class components_cbasepage extends base_components_basepage {
	
	public  $_userid        = '';
	public  $_username      = '';
	public  $_usertype      = '';
	public  $_location_area = "0300";
	private $session_server = null;
	private $is_account_down = false;
	public  $is_gray_company = false;

	//参与灰度测试的企业名单
    private $gray_companys = [//内部测试企业-本地
        989786, 114913710, //内部测试企业-线上
        114534318, 114964762, 114935038, 114697511,

        //正式环境参与测试的企业
        115151955, 114518555, 115006173, 1549676, 1210416, 1536394, 331628, 115079291, 419942, 114973902, 115115192, 114153210, 115079631, 732570, 115136361, 114153011, 862778, 115114686, 291394, 115079043, 114903268, 904685, 115086230, 1155013, 293578, 114975110, 115120029, 345670, 115068216, 360267, 115087339, 115090418, 115092473, 114453315, 757630, 301432, 115100472, 343928, 300941, 115007702, 1031636, 115052053, 115167725, 114527346, 114135462, 758577, 114698042, 115078354, 114720442, 308032, 307159, 115146082, 308403, 1376282, 114678469, 115182174, 114933360, 548986, 115165034, 114578148, 1514252, 381127, 567302, 114582112, 114485378, 1088760, 306230, 114607572, 309494, 114815836, 114921093, 115101231, 387617, 115167113, 1415487, 115010937, 306358, 1459338, 289136, 372929, 637131, 729909, 115118865, 632723, 697111, 114499656, 307113, 637405, 630949, 115119294, 290353, 1305970, 309475, 395142, 725107, 365536, 115167095, 114991287, 579202, 323694, 397066, 502634, 1152924, 114581348, 593346, 114957234, 115022649, 115164246, 115082468, 114137624, 1474599, 319811, 597050, 115060293, 115166646, 114589359, 724031, 115016518, 114954335, 114559854, 114540938, 639716, 114527615, 1251450, 115176015, 841712, 114957020, 114106806, 115079198, 114147134, 114139767, 115168703, 1416656, 115167387, 365086, 729444, 325079, 114439778, 1565394, 115092858, 292371, 114691728, 114531283, 717795, 114989131, 290054, 114449272, 115148384, 115082909, 115081789, 115093169, 479820, 114138758, 485763, 1469350, 713814, 1099781, 114133897, 311859, 114708287, 1287208, 115003132, 114545055, 115009016, 290744, 332150, 114537715, 895303, 115036622, 115171062, 114992892, 115065999, 114074654, 1004180, 292312, 522281, 114083532, 114551609, 115059620, 296685, 115124895, 115132885, 114993947, 591242, 114965269, 289496, 114993839, 114985626, 483961, 448499, 311314, 386071, 115092613, 114658500, 311416, 115080062, 306388, 114454092, 114452980, 115078204, 114095122, 115148377, 114159414, 1066050, 115019464, 306863, 115013508, 115136684, 114088303, 114569043, 115137621, 1539897, 1567354, 350895, 114592377, 1401939, 115052796, 295738, 114683586, 115107233, 114988922, 114510127, 1549882, 114898900, 114957485, 114913256, 1507255, 115134431, 114991418, 307357, 114673601, 115168034, 293964, 115002343, 114907599, 302536, 114155038, 114614824, 633799, 114723311, 114902997, 322895, 115177016, 311072, 1378439, 115019171, 114457285, 315693, 1494748, 115024004, 115086700, 315413, 114453620, 114580437, 289015, 115118744, 114556298, 114711023, 353002, 1457932, 115017917, 470503, 114992469, 297739, 816005, 561395, 300725, 297707, 114955613, 792409, 492063, 115091306, 579845, 115010137, 1222647, 852997, 114994749, 115129828, 1515812, 114983809, 114660994, 1193814, 303453, 115087697, 290312, 549496, 115081225, 114511278, 114721543, 114107074, 348033, 114581470, 115162485, 115114579, 115003397, 115046978, 115180876, 114724335, 114932899, 290812, 114955643, 115078888, 114144345, 115177038, 306449, 115116537, 114532008, 1278015, 115152216, 114513138, 114461344, 574861, 1491151, 297841, 115096202, 114457806, 114497491, 294539, 115019622, 114086812, 114986419, 1498969, 114563502, 115132286, 1466987, 311590, 114164235, 114451354, 114528688, 115171322, 294236, 604061, 605013, 115149141, 114907748, 115169861, 493590, 114094183, 431036, 114985579, 115133912, 464260, 549433, 660055, 114965440, 698802, 115122710, 115123762, 115095439, 115189145, 115014000, 114976566, 309034, 114983547, 114924932, 114517234, 357626, 114077941, 588372, 114712928, 115091642, 526219, 300888, 298302, 115186239, 1557432, 1086088, 115152092, 1415074, 114531314, 1557864, 114443131, 407191, 114663138, 310788, 312126, 115097113, 1065755, 585725, 666227, 115188650, 115030466, 303494, 302934, 115188128, 115055281, 115006282, 1423861, 114447649, 1414630, 720249, 115193208, 114136353, 1444644, 115167471, 115104487, 1568079, 1517062, 115033999, 115182199, 115108734, 115123914, 311953, 114988446, 114918371, 114967677, 115103609, 115096158, 477160, 115179114, 468075, 115089844, 114157800, 114509657, 114946436, 114576964, 1499611, 115188817, 115162310, 114674701, 114517433, 115177585, 114986250, 115009874, 564128, 337904, 311120, 115018725, 520905, 114656721, 622163, 114552003, 115181413, 114488406, 114513423, 1415872, 288982, 115190375, 114652227, 114541502, 114632577, 115152155, 385465, 115068272, 396905, 114133235, 1503095, 115173456, 327867, 115151236, 381821, 1415990, 1315279, 115008906, 114490377, 115100685, 114523790, 115132205, 114491479, 313380, 114989589, 115147466, 115096599, 114952877, 114563952, 114919853, 473848, 114513225, 311367, 596697, 115162610, 114577046, 114437984, 115153005, 114581575, 308501, 114501281, 115147099, 115175646, 996479, 294722, 114550667, 298717, 303345, 114167863, 114489488, 770484, 115068250, 114148579, 115165503, 1007348, 115111480, 1358201, 114561080, 114139980, 799249, 291159, 115108979, 114956325, 114538721, 1497579, 381011, 1288115, 307774, 114713964, 114933461, 946013, 306905, 115096938, 115099715, 115180131, 611073, 1160161, 1440642, 567176, 114964647, 1273652, 114151189, 114926415, 304754, 483522, 386154, 1111719, 115188009, 705914, 115060240, 1558711, 114926375, 115160310, 114148910, 322094, 115012625, 115066982, 310957, 115018474, 1341416, 114992292, 114936805, 1505200, 115093192, 115130431, 1108516, 115036240, 304093, 318065, 319002, 1458905, 114613131, 114670093, 114517465, 298107, 296033, 441749, 458662, 1565702, 356455, 114569145, 114145962, 115003785, 301208, 114964966, 114567860, 114091965, 115151076, 115068263, 325816, 1442769, 115115614, 115116723, 115130852, 311752, 316294, 114552338, 114095785, 784171, 114082403, 411147, 461831, 288959, 115121020, 311342, 114547931, 114980045, 114946044, 298656, 1283222, 114134893, 291735, 759595, 1299432, 1568407, 806132, 114509702, 114444495, 114457053, 114527273, 115095769, 1456690, 299935, 1539646, 115132658, 290252, 1083274, 115188199, 115136080, 397329, 115169650, 115085433, 114530850, 114491318, 114582507, 1525835, 115166308, 1020839, 420720, 114095825, 114984225, 114529470, 115024965, 1531902, 114080392, 295801, 312987, 1461337, 115149147, 738556, 375156, 115176140, 986492, 289571, 114708677, 305754, 114506287, 114561045, 115067922, 114499887, 114979912, 114640735, 114513614, 566468, 114988310, 1441198, 114613215, 114997016, 114636367, 114998205, 115009081, 115110497, 114144775, 115079113, 417052, 115085606, 114946358, 114994715, 115016393, 1464041, 289150, 114600832, 114107563, 428249, 115181415, 114439843, 115086537, 114571690, 114681789, 369901, 114579258, 114150609, 289371, 114444037, 115137849, 1496743, 1499061, 115001174, 114445713, 115132802, 114660660, 115103925, 493800, 115068259, 1037853, 114661877, 114503291, 1506931, 312801, 115007950, 750749, 114569464, 115120044, 663358, 115177845, 115122548, 311791, 447835, 115163278, 418296, 115133520, 1546746, 115145187, 114655158, 1504554, 115152759, 428559, 1484035, 1449094, 115165238, 114513469, 612791, 115067244, 794261, 115127599, 114488239, 1552003, 115086335, 358911, 114092101, 114135715, 115168575, 114593581, 115190574, 114590285, 115020900, 531268, 115104651, 542646, 115115043, 609187, 633455, 558726, 114667833, 383260, 1252480, 115087061, 628167, 114992164, 114586287, 602356, 648239, 731147, 114449797, 115191376, 1090572, 1303549, 115004889, 1032108, 115080706, 114898863, 114640836, 306417, 1375607, 115110531, 114517239, 818913, 114701141, 302398, 115137771, 989392, 114978236, 300978, 385367, 1067868, 115129052, 114571268, 1527861, 114988707, 350613, 114980406, 114555011, 115068297, 115187417, 114723842, 115119046, 114898782, 115179536, 115097101, 114540412, 303585, 354237, 599935, 114547946, 114696758, 114533429, 115193286, 306443, 300901, 294151, 114955099, 114942398, 114540600, 114531021, 114916701, 114443977, 114154408, 115114716, 115171243, 115113968, 1096935, 368029, 115125678, 115124266, 115068350, 1445525, 115134635, 308875, 114103180, 115157572, 115086704, 114711337, 296570, 114978839, 115130681, 1093210, 115079693, 115174330, 114143102, 295181, 502982, 114686730, 115152078, 312029, 295080, 115168422, 114632004, 114546739, 114590418, 114627814, 114561457, 114660209, 115141318, 114928875, 722121, 292248, 115167718, 1440785, 371797, 646197, 1504603, 1569309, 114974820, 311003, 315665, 115013993, 114531941, 114451309, 114922097, 115127271, 114955642, 114505202, 115092638, 114545622, 317838, 115041794, 115189577, 114456251, 115099524, 115185430, 115167363, 115167791, 115165267, 1458716, 541891, 115068302, 306152, 115147638, 115010645, 354667, 647119, 1372222, 114722426, 114640892, 115068219, 324105, 860370, 303890, 115128220, 114168441, 115068274, 115127634, 114988026, 301388, 114897914, 313151, 114507423, 321990, 623085, 114919419, 323617, 114971811, 114098289, 114159816, 114530194, 114916225, 991418, 115137854, 992137, 114585920, 115193928, 114638835, 291346, 114935867, 115068410, 114159283, 114107168, 305276, 114991213, 115111300, 115180900, 115193584, 308361, 114157804, 1507108, 115068347, 115162593, 115179669, 1557785, 114624947, 115128792, 114526878, 115086044, 115096498, 114907152, 114529926, 114900210, 114577877, 1497714, 294646, 115088640, 1477609, 308933, 114681088, 834637, 114947433, 114992015, 570212, 114931311, 648413, 114993743, 115125001, 115187160, 114086757, 445715, 114083118, 114506653, 347110, 292333, 114481206, 114547332, 656975, 1473869, 114583049, 114096545, 114451640, 115089199, 114499874, 653855, 290563, 291134, 289330, 658779, 114953309, 115151303, 114558017, 353614, 629540, 114107912, 115111683, 314355, 114955156, 115180342, 115049915, 114147010, 356732, 115068226, 308449, 115173450, 115168783, 114994864, 115036036, 115095515, 418622, 115086562, 114086563, 460926, 115053105, 114097111, 114660512, 115088228, 374126, 115068319, 114444184, 115181893, 114985541, 114587840, 307101, 605069, 114984403, 114923105, 1376197, 598433, 525422, 1420467, 115102012, 115025619, 114092568, 114556100, 114980669, 311404, 1230084, 114992067, 114438172, 114509159, 1525580, 114514276, 114902420, 114995132, 292708, 115142746, 1569797, 347998, 115012976, 114484666, 1380099, 366232, 115146345, 1170981, 114533444, 115053646, 114566395, 612034, 114500590, 672213, 114909080, 1093660, 654064, 114156990, 115083261, 115186926, 375622, 1499599, 115156405, 1455485, 115134112, 581062, 114453162, 115179053, 1136554, 115137315, 115068411, 115177650, 114147230, 114096368, 114082924, 574153, 997394, 115188044, 114995012, 114097678, 571785, 114537217, 114160541, 1523143, 114168479, 115167717, 114943152, 438274, 352870, 296276, 115068403, 115021278, 114998387, 115102837, 114134520, 1546894, 114965033, 114974904, 115122666, 114517198, 518454, 115176056, 115161795, 114101307, 313985, 114691542, 114647741, 115068353, 114908745, 115132522, 115068236, 114164840, 114580200, 114134701, 496912, 1430629, 410346, 115086620, 115080288, 115011367, 115115107, 114145626, 114093523, 616523, 114151098, 1352994, 357812, 115188641, 115068257, 970531, 114147854, 114590680, 114917100, 114568851, 114534397, 114519976, 414857, 1086439, 115066001, 1300440, 114103852, 1546371, 319203, 114502443, 114491343, 299781, 1044804, 1454561, 115097077, 114898835, 1525862, 1362385, 114100133, 1146655, 115082536, 114668394, 114106253, 409518, 311968, 115009404, 114526386, 114164141, 661825, 1450944, 114099061, 114978921, 581369, 114547061, 115111621, 752557, 115089562, 1539482, 115102108, 114981075, 714682, 114952755, 115096857, 114536114, 114547395, 114915177, 115147229, 1486036, 318600, 114952580, 567262, 357947, 114599306, 115092456, 115044576, 1473274, 114988717, 115008801, 296180, 1552035, 114151536, 114151142, 114458582, 603670, 300039, 1462822, 317545, 114165574, 114629315, 114583541, 115021132, 1141286, 114925231, 357066, 638713, 678126, 115183296, 523036, 114579191, 115108853, 115177209, 301492, 115068231, 822522, 440592, 114564128, 989016, 114995832, 1201425,

    ];
	
	public function __construct($need_login = true, $type = "") {

	    //----允许netfair的跨域ajax请求----
        $origin[] = 'http:'.base_lib_Constant::NETFAIR_URL_NOT_HTTP;
        $origin[] = 'https:'.base_lib_Constant::NETFAIR_URL_NOT_HTTP;
        $AllowOrigin = strtolower($_SERVER["HTTP_ORIGIN"]);
        if(in_array($_SERVER["HTTP_ORIGIN"],$origin))
        {
            $AllowOrigin = $_SERVER["HTTP_ORIGIN"];
        }
        header("Access-Control-Allow-Origin: ".$AllowOrigin );
        header("Access-Control-Allow-Credentials: true");


        parent::__construct();
		//定位地区
		$base_area_id = base_lib_BaseUtils::getCookie('ip_area_info');
		$this->_location_area = !empty($base_area_id) ? $base_area_id : "0300";
		parent::ass("base_location_area", $this->_location_area);

		if ($need_login) {
		    $this->checkLogin($type);
		}

		$this->isGrayTestCompany();
	}

    /**
     * 是否是参与灰度测试
     * 使用缓存cookie：
     *      1、参数is_gray 可以控制cookie： is_gray:open|close
     *      2、cookie默认是参与灰度测试
     *      3、企业是否参与灰度测试的判定：cookie==open 同时 企业必须在灰度测试的企业内
     * @return bool true:是   false：不是
     */
    private function isGrayTestCompany()
    {
        $is_gray_test_company = false;


        $param_controll = false;//默人参数控制是关闭的
        $is_gray_param  = base_lib_BaseUtils::getStr($_GET['is_gray'] ? $_GET['is_gray'] : $_POST['is_gray'], "string", '');
        if ($is_gray_param == 'open') {
            base_lib_BaseUtils::ssetcookie(['_is_gray' => 'open'], 3600 * 24 * 60, '/', base_lib_Constant::COOKIE_DOMAIN);
            $param_controll = true;
        } elseif ($is_gray_param == 'close') {
            base_lib_BaseUtils::ssetcookie(['_is_gray' => 'close'], 3600 * 24 * 60, '/', base_lib_Constant::COOKIE_DOMAIN);
            $param_controll = false;
        } else {

            $_is_gray = base_lib_BaseUtils::getCookie("_is_gray");
            if (empty($_is_gray) || $_is_gray == "open")
                $param_controll = true;
            elseif ($_is_gray == 'close')
                $param_controll = false;
        }

        if ($this->_userid && $this->_usertype == 'c' &&  $param_controll  && in_array($this->_userid, $this->gray_companys)) {
            $is_gray_test_company = true;
        }

        $this->_aParams['_is_gray_test_company'] = $is_gray_test_company;

        $this->is_gray_company = $is_gray_test_company;
        return $is_gray_test_company;
    }

	public function checkLogin($type = "") {
		//session or cookie
		if ($this->isLogin() && $this->_usertype == 'c') {

		    $service_company_account = new base_service_company_account();
			$cookie_account_id = isset($_POST['upload_cookie_accountid']) ? $_POST['upload_cookie_accountid'] : base_lib_BaseUtils::getCookie('accountid');
			$cookie_account = $service_company_account->getAccount($cookie_account_id, 'state,is_effect');
			if (!$cookie_account['is_effect'] || !$cookie_account['state']) {
				$this->destroySession(); //需要在清除cookie之前调用
				
				$aCookie = array (
					'userid' => '', 'nickname' => '', 'usertype' => '', 'tick' => '', 'userkey' => '', 'headphoto' => '', 'hidePromiseTip' => '', 'accountid' => ''
				);
				base_lib_BaseUtils::ssetcookie($aCookie, -1, '/', base_lib_Constant::COOKIE_DOMAIN);
				//销毁session
				session_start();
				session_unset();
				session_destroy();
				$this->redirect_url('/login');
				exit();
			}
		}
		else {
			if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
				$result['isNeedLogin'] = 'true';
				$result['type'] = 'c';
				echo json_encode($result);
				exit();
			}
			else {
				if ($type == "part") {
					$this->redirect_url('/login?type=part');
				}
				else {
				    if($this->is_account_down){
                        $this->redirect_url('/login?is_down=1');
                    }else{
                        $this->redirect_url('/login');
                    }

				}
				exit();
			}
		}
	}
	
	//初始化session服务器
	private function getSessionServer() {
		if ($this->session_server == null) {
			$this->session_server = new base_lib_Cache('redis3');
			$this->session_server->redis_select(4);
		}
		
		return $this->session_server;
	}
	
	//设置生成周期
	function setLifetime($key, $value, $is_boss_login = false) {
		$redis = $this->getSessionServer();
		#region 需要处理的单点登录 单位登录com-
		$re_key = explode('-', $key);
		if (!$is_boss_login && count($re_key) == 5 && $re_key[3] && $re_key[0] == 'com' && in_array($re_key[2], array ('web', 'mobile'))) {
			//需要处理的单点登录 单位登录com-
			$login_keys = $redis->redis_keys("com-{$re_key[1]}-{$re_key[2]}-{$re_key[3]}-*");
			if ($login_keys) {
				array_walk($login_keys, function ($value, $key) use ($redis) {
					$redis->redis_del($value);
				});
			}
		}
		#endregion
		$redis->redis_setex($key, 7200, $value); //2 hour
		//echo 'write';
	}
	
	//获取sessionid $sw_session是否需要判断终端
	function getSessionid($company_id, $tick, $account_id = 0, $verdict_mobile = true) {
		$detect = new SMobiledetect();
		$tick_session_id[] = 'com';
		$tick_session_id[] = $company_id;
		$verdict_mobile and $tick_session_id[] = !$detect->isMobile() && !$detect->isTablet() ? 'web' : 'mobile';
		$account_id and $tick_session_id[] = $account_id;
		$tick_session_id[] = $tick;
		
		return implode('-', $tick_session_id);
	}
	
	/**
	 * 单点登录上线 老数据自动切换到单点登录逻辑    zhouwenjun 2019/1/18 14:56
	 * @param array $storage
	 * @return array
	 */
	function switchover_single_login($storage = array (), $iUserId, $sTick, $sAccountid, $company_id, $tick, $skey) {
		if ($storage) {
			return $storage;
		}
		if ($iUserId && $sTick) {
			$redis = $this->getSessionServer();
			$session_id = $this->getSessionid($iUserId, $sTick, $sAccountid, false);
			$storage = $redis->redis_get($session_id); //格式：key_time
			if ($storage && $company_id && $tick && $skey) {
				//老数据自动切换到单点登录逻辑
				$this->setLifetime($this->getSessionid($company_id, $tick, $sAccountid), $storage);
				$redis->redis_del($session_id);//删除老数据
			}
		}
		
		return $storage;
	}
	
	//销毁session
	function destroySession() {
		$iUserId = isset($_POST['upload_cookie_userid']) ? $_POST['upload_cookie_userid'] : base_lib_BaseUtils::getCookie('userid');
		$sUserName = isset($_POST['upload_cookie_nickname']) ? $_POST['upload_cookie_nickname'] : base_lib_BaseUtils::getCookie('nickname');
		$sUserType = isset($_POST['upload_cookie_usertype']) ? $_POST['upload_cookie_usertype'] : base_lib_BaseUtils::getCookie('usertype');
		$sKey = isset($_POST['upload_cookie_userkey']) ? $_POST['upload_cookie_userkey'] : base_lib_BaseUtils::getCookie('userkey');
		$sTick = isset($_POST['upload_cookie_tick']) ? $_POST['upload_cookie_tick'] : base_lib_BaseUtils::getCookie('tick');
		$sAccountid = isset($_POST['upload_cookie_accountid']) ? $_POST['upload_cookie_accountid'] : base_lib_BaseUtils::getCookie('accountid');
		if (!empty($sKey) && md5($iUserId . $sUserName . $sUserType . base_lib_Constant::SYSUSERKEY) == $sKey) {
			$redis = $this->getSessionServer();
			$session_id = $this->getSessionid($iUserId, $sTick, $sAccountid);
			
			$redis->redis_delete($session_id);
		}
	}
	
	//单位专用的登录校验,比个人更严格
	function isLogin() {
		$result = false;
		//用于上传时解决火狐FLASH丢失COOKIE导致无法验证而上传不起的BUG
		$iUserId = isset($_POST['upload_cookie_userid']) ? $_POST['upload_cookie_userid'] : base_lib_BaseUtils::getCookie('userid');
		$sUserName = isset($_POST['upload_cookie_nickname']) ? $_POST['upload_cookie_nickname'] : base_lib_BaseUtils::getCookie('nickname');
		$sUserType = isset($_POST['upload_cookie_usertype']) ? $_POST['upload_cookie_usertype'] : base_lib_BaseUtils::getCookie('usertype');
		$sKey = isset($_POST['upload_cookie_userkey']) ? $_POST['upload_cookie_userkey'] : base_lib_BaseUtils::getCookie('userkey');
		$sTick = isset($_POST['upload_cookie_tick']) ? $_POST['upload_cookie_tick'] : base_lib_BaseUtils::getCookie('tick');
		$sAccountid = isset($_POST['upload_cookie_accountid']) ? $_POST['upload_cookie_accountid'] : base_lib_BaseUtils::getCookie('accountid');


		if (!empty($sKey) && md5($iUserId . $sUserName . $sUserType . base_lib_Constant::SYSUSERKEY) == $sKey) {
			$redis = $this->getSessionServer();
			$session_id = $this->getSessionid($iUserId, $sTick, $sAccountid);
			$storage = $redis->redis_get($session_id); //格式：key_time

			//判断是否需要切换数据-老数据自动切换到单点登录逻辑
//			$storage = $this->switchover_single_login($storage, $iUserId, $sTick,$sAccountid, $iUserId, $sTick, $sKey);
			$arr = explode('_', $storage);
			$session_key = $arr[0];
			$last_time = $arr[1];
			
			if (!empty($session_key) && $session_key == $sKey) {
				$cache_bosslogin = new company_service_bosslogin();
				$is_boss_login = $cache_bosslogin->getVerifyCode($session_id);
				
				$service_company = new base_service_company_company();
				/*--------------boss端企业违规封号处理- 2018-12-15 --------------*/
				$boos_fordden = $service_company->isBossForbid(null, $iUserId, null, null);


                //----------是否是汇博教育企业-----------
                $service_comstate = new base_service_company_comstate();
                $comstate         = $service_comstate->getCompanyState($iUserId, "train_type");
                $is_company_edu   = $comstate['train_type'] == 1 ? true : false;

                if (!$is_boss_login && ($boos_fordden['is_foribid'] === true || $is_company_edu )) {
					$result = false;
				}
				else {
					
					//ok
					$this->_username = $sUserName;
					$this->_userid = $iUserId;
					$this->_usertype = $sUserType;
					
					parent::ass('_userid', $iUserId);
					parent::ass('_nickname', $sUserName);
					parent::ass('_usertype', $sUserType);
					
					//设置活跃时间,防止太频繁，每分钟写一次就可以了    延长到10分钟写一次
					$mi = date('Y-m-d H:i');
					$stamp = strtotime($mi);
					if (($last_time + 600) < $stamp) {
						$this->setLifetime($session_id, $session_key . '_' . $stamp);
					}
					
					$result = true;
				}
			}
			else {
			    $this->is_account_down = true;
				$result = false;
			}
		}
		else {
			$result = false;
		}
		
		if (!$result && $iUserId > 0) {
			//清除一下cookie,防止在客户端仍能显示出登录状态
			$aCookie = array (
				'userid' => '', 'nickname' => '', 'usertype' => '', 'tick' => '', 'userkey' => ''
			);
			base_lib_BaseUtils::ssetcookie($aCookie, -1, '/', base_lib_Constant::COOKIE_DOMAIN);
		}
		
		return $result;
	}
	
	/**
	 *Json格式化输出
	 * @param bool $status 成功状态
	 * @param string $msg  提示消息
	 * @return string json格式字符串
	 **/
	protected function jsonMsg($status, $msg = '', $json_data = array ()) {
		return json_encode(array ('status' => $status, 'msg' => $msg, 'data' => $json_data));
	}
	
	/**
	 * 获取公司会员情况
	 * @return array(是否为会员，离有效期还有天数)
	 */
	protected function getCompanyMemberInfo() {
		if (empty($this->_userid)) {
			return false;
		}
		
		$company_service = new base_service_company_company();
		$company = $company_service->getCompany($this->_userid, 1, "com_level,start_time,end_time");
		$is_opened = true;
		if (empty($company['com_level']) || intval($company['com_level']) <= 0 || base_lib_BaseUtils::nullOrEmpty($company['end_time']) || base_lib_BaseUtils::nullOrEmpty($company['start_time'])) {
			$is_opened = false;
		}
		
		$diff = intval(floor((strtotime($company['end_time']) - strtotime(date("Y-m-d 00:00:00"))) / 3600 / 24));
		$xml = SXML::load('../config/company/company.xml');
		$bufferDay = 0;
		if (!is_null($xml)) {
			$bufferDay = $xml->OverdueBufferDay;
		}
		
		if ($diff < -$bufferDay || !$is_opened) {
			return "notmember";
		}
		else {
			if ($diff < 0) {
				return "overduemember";
			}
			else {
				return "member";
			}
		}
	}
	
	/**
	 * @desc 判断有没有权限操作这个
	 */
	public function canDo($popedom_code, $boss_user_id = null) {
		$boss_user_id = !empty($boss_user_id) ? $boss_user_id : base_lib_BaseUtils::getCookie("bossuser");
		if (empty($boss_user_id)) {
			return true;
		}
		$service_related_user = new base_service_boss_company_grouprelateduser();
		$popedom_array = $service_related_user->getAllPopedom($boss_user_id);
		if (empty($popedom_array)) {
			return false;
		}
		$popedom_code_array = base_lib_BaseUtils::getProperty($popedom_array, "popedom_code");
		if (in_array($popedom_code, $popedom_code_array)) {
			return true;
		}
		
		return false;
		
	}
	
	/**
	 * 返回json数据
	 * @param int|string $code fail   success
	 * @param string $msg
	 * @param array| $data
	 * @author chenbin
	 */
	function ajax_data_json($code = ERROR, $msg = "操作失败", $data = array ()) {
		define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') && !$_REQUEST['hbDialog']) ? true : false);
		$msg = $msg ? $msg : '操作失败';
		$code = $code ? $code : ERROR;
		if (!IS_AJAX) {
			$render_data['msg'] = $msg;
			$render_data["url"] = base_lib_Constant::COMPANY_URL_NO_HTTP;
			exit($this->render('./common/tipsmsg1.html', $render_data));
		}
		// 返回JSON数据格式到客户端 包含状态信息
		header('Content-Type:application/json; charset=utf-8');
		exit(json_encode(array ('code' => $code, 'msg' => $msg, 'data' => $data)));
	}
	
	/**
	 * 设置Excel基本信息
	 * @param $filename
	 * @param $title
	 */
	function SetExcelHeader($filename, $title) {
		header("Content-Type: application/vnd.ms-excel;");
		header("Content-Disposition:attachment;filename=$filename.xls");
	}
	
	/**
	 * 导出Excel文本
	 * @param $data
	 * @param $fields
	 * @param bool $is_filter_html 是否html 过滤
	 */
	function SetExcelBody($data, $fields, $is_filter_html = false) {
		foreach ($fields as $v) {
			echo iconv("utf-8", "gbk", $v) . "\t";
		}
		echo "\n";
		
		$keys = array_keys($fields);
		foreach ($data as $value) {
			foreach ($keys as $key) {
				$value[ $key ] = strip_tags($value[ $key ]);
				if (!empty($value[ $key ])) {
					if (is_numeric(@$value[ $key ]) && strlen(@$value[ $key ]) > 15) {
						echo iconv("utf-8", "gbk", "'" . trim(@$value[ $key ])) . "\t";
					}
					else {
						echo iconv("utf-8", "gbk", (trim(@$value[ $key ])) ? trim(@$value[ $key ]) : '') . "\t";
					}
				}
				else {
					echo iconv("utf-8", "gbk", (trim(@$value[ $key ])) ? trim(@$value[ $key ]) : '') . "\t";
				}
			}
			echo "\n";
		}
		die;
	}
	
	/**
	 * 验证企业认证是否完善
	 * @param $company_id    企业id
	 */
	public function checkCompanyLetter($company_id, $source = "") {
		if (empty($company_id)) {
			return array ("code" => 500, 'status' => false, "msg" => "参数错误");
		}
		
		$service_company = new base_service_company_company();
		$service_company_resources_resources = base_service_company_resources_resources::getInstance($company_id);
		$letter_info = $service_company_resources_resources->getCompanyAuditStatusV2();
		if ($company_id == 114492811) {
			SlightPHP::log("企业认证:" . print_r($letter_info, true));
		}
//		var_dump($letter_info);die;
		$licence_audit_type = $letter_info['licence_audit_type'];
		$letter_audit_type = $letter_info['letter_audit_type'];
		$code = 200;
		$msg = "";
		
		$end_time = '2019-01-31 23:59:59';
		$company = $service_company->getCompany($company_id, '1', 'is_effect,is_audit,audit_state,com_level,create_time');
		
		//获取企业信息
		//10.已付费会员企业未上传委托书，截止2019年3月31日后，如仍未上传自动转为仅上传营业执照的提示，且在招岗位暂时屏蔽，直至委托书认证通过解除屏蔽
		$audit_type = $letter_info['audit_type'];
		//用人单位在招聘时需提供招聘委托证明材料
		//if ($audit_type == 1) {
        if (false) {
			if ($letter_audit_type == 0 && $source != 'resume') {
				if ($company['com_level'] == 1) {
					$code = 511;
					$msg = "根据国务院《人力资源市场暂行条例》相关规定，用人单位在招聘时需提供招聘委托证明材料，请贵单位于2019年1月31日前补交<span>招聘委托书</span>，逾期招聘行为将暂时冻结，通过认证后恢复。";
					
					return array ("code" => $code, 'status' => false, "msg" => $msg);
				}
				if ($company['com_level'] > 1) {
					$code = 510;
					$msg = "根据国务院《人力资源市场暂行条例》相关规定，用人单位在招聘时需提供招聘委托证明材料，请贵单位于2019年5月13日前补交<span>招聘委托书</span>，逾期招聘行为将暂时冻结，通过认证后恢复。";
					
					return array ("code" => $code, 'status' => false, "msg" => $msg);
				}
			}
		}
		
		//4.营业执照与招聘委托书审核中
		if ($letter_audit_type == 2 && $licence_audit_type == 2) {
			$code = 504;
			$msg = "<span>营业执照</span>与<span>招聘委托书</span>正在审核中";
			
			return array ("code" => $code, 'status' => false, "msg" => $msg);
		}
		//某一个证件在审核中，另一个必须是审核通过的
		//5.招聘委托书正在审核中
		if ($letter_audit_type == 2) {
			$code = 505;
			$msg = "<span>招聘委托书</span>正在审核中";
			
			return array ("code" => $code, 'status' => false, "msg" => $msg);
		}
		//6.营业执照正在审核中
		if ($licence_audit_type == 2) {
			$code = 506;
			$msg = "<span>营业执照</span>正在审核中";
			
			return array ("code" => $code, 'status' => false, "msg" => $msg);
		}
		
		//委托书 暂缺待补 临时证待补和暂缺待补
		if (($licence_audit_type == 4 || $letter_audit_type == 4) && $source != 'resume') {
			//均是暂缺待补 //请于2019-06-07日前上传营业执照/委托书，逾期冻结职位，认证后回复。
			if ($licence_audit_type == 4 && $letter_audit_type == 4) {
				$end_time = min($letter_info['licence_end_time'], $letter_info['letter_end_time']);
				if (strtotime($end_time)) {
					$end_time = date('Y年m月d日', strtotime($end_time));
					$code = 501;
					$msg = "请于{$end_time}前上传<span>营业执照</span>与<span>委托书</span>，逾期冻结职位，认证后恢复。";
					
					return array ("code" => $code, 'status' => false, "msg" => $msg);
				}
			}
			//1项通过，1项暂缺待补
			//请于2019-06-07日前上传 营业执照/委托书 ，逾期冻结职位，认证后回复。
			if (($licence_audit_type == 4 && $letter_audit_type == 1)
				|| ((in_array($licence_audit_type, [1, 5]) && $letter_audit_type == 4))
			) {
				$end_time = $licence_audit_type == 4 ? $letter_info['licence_end_time'] : $letter_info['letter_end_time'];
				if (strtotime($end_time)) {
					$code = 501;
					$msg_content = $licence_audit_type == 4 ? "营业执照" : "委托书";
					if (date('Y-m-d', strtotime($end_time)) >= $this->_ymd) {
						$end_time = date('Y年m月d日', strtotime($end_time));
						$msg = "请于{$end_time}前上传<span>{$msg_content}</span>，逾期冻结职位，认证后恢复。";
					}
					//暂缺待补 已失效
					else {
						$msg = "<span>{$msg_content}</span>已逾期,请尽快认证。";
					}
					
					return array ("code" => $code, 'status' => false, "msg" => $msg);
				}
			}
		}
		
		//审核不通过
		//7.营业执照和委托书均未通过审核
		if ($letter_audit_type == 3 && $licence_audit_type == 3) {
			$code = 507;
			$msg = "您的<span>营业执照</span>与<span>招聘委托书</span>未通过审核";
			
			return array ("code" => $code, 'status' => false, "msg" => $msg);
		}
		//8.营业执照未通过审核
		if ($letter_audit_type != 3 && $licence_audit_type == 3) {
			$code = 508;
			$msg = "您的<span>营业执照</span>未通过审核";
			
			return array ("code" => $code, 'status' => false, "msg" => $msg);
		}
		//9.招聘委托书未通过审核
		if ($letter_audit_type == 3 && $licence_audit_type != 3) {
			$code = 509;
			$msg = "您的<span>招聘委托书</span>未通过审核";
			
			return array ("code" => $code, 'status' => false, "msg" => $msg);
		}
		
		//1.营业执照和委托书均未上传
		if ($letter_audit_type == 0 && $licence_audit_type == 0) {
			$code = 501;
			if ($source == 'resume') {
				if ($audit_type != 1) {
//					$msg = "您还未上传<span>营业执照</span>和<span>招聘委托书</span>，暂不能对简历进行处理";
					$msg = "您还未上传<span>营业执照</span>，暂不能对简历进行处理";
					
					return array ("code" => $code, 'status' => false, "msg" => $msg);
				}
			}
			else {
//				$msg = "您还未上传<span>营业执照</span>和<span>招聘委托书</span>，发布职位无法对外展示";
				$msg = "您还未上传<span>营业执照</span>，发布职位无法对外展示";
				
				return array ("code" => $code, 'status' => false, "msg" => $msg);
			}
			
			
		}
		//2.仅上传营业执照
		if ($letter_audit_type == 0 && $licence_audit_type != 0 && $licence_audit_type != 5) {
			$code = 502;
			if ($source == 'resume') {
				if ($audit_type != 1) {
					$msg = "您还有<span>招聘委托书</span>未上传，暂不能对简历进行处理";
					
					return array ("code" => $code, 'status' => false, "msg" => $msg);
				}
				
			}
			else {
				$msg = "您还有<span>招聘委托书</span>未上传，发布职位无法对外展示";
				
				return array ("code" => $code, 'status' => false, "msg" => $msg);
			}
			
		}
		
		//3.仅上传委托书
		if ($letter_audit_type != 0 && $licence_audit_type == 0) {
			$code = 503;
			if ($source == 'resume') {
				if ($audit_type != 1) {
					$msg = "您还未上传<span>营业执照</span>，暂不能对简历进行处理";
					
					return array ("code" => $code, 'status' => false, "msg" => $msg);
				}
				
			}
			else {
				$msg = "您还未上传<span>营业执照</span>，发布职位无法对外展示";
				
				return array ("code" => $code, 'status' => false, "msg" => $msg);
			}
			
		}
		
		//7同行认证
		if ($licence_audit_type == 5) {
			$code = 501;
			$msg = "请完善企业认证资料";
			
			return array ("code" => $code, 'status' => false, "msg" => $msg);
		}
		
		
		return array ("code" => 200, 'status' => true, "msg" => "");
	}
	
    /**
     * 灰度测试页面新，旧页面显示
     * @param type $source 原地址 
     *                  1：收到的简历菜单下链接（apply/index）
     * @param type $params
     * @return boolean
     */
    public function grayJump($source, $inPath){
        if(!$this->is_gray_company)
            return false;
        
        $params = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        if ($source == 1) {
            if($params['status'] == 1){//已邀请简历
                include_once './app/controller/invitev1.page.php';
                $invitev1 = new controller_invitev1();
                echo $invitev1->pageList($inPath);
                die;
            }
        }else if ($source == 2) {//面试通过，未通过，爽约，已入职列表
            include_once './app/controller/invitev1.page.php';
            $invitev1 = new controller_invitev1();
            echo $invitev1->pageDealedList($inPath);
            die;
        }
    }
    
}

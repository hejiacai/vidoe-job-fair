<?php /* Smarty version Smarty-3.0.7, created on 2020-03-26 10:43:07
         compiled from "app\templates\./public/shuanxuan/statistics.html" */ ?>
<?php /*%%SmartyHeaderCode:50195e7c16bbca1101-58844582%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '44452823d2b5e378a0e333b81e8328ea5da64e80' => 
    array (
      0 => 'app\\templates\\./public/shuanxuan/statistics.html',
      1 => 1585190566,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '50195e7c16bbca1101-58844582',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_version')) include 'E:\slightPHP\plugins\smarty3\/plugins_slightphp\function.version.php';
?><?php include_once ('app/controller/piwik.php');?>

<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?fb51309e47424acd6e31c0bd2a65a5a1";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
<script type="text/javascript" language="javascript" src="<?php echo smarty_function_version(array('file'=>'track.js'),$_smarty_tpl);?>
"></script>
<!-- 原有的百度自动推送不支持https,现更换为下列支持https的写法
<script>
(function(){
    var bp = document.createElement('script');
    bp.src = '//push.zhanzhang.baidu.com/push.js';
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();
</script>
 -->
<script>
(function(){
    var bp = document.createElement('script');
    var curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https') {
        bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';        
    }
    else {
        bp.src = 'http://push.zhanzhang.baidu.com/push.js';
    }
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();
</script>

<!-- Piwik -->
<script type="text/javascript">

</script>
<!-- End Piwik Code -->

<!-- 360 code -->
<!--<script>(function(){
var src = (document.location.protocol == "http:") ? "http://js.passport.qihucdn.com/11.0.1.js?c85aede9c1d3b7d83d29336a1b436a73":"https://jspassport.ssl.qhimg.com/11.0.1.js?c85aede9c1d3b7d83d29336a1b436a73";
document.write('<script src="' + src + '" id="sozz"><\/script>');
})();
</script>-->

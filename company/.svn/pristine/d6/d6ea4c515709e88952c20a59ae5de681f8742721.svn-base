<?php
class controller_poster extends components_cbasepage
{
    private $app_id = 'wx1cf959f941c33f64';
    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct();
    }

    public function pageGetPic($inPath){
        $pathdata     = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_flag     = base_lib_BaseUtils::getStr($pathdata['job_flag'],'string','');
        $job_id     = base_lib_BaseUtils::getStr($pathdata['job_id'],'string','');
        $poster_type  = base_lib_BaseUtils::getStr($pathdata['poster_type'],'string',2);
        if($job_flag){
            $job_id = base_lib_Rewrite::getId('job',$job_flag);
        }
        $this->_aParams['job_id']     = $job_id;
        $this->_aParams['poster_type']  = $poster_type;

        $img_num = 6;
        for($i=1;$i<=$img_num;$i++){
            $this->_aParams["job_img$i"]  = base_lib_Constant::COMPANY_URL_NO_HTTP. "/posterimg/job$i.jpg";
        }

        $this->_aParams['num'] = $img_num;
        return $this->render("./showimg.html", $this->_aParams);
    }


    public function pageJobPoster($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_id    = base_lib_BaseUtils::getStr($pathdata['job_id'],'string','');
        $poster_type = base_lib_BaseUtils::getStr($pathdata['poster_type'],'string','2');
        $is_download = base_lib_BaseUtils::getStr($pathdata['is_download'],'string','');
        if(empty($job_id)){
            $this->_aParams["msg"] = "参数错误，您访问的页面不存在";
            $this->_aParams["url"] = "/";
            return $this->render("./common/showmsgpopedom.html", $this->_aParams);
        }
        ob_clean();
        $job_flag    = base_lib_Rewrite::getFlag('job',$job_id);
        $service_job     = new base_service_company_job_job();
        $service_company = new base_service_company_company();

        $job     = $service_job->getJob($job_id,'job_id,company_id,station,is_salary_show,other_reward_ids,min_salary,max_salary,degree_id,work_year_id,add_info,other_reward');
        $company = $service_company->getCompany($job['company_id'],1,'company_id,company_name,company_shortname,address,is_proxyed,proxy_com_id');

        //代招企业
        if ($company['is_proxyed'] == base_service_company_resources_resources::HR_SUB_ACCOUNT) {
            $company	  = $service_company->getCompany($company['proxy_com_id'], 1, 'company_id,company_name');
        }

        //处理数据
        //../mobile/posterimg/companyv2{$poster_type}.jpg

        $company_name = $company['company_name'];
        $degree       = $this->getDegree($job['degree_id'],'学历不限');
        $work_year    = $this->getWorkYear($job['work_year_id'],'工作经验不限');
        $rewards      = $this->getReward(explode(',',$job['other_reward_ids']));
        $rewards = implode('、', array_filter(array_merge(explode('、', $rewards), explode(',', $job['other_reward']))));
        $address      = $job['add_info'];
        header('Content-Type: image/jpeg');
        $filename = "../mobile/posterimg/job{$poster_type}.jpg";
        $filename_cover = "./posterimg/cover.png";
        $font     = '../mobile/msyhbd.ttf';
        $font2    = '../mobile/simhei.ttf';

        $src_path = "http:".base_lib_Constant::MOBILE_URL."/poster/GetCode/job_flag-$job_flag-poster_type-$poster_type";

        $src = imagecreatefromstring(file_get_contents($src_path));

        $image = imagecreatefromjpeg($filename);
        $image_cover = imagecreatefrompng($filename_cover);
        //设置字体的颜色为红色
        $jobNameColorRed    = imagecolorallocate($image, 255, 0, 0);
        $basicTxtColorBlack = imagecolorallocate($image, 0, 0, 0);
        $basicTxtColorBlue  = imagecolorallocate($image, 38, 66, 132);
        $basicTxtColortype5 = imagecolorallocate($image, 202, 178, 141);
        $basicTitle  = imagecolorallocate($image, 187,177,156);
        $basicTxtColorWrt  = imagecolorallocate($image, 250, 250, 250);
        $basicTxtColor4 = imagecolorallocate($image, 0, 3, 134);

        switch($poster_type){
            case 1:
                if(mb_strlen($job['station'])>7){
                    imagettftext($image, 24-ceil((mb_strlen($job['station'])-4)/2), 0,mb_strlen($job['station'])>10?100:130 ,750, $basicTxtColorBlack,$font,$job['station'].'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                }else{
                    $job_len = strlen($job['station'].'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                    imagettftext($image, 25, 0,180-$job_len*0.8 ,750, $basicTxtColorBlack,$font,$job['station'].'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                }
                if($degree == "学历不限"){
                    imagettftext($image, 24, 0,170 ,850, $basicTxtColorBlack,$font2,"学历要求：".$degree);
                }else{
                    imagettftext($image, 24, 0,170 ,850, $basicTxtColorBlack,$font2,"学历要求：".$degree.'及以上');
                }

                imagettftext($image, 24, 0,170 ,900, $basicTxtColorBlack,$font2,"经验要求：".$work_year);
                if(empty($rewards)){
                    $rewards = '钱多、事儿少、离家近';
                }
                $rewards = explode('、',$rewards);
                $rewards_strlen = count($rewards);
                if($rewards_strlen ==3 ){
                    imagettftext($image, 23, -5,340 ,470, $basicTxtColorBlack,$font,$rewards[0].'、'.$rewards[1]);
                    imagettftext($image, 23, -5,340 ,510, $basicTxtColorBlack,$font,$rewards[2]);
                }elseif($rewards_strlen >=4){
                    imagettftext($image, 23, -5,340 ,470, $basicTxtColorBlack,$font,$rewards[0].'、'.$rewards[1]);
                    imagettftext($image, 23, -5,340 ,510, $basicTxtColorBlack,$font,$rewards[2].'、'.$rewards[3]);
                    imagettftext($image, 23, -5,380 ,550, $basicTxtColorBlack,$font,"·····");
                }elseif($rewards_strlen ==2){
                    imagettftext($image, 23, -5,340 ,470, $basicTxtColorBlack,$font,$rewards[0].'、'.$rewards[1]);
                }else{
                    imagettftext($image, 23, -5,340 ,470, $basicTxtColorBlack,$font,$rewards[0]);
                }

                if(mb_strlen($address) > 13){
                    imagettftext($image, 19, 0,110 ,1208, $basicTxtColorBlack,$font2,mb_substr($address,0,13));
                    imagettftext($image, 19, 0,110 ,1250, $basicTxtColorBlack,$font2,mb_substr($address,13,mb_strlen($address)-13));
                }else{
                    imagettftext($image, 19, 0,110 ,1208, $basicTxtColorBlack,$font2,$address);
                }

                if(mb_strlen($company_name) > 10){
                    $company_name = mb_substr($company_name,0,10).'...';
                }
                imagettftext($image, 20, 0,110 ,1168, $basicTxtColorBlack,$font,$company_name);
//                imagecopyresampled($image, $src, 195, 969, 20, 20, 107, 107, 240, 245);
//                imagecopyresampled($image, $image_cover, 229, 1000, 0, 0, 35, 35, 100, 80);
                imagecopyresampled($image, $src, 195, 969, 0, 0, 110, 110, imagesx($src), imagesy($src));
                break;

            case 2:
                $x =300;
                $y = 890;
                //计算字体长度
                $job_strlen     = strlen($job['station']);
                if(empty($rewards)){
                    $rewards = '钱多、事儿少、离家近';
                }
                $rewards = explode('、',$rewards);
                $rewards_strlen = count($rewards);
                if($rewards_strlen > 3){
                    imagettftext($image, 21, 0,$x ,$y+120-10, $basicTxtColorBlack,$font,$rewards[0].'、'.$rewards[1]);
                    imagettftext($image, 21, 0,$x ,$y+160-10, $basicTxtColorBlack,$font,$rewards[2].'、'.$rewards[3]);

                    if($rewards_strlen >=6){
                        imagettftext($image, 21, 0,$x ,$y+200-10, $basicTxtColorBlack,$font,$rewards[4].'、'.$rewards[5]);
                        imagettftext($image, 21, 0,$x ,$y+230-10, $basicTxtColorBlack,$font,"·····");
                    }else{
                        imagettftext($image, 21, 0,$x ,$y+200-10, $basicTxtColorBlack,$font,$rewards[4]);
                    }

                }else{
                    imagettftext($image, 20, 0,$x ,$y+120-10, $basicTxtColorBlack,$font,implode("、",$rewards));
                }

                if(mb_strlen($job['station'])>8){
                    imagettftext($image, 24, 0,8*15,780, $basicTxtColorBlue,$font,mb_substr($job['station'],0,7).'...'.'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                }else{
                    $job_len = strlen($job['station'].'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                    imagettftext($image, 24, 0,180-$job_len*0.8 ,780, $basicTxtColorBlue,$font,$job['station'].'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                }
                if($degree == "学历不限"){
                    imagettftext($image, 21, 0,$x ,$y-10, $basicTxtColorBlack,$font,$degree);
                }else{
                    imagettftext($image, 21, 0,$x ,$y-10, $basicTxtColorBlack,$font,$degree.'及以上');
                }

                imagettftext($image, 21, 0,$x ,$y+50, $basicTxtColorBlack,$font,$work_year);
                imagettftext($image, 20, 0,$x-175 ,$y+319, $basicTxtColorBlue,$font,$company_name);

                if(mb_strlen($address) > 22){
                    imagettftext($image, 19, 0,$x-175 ,$y+359, $basicTxtColorBlue,$font2,mb_substr($address,0,22));
                    imagettftext($image, 19, 0,$x-175 ,$y+399, $basicTxtColorBlue,$font2,mb_substr($address,22,mb_strlen($address)-22));
                }else{
                    imagettftext($image, 19, 0,$x-175 ,$y+359, $basicTxtColorBlue,$font2,$address);
                }

                imagecopyresampled($image, $src, 497, 417, 0, 0, 130, 130, imagesx($src), imagesy($src));

                break;
            case 3:

                if(mb_strlen($job['station'])>8){
                    imagettftext($image, 24, 0,100 ,820, $basicTxtColorBlack,$font,mb_substr($job['station'],0,8).'...'.'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                }else{
                    imagettftext($image, 24, 0,200-mb_strlen($job['station'])*9 ,820, $basicTxtColorBlack,$font,$job['station'].'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                }
                if($degree == "学历不限"){
                    imagettftext($image, 22, 0,200 ,880, $basicTxtColorBlack,$font2,"学历要求：".$degree);
                }else{
                    imagettftext($image, 22, 0,200 ,880, $basicTxtColorBlack,$font2,"学历要求：".$degree.'及以上');
                }

                imagettftext($image, 22, 0,200 ,930, $basicTxtColorBlack,$font2,"经验要求：".$work_year);

                $rewards = array_merge($this->getRewardV2(explode(',',$job['other_reward_ids'])), explode(',', $job['other_reward']));
                if(empty($rewards)){
                    $rewards = ['钱多','事儿少','离家近'];
                }

                if(count($rewards) >= 4){
                    $str = $rewards[0].'/ '.$rewards[1].'/ '.$rewards[2].'/ '.$rewards[3];
                    $len = mb_strlen($str);
                    imagettftext($image, 24, 0,300-$len*9 ,720, $basicTxtColorBlack,$font,$rewards[0].'/ '.$rewards[1].'/ '.$rewards[2].'/ '.$rewards[3]);
                    imagettftext($image, 24, 0,320 ,770, $basicTxtColorBlack,$font,"······");
                }else{
                    $_rewards = '';
                    for($i=0;$i<count($rewards);$i++){
                        $_rewards .= $rewards[$i].'/ ';
                    }
                    $_rewards = substr($_rewards,0,strlen($_rewards)-2);
                    $len = mb_strlen($_rewards);
                    imagettftext($image, 24, 0,300-$len*9 ,720, $basicTxtColorBlack,$font,$_rewards);
                }

                if(mb_strlen($address) > 24){
                    imagettftext($image, 19, 0,110 ,1258, $basicTxtColorBlack,$font2,mb_substr($address,0,24));
                    imagettftext($image, 19, 0,110 ,1299, $basicTxtColorBlack,$font2,mb_substr($address,24,mb_strlen($address)-24));
                }else{
                    imagettftext($image, 19, 0,110 ,1258, $basicTxtColorBlack,$font2,$address);
                }
                imagettftext($image, 19, 0,110 ,1215, $basicTxtColorBlack,$font,$company_name);
//                imagecopyresampled($image, $src, 204, 993, 20, 20, 107, 107, 245, 245);
                imagecopyresampled($image, $src,  204, 993, 0, 0, 110, 110, imagesx($src), imagesy($src));
//                imagecopyresampled($image, $image_cover, 234, 1023, 0, 0, 40, 40, 100, 80);
                break;
            case 4:
                $x =91;
                $y = 536;
                $font_size4 = 19;
                //计算字体长度
                $job_strlen     = strlen($job['station']);
                if(empty($rewards)){
                    $rewards = '钱多、事儿少、离家近';
                }
                if(mb_strlen($rewards)>13)
                    $rewards = mb_substr($rewards,0,15).'...';
                imagettftext($image, $font_size4, 0,$x+112 ,888, $basicTxtColor4,$font2,$rewards);

                if(mb_strlen($job['station'])>12){
                    imagettftext($image, 24, 0,$x ,640, $basicTxtColor4,$font,mb_substr($job['station'],0,12).'...');
                }else{
                    imagettftext($image, 24, 0,$x ,640, $basicTxtColor4,$font,$job['station']);
                }
                imagettftext($image, 35, 0,$x ,740, $jobNameColorRed, $font, $this->getKsalary($job['min_salary'],$job['max_salary']));

                $degree = $degree == '学历不限' ? $degree : $degree.'及以上';
                imagettftext($image, $font_size4, 0,$x+112 ,808, $basicTxtColor4,$font2,$degree);

                imagettftext($image, $font_size4, 0,$x+112 ,849, $basicTxtColor4,$font2,$work_year);

                if(mb_strlen($address) > 16){
                    imagettftext($image, $font_size4, 0,120 ,1162, $basicTxtColor4,$font2,mb_substr($address,0,16));
                    imagettftext($image, $font_size4, 0,120 ,1204, $basicTxtColor4,$font2,mb_substr($address,16,mb_strlen($address)-16));
                }else{
                    imagettftext($image, $font_size4, 0,120 ,1162, $basicTxtColor4,$font2,$address);
                }

                if(mb_strlen($company_name)>13){
                    $company_name = mb_substr($company_name,0,13).'...';
                }
                imagettftext($image, 20, 0,120 ,1120, $basicTxtColor4,$font,$company_name);

//                imagecopyresampled($image, $src, 528, 1085, 20, 20, 105, 105, 245, 245);
//                imagecopyresampled($image, $image_cover, 558, 1115, 0, 0, 35, 35, 100, 80);
                imagecopyresampled($image, $src,  528, 1085, 0, 0, 110, 110, imagesx($src), imagesy($src));

                break;
            case 5:
                $x =300;
                $y = 890;
                //计算字体长度
                $job_strlen     = strlen($job['station']);
                if(empty($rewards)){
                    $rewards = '钱多、事儿少、离家近';
                }
                $rewards = explode('、',$rewards);
                $rewards_strlen = count($rewards);
                if($rewards_strlen > 3){
                    imagettftext($image, 15, 0,220 ,$y+58, $basicTxtColortype5,$font2,$rewards[0].'、'.$rewards[1].'、'.$rewards[2].'...');
                }else{
                    imagettftext($image, 15, 0,220 ,$y+58, $basicTxtColortype5,$font2,implode("、",$rewards));
                }

                imagettftext($image, 24, 0,110,765, $basicTxtColortype5,$font,$job['station']);
                imagettftext($image, 20, 0,110,810, $basicTxtColortype5,$font2,$this->getKsalary($job['min_salary'],$job['max_salary']));
                if($degree == '学历不限'){
                    imagettftext($image, 15, 0,220 ,$y-15, $basicTxtColortype5,$font2,$degree);
                }else{
                    imagettftext($image, 15, 0,220 ,$y-15, $basicTxtColortype5,$font2,$degree.'及以上');
                }
                imagettftext($image, 15, 0,220 ,$y+23, $basicTxtColortype5,$font2,$work_year);
                imagettftext($image, 17, 0,$x-170 ,$y+295, $basicTxtColortype5,$font2,$company_name);

                if(mb_strlen($address) > 19){
                    imagettftext($image, 17, 0,$x-170 ,$y+343, $basicTxtColortype5,$font2,mb_substr($address,0,19));
                    imagettftext($image, 17, 0,$x-170 ,$y+372, $basicTxtColortype5,$font2,mb_substr($address,19,mb_strlen($address)-19));
                }else{
                    imagettftext($image, 17, 0,$x-170 ,$y+343, $basicTxtColortype5,$font2,$address);
                }
                $src_x = 585;
                $src_y = 1144;
//                imagecopyresampled($image, $src, $src_x, $src_y, 20, 20, 105, 105, 245, 245);
//                imagecopyresampled($image, $image_cover, $src_x+32, $src_y+31, 0, 0, 35, 35, 100, 80);
                imagecopyresampled($image, $src,  $src_x, $src_y, 0, 0, 110, 110, imagesx($src), imagesy($src));
                break;
            case 6:

                //福利待遇
                if(empty($rewards)){
                    $rewards = '钱多、事儿少、离家近';
                }
                $rewards = explode('、',$rewards);
                $rewards_strlen = count($rewards);
                $rewards_height_firtst = 520;
                if($rewards_strlen ==3 ){
                    imagettftext($image, 17, 0,48 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0].'、'.$rewards[1]);
                    imagettftext($image, 17, 0,48 ,$rewards_height_firtst+35, $basicTxtColorWrt,$font2,$rewards[2]);
                }elseif($rewards_strlen >=4){
                    imagettftext($image, 17, 0,48 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0].'、'.$rewards[1]);
                    imagettftext($image, 17, 0,48 ,$rewards_height_firtst+35, $basicTxtColorWrt,$font2,$rewards[2].'、'.$rewards[3]);
                    imagettftext($image, 17, 0,48 ,$rewards_height_firtst+70, $basicTxtColorWrt,$font2,"·····");
                }elseif($rewards_strlen ==2){
                    imagettftext($image, 17, 0,48 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0].'、'.$rewards[1]);
                }else{
                    imagettftext($image, 17, 0,48 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0]);
                }


                //职位
                if(mb_strlen($job['station'])>10){
                    imagettftext($image, 23, 0,48 ,695, $basicTitle,$font,mb_substr($job['station'],0,9).'...');
                }else{
                    imagettftext($image, 23, 0,48 ,695, $basicTitle,$font,$job['station']);
                }
                imagettftext($image, 17, 0,48 ,695+40, $basicTxtColorWrt,$font,$this->getKsalary($job['min_salary'],$job['max_salary']));


                //学历及要求
                if($degree != '学历不限'){
                    $degree .= "及以上";
                }
                imagettftext($image, 17, 0,48 ,916, $basicTxtColorWrt,$font2,$degree);
                imagettftext($image, 17, 0,48 ,952, $basicTxtColorWrt,$font2,$work_year);


                //公司、地址
                if(mb_strlen($company_name)>10){
                    $company_name = mb_substr($company_name,0,10).'...';
                }
                imagettftext($image, 15, 0,300 ,1259, $basicTxtColorWrt,$font2,$company_name);

                $addres_two = mb_substr($address,18,mb_strlen($address)-18);
                imagettftext($image, 15, 0,300 ,1259+35, $basicTxtColorWrt,$font2,mb_substr($address,0,18));
                if($addres_two)
                    imagettftext($image, 15, 0,300 ,1259+62, $basicTxtColorWrt,$font2,$addres_two);



                //二维码
                imagecopyresampled($image, $src, 28, 1156, 20, 20, 108, 108, 240, 245);
//                imagecopyresampled($image, $image_cover, 66, 1192, 0, 0, 35, 35, 100, 80);
                imagecopyresampled($image, $src,  28, 1156, 0, 0, 110, 110, imagesx($src), imagesy($src));

                break;
        }

        if($is_download == 'download'){

            //保存图片添加统计
            $service_company_poster_statistics = new base_service_company_poster_statistics();
            $data = array(
                'job_id'    => $job_id,
                'company_id'    => $company['company_id'],
                'operate_type'  => 1,
                'poster_type'   => $poster_type,
                'share_type'    => 1
            );
            $service_company_poster_statistics->addPosterStatistics($data);

            $file_path = base_lib_Constant::SERVER_TEMP_FULL_FOLDER . 'job_poster/';
            $file_name = time().'.jpeg';
            if(!is_dir($file_path)){
                base_lib_BaseUtils::createDir($file_path);
            }
            imagejpeg($image,$file_path.$file_name);
            $outer_file_name = "汇博海报-".$job['station'].".jpeg";
            base_lib_BaseUtils::download($file_path.$file_name,$outer_file_name);
        }else{
            imagejpeg($image);
        }






    }


    public function pageGetCode($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_flag     = base_lib_BaseUtils::getStr($pathdata['job_flag'],'string','');
        $company_flag = base_lib_BaseUtils::getStr($pathdata['company_flag'],'string','');

        //配置APPID、APPSECRET
        ob_clean();
        $wx = new SWxApp($this->app_id);

        $ACCESS_TOKEN = $wx->getAccesstoken();
        //width是二维码宽度
        $qcode ="https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=$ACCESS_TOKEN";
        if(!empty($company_flag)){
            $param = json_encode(array("path"=>"pages/companyPro/companyPro?company_flag=$company_flag","width"=>50,"scene"=>"45622","is_hyaline"=>true));
        }else{
            $param = json_encode(array("path"=>"pages/jobDetails/jobDetails?jobflag=$job_flag","width"=>50,"scene"=>"45622","is_hyaline"=>true));
        }
        //POST参数
        $result = $this->httpRequest( $qcode, $param,"POST");
        //生成二维码
        header('Content-Type: image/jpeg');
        echo $result;
    }

    private function base64_image_content($base64_image_content,$path){
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $new_file = $path . "/" . date('Ymd', time()) . "/";
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return '/' . $new_file;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    //把请求发送到微信服务器换取二维码
    function httpRequest($url, $data='', $method='GET'){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        if($method=='POST')
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data != '')
            {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
        }

        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     * 生成海报二维码地址
     */
    public function pagePostCode($inPath){
        $path   = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $job_id = base_lib_BaseUtils::getStr($path['job_id'], 'string','');
        $poster_type  = base_lib_BaseUtils::getStr($path['poster_type'],'string',2);
        ob_clean();
        if($this->_userid && $this->_usertype == 'c' && empty($job_id)){
            $service_job     = new base_service_company_job_job();
            $common_jobstatus = new base_service_common_jobstatus();
            $job_count = $service_job->getJobCount($this->_userid,$common_jobstatus->use);
            if($job_count == 1){
                $jobId = $service_job->getJobList($this->_userid,'',$common_jobstatus->use,'job_id,job_flag')[0]['job_id'];
                $url      = "http:".base_lib_Constant::MOBILE_URL.'/poster/GetPic/job_flag-'.base_lib_Rewrite::getFlag('job',$jobId).'-poster_type-'.$poster_type;
            }else{
                $company_flag = base_lib_Rewrite::getFlag('company',$this->_userid);
                $url          = "http:".base_lib_Constant::MOBILE_URL.'/poster/GetPic/company_flag-'.$company_flag.'-poster_type-'.$poster_type;
            }
        }elseif($job_id){
            $job_flag = base_lib_Rewrite::getFlag('job',$job_id);
            $url      = "http:".base_lib_Constant::MOBILE_URL.'/poster/GetPic/job_flag-'.$job_flag.'-poster_type-'.$poster_type;
        }else{
            $url      = "http:".base_lib_Constant::MOBILE_URL;
        }
        SQrcode::png1($url,5,1);
    }

    function getKsalary($min,$max){
        if($min<10000){
            if(substr($min,1,1) != 0){
                $_min = substr($min,0,1).'.'.substr($min,1,1).'K';
            }else{
                $_min = substr($min,0,1).'K';
            }
        }else{
            $_min = substr($min,0,2).'K';
        }
        if($max<10000) {
            if (substr($max, 1, 1) != 0) {
                $_max = substr($max, 0, 1) . '.' . substr($max, 1, 1) . 'K';
            } else {
                $_max = substr($max, 0, 1) . 'K';
            }
        }else{
            $_max = substr($max,0,2).'K';
        }

        return $_min.'-'.$_max.'元/月';
    }


    /**
     *
     * 获取学历
     * @param $degree_id
     */
    private function getDegree($degree_id,$default='') {
        if(empty($degree_id)) {
            return $default;
        }
        $service_degree = new base_service_common_degree();
        return $service_degree->getDegree($degree_id);
    }

    /**
     *
     * 工作年限
     * @param unknown_type $workyear
     */
    private function getWorkYear($workyear,$default='') {
        if(base_lib_BaseUtils::nullOrEmpty($workyear)) {
            return $default;
        }
        if($workyear=='0') {
            return '工作经验不限';
        }
        $service_work = new base_service_common_workyear();
        return $service_work->getWorkyear($workyear);
    }


    /**
     *
     * 福利
     * @param  $reward_ids
     */
    private function getReward($reward_ids,$default='') {
        if(count($reward_ids)<=0) {
            return $default;
        }
        $arr = array();
        $service_reward = new base_service_common_reward();
        if(in_array('01', $reward_ids)&&in_array('02', $reward_ids)) {
            array_push($arr,'五险一金');
            $this->array_remove($reward_ids,'01');
            $this->array_remove($reward_ids,'02');
        }
        if(in_array('03', $reward_ids)&&in_array('04', $reward_ids)) {
            $this->array_remove($reward_ids,'03');
            $this->array_remove($reward_ids,'04');
            array_push($arr,'包吃住');
        }
        if(in_array('05', $reward_ids)) {
            $this->array_remove($reward_ids,'05');
            array_push($arr,'双休');
        }
        if(in_array('02', $reward_ids)) {
            $this->array_remove($reward_ids,'02');
            array_push($arr,'公积金');
        }
        foreach ($reward_ids as $id){
            $reward_name = $service_reward->getRewardName($id);
            if(base_lib_BaseUtils::nullOrEmpty($reward_name)) {continue;}
            array_push($arr,$reward_name);
        }
        return implode("、",$arr);
    }

    /**
     *
     * 福利
     * @param  $reward_ids
     */
    private function getRewardV2($reward_ids,$default='') {
        if(count($reward_ids)<=0) {
            return $default;
        }
        $arr = array();
        $service_reward = new base_service_common_reward();
        if(in_array('01', $reward_ids)&&in_array('02', $reward_ids)) {
            array_push($arr,'五险一金');
            $this->array_remove($reward_ids,'01');
            $this->array_remove($reward_ids,'02');
        }
        if(in_array('03', $reward_ids)&&in_array('04', $reward_ids)) {
            $this->array_remove($reward_ids,'03');
            $this->array_remove($reward_ids,'04');
            array_push($arr,'包吃住');
        }
        if(in_array('05', $reward_ids)) {
            $this->array_remove($reward_ids,'05');
            array_push($arr,'双休');
        }
        if(in_array('02', $reward_ids)) {
            $this->array_remove($reward_ids,'02');
            array_push($arr,'公积金');
        }
        foreach ($reward_ids as $id){
            $reward_name = $service_reward->getRewardName($id);
            if(base_lib_BaseUtils::nullOrEmpty($reward_name)) {continue;}
            array_push($arr,$reward_name);
        }
        return $arr;
    }

    /**
     *
     * 福利
     * @param  $reward_ids
     */
    private function getRewardV3($reward_ids,$default='') {
        if(count($reward_ids)<=0) {
            return $default;
        }
        $arr = array();
        $service_reward = new base_service_common_reward();
        if(in_array('01', $reward_ids)&&in_array('02', $reward_ids)) {
            array_push($arr,'五险一金');
            $this->array_remove($reward_ids,'01');
            $this->array_remove($reward_ids,'02');
        }
        if(in_array('03', $reward_ids)&&in_array('04', $reward_ids)) {
            $this->array_remove($reward_ids,'03');
            $this->array_remove($reward_ids,'04');
            array_push($arr,'包吃住');
        }
        if(in_array('05', $reward_ids)) {
            $this->array_remove($reward_ids,'05');
            array_push($arr,'双休');
        }
        if(in_array('02', $reward_ids)) {
            $this->array_remove($reward_ids,'02');
            array_push($arr,'公积金');
        }
        foreach ($reward_ids as $id){
            $reward_name = $service_reward->getRewardName($id);
            if(base_lib_BaseUtils::nullOrEmpty($reward_name)) {continue;}
            array_push($arr,$reward_name);
        }
        return implode(",",$arr);
    }
    /**
     *
     * 删除数据元素
     * @param $arr
     * @param $value
     */
    function array_remove(&$arr,$value) {
        for ($i = 0;$i<count($arr);$i++ ){
            if($arr[$i]==$value) {
                array_splice($arr, $i, 1);
                //unset($arr[$i]);
            }
        }
        return $arr;
    }

}
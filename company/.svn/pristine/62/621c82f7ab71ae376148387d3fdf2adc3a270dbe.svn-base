<?php
class controller_createposter extends components_cbasepage
{

    private $app_id = 'wx1cf959f941c33f64';
    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct(true);
//        $this->_userid = '28724394';
//        $this->_usertype = 'c';
    }



    public function pageIndex($inPath){
        $path   = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $job_id = base_lib_BaseUtils::getStr($path['job_id'], 'string','');
        $id     = base_lib_BaseUtils::getStr($path['id'], 'string','');
        if(empty($this->_userid)){
            header('Content-type: text/html; charset=utf-8');
            echo '参数错误,您访问的页面不存在';
            return;
        }
      //  $service_job = new base_service_company_job_job();
//        $job = $service_job->getJobsByCompanyIds([$this->_userid],'job_id')->items;
//        if(count($job) <2 && empty($id)){
//            header('Content-type: text/html; charset=utf-8');
//            echo '当前没有可展示的职位或职位数小于2个';
//            return;
//        }
        $company_id = $this->_userid;
        $company_flag = base_lib_Rewrite::getFlag('company',$company_id);
        $img_arr  = array();
        $code_arr = array();
        $total = 6;
        $job_num = 0;
        if(!empty($id)){
            $service_poster = new base_service_company_poster();
            $poster_info = $service_poster->getPosterById($id,'job_ids,rewards,poster_type,address_id,company_id');
            $poster_type = $poster_info['poster_type'];
            $job_num = count(explode(',',$poster_info['job_ids']));
            for($i = 1;$i <= $total; $i++){
                $img_url  = base_lib_Constant::COMPANY_URL_NO_HTTP.'/createposter/img/id-'.$id.'-poster_type-'.$i;
                $code_url = base_lib_Constant::COMPANY_URL_NO_HTTP.'/createposter/PostCode/poster_type-'.$i.'-id-'.$id;
                array_push($img_arr,["key"=>$i,'url'=>$img_url]);
                array_push($code_arr,["key"=>$i,'url'=>$code_url]);
            }

        }else{
            for($i = 1;$i <= $total; $i++){
                $img_url  = base_lib_Constant::COMPANY_URL_NO_HTTP.'/createposter/CompanyPoster/company_flag-'.$company_flag.'-poster_type-'.$i;
                $code_url = base_lib_Constant::COMPANY_URL_NO_HTTP.'/createposter/PostCode/poster_type-'.$i;
                array_push($img_arr,["key"=>$i,'url'=>$img_url]);
                array_push($code_arr,["key"=>$i,'url'=>$code_url]);
            }
        }
        $this->_aParams['img_arr'] = $img_arr;
        $this->_aParams['code_arr'] = $code_arr;
        $this->_aParams['id'] = $id;
        $this->_aParams['job_num'] = $job_num;
        $this->_aParams['company_flag'] = $company_flag;
        $this->_aParams['cur'] = $poster_type?$poster_type:2;

        return $this->render('poster.html',$this->_aParams);
    }

    public function pageEditPoster($inPath){
        $path = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $id   = base_lib_BaseUtils::getStr($path['id'], 'string','');
        $poster_type   = base_lib_BaseUtils::getStr($path['poster_type'], 'string','');

        if(empty($this->_userid)){
            echo '参数错误,您访问的页面不存在';
            return;
        }
        $company_id = $this->_userid;
        $service_reward         = new base_service_common_reward();
        $service_job            = new base_service_company_job_job();
        $service_company        = new base_service_company_company();
        $service_companyaddress = new base_service_company_companyaddress();
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
        //所有在招职位
        $job_status = new base_service_common_jobstatus();
        $service_poster = new base_service_company_poster();
        $job = $service_job->getJobList($company_resources->all_accounts,'',$job_status->use,'job_id,company_id,station,is_salary_show,other_reward_ids,min_salary,address_id,max_salary,degree_id,work_year_id',0,0,null);
        //$job = $service_job->getJobsByCompanyIds([$company_id],'job_id,station,address_id,other_reward_ids','order by mod_time desc')->items;
        if(empty($job) && !empty($id)){
            echo '请发布职位后再试！';
            return;
        }
        if(!empty($id)){

            $poster = $service_poster->getPosterById($id,'job_ids,rewards,poster_type,address_id,company_id');
            //职位
            $job_posted_ids = explode(',',$poster['job_ids']);
            $job_posted     = $service_job->getJobs($job_posted_ids,'job_id,company_id,station,is_salary_show,other_reward_ids,min_salary,max_salary,degree_id,work_year_id');
            //$job_posted = $service_job->getJobsByCompanyIds([$company_id],'job_id,station','order by mod_time desc')->items;
            $job_posted = array_slice($job_posted,0,7);
            //福利待遇
            $reward = explode(',',$poster['rewards']);
            $address_id = $poster['address_id'];
        }else{

            $job_posted = array_slice($job,0,3);
            $other_reward_ids = array();
            foreach($job_posted as $k=>$v){
                if($job_posted[$k]['other_reward_ids']){
                    $_other_reward_ids = explode(',',$job_posted[$k]['other_reward_ids']);
                    $other_reward_ids = array_merge($_other_reward_ids,$other_reward_ids);
                }
            }
            $other_reward_ids = array_unique($other_reward_ids);
            $reward = array();
            for($i=0;$i < 4 ;$i++){
                if($other_reward_ids[$i]){
                    array_push($reward,$service_reward->getRewardName($other_reward_ids[$i]));
                }
            }
            if(empty($other_reward_ids)){
                $reward = ["钱多","事儿少","离家近"];
            }
            $address_id = $job_posted[0]['address_id'];
            //$cur_address = $service_company->getCompany($this->_userid,1,'address')['address'];
        }
        $cur_address = $service_companyaddress->getAddressById($address_id)['add_info'];
        $job_posted_ids = base_lib_BaseUtils::getProperty($job_posted,'job_id');
        //按职位名称排序A--Z
        $asort_job = array();
        foreach ($job as $key=>$value)
        {
            $job_array[$key]['station'] = iconv('UTF-8', 'GBK', $value['station']);
            $job_array[$key]['job_id'] = $value['job_id'];
        }
        asort($job_array);
        foreach ($job_array as $key=>$value)
        {
            $asort_job[$key] = $job_array[$key];
            $asort_job[$key]['station'] = iconv('GBK', 'UTF-8', $value['station']);
        }
        $job = $asort_job;
        //该公司所有地址
        $address = $service_companyaddress->getAddressListByCompanyId($company_id,1)->items;
       // var_dump($address);
        $company_id = $this->_userid;
        $company_flag = base_lib_Rewrite::getFlag('company',$company_id);
        $img_arr  = array();
        $code_arr = array();
        $total = 6;
        if(!empty($id)){
            for($i = 1;$i <= $total; $i++){
                $img_url  = base_lib_Constant::COMPANY_URL_NO_HTTP.'/createposter/img/id-'.$id.'-poster_type-'.$i;
//                $code_url = base_lib_Constant::COMPANY_URL.'/createposter/PostCode/poster_type-'.$i;
                array_push($img_arr,["key"=>$i,'url'=>$img_url]);
                //array_push($code_arr,["key"=>$i,'url'=>$code_url]);
            }

        }else{
            for($i = 1;$i <= $total; $i++){
                $img_url  = base_lib_Constant::COMPANY_URL_NO_HTTP.'/createposter/CompanyPoster/company_flag-'.$company_flag.'-poster_type-'.$i;
//                $code_url = base_lib_Constant::COMPANY_URL.'/createposter/PostCode/poster_type-'.$i;
                array_push($img_arr,["key"=>$i,'url'=>$img_url]);
                //array_push($code_arr,["key"=>$i,'url'=>$code_url]);
            }
        }


        $this->_aParams['img_arr']  = $img_arr;
        $this->_aParams['code_arr'] = $code_arr;
        $this->_aParams['reward']   = $service_reward->getAll();
        $this->_aParams['cur_reward']   = $reward;
        $this->_aParams['job_posted']   = $job_posted; //当前生成的职位
        $this->_aParams['job_posted_ids']   = $job_posted_ids; //当前生成的职位
        $this->_aParams['job_list']   = $job;
        $this->_aParams['cur']    = $poster_type;
        $this->_aParams['address']     = $address;
        $this->_aParams['cur_address'] = $cur_address?$cur_address:$address[0]['add_info'];
        $this->_aParams['cur_address_id'] = $address_id?$address_id:0;
        $this->_aParams['company_url'] = base_lib_Constant::COMPANY_URL_NO_HTTP;

//var_dump($img_arr);

        return $this->render('editposter.html',$this->_aParams);
    }

    public function pageSavePoster($inPath){
        $path        = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $job_ids     = base_lib_BaseUtils::getStr($path['job_ids'], 'string','');
        $address_id  = base_lib_BaseUtils::getStr($path['address_id'], 'string','');
        $rewards     = base_lib_BaseUtils::getStr($path['rewards'], 'string','');
        $poster_type = base_lib_BaseUtils::getStr($path['poster_type'], 'string','2');

        $job_ids_arr = explode(',',$job_ids);
        $rewards_arr = explode(',',$rewards);

        if(empty($this->_userid) || $this->_usertype != 'c'){
            echo json_encode(['status'=>false,'msg'=>"请登录后再试"]);
            return;
        }

        if(count($job_ids_arr) < 2 || count($job_ids_arr) > 7){
            echo json_encode(['status'=>false,'msg'=>"请选择2-7个职位"]);
            return;
        }

        if(count($rewards_arr) < 1 || count($rewards_arr) > 4){
            echo json_encode(['status'=>false,'msg'=>"请选择1-4个福利待遇"]);
            return;
        }

        $service_poster = new base_service_company_poster();
        $data = [
            'job_ids'      =>$job_ids,
            'company_id'   =>$this->_userid,
            'rewards'      =>$rewards,
            'poster_type'  =>$poster_type,
            'create_time'  =>date("Y-m-d H:i:s"),
            'address_id'   =>$address_id
        ];
        $id = $service_poster->addPoster($data);
        if($id){
            echo json_encode(['status'=>true,'msg'=>"保存成功",'poster_id'=>$id]);
            return;
        }
        echo json_encode(['status'=>false,'msg'=>"保存失败",'poster_id'=>0]);
        return;
    }


    public function pageAjaxPoster($inPath){
        $path        = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $job_ids     = base_lib_BaseUtils::getStr($path['job_ids'], 'string','');
        $address_id  = base_lib_BaseUtils::getStr($path['address_id'], 'string','');
        $rewards     = base_lib_BaseUtils::getStr($path['rewards'], 'string','');
        $poster_type = base_lib_BaseUtils::getStr($path['poster_type'], 'string','1');

        if(empty($rewards)){
            $rewards = '钱多,事儿少,离家近';
        }
        $job_ids_arr = explode(',',$job_ids);
        $rewards_arr = explode(',',$rewards);
        $job_ids_arr = array_slice($job_ids_arr,0,3);
        if(empty($this->_userid) || $this->_usertype != 'c'){

            echo "<script>alert('请登录后再试')</script>";
            return;
        }

        if(count($job_ids_arr) < 2 || count($job_ids_arr) > 3){
            echo "<script>alert('请选择2-3个职位')</script>";
            return;
        }

        if(count($rewards_arr) < 1 || count($rewards_arr) > 4){
            echo "<script>alert('请选择1-4个福利待遇')</script>";
            return;
        }

        $data['job_arr']     = $job_ids;
        $data['rewards_arr'] = $rewards;
        $data['address_id']  = $address_id;
        $data['poster_type'] = $poster_type;



       // $data['rewards_arr'] = ['五险一进','水泥地发','是的放松','哥哥个好'];
       // $data['address_id']  = 11;
       // $data['poster_type'] = 1;
        $result = $this->_poster($data);
        if($result){
            echo json_encode($result);
            return;
        }
    }

    public function pageImg($inPath){
        $path       = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $data['id'] = base_lib_BaseUtils::getStr($path['id'], 'string','');
        $data['poster_type'] = base_lib_BaseUtils::getStr($path['poster_type'], 'string','');
        $this->_poster($data);
    }
    public function _poster($data){
        if(!empty($data['id'])){
            $service_poster = new base_service_company_poster();
            $_data = $service_poster->getPosterById($data['id'],'job_ids,rewards,poster_type,address_id,company_id');
            $job_ids_arr = explode(',',$_data['job_ids']);
            $rewards_arr = explode(',',$_data['rewards']);
            $address_id  = $_data['address_id'];
        }else{
            $job_ids_arr = explode(',',$data['job_arr']);
            $rewards_arr = explode(',',$data['rewards_arr']);
            $address_id  = $data['address_id'];
        }
        $poster_type = $data['poster_type'] ? $data['poster_type']:$_data['poster_type'];

        if($this->_userid != $data['company_id'] && !empty($data['company_id'])){
            return ['status'=>false,'msg'=>"参数错误，请重新登录再试"];
        }
        $company_id = $this->_userid;
        $company_flag = base_lib_Rewrite::getFlag('company',$company_id);
        //$company_flag = 'ent7mnga9';
        $service_job      = new base_service_company_job_job();
        $service_company  = new base_service_company_company();
        $common_jobstatus = new base_service_common_jobstatus();
        $service_job      = new base_service_company_job_job();
        $common_jobstatus = new base_service_common_jobstatus();
        $service_companyaddress = new base_service_company_companyaddress();

        $address = $service_companyaddress->getAddressById($address_id)['add_info'];
        $job = $service_job->getJobs($job_ids_arr,'job_id,company_id,station,is_salary_show,other_reward_ids,min_salary,max_salary,degree_id,work_year_id');
        //$job = $service_job->getJobsByCompanyIds([$company_id],'job_id,company_id,station,is_salary_show,other_reward_ids,min_salary,max_salary,degree_id,work_year_id','order by mod_time desc')->items;
        $job_count = count($job);
        if($job_count > 3){
            $job = array_slice($job,0,3);
        }

        if($job_count == 2){
            $filename = "../mobile/posterimg/companyv2{$poster_type}.jpg";
        }else{
            $filename = "../mobile/posterimg/company{$poster_type}.jpg";
        }
        //  unset($job[2]);
        $company = $service_company->getCompany($company_id,1,'company_id,company_name,company_shortname,address');
        //图片处理
        header('Content-Type: image/jpeg');
//        $filename_cover = "../mobile/posterimg/cover.png";
        $font     = './msyhbd.ttf';
        $font     = '../mobile/msyhbd.ttf';
        $font2    = '../mobile/simhei.ttf';
        //$font2    =  './msyhbd.ttf';
        $src_path = "http:".base_lib_Constant::MOBILE_URL."/poster/GetCode/company_flag-$company_flag-poster_type-$poster_type";
        $src = imagecreatefromstring(file_get_contents($src_path));
        $image = imagecreatefromjpeg($filename);
//        $image_cover = imagecreatefrompng($filename_cover);
        $company_name = $company['company_name'];
        $address      = $address ? $address : $company['address'];


        //设置字体的颜色为红色
        $jobNameColorRed    = imagecolorallocate($image, 255, 0, 0);
        $jobNameColorRed2    = imagecolorallocate($image, 176, 25, 30);
        $basicTxtColorBlack = imagecolorallocate($image, 0, 0, 0);
        $basicTxtColorBlue  = imagecolorallocate($image, 38, 66, 132);
        $basicTxtColorWrt  = imagecolorallocate($image, 250, 250, 250);
        $basicTxtColorWrt2  = imagecolorallocate($image, 63, 56, 46);
        $basicTxtColortype5 = imagecolorallocate($image, 202, 178, 141);
        $basicTitle  = imagecolorallocate($image, 187,177,156);
        $basicTxtColor4 = imagecolorallocate($image, 0, 3, 134);
        switch($poster_type){
            case 1:
                //文字X Y
                $x = 100;
                $y = $job_count == 2?790:735;
                $d = $job_count == 2? 170: 135;
                foreach($job as $k=>$v){
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }
                    if(mb_strlen($v['station'])>7){
                        $v['station'] = mb_substr($v['station'],0,6);
                        imagettftext($image, 23, 0,$x ,$y+$k*$d, $basicTxtColorBlack,$font,$v['station'].'...');
                    }else{
                        imagettftext($image, 23, 0,$x ,$y+$k*$d, $basicTxtColorBlack,$font,$v['station']);
                    }

                    imagettftext($image, 22, 0,$x+260 ,$y+$k*$d, $basicTxtColorBlack,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                    imagettftext($image, 21, 0,$x ,$y+60+$k*$d, $basicTxtColorBlack,$font2,$degree."，".$work);

                }
                $rewards_arr = implode("、",$rewards_arr);
                $rewards     = explode('、',$rewards_arr);
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

                if(mb_strlen($company_name)>10){
                    $company_name = mb_substr($company_name,0,10).'...';
                }
                imagettftext($image, 20, 0,110 ,1168, $basicTxtColorBlack,$font,$company_name);


                imagecopyresampled($image, $src, 571, 735, 0, 0, 110, 110, imagesx($src), imagesy($src));
//                imagecopyresampled($image, $image_cover, 605, 766, 0, 0, 35, 35, 100, 80);
                break;
            case 2:
                //文字X Y
                $x = 120;
                $y = $job_count == 2?820:788;
                $d = $job_count == 2? 152: 122;
                foreach($job as $k=>$v){
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }

                    if(mb_strlen($v['station'])>7){
                        $v['station'] = mb_substr($v['station'],0,6);
                        imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColorWrt,$font,$v['station'].'...');
                    }else{
                        imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColorWrt,$font,$v['station']);
                    }

                    imagettftext($image, 20, 0,$x+280 ,$y+$k*$d, $jobNameColorRed2,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                    imagettftext($image, 21, 0,$x ,$y+60+$k*$d, $basicTxtColorBlack,$font2,$degree."，".$work);

                }
                $rewards = $rewards_arr;
                $rewards_strlen = count($rewards);
                if($rewards_strlen >=4){
                    for($i=0;$i<4;$i++){
                        imagettftext($image, 28, 0,480+7 ,500+$i*50, $basicTxtColorWrt2,$font,$rewards[$i]);
                    }
                    imagettftext($image, 28, 0,510+7 ,690, $basicTxtColorWrt2,$font,"······");
                }else{
                    for($i=0;$i<$rewards_strlen;$i++){
                        imagettftext($image, 29, 0,480+7 ,500+$i*50, $basicTxtColorWrt2,$font,$rewards[$i]);
                    }
                }

                if(mb_strlen($address) > 16){
                    imagettftext($image, 19, 0,120 ,1208+30, $basicTxtColorBlack,$font2,mb_substr($address,0,16));
                    imagettftext($image, 19, 0,120 ,1250+30, $basicTxtColorBlack,$font2,mb_substr($address,16,mb_strlen($address)-16));
                }else{
                    imagettftext($image, 19, 0,120 ,1208+30, $basicTxtColorBlack,$font2,$address);
                }

                if(mb_strlen($company_name)>13){
                    $company_name = mb_substr($company_name,0,13).'...';
                }
                imagettftext($image, 20, 0,120 ,1168+30, $basicTxtColorBlack,$font,$company_name);

                imagecopyresampled($image, $src, 546, 1154, 0, 0, 110, 110, imagesx($src), imagesy($src));
                break;
            case 3:
                $x = 85;
                $y = $job_count == 2?830:800;
                $x = $job_count == 2?85:85;
                $d = $job_count == 2?160:180;
                foreach($job as $k=>$v){
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }

                    if($k<2){
                        if(mb_strlen($v['station'])>7){
                            $v['station'] = mb_substr($v['station'],0,7);
                            // imagettftext($image, 24-ceil((mb_strlen($v['station'])-2)), 0,$x ,$y+$k*$d, $basicTxtColorBlack,$font,$v['station'].'...');
                            imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColorBlack,$font,$v['station'].'...');
                        }else{
                            imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColorBlack,$font,$v['station']);
                        }
                        if($job_count < 3){
                            imagettftext($image, 20, 0,$x+250 ,$y+$k*$d, $jobNameColorRed,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                            imagettftext($image, 21, 0,$x ,$y+50+$k*$d, $basicTxtColorBlack,$font2,$degree.','.$work);
                            // imagettftext($image, 19, 0,$x ,$y+50+$k*$d, $basicTxtColorBlack,$font2,$work);
                        }else{
                            imagettftext($image, 20, 0,$x ,$y+40+$k*$d, $jobNameColorRed,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                            imagettftext($image, 21, 0,$x ,$y+80+$k*$d, $basicTxtColorBlack,$font2,$degree);
                            imagettftext($image, 21, 0,$x ,$y+120+$k*$d, $basicTxtColorBlack,$font2,$work);
                        }


                    }else{
                        if(mb_strlen($v['station'])>7){
                            $v['station'] = mb_substr($v['station'],0,7);
                            imagettftext($image, 24, 0,$x+350 ,800, $basicTxtColorBlack,$font,$v['station'].'...');
                        }else{
                            imagettftext($image, 24, 0,$x+350 ,800, $basicTxtColorBlack,$font,$v['station']);
                        }

                        imagettftext($image, 20, 0,$x+350 ,800+40, $jobNameColorRed,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                        imagettftext($image, 21, 0,$x+350 ,800+80, $basicTxtColorBlack,$font2,$degree);
                        imagettftext($image, 21, 0,$x+350 ,800+120, $basicTxtColorBlack,$font2,$work);
                    }

                }
                $rewards = $rewards_arr;
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


                if(mb_strlen($address) > 17){
                    imagettftext($image, 19, 0,105 ,1258-19, $basicTxtColorBlack,$font2,mb_substr($address,0,17));
                    imagettftext($image, 19, 0,105 ,1299-19, $basicTxtColorBlack,$font2,mb_substr($address,17,mb_strlen($address)-17));
                }else{
                    imagettftext($image, 19, 0,105 ,1258-19, $basicTxtColorBlack,$font2,$address);
                }

                if(mb_strlen($company_name)>16){
                    $company_name = mb_substr($company_name,0,16).'...';
                }
                imagettftext($image, 19, 0,105 ,1215-19, $basicTxtColorBlack,$font,$company_name);
                imagecopyresampled($image, $src, 569, 1151, 0, 0, 110, 110, imagesx($src), imagesy($src));
                break;
            case 4:
                //文字X Y
                $x = 91;
//                $y = $job_count == 2?820:1500;
                $y = 610;
//                $d = $job_count == 2? 152: 122;
                $d = 142;
                foreach($job as $k=>$v){
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }
//0 3 133
                    if(mb_strlen($v['station'])>7){
                        $v['station'] = mb_substr($v['station'],0,6);
                        imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColor4,$font,$v['station'].'...');
                    }else{
                        imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColor4,$font,$v['station']);
                    }

                    imagettftext($image, 20, 0,$x ,$y+$k*$d+50, $jobNameColorRed,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                    imagettftext($image, 21, 0,$x ,$y+85+$k*$d, $basicTxtColor4,$font2,$degree."，".$work);

                }
                $rewards      = $rewards_arr;
                if(empty($rewards)){
                    $rewards = '钱多、事儿少、离家近';
                }else{
                    $rewards = implode('、', $rewards);
                }
                if(mb_strlen($rewards)>13)
                    $rewards = mb_substr($rewards,0,15).'...';
                imagettftext($image, 19, 0,205 ,1039, $basicTxtColor4,$font2,$rewards);

                if(mb_strlen($address) > 16){
                    imagettftext($image, 19, 0,120 ,1162, $basicTxtColor4,$font2,mb_substr($address,0,16));
                    imagettftext($image, 19, 0,120 ,1204, $basicTxtColor4,$font2,mb_substr($address,16,mb_strlen($address)-16));
                }else{
                    imagettftext($image, 19, 0,120 ,1162, $basicTxtColor4,$font2,$address);
                }

                if(mb_strlen($company_name)>13){
                    $company_name = mb_substr($company_name,0,13).'...';
                }
                imagettftext($image, 20, 0,120 ,1120, $basicTxtColor4,$font,$company_name);

                imagecopyresampled($image, $src, 528, 1085, 0, 0, 110, 110, imagesx($src), imagesy($src));
                break;
            case 5:
                $y = $job_count == 2?830:800;
                $x = 110;
                $d = $job_count == 2?160:180;
                foreach($job as $k=>$v){
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }
                    $destr = $degree.'，'.$work;
                    if($k==0){
                        if(mb_strlen($v['station'])>7){
                            $v['station'] = mb_substr($v['station'],0,7);
                            imagettftext($image, 22, 0,$x ,840, $basicTxtColortype5,$font,$v['station'].'...');
                        }else{
                            imagettftext($image, 22, 0,$x ,840, $basicTxtColortype5,$font,$v['station']);
                        }

                        imagettftext($image, 20, 0,$x ,840+40, $basicTxtColortype5,$font2,$this->getKsalary($v['min_salary'],$v['max_salary']));
                        imagettftext($image, 16, 0,$x ,840+80, $basicTxtColortype5,$font2,mb_substr($destr,0,10));
                        if(mb_strlen($destr)>10){
                            imagettftext($image, 16, 0,$x ,840+104, $basicTxtColortype5,$font2,mb_substr($destr,10,mb_strlen($destr)));
                        }

                    }
                    if($k==1){
                        if(mb_strlen($v['station'])>7){
                            $v['station'] = mb_substr($v['station'],0,7);
                            imagettftext($image, 22, 0,$x+350 ,840, $basicTxtColortype5,$font,$v['station'].'...');
                        }else{
                            imagettftext($image, 22, 0,$x+350 ,840, $basicTxtColortype5,$font,$v['station']);
                        }

                        imagettftext($image, 20, 0,$x+350 ,840+40, $basicTxtColortype5,$font2,$this->getKsalary($v['min_salary'],$v['max_salary']));
                        imagettftext($image, 16, 0,$x+350 ,840+80, $basicTxtColortype5,$font2,mb_substr($destr,0,10));
                        if(mb_strlen($destr)>10){
                            imagettftext($image, 16, 0,$x+350 ,840+104, $basicTxtColortype5,$font2,mb_substr($destr,10,mb_strlen($destr)));
                        }
                    }
                    if($k==2){
                        if(mb_strlen($v['station'])>7){
                            $v['station'] = mb_substr($v['station'],0,7);
                            imagettftext($image, 22, 0,$x ,1010, $basicTxtColortype5,$font,$v['station'].'...');
                        }else{
                            imagettftext($image, 22, 0,$x ,1010, $basicTxtColortype5,$font,$v['station']);
                        }

                        imagettftext($image, 20, 0,$x ,1010+40, $basicTxtColortype5,$font2,$this->getKsalary($v['min_salary'],$v['max_salary']));

                        imagettftext($image, 16, 0,$x ,1010+80, $basicTxtColortype5,$font2,mb_substr($destr,0,10));
                        if(mb_strlen($destr)>10){
                            imagettftext($image, 16, 0,$x ,1010+104, $basicTxtColortype5,$font2,mb_substr($destr,10,mb_strlen($destr)));
                        }
                    }




                }
                $rewards = $rewards_arr;
                if(empty($rewards)){
                    $rewards = ['钱多','事儿少','离家近'];
                }
                $rewards_x = 110;
                $rewards_y = 760;
                if(count($rewards) > 4){
                    imagettftext($image, 20, 0,$rewards_x ,$rewards_y, $basicTxtColortype5,$font,$rewards[0].'、'.$rewards[1].'、'.$rewards[2].'、'.$rewards[3].'...');
                }else{
                    $_rewards = '';
                    for($i=0;$i<count($rewards);$i++){
                        $_rewards .= $rewards[$i].'、';
                    }
                    $_rewards = mb_substr($_rewards,0,mb_strlen($_rewards)-1);
                    imagettftext($image, 20, 0,$rewards_x ,$rewards_y, $basicTxtColortype5,$font,$_rewards);
                }

                $x =300;
                $y = 890;
                imagettftext($image, 17, 0,$x-170 ,$y+295, $basicTxtColortype5,$font2,$company_name);
                if(mb_strlen($address) > 19){
                    imagettftext($image, 17, 0,$x-170 ,$y+343, $basicTxtColortype5,$font2,mb_substr($address,0,19));
                    imagettftext($image, 17, 0,$x-170 ,$y+372, $basicTxtColortype5,$font2,mb_substr($address,19,mb_strlen($address)-19));
                }else{
                    imagettftext($image, 17, 0,$x-170 ,$y+343, $basicTxtColortype5,$font2,$address);
                }
                $src_x = 585;
                $src_y = 1144;
                imagecopyresampled($image, $src, $src_x, $src_y, 0, 0, 110, 110, imagesx($src), imagesy($src));
                break;
            case 6:

                $rewards      = implode('、',$rewards_arr);
                //福利待遇
                if(empty($rewards)){
                    $rewards = '钱多、事儿少、离家近';
                }
                $rewards = explode('、',$rewards);
                $rewards_strlen = count($rewards);
                $rewards_height_firtst = 385;
                if($rewards_strlen ==3 ){
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0].'、'.$rewards[1]);
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst+30, $basicTxtColorWrt,$font2,$rewards[2]);
                }elseif($rewards_strlen >=4){
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0].'、'.$rewards[1]);
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst+30, $basicTxtColorWrt,$font2,$rewards[2].'、'.$rewards[3]);
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst+60, $basicTxtColorWrt,$font2,"·····");
                }elseif($rewards_strlen ==2){
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0].'、'.$rewards[1]);
                }else{
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0]);
                }


                //---------职位信息------
                $x = 45;
                $y = 523;
                $d = 217;

                foreach($job as $k=>$v){
                    if($k==2){
                        $d= 213;
                    }
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }
                    if(mb_strlen($v['station'])>7){
                        $v['station'] = mb_substr($v['station'],0,6);
                        imagettftext($image, 23, 0,$x ,$y+$k*$d, $basicTitle,$font,$v['station'].'...');
                    }else{
                        imagettftext($image, 22, 0,$x ,$y+$k*$d, $basicTitle,$font,$v['station']);
                    }

                    imagettftext($image, 17, 0,$x ,$y+40+$k*$d, $basicTxtColorWrt,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                    imagettftext($image, 16, 0,$x ,$y+80+$k*$d, $basicTxtColorWrt,$font2,$degree);
                    imagettftext($image, 16, 0,$x ,$y+120+$k*$d, $basicTxtColorWrt,$font2,$work);

                }
                //---------职位信息------

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
                imagecopyresampled($image, $src, 28, 1156, 0, 0, 110, 110, imagesx($src), imagesy($src));

                break;
        }

        if($data['down']){
            $service_statistics = new base_service_company_poster_statistics();
            $service_statistics->addPosterStatistics(['job_id'=>0,'company_id'=>$company_id,'poster_type'=>$poster_type,'operate_type'=>1]);
            $dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
            $fileDir = "{$dirPath}poster/";
            array_map('unlink', glob("{$fileDir}*"));
            if (file_exists($fileDir)) {
                unlink($fileDir);
            }
            $s = time();
            $filename = $fileDir."$s.jpg";
            $path_arr = pathinfo($filename);
            if (!is_dir($path_arr['dirname'])) {
                base_lib_BaseUtils::createDir($fileDir);
            }

            imagejpeg($image,$filename,100);
            base_lib_BaseUtils::download($filename, "海报$s.jpg");
            unlink ($filename);
            exit;
        }
        imagejpeg($image);
    }
    /**
     * 生成海报二维码地址
     */
    public function pagePostCode($inPath){
        $path   = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $job_id = base_lib_BaseUtils::getStr($path['job_id'], 'string','');
        $poster_type  = base_lib_BaseUtils::getStr($path['poster_type'],'string',2);
        $id  = base_lib_BaseUtils::getStr($path['id'],'string','');
        if(!empty($id)){
            $url = "http:".base_lib_Constant::MOBILE_URL.'/poster/GetPic/id-'.$id.'-poster_type-'.$poster_type;
        }else{
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
                $url      = "http:".base_lib_Constant::MOBILE_URL.'/poster/GetPic';
            }
        }

        SQrcode::png($url);
    }


    public function pageGetPic($inPath){
        $pathdata     = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_flag     = base_lib_BaseUtils::getStr($pathdata['job_flag'],'string','');
        $poster_type  = base_lib_BaseUtils::getStr($pathdata['poster_type'],'string',2);
        $company_flag = base_lib_BaseUtils::getStr($pathdata['company_flag'],'string','');
        $poster_type_next  = $poster_type<3 ? $poster_type+1: 1;
        $this->_aParams['job_flag']     = $job_flag;
        $this->_aParams['company_flag'] = $company_flag;
        $this->_aParams['poster_type']  = $poster_type;
        $this->_aParams['poster_type_next']  = $poster_type_next;
        //访问量统计
        $service_statistics = new base_service_mobile_statistics();
        $service_source_type = new base_service_mobile_statisticstype();
        $items = array();
        $items["create_time"] = date("Y-m-d H:i:s");
        $items["source_type"] = $service_source_type->poster;
        $items["is_effect"] = 1;
        $items["event_number"] = 1;
        $service_statistics->addStatistics($items);
        return $this->render('showimg.html',$this->_aParams);
    }
    public function pageJobPoster($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_flag    = base_lib_BaseUtils::getStr($pathdata['job_flag'],'string','');
        $poster_type = base_lib_BaseUtils::getStr($pathdata['poster_type'],'string','1');

        if(empty($job_flag)){
            echo '参数错误，您访问的页面不存在';
            return;
        }

        $job_id    = base_lib_Rewrite::getId('job',$job_flag);
        $service_job     = new base_service_company_job_job();
        $service_company = new base_service_company_company();
        $job     = $service_job->getJob($job_id,'job_id,company_id,station,is_salary_show,other_reward_ids,min_salary,max_salary,degree_id,work_year_id');
        $company = $service_company->getCompany($job['company_id'],1,'company_id,company_name,company_shortname,address');

        //处理数据
        $company_name = $company['company_name'];
        $degree       = $this->getDegree($job['degree_id'],'学历不限');
        $work_year    = $this->getWorkYear($job['work_year_id'],'工作经验不限');
        $rewards      = $this->getReward(explode(',',$job['other_reward_ids']));
        $address      = $company['address'];
        header('Content-Type: image/jpeg');
        $filename = "../mobile/posterimg/job{$poster_type}.jpg";
        $filename_cover = "../mobile/posterimg/cover.png";
        $font     = './msyhbd.ttf';
        $font2    = './simhei.ttf';
        $font     = '../mobile/msyhbd.ttf';
        $font2    = '../mobile/simhei.ttf';
        $src_path = "http:".base_lib_Constant::MOBILE_URL."/poster/GetCode/job_flag-$job_flag";
        $src = imagecreatefromstring(file_get_contents($src_path));
        $image = imagecreatefromjpeg($filename);
        $image_cover = imagecreatefrompng($filename_cover);
        //设置字体的颜色为红色
        $jobNameColorRed    = imagecolorallocate($image, 255, 0, 0);
        $basicTxtColorBlack = imagecolorallocate($image, 0, 0, 0);
        $basicTxtColorBlue  = imagecolorallocate($image, 38, 66, 132);
        $basicTxtColortype5 = imagecolorallocate($image, 202, 178, 141);
        $basicTxtColorWrt  = imagecolorallocate($image, 250, 250, 250);
        $basicTitle  = imagecolorallocate($image, 187,177,156);
        $basicTxtColor4 = imagecolorallocate($image, 0, 3, 134);
        switch($poster_type){
            case 1:
                if(mb_strlen($job['station'])>7){
                    imagettftext($image, 24-ceil((mb_strlen($job['station'])-4)/2), 0,mb_strlen($job['station'])>10?100:130 ,750, $basicTxtColorBlack,$font,$job['station'].'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                }else{
                    $job_len = strlen($job['station'].'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                    imagettftext($image, 25, 0,180-$job_len*0.8 ,750, $basicTxtColorBlack,$font,$job['station'].'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                }
                imagettftext($image, 24, 0,170 ,850, $basicTxtColorBlack,$font2,"学历要求：".$degree.'及以上');
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

                if(mb_strlen($company_name)>10){
                    $company_name = mb_substr($company_name,0,10).'...';
                }
                imagettftext($image, 20, 0,110 ,1168, $basicTxtColorBlack,$font,$company_name);
                imagecopyresampled($image, $src, 195, 969, 20, 20, 107, 107, 240, 245);
                imagecopyresampled($image, $image_cover, 229, 1000, 0, 0, 35, 35, 100, 80);

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
                imagettftext($image, 21, 0,$x ,$y-10, $basicTxtColorBlack,$font,$degree.'及以上');
                imagettftext($image, 21, 0,$x ,$y+50, $basicTxtColorBlack,$font,$work_year);
                imagettftext($image, 20, 0,$x-175 ,$y+319, $basicTxtColorBlue,$font,$company_name);

                if(mb_strlen($address) > 22){
                    imagettftext($image, 19, 0,$x-175 ,$y+359, $basicTxtColorBlue,$font2,mb_substr($address,0,22));
                    imagettftext($image, 19, 0,$x-175 ,$y+399, $basicTxtColorBlue,$font2,mb_substr($address,22,mb_strlen($address)-22));
                }else{
                    imagettftext($image, 19, 0,$x-175 ,$y+359, $basicTxtColorBlue,$font2,$address);
                }
                imagecopyresampled($image, $src, 497, 417, 20, 20, 131, 131, 240, 245);
                imagecopyresampled($image, $image_cover, 545, 460, 0, 0, 45, 45, 100, 80);
                break;
            case 3:

                if(mb_strlen($job['station'])>8){
                    imagettftext($image, 24, 0,100 ,820, $basicTxtColorBlack,$font,mb_substr($job['station'],0,8).'...'.'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                }else{
                    imagettftext($image, 24, 0,200-mb_strlen($job['station'])*9 ,820, $basicTxtColorBlack,$font,$job['station'].'/'.$this->getKsalary($job['min_salary'],$job['max_salary']));
                }
                imagettftext($image, 22, 0,200 ,880, $basicTxtColorBlack,$font2,"学历要求：".$degree.'及以上');
                imagettftext($image, 22, 0,200 ,930, $basicTxtColorBlack,$font2,"经验要求：".$work_year);

                $rewards = $this->getRewardV2(explode(',',$job['other_reward_ids']));
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
                imagecopyresampled($image, $src, 204, 993, 20, 20, 107, 107, 245, 245);
                imagecopyresampled($image, $image_cover, 234, 1023, 0, 0, 40, 40, 100, 80);
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

                imagecopyresampled($image, $src, 528, 1085, 20, 20, 105, 105, 245, 245);
                imagecopyresampled($image, $image_cover, 558, 1115, 0, 0, 35, 35, 100, 80);

                break;
            case 5:
                $x =300;
                $y = 890;
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
                imagecopyresampled($image, $src, $src_x, $src_y, 20, 20, 105, 105, 245, 245);
                imagecopyresampled($image, $image_cover, $src_x+32, $src_y+31, 0, 0, 35, 35, 100, 80);
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
                imagettftext($image, 23, 0,48 ,695, $basicTitle,$font,$job['station']);
                imagettftext($image, 17, 0,48 ,695+40, $basicTxtColorWrt,$font,$this->getKsalary($job['min_salary'],$job['max_salary']));


                //学历及要求
                if($degree != '学历不限'){
                    $degree .= "及以上学历";
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
                imagecopyresampled($image, $image_cover, 66, 1192, 0, 0, 35, 35, 100, 80);

                break;
        }
        imagejpeg($image);
    }

    public function pageCompanyPoster($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $company_flag = base_lib_BaseUtils::getStr($pathdata['company_flag'],'string','');
        $poster_type = base_lib_BaseUtils::getStr($pathdata['poster_type'],'string','2');
        $is_down  = base_lib_BaseUtils::getStr($pathdata['down'],'string','0');

        if(empty($this->_userid)){
            echo '参数错误,您访问的页面不存在';
            return;
        }

        $company_id = base_lib_Rewrite::getId('company',$company_flag);
        if(empty($company_flag)){
            $company_id = $this->_userid;
        }
        //$company_id = '28724394';
        $service_job      = new base_service_company_job_job();
        $service_company  = new base_service_company_company();
        $common_jobstatus = new base_service_common_jobstatus();
        $service_job     = new base_service_company_job_job();
        $common_jobstatus = new base_service_common_jobstatus();

        $job = array();
        //$job = $service_job->getJobsByCompanyIds([$company_id],'job_id,company_id,station,is_salary_show,other_reward_ids,min_salary,address_id,max_salary,degree_id,work_year_id','order by mod_time desc')->items;
        $company_resources = base_service_company_resources_resources::getInstance($this->_userid);
        $job_status = new base_service_common_jobstatus();
        $job = $service_job->getJobList($company_resources->all_accounts,'',$job_status->use,'job_id,company_id,station,is_salary_show,other_reward_ids,min_salary,address_id,max_salary,degree_id,work_year_id',0,0,null);
        $job_count = count($job);
        if($job_count > 3){
            $job = array_slice($job,0,3);
        }

        ob_clean();
        if($job_count == 2){
            $filename = "../mobile/posterimg/companyv2{$poster_type}.jpg";
        }else{
            $filename = "../mobile/posterimg/company{$poster_type}.jpg";
        }
        //  unset($job[2]);
        $company = $service_company->getCompany($company_id,1,'company_id,company_name,company_shortname,address');
        //图片处理
        header('Content-Type: image/jpeg');
        $filename_cover = "../mobile/posterimg/cover.png";
        $font     = '../mobile/msyhbd.ttf';
        $font2    = '../mobile/simhei.ttf';
        //判断是否代招
        $job_proxyed = $service_job->getJob($job[0]['job_id'],'company_id');
        $proxyed_company = $service_company->getCompany($job_proxyed['company_id'],1,'is_proxyed,proxy_com_id');
        if ($proxyed_company['is_proxyed'] == base_service_company_resources_resources::HR_SUB_ACCOUNT) {
            $proxyed_company_id = $proxyed_company['proxy_com_id'];
            $company_flag       = base_lib_Rewrite::getFlag("company",$proxyed_company_id);
        }
        $src_path = "http:".base_lib_Constant::MOBILE_URL."/poster/GetCode/company_flag-$company_flag";
        $src = imagecreatefromstring(file_get_contents($src_path));
        //$src = @imagecreatefromjpeg($src_path);

        $image = imagecreatefromjpeg($filename);
        $image_cover = imagecreatefrompng($filename_cover);
        $company_name = $company['company_name'];
        $service_companyaddress = new base_service_company_companyaddress();
        $address_id = $job[0]['address_id']; // 默认去第一个职位的地址
        $address = $service_companyaddress->getAddressById($address_id)['add_info'];
        $address      = $address ? $address : $company['address'];

        //处理数据
        $other_reward_ids = array();
        foreach($job as $k=>$v){
            if($job[$k]['other_reward_ids']){
                $_other_reward_ids = explode(',',$job[$k]['other_reward_ids']);
                $other_reward_ids = array_merge($_other_reward_ids,$other_reward_ids);
            }
        }
        $other_reward_ids = array_unique($other_reward_ids);
        //设置字体的颜色为红色
        $jobNameColorRed    = imagecolorallocate($image, 255, 0, 0);
        $jobNameColorRed2    = imagecolorallocate($image, 176, 25, 30);
        $basicTxtColorBlack = imagecolorallocate($image, 0, 0, 0);
        $basicTxtColorBlue  = imagecolorallocate($image, 38, 66, 132);
        $basicTxtColorWrt  = imagecolorallocate($image, 250, 250, 250);
        $basicTxtColorWrt2  = imagecolorallocate($image, 63, 56, 46);
        $basicTxtColortype5 = imagecolorallocate($image, 202, 178, 141);
        $basicTitle  = imagecolorallocate($image, 187,177,156);
        $basicTxtColor4 = imagecolorallocate($image, 0, 3, 133);
        switch($poster_type){
            case 1:
                //文字X Y
                $x = 100;
                $y = $job_count == 2?790:735;
                $d = $job_count == 2? 170: 135;
                foreach($job as $k=>$v){
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }
                    if(mb_strlen($v['station'])>7){
                        $v['station'] = mb_substr($v['station'],0,6);
                        imagettftext($image, 23, 0,$x ,$y+$k*$d, $basicTxtColorBlack,$font,$v['station'].'...');
                    }else{
                        imagettftext($image, 23, 0,$x ,$y+$k*$d, $basicTxtColorBlack,$font,$v['station']);
                    }

                    imagettftext($image, 22, 0,$x+260 ,$y+$k*$d, $basicTxtColorBlack,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                    imagettftext($image, 21, 0,$x ,$y+60+$k*$d, $basicTxtColorBlack,$font2,$degree."，".$work);

                }
                $rewards      = $this->getReward($other_reward_ids);
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

                if(mb_strlen($company_name)>10){
                    $company_name = mb_substr($company_name,0,10).'...';
                }
                imagettftext($image, 20, 0,110 ,1168, $basicTxtColorBlack,$font,$company_name);

//                imagecopyresampled($image, $src, 571, 736, 20, 20, 105, 105, 245, 245);
                imagecopyresampled($image, $src, 571, 736, 0, 0, 110, 110, imagesx($src), imagesy($src));
//                imagecopyresampled($image, $image_cover, 605, 766, 0, 0, 35, 35, 100, 80);
                break;
            case 2:
                //文字X Y
                $x = 120;
                $y = $job_count == 2?820:788;
                $d = $job_count == 2? 152: 122;
                foreach($job as $k=>$v){
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }

                    if(mb_strlen($v['station'])>7){
                        $v['station'] = mb_substr($v['station'],0,6);
                        imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColorWrt,$font,$v['station'].'...');
                    }else{
                        imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColorWrt,$font,$v['station']);
                    }

                    imagettftext($image, 20, 0,$x+280 ,$y+$k*$d, $jobNameColorRed2,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                    imagettftext($image, 21, 0,$x ,$y+60+$k*$d, $basicTxtColorBlack,$font2,$degree."，".$work);

                }
                $rewards      = $this->getRewardV3($other_reward_ids);
                if(empty($rewards)){
                    $rewards = '钱多,事儿少,离家近';
                }
                $rewards = explode(",",$rewards);
                $rewards_strlen = count($rewards);
                if($rewards_strlen >=4){
                    for($i=0;$i<4;$i++){
                        imagettftext($image, 28, 0,480+7 ,500+$i*50, $basicTxtColorWrt2,$font,$rewards[$i]);
                    }
                    imagettftext($image, 28, 0,510+7 ,690, $basicTxtColorWrt2,$font,"······");
                }else{
                    for($i=0;$i<$rewards_strlen;$i++){
                        imagettftext($image, 29, 0,480+7 ,500+$i*50, $basicTxtColorWrt2,$font,$rewards[$i]);
                    }
                }

                if(mb_strlen($address) > 16){
                    imagettftext($image, 19, 0,120 ,1208+30, $basicTxtColorBlack,$font2,mb_substr($address,0,16));
                    imagettftext($image, 19, 0,120 ,1250+30, $basicTxtColorBlack,$font2,mb_substr($address,16,mb_strlen($address)-16));
                }else{
                    imagettftext($image, 19, 0,120 ,1208+30, $basicTxtColorBlack,$font2,$address);
                }

                if(mb_strlen($company_name)>13){
                    $company_name = mb_substr($company_name,0,13).'...';
                }
                imagettftext($image, 20, 0,120 ,1168+30, $basicTxtColorBlack,$font,$company_name);

//                imagecopyresampled($image, $src, 546, 1154, 20, 20, 105, 105, 245, 245);
                imagecopyresampled($image, $src, 546, 1154, 0, 0, 110, 110, imagesx($src), imagesy($src));
//                imagecopyresampled($image, $image_cover, 578, 1185, 0, 0, 35, 35, 100, 80);
                break;
            case 3:
                $x = 85;
                $y = $job_count == 2?830:800;
                $x = $job_count == 2?85:85;
                $d = $job_count == 2?160:180;
                foreach($job as $k=>$v){
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }

                    if($k<2){
                        if(mb_strlen($v['station'])>7){
                            $v['station'] = mb_substr($v['station'],0,7);
                            // imagettftext($image, 24-ceil((mb_strlen($v['station'])-2)), 0,$x ,$y+$k*$d, $basicTxtColorBlack,$font,$v['station'].'...');
                            imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColorBlack,$font,$v['station'].'...');
                        }else{
                            imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColorBlack,$font,$v['station']);
                        }
                        if($job_count < 3){
                            imagettftext($image, 20, 0,$x+250 ,$y+$k*$d, $jobNameColorRed,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                            imagettftext($image, 21, 0,$x ,$y+50+$k*$d, $basicTxtColorBlack,$font2,$degree.','.$work);
                            // imagettftext($image, 19, 0,$x ,$y+50+$k*$d, $basicTxtColorBlack,$font2,$work);
                        }else{
                            imagettftext($image, 20, 0,$x ,$y+40+$k*$d, $jobNameColorRed,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                            imagettftext($image, 21, 0,$x ,$y+80+$k*$d, $basicTxtColorBlack,$font2,$degree);
                            imagettftext($image, 21, 0,$x ,$y+120+$k*$d, $basicTxtColorBlack,$font2,$work);
                        }


                    }else{
                        if(mb_strlen($v['station'])>7){
                            $v['station'] = mb_substr($v['station'],0,7);
                            imagettftext($image, 24, 0,$x+350 ,800, $basicTxtColorBlack,$font,$v['station'].'...');
                        }else{
                            imagettftext($image, 24, 0,$x+350 ,800, $basicTxtColorBlack,$font,$v['station']);
                        }

                        imagettftext($image, 20, 0,$x+350 ,800+40, $jobNameColorRed,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                        imagettftext($image, 21, 0,$x+350 ,800+80, $basicTxtColorBlack,$font2,$degree);
                        imagettftext($image, 21, 0,$x+350 ,800+120, $basicTxtColorBlack,$font2,$work);
                    }

                }
                $rewards = $this->getRewardV2($other_reward_ids);
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


                if(mb_strlen($address) > 17){
                    imagettftext($image, 19, 0,105 ,1258-19, $basicTxtColorBlack,$font2,mb_substr($address,0,17));
                    imagettftext($image, 19, 0,105 ,1299-19, $basicTxtColorBlack,$font2,mb_substr($address,17,mb_strlen($address)-17));
                }else{
                    imagettftext($image, 19, 0,105 ,1258-19, $basicTxtColorBlack,$font2,$address);
                }

                if(mb_strlen($company_name)>16){
                    $company_name = mb_substr($company_name,0,16).'...';
                }
                imagettftext($image, 19, 0,105 ,1215-19, $basicTxtColorBlack,$font,$company_name);
//                imagecopyresampled($image, $src, 569, 1151, 20, 20, 110, 110, 245, 245);
                imagecopyresampled($image, $src, 569, 1151, 0, 0, 110, 110, imagesx($src), imagesy($src));
//                imagecopyresampled($image, $image_cover, 604, 1185, 0, 0, 35, 35, 100, 80);
                break;
            case 4:
                //文字X Y
                $x = 91;
//                $y = $job_count == 2?820:1500;
                $y = 610;
//                $d = $job_count == 2? 152: 122;
                $d = 142;
                foreach($job as $k=>$v){
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }
//0 3 133
                    if(mb_strlen($v['station'])>7){
                        $v['station'] = mb_substr($v['station'],0,6);
                        imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColor4,$font,$v['station'].'...');
                    }else{
                        imagettftext($image, 24, 0,$x ,$y+$k*$d, $basicTxtColor4,$font,$v['station']);
                    }

                    imagettftext($image, 20, 0,$x ,$y+$k*$d+50, $jobNameColorRed,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                    imagettftext($image, 21, 0,$x ,$y+85+$k*$d, $basicTxtColor4,$font2,$degree."，".$work);

                }
                $rewards      = $this->getRewardV2($other_reward_ids);
                if(empty($rewards)){
                    $rewards = '钱多、事儿少、离家近';
                }else{
                    $rewards = implode('、', $rewards);
                }
                if(mb_strlen($rewards)>13)
                    $rewards = mb_substr($rewards,0,15).'...';
                imagettftext($image, 19, 0,205 ,1039, $basicTxtColor4,$font2,$rewards);

                if(mb_strlen($address) > 16){
                    imagettftext($image, 19, 0,120 ,1162, $basicTxtColor4,$font2,mb_substr($address,0,16));
                    imagettftext($image, 19, 0,120 ,1204, $basicTxtColor4,$font2,mb_substr($address,16,mb_strlen($address)-16));
                }else{
                    imagettftext($image, 19, 0,120 ,1162, $basicTxtColor4,$font2,$address);
                }

                if(mb_strlen($company_name)>13){
                    $company_name = mb_substr($company_name,0,13).'...';
                }
                imagettftext($image, 20, 0,120 ,1120, $basicTxtColor4,$font,$company_name);

//                imagecopyresampled($image, $src, 528, 1085, 20, 20, 105, 105, 245, 245);
                imagecopyresampled($image, $src, 528, 1085, 0, 0, 110, 110, imagesx($src), imagesy($src));
//                imagecopyresampled($image, $image_cover, 558, 1115, 0, 0, 35, 35, 100, 80);
                break;
            case 5:
                $y = $job_count == 2?830:800;
                $x = 110;
                $d = $job_count == 2?160:180;
                foreach($job as $k=>$v){
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }
                    $destr = $degree.'，'.$work;
                    if($k==0){
                        if(mb_strlen($v['station'])>7){
                            $v['station'] = mb_substr($v['station'],0,7);
                            imagettftext($image, 22, 0,$x ,840, $basicTxtColortype5,$font,$v['station'].'...');
                        }else{
                            imagettftext($image, 22, 0,$x ,840, $basicTxtColortype5,$font,$v['station']);
                        }

                        imagettftext($image, 20, 0,$x ,840+40, $basicTxtColortype5,$font2,$this->getKsalary($v['min_salary'],$v['max_salary']));
                        imagettftext($image, 16, 0,$x ,840+80, $basicTxtColortype5,$font2,mb_substr($destr,0,10));
                        if(mb_strlen($destr)>10){
                            imagettftext($image, 16, 0,$x ,840+104, $basicTxtColortype5,$font2,mb_substr($destr,10,mb_strlen($destr)));
                        }

                    }
                    if($k==1){
                        if(mb_strlen($v['station'])>7){
                            $v['station'] = mb_substr($v['station'],0,7);
                            imagettftext($image, 22, 0,$x+350 ,840, $basicTxtColortype5,$font,$v['station'].'...');
                        }else{
                            imagettftext($image, 22, 0,$x+350 ,840, $basicTxtColortype5,$font,$v['station']);
                        }

                        imagettftext($image, 20, 0,$x+350 ,840+40, $basicTxtColortype5,$font2,$this->getKsalary($v['min_salary'],$v['max_salary']));
                        imagettftext($image, 16, 0,$x+350 ,840+80, $basicTxtColortype5,$font2,mb_substr($destr,0,10));
                        if(mb_strlen($destr)>10){
                            imagettftext($image, 16, 0,$x+350 ,840+104, $basicTxtColortype5,$font2,mb_substr($destr,10,mb_strlen($destr)));
                        }
                    }
                    if($k==2){
                        if(mb_strlen($v['station'])>7){
                            $v['station'] = mb_substr($v['station'],0,7);
                            imagettftext($image, 22, 0,$x ,1010, $basicTxtColortype5,$font,$v['station'].'...');
                        }else{
                            imagettftext($image, 22, 0,$x ,1010, $basicTxtColortype5,$font,$v['station']);
                        }

                        imagettftext($image, 20, 0,$x ,1010+40, $basicTxtColortype5,$font2,$this->getKsalary($v['min_salary'],$v['max_salary']));

                        imagettftext($image, 16, 0,$x ,1010+80, $basicTxtColortype5,$font2,mb_substr($destr,0,10));
                        if(mb_strlen($destr)>10){
                            imagettftext($image, 16, 0,$x ,1010+104, $basicTxtColortype5,$font2,mb_substr($destr,10,mb_strlen($destr)));
                        }
                    }




                }
                $rewards = $this->getRewardV2($other_reward_ids);
                if(empty($rewards)){
                    $rewards = ['钱多','事儿少','离家近'];
                }
                $rewards_x = 110;
                $rewards_y = 760;
                if(count($rewards) > 4){
                    imagettftext($image, 20, 0,$rewards_x ,$rewards_y, $basicTxtColortype5,$font,$rewards[0].'、'.$rewards[1].'、'.$rewards[2].'、'.$rewards[3].'...');
                }else{
                    $_rewards = '';
                    for($i=0;$i<count($rewards);$i++){
                        $_rewards .= $rewards[$i].'、';
                    }
                    $_rewards = mb_substr($_rewards,0,mb_strlen($_rewards)-1);
                    imagettftext($image, 20, 0,$rewards_x ,$rewards_y, $basicTxtColortype5,$font,$_rewards);
                }

                $x =300;
                $y = 890;
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
                imagecopyresampled($image, $src, $src_x, $src_y, 0, 0, 110, 110, imagesx($src), imagesy($src));
//                imagecopyresampled($image, $image_cover, $src_x+32, $src_y+31, 0, 0, 35, 35, 100, 80);;
                break;
            case 6:

                $rewards = $this->getReward($other_reward_ids);
                //福利待遇
                if(empty($rewards)){
                    $rewards = '钱多、事儿少、离家近';
                }
                $rewards = explode('、',$rewards);
                $rewards_strlen = count($rewards);
                $rewards_height_firtst = 385;
                if($rewards_strlen ==3 ){
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0].'、'.$rewards[1]);
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst+30, $basicTxtColorWrt,$font2,$rewards[2]);
                }elseif($rewards_strlen >=4){
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0].'、'.$rewards[1]);
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst+30, $basicTxtColorWrt,$font2,$rewards[2].'、'.$rewards[3]);
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst+60, $basicTxtColorWrt,$font2,"·····");
                }elseif($rewards_strlen ==2){
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0].'、'.$rewards[1]);
                }else{
                    imagettftext($image, 17, 0,45 ,$rewards_height_firtst, $basicTxtColorWrt,$font2,$rewards[0]);
                }


                //---------职位信息------
                $x = 45;
                $y = 523;
                $d = 217;

                foreach($job as $k=>$v){
                    if($k==2){
                        $d= 213;
                    }
                    if($v['degree_id']){
                        $degree = $this->getDegree($v['degree_id'],'学历不限').'及以上学历';
                    }else{
                        $degree = '学历不限';
                    }
                    if($v['work_year_id']){
                        $work = $this->getWorkYear($v['work_year_id'],'工作经验不限').'经验';
                    }else{
                        $work = '工作经验不限';
                    }
                    if(mb_strlen($v['station'])>7){
                        $v['station'] = mb_substr($v['station'],0,6);
                        imagettftext($image, 23, 0,$x ,$y+$k*$d, $basicTitle,$font,$v['station'].'...');
                    }else{
                        imagettftext($image, 22, 0,$x ,$y+$k*$d, $basicTitle,$font,$v['station']);
                    }

                    imagettftext($image, 17, 0,$x ,$y+40+$k*$d, $basicTxtColorWrt,$font,$this->getKsalary($v['min_salary'],$v['max_salary']));
                    imagettftext($image, 16, 0,$x ,$y+80+$k*$d, $basicTxtColorWrt,$font2,$degree);
                    imagettftext($image, 16, 0,$x ,$y+120+$k*$d, $basicTxtColorWrt,$font2,$work);

                }
                //---------职位信息------

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
//                imagecopyresampled($image, $src, 28, 1156, 20, 20, 108, 108, 240, 245);
                imagecopyresampled($image, $src, 28, 1156, 0, 0, 110, 110, imagesx($src), imagesy($src));
//                imagecopyresampled($image, $image_cover, 66, 1192, 0, 0, 35, 35, 100, 80);

                break;
        }
        if($is_down == 1){
            $service_statistics = new base_service_company_poster_statistics();
            $service_statistics->addPosterStatistics(['job_id'=>0,'company_id'=>$company_id,'poster_type'=>$poster_type,'operate_type'=>1]);
            $dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
            $fileDir = "{$dirPath}poster/";
            array_map('unlink', glob("{$fileDir}*"));
            if (file_exists($fileDir)) {
                unlink($fileDir);
            }
            $s = time();
            $filename = $fileDir."$s.jpg";
            $path_arr = pathinfo($filename);
            if (!is_dir($path_arr['dirname'])) {
                base_lib_BaseUtils::createDir($path_arr['dirname']);
            }
            imagejpeg($image,$filename,100);
            base_lib_BaseUtils::download($filename, "海报$s.jpg");
            unlink($filename);
            exit;
        }
        imagejpeg($image);

        //var_dump($company);
    }

    //下载
    public function pageDown($inPath){
        $path       = base_lib_BaseUtils::sstripslashes($this->getUrlParams($inPath));
        $data['id'] = base_lib_BaseUtils::getStr($path['id'], 'string','');
        $data['poster_type'] = base_lib_BaseUtils::getStr($path['poster_type'], 'string','');
        $data['down']   = true;
        $this->_poster($data);
    }
    public function pagea(){
        $dirPath = base_lib_Constant::SERVER_TEMP_FULL_FOLDER;
        $fileDir = "{$dirPath}resumedown_person/$this->_userid/";
        array_map('unlink', glob("{$fileDir}*"));
        if (file_exists($fileDir)) {
            unlink($fileDir);
        }
        $path_arr = pathinfo($fileDir);
        if (!is_dir($path_arr['dirname'])) {
            base_lib_BaseUtils::createDir($path_arr['dirname']);
        }
        imagejpeg($image,$fileDir.'1212.jpg',100);

        base_lib_BaseUtils::download($fileDir.'1212.jpg', "1.jpg");
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

    public function pageGetCode($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $job_flag     = base_lib_BaseUtils::getStr($pathdata['job_flag'],'string','');
        $company_flag = base_lib_BaseUtils::getStr($pathdata['company_flag'],'string','');
        $poster_type  = base_lib_BaseUtils::getStr($pathdata['poster_type'],'string','2');
        //配置APPID、APPSECRET
        $wx = new SWxApp($this->app_id);

        $ACCESS_TOKEN = $wx->getAccesstoken();
        //width是二维码宽度
        $qcode ="https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=$ACCESS_TOKEN";
        if(!empty($company_flag)){
            $param = json_encode(array("path"=>"pages/companyPro/companyPro?company_flag=$company_flag&pt=$poster_type&s=ewm_all_xx_haibao","width"=>50,"scene"=>"45622","is_hyaline"=>true));
        }else{
            $param = json_encode(array("path"=>"pages/jobDetails/jobDetails?jobflag=$job_flag&pt=$poster_type&s=ewm_all_xx_haibao","width"=>50,"scene"=>"45622","is_hyaline"=>true));
        }
        //POST参数
        $result = $this->httpRequest( $qcode, $param,"POST");

        //生成二维码
        header('Content-Type: image/jpeg');
        echo $result;
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
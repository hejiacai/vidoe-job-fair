<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/23
 * Time: 14:01
 */

class controller_jobexcept extends components_cbasepage
{

    function __construct()
    {
        parent::__construct(true);
    }

    /**
     * 添加期望职位
     * @param $inPath
     * @return mixed
     */
    public function pageAdd($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $station = base_lib_BaseUtils::getStr($pathdata['station'], 'string', '');
        $person_id = $this->_userid;
        if (empty($station)) {
            return $this->jsonMsg(false, '请填写期望职位');
        }
        if (mb_strlen($station) > 10) {
            return $this->jsonMsg(false, '期望职位名称不能超过10个字');
        }
        //查询当前用户的默认简历
        $service_resume = new base_service_person_resume_resume();
        $resume = $service_resume->getDefaultResume($person_id, 'resume_id,person_id,station');
        $jobsorts = [];
        if (!empty($resume)) {
            $resume_id = $resume['resume_id'];
            $service_jobexcept = new base_service_person_resume_jobsortexp();
            $jobsorts = (array)$service_jobexcept->getResumeJobsortexpList($resume_id, 'jobsort')->items;
            if (!empty($jobsorts)) {
                $jobsorts = implode(',', base_lib_BaseUtils::getProperty($jobsorts, 'jobsort'));
            }
        }
        $service_jobexcept = new base_service_person_jobexceptfeedback();
        $params = array(
            'resume_id' => !empty($resume['resume_id']) ? $resume['resume_id'] : null,
            'person_id' => $person_id,
            'station' => $station,
            'jobsort_ids' => $jobsorts
        );
        $re = $service_jobexcept->addExceptStation($params);
        if (!$re) {
            return $this->jsonMsg(false, '提交失败');
        }
        return $this->jsonMsg(true, '提交成功');
    }

    /**
     * 获取
     * @param $inPath
     * @return mixed
     */
    public function pageGetJobsorts($inPath){
        $pathdata = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $station = base_lib_BaseUtils::getStr($pathdata['station'], 'string', '');
        if (empty($station)) {
            return $this->jsonMsg(false, '请填写期望职位');
        }
        $service_jobsort = new base_service_common_jobsort();
        $third_jobsorts = $service_jobsort->getThirdJobsort();
        $jobsort_arr = array();
        foreach($third_jobsorts as $k => $v){
            if (strpos($v['jobsort_name'], $station) !== false) {
                $sub_jobsort = $service_jobsort->getJobsort($v['parent_id']);
                $top_jobsort = $service_jobsort->getJobsort($sub_jobsort['parent_id']);
                $jobsort_arr[$v['jobsort']] = array(
                    'jobsort_name' => $v['jobsort_name'],
                    'jobsort' => $v['jobsort'],
                    'sub_jobsort' => $sub_jobsort['jobsort'],
                    'sub_jobsort_name' => $sub_jobsort['jobsort_name'],
                    'top_jobsort' => $top_jobsort['jobsort'],
                    'top_jobsort_name' => $top_jobsort['jobsort_name']
                );
            }
        }
        return $this->jsonMsg(true, '查询成功', $jobsort_arr);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/30
 * Time: 15:25
 */
class controller_answer extends components_cbasepage {


    function __construct() {
        parent::__construct();
       // $this->_userid='989786';
    }


    public function pageIndex($inPath){
        $path_data = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));

        $common_answer = new base_service_common_answer();
        $_data['answer_one'] = $common_answer->getAllOne();
        $_data['answer_two'] = $common_answer->getAllTwo();
        $_data['answer_three'] = $common_answer->getAllThree();

        return $this->render('./question.html', $_data);
    }

    public function pageAddAnswer($inPath){
        $path_data    = base_lib_BaseUtils::saddslashes($this->getUrlParams($inPath));
        $answer_one   = base_lib_BaseUtils::getStr($path_data['answer_one']);
        $answer_two   = base_lib_BaseUtils::getStr($path_data['answer_two']);
        $answer_three = base_lib_BaseUtils::getStr($path_data['answer_three']);
        $answer_four  = base_lib_BaseUtils::getStr($path_data['answer_four']);
        $answer_other = base_lib_BaseUtils::getStr($path_data['answer_other']);

        if(empty($answer_one)){
            echo json_encode(['status'=>false,'msg'=>'请选择第一题']);
            return;
        }

        if(empty($answer_two)){
            echo json_encode(['status'=>false,'msg'=>'请选择第二题']);
            return;
        }
        if(empty($answer_three)){
            echo json_encode(['status'=>false,'msg'=>'请选择第三题']);
            return;
        }

        if(strpos($answer_three,'06')!== false && mb_strlen($answer_other)>50){
            echo json_encode(['status'=>false,'msg'=>'其他原因最多输入50字']);
            return;
        }
        if(mb_strlen($answer_four)>300){
            echo json_encode(['status'=>false,'msg'=>'第四题最多输入300字']);
            return;
        }

        if(empty($this->_userid)){
            echo json_encode(['status'=>false,'msg'=>'请先登录']);
            return;
        }
        $servcie_company  = new base_service_company_company();
        $service_question = new base_service_company_question();

        $company = $servcie_company->getCompany($this->_userid,1,'company_id,calling_id,property_id,size_id,is_famous');

        $data = [
            'question_1'      =>$answer_one,
            'question_2'      =>$answer_two,
            'question_3'      =>$answer_three,
            'question_4'      =>$answer_four,
            'question_3_other'=>strpos($answer_three,'06')!== false?$answer_other:'',
            'calling_id'      =>$company['calling_id'],
            'property_id'     =>$company['property_id'],
            'size_id'         =>$company['size_id'],
            'is_famous'       =>$company['is_famous'],
            'company_id'      =>$this->_userid,
        ];
        $res = $service_question->checkAddQuestion($this->_userid,$data);

        if($res['status']){
            echo json_encode(['status'=>true,'msg'=>$res['msg']]);
            return;
        }
        echo json_encode(['status'=>false,'msg'=>$res['msg']]);
        return;

    }
}
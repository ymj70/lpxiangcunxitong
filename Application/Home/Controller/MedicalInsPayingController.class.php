<?php

namespace Home\Controller;
class MedicalInsPayingController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "城乡居民医疗保险");
        session("model", "社保缴纳");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function medicalInsPayingIndex()
    {
        $this->display("medicalInsPayingIndex");
    }
    public function choosePayingPeople(){
        if (!IS_POST){
            $this->display("choosePayingPeople");
            return;
        }
        $idcard=I("pos.idcard");
    }
    public function payingPeopleList(){
        $this->display("payingPeopleList");
    }
    public function toMedicalInsPaying(){
        $this->display("toMedicalInsPaying");
    }

}

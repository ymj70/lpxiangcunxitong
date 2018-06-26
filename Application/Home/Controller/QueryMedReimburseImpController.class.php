<?php

namespace Home\Controller;
class QueryMedReimburseImpController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "医疗报销提高查询");
        session("model", "信息查询");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function getIdcardInfo(){
        $this->display("getIdcardInfo");
    }
    public function showResult(){
        $this->display("showResult");
    }
}

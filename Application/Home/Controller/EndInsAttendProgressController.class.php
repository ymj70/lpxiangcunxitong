<?php

namespace Home\Controller;
class EndInsAttendProgressController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "养老参保进度查询");
        session("model", "信息查询");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function attentionMatters(){
        $this->display("attentionMatters");
    }
    public function getIdcardInfo(){
        $this->display("getIdcardInfo");
    }
    public function showResult(){
        $this->display("showResult");
    }
}

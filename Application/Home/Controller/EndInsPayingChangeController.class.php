<?php

namespace Home\Controller;
class EndInsPayingChangeController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "城乡居民养老缴费变更");
        session("model", "社保缴纳");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function index()
    {
        $this->display("index");
    }


}

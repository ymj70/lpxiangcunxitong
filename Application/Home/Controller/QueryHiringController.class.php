<?php

namespace Home\Controller;
class QueryHiringController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "单位招聘查询");
        session("model", "帮村民找工作");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function index(){
        $this->display();
    }

}

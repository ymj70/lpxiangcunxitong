<?php

namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller
{
    public function _initialize()
    {
        if (!is_login()) {
            redirect(U("Public/login"));
        }
        if (empty(session("menustatus"))){
            session("menustatus", 1);
        }
        $userinfo = S(session("username"));
        $departmen = array($userinfo["data"]["departmentId"]);
        $this->assign("departmen", $departmen);
    }

    public function menuStatus()
    {
        $status = session("menustatus");
        if ($status == 2) {
            session("menustatus", 1);
            $this->assign("menustatus", 1);
        } else {
            session("menustatus", 2);
            $this->assign("menustatus", 1);
        }


    }

}
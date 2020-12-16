<?php

namespace app\admin\controller\creditlog;

use app\common\controller\Backend;

/**
 * 征信记录
 *
 * @icon fa fa-circle-o
 */
class Promise extends Backend
{

    /**
     * Paymentlog模型对象
     * @var \app\admin\model\Paymentlog
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Creditlog;

    }

    public function import()
    {
        parent::import();
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $wheres = ['credit_rating'=>['in',['A','AA','AAA','AAA+']]];
            $list = $this->model
                ->where($where)
                ->where($wheres)
                ->order($sort, $order)
                ->paginate($limit);
            foreach ($list as $k => $v){
                $list[$k]['address'] = $v['area'] . ' - ' . $v['country'] . ' - ' .$v['village'];
            }
//            halt($list);
            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

}
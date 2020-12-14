<?php

namespace app\admin\controller\loanenterprise;

use app\common\controller\Backend;

/**
 * 企业借贷信息管理
 *
 * @icon fa fa-circle-o
 */
class Enterprise extends Backend
{
    
    /**
     * Enterprise模型对象
     * @var \app\admin\model\loanenterprise\Enterprise
     */
    protected $model = null;
    protected $noNeedRight = ['getaddress','checkidcard'];
    protected $searchFields = 'nickname,credit_code,deputy_username';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\loanenterprise\Enterprise;

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
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                    
                    ->where($where)
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                $row->visible(['id','nickname','credit_code','deputy_username','phone','loan_price','loan_term']);
                
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 服务站详情
     * @param $ids
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function detail($ids)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $row->pid = \db('service_station')->where(['area'=>$row->area,'country'=>$row->country])->value('name');
        $this->view->assign("row",$row);
        return $this->view->fetch();
    }

}

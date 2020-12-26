<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
use think\Exception;

/**
 * 申请修改贷款信息管理
 *
 * @icon fa fa-circle-o
 */
class Applyedit extends Backend
{
    
    /**
     * Applyedit模型对象
     * @var \app\admin\model\Applyedit
     */
    protected $model = null;
    protected $noNeedRight = ['applyeditshenhe'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Applyedit;

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
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                    ->with(['admin','admins'])
                    ->where($where)
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                $row->visible(['id','apply_id','loan_id','loan_type','admin_id','createtime','updatetime','checktime','status']);
                $row->visible(['admin']);
				$row->getRelation('admin')->visible(['nickname']);
				$row->visible(['admins']);
				$row->getRelation('admins')->visible(['nickname']);

            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 审核申请修改权限
     * @param $ids
     */
    public function applyeditshenhe($ids)
    {
        $ids =$this->request->post('ids') ?? $ids ;
        $params = $this->model->get($ids);
        if ($ids){
            $type =$this->request->post('type');
            Db::startTrans();
            try {
                $data = [
                    "admin_id" => $this->auth->id ,
                    "status" => $type ,
                    "checktime" => time() ,
                ];
                $this->model->save($data,['id'=>$ids]);
                if ($params['loan_type'] == 1){
                    $res = \db('loan_user_info')->where(['id'=>$params['loan_id']])->update(['applyedit_status'=>$type]);
                }elseif($params['loan_type'] ==2){
                    $res = \db('loan_enterprise_info')->where(['id'=>$params['loan_id']])->update(['applyedit_status'=>$type]);
                }
                if ($res === false) throw new Exception('操作异常');
                Db::commit();
                $this->success('操作成功');
            } catch (Exception $e) {
                $this->error($e->getMessage());
                Db::rollback();
            }
        }else{
            $this->error(__('No Results were found'));
        }
    }

}

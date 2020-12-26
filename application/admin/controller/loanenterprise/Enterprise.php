<?php

namespace app\admin\controller\loanenterprise;

use app\admin\library\Auth;
use app\common\controller\Backend;
use think\Db;
use think\Exception;

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
        $bankinfo = $this->model->getbankinfo();
        $this->assign("bankinfo",$bankinfo);
        $this->assignconfig("loanauth",Auth::instance()->check('loanenterprise/enterprise/loancheckstatus'));
        $this->assignconfig("bankauth",Auth::instance()->check('loanenterprise/enterprise/bankcheckstatus'));
        $this->assign('overduereason',$this->model->getOverdueReason());
        $this->assign('loanmodel',$this->model->getLoanModel());
        $this->assign('uppoortype',$this->model->getUppoorType());
        $this->assign('loanstatustype',$this->model->getLoanStatusType());

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
            $service_id = $this->auth->service_id;
            $service_data = \db('service_station')->where(['id'=>$service_id])->field('country,village,type,name')->find();
            $wheres = [];
            if ($service_data['type'] >=3){
                $wheres['loan_bank'] = $service_data['name'];
                $wheres['check_status'] = ['in',[2,3,4]];
            }elseif($service_data['type'] == 2){
                $wheres['village'] = $service_data['village'];
            }elseif($service_data['type'] == 1){
                $wheres['country'] = $service_data['country'];
            }
            $list = $this->model
                    
                    ->where($where)
                    ->where($wheres)
                    ->order($sort, $order)
                    ->paginate($limit);

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                //判断是否逾期
                if ($params['payback_time'] <= $params['loan_endtime']){
                    $params['overduereason'] = null;
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $params['uppoor_type'] = implode($params['uppoor_type'],',');
                    $params['createtime'] = time();
                    $params['admin_id'] = $this->auth->id;
                    $result = $this->model->allowField(true)->save($params);
                    $lastinsid = $this->model->getLastInsID();
                    //还款记录
                    $params['day'] = $this->getdays($params['payback_time'],$params['loan_endtime']);
                    $params['loan_id'] = $lastinsid;
                    $params['type'] = 2;
                    model('PaymentLog')->allowField(true)->save($params);
                    model('Creditlog')->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                //判断是否逾期
                if ($params['payback_time'] <= $params['loan_endtime']){
                    $params['overduereason'] = null;
                }
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }

                    //消除修改权限
                    $result = \db('apply_edit')->where(['id'=>$params['applyedit_id']])->update(['updatetime'=>time()]);

                    $params['applyedit_id'] = null;
                    $params['uppoor_type'] = implode($params['uppoor_type'],',');
                    $params['admin_id'] = $this->auth->id;
                    $result = $row->allowField(true)->save($params);
                    //还款记录
                    $params['day'] = $this->getdays($params['payback_time'],$params['loan_endtime']);
                    $where = ["loan_id"=>$ids,"type"=>2];
                    model('PaymentLog')->allowField(true)->save($params,$where);
                    $isset = model('Creditlog')->where($where)->find();
                    if ($isset){
                        model('Creditlog')->allowField(true)->save($params,$where);
                    }else{
                        model('Creditlog')->allowField(true)->save($params);
                    }

                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 申请修改权限
     * @param $ids
     */
    public function applyedit($ids)
    {
        $ids =$this->request->post('ids') ?? $ids ;
        if ($ids){
            Db::startTrans();
            try {
                $where = $data = [
                    "apply_id" => $this->auth->id ,
                    "loan_id" => $ids ,
                    "loan_type" => 2 ,
                    "updatetime" => null ,
                ];
                $isset = \db('apply_edit')->where($where)->find();
                if ($isset){
                    $applyedit_id = $isset['id'];
                }else{
                    unset($where['updatetime']);
                    $data["createtime"] = time() ;
                    $applyedit_id = \db('apply_edit')->insertGetId($where);
                }
                $res = $this->model->save(['applyedit_id'=>$applyedit_id],['id'=>$ids]);
                if ($res === false) throw new Exception('申请失败');
                Db::commit();
                $this->success('申请成功');
            } catch (Exception $e) {
                $this->error($e->getMessage());
                Db::rollback();
            }

            halt($ids);
        }else{
            $this->error(__('No Results were found'));
        }

    }

    /**
     * 企业贷款详情
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
        //地区
        $row['a_c_v'] = $row['area'].'-'.$row['country'].'-'.$row['village'];
        $this->view->assign("row",$row);
        return $this->view->fetch();
    }

    /**
     * 提交审核贷款信息
     */
    public function loancheckstatus()
    {
        $ids = $this->request->post('ids');
        $res = $this->model->save(['check_status'=>2],['id'=>$ids]);
        if ($res !== false){
            $this->success('提交成功');
        }else{
            $this->success('提交失败');
        }
    }
    /**
     * 银行审核
     */
    public function bankcheckstatus()
    {
        $pms = $this->request->post();
        $res = $this->model->allowField(true)->save($pms,['id'=>$pms['ids']]);
        if ($res !== false){
            if ($pms['check_status'] == 3){
                $service_id = $this->auth->service_id;
                $this->InsertBankPrice($service_id,(float)$pms['price']);
            }
            $this->success('审核成功');
        }else{
            $this->success('审核失败');
        }
    }

}

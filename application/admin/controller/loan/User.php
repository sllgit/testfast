<?php

namespace app\admin\controller\loan;

use app\admin\library\Auth;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 农户借贷信息管理
 *
 * @icon fa fa-circle-o
 */
class User extends Backend
{
    
    /**
     * User模型对象
     * @var \app\admin\model\loan\User
     */
    protected $model = null;

    protected $noNeedRight = ['getaddress','checkidcard'];

    protected $searchFields = 'nickname,phone,idcard';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\loan\User;
        $bankinfo = $this->model->getbankinfo();
        $this->assignconfig("loanauth",Auth::instance()->check('loan/user/loancheckstatus'));
        $this->assignconfig("bankauth",Auth::instance()->check('loan/user/bankcheckstatus'));
        $this->assign("bankinfo",$bankinfo);
        $this->assign('loanstatus',$this->getloanstatus());
        $this->assign("root",$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']);
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
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    //借贷业务号码
                    $params['local_no'] = date("Ymd").rand(9999,9999999);
                    $this->model->allowField(true)->save($params);
                    Db::commit();
                    $this->success();
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
                    $result = $row->allowField(true)->save($params);
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
        //地区
        $row['a_c_v'] = $row['area'].'-'.$row['country'].'-'.$row['village'];
        $this->view->assign("row",$row);
        return $this->view->fetch();
    }

    /**
     * 检测身份证号是否已录入
     * @return array
     * @throws \think\Exception
     */
    public function checkidcard()
    {
        $idcard = $this->request->post('idcard');
        $idcard_count = db('loan_user_info')->where(['idcard'=>$idcard])->count();
        if ($idcard_count > 0){
            return ['code'=>203];
        }else{
            return ["code"=>200];
        }
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
            $this->success('审核成功');
        }else{
            $this->success('审核失败');
        }
    }


}

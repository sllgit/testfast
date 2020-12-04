<?php

namespace app\admin\controller\organization;

use app\common\controller\Backend;
use fast\Tree;
use think\Db;

/**
 * 服务站管理
 *
 * @icon fa fa-circle-o
 */
class Servicestation extends Backend
{
    
    /**
     * Servicestation模型对象
     * @var \app\admin\model\organization\Servicestation
     */
    protected $model = null;
    protected $searchFields = 'name';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\organization\Servicestation;
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
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    if ($params['type'] == ''){
                        $this->error('请选择服务站类型！');
                    }
                    elseif ($params['type'] == 0) {
                        if (empty($params['area'])) {
                            $this->error('请选择行政区域！');
                        }
                        $params['pid'] = -2;
                        $params['country'] = null;
                        $params['village'] = null;
                        $this_isset = \db('service_station')->where(['area'=>$params['area'],'country'=>$params['country'],'village'=>$params['village']])->find();
                        if ($this_isset){
                            $this->error('该区域已存在服务站！');
                        }
                    }
                    elseif ($params['type'] == 2){
                        if (empty($params['area']) || empty($params['country'])){
                            $this->error('请选择行政区域！');
                        }
                        $country_isset = \db('service_station')->where(['area'=>$params['area'],'country'=>$params['country']])->find();
                        if (!$country_isset){
                            $this->error('请先添加对应区域乡级服务站！');
                        }
                        $this_isset = \db('service_station')->where(['area'=>$params['area'],'country'=>$params['country'],'village'=>$params['village']])->find();
                        if ($this_isset){
                            $this->error('该区域已存在服务站！');
                        }
                    }
                    elseif ($params['type'] == 1){
                        $params['village'] = null;
                        $this_isset = \db('service_station')->where(['area'=>$params['area'],'country'=>$params['country']])->find();
                        if ($this_isset){
                            $this->error('该区域已存在服务站！');
                        }
                    }
                    $params['createtime'] = time();
                    $result = $this->model->allowField(true)->save($params);
                    $id = $this->model->getLastInsID();
                    $id_ = intval($id + 100000);
                    $service_id = 'FW'.$id_;
                    $this->model->save(["service_id"=>$service_id],['id'=>$id]);
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
                    if ($params['type'] == ''){
                        $this->error('请选择服务站类型！');
                    }
                    elseif ($params['type'] == 0) {
                        if (empty($params['area'])) {
                            $this->error('请选择行政区域！');
                        }
                        $params['pid'] = -2;
                        $params['country'] = null;
                        $params['village'] = null;
                        $this_isset = \db('service_station')->where(['area'=>$params['area'],'country'=>$params['country'],'village'=>$params['village'],'id'=>['neq'=>$ids]])->find();
                        if ($this_isset){
                            $this->error('该区域已存在服务站！');
                        }
                    }
                    elseif ($params['type'] == 2){
                        if (empty($params['area']) || empty($params['country'])){
                            $this->error('请选择行政区域！');
                        }
                        $country_isset = \db('service_station')->where(['area'=>$params['area'],'country'=>$params['country']])->find();
                        if (!$country_isset){
                            $this->error('请先添加对应区域乡级服务站！');
                        }
                        $this_isset = \db('service_station')->where([
                            'area'=>$params['area'],
                            'country'=>$params['country'],
                            'village'=>$params['village'],
                            'id'=>['neq',$ids]
                        ])->find();
                        if ($this_isset){
                            $this->error('该区域已存在服务站！');
                        }
                    }
                    elseif ($params['type'] == 1){
                        $params['village'] = null;
                        $this_isset = \db('service_station')->where(['area'=>$params['area'],'country'=>$params['country'],'id'=>['neq'=>$ids]])->find();
                        if ($this_isset){
                            $this->error('该区域已存在服务站！');
                        }
                    }
                    $params['service_id'] = 'FW'.intval($ids + 100000);
                    $params['updatetime'] = time();
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
        $row->pid = \db('service_station')->where(['area'=>$row->area,'country'=>$row->country])->value('name');
        $this->view->assign("row",$row);
        return $this->view->fetch();
    }


    /**
     * 查询出来商家名称
     * @return string|\think\response\Json
     */
    public function getbank()
    {
        $model = input("model");
        if ($this->request->request('keyField')) {
            switch ($model) {
                case 'bank':
                    $keyword = input('bank_name');
                    $where = ['status'=>1,'bank_name'=>['like',"%$keyword%"]];
                    break;
                default:
                    $where = [];
                    break;
            }
            return $this->selectDataPage($model,$where);
        }
        return "error";
    }

}

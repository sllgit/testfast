<?php

namespace app\admin\controller\loan;

use app\admin\library\Auth;
use app\common\controller\Backend;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
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
        $this->assign('distribution',$this->model->getDistribution());
        $this->assign('overduereason',$this->model->getOverdueReason());
        $this->assign('loanuse',$this->model->getLoanUse());
        $this->assign('isjoincredit',$this->model->getIsJoincredit());
        $this->assign('loanstatustype',$this->model->getLoanStatusType());

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
                if ($params['loan_use'] != '第一产业'){
                    $params['distribution'] = null;
                }
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $nowtime = time();
                    //借贷业务号码
                    $params['local_no'] = date("Ymd").rand(9999,9999999);
                    $params['createtime'] = $nowtime;
                    $params['admin_id'] = $this->auth->id;
                    $this->model->allowField(true)->save($params);
                    $lastinsid = $this->model->getLastInsID();
                    //还款记录
                    $params['day'] = $this->getdays($params['payback_time'],$params['loan_endtime']);
                    $params['loan_id'] = $lastinsid;
                    $params['type'] = 1;
                    model('PaymentLog')->allowField(true)->save($params);
                    model('Creditlog')->allowField(true)->save($params);
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
                //判断是否逾期
                if ($params['payback_time'] <= $params['loan_endtime']){
                    $params['overduereason'] = null;
                }
                if ($params['loan_use'] != '第一产业'){
                    $params['distribution'] = null;
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
                    $nowtime = time();
                    //消除修改权限
                    $result = \db('apply_edit')->where(['id'=>$params['applyedit_id']])->update(['updatetime'=>$nowtime]);

                    $params['applyedit_id'] = null;
                    $params['applyedit_status'] = 0;
                    $params['updatetime'] = $nowtime;
                    $params['admin_id'] = $this->auth->id;
                    $result = $row->allowField(true)->save($params);
                    //还款记录
                    $params['day'] = $this->getdays($params['payback_time'],$params['loan_endtime']);
                    $where = ["loan_id"=>$ids,"type"=>1];
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
     * 农户贷款详情
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
     * 导入
     */
    public function import()
    {
        $file = $this->request->request('file');
        if (!$file) {
            $this->error(__('Parameter %s can not be empty', 'file'));
        }
        $filePath = ROOT_PATH . DS . 'public' . DS . $file;
        if (!is_file($filePath)) {
            $this->error(__('No results were found'));
        }
        //实例化reader
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);//后缀
        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            $this->error(__('Unknown data format'));
        }
        if ($ext === 'csv') {
            $file = fopen($filePath, 'r');
            $filePath = tempnam(sys_get_temp_dir(), 'import_csv');
            $fp = fopen($filePath, "w");
            $n = 0;
            while ($line = fgets($file)) {
                $line = rtrim($line, "\n\r\0");
                $encoding = mb_detect_encoding($line, ['utf-8', 'gbk', 'latin1', 'big5']);
                if ($encoding != 'utf-8') {
                    $line = mb_convert_encoding($line, 'utf-8', $encoding);
                }
                if ($n == 0 || preg_match('/^".*"$/', $line)) {
                    fwrite($fp, $line . "\n");
                } else {
                    fwrite($fp, '"' . str_replace(['"', ','], ['""', '","'], $line) . "\"\n");
                }
                $n++;
            }
            fclose($file) || fclose($fp);

            $reader = new Csv();
        }
        elseif ($ext === 'xls') {
            $reader = new Xls();
        }
        else {
            $reader = new Xlsx();
        }

        //导入文件首行类型,默认是注释,如果需要使用字段名称请使用name
        $importHeadType = isset($this->importHeadType) ? $this->importHeadType : 'comment';

        $table = $this->model->getQuery()->getTable();
        $database = \think\Config::get('database.database');
        $fieldArr = [];
        $list = db()->query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?", [$table, $database]);
        foreach ($list as $k => $v) {
            if ($importHeadType == 'comment') {
                $fieldArr[$v['COLUMN_COMMENT']] = $v['COLUMN_NAME'];
            } else {
                $fieldArr[$v['COLUMN_NAME']] = $v['COLUMN_NAME'];
            }
        }

        //加载文件
        $insert = [];
        try {
            if (!$PHPExcel = $reader->load($filePath)) {
                $this->error(__('Unknown data format'));
            }
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            $maxColumnNumber = Coordinate::columnIndexFromString($allColumn);//列数
            $fields = [];
            //获取列名称
            for ($currentRow = 1; $currentRow <= 1; $currentRow++) {
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $fields[] = $val;
                }
            }
            //获取值 从第二行获取
            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                $values = [];
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $values[] = is_null($val) ? '' : $val;
                }
                $row = [];
                $temp = array_combine($fields, $values);
                foreach ($temp as $k => $v) {
                    if (isset($fieldArr[$k]) && $k !== '') {
                        $row[$fieldArr[$k]] = $v;
                    }
                }
                //处理数据 地区
                $pid = \db('areas')->where(['name'=>$row['country']])->value('pid');
                switch ($pid){
                    case 0: $row['area'] = $row['country'];unset($row['country']);break;
                    case 1: $row['country'] = $row['country'];unset($row['country']);break;
                    default: break;
                }
                if ($row) {
                    $insert[] = $row;
                }
            }

        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
        if (!$insert) {
            $this->error(__('No rows were updated'));
        }

        try {
            //是否包含admin_id字段
            $has_admin_id = false;
            foreach ($fieldArr as $name => $key) {
                if ($key == 'admin_id') {
                    $has_admin_id = true;
                    break;
                }
            }
            if ($has_admin_id) {
                $auth = Auth::instance();
                foreach ($insert as &$val) {
                    if (!isset($val['admin_id']) || empty($val['admin_id'])) {
                        $val['admin_id'] = $auth->isLogin() ? $auth->id : 0;
                    }
                }
            }

            $this->model->saveAll($insert);
        } catch (PDOException $exception) {
            $msg = $exception->getMessage();
            if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                $msg = "导入失败，包含【{$matches[1]}】的记录已存在";
            };
            $this->error($msg);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success();
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
                    "loan_type" => 1 ,
                    "updatetime" => null ,
                ];
                $isset = \db('apply_edit')->where($where)->find();
                if ($isset){
                    $applyedit_id = $isset['id'];
                }else{
                    unset($where['updatetime']);
                    $where["createtime"] = time() ;
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

}

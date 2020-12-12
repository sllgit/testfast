<?php

namespace app\admin\controller\auth;

use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;
use app\common\controller\Backend;
use fast\Random;
use fast\Tree;
use think\Config;
use think\Db;
use think\Validate;

/**
 * 管理员管理
 *
 * @icon fa fa-users
 * @remark 一个管理员可以有多个角色组,左侧的菜单根据管理员所拥有的权限进行生成
 */
class Admin extends Backend
{

    /**
     * @var \app\admin\model\Admin
     */
    protected $model = null;
    protected $noNeedRight = ['','closeadmin','openadmin'];
    protected $selectpageFields = 'id,username,nickname,avatar';
    protected $searchFields = 'id,username,nickname,phone,email';
    protected $childrenGroupIds = [];
    protected $childrenAdminIds = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Admin');
        $this->childrenAdminIds = $this->auth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds(true);

        $groupList = collection(AuthGroup::where('id', 'in', $this->childrenGroupIds)->select())->toArray();

        Tree::instance()->init($groupList);
        $groupdata = [];
        if ($this->auth->isSuperAdmin()) {
            $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));
            foreach ($result as $k => $v) {
                $groupdata[$v['id']] = $v['name'];
            }
        } else {
            $result = [];
            $groups = $this->auth->getGroups();
            foreach ($groups as $m => $n) {
                $childlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray($n['id']));
                $temp = [];
                foreach ($childlist as $k => $v) {
                    $temp[$v['id']] = $v['name'];
                }
                $result[__($n['name'])] = $temp;
            }
            $groupdata = $result;
        }
        $this->view->assign('groupdata', $groupdata);
        $this->view->assign('servicedatas', $this->getservice());
        $this->assignconfig("admin", ['id' => $this->auth->id]);

    }

    public function getservice()
    {
        //县-全部 乡-乡和村 村-村 银行-银行 担保-担保
        //判断当前登录的角色
        $service_id = $this->auth->service_id;
        $info = \db('service_station')->where(['id'=>$service_id])->field('type,pid')->find();
        $where = [];
        switch ($info['type']){
            case '0':$where = [];break;
            case '1':$where['id|pid'] = $service_id;break;
            default:$where['id'] = $service_id;break;
        }
//        dump($where);
        $groupList = \db('service_station')->where($where)->select();
        $data = $this->generateTree($groupList);
        $data['id'] = $service_id;
//        halt($data);
        return $data;
    }

    function generateTree($array){
        $area = Config::get('site.areas');//默认县城id
//        halt($area);
        //第一步 构造数据
        $items = array();
        foreach($array as $value){
            $items['data'][$value['id']] = $value;
        }
        //第二部 遍历数据 生成树状结构
        $tree = array();
        $tree['other'] = array();
        $tree['area'] = array();
        $tree['data'] = array();
        foreach($items['data'] as $key => $value){
            $items['data'][$key]['son'] = [];
            if(isset($items['data'][$value['pid']])){
                $items['data'][$value['pid']]['son'][] = &$items['data'][$key];
            }else{
                if ($value['pid'] == -1){
                    $tree['other'][] = $value;
                }
                elseif ($value['pid'] == -2 && $value['area'] == $area){
                    $tree['area'] = $value;
                }
                else{
                    $tree['data'][] = &$items['data'][$key];
                }
            }
        }
        return $tree;
    }

    /**
     * 查看
     */
    public function index()
    {

        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
//        $service_id = $this->request->post('service_id') ?? db('service_station')->where(['area'=>Config::get('site.areas'),'country'=>null,'village'=>null])->value('id');
        $service_id = $this->auth->service_id;
        if ($this->request->isAjax()) {
            $page = $this->request->get('page') ?? 2;
            $limit = $this->request->get('limit') ?? 2;
            $id = $this->request->get('id');
            $service_ids = $this->request->get('service_id');
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $childrenGroupIds = $this->childrenGroupIds;
            $groupName = AuthGroup::where('id', 'in', $childrenGroupIds)
                ->column('id,name');
            $authGroupList = AuthGroupAccess::where('group_id', 'in', $childrenGroupIds)
                ->field('uid,group_id')
                ->select();

            $adminGroupName = [];
            foreach ($authGroupList as $k => $v) {
                if (isset($groupName[$v['group_id']])) {
                    $adminGroupName[$v['uid']][$v['group_id']] = $groupName[$v['group_id']];
                }
            }
            $groups = $this->auth->getGroups();
            foreach ($groups as $m => $n) {
                $adminGroupName[$this->auth->id][$n['id']] = $n['name'];
            }

            $where["service_id"] = $service_ids ? $service_ids : $service_id;
            if (!empty($id)) $where['username|nickname'] = ["like","%$id%"];
            $total = $this->model
                ->where($where)
                ->count();

            $list = $this->model
                ->where($where)
                ->page($page,$limit)
                ->select();

            foreach ($list as $k => &$v) {
                $groups = isset($adminGroupName[$v['id']]) ? $adminGroupName[$v['id']] : [];
                $v['groups'] = implode(',', array_keys($groups));
                $v['groups_text'] = implode(',', array_values($groups));
                $list[$k]['service_name'] = db('service_station')->where(['id'=>$v['service_id']])->value('name');
                $v['logintime'] = date("Y-m-d H:i:s",$v['logintime']);
            }
            unset($v);

            $data = json_decode(json_encode($list,256),true);
            $result = array("code"=>0,"msg"=>"请求成功","count" => $total, "data"=>$data);

            return $result;
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a");
            if ($params) {
                //验证手机号
                $iseet = $this->model->where(['phone'=>$params['phone']])->find();
                if ($iseet){
                    $this->error('该账号已存在！');
                }
                if (!Validate::is($params['password'], '\S{6,16}')) {
                    $this->error(__("Please input correct password"));
                }
                $params['salt'] = Random::alnum();
                $params['password'] = md5(md5($params['password']) . $params['salt']);
                $params['avatar'] = '/assets/img/avatar.png'; //设置新管理员默认头像。

                $group = $this->request->post("group/a");
                if (count($group) >= 2){
                    $this->error('所属组别只能选择一个');
                }
                $params['group_id'] = $group[0] ?? $this->error('所属组别必选');

                $result = $this->model->validate('Admin.add')->save($params);
                if ($result === false) {
                    $this->error($this->model->getError());
                }

                //过滤不允许的组别,避免越权
                $group = array_intersect($this->childrenGroupIds, $group);
                $dataset = [];
                foreach ($group as $value) {
                    $dataset[] = ['uid' => $this->model->id, 'group_id' => $value];
                }
                model('AuthGroupAccess')->saveAll($dataset);
                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if (!in_array($row->id, $this->childrenAdminIds)) {
            $this->error(__('You have no permission'));
        }
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a");
            if ($params) {
                //验证手机号
                $iseet = $this->model->where(['phone'=>$params['phone'],'id'=>['neq',$ids]])->find();
                if ($iseet){
                    $this->error('该登录手机号已存在！');
                }
                if ($params['password']) {
                    if (!Validate::is($params['password'], '\S{6,16}')) {
                        $this->error(__("Please input correct password"));
                    }
                    $params['salt'] = Random::alnum();
                    $params['password'] = md5(md5($params['password']) . $params['salt']);
                } else {
                    unset($params['password'], $params['salt']);
                }
                //这里需要针对username和email做唯一验证
                $adminValidate = \think\Loader::validate('Admin');
                $adminValidate->rule([
                    'username' => 'require|regex:\w{3,12}|unique:admin,username,' . $row->id,
                    'email'    => 'require|email|unique:admin,email,' . $row->id,
                    'password' => 'regex:\S{32}',
                ]);

                // 先移除所有权限
                model('AuthGroupAccess')->where('uid', $row->id)->delete();

                $group = $this->request->post("group/a");
                if (count($group) >= 2){
                    $this->error('所属组别只能选择一个');
                }
                $params['group_id'] = $group[0] ?? $this->error('所属组别必选');

                $result = $row->validate('Admin.edit')->save($params);
                if ($result === false) {
                    $this->error($row->getError());
                }

                // 过滤不允许的组别,避免越权
                $group = array_intersect($this->childrenGroupIds, $group);

                $dataset = [];
                foreach ($group as $value) {
                    $dataset[] = ['uid' => $row->id, 'group_id' => $value];
                }
                model('AuthGroupAccess')->saveAll($dataset);
                $this->success();
            }
            $this->error();
        }
        $grouplist = $this->auth->getGroups($row['id']);
        $groupids = [];
        foreach ($grouplist as $k => $v) {
            $groupids[] = $v['id'];
        }
        $row->service_name = db('service_station')->where(['id'=>$row->service_id])->value('name');
        $this->view->assign("row", $row);
        $this->view->assign("groupids", $groupids);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if (!$this->request->isPost()) {
            return ["code"=>203,"data"=>__('Invalid parameters')];
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        if ($ids) {
            $ids = array_intersect($this->childrenAdminIds, array_filter(explode(',', $ids)));
            // 避免越权删除管理员
            $childrenGroupIds = $this->childrenGroupIds;
            $adminList = $this->model->where('id', 'in', $ids)->where('id', 'in', function ($query) use ($childrenGroupIds) {
                $query->name('auth_group_access')->where('group_id', 'in', $childrenGroupIds)->field('uid');
            })->select();
            if ($adminList) {
                $deleteIds = [];
                foreach ($adminList as $k => $v) {
                    $deleteIds[] = $v->id;
                }
                $deleteIds = array_values(array_diff($deleteIds, [$this->auth->id]));
                if ($deleteIds) {
                    $this->model->destroy($deleteIds);
                    model('AuthGroupAccess')->where('uid', 'in', $deleteIds)->delete();
                    return ["code"=>200,"data"=>"删除成功"];
                }
            }
        }
        return ["code"=>203,"data"=>__('You have no permission')];
    }

    /**
     * 关停管理员
     * @internal
     */
    public function closeadmin($ids = "")
    {
        if (!$this->request->isPost()) {
            return ["code"=>203,"data"=>__('Invalid parameters')];
        }
        $ids = $ids ? $ids : $this->request->post("ids");

        $res = $this->model->save(["status"=>"disable"],["id"=>$ids]);
        if ($res === false){
            return ["code"=>203,"data"=>"关停操作异常"];
        }else{
            return ["code"=>200,"data"=>"关停成功"];
        }
    }

    /**
     * 恢复管理员
     * @internal
     */
    public function openadmin($ids = "")
    {
        if (!$this->request->isPost()) {
            return ["code"=>203,"data"=>__('Invalid parameters')];
        }
        $ids = $ids ? $ids : $this->request->post("ids");

        $res = $this->model->save(["status"=>"normal"],["id"=>$ids]);halt($res);
        if ($res === false){
            return ["code"=>203,"data"=>"恢复操作异常"];
        }else{
            return ["code"=>200,"data"=>"恢复成功"];
        }
    }

}

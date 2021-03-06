<?php
/**
 * Created by IntelliJ IDEA.
 * User: clong
 * Date: 18-7-1
 * Time: 上午1:51
 */

declare(strict_types=1);

namespace main\model\impl;

use EasyPhp\util\Util;
use main\db\conn\Mysql;
use main\model\Power;
use main\db\impl;
use \Exception;

class PowerImpl implements Power
{
    //数据库句柄
    private $mysql = null;
    private $pageSize = 20;

    public function __construct()
    {
        $this->mysql = new Mysql();
    }

    /**
     * 增加权限
     * @param array $data
     * @return array
     */
    public function add(array $data): array
    {
        //TODO 过滤数据
        $handle = $this->mysql->pdo;
        $privilege = new impl\PrivilegeImpl($handle);
        try {
            $privilege->insert([
                [
                    'name' => $data['name'],
                    'path' => $data['path'],
                    'info' => $data['info'],
                    'privilege_type' => (int)$data['type']
                ]
            ]);
        } catch (Exception $exception) {
            //TODO 写日志
            $code = $exception->getCode() . time() . Util::random();
            return ['state' => false, 'data' => '增加权限失败!', 'exception' => $code];
        }

        return ['state' => true, 'data' => '添加成功'];
    }

    /**
     * @param $data
     * @return array
     */
    public function update(array $data): array
    {
        $handle = $this->mysql->pdo;
        $privilege = new impl\PrivilegeImpl($handle);
        try {
            $privilege->update([[
                'path' => $data['path'],
                'name' => $data['name'],
                'info' => $data['info'],
                'privilege_type' => $data['type']
            ]], [[
                'privilege_id' => $data['id']
            ]]);
        } catch (Exception $exception) {
            //TODO 写日志
            $code = $exception->getCode() . time() . Util::random();
            return ['state' => false, 'data' => '修改失败', 'exception' => $code];
        }

        return ['state' => true, 'data' => '修改成功'];
    }


    public function delById(int $id): array
    {
        $handle = $this->mysql->pdo;
        $returnData = ['state' => true, 'data' => '删除成功'];
        $privilege = new impl\PrivilegeImpl($handle);

        //TODO 防拿别人的id删除

        //执行删除
        try {
            $privilege->delete([['privilege_id' => $id]]);
        } catch (Exception $e) {
            $returnData = ['state' => false, 'data' => '权限删除失败'];
        }
        return $returnData;
    }

    /**
     * 获取所有权限
     * @return array
     */
    /*public function getAll(): array
    {
        $handle = $this->mysql->pdo;
        $privilege = new impl\PrivilegeImpl($handle);
        try {
            $res = $privilege->getAll();
        } catch (Exception $e) {
            return ['state' => false, 'data' => '获取权限列表失败'];
        }
        return ['state' => true, 'data' => $res];
    }*/


    /**
     * TODO 判断结果正确性
     * @param $id
     * @return mixed
     */
    public function getById(int $id): array
    {
        $handle = $this->mysql->pdo;
        $privilege = new impl\PrivilegeImpl($handle);
        try {
            $res = $privilege->select(['path', 'name', 'info', 'privilege_type'], ['privilege_id' => $id]);
        } catch (Exception $exception) {
            //TODO 写日志
            return ['state' => false, 'data' => '获取权限失败'];
        }
        return ['state' => true, 'data' => $res[0]];
    }

    /**
     * 获取管线类型
     * @return array
     */
    public function getAllType(): array
    {
        $handle = $this->mysql->pdo;
        $type = new impl\TypeImpl($handle);
        try {
            $res = $type->select(['type_id', 'name'], ['category' => 'domain']);
        } catch (Exception $exception) {
            //TODO 写日志
            $code = $exception->getCode() . time() . Util::random();
            return ['state' => false, 'data' => '获取权类型限列表失败', 'exception' => $code];
        }

        return ['state' => true, 'data' => $res];
    }

    /**
     * 获取总页数
     * @return array
     */
    public function totalPage(): array
    {
        $handle = $this->mysql->pdo;
        $privilege = new impl\PrivilegeImpl($handle);
        try {
            $res = ceil($privilege->count() / $this->pageSize);
        } catch (Exception $e) {
            return ['state' => false, 'data' => '获取总页数失败'];
        }
        return ['state' => true, 'data' => $res];
    }

    /**
     * 用于分页
     * @param int $page
     * @return array
     */
    public function getPage(int $page): array
    {
        $handle = $this->mysql->pdo;
        $privilege = new impl\PrivilegeImpl($handle);
        $start = ($page - 1) * $this->pageSize;
        try {
            $res = $privilege->getLimit($start, $this->pageSize);
        } catch (Exception $exception) {
            //TODO 写日志
            $code = $exception->getCode() . time() . Util::random();
            return ['state' => false, 'data' => '获取权限列表失败', 'exception' => $code];
        }
        return ['state' => true, 'data' => $res];
    }
}
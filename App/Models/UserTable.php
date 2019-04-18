<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 23:21
 */

namespace App\Models;

use Core\Db\Model;


class UserTable extends Model
{
    /**
     * 获取一条用户信息
     * @param $id
     * @return mixed
     * @throws \Core\Exception\CoreException
     */
    public function getOneUser($id)
    {
        $where = [
            'id' => ['=', $id]
        ];

        return $this->where($where)
            ->orderBy('id desc')
            ->findOne();
    }
}
<?php

use yii\db\Migration;
use zxf\models\entities\Purview;

class m161103_100246_insertTable_purview extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $model = Purview::findOne(['pv_id' => 1]);
        if (!$model) {
            $this->batchInsert(Purview::tableName(),
                ['pv_id', 'pv_name', 'pv_key', 'pv_status', 'pv_isdel', 'pv_lasttime', 'pv_lastip'],
                [
                    [1,  '后台管理',       'top-sys',                     Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [2,  '默认首页',       'site/index',                  Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [3,  '导航列表',       'menu-nav/index',              Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [4,  '导航添加',       'menu-nav/add',                Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [5,  '导航修改',       'menu-nav/edit',               Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [6,  '导航删除',       'menu-nav/del',                Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [7,  '管理员列表',     'admin/index',                 Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [8,  '管理员添加',     'admin/add',                   Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [9,  '管理员修改',     'admin/edit',                  Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [10, '管理员删除',     'admin/del',                   Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [11, '管理员组列表',   'admin-group/index',           Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [12, '管理员组添加',   'admin-group/add',             Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [13, '管理员组修改',   'admin-group/edit',            Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [14, '管理员组删除',   'admin-group/del',             Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [15, '权限列表',       'purview/index',               Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [16, '权限添加',       'purview/add',                 Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [17, '权限修改',       'purview/edit',                Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [18, '权限删除',       'purview/del',                 Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [19, '权限组列表',     'purview-group/index',         Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [20, '权限组添加',     'purview-group/add',           Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [21, '权限组修改',     'purview-group/edit',          Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [22, '权限组删除',     'purview-group/del',           Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [23, '管理员日志列表', 'admin-log/index',             Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                    [24, '管理员登录日志列表', 'admin-login-log/index',   Purview::STATUS_ON, Purview::DEL_NOT, 1467302400, 2130706433],
                ]
            );
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $model = Purview::findOne(['pv_id' => 24]);
        if ($model && $model->pv_name == '管理员登录日志列表') {
            $this->delete(Purview::tableName(), ['and', ['>=', 'pv_id', 1], ['<=', 'pv_id', 24]]);
        }
    }
}

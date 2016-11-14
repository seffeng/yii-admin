<?php

use yii\db\Migration;
use zxf\models\entities\MenuNav;

class m161103_090526_insertTable_menu_nav extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $model = MenuNav::findOne(['mn_id' => 1]);
        if (!$model) {
            $this->batchInsert(MenuNav::tableName(),
                ['mn_id', 'mn_name', 'mn_url', 'mn_icon', 'mn_type', 'mn_sort', 'mn_pid', 'mn_status', 'mn_isdel', 'mn_lasttime', 'mn_lastip'],
                [
                    [1,  '后台管理',       'top-sys',                    'fa-chrome',    MenuNav::TYPE_TOP,   0, 0,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [2,  '默认首页',       'site/index',                 'fa-home',      MenuNav::TYPE_LEFT,  0, 1,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [3,  '导航列表',       'menu-nav/index',             'fa-navicon',   MenuNav::TYPE_LEFT,  0, 1,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [4,  '导航添加',       'menu-nav/add',               '',             MenuNav::TYPE_OTHER, 0, 3,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [5,  '导航修改',       'menu-nav/edit',              '',             MenuNav::TYPE_OTHER, 0, 3,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [6,  '导航删除',       'menu-nav/del',               '',             MenuNav::TYPE_OTHER, 0, 3,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [7,  '管理员列表',     'admin/index',                'fa-user',      MenuNav::TYPE_LEFT,  0, 1,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [8,  '管理员添加',     'admin/add',                  '',             MenuNav::TYPE_OTHER, 0, 7,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [9,  '管理员修改',     'admin/edit',                 '',             MenuNav::TYPE_OTHER, 0, 7,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [10, '管理员删除',     'admin/del',                  '',             MenuNav::TYPE_OTHER, 0, 7,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [11, '管理员组列表',   'admin-group/index',          'fa-users',     MenuNav::TYPE_LEFT,  0, 1,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [12, '管理员组添加',   'admin-group/add',            '',             MenuNav::TYPE_OTHER, 0, 11, MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [13, '管理员组修改',   'admin-group/edit',           '',             MenuNav::TYPE_OTHER, 0, 11, MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [14, '管理员组删除',   'admin-group/del',            '',             MenuNav::TYPE_OTHER, 0, 11, MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [15, '权限列表',       'purview/index',              'fa-eye',       MenuNav::TYPE_LEFT,  0, 1,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [16, '权限添加',       'purview/add',                '',             MenuNav::TYPE_OTHER, 0, 15, MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [17, '权限修改',       'purview/edit',               '',             MenuNav::TYPE_OTHER, 0, 15, MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [18, '权限删除',       'purview/del',                '',             MenuNav::TYPE_OTHER, 0, 15, MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [19, '权限组列表',     'purview-group/index',        'fa-bullseye',  MenuNav::TYPE_LEFT,  0, 1,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [20, '权限组添加',     'purview-group/add',          '',             MenuNav::TYPE_OTHER, 0, 19, MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [21, '权限组修改',     'purview-group/edit',         '',             MenuNav::TYPE_OTHER, 0, 19, MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [22, '权限组删除',     'purview-group/del',          '',             MenuNav::TYPE_OTHER, 0, 19, MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [23, '管理员日志列表', 'admin-log/index',            'fa-list',      MenuNav::TYPE_LEFT,  0, 1,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
                    [24, '管理员登录日志列表', 'admin-login-log/index',  'fa-list-alt',  MenuNav::TYPE_LEFT,  0, 1,  MenuNav::STATUS_ON, MenuNav::DEL_NOT, 1467302400, 2130706433],
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
        $model = MenuNav::findOne(['mn_id' => 24]);
        if ($model && $model->mn_name == '管理员登录日志列表') {
            $this->delete(MenuNav::tableName(), ['and', ['>=', 'mn_id', 1], ['<=', 'mn_id', 24]]);
        }
    }
}

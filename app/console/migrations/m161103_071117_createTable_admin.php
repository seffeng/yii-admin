<?php

use yii\db\Migration;
use zxf\models\entities\Admin;

class m161103_071117_createTable_admin extends Migration
{
    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableAdmin = Admin::tableName();
        $tableInfo  = $this->getDb()->getTableSchema($tableAdmin);
        if (!$tableInfo) {
            $this->createTable($tableAdmin, [
                'ad_id'       => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'管理员ID[自增]\'',
                'ad_username' => 'VARCHAR(20) NOT NULL COMMENT \'用户名\'',
                'ad_password' => 'VARCHAR(72) NOT NULL COMMENT \'密码\'',
                'adg_id'      => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'管理员组ID\'',
                'pv_ids'      => 'TEXT NOT NULL COMMENT \'操作权限[,分割]\'',
                'pvg_ids'     => 'VARCHAR(255) NOT NULL COMMENT \'操作权限组[,分割]\'',
                'ad_status'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. Admin::STATUS_ON .'\' COMMENT \'状态[1-启用,2-停用]\'',
                'ad_isdel'    => 'TINYINT(1) UNSIGNED UNSIGNED NOT NULL DEFAULT \''. Admin::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'ad_addtime'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'添加时间\'',
                'ad_addip'    => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'添加IP\'',
                'ad_lasttime' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新时间\'',
                'ad_lastip'   => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新IP\'',
                'PRIMARY KEY (`ad_id`)',
                'KEY `adg_id` (`adg_id`)',
                'KEY `ad_username` (`ad_username`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'管理员\'');
        }
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableAdmin = Admin::tableName();
        if ($this->getDb()->getTableSchema($tableAdmin)) {
            $this->dropTable($tableAdmin);
        }
    }
}

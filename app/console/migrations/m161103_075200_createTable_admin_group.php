<?php

use yii\db\Migration;
use zxf\models\entities\AdminGroup;

class m161103_075200_createTable_admin_group extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableAdminGroup = AdminGroup::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableAdminGroup);
        if (!$tableInfo) {
            $this->createTable($tableAdminGroup, [
                'adg_id'       => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'管理员组ID[自增]\'',
                'adg_name'     => 'VARCHAR(50) NOT NULL COMMENT \'管理员组名称\'',
                'adg_status'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. AdminGroup::STATUS_ON.'\' COMMENT \'状态[1-启用,2-停用]\'',
                'adg_isdel'    => 'TINYINT(1) UNSIGNED UNSIGNED NOT NULL DEFAULT \''. AdminGroup::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'adg_addtime'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'添加时间\'',
                'adg_addip'    => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'添加IP\'',
                'adg_lasttime' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新时间\'',
                'adg_lastip'   => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新IP\'',
                'PRIMARY KEY (`adg_id`)',
                'KEY `adg_name` (`adg_name`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'管理员组\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableAdminGroup = AdminGroup::tableName();
        if ($this->getDb()->getTableSchema($tableAdminGroup)) {
            $this->dropTable($tableAdminGroup);
        }
    }
}

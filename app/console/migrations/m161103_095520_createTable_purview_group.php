<?php

use yii\db\Migration;
use zxf\models\entities\PurviewGroup;

class m161103_095520_createTable_purview_group extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableName = PurviewGroup::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableName);
        if (!$tableInfo) {
            $this->createTable($tableName, [
                'pvg_id'       => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'自增ID\'',
                'pvg_name'     => 'VARCHAR(50) NOT NULL COMMENT \'权限组名称\'',
                'pv_ids'       => 'TEXT NOT NULL COMMENT \'权限ID集[,分割]\'',
                'pvg_status'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. PurviewGroup::STATUS_ON .'\' COMMENT \'状态[1-启用,2-停用]\'',
                'pvg_isdel'    => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. PurviewGroup::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'pvg_lasttime' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后时间[时间戳]\'',
                'pvg_lastip'   => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后IP[数字型]\'',
                'PRIMARY KEY (`pvg_id`)',
                'KEY `pvg_name` (`pvg_name`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'管理员权限组表\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableName = PurviewGroup::tableName();
        if ($this->getDb()->getTableSchema($tableName)) {
            $this->dropTable($tableName);
        }
    }
}

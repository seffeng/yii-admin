<?php

use yii\db\Migration;
use zxf\models\entities\Purview;

class m161103_093255_createTable_purview extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableName = Purview::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableName);
        if (!$tableInfo) {
            $this->createTable($tableName, [
                'pv_id'       => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'自增ID\'',
                'pv_name'     => 'VARCHAR(50) NOT NULL COMMENT \'权限名称\'',
                'pv_key'      => 'VARCHAR(100) NOT NULL COMMENT \'权限KEY[唯一]\'',
                'pv_status'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. Purview::STATUS_ON .'\' COMMENT \'状态[1-启用,2-停用]\'',
                'pv_isdel'    => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. Purview::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'pv_lasttime' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后时间[时间戳]\'',
                'pv_lastip'   => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后IP[数字型]\'',
                'PRIMARY KEY (`pv_id`)',
                'KEY `pv_key` (`pv_key`)',
                'KEY `pv_name` (`pv_name`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'管理员权限表\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableName = Purview::tableName();
        if ($this->getDb()->getTableSchema($tableName)) {
            $this->dropTable($tableName);
        }
    }
}

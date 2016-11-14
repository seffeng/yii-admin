<?php

use yii\db\Migration;
use zxf\models\entities\AdminLoginLog;

class m161109_085917_createTable_admin_login_log extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableName = AdminLoginLog::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableName);
        if (!$tableInfo) {
            $this->createTable($tableName, [
                'all_id'       => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'日志ID[自增]\'',
                'ad_id'       => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\'  COMMENT \'管理员ID\'',
                'all_type'     => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'日志类型[AdminLoginLog::TYPE_]\'',
                'all_result'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作结果[1-成功,2-失败]\'',
                'all_content'  => 'VARCHAR(255) NOT NULL COMMENT \'日志内容\'',
                'all_isdel'    => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. AdminLoginLog::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'all_addtime'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作时间\'',
                'all_addip'    => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作IP\'',
                'PRIMARY KEY (`all_id`)',
                'KEY `ad_id` (`ad_id`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'管理员登录日志表\'');
        }
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableName = AdminLoginLog::tableName();
        if ($this->getDb()->getTableSchema($tableName)) {
            $this->dropTable($tableName);
        }
    }
}

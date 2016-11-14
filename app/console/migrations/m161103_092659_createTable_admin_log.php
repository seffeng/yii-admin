<?php

use yii\db\Migration;
use zxf\models\entities\AdminLog;

class m161103_092659_createTable_admin_log extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableName = AdminLog::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableName);
        if (!$tableInfo) {
            $this->createTable($tableName, [
                'al_id'       => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'日志ID[自增]\'',
                'ad_id'       => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\'  COMMENT \'管理员ID\'',
                'tab_key_id'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\'  COMMENT \'对应表ID[ad_id, mn_id, pv_id...]\'',
                'al_type'     => 'SMALLINT(6) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'日志类型[AdminLog::TYPE_]\'',
                'al_result'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作结果[1-成功,2-失败]\'',
                'al_content'  => 'VARCHAR(255) NOT NULL COMMENT \'日志内容\'',
                'al_detail'   => 'TEXT COMMENT \'日志详情[对应修改差异,json数据]\'',
                'al_isdel'    => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. AdminLog::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'al_addtime'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作时间\'',
                'al_addip'    => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作IP\'',
                'PRIMARY KEY (`al_id`)',
                'KEY `ad_id` (`ad_id`)',
                'KEY `al_type` (`al_type`)',
                'KEY `tab_key_id` (`tab_key_id`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'管理员日志表\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableName = AdminLog::tableName();
        if ($this->getDb()->getTableSchema($tableName)) {
            $this->dropTable($tableName);
        }
    }
}

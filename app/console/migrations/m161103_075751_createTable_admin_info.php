<?php

use yii\db\Migration;
use zxf\models\entities\AdminInfo;

class m161103_075751_createTable_admin_info extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableAdminInfo = AdminInfo::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableAdminInfo);
        if (!$tableInfo) {
            $this->createTable($tableAdminInfo, [
                'ai_id'       => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'自增ID\'',
                'ad_id'       => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\'  COMMENT \'管理员ID\'',
                'ai_name'     => 'VARCHAR(50) NOT NULL COMMENT \'姓名\'',
                'ai_phone'    => 'BIGINT(11) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'手机号\'',
                'ai_email'    => 'VARCHAR(100) NOT NULL COMMENT \'邮箱\'',
                'ai_lasttime' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新时间\'',
                'PRIMARY KEY (`ai_id`)',
                'UNIQUE `ad_id` (`ad_id`)',
                'KEY `ai_name` (`ai_name`)',
                'KEY `ai_phone` (`ai_phone`)',
                'KEY `ai_email` (`ai_email`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'管理员信息表\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableAdminInfo = AdminInfo::tableName();
        if ($this->getDb()->getTableSchema($tableAdminInfo)) {
            $this->dropTable($tableAdminInfo);
        }
    }
}

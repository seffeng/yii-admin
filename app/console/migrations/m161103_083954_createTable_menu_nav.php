<?php

use yii\db\Migration;
use zxf\models\entities\MenuNav;

class m161103_083954_createTable_menu_nav extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableName = MenuNav::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableName);
        if (!$tableInfo) {
            $this->createTable($tableName, [
                'mn_id'       => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'菜单ID[自增]\'',
                'mn_name'     => 'VARCHAR(50) NOT NULL COMMENT \'菜单名称\'',
                'mn_url'      => 'VARCHAR(255) NOT NULL COMMENT \'菜单地址\'',
                'mn_icon'     => 'VARCHAR(50) NOT NULL COMMENT \'菜单图标[class]\'',
                'mn_type'     => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. MenuNav::TYPE_OTHER .'\' COMMENT \'菜单类型[1-上导航,2-左导航,3-其他]\'',
                'mn_sort'     => 'SMALLINT(6) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'排序[由小到大优先排序]\'',
                'mn_pid'      => 'BIGINT(20) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'菜单父ID[0-顶级]\'',
                'mn_status'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. MenuNav::STATUS_ON .'\' COMMENT \'状态[1-启用,2-停用]\'',
                'mn_isdel'    => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. MenuNav::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'mn_lasttime' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后时间[时间戳]\'',
                'mn_lastip'   => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后IP[数字型]\'',
                'PRIMARY KEY (`mn_id`)',
                'KEY `mn_pid` (`mn_pid`)',
                'KEY `mn_name` (`mn_name`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'菜单和导航表\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableName = MenuNav::tableName();
        if ($this->getDb()->getTableSchema($tableName)) {
            $this->dropTable($tableName);
        }
    }
}

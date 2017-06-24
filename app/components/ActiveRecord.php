<?php
/**
 * 继承 ActiveRecord
 */

namespace zxf\components;

use Yii;

class ActiveRecord extends \yii\db\ActiveRecord {

    /**
     * 返回数据库链接
     * @date   2017年4月13日
     * @param  string $id 数据库配置ID DEFAULT['db']
     * [
     *     'components'  => [
     *         'db'      => [
     *             'class'       => 'yii\db\Connection',
     *             'dsn'         => 'mysql:host=localhost;dbname=dbname1',
     *             'username'    => 'user',
     *             'password'    => 'pass',
     *             'charset'     => 'utf8',
     *             'tablePrefix' => '',
     *         ],
     *         'db2'      => [
     *             'class'       => 'yii\db\Connection',
     *             'dsn'         => 'mysql:host=localhost;dbname=dbname2',
     *             'username'    => 'user',
     *             'password'    => 'pass',
     *             'charset'     => 'utf8',
     *             'tablePrefix' => '',
     *         ],
     *     ]
     * ];
     * @return object|NULL
     */
    public static function getDb($id=NULL) {
        return Yii::$app->get($id ? $id : 'db');
    }

    /**
     * 返回表内索引
     * @date   2017年4月13日
     * @param  string $table  表名
     * @param  string $schema 数据库名
     * @param  string $id     数据库链接配置
     */
    public static function getIndexes($table, $schema=NULL, $id=NULL) {
        $indexes = self::getDb($id)->createCommand('SHOW INDEXES FROM '. ($schema ? ($schema .'.') : '') . $table)->queryAll();
        $coulumn = [];
        if ($indexes) {
            foreach ($indexes as $val) {
                $coulumn[$val['Key_name']] = $val['Key_name'];
            }
        }
        return $coulumn;
    }

    /**
     * 字段是否索引
     * @date   2017年4月13日
     * @param  string $column 字段
     * @param  string $table  表名
     * @param  string $schema 数据库名
     * @param  string $id     数据库链接配置
     * @return boolean
     */
    public static function isIndex($column, $table, $schema=NULL, $id=NULL) {
        $indexes = self::getIndexes($table, $schema, $id);
        return in_array($column, $indexes);
    }
}
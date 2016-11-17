<?php

namespace zxf\models\entities;

use Yii;
use zxf\components\ActiveRecord;
use zxf\models\queries\PurviewQuery;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;
use yii\helpers\ArrayHelper;

/**
 * 权限
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class Purview extends ActiveRecord {

    /**
     * 启用
     * pv_status
     */
    const STATUS_ON   = 1;
    /**
     * 停用
     * pv_status
     */
    const STATUS_OFF  = 2;
    /**
     * 状态说明
     */
    const STATUS_TEXT = [
        self::STATUS_ON  => '启用',
        self::STATUS_OFF => '停用',
    ];

    /**
     * 已删除
     * pv_isdel
     */
    const DEL_YET = 1;
    /**
     * 未删除
     * pv_isdel
     */
    const DEL_NOT = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return string
     */
    public static function tableName() {
        return '{{%purview}}';
    }

    /**
     * 重写 find()
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @return \zxf\models\queries\PurviewQuery
     */
    public static function find() {
        return Yii::createObject(PurviewQuery::className(), [get_called_class()]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios() {
        return ArrayHelper::merge(parent::scenarios(), [
            ConstService::SCENARIO_SEARCH => ['pv_name', 'add_start_date', 'add_end_date', 'pv_status'],
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\ActiveRecord::attributes()
     */
    public function attributes() {
        return ArrayHelper::merge(parent::attributes(), [
            'add_start_date', 'add_end_date'
        ]);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels() {
        return [
            'pv_id'       => 'ID',
            'pv_name'     => '名称',
            'pv_key'      => 'KEY',
            'pv_status'   => '状态',
            'pv_isdel'    => '是否删除',
            'pv_lasttime' => '时间',
            'pv_lastip'   => 'IP',
            'add_start_date' => '时间',
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::rules()
     */
    public function rules() {
        return [
            [['pv_name', 'pv_key'], 'required', 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['pv_name', 'pv_key'], 'unique', 'filter' => ['pv_isdel' => self::DEL_NOT], 'message' => ConstService::ERROR_RULES_EXISTS],
            [['pv_status', 'pv_isdel'], 'integer', 'message' => ConstService::ERROR_RULES_FORMAT],
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) {
        $this->pv_lasttime = THIS_TIME;
        $this->pv_lastip   = ip2long(FunctionService::getUserIP());
        return parent::beforeSave($insert);
    }
}
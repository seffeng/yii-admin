<?php

namespace zxf\models\entities;

use Yii;
use zxf\components\ActiveRecord;
use zxf\models\queries\PurviewGroupQuery;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;
use yii\helpers\ArrayHelper;
/**
 * 权限组
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class PurviewGroup extends ActiveRecord {

    /**
     * 启用
     * pvg_status
     */
    const STATUS_ON   = 1;
    /**
     * 停用
     * pvg_status
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
     * pvg_isdel
     */
    const DEL_YET = 1;
    /**
     * 未删除
     * pvg_isdel
     */
    const DEL_NOT = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return string
     */
    public static function tableName() {
        return '{{%purview_group}}';
    }

    /**
     * 重新 find()
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @return object|mixed
     */
    public static function find() {
        return Yii::createObject(PurviewGroupQuery::className(), [get_called_class()]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios() {
        return ArrayHelper::merge(parent::scenarios(), [
            ConstService::SCENARIO_SEARCH => ['pvg_name', 'add_start_date', 'add_end_date', 'pvg_status'],
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
            'pvg_id'     => 'ID',
            'pvg_name'   => '名称',
            'pv_ids'     => '权限',
            'pvg_status' => '状态',
            'pvg_isdel'  => '是否删除',
            'pvg_lasttime'   => '时间',
            'pvg_lastip'     => 'IP',
            'add_start_date' => '时间',
        ];
    }

    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert) {
        $this->pvg_lasttime = THIS_TIME;
        $this->pvg_lastip   = ip2long(FunctionService::getUserIP());
        if ($this->pv_ids) $this->pv_ids = ','. trim($this->pv_ids, ',') . ',';
        return parent::beforeSave($insert);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::rules()
     */
    public function rules() {
        return [
            ['pvg_name', 'required', 'message' => ConstService::ERROR_RULES_REQUIRE],
            ['pvg_name', 'unique', 'filter' => ['pvg_isdel' => self::DEL_NOT], 'message' => ConstService::ERROR_RULES_EXISTS],
            [['pvg_status', 'pvg_isdel'], 'integer', 'message' => ConstService::ERROR_RULES_FORMAT],
            ['pv_ids', 'string', 'message' => ConstService::ERROR_RULES_FORMAT],
        ];
    }
}
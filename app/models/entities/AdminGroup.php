<?php

namespace zxf\models\entities;

use Yii;
use zxf\components\ActiveRecord;
use zxf\models\queries\AdminGroupQuery;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;
use yii\helpers\ArrayHelper;
/**
 * 管理员组
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class AdminGroup extends ActiveRecord {

    /**
     * 启用
     * adg_status
     */
    const STATUS_ON   = 1;
    /**
     * 停用
     * adg_status
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
     * adg_isdel
     */
    const DEL_YET = 1;
    /**
     * 未删除
     * adg_isdel
     */
    const DEL_NOT = 2;

    /**
     * 超级管理员组ID
     * adg_id
     */
    const SUPER_ADMIN_GROUP = 1;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return string
     */
    public static function tableName() {
        return '{{%admin_group}}';
    }

    /**
     * 重写 find()
     * @author ZhangXueFeng
     * @date   2016年11月7日
     * @return object|mixed
     */
    public static function find() {
        return Yii::createObject(AdminGroupQuery::className(), [get_called_class()]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\ActiveRecord::attributes()
     */
    public function attributes() {
        return ArrayHelper::merge(parent::attributes(), [
            'add_start_date', 'add_end_date', 'status', 'name'
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels() {
        return [
            'adg_id'       => 'ID',
            'adg_name'     => '名称',
            'adg_status'   => '状态',
            'adg_addtime'  => '添加时间',
            'adg_addip'    => '添加IP',
            'adg_lasttime' => '修改时间',
            'adg_lastip'   => '修改IP',
            'name'    => '名称',
            'status'  => '状态',
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
            ['adg_name', 'required', 'message' => ConstService::ERROR_RULES_REQUIRE],
            ['adg_status', 'integer', 'message' => ConstService::ERROR_RULES_FORMAT],
            ['adg_name', 'unique', 'filter' => ['adg_isdel' => self::DEL_NOT], 'message' => ConstService::ERROR_RULES_EXISTS],
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios() {
        return ArrayHelper::merge(parent::scenarios(), [
            ConstService::SCENARIO_SEARCH => ['username', 'name', 'add_start_date', 'add_end_date', 'status'],
        ]);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) {
        $ipLong = ip2long(FunctionService::getUserIP());
        if ($insert) {
            $this->adg_addtime = THIS_TIME;
            $this->adg_addip   = $ipLong;
        }
        $this->adg_lasttime = THIS_TIME;
        $this->adg_lastip   = $ipLong;
        return parent::beforeSave($insert);
    }
}
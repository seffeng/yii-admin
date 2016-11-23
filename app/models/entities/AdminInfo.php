<?php

namespace zxf\models\entities;

use zxf\components\ActiveRecord;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;
/**
 * 管理员信息
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class AdminInfo extends ActiveRecord {

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return string
     */
    public static function tableName() {
        return '{{%admin_info}}';
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels() {
        return [
            'ai_id'       => 'ID',
            'ad_id'       => '管理员ID',
            'ai_name'     => '姓名',
            'ai_phone'    => '手机',
            'ai_email'    => '邮箱',
            'ai_lasttime' => '时间',
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::rules()
     */
    public function rules() {
        return [
            ['ai_name', 'required', 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['ai_name', 'ai_email'], 'string', 'message' => ConstService::ERROR_RULES_FORMAT],
            ['ai_phone', 'checkAttribute'],
            ['ai_email', 'email', 'message' => ConstService::ERROR_RULES_FORMAT],
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) {
        $this->ai_lasttime = THIS_TIME;
        !$this->ai_phone && $this->ai_phone = 0;
        return parent::beforeSave($insert);
    }

    /**
     * 字段检测
     * @author ZhangXueFeng
     * @date   2016年11月23日
     * @param  string $attribute
     */
    public function checkAttribute($attribute) {
        switch ($attribute) {
            case 'ai_phone' : {
                if (!FunctionService::checkData($this->ai_phone, 'mobile')) {
                    $this->addError($attribute, '手机 格式错误！');
                }
                break;
            }
        }
    }
}
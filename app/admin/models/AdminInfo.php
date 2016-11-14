<?php
/**
 * 管理员资料
 */

namespace appdir\admin\models;

use appdir\admin\components\Model;

class AdminInfo extends Model {

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年1月6日
     * @return string
     */
    public static function tableName() {
        return '{{%admin_info}}';
    }

    /**
     * 字段规则
     * @date   2016-01-06
     * @author ZhangXueFeng
     * @return array
     */
    public function rules() {
        return [
            [['ai_name', 'ai_nickname', 'ai_phone', 'ai_email'], 'string'],
            [['ad_id', 'ai_lasttime'], 'integer'],
        ];
    }

    /**
     * 字段说明
     * @date   2016-01-06
     * @author ZhangXueFeng
     * @return [array]
     */
    public function attributeLabels() {
        return [
            'ai_id'    => 'ID',
            'ad_id'    => '管理员ID',
            'ai_name'  => '姓名',
            'ai_nickname' => '昵称',
            'ai_phone'    => '手机',
            'ai_email'    => '邮箱',
            'ai_lasttime' => '修改时间',
        ];
    }

    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert) {
        $this->ai_lasttime = time();
        return parent::beforeSave($insert);
    }
}
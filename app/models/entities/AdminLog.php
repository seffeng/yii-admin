<?php

namespace zxf\models\entities;

use Yii;
use zxf\web\admin\components\ActiveRecord;
use zxf\models\services\FunctionService;
use yii\helpers\ArrayHelper;
use zxf\models\queries\AdminLogQuery;
use zxf\models\services\ConstService;
/**
 * 管理员操作日志
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class AdminLog extends ActiveRecord {

    /**
     * 添加导航
     * al_type
     */
    const TYPE_ADD_MENU   = 1;
    /**
     * 修改导航
     * al_type
     */
    const TYPE_EDIT_MENU  = 2;
    /**
     * 删除导航
     * al_type
     */
    const TYPE_DEL_MENU   = 3;

    /**
     * 添加管理员
     * al_type
     */
    const TYPE_ADD_ADMIN  = 4;
    /**
     * 修改管理员
     * al_type
     */
    const TYPE_EDIT_ADMIN = 5;
    /**
     * 删除管理员
     * al_type
     */
    const TYPE_DEL_ADMIN  = 6;

    /**
     * 添加管理员组
     * al_type
     */
    const TYPE_ADD_ADMINGROUP  = 7;
    /**
     * 修改管理员组
     * al_type
     */
    const TYPE_EDIT_ADMINGROUP = 8;
    /**
     * 删除管理员组
     * al_type
     */
    const TYPE_DEL_ADMINGROUP  = 9;

    /**
     * 添加权限
     * al_type
     */
    const TYPE_ADD_PURVIEW   = 10;
    /**
     * 修改权限
     * al_type
     */
    const TYPE_EDIT_PURVIEW  = 11;
    /**
     * 删除权限
     * al_type
     */
    const TYPE_DEL_PURVIEW   = 12;

    /**
     * 添加权限组
     * al_type
     */
    const TYPE_ADD_PURVIEWGROUP   = 13;
    /**
     * 修改权限组
     * al_type
     */
    const TYPE_EDIT_PURVIEWGROUP  = 14;
    /**
     * 删除权限组
     * al_type
     */
    const TYPE_DEL_PURVIEWGROUP   = 15;

    /**
     * 成功
     * al_result
     */
    const RESULT_OK    = 1;
    /**
     * 失败
     * al_result
     */
    const RESULT_FAILD = 2;
    /**
     * 结果说明
     */
    const RESULT_TEXT  = [
        self::RESULT_OK    => '成功',
        self::RESULT_FAILD => '失败',
    ];

    /**
     * 已删除
     * al_isdel
     */
    const DEL_YET = 1;
    /**
     * 已删除
     * al_isdel
     */
    const DEL_NOT = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return string
     */
    public static function tableName() {
        return '{{%admin_log}}';
    }

    /**
     * 重写 find()
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @return object|mixed
     */
    public static function find() {
        return Yii::createObject(AdminLogQuery::className(), [get_called_class()]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios() {
        return ArrayHelper::merge(parent::scenarios(), [
            ConstService::SCENARIO_SEARCH => ['username', 'name', 'add_start_date', 'add_end_date', 'result', 'ad_id'],
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\ActiveRecord::attributes()
     */
    public function attributes() {
        return ArrayHelper::merge(parent::attributes(), [
            'add_start_date', 'add_end_date', 'result', 'name', 'username'
        ]);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels() {
        return [
            'al_id'      => 'ID',
            'ad_id'      => '管理员ID',
            'al_result'  => '结果',
            'al_content' => '内容',
            'al_addtime' => '时间',
            'al_addip'   => 'IP',
            'username' => '用户名',
            'name'     => '姓名',
            'result'   => '结果',
            'add_start_date' => '时间',
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) {
        if ($insert) {
            $this->al_addtime = THIS_TIME;
            $this->al_addip   = ip2long(FunctionService::getUserIP());
        }
        return parent::beforeSave($insert);
    }

    /**
     * 关联管理员
     * @author ZhangXueFeng
     * @date   2016年11月4日
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin() {
        return $this->hasOne(Admin::className(), ['ad_id' => 'ad_id']);
    }

    /**
     * 关联管理员资料
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @return \yii\db\ActiveQuery
     */
    public function getAdminInfo() {
        return $this->hasOne(AdminInfo::className(), ['ad_id' => 'ad_id']);
    }
}
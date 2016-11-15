<?php

namespace zxf\models\entities;

use Yii;
use zxf\web\admin\components\ActiveRecord;
use zxf\models\services\FunctionService;
use yii\helpers\ArrayHelper;
use zxf\models\queries\AdminLoginLogQuery;
use zxf\models\services\ConstService;

class AdminLoginLog extends ActiveRecord {

    /**
     * 登录
     * all_type
     */
    const TYPE_LOGIN   = 1;
    /**
     * 登出
     * all_type
     */
    const TYPE_LOGOUT  = 2;

    /**
     * 成功
     * all_result
     */
    const RESULT_OK    = 1;
    /**
     * 失败
     * all_result
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
     * all_isdel
     */
    const DEL_YET = 1;
    /**
     * 已删除
     * all_isdel
     */
    const DEL_NOT = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月9日
     * @return string
     */
    public static function tableName() {
        return '{{%admin_login_log}}';
    }

    /**
     * 重写 find()
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @return object|mixed
     */
    public static function find() {
        return Yii::createObject(AdminLoginLogQuery::className(), [get_called_class()]);
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
            'all_id'      => 'ID',
            'ad_id'       => '管理员',
            'all_result'  => '结果',
            'all_content' => '内容',
            'all_addtime' => '时间',
            'all_addip'   => 'IP',
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
            $this->all_addtime = THIS_TIME;
            $this->all_addip   = ip2long(FunctionService::getUserIP());
        }
        return parent::beforeSave($insert);
    }

    /**
     * 关联管理员
     * @author ZhangXueFeng
     * @date   2016年11月9日
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
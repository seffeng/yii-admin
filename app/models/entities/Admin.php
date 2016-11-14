<?php

namespace zxf\models\entities;

use Yii;
use zxf\models\queries\AdminQuery;
use zxf\web\admin\components\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use zxf\models\services\AdminService;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;
/**
 * 管理员
 * @author ZhangXueFeng
 * @date   2016年11月2日
 */
class Admin extends ActiveRecord implements IdentityInterface {

    /**
     * 启用
     * ad_status
     */
    const STATUS_ON  = 1;
    /**
     * 停用
     * ad_status
     */
    const STATUS_OFF = 2;
    /**
     * 状态说明
     */
    const STATUS_TEXT = [
        self::STATUS_ON  => '启用',
        self::STATUS_OFF => '停用',
    ];

    /**
     * 已删除
     * ad_isdel
     */
    const DEL_YET = 1;
    /**
     * 未删除
     * ad_isdel
     */
    const DEL_NOT = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月2日
     * @return string
     */
    public static function tableName() {
        return '{{%admin}}';
    }

    /**
     * 重写 find()
     * @author ZhangXueFeng
     * @date   2016年11月2日
     * @return \zxf\models\queries\AdminQuery
     */
    public static function find() {
        return Yii::createObject(AdminQuery::className(), [get_called_class()]);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\ActiveRecord::attributes()
     */
    public function attributes() {
        return ArrayHelper::merge(parent::attributes(), [
            'username', 'userpass', 'add_start_date', 'add_end_date', 'status', 'name'
        ]);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::rules()
     */
    public function rules() {
        return [
            [['username', 'userpass'], 'required', 'on' => ConstService::SCENARIO_LOGIN, 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['ad_username', 'ad_password'], 'required', 'on' => [ConstService::SCENARIO_INSERT], 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['ad_username'], 'required', 'on' => [ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['ad_username', 'ad_password', 'pv_ids', 'pvg_ids'], 'string', 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_FORMAT],
            [['ad_username'], 'unique', 'filter' => ['ad_isdel' => self::DEL_NOT], 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_EXISTS],
            [['ad_status', 'adg_id'], 'integer', 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_FORMAT],
            ['ad_password', 'checkAttribute', 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE]],
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels() {
        return [
            'username' => '用户名',
            'userpass' => '密码',
            'ad_id'       => 'ID',
            'ad_username' => '用户名',
            'ad_password' => '密码',
            'adg_id'      => '管理员组',
            'pv_ids'      => '权限',
            'pvg_ids'     => '权限组',
            'ad_status'   => '状态',
            'ad_addtime'  => '添加时间',
            'ad_lasttime' => '修改时间',
            'add_start_date' => '时间',
            'status'         => '状态',
            'name'           => '姓名'
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios() {
        return ArrayHelper::merge(parent::scenarios(), [
            ConstService::SCENARIO_LOGIN  => ['username', 'userpass'],
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
            $this->ad_addtime  = THIS_TIME;
            $this->ad_addip    = $ipLong;
            $this->ad_password = AdminService::encryptPassword($this->ad_password);
        } else {
            if ($this->ad_password && $this->ad_password !== $this->getOldAttribute('ad_password')) {
                $this->ad_password = AdminService::encryptPassword($this->ad_password);
            } else {
                unset($this->ad_password);
            }
        }
        $this->ad_lasttime = THIS_TIME;
        $this->ad_lastip   = $ipLong;
        if ($this->pv_ids) $this->pv_ids   = ','. trim($this->pv_ids, ',') . ',';
        if ($this->pvg_ids) $this->pvg_ids = ','. trim($this->pvg_ids, ',') . ',';
        return parent::beforeSave($insert);
    }

    /**
     *
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  integer $id
     * @return \zxf\models\entities\Admin|NULL
     */
    public static function findIdentity($id) {
        return self::find()->byId($id)->byIsDel()->byStatus()->limit(1)->one();
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\web\IdentityInterface::findIdentityByAccessToken()
     */
    public static function findIdentityByAccessToken($token, $type=NULL) {
        return NULL;
    }

    /**
     * 当前登录用户ID
     * {@inheritDoc}
     * @see \yii\web\IdentityInterface::getId()
     */
    public function getId() {
        return $this->ad_id;
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\web\IdentityInterface::getAuthKey()
     */
    public function getAuthKey() {
        return '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\web\IdentityInterface::validateAuthKey()
     */
    public function validateAuthKey($authKey) {
        return TRUE;
    }

    /**
     * 关联管理员组
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return \yii\db\ActiveQuery
     */
    public function getAdminGroup() {
        return $this->hasOne(AdminGroup::className(), ['adg_id' => 'adg_id']);
    }

    /**
     * 关联管理员信息
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return \yii\db\ActiveQuery
     */
    public function getAdminInfo() {
        return $this->hasOne(AdminInfo::className(), ['ad_id' => 'ad_id']);
    }

    /**
     * 字段检测
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  string $attribute
     */
    public function checkAttribute($attribute) {
        switch ($attribute) {
            case 'ad_password' : {
                if (!FunctionService::checkData($this->ad_password, 'password')) {
                    $this->addError($attribute, '密码 格式错误！');
                }
                break;
            }
        }
    }
}
<?php
/**
 * 权限 models
 */

namespace appdir\admin\models;

use Yii;
use appdir\admin\components\Model;

class Purview extends Model {

    /* pv_status */
    const STATUS_NORMAL = 1;  /* 启用 */
    const STATUS_STOP   = 2;  /* 停用 */
    /* pv_isdel */
    const DEL_NOT       = 0;  /* 未删除 */
    const DEL_YET       = 1;  /* 已删除 */

    /**
     * 状态说明
     * @var array
     */
    public static $statusText = [
        self::STATUS_NORMAL => '启用',
        self::STATUS_STOP   => '停用',
    ];

    /**
     * 返回状态说明
     * @date   2015-12-09
     * @author ZhangXueFeng
     * @param  integer     $status [状态]
     * @return string
     */
    public static function getStatusText($status) {
        if (isset(static::$statusText[$status])) {
            return static::$statusText[$status];
        }
        return '-';
    }

    /**
     * 表名
     * @author ZhangXueFeng
     * @return string
     */
    public static function tableName() {
        return '{{%purview}}';
    }

    /**
     * 字段规则
     * @date   2015-12-28
     * @author ZhangXueFeng
     * @return array
     */
    public function rules() {
        return [
            [['pv_name', 'pv_key'], 'required', 'message' => '请填写{attribute}'],
            [['pv_name', 'pv_key'], 'string'],
            [['pv_name', 'pv_key'], 'check_exists'],
            [['pv_status', 'pv_isdel', 'pv_lasttime', 'pv_lastip'], 'integer'],
        ];
    }

    /**
     * 字段说明
     * @date   2015-12-28
     * @author ZhangXueFeng
     * @return [array]
     */
    public function attributeLabels() {
        return [
            'pv_id'     => 'ID',
            'pv_name'   => '权限名',
            'pv_key'    => 'KEY',
            'pv_status' => '状态',
            'pv_isdel'  => '是否删除',
            'pv_lasttime'   => '时间',
            'pv_lastip'     => 'IP',
        ];
    }

    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert) {
        if ($insert) {
            $this->pv_isdel = self::DEL_NOT;
        }
        $this->pv_lasttime = THIS_TIME;
        $this->pv_lastip   = THIS_IP_LONG;
        return parent::beforeSave($insert);
    }

    /**
     * 检测名称/KEY是否存在
     * @author ZhangXueFeng
     * @param  string $attribute 字段
     */
    public function check_exists($attribute) {
        if($this->pv_isdel == self::DEL_YET) return FALSE;
        $models = [];
        $this->pv_name = trim($this->pv_name);
        $this->pv_key  = trim($this->pv_key);
        if ($attribute == 'pv_name') $models[] = self::find()->where(['pv_name' => $this->pv_name, 'pv_isdel' => self::DEL_NOT])->one();
        if ($attribute == 'pv_key') $models[] = self::find()->where(['pv_key' => $this->pv_key, 'pv_isdel' => self::DEL_NOT])->one();
        if (is_foreach($models)) foreach ($models as $model) {
            if ($model && $model->pv_id != $this->pv_id) {
                $attributeLabel = $this->attributeLabels();
                $message = isset($attributeLabel[$attribute]) ? $attributeLabel[$attribute] : '该项';
                $this->addError($attribute, $message.'已经存在');
                break;
            }
        }
    }

    /**
     * 查询所有有效权限
     * @author ZhangXueFeng
     * @date   2016年1月5日
     * @param  boolean $index 是否返回['pv_id' => 'pv_name']结构
     * @return array
     */
    public static function getValidPurview($index=FALSE) {
        $result = self::find()->where(['pv_isdel' => self::DEL_NOT, 'pv_status' => self::STATUS_NORMAL])->all();
        $return = [];
        if (is_foreach($result)) foreach ($result as $val) {
            if ($index) {
                $return[$val->pv_id] = $val->pv_name;
            } else {
                $return[$val->pv_id] = $val;
            }
        }
        return $return;
    }

    /**
     * 权限检查
     * @author ZhangXueFeng
     * @date   2016年1月6日
     * @param  string $controller  控制器
     * @param  string $action      操作
     * @return boolean
     */
    public static function check_purview($controller, $action) {
        if (\Yii::$app->user->identity->adg_id == Admin::SUPER_ADMIN_GROUP) return TRUE;
        $purview = Admin::getAdminPurview();
        if (isset($purview['key']) && in_array($controller .'/'. $action, $purview['key'])) return TRUE;
        return FALSE;
    }
}
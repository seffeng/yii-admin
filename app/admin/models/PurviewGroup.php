<?php
/**
 * 权限 models
 */

namespace appdir\admin\models;

use Yii;
use appdir\admin\components\Model;

class PurviewGroup extends Model {

    /* pvg_status */
    const STATUS_NORMAL = 1;  /* 启用 */
    const STATUS_STOP   = 2;  /* 停用 */
    /* pvg_isdel */
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
     * @date   2015-12-29
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
        return '{{%purview_group}}';
    }

    /**
     * 字段规则
     * @date   2015-12-28
     * @author ZhangXueFeng
     * @return array
     */
    public function rules() {
        return [
            [['pvg_name'], 'required', 'message' => '请填写{attribute}'],
            [['pvg_name'], 'check_exists'],
            [['pv_ids'], 'string'],
            [['pvg_status', 'pvg_isdel', 'pvg_lasttime', 'pvg_lastip'], 'integer'],
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
            'pvg_id'     => 'ID',
            'pvg_name'   => '权限组',
            'pv_ids'     => '权限',
            'pvg_status' => '状态',
            'pvg_isdel'  => '是否删除',
            'pvg_lasttime'   => '时间',
            'pvg_lastip'     => 'IP',
        ];
    }

    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert) {
        if ($insert) {
            $this->pvg_isdel = self::DEL_NOT;
        }
        $this->pvg_lasttime = THIS_TIME;
        $this->pvg_lastip   = THIS_IP_LONG;
        if ($this->pv_ids) $this->pv_ids = ','. trim($this->pv_ids, ',') . ',';
        return parent::beforeSave($insert);
    }

    /**
     * 检测名称/KEY是否存在
     * @author ZhangXueFeng
     * @param  string $attribute 字段
     */
    public function check_exists($attribute) {
        if($this->pvg_isdel == self::DEL_YET) return FALSE;
        $models = [];
        $this->pvg_name = trim($this->pvg_name);
        if ($attribute == 'pvg_name') $models[] = self::find()->where(['pvg_name' => $this->pvg_name, 'pvg_isdel' => self::DEL_NOT])->one();
        if (is_foreach($models)) foreach ($models as $model) {
            if ($model && $model->pvg_id != $this->pvg_id) {
                $attributeLabel = $this->attributeLabels();
                $message = isset($attributeLabel[$attribute]) ? $attributeLabel[$attribute] : '该项';
                $this->addError($attribute, $message.'已经存在');
                break;
            }
        }
    }

    /**
     * 查询所有有效权限组
     * @author ZhangXueFeng
     * @date   2016年1月5日
     * @param  boolean $index 是否返回['pvg_id' => 'pvg_name']结构
     * @return array
     */
    public static function getValidPurviewGroup($index=FALSE) {
        $result = self::find()->where(['pvg_isdel' => self::DEL_NOT, 'pvg_status' => self::STATUS_NORMAL])->all();
        $return = [];
        if (is_foreach($result)) foreach ($result as $val) {
            if ($index) {
                $return[$val->pvg_id] = $val->pvg_name;
            } else {
                $return[$val->pvg_id] = $val;
            }
        }
        return $return;
    }
}
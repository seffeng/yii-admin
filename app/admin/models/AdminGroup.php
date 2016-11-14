<?php
/**
 * 管理员组类
 */

namespace appdir\admin\models;

use appdir\admin\components\Model;

class AdminGroup extends Model {

    /* adg_status */
    const STATUS_NORMAL = 1;  /* 启用 */
    const STATUS_STOP   = 2;  /* 停用 */
    /* adg_isdel */
    const DEL_NOT       = 0;  /* 未删除 */
    const DEL_YET       = 1;  /* 已删除 */

    /**
     * 状态说明
     * @var unknown
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
        return '{{%admin_group}}';
    }

    /**
     * 字段说明
     * @date   2015-12-12
     * @author ZhangXueFeng
     * @return [array]
     */
    public function attributeLabels() {
        return [
            'adg_id'    => 'ID',
            'adg_name'  => '组名',
            'pv_ids'    => '权限',
            'pvg_ids'   => '权限组',
            'adg_status'  => '状态',
            'adg_isdel'   => '是否删除',
            'adg_lasttime'=> '时间',
            'adg_lastip'  => 'IP',
        ];
    }

    /**
     * 字段规则
     * @date   2015-12-29
     * @author ZhangXueFeng
     * @return array
     */
    public function rules() {
        return [
            [['adg_name'], 'required', 'message' => '请填写{attribute}'],
            [['adg_name', 'pv_ids', 'pvg_ids'], 'string'],
            [['adg_name'], 'check_name'],
            [['adg_status', 'adg_isdel', 'adg_lasttime', 'adg_lastip'], 'integer'],
        ];
    }

    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert) {
        if ($insert) {
            $this->adg_isdel = self::DEL_NOT;
        }
        $this->adg_lasttime = THIS_TIME;
        $this->adg_lastip   = THIS_IP_LONG;
        if ($this->pv_ids) $this->pv_ids = ','. trim($this->pv_ids, ',') . ',';
        if ($this->pvg_ids) $this->pvg_ids = ','. trim($this->pvg_ids, ',') . ',';
        return parent::beforeSave($insert);
    }

    /**
     * 检测组名是否存在
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @param  string $attribute 字段
     */
    public function check_name($attribute) {
        if($this->adg_isdel == self::DEL_YET) return FALSE;
        $this->adg_name = trim($this->adg_name);
        $model = self::find()->where(['adg_name' => $this->adg_name, 'adg_isdel' => self::DEL_NOT])->one();
        if ($model && $model->adg_id != $this->adg_id) {
            $this->addError($attribute, '该组名已经存在');
        }
    }
}
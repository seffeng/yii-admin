<?php

namespace zxf\models\entities;

use Yii;
use zxf\web\admin\components\ActiveRecord;
use zxf\models\queries\MenuNavQuery;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;
use yii\helpers\ArrayHelper;
use zxf\models\services\PurviewService;

/**
 * 导航菜单
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class MenuNav extends ActiveRecord {

    /**
     * 上导航[一级导航]
     * mn_type
     */
    const TYPE_TOP   = 1;
    /**
     * 上导航[二级导航]
     * mn_type
     */
    const TYPE_LEFT  = 2;
    /**
     * 其他 导航[功能菜单]
     * mn_type
     */
    const TYPE_OTHER = 3;
    /**
     * 类型说明
     */
    const TYPE_TEXT  = [
        self::TYPE_TOP   => '一级导航',
        self::TYPE_LEFT  => '二级导航',
        self::TYPE_OTHER => '功能导航',
    ];

    /**
     * 启用
     * mn_status
     */
    const STATUS_ON   = 1;
    /**
     * 停用
     * mn_status
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
     * mn_isdel
     */
    const DEL_YET    = 1;
    /**
     * 未删除
     * mn_isdel
     */
    const DEL_NOT    = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return string
     */
    public static function tableName() {
        return '{{%menu_nav}}';
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels() {
        return [
            'mn_id'   => 'ID',
            'mn_name' => '菜单名称',
            'mn_url'  => '菜单地址',
            'mn_icon' => '菜单图标',
            'mn_type' => '菜单类型',
            'mn_sort' => '排序',
            'mn_pid'  => '父ID',
            'mn_status'   => '状态',
            'mn_isdel'    => '是否删除',
            'mn_lasttime' => '时间',
            'mn_lastip'   => 'IP',
            'add_start_date' => '时间',
        ];
    }

    /**
     * 
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return \zxf\models\queries\MenuNavQuery
     */
    public static function find() {
        return Yii::createObject(MenuNavQuery::className(), [get_called_class()]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios() {
        return ArrayHelper::merge(parent::scenarios(), [
            ConstService::SCENARIO_SEARCH => ['mn_name', 'add_start_date', 'add_end_date', 'mn_status', 'mn_type'],
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
     * @see \yii\base\Model::rules()
     */
    public function rules() {
        return [
            [['mn_name', 'mn_url'], 'required', 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['mn_name', 'mn_url'], 'unique', 'filter' => ['mn_isdel' => self::DEL_NOT], 'message' => ConstService::ERROR_RULES_EXISTS],
            ['mn_icon', 'string', 'message' => ConstService::ERROR_RULES_FORMAT],
            [['mn_type', 'mn_sort', 'mn_pid', 'mn_status', 'mn_isdel'], 'integer', 'message' => ConstService::ERROR_RULES_FORMAT],
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) {
        $this->mn_lasttime = THIS_TIME;
        $this->mn_lastip = ip2long(FunctionService::getUserIP());
        return parent::beforeSave($insert);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::afterSave()
     */
    public function afterSave($insert, $changedAttributes) {
        PurviewService::menuNavSync($this);
    }
}
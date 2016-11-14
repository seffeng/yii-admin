<?php
/**
 * 导航 models
 */

namespace appdir\admin\models;

use Yii;
use yii\helpers\Url;
use appdir\admin\components\Model;
use appdir\admin\models\MenuQuery;

class Menu extends Model {

    /* mn_status */
    const STATUS_NORMAL = 1;  /* 启用 */
    const STATUS_STOP   = 2;  /* 停用 */
    /* mn_type */
    const TYPE_LEFT     = 1;  /* 子导航 */
    const TYPE_TOP      = 2;  /* 父导航 */
    const TYPE_OTHER    = 3;  /* 其他导航 */
    /* mn_isdel */
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
     * 类型说明
     * @var array
     */
    public static $typeText = [
        self::TYPE_TOP   => '一级导航',
        self::TYPE_LEFT  => '二级导航',
        self::TYPE_OTHER => '其他',
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
     * 返回类型说明
     * @date   2015-12-09
     * @author ZhangXueFeng
     * @param  integer     $status [类型]
     * @return string
     */
    public static function getTypeText($type) {
        if (isset(static::$typeText[$type])) {
            return static::$typeText[$type];
        }
        return '-';
    }

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2015年12月8日
     * @return string
     */
    public static function tableName() {
        return '{{%menu_nav}}';
    }

    /**
     * 重载 find
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @return mixed
     */
    public static function find() {
        return new MenuQuery(get_called_class());
    }

    /**
     * 字段规则
     * @date   2015-12-12
     * @author ZhangXueFeng
     * @return array
     */
    public function rules() {
        return [
            [['mn_name', 'mn_type', 'mn_pid', 'mn_status', 'mn_url'], 'required', 'message' => '请填写{attribute}'],
            [['mn_url', 'mn_icon'], 'string'],
            [['mn_type', 'mn_pid', 'mn_status'], 'integer'],
        ];
    }

    /**
     * 字段说明
     * @date   2015-12-12
     * @author ZhangXueFeng
     * @return [array]
     */
    public function attributeLabels() {
        return [
            'mn_id'    => 'ID',
            'mn_name'  => '菜单名称',
            'mn_url'   => '菜单地址',
            'mn_icon'  => '菜单图标',
            'mn_type'  => '菜单类型',
            'mn_pid'   => '父导航',
            'mn_status'=> '状态',
            'mn_sort'  => '排序',
        ];
    }

    /**
     * 保存前操作
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert) {
        if ($insert) {
            $this->mn_lasttime = THIS_TIME;
            $this->mn_lastip = THIS_IP_LONG;
        }
        return parent::beforeSave($insert);
    }

    /**
     * 查询顶级导航
     * @date   2015-12-09
     * @author ZhangXueFeng
     * @return [array]
     */
    public static function get_top() {
        $menu = self::find()->is_del()->by_status(self::STATUS_NORMAL)->by_type(self::TYPE_TOP)->asArray()->all();
        return $menu;
    }

    /**
     * 查询子导航
     * @date   2015-12-09
     * @author ZhangXueFeng
     * @return [array]
     */
    public static function get_left() {
        $menu = self::find()->is_del()->by_status(self::STATUS_NORMAL)->by_type(self::TYPE_LEFT)->asArray()->all();
        return $menu;
    }

    /**
     * 查询导航
     * @date   2015-12-09
     * @author ZhangXueFeng
     * @return [array]
     */
    public static function get_menu() {
        $top = self::get_top();
        $left = self::get_left();
        $purview = Admin::getAdminPurview();
        $menu = [];
        if (is_array($top) && count($top) > 0) foreach($top as $key => $val) {
            if (is_array($left) && count($left) > 0) foreach ($left as $left_key => $left_val) {
                if (\Yii::$app->user->identity->adg_id == Admin::SUPER_ADMIN_GROUP){
                    if ($val['mn_id'] == $left_val['mn_pid']) {
                        !isset($menu[$key]) && $menu[$key] = $val;
                        $left_val['mn_url'] = Url::to([$left_val['mn_url']]);
                        $menu[$key]['list'][] = $left_val;
                        unset($left[$left_key]);
                    }
                } elseif (isset($purview['key']) && in_array($left_val['mn_url'], $purview['key'])) {
                    if ($val['mn_id'] == $left_val['mn_pid']) {
                        !isset($menu[$key]) && $menu[$key] = $val;
                        $left_val['mn_url'] = Url::to([$left_val['mn_url']]);
                        $menu[$key]['list'][] = $left_val;
                        unset($left[$left_key]);
                    }
                }
            }
        }
        return $menu;
    }

    /**
     * [查询有效菜单关联列表]
     * @date   2015-12-27
     * @author ZhangXueFeng
     * @return [array]
     */
    public static function get_relation_menu() {
        $return = array();
        $list = self::find()->where(['mn_isdel' => self::DEL_NOT, 'mn_status' => self::STATUS_NORMAL])->asArray()->all();
        if(is_foreach($list)){
            $return = self::get_by_index(0, $list);
        }
        return $return;
    }

    /**
     * [生成数组层级关系]
     * @date   2015-12-27
     * @author ZhangXueFeng
     * @param  integer  $index 上级导航ID
     * @param  array    $list  导航列表
     * @return array
     */
    public static function get_by_index($index, $list){
        $return = array();
        if(is_foreach($list)) foreach($list as $val){
            if($val['mn_pid'] == $index){
                $tmp = self::get_by_index($val['mn_id'], $list);
                if(is_foreach($tmp)) $val['list'] = $tmp;
                unset($val['mn_pid']);
                $return[] = $val;
            }
        }
        return $return;
    }

    /**
     * 菜单列表生成OPTION
     * @date   2015-12-27
     * @author ZhangXueFeng
     * @param  array      $menu_list 导航关联列表 self::get_relation_menu()
     * @param  integer    $depth     导航级别 default[0-一级]
     * @param  integer    $id        导航ID
     * @return string
     */
    public static function menu_arraytohtml($menu_list, $depth=0, $id=0){
        $html = '';
        foreach($menu_list as $val){
            $html .= '<option value="'.$val['mn_id'].'"'.($id == $val['mn_id'] ? ' selected' : '').'>'.($depth > 0 ? str_repeat('---', $depth) : '').$val['mn_name'].'</option>';
            if(isset($val['list']) && is_foreach($val['list'])){
                $html .= self::menu_arraytohtml($val['list'], $depth+1, $id);
            }
        }
        return $html;
    }

    /**
     * 根据ID查询导航
     * @date   2015-12-27
     * @author ZhangXueFeng
     * @param  integer     $id 导航ID
     * @return mixed
     */
    public static function get_by_id($id) {
        return self::find()->by_ids($id)->is_del()->one();
    }
}
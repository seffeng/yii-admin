<?php

namespace zxf\models\services;

use Yii;
use zxf\models\entities\MenuNav;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use zxf\models\entities\AdminGroup;

/**
 * 导航菜单
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class MenuNavService {

    /**
     * 获取一级导航
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  boolean $isArray 是否返回数组 default[TRUE]
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getMenuTop($isArray=TRUE) {
        $query = MenuNav::find();
        $query->byType()->byStatus()->byIsDel();
        $isArray && $query->asArray();
        return $query->all();
    }

    /**
     * 获取二级导航
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  boolean $isArray 是否返回数组 default[TRUE]
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getMenuLeft($isArray=TRUE) {
        $query = MenuNav::find();
        $query->byType(MenuNav::TYPE_LEFT)->byStatus()->byIsDel();
        $isArray && $query->asArray();
        return $query->all();
    }

    /**
     * 获取导航
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return array
     */
    public static function getMenuNav() {
        $menuTop  = self::getMenuTop();
        $menuLeft = self::getMenuLeft();
        $menuList = [];
        if (is_array($menuTop) && count($menuTop) > 0) {
            $purview = PurviewService::getPurview();
            foreach ($menuTop as $keyTop => $valTop) {
                if (is_array($menuLeft) && count($menuLeft) > 0) {
                    foreach ($menuLeft as $keyLeft => $valLeft) {
                        if (ArrayHelper::getValue(Yii::$app->user, 'identity.adg_id') == AdminGroup::SUPER_ADMIN_GROUP){
                            if ($valTop['mn_id'] == $valLeft['mn_pid']) {
                                !isset($menuList[$keyTop]) && $menuList[$keyTop] = $valTop;
                                $valLeft['mn_url'] = Url::to([$valLeft['mn_url']]);
                                $menuList[$keyTop]['list'][] = $valLeft;
                                unset($menuLeft[$keyLeft]);
                            }
                        } elseif (isset($purview['key']) && in_array($valLeft['mn_url'], $purview['key'])) {
                            if ($valTop['mn_id'] == $valLeft['mn_pid']) {
                                !isset($menuList[$keyTop]) && $menuList[$keyTop] = $valTop;
                                $valLeft['mn_url'] = Url::to([$valLeft['mn_url']]);
                                $menuList[$keyTop]['list'][] = $valLeft;
                                unset($menuLeft[$keyLeft]);
                            }
                        }
                    }
                }
            }
        }
        return $menuList;
    }

    /**
     * 导航列表
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  mixed $form
     * @param  integer $page      当前页码
     * @param  integer $pageSize  每页显示数量
     * @return \zxf\models\services\ActiveDataProvider
     */
    public static function getList($form=NULL, $page=1, $pageSize=10) {
        $query = MenuNav::find();
        if (isset($form->mn_id) && $form->mn_id > 0) {
            $query->byId($form->mn_id);
        }
        if (isset($form->mn_name) && $form->mn_name != '') {
            $query->byName($form->mn_name);
        }
        if (isset($form->mn_type) && $form->mn_type > 0) {
            $query->byType($form->mn_type);
        }
        if (isset($form->mn_status) && $form->mn_status > 0) {
            $query->byStatus($form->mn_status);
        }
        if (isset($form->add_start_date) && $form->add_start_date != '') {
            $query->andWhere(['>=', 'mn_lasttime', strtotime($form->add_start_date)]);
        }
        if (isset($form->add_end_date) && $form->add_end_date != '') {
            $query->andWhere(['<=', 'mn_lasttime', strtotime($form->add_end_date) + 86400]);
        }
        $query->byIsDel();
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page'     => $page - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'attributes' => ['mn_id', 'mn_lasttime'],
                'defaultOrder' => ['mn_id' => SORT_DESC]
            ]
        ]);
    }

    /**
     * 状态说明
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  integer $status
     * @return string
     */
    public static function getStatusText($status) {
        return ArrayHelper::getValue(MenuNav::STATUS_TEXT, $status, '-');
    }

    /**
     * 类型说明
     * @author ZhangXueFeng
     * @date   2016年11月4日
     * @param  integer $type
     * @return string
     */
    public static function getTypeText($type) {
        return ArrayHelper::getValue(MenuNav::TYPE_TEXT, $type, '-');
    }

    /**
     * 查询有效菜单关联列表
     * @author ZhangXueFeng
     * @date   2016年11月4日
     * @return array
     */
    public static function getRelationMenu() {
        $return = [];
        $list = MenuNav::find()->byIsDel()->byStatus()->asArray()->all();
        if(FunctionService::isForeach($list)){
            $return = self::getByIndex(0, $list);
        }
        return $return;
    }

    /**
     * 生成数组层级关系
     * @author ZhangXueFeng
     * @date   2016年11月4日
     * @param  integer  $index 上级导航ID
     * @param  array    $list  导航列表
     * @return array
     */
    public static function getByIndex($index, $list){
        $return = array();
        if(FunctionService::isForeach($list)) foreach($list as $val){
            if($val['mn_pid'] == $index){
                $tmp = self::getByIndex($val['mn_id'], $list);
                if(FunctionService::isForeach($tmp)) $val['list'] = $tmp;
                unset($val['mn_pid']);
                $return[] = $val;
            }
        }
        return $return;
    }

    /**
     * 菜单列表生成OPTION
     * @author ZhangXueFeng
     * @date   2016年11月4日
     * @param  array      $list 导航关联列表 self::getRelationMenu()
     * @param  integer    $depth     导航级别 default[0-一级]
     * @param  integer    $id        导航ID
     * @return string
     */
    public static function menuArrayToHtml($list, $depth=0, $id=0){
        $html = '';
        foreach($list as $val){
            $html .= '<option value="'.$val['mn_id'].'"'.($id == $val['mn_id'] ? ' selected' : '').'>'.($depth > 0 ? str_repeat('---', $depth) : '').$val['mn_name'].'</option>';
            if(isset($val['list']) && FunctionService::isForeach($val['list'])){
                $html .= self::menuArrayToHtml($val['list'], $depth+1, $id);
            }
        }
        return $html;
    }

    /**
     * 根据ID查询
     * @author ZhangXueFeng
     * @date   2016年11月4日
     * @param  integer $id
     * @return \zxf\models\queries\MenuNavQuery
     */
    public static function getById($id) {
        return MenuNav::find()->byId($id)->byIsDel()->limit(1)->one();
    }

    /**
     * 是否启用状态
     * @author ZhangXueFeng
     * @date   2016年11月4日
     * @param  integer $status
     * @return boolean
     */
    public static function statusIsOn($status) {
        return $status == MenuNav::STATUS_ON ? TRUE : FALSE;
    }
}
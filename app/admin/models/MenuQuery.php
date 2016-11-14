<?php
/**
 * 导航查询
 */
namespace appdir\admin\models;

use yii\db\ActiveQuery;
use appdir\admin\models\Menu;

class MenuQuery extends ActiveQuery {

    /**
     * 是否删除
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @param  boolean    $is_del 是否删除 default[FALSE]
     * @return mixed
     */
    public function is_del($is_del=FALSE) {
        return $this->andWhere(['mn_isdel' => intval($is_del)]);
    }

    /**
     * 根据 状态 条件
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @param  integer     $status [状态]
     * @return [Object]
     */
    public function by_status($status=NULL) {
        if ($status === NULL) $status = Menu::STATUS_NORMAL;
        return $this->andWhere(['mn_status' => $status]);
    }

    /**
     * 根据 类型 条件
     * @date   2015-12-09
     * @author ZhangXueFeng
     * @param  integer     $type [类型]
     * @return mixed
     */
    public function by_type($type) {
        return $this->andWhere(['mn_type' => $type]);
    }

    /**
     * 根据 ID 条件
     * @date   2015-12-11
     * @author ZhangXueFeng
     * @param  integer|array     $ids [ID]
     * @return mixed
     */
    public function by_ids($ids) {
        return $this->andWhere(['mn_id' => $ids]);
    }
}
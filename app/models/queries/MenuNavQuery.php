<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\MenuNav;

/**
 * 导航菜单查询条件
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class MenuNavQuery extends ActiveQuery {

    /**
     * 导航ID
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  integer|array $id 导航ID
     * @return \zxf\models\queries\AdminQuery
     */
    public function byId($id) {
        return $this->andWhere(['mn_id' => $id]);
    }

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  boolean $isDel 是否删除 default[FALSE]
     * @return \zxf\models\queries\MenuNavQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? MenuNav::DEL_YET : MenuNav::DEL_NOT;
        return $this->andWhere(['mn_isdel' => $isDel]);
    }

    /**
     * 状态
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  integer $status 状态 default[NULL]
     * @return \zxf\models\queries\MenuNavQuery
     */
    public function byStatus($status=NULL) {
        $status === NULL && $status = MenuNav::STATUS_ON;
        return $this->andWhere(['mn_status' => $status]);
    }

    /**
     * 父ID
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  integer $pid
     * @return \zxf\models\queries\MenuNavQuery
     */
    public function byPid($pid=0) {
        return $this->andWhere(['mn_pid' => $pid]);
    }

    /**
     * 类型
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  integer $type
     * @return \zxf\models\queries\MenuNavQuery
     */
    public function byType($type=NULL) {
        $type === NULL && $type = MenuNav::TYPE_TOP;
        return $this->andWhere(['mn_type' => $type]);
    }

    /**
     * 菜单名
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  string $name
     * @return \zxf\models\queries\MenuNavQuery
     */
    public function byName($name) {
        return $this->andWhere(['mn_name' => $name]);
    }
}
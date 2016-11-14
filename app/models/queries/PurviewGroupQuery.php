<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\PurviewGroup;

class PurviewGroupQuery extends ActiveQuery {

    /**
     * 根据ID查询
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $id
     * @return \zxf\models\queries\PurviewGroupQuery
     */
    public function byId($id) {
        return $this->andWhere(['pvg_id' => $id]);
    }

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  boolean $isDel 是否删除 default[FALSE]
     * @return \zxf\models\queries\PurviewGroupQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? PurviewGroup::DEL_YET : PurviewGroup::DEL_NOT;
        return $this->andWhere(['pvg_isdel' => $isDel]);
    }

    /**
     * 状态
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $status 状态 default[NULL]
     * @return \zxf\models\queries\PurviewGroupQuery
     */
    public function byStatus($status=NULL) {
        $status === NULL && $status = PurviewGroup::STATUS_ON;
        return $this->andWhere(['pvg_status' => $status]);
    }

    /**
     * 权限组名
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  string $name
     * @return \zxf\models\queries\PurviewGroupQuery
     */
    public function byName($name) {
        return $this->andWhere(['pvg_name' => $name]);
    }
}
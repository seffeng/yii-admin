<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\Purview;

class PurviewQuery extends ActiveQuery {

    /**
     * 根据ID查询
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $id
     * @return \zxf\models\queries\PurviewQuery
     */
    public function byId($id) {
        return $this->andWhere(['pv_id' => $id]);
    }

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  boolean $isDel 是否删除 default[FALSE]
     * @return \zxf\models\queries\PurviewQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? Purview::DEL_YET : Purview::DEL_NOT;
        return $this->andWhere(['pv_isdel' => $isDel]);
    }

    /**
     * 状态
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $status 状态 default[NULL]
     * @return \zxf\models\queries\PurviewQuery
     */
    public function byStatus($status=NULL) {
        $status === NULL && $status = Purview::STATUS_ON;
        return $this->andWhere(['pv_status' => $status]);
    }

    /**
     * 权限名
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  string $name
     * @return \zxf\models\queries\PurviewQuery
     */
    public function byName($name) {
        return $this->andWhere(['pv_name' => $name]);
    }
}
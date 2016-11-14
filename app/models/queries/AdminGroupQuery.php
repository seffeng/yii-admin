<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\AdminGroup;

class AdminGroupQuery extends ActiveQuery {

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月7日
     * @param  boolean $isDel
     * @return \zxf\models\queries\AdminGroupQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? AdminGroup::DEL_YET : AdminGroup::DEL_NOT;
        return $this->andWhere(['adg_isdel' => $isDel]);
    }

    /**
     * 状态
     * @author ZhangXueFeng
     * @date   2016年11月7日
     * @param  integer $status
     * @return \zxf\models\queries\AdminGroupQuery
     */
    public function byStatus($status=NULL) {
        $status === NULL && $status = AdminGroup::STATUS_ON;
        return $this->andWhere(['adg_status' => $status]);
    }

    /**
     * 根据ID
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $id
     * @return \zxf\models\queries\AdminGroupQuery
     */
    public function byId($id) {
        return $this->andWhere(['adg_id' => $id]);
    }

    /**
     * 根据组名
     * @author Save.zxf
     * @date   2016年11月10日
     * @param  string $name
     * @return \zxf\models\queries\AdminGroupQuery
     */
    public function byName($name) {
        return $this->andWhere(['adg_name' => $name]);
    }
}
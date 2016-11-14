<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\Admin;
/**
 * 管理员查询条件
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class AdminQuery extends ActiveQuery {

    /**
     * 管理员ID
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  integer|array $id 管理员ID
     * @return \zxf\models\queries\AdminQuery
     */
    public function byId($id) {
        return $this->andWhere(['ad_id' => $id]);
    }

    /**
     * 用户名条件
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  string $username 用户名
     * @return \zxf\models\queries\AdminQuery
     */
    public function byUsername($username) {
        return $this->andWhere(['ad_username' => $username]);
    }

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  boolean $isDel 是否删除 default[FALSE]
     * @return \zxf\models\queries\AdminQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? Admin::DEL_YET : Admin::DEL_NOT;
        return $this->andWhere(['ad_isdel' => $isDel]);
    }

    /**
     * 状态
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  integer $status 状态 default[NULL]
     * @return \zxf\models\queries\AdminQuery
     */
    public function byStatus($status=NULL) {
        $status === NULL && $status = Admin::STATUS_ON;
        return $this->andWhere(['ad_status' => $status]);
    }

    /**
     * 根据姓名
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  string $name
     * @return \zxf\models\queries\AdminQuery
     */
    public function byName($name) {
        return $this->joinWith(['adminInfo' => function($query) use ($name) { $query->andWhere(['ai_name' => $name]); }]);
    }
}
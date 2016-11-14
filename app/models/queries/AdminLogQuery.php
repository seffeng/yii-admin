<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\AdminLog;

class AdminLogQuery extends ActiveQuery {

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  boolean $isDel
     * @return \zxf\models\queries\AdminLogQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? AdminLog::DEL_YET : AdminLog::DEL_NOT;
        return $this->andWhere(['al_isdel' => $isDel]);
    }

    /**
     * 结果
     * @author ZhangXueFeng
     * @date   2016年11月1日
     * @param  integer $status
     * @return \zxf\models\queries\AdminLogQuery
     */
    public function byResult($result=NULL) {
        $result === NULL && $result = AdminLog::RESULT_OK;
        return $this->andWhere(['al_result' => $result]);
    }

    /**
     * 根据ID
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $id
     * @return \zxf\models\queries\AdminLogQuery
     */
    public function byId($id) {
        return $this->andWhere(['al_id' => $id]);
    }

    /**
     * 根据ID
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $id
     * @return \zxf\models\queries\AdminLogQuery
     */
    public function byAdId($ad_id) {
        return $this->andWhere(['ad_id' => $ad_id]);
    }

    /**
     * 根据管理员用户名
     * @author Save.zxf
     * @date   2016年11月11日
     * @param  string $name
     * @return \zxf\models\queries\AdminLogQuery
     */
    public function byUsername($username) {
        return $this->joinWith(['admin' => function($query) use ($username) { $query->andWhere(['ad_username' => $username]); }]);
    }

    /**
     * 根据管理员姓名
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  string $name
     * @return \zxf\models\queries\AdminLogQuery
     */
    public function byName($name) {
        return $this->joinWith(['adminInfo' => function($query) use ($name) { $query->andWhere(['ai_name' => $name]); }]);
    }
}
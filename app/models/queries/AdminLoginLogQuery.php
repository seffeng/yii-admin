<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\AdminLoginLog;

class AdminLoginLogQuery extends ActiveQuery {

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  boolean $isDel
     * @return \zxf\models\queries\AdminLoginLogQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? AdminLoginLog::DEL_YET : AdminLoginLog::DEL_NOT;
        return $this->andWhere(['all_isdel' => $isDel]);
    }
    
    /**
     * 结果
     * @author ZhangXueFeng
     * @date   2016年11月1日
     * @param  integer $status
     * @return \zxf\models\queries\AdminLoginLogQuery
     */
    public function byResult($result=NULL) {
        $result === NULL && $result = AdminLoginLog::RESULT_OK;
        return $this->andWhere(['all_result' => $result]);
    }
    
    /**
     * 根据ID
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $id
     * @return \zxf\models\queries\AdminLoginLogQuery
     */
    public function byId($id) {
        return $this->andWhere(['all_id' => $id]);
    }
    
    /**
     * 根据ID
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $id
     * @return \zxf\models\queries\AdminLoginLogQuery
     */
    public function byAdId($ad_id) {
        return $this->andWhere(['ad_id' => $ad_id]);
    }
    
    /**
     * 根据管理员用户名
     * @author Save.zxf
     * @date   2016年11月11日
     * @param  string $name
     * @return \zxf\models\queries\AdminLoginLogQuery
     */
    public function byUsername($username) {
        return $this->joinWith(['admin' => function($query) use ($username) { $query->andWhere(['ad_username' => $username]); }]);
    }
    
    /**
     * 根据管理员姓名
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  string $name
     * @return \zxf\models\queries\AdminLoginLogQuery
     */
    public function byName($name) {
        return $this->joinWith(['adminInfo' => function($query) use ($name) { $query->andWhere(['ai_name' => $name]); }]);
    }
}
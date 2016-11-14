<?php
/**
 * 管理员查询
 */
namespace appdir\admin\models;

use yii\db\ActiveQuery;

class AdminQuery extends ActiveQuery {

    /**
     * 根据 username 条件
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @param  string     $username [用户名]
     * @return [Object]
     */
    public function byUsername($username) {
        return $this->andWhere(['ad_username' => $username]);
    }

    /**
     * 根据 password 条件
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @param  string     $password [密码]
     * @return [Object]
     */
    public function byPassword($password) {
        return $this->andWhere(['ad_password' => $password]);
    }

    /**
     * 是否删除
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @param  boolean    $is_del [是否删除] default[FALSE]
     * @return [Object]
     */
    public function is_del($is_del=FALSE) {
        return $this->andWhere(['ad_isdel' => intval($is_del)]);
    }

    /**
     * 根据 状态 条件
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @param  integer     $status [状态]
     * @return [Object]
     */
    public function by_status($status=NULL) {
        if ($status === NULL) $status = Admin::STATUS_NORMAL;
        return $this->andWhere(['ad_status' => intval($status)]);
    }

    /**
     * 根据 ID 条件
     * @date   2015-12-28
     * @author ZhangXueFeng
     * @param  integer|array     $ids [ID]
     * @return mixed
     */
    public function by_ids($ids) {
        return $this->andWhere(['ad_id' => $ids]);
    }
}
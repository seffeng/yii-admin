<?php

namespace zxf\models\services;

use yii\data\ActiveDataProvider;
use zxf\models\entities\PurviewGroup;
use yii\helpers\ArrayHelper;

class PurviewGroupService {

    /**
     * 
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  mixed $form
     * @param  integer $page      当前页码
     * @param  integer $pageSize  每页显示数量
     * @return \yii\data\ActiveDataProvider
     */
    public static function getList($form=NULL, $page=1, $pageSize=10) {
        $query = PurviewGroup::find();
        if (isset($form->pvg_id) && $form->pvg_id > 0) {
            $query->byId($form->pvg_id);
        }
        if (isset($form->pvg_name) && $form->pvg_name != '') {
            $query->byName($form->pvg_name);
        }
        if (isset($form->pvg_status) && $form->pvg_status > 0) {
            $query->byStatus($form->pvg_status);
        }
        if (isset($form->add_start_date) && $form->add_start_date != '') {
            $query->andWhere(['>=', 'pvg_lasttime', strtotime($form->add_start_date)]);
        }
        if (isset($form->add_end_date) && $form->add_end_date != '') {
            $query->andWhere(['<=', 'pvg_lasttime', strtotime($form->add_end_date) + 86400]);
        }
        $query->byIsDel();
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page'     => $page - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'attributes' => ['pvg_id', 'pvg_lasttime'],
                'defaultOrder' => ['pvg_id' => SORT_DESC]
            ]
        ]);
    }

    /**
     * 返回状态
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $status
     * @return string
     */
    public static function getStatusText($status) {
        return ArrayHelper::getValue(PurviewGroup::STATUS_TEXT, $status, '-');
    }

    /**
     * 根据ID查询
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $id
     * @return \yii\db\ActiveRecord|NULL
     */
    public static function getById($id) {
        return PurviewGroup::find()->byId($id)->byIsdel()->limit(1)->one();
    }

    /**
     * 是否启用
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $status
     * @return boolean
     */
    public static function statusIsOn($status) {
        return $status == PurviewGroup::STATUS_ON ? TRUE : FALSE;
    }

    /**
     * 根据组ID获取有效的权限ID
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  array $ids
     * @return array
     */
    public static function getValidPvIds($ids) {
        return PurviewGroup::find()->select('pv_ids')->byId($ids)->byIsDel()->byStatus()->column();
    }

    /**
     * 获取所有有效权限组
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  boolean $index 是否[key => value]类型 default[FALSE]
     * @return array
     */
    public static function getValidPurviewGroup($index=FALSE) {
        $models = PurviewGroup::find()->byIsDel()->byStatus()->all();
        $return = [];
        if (FunctionService::isForeach($models)) foreach ($models as $model) {
            if ($index) {
                $return[$model->pvg_id] = $model->pvg_name;
            } else {
                $return[$model->pvg_id] = $model;
            }
        }
        return $return;
    }
}
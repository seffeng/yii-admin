<?php

namespace zxf\models\services;

use zxf\models\entities\AdminGroup;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class AdminGroupService {

    /**
     * 管理员组下拉列表
     * @author ZhangXueFeng
     * @date   2016年11月7日
     * @return array
     */
    public static function getDownList () {
        $return = ['无'];
        $models = AdminGroup::find()->select('adg_id,adg_name')->byIsdel()->byStatus()->all();
        if ($models) {
            foreach ($models as $model) {
                $return[$model->adg_id] = $model->adg_name;
            }
        }
        return $return;
    }

    /**
     * 返回状态
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $status
     * @return string
     */
    public static function getStatusText($status) {
        return ArrayHelper::getValue(AdminGroup::STATUS_TEXT, $status, '-');
    }

    /**
     * 列表
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  mixed $form
     * @param  integer $page      当前页码
     * @param  integer $pageSize  每页显示数量
     * @return \zxf\models\services\ActiveDataProvider
     */
    public static function getList($form=NULL, $page=1, $pageSize=10) {
        $query = AdminGroup::find();
        if (isset($form->adg_id) && $form->adg_id > 0) {
            $query->byId($form->adg_id);
        }
        if (isset($form->name) && $form->name != '') {
            $query->byName($form->name);
        }
        if (isset($form->status) && $form->status > 0) {
            $query->byStatus($form->status);
        }
        if (isset($form->add_start_date) && $form->add_start_date != '') {
            $query->andWhere(['>=', 'adg_addtime', strtotime($form->add_start_date)]);
        }
        if (isset($form->add_end_date) && $form->add_end_date != '') {
            $query->andWhere(['<=', 'adg_addtime', strtotime($form->add_end_date) + 86400]);
        }
        $query->byIsDel();
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page'     => $page - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'attributes' => ['adg_id', 'adg_lasttime'],
                'defaultOrder' => ['adg_id' => SORT_DESC]
            ]
        ]);
    }

    /**
     * 根据ID查询
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $id
     * @return \zxf\models\entities\Admin|\zxf\models\entities\NULL
     */
    public static function getById($id) {
        return AdminGroup::find()->byId($id)->byIsDel()->limit(1)->one();
    }

    /**
     * 是否启用
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $status
     * @return boolean
     */
    public static function statusIsOn($status) {
        return $status == AdminGroup::STATUS_ON ? TRUE : FALSE;
    }
}
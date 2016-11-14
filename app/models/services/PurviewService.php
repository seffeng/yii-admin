<?php

namespace zxf\models\services;

use Yii;
use zxf\models\entities\Purview;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use zxf\models\entities\AdminGroup;
use zxf\models\entities\AdminLog;

class PurviewService {

    /**
     * 权限列表
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  mixed $form
     * @param  integer $page      当前页码
     * @param  integer $pageSize  每页显示数量
     * @return \zxf\models\services\ActiveDataProvider
     */
    public static function getList($form=NULL, $page=1, $pageSize=10) {
        $query = Purview::find();
        if (isset($form->pv_id) && $form->pv_id > 0) {
            $query->byId($form->pv_id);
        }
        if (isset($form->pv_name) && $form->pv_name != '') {
            $query->byName($form->pv_name);
        }
        if (isset($form->pv_status) && $form->pv_status > 0) {
            $query->byStatus($form->pv_status);
        }
        if (isset($form->add_start_date) && $form->add_start_date != '') {
            $query->andWhere(['>=', 'pv_lasttime', strtotime($form->add_start_date)]);
        }
        if (isset($form->add_end_date) && $form->add_end_date != '') {
            $query->andWhere(['<=', 'pv_lasttime', strtotime($form->add_end_date) + 86400]);
        }
        $query->byIsDel();
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page'     => $page - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'attributes' => ['pv_id', 'pv_lasttime'],
                'defaultOrder' => ['pv_id' => SORT_DESC]
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
        return ArrayHelper::getValue(Purview::STATUS_TEXT, $status, '-');
    }

    /**
     * 根据ID查询
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $id
     * @return \yii\db\ActiveRecord|NULL
     */
    public static function getById($id) {
        return Purview::find()->byId($id)->byIsDel()->limit(1)->one();
    }

    /**
     * 是否启用
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  integer $status
     * @return boolean
     */
    public static function statusIsOn($status) {
        return $status == Purview::STATUS_ON ? TRUE : FALSE;
    }

    /**
     * 获取所有有效权限
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  boolean $index 是否[key => value]类型 default[FALSE]
     * @return array
     */
    public static function getValidPurview($index=FALSE) {
        $models = Purview::find()->byIsDel()->byStatus()->all();
        $return = [];
        if (FunctionService::isForeach($models)) foreach ($models as $model) {
            if ($index) {
                $return[$model->pv_id] = $model->pv_name;
            } else {
                $return[$model->pv_id] = $model;
            }
        }
        return $return;
    }

    /**
     * 权限检查
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  string $controller  控制器
     * @param  string $action      操作
     * @return boolean
     */
    public static function checkPurview($controller, $action) {
        if (Yii::$app->user->identity->adg_id == AdminGroup::SUPER_ADMIN_GROUP) return TRUE;
        $purview = self::getPurview();
        if (isset($purview['key']) && in_array($controller .'/'. $action, $purview['key'])) return TRUE;
        return FALSE;
    }

    /**
     * 获取有效的权限
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @param  array $ids
     * @return array
     */
    public static function getValidIdsKeys($ids) {
        $models = Purview::find()->byId($ids)->byIsDel()->byStatus()->all();
        $return = [];
        if (FunctionService::isForeach($models)) foreach ($models as $model) {
            $return['id'][]  = $model->pv_id;
            $return['key'][] = $model->pv_key;
        }
        return $return;
    }

    /**
     * 查询管理员权限
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @return array
     */
    public static function getPurview() {
        $purview = [];
        if (AdminService::isLogin()) {
            $user = Yii::$app->user->identity;
            $pvgIds = $user->pvg_ids;
            $pvgIds = array_unique(array_filter(explode(',', trim($pvgIds))));
            $pvgIds = PurviewGroupService::getValidPvIds($pvgIds);
            $pvIds  = $user->pv_ids;
            if (FunctionService::isForeach($pvgIds)) foreach ($pvgIds as $pvgId) {
                $pvIds .= $pvgId;
            }
            $pvIds = array_unique(array_filter(explode(',', trim($pvIds))));
            return PurviewService::getValidIdsKeys($pvIds);
        }
        return $purview;
    }

    /**
     * 导航修改权限同步修改
     * @author Save.zxf
     * @date   2016年11月13日
     * @param  mixed $menuNav ActiveRecord
     */
    public static function menuNavSync($menuNav) {
        $model  = Purview::find()->byId($menuNav->mn_id)->limit(1)->one();
        $return = ['r' => 0, 'm' => ''];
        $type   = '';
        if ($model) {
            $isChange = FALSE;
            if ($model->pv_name != $menuNav->mn_name) {
                $model->pv_name = $menuNav->mn_name;
                $isChange = TRUE;
            }
            if ($model->pv_key != $menuNav->mn_url) {
                $model->pv_key = $menuNav->mn_url;
                $isChange = TRUE;
            }
            if ($model->pv_status != $menuNav->mn_status) {
                $model->pv_status = $menuNav->mn_status;
                $isChange = TRUE;
            }
            if ($model->pv_isdel != $menuNav->mn_isdel) {
                $model->pv_isdel = $menuNav->mn_isdel;
                $isChange = TRUE;
            }
            if ($isChange) {
                if ($model->save()) {
                    $return['r'] = 1;
                }
                if ($model->pv_isdel == Purview::DEL_YET) {
                    $return['m'] = '权限同步删除：';
                    $type = AdminLog::TYPE_DEL_PURVIEW;
                } else {
                    $return['m'] = '权限同步修改：';
                    $type = AdminLog::TYPE_EDIT_PURVIEW;
                }
            }
        } else {
            $model = new Purview();
            $model->pv_id   = $menuNav->mn_id;
            $model->pv_name = $menuNav->mn_name;
            $model->pv_key  = $menuNav->mn_url;
            $model->pv_status = $menuNav->mn_status;
            if ($model->save()) {
                $return['r'] = 1;
            }
            $type = AdminLog::TYPE_ADD_PURVIEW;
            $return['m'] = '权限同步添加：';
        }
        if ($type != '') {
            $logData = [
                'ad_id'   => Yii::$app->getUser()->getId(),
                'key_id'  => $model->pv_id,
                'type'    => $type,
                'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                'content' => $return['m'].'[pv_name='.$model->pv_name.']'
            ];
            AdminLogService::addLog($logData);
        }
    }
}
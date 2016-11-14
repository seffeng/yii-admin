<?php

namespace zxf\models\services;

use zxf\models\entities\AdminLoginLog;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class AdminLoginLogService {

    /**
     * 日志列表
     * @author ZhangXueFeng
     * @date   2016年11月9日
     * @param  mixed $form 查询条件
     * @param  integer $page      当前页码
     * @param  integer $pageSize  每页显示数量
     * @return \yii\data\ActiveDataProvider
     */
    public static function getList($form=NULL, $page=1, $pageSize=10) {
        $query = AdminLoginLog::find();
        if (isset($form->ad_id) && $form->ad_id > 0) {
            $query->byAdId($form->ad_id);
        }
        if (isset($form->username) && $form->username != '') {
            $query->byUsername($form->username);
        }
        if (isset($form->name) && $form->name != '') {
            $query->byName($form->name);
        }
        if (isset($form->add_start_date) && $form->add_start_date != '') {
            $query->andWhere(['>=', 'all_addtime', strtotime($form->add_start_date)]);
        }
        if (isset($form->add_end_date) && $form->add_end_date != '') {
            $query->andWhere(['<=', 'all_addtime', strtotime($form->add_end_date) + 86400]);
        }
        if (isset($form->result) && $form->result > 0) {
            $query->byResult($form->result);
        }
        $query->byIsDel();
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page'     => $page - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'attributes' => ['all_id', 'all_addtime'],
                'defaultOrder' => ['all_id' => SORT_DESC]
            ]
        ]);
    }
    
    /**
     * 添加日志
     * @author ZhangXueFeng
     * @date   2016年11月9日
     * @param  array $data  ['ad_id' => '', 'type' => 'type', 'result' => 'result', 'content' => 'content']
     * @return boolean
     */
    public static function addLog($data) {
        if (!isset($data['ad_id']) || $data['ad_id'] < 1) return FALSE;
        if (!isset($data['content']) || $data['content'] == '') return FALSE;
        if (!isset($data['type']) || $data['type'] == '') return FALSE;
        if (!isset($data['result']) || !in_array($data['result'], [AdminLoginLog::RESULT_OK, AdminLoginLog::RESULT_FAILD])) return FALSE;
        $model = new AdminLoginLog();
        $model->ad_id       = $data['ad_id'];
        $model->all_type     = $data['type'];
        $model->all_result   = $data['result'];
        $model->all_content  = $data['content'];
        return $model->save();
    }
    
    /**
     * 返回结果说明
     * @author ZhangXueFeng
     * @date   2016年11月9日
     * @param  integer $result
     * @return string
     */
    public static function getResultText($result) {
        return ArrayHelper::getValue(AdminLoginLog::RESULT_TEXT, $result, '-');
    }
}
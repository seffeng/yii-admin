<?php
/**
 * 日志管理
 */

namespace appdir\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use appdir\admin\components\AdminWebController;
use appdir\admin\models\AdminLog;

class AdminLogController extends AdminWebController {

    /**
     * 日志列表
     * @author ZhangXueFeng
     * @date   2015年12月28日
     */
    public function actionIndex() {
        $page_size = Yii::$app->request->get('per-page', 10);
        $query = Adminlog::find()->where(['al_isdel' => Adminlog::DEL_NOT]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $page_size,
            ],
            'sort' => [
                'attributes' => ['al_id', 'al_lasttime'],
                'defaultOrder' => ['al_id' => SORT_DESC]
            ]
        ]);
        $params = [
            'dataProvider' => $dataProvider,
            'breadcrumb' => ['日志列表', '后台管理', '日志列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            die(json_encode($return));
        }
        return $this->render('index', $params);
    }
}
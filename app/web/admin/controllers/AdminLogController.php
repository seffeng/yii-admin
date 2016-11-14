<?php

namespace zxf\web\admin\controllers;

use Yii;
use zxf\web\admin\components\Controller;
use zxf\models\services\AdminLogService;
use zxf\models\entities\AdminLog;
use zxf\models\services\ConstService;

class AdminLogController extends Controller {

    /**
     * 列表
     * @author ZhangXueFeng
     * @date   2016年11月4日
     */
    public function actionIndex() {
        $request = Yii::$app->request;
        $page    = $request->get('page', $request->post('page', 1));
        $formModel = new AdminLog(['scenario' => ConstService::SCENARIO_SEARCH]);
        $formModel->load($request->get());
        $dataProvider = AdminLogService::getList($formModel, $page);
        $params = [
            'formModel'    => $formModel,
            'resultText'   => ['' => '----'] + AdminLog::RESULT_TEXT,
            'dataProvider' => $dataProvider,
            'breadcrumb'   => ['日志列表 ', '后台管理', '日志列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            return $return;
        }
        return $this->render('index', $params);
    }
}
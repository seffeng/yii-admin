<?php

namespace zxf\web\admin\controllers;

use Yii;
use zxf\web\admin\components\Controller;
use zxf\models\services\AdminLoginLogService;
use zxf\models\services\ConstService;
use zxf\models\entities\AdminLoginLog;

class AdminLoginLogController extends Controller {

    /**
     * 列表
     * @author ZhangXueFeng
     * @date   2016年11月9日
     */
    public function actionIndex() {
        $request = Yii::$app->request;
        $page    = $request->get('page', $request->post('page', 1));
        $formModel = new AdminLoginLog(['scenario' => ConstService::SCENARIO_SEARCH]);
        $formModel->load($request->get());
        $dataProvider = AdminLoginLogService::getList($formModel, $page);
        $params = [
            'formModel'    => $formModel,
            'resultText'   => ['' => '----'] + AdminLoginLog::RESULT_TEXT,
            'dataProvider' => $dataProvider,
            'breadcrumb' => ['登录日志列表 ', '后台管理', '登录日志列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            return $return;
        }
        return $this->render('index', $params);
    }
}
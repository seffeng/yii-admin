<?php

namespace zxf\web\admin\controllers;

use Yii;
use zxf\web\admin\components\Controller;
use zxf\models\services\PurviewService;
use zxf\models\entities\Purview;
use zxf\models\services\FunctionService;
use zxf\models\entities\AdminLog;
use zxf\models\services\AdminLogService;
use zxf\models\services\ConstService;
use yii\helpers\Json;

class PurviewController extends Controller {

    /**
     * 列表
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @return array
     */
    public function actionIndex() {
        $request = Yii::$app->request;
        $page    = $request->get('page', $request->post('page', 1));
        $formModel = new Purview(['scenario' => ConstService::SCENARIO_SEARCH]);
        $formModel->load($request->get());
        $dataProvider = PurviewService::getList($formModel, $page);
        $params = [
            'formModel'    => $formModel,
            'statusText'   => ['' => '----'] + Purview::STATUS_TEXT,
            'dataProvider' => $dataProvider,
            'editPurview'  => PurviewService::checkPurview($this->action->controller->id, 'edit'),
            'delPurview'   => PurviewService::checkPurview($this->action->controller->id, 'del'),
            'breadcrumb'   => ['权限列表  '. (PurviewService::checkPurview($this->action->controller->id, 'add') ? '<span class="text-blue text-sm" adm="add" role="button">添加权限</span>' : '') .'<span class="text-red text-sm margin-left-20">权限将在导航添加、修改、删除时自动同步，尽量不要在此页面直接操作！</span>', '后台管理', '权限列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            return $return;
        }
        return $this->render('index', $params);
    }

    /**
     * 添加
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @return array
     */
    public function actionAdd() {
        $model = new Purview();
        $params = [
            'model' => $model,
            'breadcrumb'  => ['添加权限  '. (PurviewService::checkPurview($this->action->controller->id, 'index') ? '<span class="text-blue text-sm" adm="list" role="button">权限列表</span>' : ''), '后台管理', '添加权限'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '添加权限成功！'];
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model)];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->pv_id,
                    'type'    => AdminLog::TYPE_ADD_PURVIEW,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '添加权限:'.$return['m'].'[pv_name='.$model->pv_name.']'
                ];
                AdminLogService::addLog($logData);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('add', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            return $return;
        }
        return $this->render('add', $params);
    }

    /**
     * 修改
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @return array
     */
    public function actionEdit() {
        $id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
        if ($id < 1) {
            return ['r' => 0, 'm' => '参数错误！'];
        }
        $model = PurviewService::getById($id);
        if (!$model) {
            return ['r' => 0, 'm' => '权限不存在！'];
        }
        $params = [
            'model' => $model,
            'breadcrumb'  => ['修改权限  '. (PurviewService::checkPurview($this->action->controller->id, 'index') ? '<span class="text-blue text-sm" adm="list" role="button">权限列表</span>' : ''), '后台管理', '修改权限'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $modelDiff = [];
                if ($model->load($post) && $model->validate()) {
                    $modelDiff = FunctionService::modelDiff($model, ['pv_name', 'pv_key', 'pv_status']);
                    if ($model->save()) {
                        $return = ['r' => 1, 'm' => '修改权限成功！'];
                    } else {
                        $return = ['r' => 0, 'm' => '修改权限失败！'];
                    }
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model)];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->pv_id,
                    'type'    => AdminLog::TYPE_EDIT_PURVIEW,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '修改权限:'.$return['m'].'[pv_name='.$model->pv_name.']',
                    'detail'  => $modelDiff ? Json::encode($modelDiff) : ''
                ];
                AdminLogService::addLog($logData);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('edit', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            return $return;
        }
        return $this->render('edit', $params);
    }

    /**
     * 删除
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @return array|\yii\web\Response
     */
    public function actionDel() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
            if ($id < 1) {
                return ['r' => 0, 'm' => '参数错误！'];
            }
            $model = PurviewService::getById($id);
            $return = ['r' => 0, 'm' => '删除权限失败！'];
            if ($model) {
                $model->pv_isdel = Purview::DEL_YET;
                if ($model->save(FALSE)) {
                    $return = ['r' => 1, 'm' => '删除权限成功！'];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->pv_id,
                    'type'    => AdminLog::TYPE_DEL_PURVIEW,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '删除权限:'.$return['m'].'[pv_name='.$model->pv_name.']'
                ];
                AdminLogService::addLog($logData);
            }
            return $return;
        }
        return $this->goHome();
    }
}
<?php

namespace zxf\web\admin\controllers;

use Yii;
use zxf\web\admin\components\Controller;
use zxf\models\services\PurviewGroupService;
use zxf\models\entities\PurviewGroup;
use zxf\models\entities\AdminLog;
use zxf\models\services\AdminLogService;
use zxf\models\services\FunctionService;
use zxf\models\services\PurviewService;
use zxf\models\services\ConstService;
use yii\helpers\Json;

class PurviewGroupController extends Controller {

    /**
     * 列表
     * @author ZhangXueFeng
     * @date   2016年11月8日
     * @return array
     */
    public function actionIndex() {
        $request = Yii::$app->request;
        $page    = $request->get('page', $request->post('page', 1));
        $formModel = new PurviewGroup(['scenario' => ConstService::SCENARIO_SEARCH]);
        $formModel->load($request->get());
        $dataProvider = PurviewGroupService::getList($formModel, $page);
        $params = [
            'formModel'    => $formModel,
            'statusText'   => ['' => '----'] + PurviewGroup::STATUS_TEXT,
            'dataProvider' => $dataProvider,
            'editPurview'  => PurviewService::checkPurview($this->action->controller->id, 'edit'),
            'delPurview'   => PurviewService::checkPurview($this->action->controller->id, 'del'),
            'breadcrumb'  => ['权限组列表  '. (PurviewService::checkPurview($this->action->controller->id, 'add') ? '<span class="text-blue text-sm" adm="add" role="button">添加权限组</span>' : ''), '后台管理', '权限组列表']
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
        $model = new PurviewGroup();
        $params = [
            'model' => $model,
            'purview'     => PurviewService::getValidPurview(TRUE),
            'breadcrumb'  => ['添加权限组  '. (PurviewService::checkPurview($this->action->controller->id, 'index') ? '<span class="text-blue text-sm" adm="list" role="button">权限组列表</span>' : ''), '后台管理', '添加权限组'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '添加权限组成功！'];
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model)];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->pvg_id,
                    'type'    => AdminLog::TYPE_ADD_PURVIEWGROUP,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '添加权限组:'.$return['m'].'[pvg_name='.$model->pvg_name.']'
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
        $model = PurviewGroupService::getById($id);
        if (!$model) {
            return ['r' => 0, 'm' => '权限组不存在！'];
        }
        $params = [
            'model' => $model,
            'purview'     => PurviewService::getValidPurview(TRUE),
            'breadcrumb'  => ['修改权限组  '. (PurviewService::checkPurview($this->action->controller->id, 'index') ? '<span class="text-blue text-sm" adm="list" role="button">权限组列表</span>' : ''), '后台管理', '修改权限组'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $modelDiff = [];
                if ($model->load($post) && $model->validate()) {
                    $modelDiff = FunctionService::modelDiff($model, ['pvg_name', 'pvg_status']);
                    if ($model->save()) {
                        $return = ['r' => 1, 'm' => '修改权限组成功！'];
                    } else {
                        $return = ['r' => 0, 'm' => '修改权限组失败！'];
                    }
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model)];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->pvg_id,
                    'type'    => AdminLog::TYPE_EDIT_PURVIEWGROUP,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '修改权限组:'.$return['m'].'[pvg_name='.$model->pvg_name.']',
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
            $model = PurviewGroupService::getById($id);
            $return = ['r' => 0, 'm' => '删除权限组失败！'];
            if ($model) {
                $model->pvg_isdel = PurviewGroup::DEL_YET;
                if ($model->save(FALSE)) {
                    $return = ['r' => 1, 'm' => '删除权限组成功！'];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->pvg_id,
                    'type'    => AdminLog::TYPE_DEL_PURVIEWGROUP,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '删除权限组:'.$return['m'].'[pvg_name='.$model->pvg_name.']'
                ];
                AdminLogService::addLog($logData);
            }
            return $return;
        }
        return $this->goHome();
    }
}
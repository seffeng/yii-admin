<?php

namespace zxf\web\admin\controllers;

use Yii;
use zxf\web\admin\components\Controller;
use zxf\models\services\AdminService;
use zxf\models\entities\Admin;
use zxf\models\services\FunctionService;
use zxf\models\entities\AdminLog;
use zxf\models\services\AdminLogService;
use zxf\models\services\AdminGroupService;
use zxf\models\entities\AdminInfo;
use yii\helpers\ArrayHelper;
use zxf\models\services\PurviewService;
use zxf\models\services\PurviewGroupService;
use zxf\models\services\ConstService;
use yii\helpers\Json;

class AdminController extends Controller {

    /**
     * 首页
     * @author ZhangXueFeng
     * @date   2016年11月7日
     * @return array
     */
    public function actionIndex() {
        $request = Yii::$app->request;
        $page    = $request->get('page', $request->post('page', 1));
        $formModel = new Admin(['scenario' => ConstService::SCENARIO_SEARCH]);
        $formModel->load($request->get());
        $dataProvider = AdminService::getList($formModel, $page);
        $params = [
            'formModel'    => $formModel,
            'statusText'   => ['' => '----'] + Admin::STATUS_TEXT,
            'dataProvider' => $dataProvider,
            'editPurview'  => PurviewService::checkPurview($this->action->controller->id, 'edit'),
            'delPurview'   => PurviewService::checkPurview($this->action->controller->id, 'del'),
            'breadcrumb'   => ['管理员列表  '. (PurviewService::checkPurview($this->action->controller->id, 'add') ? '<span class="text-blue text-sm" adm="add" role="button">添加管理员</span>' : ''), '后台管理', '管理员列表']
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
     * @date   2016年11月7日
     * @return string
     */
    public function actionAdd() {
        $model = new Admin(['scenario' => 'insert']);
        $adminInfo = new AdminInfo();
        $params = [
            'model'        => $model,
            'adminInfo'    => $adminInfo,
            'adminGroup'   => AdminGroupService::getDownList(),
            'purview'      => PurviewService::getValidPurview(TRUE),
            'purviewGroup' => PurviewGroupService::getValidPurviewGroup(TRUE),
            'breadcrumb'   => ['添加管理员  '. (PurviewService::checkPurview($this->action->controller->id, 'index') ? '<span class="text-blue text-sm" adm="list" role="button">管理员列表</span>' : ''), '后台管理', '添加管理员'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                if ($model->load($post) && $model->validate() && $adminInfo->load($post) && $adminInfo->validate()) {
                    if ($model->save()) {
                        $adminInfo->ad_id = $model->ad_id;
                        $adminInfo->save();
                        $return = ['r' => 1, 'm' => '添加管理员成功！'];
                    } else {
                        $return = ['r' => 1, 'm' => '添加管理员失败！'];
                    }
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model).' '.FunctionService::getErrorsForString($adminInfo)];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->ad_id,
                    'type'    => AdminLog::TYPE_ADD_ADMIN,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '添加管理员:'.$return['m'].'[ad_username='.$model->ad_username.']'
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
     * @date   2016年11月7日
     * @return array
     */
    public function actionEdit() {
        $id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
        if ($id < 1) {
            return ['r' => 0, 'm' => '参数错误！'];
        }
        $model = AdminService::getById($id);
        if (!$model) {
            return ['r' => 0, 'm' => '管理员不存在！'];
        }
        $model->setScenario(ConstService::SCENARIO_UPDATE);
        $adminInfo = ArrayHelper::getValue($model, 'adminInfo') ?: new AdminInfo();
        $params = [
            'model'        => $model,
            'adminInfo'    => $adminInfo,
            'adminGroup'   => AdminGroupService::getDownList(),
            'purview'      => PurviewService::getValidPurview(TRUE),
            'purviewGroup' => PurviewGroupService::getValidPurviewGroup(TRUE),
            'breadcrumb'   => ['修改管理员  '. (PurviewService::checkPurview($this->action->controller->id, 'index') ? '<span class="text-blue text-sm" adm="list" role="button">管理员列表</span>' : ''), '后台管理', '修改管理员'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $modelDiff = $adminInfoDiff = [];
                if ($model->load($post) && $adminInfo->load($post) && $model->validate() && $adminInfo->validate()) {
                    $modelDiff = FunctionService::modelDiff($model, ['ad_username', 'adg_id', 'ad_status']);
                    if ($model->save()) {
                        $adminInfo->ad_id = $model->ad_id;
                        $adminInfoDiff = FunctionService::modelDiff($adminInfo, ['ai_name', 'ai_phone', 'ai_email']);
                        $adminInfo->save();
                        $return = ['r' => 1, 'm' => '修改管理员成功！'];
                    } else {
                        $return = ['r' => 0, 'm' => '修改管理员失败！'];
                    }
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model).' '.FunctionService::getErrorsForString($adminInfo)];
                }
                $diff = ArrayHelper::merge($modelDiff, $adminInfoDiff);
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->ad_id,
                    'type'    => AdminLog::TYPE_EDIT_ADMIN,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '修改管理员:'.$return['m'].'[ad_username='.$model->ad_username.']',
                    'detail'  => $diff ? Json::encode($diff) : ''
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
            $model = AdminService::getById($id);
            $return = ['r' => 0, 'm' => '删除管理员失败'];
            if ($model) {
                $model->ad_isdel = Admin::DEL_YET;
                if ($model->save(FALSE)) {
                    $return = ['r' => 1, 'm' => '删除管理员成功'];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->ad_id,
                    'type'    => AdminLog::TYPE_DEL_ADMIN,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '删除管理员:'.$return['m'].'[ad_username='.$model->ad_username.']'
                ];
                AdminLogService::addLog($logData);
            }
            return $return;
        }
        return $this->goHome();
    }

    /**
     * 修改自己资料
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @return array
     */
    public function actionUpdateSelf() {
        $model = Yii::$app->getUser()->getIdentity();
        $model->setScenario(ConstService::SCENARIO_UPDATE);
        $adminInfo = ArrayHelper::getValue($model, 'adminInfo') ?: new AdminInfo();
        $params = [
            'model'        => $model,
            'adminInfo'    => $adminInfo,
            'adminGroup'   => AdminGroupService::getDownList(),
            'purview'      => PurviewService::getValidPurview(TRUE),
            'purviewGroup' => PurviewGroupService::getValidPurviewGroup(TRUE),
            'breadcrumb'   => ['修改资料 ', '后台管理', '修改资料'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $modelDiff = $adminInfoDiff = [];
                if ($model->load($post) && $adminInfo->load($post) && $model->validate() && $adminInfo->validate()) {
                    $modelDiff = FunctionService::modelDiff($model, ['ad_username', 'adg_id', 'ad_status']);
                    if ($model->save()) {
                        $adminInfo->ad_id = $model->ad_id;
                        $adminInfoDiff = FunctionService::modelDiff($adminInfo, ['ai_name', 'ai_phone', 'ai_email']);
                        $adminInfo->save();
                        $return = ['r' => 1, 'm' => '修改资料成功！'];
                    } else {
                        $return = ['r' => 0, 'm' => '修改资料失败！'];
                    }
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model).' '.FunctionService::getErrorsForString($adminInfo)];
                }
                $diff = ArrayHelper::merge($modelDiff, $adminInfoDiff);
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->ad_id,
                    'type'    => AdminLog::TYPE_EDIT_ADMIN,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '修改资料:'.$return['m'].'[ad_username='.$model->ad_username.']',
                    'detail'  => $diff ? Json::encode($diff) : ''
                ];
                AdminLogService::addLog($logData);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('update-self', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            return $return;
        }
        return $this->render('update-self', $params);
    }
}
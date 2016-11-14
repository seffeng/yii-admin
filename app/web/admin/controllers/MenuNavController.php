<?php

namespace zxf\web\admin\controllers;

use Yii;
use zxf\web\admin\components\Controller;
use zxf\models\services\MenuNavService;
use zxf\models\entities\MenuNav;
use zxf\models\services\FunctionService;
use zxf\models\entities\AdminLog;
use zxf\models\services\AdminLogService;
use zxf\models\services\PurviewService;
use zxf\models\services\ConstService;
use yii\helpers\Json;
/**
 * 导航菜单
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class MenuNavController extends Controller {

    /**
     * 获取导航菜单
     * @author ZhangXueFeng
     * @date   2016年11月3日
     */
    public function actionGetMenuNav() {
        return MenuNavService::getMenuNav();
    }

    /**
     * 列表
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return array
     */
    public function actionIndex() {
        $request = Yii::$app->request;
        $page    = $request->get('page', $request->post('page', 1));
        $formModel = new MenuNav(['scenario' => ConstService::SCENARIO_SEARCH]);
        $formModel->load($request->get());
        $dataProvider = MenuNavService::getList($formModel, $page);
        $params = [
            'formModel'    => $formModel,
            'statusText'   => ['' => '----'] + MenuNav::STATUS_TEXT,
            'typeText'     => ['' => '----'] + MenuNav::TYPE_TEXT,
            'dataProvider' => $dataProvider,
            'editPurview'  => PurviewService::checkPurview($this->action->controller->id, 'edit'),
            'delPurview'   => PurviewService::checkPurview($this->action->controller->id, 'del'),
            'breadcrumb'   => ['导航列表  '. (PurviewService::checkPurview($this->action->controller->id, 'add') ? '<span class="text-blue text-sm" adm="add" role="button">添加导航</span>' : '') .'<span class="text-red text-sm margin-left-20">导航添加、修改、删除时权限将同步操作！</span>', '后台管理', '导航列表']
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
     * @date   2016年11月4日
     * @return array
     */
    public function actionAdd() {
        $model = new MenuNav();
        $params = [
            'model' => $model,
            'menu_option' => MenuNavService::menuArrayToHtml(MenuNavService::getRelationMenu()),
            'mn_types'    => MenuNav::TYPE_TEXT,
            'mn_status'   => MenuNav::STATUS_TEXT,
            'breadcrumb'  => ['添加导航  '. (PurviewService::checkPurview($this->action->controller->id, 'index') ? '<span class="text-blue text-sm" adm="list" role="button">导航列表</span>' : ''), '后台管理', '添加导航'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '添加导航成功！'];
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model)];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->mn_id,
                    'type'    => AdminLog::TYPE_ADD_MENU,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '添加导航:'.$return['m'].'[mn_name='.$model->mn_name.']'
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
     * @date   2016年11月4日
     * @return array
     */
    public function actionEdit() {
        $id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
        if ($id < 1) {
            return ['r' => 0, 'm' => '参数错误！'];
        }
        $model = MenuNavService::getById($id);
        if (!$model) {
            return ['r' => 0, 'm' => '导航菜单不存在！'];
        }
        $params = [
            'model' => $model,
            'menu_option' => MenuNavService::menuArrayToHtml(MenuNavService::getRelationMenu(), 0, $model->mn_pid),
            'mn_types'    => MenuNav::TYPE_TEXT,
            'mn_status'   => MenuNav::STATUS_TEXT,
            'breadcrumb'  => ['修改导航  '. (PurviewService::checkPurview($this->action->controller->id, 'index') ? '<span class="text-blue text-sm" adm="list" role="button">导航列表</span>' : ''), '后台管理', '修改导航'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $modelDiff = [];
                if ($model->load($post) && $model->validate()) {
                    $modelDiff = FunctionService::modelDiff($model, ['mn_name', 'mn_url', 'mn_icon', 'mn_type', 'mn_pid', 'mn_status']);
                    if ($model->save()) {
                        $return = ['r' => 1, 'm' => '修改导航成功！'];
                    } else {
                        $return = ['r' => 1, 'm' => '修改导航失败！'];
                    }
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model)];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->mn_id,
                    'type'    => AdminLog::TYPE_EDIT_MENU,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '修改导航:'.$return['m'].'[mn_name='.$model->mn_name.']',
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
     * @date   2016年11月4日
     * @return array
     */
    public function actionDel() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
            if ($id < 1) {
                return ['r' => 0, 'm' => '参数错误！'];
            }
            $model = MenuNavService::getById($id);
            $return = ['r' => 0, 'm' => '删除导航失败'];
            if ($model) {
                $model->mn_isdel = MenuNav::DEL_YET;
                if ($model->save(FALSE)) {
                    $return = ['r' => 1, 'm' => '删除导航成功'];
                }
                $logData = [
                    'ad_id'   => Yii::$app->getUser()->getId(),
                    'key_id'  => $model->mn_id,
                    'type'    => AdminLog::TYPE_DEL_MENU,
                    'result'  => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD,
                    'content' => '删除导航:'.$return['m'].'[mn_name='.$model->mn_name.']'
                ];
                AdminLogService::addLog($logData);
            }
            return $return;
        }
        return $this->goHome();
    }
}
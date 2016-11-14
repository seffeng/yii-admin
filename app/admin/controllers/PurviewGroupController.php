<?php
/**
 * 权限组控制器
 */

namespace appdir\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use appdir\admin\components\AdminWebController;
use appdir\admin\models\PurviewGroup;
use appdir\admin\models\AdminLog;
use appdir\admin\models\Purview;

class PurviewGroupController extends AdminWebController {

    /**
     * 权限组列表
     * @author ZhangXueFeng
     * @date   2015年12月29日
     * @return mixed
     */
    public function actionIndex() {
        $page_size = Yii::$app->request->get('per-page', 10);
        $query = PurviewGroup::find()->where(['pvg_isdel' => PurviewGroup::DEL_NOT]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $page_size,
            ],
            'sort' => [
                'attributes' => ['pvg_id', 'pvg_lasttime'],
                'defaultOrder' => ['pvg_id' => SORT_DESC]
            ]
        ]);
        $params = [
            'dataProvider' => $dataProvider,
            'breadcrumb' => ['权限组列表  <span class="text-blue text-sm" adm="add" role="button">添加权限组</span>', '后台管理', '权限组列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            die(json_encode($return));
        }
        return $this->render('index', $params);
    }

    /**
     * 权限组添加
     * @author ZhangXueFeng
     * @date   2015年12月29日
     * @return mixed
     */
    public function actionAdd() {
        $params = [
                'pvg_status'  => PurviewGroup::$statusText,
                'purview'     => Purview::getValidPurview(TRUE),
                'breadcrumb' => ['添加权限组  <span class="text-blue text-sm" adm="list" role="button">权限组列表</span>', '后台管理', '添加权限组'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $model = new PurviewGroup();
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '添加权限组成功'];
                } else {
                    $errors = $model->getFirstErrors();
                    $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                        if (!$errors) return '';
                        foreach ($errors as $val) {
                            return $val;
                        }
                    })];
                }
                $log_data = ['content' => '添加权限组:'.$return['m'].'[pvg_name='.$model->pvg_name.']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('add', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('add', $params);
    }

    /**
     * 权限组修改
     * @author ZhangXueFeng
     * @date   2015年12月29日
     * @return mixed
     */
    public function actionEdit() {
        $id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
        if ($id < 1) {
            $return = ['r' => 0, 'm' => 'ID 错误'];
            die(json_encode($return));
        }
        $model = PurviewGroup::find()->where(['pvg_id' => $id, 'pvg_isdel' => PurviewGroup::DEL_NOT])->one();
        if (!$model) {
            $return = ['r' => 0, 'm' => '权限组已删除或不存在'];
            die(json_encode($return));
        }
        $params = [
                'model' => $model,
                'pvg_status'  => PurviewGroup::$statusText,
                'purview'     => Purview::getValidPurview(TRUE),
                'breadcrumb' => ['修改权限组  <span class="text-blue text-sm" adm="list" role="button">权限组列表</span>', '后台管理', '修改权限组']
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '编辑权限组成功'];
                } else {
                    $errors = $model->getFirstErrors();
                    $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                        if (!$errors) return '';
                        foreach ($errors as $val) {
                            return $val;
                        }
                    })];
                }
                $pvg_name = $model->pvg_name;
                $log_data = ['content' => '编辑权限组:'.$return['m'].'[pvg_id='.$id.($pvg_name != '' ? (',pvg_name='.$pvg_name) : '').']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('edit', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('edit', $params);
    }

    /**
     * 权限组删除
     * @author ZhangXueFeng
     * @date   2015年12月29日
     * @return mixed
     */
    public function actionDel() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id', 0);
            $return = ['r' => 0, 'd' => [], 'm' => '参数错误'];
            if ($id > 0) {
                $pvg_name = '';
                $model = PurviewGroup::find()->where(['pvg_id' => $id, 'pvg_isdel' => PurviewGroup::DEL_NOT])->one();
                if ($model) {
                    $pvg_name = $model->pvg_name;
                    $model->pvg_isdel = PurviewGroup::DEL_YET;
                    if ($model->save()) {
                        $return['r'] = 1;
                        $return['m'] = '删除成功';
                    } else {
                        $return['m'] = '删除失败';
                    }
                } else {
                    $return['m'] = '该权限组已删除或或不存在';
                }
                $log_data = ['content' => '删除权限组:'.$return['m'].'[pvg_id='.$id.($pvg_name != '' ? (',pvg_name='.$pvg_name) : '').']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            }
            die(json_encode($return));
        }
        return $this->redirect(Yii::$app->params['admin_home']);
    }
}
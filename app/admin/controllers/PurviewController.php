<?php
/**
 * 权限控制器
 */

namespace appdir\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use appdir\admin\components\AdminWebController;
use appdir\admin\models\Purview;
use appdir\admin\models\AdminLog;

class PurviewController extends AdminWebController {

    /**
     * 权限列表
     * @author ZhangXueFeng
     * @date   2015年12月29日
     * @return mixed
     */
    public function actionIndex() {
        $page_size = Yii::$app->request->get('per-page', 10);
        $query = Purview::find()->where(['pv_isdel' => Purview::DEL_NOT]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $page_size,
            ],
            'sort' => [
                'attributes' => ['pv_id', 'pv_status', 'pv_lasttime'],
                'defaultOrder' => ['pv_id' => SORT_DESC]
            ]
        ]);
        $params = [
            'dataProvider' => $dataProvider,
            'breadcrumb' => ['权限列表  <span class="text-blue text-sm" adm="add" role="button">添加权限</span>', '后台管理', '权限列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            die(json_encode($return));
        }
        return $this->render('index', $params);
    }

    /**
     * 权限添加
     * @author ZhangXueFeng
     * @date   2015年12月29日
     * @return mixed
     */
    public function actionAdd() {
        $params = [
                'pv_status'  => Purview::$statusText,
                'breadcrumb' => ['添加权限  <span class="text-blue text-sm" adm="list" role="button">权限列表</span>', '后台管理', '添加权限'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $model = new Purview();
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '添加权限成功'];
                } else {
                    $errors = $model->getFirstErrors();
                    $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                        if (!$errors) return '';
                        foreach ($errors as $val) {
                            return $val;
                        }
                    })];
                }
                $log_data = ['content' => '添加权限:'.$return['m'].'[pv_name='.$model->pv_name.']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('add', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('add', $params);
    }

    /**
     * 权限修改
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
        $model = Purview::find()->where(['pv_id' => $id, 'pv_isdel' => Purview::DEL_NOT])->one();
        if (!$model) {
            $return = ['r' => 0, 'm' => '权限已删除或不存在'];
            die(json_encode($return));
        }
        $params = [
                'model' => $model,
                'pv_status'  => Purview::$statusText,
                'breadcrumb' => ['修改权限  <span class="text-blue text-sm" adm="list" role="button">权限列表</span>', '后台管理', '修改权限']
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '编辑权限成功'];
                } else {
                    $errors = $model->getFirstErrors();
                    $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                        if (!$errors) return '';
                        foreach ($errors as $val) {
                            return $val;
                        }
                    })];
                }
                $pv_name = $model->pv_name;
                $log_data = ['content' => '编辑权限:'.$return['m'].'[pv_id='.$id.($pv_name != '' ? (',pv_name='.$pv_name) : '').']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('edit', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('edit', $params);
    }

    /**
     * 权限删除
     * @author ZhangXueFeng
     * @date   2015年12月29日
     * @return mixed
     */
    public function actionDel() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id', 0);
            $return = ['r' => 0, 'd' => [], 'm' => '参数错误'];
            if ($id > 0) {
                $pv_name = '';
                $model = Purview::find()->where(['pv_id' => $id, 'pv_isdel' => Purview::DEL_NOT])->one();
                if ($model) {
                    $pv_name = $model->pv_name;
                    $model->pv_isdel = Purview::DEL_YET;
                    if ($model->save()) {
                        $return['r'] = 1;
                        $return['m'] = '删除成功';
                    } else {
                        $return['m'] = '删除失败';
                    }
                } else {
                    $return['m'] = '该权限已删除或或不存在';
                }
                $log_data = ['content' => '删除权限:'.$return['m'].'[pv_id='.$id.($pv_name != '' ? (',pv_name='.$pv_name) : '').']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            }
            die(json_encode($return));
        }
        return $this->redirect(Yii::$app->params['admin_home']);
    }
}
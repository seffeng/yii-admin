<?php
/**
 * 管理员组控制器
 */

namespace appdir\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use appdir\admin\components\AdminWebController;
use appdir\admin\models\AdminGroup;
use appdir\admin\models\AdminLog;
use appdir\admin\models\Purview;
use appdir\admin\models\PurviewGroup;

class AdminGroupController extends AdminWebController {

    /**
     * 管理员组列表
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @return mixed
     */
    public function actionIndex() {
        $page_size = Yii::$app->request->get('per-page', 10);
        $query = AdminGroup::find()->where(['adg_isdel' => AdminGroup::DEL_NOT]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $page_size,
            ],
            'sort' => [
                'attributes' => ['adg_id', 'adg_lasttime'],
                'defaultOrder' => ['adg_id' => SORT_DESC]
            ]
        ]);
        $params = [
            'dataProvider' => $dataProvider,
            'breadcrumb' => ['管理员组列表  <span class="text-blue text-sm" adm="add" role="button">添加管理员组</span>', '后台管理', '管理员组列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            die(json_encode($return));
        }
        return $this->render('index', $params);
    }

    /**
     * 管理员组添加
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @return mixed
     */
    public function actionAdd() {
        $params = [
                'adg_status'  => AdminGroup::$statusText,
                'purview'     => Purview::getValidPurview(TRUE),
                'purviewGroup' => PurviewGroup::getValidPurviewGroup(TRUE),
                'breadcrumb' => ['添加管理员组  <span class="text-blue text-sm" adm="list" role="button">管理员组列表</span>', '后台管理', '添加管理员组'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $model = new AdminGroup();
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '添加管理员组成功'];
                } else {
                    $errors = $model->getFirstErrors();
                    $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                        if (!$errors) return '';
                        foreach ($errors as $val) {
                            return $val;
                        }
                    })];
                }
                $log_data = ['content' => '添加管理员组:'.$return['m'].'[adg_name='.$model->adg_name.']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('add', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('add', $params);
    }

    /**
     * 管理员组修改
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @return mixed
     */
    public function actionEdit() {
        $adg_id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
        if ($adg_id < 1) {
            $return = ['r' => 0, 'm' => 'ID 错误'];
            die(json_encode($return));
        }
        $model = AdminGroup::find()->where(['adg_id' => $adg_id, 'adg_isdel' => AdminGroup::DEL_NOT])->one();
        if (!$model) {
            $return = ['r' => 0, 'm' => '管理员组已删除或不存在'];
            die(json_encode($return));
        }
        $params = [
                'model' => $model,
                'adg_status'  => AdminGroup::$statusText,
                'purview'     => Purview::getValidPurview(TRUE),
                'purviewGroup' => PurviewGroup::getValidPurviewGroup(TRUE),
                'breadcrumb' => ['修改管理员组  <span class="text-blue text-sm" adm="list" role="button">管理员组列表</span>', '后台管理', '修改管理员组']
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '编辑管理员组成功'];
                } else {
                    $errors = $model->getFirstErrors();
                    $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                        if (!$errors) return '';
                        foreach ($errors as $val) {
                            return $val;
                        }
                    })];
                }
                $name = $model->adg_name;
                $log_data = ['content' => '编辑管理员组:'.$return['m'].'[adg_id='.$adg_id.($name != '' ? (',adg_name='.$name) : '').']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('edit', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('edit', $params);
    }

    /**
     * 管理员组删除
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @return mixed
     */
    public function actionDel() {
        if (Yii::$app->request->isAjax) {
            $adg_id = Yii::$app->request->post('id', 0);
            $return = ['r' => 0, 'd' => [], 'm' => '参数错误'];
            if ($adg_id > 0) {
                $name = '';
                $model = AdminGroup::find()->where(['adg_id' => $adg_id, 'adg_isdel' => AdminGroup::DEL_NOT])->one();
                if ($model) {
                    $name = $model->adg_name;
                    $model->adg_isdel = AdminGroup::DEL_YET;
                    if ($model->save()) {
                        $return['r'] = 1;
                        $return['m'] = '删除成功';
                    } else {
                        $return['m'] = '删除失败';
                    }
                } else {
                    $return['m'] = '该管理员组已删除或或不存在';
                }
                $log_data = ['content' => '删除管理员组:'.$return['m'].'[adg_id='.$adg_id.($name != '' ? (',adg_name='.$name) : '').']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            }
            die(json_encode($return));
        }
        return $this->redirect(Yii::$app->params['admin_home']);
    }
}
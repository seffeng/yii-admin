<?php
/**
 * 管理员控制器
 */

namespace appdir\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use appdir\admin\components\AdminWebController;
use appdir\admin\models\Admin;
use appdir\admin\models\AdminLog;
use appdir\admin\models\AdminGroup;
use appdir\admin\models\Purview;
use appdir\admin\models\PurviewGroup;
use appdir\admin\models\AdminInfo;

class AdminController extends AdminWebController {

    /**
     * 管理员列表
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @return mixed
     */
    public function actionIndex() {
        $page_size = Yii::$app->request->get('per-page', 10);
        $query = Admin::find()->is_del();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $page_size,
            ],
            'sort' => [
                'attributes' => ['ad_id', 'ad_addtime'],
                'defaultOrder' => ['ad_id' => SORT_DESC]
            ]
        ]);
        $params = [
            'dataProvider' => $dataProvider,
            'breadcrumb' => ['管理员列表  <span class="text-blue text-sm" adm="add" role="button">添加管理员</span>', '后台管理', '管理员列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            die(json_encode($return));
        }
        return $this->render('index', $params);
    }

    /**
     * 管理员添加
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @return mixed
     */
    public function actionAdd() {
        $result = AdminGroup::find()->where(['adg_isdel' => AdminGroup::DEL_NOT])->all();
        $admin_group = ['无'];
        if ($result) foreach ($result as $val) {
            $admin_group[$val->adg_id] = $val->adg_name;
        }
        $params = [
            'ad_status'   => Admin::$statusText,
            'admin_group' => $admin_group,
            'purview'     => Purview::getValidPurview(TRUE),
            'purviewGroup' => PurviewGroup::getValidPurviewGroup(TRUE),
            'breadcrumb'   => ['添加管理员  <span class="text-blue text-sm" adm="list" role="button">管理员列表</span>', '后台管理', '添加管理员'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $model = new Admin(['scenario' => 'insert']);
                if ($model->load($post) && $model->save()) {
                    $admin_info = new AdminInfo();
                    if ($admin_info->load($post)) {
                        $admin_info->ad_id = $model->ad_id;
                        $admin_info->save();
                    }
                    $return = ['r' => 1, 'm' => '添加管理员成功'];
                } else {
                    $errors = $model->getFirstErrors();
                    $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                        if (!$errors) return '';
                        foreach ($errors as $val) {
                            return $val;
                        }
                    })];
                }
                $log_data = ['content' => '添加管理员:'.$return['m'].'[ad_username='.$model->ad_username.']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('add', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('add', $params);
    }

    /**
     * 管理员修改
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @return mixed
     */
    public function actionEdit() {
        $ad_id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
        if ($ad_id < 1) {
            $return = ['r' => 0, 'm' => 'ID 错误'];
            die(json_encode($return));
        }
        $model = Admin::find()->by_ids($ad_id)->is_del()->one();
        if (!$model) {
            $return = ['r' => 0, 'm' => '管理员已删除或不存在'];
            die(json_encode($return));
        }
        $result = AdminGroup::find()->where(['adg_isdel' => AdminGroup::DEL_NOT])->all();
        $admin_group = ['无'];
        if ($result) foreach ($result as $val) {
            $admin_group[$val->adg_id] = $val->adg_name;
        }
        $params = [
            'model' => $model,
            'ad_status'  => Admin::$statusText,
            'admin_group' => $admin_group,
            'admin_info' => AdminInfo::findOne(['ad_id' => $model->ad_id]),
            'purview'     => Purview::getValidPurview(TRUE),
            'purviewGroup' => PurviewGroup::getValidPurviewGroup(TRUE),
            'breadcrumb' => ['修改管理员  <span class="text-blue text-sm" adm="list" role="button">管理员列表</span>', '后台管理', '修改管理员']
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                if ($model->load($post) && $model->save()) {
                    $admin_info = $params['admin_info'];
                    if (!$admin_info) {
                        $admin_info = new AdminInfo();
                    }
                    if ($admin_info->load($post)) {
                        $admin_info->ad_id = $model->ad_id;
                        $admin_info->save();
                    }
                    $return = ['r' => 1, 'm' => '编辑管理员成功'];
                } else {
                    $errors = $model->getFirstErrors();
                    $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                        if (!$errors) return '';
                        foreach ($errors as $val) {
                            return $val;
                        }
                    })];
                }
                $username = $model->ad_username;
                $log_data = ['content' => '编辑管理员:'.$return['m'].'[ad_id='.$ad_id.($username != '' ? (',ad_username='.$username) : '').']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('edit', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('edit', $params);
    }

    /**
     * 管理员删除
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @return mixed
     */
    public function actionDel() {
        if (Yii::$app->request->isAjax) {
            $ad_id = Yii::$app->request->post('id', 0);
            $return = ['r' => 0, 'd' => [], 'm' => '参数错误'];
            if ($ad_id > 0) {
                $username = '';
                $model = Admin::find()->by_ids($ad_id)->is_del()->one();
                if ($model) {
                    $username = $model->ad_username;
                    $model->ad_isdel = Admin::DEL_YET;
                    if ($model->save()) {
                        $return['r'] = 1;
                        $return['m'] = '删除成功';
                    } else {
                        $return['m'] = '删除失败';
                    }
                } else {
                    $return['m'] = '该管理员已删除或或不存在';
                }
                $log_data = ['content' => '删除管理员:'.$return['m'].'[ad_id='.$ad_id.($username != '' ? (',ad_username='.$username) : '').']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            }
            die(json_encode($return));
        }
        return $this->redirect(Yii::$app->params['admin_home']);
    }
}
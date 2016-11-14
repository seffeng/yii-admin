<?php
/**
 * 导航控制器
 */

namespace appdir\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use appdir\admin\components\AdminWebController;
use appdir\admin\models\Menu;
use appdir\admin\models\AdminLog;

class MenuController extends AdminWebController {

    /**
     * 获取导航菜单
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @return mixed
     */
    public function actionGetMenu() {
        $menu = Menu::get_menu();
        die(json_encode($menu));
    }

    /**
     * 导航列表
     * @date   2015-12-09
     * @author ZhangXueFeng
     * @return mixed
     */
    public function actionIndex() {
        $page_size = Yii::$app->request->get('per-page', 10);
        $query = Menu::find()->is_del();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $page_size,
            ],
            'sort' => [
                'attributes' => ['mn_id', 'mn_lasttime'],
                'defaultOrder' => ['mn_id' => SORT_DESC]
            ]
        ]);
        $params = [
            'dataProvider' => $dataProvider,
            'breadcrumb' => ['导航列表  <span class="text-blue text-sm" adm="add" role="button">添加导航</span>', '后台管理', '导航列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            die(json_encode($return));
        }
        return $this->render('index', $params);
    }

    /**
     * 添加导航
     * @date   2015-12-11
     * @author ZhangXueFeng
     * @return [type]
     */
    public function actionAdd() {
        $params = [
            'menu_option' => Menu::menu_arraytohtml(Menu::get_relation_menu()),
            'mn_types'   => Menu::$typeText,
            'mn_status'  => Menu::$statusText,
            'breadcrumb' => ['添加导航  <span class="text-blue text-sm" adm="list" role="button">导航列表</span>', '后台管理', '添加导航'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $model = new Menu();
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '添加导航成功'];
                } else {
                    $errors = $model->getFirstErrors();
                    $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                        if (!$errors) return '';
                        foreach ($errors as $val) {
                            return $val;
                        }
                    })];
                }
                $log_data = ['content' => '添加导航:'.$return['m'].'[mn_name='.$model->mn_name.']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('add', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('add', $params);
    }

    /**
     * 修改导航
     * @date   2015-12-11
     * @author ZhangXueFeng
     * @return [type]
     */
    public function actionEdit() {
        $mn_id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
        if ($mn_id < 1) {
            $return = ['r' => 0, 'm' => 'ID 错误'];
            die(json_encode($return));
        }
        $model = Menu::get_by_id($mn_id);
        if (!$model) {
            $return = ['r' => 0, 'm' => '导航已删除或不存在'];
            die(json_encode($return));
        }
        $params = [
            'model' => $model,
            'menu_option' => Menu::menu_arraytohtml(Menu::get_relation_menu(), 0, $model->mn_pid),
            'mn_types'   => Menu::$typeText,
            'mn_status'  => Menu::$statusText,
            'breadcrumb' => ['修改导航  <span class="text-blue text-sm" adm="list" role="button">导航列表</span>', '后台管理', '修改导航']
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                if ($model->load($post) && $model->save()) {
                    $return = ['r' => 1, 'm' => '编辑导航成功'];
                } else {
                    $errors = $model->getFirstErrors();
                    $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                        if (!$errors) return '';
                        foreach ($errors as $val) {
                            return $val;
                        }
                    })];
                }
                $mn_name = $model->mn_name;
                $log_data = ['content' => '编辑导航:'.$return['m'].'[mn_id='.($mn_name != '' ? (',mn_name='.$mn_name) : '').']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('edit', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('edit', $params);
    }

    /**
     * 删除导航
     * @date   2015-12-11
     * @author ZhangXueFeng
     * @return [type]
     */
    public function actionDel() {
        if (Yii::$app->request->isAjax) {
            $mn_id = Yii::$app->request->post('id', 0);
            $return = ['r' => 0, 'd' => [], 'm' => '参数错误'];
            if ($mn_id > 0) {
                $model = Menu::find()->by_ids($mn_id)->is_del()->one();
                $mn_name = '';
                if ($model) {
                    $mn_name = $model->mn_name;
                    $model->mn_isdel = Menu::DEL_YET;
                    if ($model->save()) {
                        $return['r'] = 1;
                        $return['m'] = '删除成功';
                    } else {
                        $return['m'] = '删除失败';
                    }
                } else {
                    $return['m'] = '该导航已删除或或不存在';
                }
                $log_data = ['content' => '删除导航:'.$return['m'].'[mn_id='.$mn_id.($mn_name != '' ? (',mn_name='.$mn_name) : '').']', 'ad_id' => Yii::$app->user->identity->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
                AdminLog::addLog($log_data);
            }
            die(json_encode($return));
        }
        return $this->redirect(Yii::$app->params['admin_home']);
    }
}